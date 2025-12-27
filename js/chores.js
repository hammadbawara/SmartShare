// ===== CHORES PAGE JAVASCRIPT =====

let currentWeekOffset = 0;

document.addEventListener('DOMContentLoaded', function() {
    setupFilterTabs();
    setupChoreForm();
    updateWeekDisplay();
});

function setupFilterTabs() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const choreCards = document.querySelectorAll('.chore-card');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            filterChores(filter);
        });
    });
}

function filterChores(filter) {
    const choreCards = document.querySelectorAll('.chore-card');
    
    choreCards.forEach(card => {
        const isCompleted = card.classList.contains('completed');
        const assignee = card.querySelector('.chore-assignee span').textContent;
        const currentUser = 'John Doe'; // Would come from user session
        
        switch(filter) {
            case 'all':
                card.style.display = 'flex';
                break;
            case 'mine':
                card.style.display = assignee === currentUser ? 'flex' : 'none';
                break;
            case 'pending':
                card.style.display = !isCompleted ? 'flex' : 'none';
                break;
            case 'completed':
                card.style.display = isCompleted ? 'flex' : 'none';
                break;
        }
    });
}

function completeChore(choreId) {
    const choreCard = document.querySelector(`.chore-card[data-chore-id="${choreId}"]`);
    if (choreCard) {
        choreCard.classList.add('completed');
        
        const choreName = choreCard.querySelector('.chore-title').textContent;
        smartShare.showNotification(`"${choreName}" marked as complete! Great job! 🎉`);
        
        // Hide the complete button
        const completeBtn = choreCard.querySelector('.chore-complete-btn');
        if (completeBtn) {
            completeBtn.style.display = 'none';
        }
        
        // Update stats
        updateChoreStats();
    }
}

function updateChoreStats() {
    // This would recalculate completion rates in a real app
    console.log('Updating chore stats...');
}

function rotateChores() {
    const confirmed = confirm('This will reassign all chores to the next person. Continue?');
    
    if (confirmed) {
        smartShare.showNotification('Chore schedule rotated successfully!');
        
        // In a real app, this would rotate assignments
        setTimeout(() => {
            location.reload();
        }, 1500);
    }
}

function previousWeek() {
    currentWeekOffset--;
    updateWeekDisplay();
}

function nextWeek() {
    currentWeekOffset++;
    updateWeekDisplay();
}

function updateWeekDisplay() {
    const weekDisplay = document.getElementById('currentWeek');
    if (!weekDisplay) return;
    
    const today = new Date();
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - today.getDay() + (currentWeekOffset * 7));
    
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);
    
    const formatOptions = { month: 'short', day: 'numeric', year: 'numeric' };
    const startStr = startOfWeek.toLocaleDateString('en-US', formatOptions);
    const endStr = endOfWeek.toLocaleDateString('en-US', formatOptions);
    
    weekDisplay.textContent = `${startStr.replace(', 2025', '')} - ${endStr}`;
}

function setupChoreForm() {
    const form = document.getElementById('addChoreForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('addChoreForm')) {
                return;
            }
            
            const choreName = document.getElementById('choreName').value;
            smartShare.showNotification(`Chore "${choreName}" added successfully!`);
            smartShare.closeModal('addChoreModal');
        });
    }
}

// Export functions for global access
window.completeChore = completeChore;
window.rotateChores = rotateChores;
window.previousWeek = previousWeek;
window.nextWeek = nextWeek;
