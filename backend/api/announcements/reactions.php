<?php
/**
 * Announcement Reactions API Endpoint
 * POST /backend/api/announcements/reactions.php
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/validation.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendMethodNotAllowed(['POST']);
}

try {
    $user = requireAuth();
    $db = getDB();
    $data = getRequestData();
    
    $errors = validateRequired($data, ['announcement_id', 'reaction']);
    if (!empty($errors)) {
        sendValidationError($errors);
    }
    
    $announcementId = (int)$data['announcement_id'];
    $reaction = sanitizeString($data['reaction']);
    
    // Check if announcement exists
    $stmt = $db->prepare("SELECT id FROM announcements WHERE id = ?");
    $stmt->execute([$announcementId]);
    if (!$stmt->fetch()) {
        sendNotFound('Announcement not found');
    }
    
    // Check if user already reacted
    $stmt = $db->prepare("SELECT id FROM announcement_reactions WHERE announcement_id = ? AND user_id = ?");
    $stmt->execute([$announcementId, $user['id']]);
    $existingReaction = $stmt->fetch();
    
    if ($existingReaction) {
        // Update existing reaction
        $stmt = $db->prepare("UPDATE announcement_reactions SET reaction = ? WHERE id = ?");
        $stmt->execute([$reaction, $existingReaction['id']]);
        $message = 'Reaction updated successfully';
    } else {
        // Insert new reaction
        $stmt = $db->prepare("
            INSERT INTO announcement_reactions (announcement_id, user_id, reaction)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$announcementId, $user['id'], $reaction]);
        $message = 'Reaction added successfully';
    }
    
    logActivity('react_announcement', 'announcement', $announcementId, "Reacted with: $reaction");
    
    sendSuccess(null, $message);
    
} catch (Exception $e) {
    if (APP_DEBUG) {
        sendServerError($e->getMessage());
    } else {
        sendServerError('An error occurred processing your reaction');
    }
}
