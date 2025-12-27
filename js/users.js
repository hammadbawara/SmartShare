// ===== USER MANAGEMENT PAGE JAVASCRIPT =====

let usersData = [];

document.addEventListener('DOMContentLoaded', async function() {
    setupAddUserForm();
    await loadUsers();
});

// Load users from API
async function loadUsers() {
    const container = document.getElementById('usersContainer');
    if (!container) return;
    
    showLoadingState(container, 'Loading users...');
    
    try {
        const response = await UsersAPI.getUsers();
        
        if (!response.success) {
            showErrorState(container, response.message);
            return;
        }
        
        usersData = response.data.users || [];
        
        if (usersData.length === 0) {
            showEmptyState(container, 'No users found', '👥');
            return;
        }
        
        renderUsers(usersData);
        
    } catch (error) {
        console.error('Error loading users:', error);
        showErrorState(container, 'Failed to load users');
    }
}

// Render users
function renderUsers(users) {
    const container = document.getElementById('usersContainer');
    if (!container) return;
    
    container.innerHTML = users.map(user => `
        <div class="user-card ${!user.is_active ? 'inactive' : ''}">
            <div class="user-avatar large" style="background: var(--primary);">
                ${user.avatar || user.full_name.substring(0, 2).toUpperCase()}
            </div>
            <div class="user-info">
                <h4>${escapeHtml(user.full_name)}</h4>
                <p class="user-email">${escapeHtml(user.email)}</p>
                <span class="role-badge ${user.role}">${formatRole(user.role)}</span>
                ${user.lease_start ? `
                    <p class="user-lease">
                        Lease: ${formatDate(user.lease_start)} - ${formatDate(user.lease_end)}
                    </p>
                ` : ''}
            </div>
            <div class="user-actions">
                <button class="btn-secondary btn-sm" onclick="editUser(${user.id})">Edit</button>
                <button class="btn-secondary btn-sm" onclick="viewActivity(${user.id})">Activity</button>
                ${user.is_active ? `
                    <button class="btn-danger btn-sm" onclick="removeUser(${user.id})">Remove</button>
                ` : `
                    <span class="inactive-badge">Inactive</span>
                `}
            </div>
        </div>
    `).join('');
}

// Setup add user form
function setupAddUserForm() {
    const form = document.getElementById('addUserForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('addUserForm')) {
                return;
            }
            
            const userData = {
                username: document.getElementById('userName').value,
                email: document.getElementById('userEmail').value,
                password: document.getElementById('userPassword').value,
                full_name: document.getElementById('userFullName').value,
                role: document.getElementById('userRole').value,
                phone: document.getElementById('userPhone')?.value || '',
                lease_start: document.getElementById('userLeaseStart')?.value || null,
                lease_end: document.getElementById('userLeaseEnd')?.value || null
            };
            
            try {
                const response = await UsersAPI.createUser(userData);
                
                if (response.success) {
                    smartShare.showNotification(`${userData.full_name} added as ${formatRole(userData.role)}!`);
                    smartShare.closeModal('addUserModal');
                    form.reset();
                    await loadUsers();
                } else {
                    smartShare.showNotification(response.message, 'error');
                }
                
            } catch (error) {
                console.error('Error creating user:', error);
                smartShare.showNotification('Failed to add user', 'error');
            }
        });
    }
}

// Edit user
function editUser(userId) {
    const user = usersData.find(u => u.id === userId);
    if (!user) {
        smartShare.showNotification('User not found', 'error');
        return;
    }
    
    // TODO: Implement edit modal with pre-filled data
    smartShare.showNotification(`Edit functionality for ${user.full_name} coming soon!`);
    
    // For now, just show user details
    console.log('Edit user:', user);
}

// View activity
function viewActivity(userId) {
    const user = usersData.find(u => u.id === userId);
    if (!user) {
        smartShare.showNotification('User not found', 'error');
        return;
    }
    
    // TODO: Implement activity log view
    alert(`Activity Log for ${user.full_name}\n\n` +
          `Total Activities: ${user.activity_count || 0}\n` +
          `Last Login: ${formatDate(user.created_at)}\n\n` +
          `Detailed activity log coming soon!`);
}

// Remove user
async function removeUser(userId) {
    const user = usersData.find(u => u.id === userId);
    if (!user) {
        smartShare.showNotification('User not found', 'error');
        return;
    }
    
    const confirmed = confirm(
        `Are you sure you want to remove ${user.full_name} from the household?\n\n` +
        `This will deactivate their account. This action can be reversed by reactivating the user.`
    );
    
    if (!confirmed) return;
    
    try {
        const response = await UsersAPI.deleteUser(userId);
        
        if (response.success) {
            smartShare.showNotification(`${user.full_name} removed successfully`);
            await loadUsers();
        } else {
            smartShare.showNotification(response.message, 'error');
        }
        
    } catch (error) {
        console.error('Error removing user:', error);
        smartShare.showNotification('Failed to remove user', 'error');
    }
}

// Helper functions
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatRole(role) {
    const roleMap = {
        'admin': 'House Admin',
        'roommate': 'Roommate',
        'landlord': 'Landlord',
        'maintenance': 'Maintenance Staff'
    };
    return roleMap[role] || role;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

// Export functions for global access
window.editUser = editUser;
window.viewActivity = viewActivity;
window.removeUser = removeUser;
