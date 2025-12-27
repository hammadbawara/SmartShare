// ===== LOGIN PAGE JAVASCRIPT =====

// Mock user database
const users = {
    admin: {
        username: 'ahmed',
        password: 'admin123',
        role: 'admin',
        fullName: 'Ahmed Khan',
        dashboard: 'admin-dashboard.html'
    },
    hassan: {
        username: 'hassan',
        password: 'room123',
        role: 'roommate',
        fullName: 'Hassan Ali',
        roommateName: 'hassan',
        dashboard: 'roommate-dashboard.html'
    },
    fatima: {
        username: 'fatima',
        password: 'room123',
        role: 'roommate',
        fullName: 'Fatima Noor',
        roommateName: 'fatima',
        dashboard: 'roommate-dashboard.html'
    },
    landlord: {
        username: 'malik',
        password: 'land123',
        role: 'landlord',
        fullName: 'Malik Tariq',
        dashboard: 'landlord-dashboard.html'
    },
    maintenance: {
        username: 'usman',
        password: 'maint123',
        role: 'maintenance',
        fullName: 'Usman Electrician',
        dashboard: 'maintenance-dashboard.html'
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Check if already logged in
    const currentUser = sessionStorage.getItem('currentUser');
    if (currentUser) {
        const userData = JSON.parse(currentUser);
        window.location.href = userData.dashboard;
        return;
    }
    
    setupLoginForm();
    setupCredentialCards();
});

function setupLoginForm() {
    const form = document.getElementById('loginForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        handleLogin();
    });
}

function handleLogin() {
    const username = document.getElementById('username').value.trim().toLowerCase();
    const password = document.getElementById('password').value;
    const selectedRole = document.getElementById('userRole').value;
    
    // Validation
    if (!username || !password || !selectedRole) {
        showNotification('Please fill in all fields', 'error');
        return;
    }
    
    // Find user
    let authenticatedUser = null;
    for (let key in users) {
        const user = users[key];
        if (user.username === username && user.password === password) {
            authenticatedUser = user;
            break;
        }
    }
    
    if (!authenticatedUser) {
        showNotification('Invalid username or password', 'error');
        shakeForm();
        return;
    }
    
    // Check if role matches
    if (authenticatedUser.role !== selectedRole && selectedRole !== 'guest') {
        showNotification(`Please select "${getRoleName(authenticatedUser.role)}" as your role`, 'error');
        return;
    }
    
    // Guest access
    if (selectedRole === 'guest') {
        window.location.href = 'guest-view.html';
        return;
    }
    
    // Show loading
    showLoading();
    
    // Store user data in session
    const sessionData = {
        username: authenticatedUser.username,
        fullName: authenticatedUser.fullName,
        role: authenticatedUser.role,
        dashboard: authenticatedUser.dashboard,
        roommateName: authenticatedUser.roommateName || null,
        loginTime: new Date().toISOString()
    };
    
    sessionStorage.setItem('currentUser', JSON.stringify(sessionData));
    
    // Simulate loading and redirect
    setTimeout(() => {
        showNotification(`Welcome back, ${authenticatedUser.fullName}!`, 'success');
        setTimeout(() => {
            window.location.href = authenticatedUser.dashboard;
        }, 500);
    }, 1000);
}

function setupCredentialCards() {
    const credentialCards = document.querySelectorAll('.credential-card');
    
    credentialCards.forEach(card => {
        card.addEventListener('click', function() {
            const username = this.querySelector('p:nth-child(1) strong').nextSibling.textContent.trim();
            const password = this.querySelector('p:nth-child(2) strong').nextSibling.textContent.trim();
            const roleText = this.querySelector('.role-badge').textContent.trim().toLowerCase();
            
            // Map role text to role value
            let role = 'roommate';
            if (roleText.includes('admin')) role = 'admin';
            else if (roleText.includes('landlord')) role = 'landlord';
            else if (roleText.includes('maintenance')) role = 'maintenance';
            
            // Fill form
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            document.getElementById('userRole').value = role;
            
            // Visual feedback
            credentialCards.forEach(c => c.style.transform = '');
            this.style.transform = 'scale(1.05)';
            
            showNotification('Credentials auto-filled! Click Login to continue.', 'success');
        });
    });
}

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = '👁️';
    }
}

function showLoading() {
    document.getElementById('loadingOverlay').classList.add('active');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.remove('active');
}

function shakeForm() {
    const form = document.getElementById('loginForm');
    form.style.animation = 'shake 0.5s';
    setTimeout(() => {
        form.style.animation = '';
    }, 500);
}

function showNotification(message, type = 'success') {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Style notification
    notification.style.cssText = `
        position: fixed;
        top: 24px;
        right: 24px;
        background: ${type === 'error' ? '#DC2626' : '#0A6738'};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        animation: slideIn 0.3s ease;
        max-width: 400px;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function getRoleName(role) {
    const roleNames = {
        'admin': 'House Admin',
        'roommate': 'Roommate',
        'landlord': 'Landlord',
        'maintenance': 'Maintenance Staff',
        'guest': 'Guest'
    };
    return roleNames[role] || role;
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
        20%, 40%, 60%, 80% { transform: translateX(10px); }
    }
`;
document.head.appendChild(style);

// Export for global access
window.togglePasswordVisibility = togglePasswordVisibility;
