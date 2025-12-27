// ===== ANNOUNCEMENTS PAGE JAVASCRIPT =====

let announcementsData = [];

document.addEventListener('DOMContentLoaded', async function() {
    setupAnnouncementForm();
    await loadAnnouncements();
});

// Load announcements from API
async function loadAnnouncements() {
    const container = document.getElementById('announcementsContainer');
    if (!container) return;
    
    showLoadingState(container, 'Loading announcements...');
    
    try {
        const response = await AnnouncementsAPI.getAnnouncements();
        
        if (!response.success) {
            showErrorState(container, response.message);
            return;
        }
        
        announcementsData = response.data.announcements || [];
        
        if (announcementsData.length === 0) {
            showEmptyState(container, 'No announcements yet', '📢');
            return;
        }
        
        renderAnnouncements(announcementsData);
        setupReactions();
        
    } catch (error) {
        console.error('Error loading announcements:', error);
        showErrorState(container, 'Failed to load announcements');
    }
}

// Render announcements
function renderAnnouncements(announcements) {
    const container = document.getElementById('announcementsContainer');
    if (!container) return;
    
    container.innerHTML = announcements.map(announcement => `
        <div class="announcement-card ${announcement.is_important ? 'important' : ''}">
            ${announcement.is_important ? '<div class="important-badge">📌 Important</div>' : ''}
            <div class="announcement-header">
                <div class="announcement-author">
                    <div class="user-avatar small">${announcement.posted_by_avatar || 'U'}</div>
                    <div>
                        <strong>${escapeHtml(announcement.posted_by_name)}</strong>
                        <span class="announcement-date">${formatDate(announcement.created_at)}</span>
                    </div>
                </div>
            </div>
            <h4 class="announcement-title">${escapeHtml(announcement.title)}</h4>
            <p class="announcement-content">${escapeHtml(announcement.content)}</p>
            <div class="announcement-reactions">
                ${renderReactionButtons(announcement)}
            </div>
        </div>
    `).join('');
}

// Render reaction buttons
function renderReactionButtons(announcement) {
    const reactionCounts = announcement.reaction_counts || {};
    const reactions = ['👍', '❤️', '😊', '🎉'];
    
    return reactions.map(emoji => {
        const count = reactionCounts[emoji] || 0;
        return `
            <button class="reaction-btn" 
                    data-announcement-id="${announcement.id}" 
                    data-reaction="${emoji}">
                ${emoji} ${count > 0 ? count : ''}
            </button>
        `;
    }).join('');
}

// Setup announcement form
function setupAnnouncementForm() {
    const form = document.getElementById('newAnnouncementForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('newAnnouncementForm')) {
                return;
            }
            
            const announcementData = {
                title: document.getElementById('announcementTitle').value,
                content: document.getElementById('announcementContent').value,
                is_important: document.getElementById('pinAnnouncement')?.checked || false
            };
            
            try {
                const response = await AnnouncementsAPI.createAnnouncement(announcementData);
                
                if (response.success) {
                    let message = `Announcement "${announcementData.title}" posted successfully!`;
                    if (announcementData.is_important) {
                        message += ' (Pinned to top)';
                    }
                    
                    smartShare.showNotification(message);
                    smartShare.closeModal('newAnnouncementModal');
                    form.reset();
                    await loadAnnouncements();
                } else {
                    smartShare.showNotification(response.message, 'error');
                }
                
            } catch (error) {
                console.error('Error creating announcement:', error);
                smartShare.showNotification('Failed to post announcement', 'error');
            }
        });
    }
}

// Setup reactions
function setupReactions() {
    const reactionBtns = document.querySelectorAll('.reaction-btn');
    
    reactionBtns.forEach(btn => {
        btn.addEventListener('click', async function() {
            const announcementId = parseInt(this.getAttribute('data-announcement-id'));
            const reaction = this.getAttribute('data-reaction');
            
            try {
                const response = await AnnouncementsAPI.addReaction(announcementId, reaction);
                
                if (response.success) {
                    // Visual feedback
                    this.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 200);
                    
                    smartShare.showNotification('Reaction added!');
                    
                    // Reload to update counts
                    await loadAnnouncements();
                } else {
                    smartShare.showNotification(response.message, 'error');
                }
                
            } catch (error) {
                console.error('Error adding reaction:', error);
                smartShare.showNotification('Failed to add reaction', 'error');
            }
        });
    });
}

// Helper functions
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
    if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
