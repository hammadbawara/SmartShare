// ===== GUEST PAGE JAVASCRIPT =====

let passwordVisible = false;

document.addEventListener('DOMContentLoaded', function() {
    // No special initialization needed for guest page
});

function togglePassword() {
    passwordVisible = !passwordVisible;
    const passwordText = document.getElementById('wifiPassword');
    const toggleBtn = document.querySelector('.toggle-password');
    
    if (passwordVisible) {
        passwordText.textContent = 'Welcome2024!';
        toggleBtn.textContent = '🙈';
    } else {
        passwordText.textContent = '••••••••••••';
        toggleBtn.textContent = '👁️';
    }
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            smartShare.showNotification('Copied to clipboard!');
        }).catch(() => {
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        smartShare.showNotification('Copied to clipboard!');
    } catch (err) {
        smartShare.showNotification('Failed to copy', 'error');
    }
    
    document.body.removeChild(textarea);
}

// Export functions for global access
window.togglePassword = togglePassword;
window.copyToClipboard = copyToClipboard;
