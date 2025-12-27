<?php
/**
 * Financial Summary API Endpoint
 * GET /backend/api/finances/summary.php
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendMethodNotAllowed(['GET']);
}

try {
    // Require authentication
    $user = requireRole(['admin', 'roommate']);
    $db = getDB();
    
    // Get date range (default: current month)
    $month = $_GET['month'] ?? date('Y-m');
    $startDate = $month . '-01';
    $endDate = date('Y-m-t', strtotime($startDate));
    
    // Total expenses for the period
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(amount), 0) as total
        FROM bills
        WHERE due_date BETWEEN ? AND ?
    ");
    $stmt->execute([$startDate, $endDate]);
    $totalExpenses = $stmt->fetch()['total'];
    
    // Total paid
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(amount), 0) as total
        FROM bills
        WHERE due_date BETWEEN ? AND ? AND is_paid = TRUE
    ");
    $stmt->execute([$startDate, $endDate]);
    $totalPaid = $stmt->fetch()['total'];
    
    // Total unpaid
    $totalUnpaid = $totalExpenses - $totalPaid;
    
    // User's share (only for roommates and admin)
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(amount), 0) as total
        FROM bill_splits
        WHERE user_id = ? 
        AND bill_id IN (
            SELECT id FROM bills WHERE due_date BETWEEN ? AND ?
        )
    ");
    $stmt->execute([$user['id'], $startDate, $endDate]);
    $userShare = $stmt->fetch()['total'];
    
    // User's paid amount
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(amount), 0) as total
        FROM bill_splits
        WHERE user_id = ? AND is_paid = TRUE
        AND bill_id IN (
            SELECT id FROM bills WHERE due_date BETWEEN ? AND ?
        )
    ");
    $stmt->execute([$user['id'], $startDate, $endDate]);
    $userPaid = $stmt->fetch()['total'];
    
    // User's outstanding
    $userOutstanding = $userShare - $userPaid;
    
    // Expenses by category
    $stmt = $db->prepare("
        SELECT category, COALESCE(SUM(amount), 0) as total
        FROM bills
        WHERE due_date BETWEEN ? AND ?
        GROUP BY category
    ");
    $stmt->execute([$startDate, $endDate]);
    $expensesByCategory = $stmt->fetchAll();
    
    // Recent transactions (last 10)
    $stmt = $db->prepare("
        SELECT 
            b.id,
            b.title,
            b.amount,
            b.category,
            b.due_date,
            b.is_paid,
            b.paid_date,
            u.full_name as paid_by_name
        FROM bills b
        LEFT JOIN users u ON b.paid_by = u.id
        WHERE b.due_date BETWEEN ? AND ?
        ORDER BY b.created_at DESC
        LIMIT 10
    ");
    $stmt->execute([$startDate, $endDate]);
    $recentTransactions = $stmt->fetchAll();
    
    // Payment statistics per user
    $stmt = $db->prepare("
        SELECT 
            u.id,
            u.full_name,
            u.avatar,
            COALESCE(SUM(bs.amount), 0) as total_share,
            COALESCE(SUM(CASE WHEN bs.is_paid THEN bs.amount ELSE 0 END), 0) as paid,
            COALESCE(SUM(CASE WHEN NOT bs.is_paid THEN bs.amount ELSE 0 END), 0) as outstanding
        FROM users u
        LEFT JOIN bill_splits bs ON u.id = bs.user_id
        LEFT JOIN bills b ON bs.bill_id = b.id
        WHERE u.role IN ('admin', 'roommate') AND u.is_active = TRUE
        AND (b.due_date BETWEEN ? AND ? OR b.due_date IS NULL)
        GROUP BY u.id, u.full_name, u.avatar
    ");
    $stmt->execute([$startDate, $endDate]);
    $userStats = $stmt->fetchAll();
    
    sendSuccess([
        'period' => [
            'month' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate
        ],
        'totals' => [
            'expenses' => floatval($totalExpenses),
            'paid' => floatval($totalPaid),
            'unpaid' => floatval($totalUnpaid)
        ],
        'userSummary' => [
            'share' => floatval($userShare),
            'paid' => floatval($userPaid),
            'outstanding' => floatval($userOutstanding)
        ],
        'expensesByCategory' => $expensesByCategory,
        'recentTransactions' => $recentTransactions,
        'userStats' => $userStats
    ]);
    
} catch (Exception $e) {
    if (APP_DEBUG) {
        sendServerError($e->getMessage());
    } else {
        sendServerError('An error occurred fetching financial summary');
    }
}
