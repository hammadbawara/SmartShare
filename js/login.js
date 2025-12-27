// ===== LOGIN PAGE JAVASCRIPT =====

// API Configuration
const API_BASE_URL = window.location.origin + '/backend/api';

// Demo credentials for display (passwords are in database)
const demoCredentials = {
    admin: {
        username: 'admin',
        password: 'password123',
        role: 'admin',
        fullName: 'Ahmed Ali'
    },
    roommate1: {
        username: 'roommate1',
        password: 'password123',
        role: 'roommate',
        fullName: 'Hassan Khan'
    },
    roommate2: {
        username: 'roommate2',
        password: 'password123',
        role: 'roommate',
        fullName: 'Fatima Noor'
    },
    landlord: {
        username: 'landlord',
        password: 'password123',
        role: 'landlord',
        fullName: 'Malik Ahmed'
    },
    maintenance: {
        username: 'maintenance',
        password: 'password123',
        role: 'maintenance',
        fullName: 'Usman Tariq'
    }
};

document.addEventListener('DOMContentLoaded', async function() {
    // Check if already logged in
    try {
        const sessionResponse = await fetch(`${API_BASE_URL}/auth/session.php`, {
            credentials: 'include'
        });
        const sessionData = await sessionResponse.json();
        
        if (sessionData.success && sessionData.data.authenticated) {
            // User is already logged in, redirect to appropriate dashboard
            window.location.href = getDashboardUrl(sessionData.data.user.role);
            return;
        }
    } catch (error) {
        console.log('Not logged in or session check failed');
    }
    
    setupLoginForm();
    setupCredentialCards();
});

function setupLoginForm() {
    const form = document.getElementById('loginForm');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        await handleLogin();
    });
}

async function handleLogin() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const selectedRole = document.getElementById('userRole').value;
    
    // Validation
    if (!username || !password || !selectedRole) {
        showNotification('Please fill in all fields', 'error');
        return;
    }
    
    // Guest access
    if (selectedRole === 'guest') {
        window.location.href = 'guest-view.html';
        return;
    }
    
    // Show loading
    showLoading();
    
    try {
        // Call backend login API
        const response = await fetch(`${API_BASE_URL}/auth/login.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'include', // Important for cookies/sessions
            body: JSON.stringify({
                username: username,
                password: password
            })
        });
        
        const data = await response.json();
        
        hideLoading();
        
        if (!data.success) {
            showNotification(data.message || 'Login failed', 'error');
            shakeForm();
            return;
        }
        
        // Check if role matches
        if (data.data.user.role !== selectedRole) {
            showNotification(`Please select "${getRoleName(data.data.user.role)}" as your role`, 'error');
            return;
        }
        
        // Store minimal user data in sessionStorage for quick access
        // (Primary auth is server-side via session)
        const sessionData = {
            id: data.data.user.id,
            username: data.data.user.username,
            fullName: data.data.user.fullName,
            email: data.data.user.email,
            role: data.data.user.role,
            avatar: data.data.user.avatar,
            loginTime: new Date().toISOString()
        };
        
        sessionStorage.setItem('currentUser', JSON.stringify(sessionData));
        
        // Success notification and redirect
        showNotification(`Welcome back, ${data.data.user.fullName}!`, 'success');
        setTimeout(() => {
            window.location.href = getDashboardUrl(data.data.user.role);
        }, 500);
        
    } catch (error) {
        hideLoading();
        console.error('Login error:', error);
        showNotification('Login failed. Please check your connection and try again.', 'error');
        shakeForm();
    }
}

// Helper function to get dashboard URL based on role
function getDashboardUrl(role) {
    const dashboards = {
        'admin': 'admin-dashboard.html',
        'roommate': 'roommate-dashboard.html',
        'landlord': 'landlord-dashboard.html',
        'maintenance': 'maintenance-dashboard.html'
    };
    return dashboards[role] || 'admin-dashboard.html';
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
            else if (roleText.includes('roommate')) role = 'roommate';
            
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
