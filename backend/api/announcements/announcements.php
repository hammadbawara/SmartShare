<?php
/**
 * Announcements API Endpoint
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/validation.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $user = requireAuth(); // All authenticated users
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            handleGetAnnouncements($db, $user);
            break;
        case 'POST':
            handleCreateAnnouncement($db, $user);
            break;
        case 'PUT':
            handleUpdateAnnouncement($db, $user);
            break;
        case 'DELETE':
            handleDeleteAnnouncement($db, $user);
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

function handleGetAnnouncements($db, $user) {
    $stmt = $db->query("
        SELECT 
            a.*,
            u.full_name as posted_by_name,
            u.avatar as posted_by_avatar
        FROM announcements a
        JOIN users u ON a.posted_by = u.id
        ORDER BY a.is_important DESC, a.created_at DESC
    ");
    
    $announcements = $stmt->fetchAll();
    
    // Get reactions for each announcement
    foreach ($announcements as &$announcement) {
        $stmt = $db->prepare("
            SELECT 
                ar.reaction,
                u.full_name as user_name,
                u.avatar
            FROM announcement_reactions ar
            JOIN users u ON ar.user_id = u.id
            WHERE ar.announcement_id = ?
        ");
        $stmt->execute([$announcement['id']]);
        $announcement['reactions'] = $stmt->fetchAll();
        
        // Count reactions by type
        $reactionCounts = [];
        foreach ($announcement['reactions'] as $r) {
            $reactionCounts[$r['reaction']] = ($reactionCounts[$r['reaction']] ?? 0) + 1;
        }
        $announcement['reaction_counts'] = $reactionCounts;
    }
    
    sendSuccess(['announcements' => $announcements]);
}

function handleCreateAnnouncement($db, $user) {
    // Only admin and roommates can create announcements
    if (!in_array($user['role'], ['admin', 'roommate', 'landlord'])) {
        sendForbidden('You do not have permission to create announcements');
    }
    
    $data = getRequestData();
    $errors = validateRequired($data, ['title', 'content']);
    
    if (!empty($errors)) {
        sendValidationError($errors);
    }
    
    $stmt = $db->prepare("
        INSERT INTO announcements (title, content, is_important, posted_by)
        VALUES (?, ?, ?, ?)
    ");
    
    $stmt->execute([
        sanitizeString($data['title']),
        sanitizeString($data['content']),
        (bool)($data['is_important'] ?? false),
        $user['id']
    ]);
    
    $announcementId = $db->lastInsertId();
    logActivity('create_announcement', 'announcement', $announcementId, "Posted: {$data['title']}");
    
    sendSuccess(['id' => $announcementId], 'Announcement created successfully', 201);
}

function handleUpdateAnnouncement($db, $user) {
    if (!isset($_GET['id'])) {
        sendError('Announcement ID is required');
    }
    
    $announcementId = (int)$_GET['id'];
    $data = getRequestData();
    
    $stmt = $db->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->execute([$announcementId]);
    $announcement = $stmt->fetch();
    
    if (!$announcement) {
        sendNotFound('Announcement not found');
    }
    
    // Only the creator or admin can edit
    if ($announcement['posted_by'] != $user['id'] && $user['role'] !== 'admin') {
        sendForbidden('You can only edit your own announcements');
    }
    
    $updates = [];
    $params = [];
    
    if (isset($data['title'])) {
        $updates[] = "title = ?";
        $params[] = sanitizeString($data['title']);
    }
    
    if (isset($data['content'])) {
        $updates[] = "content = ?";
        $params[] = sanitizeString($data['content']);
    }
    
    if (isset($data['is_important'])) {
        $updates[] = "is_important = ?";
        $params[] = (bool)$data['is_important'];
    }
    
    if (empty($updates)) {
        sendError('No valid updates provided');
    }
    
    $params[] = $announcementId;
    $sql = "UPDATE announcements SET " . implode(', ', $updates) . " WHERE id = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    logActivity('update_announcement', 'announcement', $announcementId, "Updated announcement");
    
    sendSuccess(null, 'Announcement updated successfully');
}

function handleDeleteAnnouncement($db, $user) {
    if (!isset($_GET['id'])) {
        sendError('Announcement ID is required');
    }
    
    $announcementId = (int)$_GET['id'];
    
    $stmt = $db->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->execute([$announcementId]);
    $announcement = $stmt->fetch();
    
    if (!$announcement) {
        sendNotFound('Announcement not found');
    }
    
    // Only the creator or admin can delete
    if ($announcement['posted_by'] != $user['id'] && $user['role'] !== 'admin') {
        sendForbidden('You can only delete your own announcements');
    }
    
    $stmt = $db->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->execute([$announcementId]);
    
    logActivity('delete_announcement', 'announcement', $announcementId, "Deleted: {$announcement['title']}");
    
    sendSuccess(null, 'Announcement deleted successfully');
}
