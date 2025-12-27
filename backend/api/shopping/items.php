<?php
/**
 * Shopping Items API Endpoint
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/validation.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $user = requireRole(['admin', 'roommate']);
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            handleGetItems($db, $user);
            break;
        case 'POST':
            handleCreateItem($db, $user);
            break;
        case 'PUT':
            handleUpdateItem($db, $user);
            break;
        case 'DELETE':
            handleDeleteItem($db, $user);
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

function handleGetItems($db, $user) {
    $status = $_GET['status'] ?? 'all';
    
    $sql = "
        SELECT 
            s.*,
            u.full_name as added_by_name,
            u.avatar as added_by_avatar,
            c.full_name as claimed_by_name,
            c.avatar as claimed_by_avatar
        FROM shopping_items s
        JOIN users u ON s.added_by = u.id
        LEFT JOIN users c ON s.claimed_by = c.id
    ";
    
    if ($status === 'purchased') {
        $sql .= " WHERE s.is_purchased = TRUE";
    } elseif ($status === 'pending') {
        $sql .= " WHERE s.is_purchased = FALSE";
    }
    
    $sql .= " ORDER BY s.is_purchased ASC, s.created_at DESC";
    
    $stmt = $db->query($sql);
    $items = $stmt->fetchAll();
    
    sendSuccess(['items' => $items]);
}

function handleCreateItem($db, $user) {
    $data = getRequestData();
    $errors = validateRequired($data, ['item_name']);
    
    if (!empty($errors)) {
        sendValidationError($errors);
    }
    
    $stmt = $db->prepare("
        INSERT INTO shopping_items (item_name, quantity, category, notes, added_by)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        sanitizeString($data['item_name']),
        sanitizeString($data['quantity'] ?? '1'),
        $data['category'] ?? 'groceries',
        sanitizeString($data['notes'] ?? ''),
        $user['id']
    ]);
    
    $itemId = $db->lastInsertId();
    logActivity('create_shopping_item', 'shopping_item', $itemId, "Added item: {$data['item_name']}");
    
    sendSuccess(['id' => $itemId], 'Item added successfully', 201);
}

function handleUpdateItem($db, $user) {
    if (!isset($_GET['id'])) {
        sendError('Item ID is required');
    }
    
    $itemId = (int)$_GET['id'];
    $data = getRequestData();
    
    $stmt = $db->prepare("SELECT * FROM shopping_items WHERE id = ?");
    $stmt->execute([$itemId]);
    $item = $stmt->fetch();
    
    if (!$item) {
        sendNotFound('Item not found');
    }
    
    if (isset($data['is_purchased'])) {
        $isPurchased = (bool)$data['is_purchased'];
        
        $stmt = $db->prepare("
            UPDATE shopping_items 
            SET is_purchased = ?, purchased_date = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $isPurchased,
            $isPurchased ? date('Y-m-d') : null,
            $itemId
        ]);
        
        logActivity(
            $isPurchased ? 'purchase_item' : 'unpurchase_item',
            'shopping_item',
            $itemId,
            "Marked item as " . ($isPurchased ? 'purchased' : 'not purchased')
        );
        
        sendSuccess(null, 'Item updated successfully');
    }
    
    if (isset($data['claimed_by'])) {
        $claimedBy = $data['claimed_by'] ? (int)$data['claimed_by'] : null;
        
        $stmt = $db->prepare("UPDATE shopping_items SET claimed_by = ? WHERE id = ?");
        $stmt->execute([$claimedBy, $itemId]);
        
        logActivity('claim_item', 'shopping_item', $itemId, "Claimed shopping item");
        
        sendSuccess(null, 'Item claimed successfully');
    }
    
    sendError('No valid updates provided');
}

function handleDeleteItem($db, $user) {
    if (!isset($_GET['id'])) {
        sendError('Item ID is required');
    }
    
    $itemId = (int)$_GET['id'];
    
    $stmt = $db->prepare("SELECT item_name FROM shopping_items WHERE id = ?");
    $stmt->execute([$itemId]);
    $item = $stmt->fetch();
    
    if (!$item) {
        sendNotFound('Item not found');
    }
    
    $stmt = $db->prepare("DELETE FROM shopping_items WHERE id = ?");
    $stmt->execute([$itemId]);
    
    logActivity('delete_shopping_item', 'shopping_item', $itemId, "Deleted item: {$item['item_name']}");
    
    sendSuccess(null, 'Item deleted successfully');
}
