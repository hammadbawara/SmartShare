// ===== USER MANAGEMENT PAGE JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    setupAddUserForm();
});

function setupAddUserForm() {
    const form = document.getElementById('addUserForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('addUserForm')) {
                return;
            }
            
            const userName = document.getElementById('userName').value;
            const userEmail = document.getElementById('userEmail').value;
            const userRole = document.getElementById('userRole').value;
            
            smartShare.showNotification(`${userName} added as ${userRole}!`);
            smartShare.closeModal('addUserModal');
            
            // In a real app, this would add user to the list
            console.log('User added:', { userName, userEmail, userRole });
        });
    }
}

function editUser(userId) {
    smartShare.showNotification(`Opening edit form for user #${userId}`);
    // In a real app, this would open an edit modal with pre-filled data
    console.log('Edit user:', userId);
}

function viewActivity(userId) {
    smartShare.showNotification(`Viewing activity for user #${userId}`);
    // In a real app, this would show user's activity history
    console.log('View activity:', userId);
}

function removeUser(userId) {
    const userCard = document.querySelector(`.user-card:has([onclick*="${userId}"])`);
    const userName = userCard?.querySelector('h4')?.textContent || 'this user';
    
    const confirmed = confirm(`Are you sure you want to remove ${userName} from the household? This action cannot be undone.`);
    
    if (confirmed) {
        if (userCard) {
            userCard.style.transition = 'all 0.3s ease';
            userCard.style.opacity = '0';
            userCard.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                userCard.remove();
                smartShare.showNotification(`${userName} removed successfully`);
            }, 300);
        }
    }
}

// Export functions for global access
window.editUser = editUser;
window.viewActivity = viewActivity;
window.removeUser = removeUser;
