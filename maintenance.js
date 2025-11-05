// ===== MAINTENANCE PAGE JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    setupFilterTabs();
    setupNewTicketForm();
    setupUpdateStatusForm();
});

function setupFilterTabs() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const ticketCards = document.querySelectorAll('.ticket-card');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            ticketCards.forEach(card => {
                const status = card.getAttribute('data-status');
                if (filter === 'all') {
                    card.style.display = '';
                } else {
                    card.style.display = status === filter ? '' : 'none';
                }
            });
        });
    });
}

function setupNewTicketForm() {
    const form = document.getElementById('newTicketForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('newTicketForm')) {
                return;
            }
            
            const title = document.getElementById('ticketTitle').value;
            const priority = document.getElementById('ticketPriority').value;
            
            smartShare.showNotification(`Maintenance request "${title}" submitted successfully!`);
            smartShare.closeModal('newTicketModal');
            
            // In a real app, this would create a new ticket card
            console.log('New ticket submitted');
        });
    }
}

function setupUpdateStatusForm() {
    const form = document.getElementById('updateStatusForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newStatus = document.getElementById('newStatus').value;
            smartShare.showNotification(`Ticket status updated to: ${newStatus}`);
            smartShare.closeModal('updateStatusModal');
        });
    }
}

function viewTicketDetails(ticketId) {
    smartShare.showNotification(`Viewing details for ticket #${ticketId}`);
    // In a real app, this would open a detailed view modal
    console.log('View ticket:', ticketId);
}

function updateTicketStatus(ticketId) {
    // Store the ticket ID for the update form
    window.currentTicketId = ticketId;
    smartShare.openModal('updateStatusModal');
}

// Export functions for global access
window.viewTicketDetails = viewTicketDetails;
window.updateTicketStatus = updateTicketStatus;
