// ===== ANNOUNCEMENTS PAGE JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    setupAnnouncementForm();
    setupReactions();
});

function setupAnnouncementForm() {
    const form = document.getElementById('newAnnouncementForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('newAnnouncementForm')) {
                return;
            }
            
            const title = document.getElementById('announcementTitle').value;
            const type = document.getElementById('announcementType').value;
            const isPinned = document.getElementById('pinAnnouncement').checked;
            
            let message = `Announcement "${title}" posted successfully!`;
            if (isPinned) {
                message += ' (Pinned to top)';
            }
            
            smartShare.showNotification(message);
            smartShare.closeModal('newAnnouncementModal');
            
            // In a real app, this would add the announcement to the feed
            console.log('Announcement posted:', { title, type, isPinned });
        });
    }
}

function setupReactions() {
    const reactionBtns = document.querySelectorAll('.reaction-btn');
    
    reactionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Extract current count
            const text = this.textContent.trim();
            const parts = text.split(' ');
            const emoji = parts[0];
            const count = parseInt(parts[1]) || 0;
            
            // Toggle reaction (simplified - just increment)
            const newCount = count + 1;
            this.textContent = `${emoji} ${newCount}`;
            
            // Add visual feedback
            this.style.transform = 'scale(1.2)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
            
            smartShare.showNotification(`Reaction added!`);
        });
    });
}

// Export functions - none needed for this page currently
