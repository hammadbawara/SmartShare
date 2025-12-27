// ===== SMART-SHARE HOUSEHOLD MANAGER - MAIN APP.JS =====

// ===== AUTHENTICATION CHECK =====
function checkAuthentication() {
    const currentUser = sessionStorage.getItem('currentUser');
    const currentPage = window.location.pathname.split('/').pop();
    
    // Pages that don't require authentication
    const publicPages = ['login.html', 'guest-view.html'];
    
    // If not on a public page and not logged in, redirect to login
    if (!publicPages.includes(currentPage) && !currentUser) {
        window.location.href = 'login.html';
        return null;
    }
    
    // If logged in, return user data
    if (currentUser) {
        return JSON.parse(currentUser);
    }
    
    return null;
}

// ===== GLOBAL STATE =====
let currentUser = null;
let darkMode = false;

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Check authentication first
    currentUser = checkAuthentication();
    
    // If no user and not on public page, return (already redirected)
    if (!currentUser && !['login.html', 'guest-view.html'].includes(window.location.pathname.split('/').pop())) {
        return;
    }
    
    // Load saved theme preference
    loadThemePreference();
    
    // Setup event listeners
    setupEventListeners();
    
    // Update user display from session
    if (currentUser) {
        updateUserDisplay();
    }
    
    // Update active nav item based on current page
    updateActiveNavItem();
}

// ===== UPDATE USER DISPLAY FROM SESSION =====
function updateUserDisplay() {
    // Update user name in header
    const userNameElement = document.getElementById('userName') || document.querySelector('.user-name');
    if (userNameElement && currentUser) {
        userNameElement.textContent = currentUser.fullName;
    }
    
    // Update role badge
    const roleElement = document.querySelector('.user-role');
    if (roleElement && currentUser) {
        const roleNames = {
            'admin': 'House Admin',
            'roommate': 'Roommate',
            'landlord': 'Landlord',
            'maintenance': 'Maintenance Staff'
        };
        roleElement.textContent = roleNames[currentUser.role] || currentUser.role;
        
        // Update role badge class
        roleElement.className = 'user-role';
        roleElement.classList.add(`${currentUser.role}-badge`);
    }
    
    // Update role selector if exists (for old pages)
    const roleSelect = document.getElementById('roleSelect');
    if (roleSelect && currentUser) {
        roleSelect.value = currentUser.role;
    }
}

// ===== EVENT LISTENERS SETUP =====
function setupEventListeners() {
    // Role selector
    const roleSelect = document.getElementById('roleSelect');
    if (roleSelect) {
        roleSelect.addEventListener('change', function(e) {
            switchRole(e.target.value);
        });
    }
    
    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
    }
    
    // Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menuToggle');
        if (sidebar && menuToggle) {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
}

// ===== ROLE SWITCHING (Updated for new auth system) =====
function switchRole(role) {
    // This function is deprecated in new auth system
    // Role is determined by login, not by switching
    // Keep for backward compatibility with old pages
    
    if (!currentUser) return;
    
    // Update body attribute for role-specific styling
    document.body.setAttribute('data-role', currentUser.role);
    
    // Show/hide navigation items based on role
    updateNavigationForRole(currentUser.role);
}

function updateNavigationForRole(role) {
    const navItems = document.querySelectorAll('.nav-item[data-role]');
    
    navItems.forEach(item => {
        const allowedRoles = item.getAttribute('data-role').split(',');
        if (allowedRoles.includes(role)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

function updateActiveNavItem() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPage) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
}

// ===== DARK MODE =====
function toggleDarkMode() {
    darkMode = !darkMode;
    document.body.classList.toggle('dark-mode', darkMode);
    
    // Save preference
    localStorage.setItem('darkMode', darkMode ? 'enabled' : 'disabled');
    
    // Update icon
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.textContent = darkMode ? '☀️' : '🌙';
    }
    
    showNotification(darkMode ? 'Dark mode enabled' : 'Light mode enabled');
}

function loadThemePreference() {
    const savedTheme = localStorage.getItem('darkMode');
    if (savedTheme === 'enabled') {
        darkMode = true;
        document.body.classList.add('dark-mode');
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.textContent = '☀️';
        }
    }
}

// ===== MODAL FUNCTIONS =====
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = ''; // Restore scrolling
        
        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
        }
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
        document.body.style.overflow = '';
    }
});

// ===== FORM VALIDATION =====
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.style.borderColor = 'var(--danger)';
            
            // Remove error styling after user starts typing
            input.addEventListener('input', function() {
                input.style.borderColor = '';
            }, { once: true });
        }
    });
    
    if (!isValid) {
        showNotification('Please fill in all required fields', 'error');
    }
    
    return isValid;
}

// ===== NOTIFICATION SYSTEM =====
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
        background: ${type === 'error' ? 'var(--danger)' : 'var(--success)'};
        color: white;
        padding: 16px 24px;
        border-radius: var(--border-radius-small);
        box-shadow: var(--shadow-lg);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
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
`;
document.head.appendChild(style);

// ===== UTILITY FUNCTIONS =====

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Copied to clipboard!');
    }).catch(() => {
        showNotification('Failed to copy', 'error');
    });
}

// Format currency (Updated for PKR)
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PK', {
        style: 'currency',
        currency: 'PKR'
    }).format(amount);
}

// Format date
function formatDate(date) {
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }).format(new Date(date));
}

// Calculate days until
function daysUntil(date) {
    const today = new Date();
    const targetDate = new Date(date);
    const diffTime = targetDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
}

// ===== LOGOUT FUNCTION (Global) =====
function handleLogout() {
    if (confirm('Are you sure you want to logout?')) {
        sessionStorage.removeItem('currentUser');
        window.location.href = 'login.html';
    }
}

// Make logout function globally accessible
window.handleLogout = handleLogout;

// ===== EXPORT FOR OTHER SCRIPTS =====
window.smartShare = {
    openModal,
    closeModal,
    showNotification,
    validateForm,
    copyToClipboard,
    formatCurrency,
    formatDate,
    daysUntil,
    currentUser: () => currentUser,
    handleLogout
};
