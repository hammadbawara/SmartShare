// ===== MAINTENANCE PAGE JAVASCRIPT =====

let currentFilter = 'all';
let ticketsData = [];

document.addEventListener('DOMContentLoaded', async function() {
    setupFilterTabs();
    setupNewTicketForm();
    setupUpdateStatusForm();
    await loadTickets();
});

// Load tickets from API
async function loadTickets() {
    const container = document.getElementById('ticketsContainer');
    if (!container) return;
    
    showLoadingState(container, 'Loading maintenance tickets...');
    
    try {
        const response = await MaintenanceAPI.getTickets(currentFilter);
        
        if (!response.success) {
            showErrorState(container, response.message);
            return;
        }
        
        ticketsData = response.data.tickets || [];
        
        if (ticketsData.length === 0) {
            showEmptyState(container, 'No maintenance tickets found', '🔧');
            return;
        }
        
        renderTickets(ticketsData);
        
    } catch (error) {
        console.error('Error loading tickets:', error);
        showErrorState(container, 'Failed to load tickets');
    }
}

// Render tickets
function renderTickets(tickets) {
    const container = document.getElementById('ticketsContainer');
    if (!container) return;
    
    container.innerHTML = tickets.map(ticket => `
        <div class="ticket-card" data-status="${ticket.status}">
            <div class="ticket-header">
                <div>
                    <h4 class="ticket-title">${escapeHtml(ticket.title)}</h4>
                    <span class="ticket-id">#${ticket.id}</span>
                </div>
                <span class="priority-badge ${ticket.priority}">${ticket.priority}</span>
            </div>
            <p class="ticket-description">${escapeHtml(ticket.description)}</p>
            <div class="ticket-meta">
                <div class="ticket-info">
                    <span class="ticket-category">${ticket.category}</span>
                    ${ticket.location ? `<span class="ticket-location">📍 ${escapeHtml(ticket.location)}</span>` : ''}
                </div>
                <div class="ticket-reporter">
                    Reported by: ${escapeHtml(ticket.reported_by_name)}
                </div>
            </div>
            <div class="ticket-footer">
                <span class="status-badge ${ticket.status}">${formatStatus(ticket.status)}</span>
                <div class="ticket-actions">
                    <button class="btn-secondary" onclick="viewTicketDetails(${ticket.id})">View Details</button>
                    <button class="btn-primary" onclick="updateTicketStatus(${ticket.id})">Update Status</button>
                </div>
            </div>
        </div>
    `).join('');
}

// Setup filter tabs
function setupFilterTabs() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', async function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            currentFilter = this.getAttribute('data-filter');
            await loadTickets();
        });
    });
}

// Setup new ticket form
function setupNewTicketForm() {
    const form = document.getElementById('newTicketForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('newTicketForm')) {
                return;
            }
            
            const ticketData = {
                title: document.getElementById('ticketTitle').value,
                description: document.getElementById('ticketDescription').value,
                category: document.getElementById('ticketCategory').value,
                priority: document.getElementById('ticketPriority').value,
                location: document.getElementById('ticketLocation')?.value || ''
            };
            
            try {
                const response = await MaintenanceAPI.createTicket(ticketData);
                
                if (response.success) {
                    smartShare.showNotification(`Maintenance request "${ticketData.title}" submitted successfully!`);
                    smartShare.closeModal('newTicketModal');
                    form.reset();
                    await loadTickets();
                } else {
                    smartShare.showNotification(response.message, 'error');
                }
                
            } catch (error) {
                console.error('Error creating ticket:', error);
                smartShare.showNotification('Failed to submit ticket', 'error');
            }
        });
    }
}

// Setup update status form
function setupUpdateStatusForm() {
    const form = document.getElementById('updateStatusForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const ticketId = window.currentTicketId;
            if (!ticketId) {
                smartShare.showNotification('No ticket selected', 'error');
                return;
            }
            
            const newStatus = document.getElementById('newStatus').value;
            const notes = document.getElementById('statusNotes')?.value || '';
            
            try {
                const response = await MaintenanceAPI.updateTicket(ticketId, {
                    status: newStatus,
                    notes: notes
                });
                
                if (response.success) {
                    smartShare.showNotification(`Ticket status updated to: ${formatStatus(newStatus)}`);
                    smartShare.closeModal('updateStatusModal');
                    form.reset();
                    await loadTickets();
                } else {
                    smartShare.showNotification(response.message, 'error');
                }
                
            } catch (error) {
                console.error('Error updating ticket:', error);
                smartShare.showNotification('Failed to update ticket', 'error');
            }
        });
    }
}

// View ticket details
function viewTicketDetails(ticketId) {
    const ticket = ticketsData.find(t => t.id === ticketId);
    if (!ticket) {
        smartShare.showNotification('Ticket not found', 'error');
        return;
    }
    
    // Display ticket details in a modal or separate view
    alert(`Ticket Details:\n\n` +
          `ID: #${ticket.id}\n` +
          `Title: ${ticket.title}\n` +
          `Status: ${formatStatus(ticket.status)}\n` +
          `Priority: ${ticket.priority}\n` +
          `Category: ${ticket.category}\n` +
          `Location: ${ticket.location || 'Not specified'}\n` +
          `Description: ${ticket.description}\n` +
          `Reported by: ${ticket.reported_by_name}\n` +
          `Created: ${formatDate(ticket.created_at)}`);
}

// Update ticket status
function updateTicketStatus(ticketId) {
    window.currentTicketId = ticketId;
    smartShare.openModal('updateStatusModal');
}

// Helper functions
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatStatus(status) {
    const statusMap = {
        'open': 'Open',
        'in_progress': 'In Progress',
        'completed': 'Completed',
        'cancelled': 'Cancelled'
    };
    return statusMap[status] || status;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

// Export functions for global access
window.viewTicketDetails = viewTicketDetails;
window.updateTicketStatus = updateTicketStatus;
