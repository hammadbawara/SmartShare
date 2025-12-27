<?php
/**
 * Bills API Endpoint
 * GET    /backend/api/finances/bills.php - Get all bills
 * POST   /backend/api/finances/bills.php - Create new bill
 * PUT    /backend/api/finances/bills.php?id={id} - Update bill
 * DELETE /backend/api/finances/bills.php?id={id} - Delete bill
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/validation.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Require authentication for all finance operations
    $user = requireRole(['admin', 'roommate']);
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            handleGetBills($db, $user);
            break;
            
        case 'POST':
            handleCreateBill($db, $user);
            break;
            
        case 'PUT':
            handleUpdateBill($db, $user);
            break;
            
        case 'DELETE':
            handleDeleteBill($db, $user);
            break;
            
        default:
            sendMethodNotAllowed(['GET', 'POST', 'PUT', 'DELETE']);
    }
    
} catch (Exception $e) {
    if (APP_DEBUG) {
        sendServerError($e->getMessage());
    } else {
        sendServerError('An error occurred processing your request');
    }
}

/**
 * Get all bills with splits
 */
function handleGetBills($db, $user) {
    $status = $_GET['status'] ?? 'all'; // all, paid, unpaid
    
    $sql = "
        SELECT 
            b.*,
            u.full_name as created_by_name,
            p.full_name as paid_by_name,
            (SELECT COUNT(*) FROM bill_splits WHERE bill_id = b.id) as split_count
        FROM bills b
        LEFT JOIN users u ON b.created_by = u.id
        LEFT JOIN users p ON b.paid_by = p.id
    ";
    
    if ($status === 'paid') {
        $sql .= " WHERE b.is_paid = TRUE";
    } elseif ($status === 'unpaid') {
        $sql .= " WHERE b.is_paid = FALSE";
    }
    
    $sql .= " ORDER BY b.due_date DESC, b.created_at DESC";
    
    $stmt = $db->query($sql);
    $bills = $stmt->fetchAll();
    
    // Get splits for each bill
    foreach ($bills as &$bill) {
        $stmt = $db->prepare("
            SELECT 
                bs.*,
                u.full_name as user_name,
                u.avatar
            FROM bill_splits bs
            JOIN users u ON bs.user_id = u.id
            WHERE bs.bill_id = ?
        ");
        $stmt->execute([$bill['id']]);
        $bill['splits'] = $stmt->fetchAll();
    }
    
    sendSuccess(['bills' => $bills]);
}

/**
 * Create new bill
 */
function handleCreateBill($db, $user) {
    // Only admin can create bills
    if ($user['role'] !== 'admin') {
        sendForbidden('Only administrators can create bills');
    }
    
    $data = getRequestData();
    
    // Validate required fields
    $errors = validateRequired($data, ['title', 'amount', 'category', 'due_date']);
    if (!empty($errors)) {
        sendValidationError($errors);
    }
    
    // Validate amount
    if (!validateNumber($data['amount'], 0)) {
        sendValidationError(['amount' => 'Amount must be a positive number']);
    }
    
    // Validate date
    if (!validateDate($data['due_date'])) {
        sendValidationError(['due_date' => 'Invalid date format']);
    }
    
    // Validate category
    $validCategories = ['rent', 'electricity', 'internet', 'gas', 'water', 'maintenance', 'other'];
    if (!validateEnum($data['category'], $validCategories)) {
        sendValidationError(['category' => 'Invalid category']);
    }
    
    // Begin transaction
    $db->beginTransaction();
    
    try {
        // Insert bill
        $stmt = $db->prepare("
            INSERT INTO bills (title, amount, category, due_date, description, created_by)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            sanitizeString($data['title']),
            $data['amount'],
            $data['category'],
            $data['due_date'],
            sanitizeString($data['description'] ?? ''),
            $user['id']
        ]);
        
        $billId = $db->lastInsertId();
        
        // Auto-split bill among active roommates and admin
        $stmt = $db->prepare("
            SELECT id FROM users 
            WHERE role IN ('admin', 'roommate') AND is_active = TRUE
        ");
        $stmt->execute();
        $roommates = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($roommates) > 0) {
            $splitAmount = $data['amount'] / count($roommates);
            
            $stmt = $db->prepare("
                INSERT INTO bill_splits (bill_id, user_id, amount)
                VALUES (?, ?, ?)
            ");
            
            foreach ($roommates as $roommateId) {
                $stmt->execute([$billId, $roommateId, $splitAmount]);
            }
        }
        
        $db->commit();
        
        // Log activity
        logActivity('create_bill', 'bill', $billId, "Created bill: {$data['title']}");
        
        // Get the created bill with splits
        $stmt = $db->prepare("
            SELECT b.*, u.full_name as created_by_name
            FROM bills b
            JOIN users u ON b.created_by = u.id
            WHERE b.id = ?
        ");
        $stmt->execute([$billId]);
        $bill = $stmt->fetch();
        
        $stmt = $db->prepare("
            SELECT bs.*, u.full_name as user_name, u.avatar
            FROM bill_splits bs
            JOIN users u ON bs.user_id = u.id
            WHERE bs.bill_id = ?
        ");
        $stmt->execute([$billId]);
        $bill['splits'] = $stmt->fetchAll();
        
        sendSuccess(['bill' => $bill], 'Bill created successfully', 201);
        
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

/**
 * Update bill (mark as paid/unpaid)
 */
function handleUpdateBill($db, $user) {
    if (!isset($_GET['id'])) {
        sendError('Bill ID is required');
    }
    
    $billId = (int)$_GET['id'];
    $data = getRequestData();
    
    // Get bill
    $stmt = $db->prepare("SELECT * FROM bills WHERE id = ?");
    $stmt->execute([$billId]);
    $bill = $stmt->fetch();
    
    if (!$bill) {
        sendNotFound('Bill not found');
    }
    
    // Update is_paid status
    if (isset($data['is_paid'])) {
        $isPaid = (bool)$data['is_paid'];
        
        $stmt = $db->prepare("
            UPDATE bills 
            SET is_paid = ?, paid_by = ?, paid_date = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $isPaid,
            $isPaid ? $user['id'] : null,
            $isPaid ? date('Y-m-d') : null,
            $billId
        ]);
        
        // Update all splits
        $stmt = $db->prepare("
            UPDATE bill_splits 
            SET is_paid = ?, paid_date = ?
            WHERE bill_id = ?
        ");
        
        $stmt->execute([
            $isPaid,
            $isPaid ? date('Y-m-d') : null,
            $billId
        ]);
        
        logActivity(
            $isPaid ? 'mark_paid' : 'mark_unpaid',
            'bill',
            $billId,
            "Marked bill as " . ($isPaid ? 'paid' : 'unpaid')
        );
        
        sendSuccess(null, 'Bill updated successfully');
    }
    
    sendError('No valid updates provided');
}

/**
 * Delete bill
 */
function handleDeleteBill($db, $user) {
    // Only admin can delete bills
    if ($user['role'] !== 'admin') {
        sendForbidden('Only administrators can delete bills');
    }
    
    if (!isset($_GET['id'])) {
        sendError('Bill ID is required');
    }
    
    $billId = (int)$_GET['id'];
    
    // Check if bill exists
    $stmt = $db->prepare("SELECT title FROM bills WHERE id = ?");
    $stmt->execute([$billId]);
    $bill = $stmt->fetch();
    
    if (!$bill) {
        sendNotFound('Bill not found');
    }
    
    // Delete bill (splits will be deleted by CASCADE)
    $stmt = $db->prepare("DELETE FROM bills WHERE id = ?");
    $stmt->execute([$billId]);
    
    logActivity('delete_bill', 'bill', $billId, "Deleted bill: {$bill['title']}");
    
    sendSuccess(null, 'Bill deleted successfully');
}
