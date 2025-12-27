// ===== FINANCES PAGE JAVASCRIPT =====

// Filter tabs functionality
document.addEventListener('DOMContentLoaded', function() {
    setupFilterTabs();
    setupBillForm();
});

function setupFilterTabs() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const billRows = document.querySelectorAll('#billsTableBody tr[data-status]');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            // Filter bills
            billRows.forEach(row => {
                if (filter === 'all') {
                    row.style.display = '';
                } else {
                    const status = row.getAttribute('data-status');
                    row.style.display = status === filter ? '' : 'none';
                }
            });
        });
    });
}

function setupBillForm() {
    const form = document.getElementById('addBillForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('addBillForm')) {
                return;
            }
            
            const billName = document.getElementById('billName').value;
            const billAmount = document.getElementById('billAmount').value;
            const billDueDate = document.getElementById('billDueDate').value;
            
            smartShare.showNotification(`Bill "${billName}" added successfully!`);
            smartShare.closeModal('addBillModal');
            
            // In a real app, this would add the bill to the table
            console.log('Bill added:', { billName, billAmount, billDueDate });
        });
    }
}

function markAsPaid(button) {
    const row = button.closest('tr');
    const billName = row.querySelector('strong').textContent;
    const statusBadge = row.querySelector('.status-badge');
    const actionCell = row.querySelector('td:last-child');
    
    // Update status
    row.setAttribute('data-status', 'paid');
    statusBadge.className = 'status-badge paid';
    statusBadge.textContent = 'Paid';
    
    // Replace button with paid date
    const today = new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    actionCell.innerHTML = `<span class="paid-date">Paid ${today}</span>`;
    
    smartShare.showNotification(`${billName} marked as paid!`);
    
    // Update summary stats (in a real app)
    updateFinancialSummary();
}

function updateFinancialSummary() {
    // This would recalculate totals in a real app
    console.log('Updating financial summary...');
}

// Export functions for global access
window.markAsPaid = markAsPaid;
