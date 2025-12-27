// ===== CHORES PAGE JAVASCRIPT =====

let currentWeekOffset = 0;
let currentFilter = 'all';
let choresData = [];

document.addEventListener('DOMContentLoaded', async function() {
    setupFilterTabs();
    setupChoreForm();
    await loadChores();
    updateWeekDisplay();
});

// Load chores from API
async function loadChores() {
    const container = document.getElementById('choresContainer');
    if (!container) return;
    
    showLoadingState(container, 'Loading chores...');
    
    try {
        const week = getCurrentWeek();
        const response = await ChoresAPI.getChores(currentFilter, week);
        
        if (!response.success) {
            showErrorState(container, response.message);
            return;
        }
        
        choresData = response.data.chores || [];
        
        if (choresData.length === 0) {
            showEmptyState(container, 'No chores for this week', '✓');
            return;
        }
        
        renderChores(choresData);
        updateStats(response.data.stats);
        
    } catch (error) {
        console.error('Error loading chores:', error);
        showErrorState(container, 'Failed to load chores');
    }
}

// Render chores
function renderChores(chores) {
    const container = document.getElementById('choresContainer');
    if (!container) return;
    
    container.innerHTML = chores.map(chore => `
        <div class="chore-card ${chore.is_completed ? 'completed' : ''}" data-chore-id="${chore.id}">
            <div class="chore-header">
                <h4 class="chore-title">${escapeHtml(chore.title)}</h4>
                ${chore.is_completed ? '<span class="complete-badge">✓ Complete</span>' : ''}
            </div>
            <p class="chore-description">${escapeHtml(chore.description || 'No description')}</p>
            <div class="chore-meta">
                <div class="chore-assignee">
                    <span class="user-avatar small">${chore.assigned_to_avatar || 'U'}</span>
                    <span>${escapeHtml(chore.assigned_to_name)}</span>
                </div>
                <div class="chore-date">
                    Due: ${formatDate(chore.due_date)}
                </div>
            </div>
            ${!chore.is_completed ? `
                <button class="btn-primary chore-complete-btn" onclick="completeChore(${chore.id})">
                    Mark Complete
                </button>
            ` : ''}
        </div>
    `).join('');
}

// Update stats
function updateStats(stats) {
    const completionRateEl = document.getElementById('completionRate');
    const pendingChoresEl = document.getElementById('pendingChores');
    
    if (completionRateEl && stats) {
        completionRateEl.textContent = `${stats.completionRate}%`;
    }
    if (pendingChoresEl && stats) {
        pendingChoresEl.textContent = stats.pending;
    }
}

// Setup filter tabs
function setupFilterTabs() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', async function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            currentFilter = this.getAttribute('data-filter');
            await loadChores();
        });
    });
}

// Setup chore form
function setupChoreForm() {
    const form = document.getElementById('addChoreForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('addChoreForm')) {
                return;
            }
            
            const choreData = {
                title: document.getElementById('choreName').value,
                description: document.getElementById('choreDescription')?.value || '',
                assigned_to: parseInt(document.getElementById('choreAssignee').value),
                due_date: document.getElementById('choreDueDate').value,
                assigned_date: new Date().toISOString().split('T')[0],
                recurrence: document.getElementById('choreRecurrence')?.value || 'weekly'
            };
            
            try {
                const response = await ChoresAPI.createChore(choreData);
                
                if (response.success) {
                    smartShare.showNotification('Chore added successfully!');
                    smartShare.closeModal('addChoreModal');
                    form.reset();
                    await loadChores();
                } else {
                    smartShare.showNotification(response.message, 'error');
                }
                
            } catch (error) {
                console.error('Error creating chore:', error);
                smartShare.showNotification('Failed to add chore', 'error');
            }
        });
    }
}

// Complete chore
async function completeChore(choreId) {
    try {
        const response = await ChoresAPI.updateChore(choreId, { is_completed: true });
        
        if (response.success) {
            smartShare.showNotification('Chore marked as complete! Great job! 🎉');
            await loadChores();
        } else {
            smartShare.showNotification(response.message, 'error');
        }
        
    } catch (error) {
        console.error('Error completing chore:', error);
        smartShare.showNotification('Failed to complete chore', 'error');
    }
}

// Rotate chores
async function rotateChores() {
    const confirmed = confirm('This will reassign all chores to the next person. Continue?');
    
    if (!confirmed) return;
    
    try {
        const response = await ChoresAPI.rotateChores();
        
        if (response.success) {
            smartShare.showNotification('Chore schedule rotated successfully!');
            setTimeout(async () => {
                await loadChores();
            }, 1000);
        } else {
            smartShare.showNotification(response.message, 'error');
        }
        
    } catch (error) {
        console.error('Error rotating chores:', error);
        smartShare.showNotification('Failed to rotate chores', 'error');
    }
}

// Week navigation
function previousWeek() {
    currentWeekOffset--;
    updateWeekDisplay();
    loadChores();
}

function nextWeek() {
    currentWeekOffset++;
    updateWeekDisplay();
    loadChores();
}

function updateWeekDisplay() {
    const weekDisplay = document.getElementById('currentWeek');
    if (!weekDisplay) return;
    
    const date = new Date();
    date.setDate(date.getDate() + (currentWeekOffset * 7));
    
    const startOfWeek = new Date(date);
    startOfWeek.setDate(date.getDate() - date.getDay() + 1); // Monday
    
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6); // Sunday
    
    const formatOptions = { month: 'short', day: 'numeric' };
    weekDisplay.textContent = `${startOfWeek.toLocaleDateString('en-US', formatOptions)} - ${endOfWeek.toLocaleDateString('en-US', formatOptions)}`;
}

function getCurrentWeek() {
    const date = new Date();
    date.setDate(date.getDate() + (currentWeekOffset * 7));
    
    const year = date.getFullYear();
    const weekNumber = getWeekNumber(date);
    
    return `${year}-W${String(weekNumber).padStart(2, '0')}`;
}

function getWeekNumber(date) {
    const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
    const dayNum = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dayNum);
    const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
    return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}

// Export functions for global access
window.completeChore = completeChore;
window.rotateChores = rotateChores;
window.previousWeek = previousWeek;
window.nextWeek = nextWeek;
