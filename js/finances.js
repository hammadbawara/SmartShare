// ===== FINANCES PAGE JAVASCRIPT =====

let currentFilter = 'all';
let billsData = [];

document.addEventListener('DOMContentLoaded', async function() {
    setupFilterTabs();
    setupBillForm();
    await loadBills();
    await loadFinancialSummary();
});

// Load bills from API
async function loadBills() {
    const container = document.getElementById('billsTableBody');
    if (!container) return;
    
    showLoadingState(container, 'Loading bills...');
    
    try {
        const response = await FinancesAPI.getBills(currentFilter);
        
        if (!response.success) {
            showErrorState(container, response.message);
            return;
        }
        
        billsData = response.data.bills || [];
        
        if (billsData.length === 0) {
            showEmptyState(container, 'No bills found', '💰');
            return;
        }
        
        renderBills(billsData);
        
    } catch (error) {
        console.error('Error loading bills:', error);
        showErrorState(container, 'Failed to load bills');
    }
}

// Render bills table
function renderBills(bills) {
    const container = document.getElementById('billsTableBody');
    if (!container) return;
    
    container.innerHTML = bills.map(bill => `
        <tr data-status="${bill.is_paid ? 'paid' : 'unpaid'}">
            <td>
                <strong>${escapeHtml(bill.title)}</strong>
                <br>
                <span class="bill-category">${bill.category}</span>
            </td>
            <td>Rs. ${parseFloat(bill.amount).toLocaleString('en-PK')}</td>
            <td>${formatDate(bill.due_date)}</td>
            <td>
                <span class="status-badge ${bill.is_paid ? 'paid' : 'unpaid'}">
                    ${bill.is_paid ? 'Paid' : 'Unpaid'}
                </span>
            </td>
            <td>
                ${bill.is_paid 
                    ? `<span class="paid-date">Paid ${formatDate(bill.paid_date)}</span>`
                    : `<button class="btn-action" onclick="markAsPaid(${bill.id})">Mark as Paid</button>`
                }
            </td>
        </tr>
    `).join('');
}

// Load financial summary
async function loadFinancialSummary() {
    try {
        const response = await FinancesAPI.getSummary();
        
        if (!response.success) {
            console.error('Failed to load summary:', response.message);
            return;
        }
        
        const summary = response.data;
        updateSummaryDisplay(summary);
        
    } catch (error) {
        console.error('Error loading summary:', error);
    }
}

// Update summary display
function updateSummaryDisplay(summary) {
    const totalExpensesEl = document.getElementById('totalExpenses');
    const userShareEl = document.getElementById('userShare');
    const outstandingEl = document.getElementById('outstanding');
    
    if (totalExpensesEl) {
        totalExpensesEl.textContent = `Rs. ${summary.totals.expenses.toLocaleString('en-PK')}`;
    }
    if (userShareEl) {
        userShareEl.textContent = `Rs. ${summary.userSummary.share.toLocaleString('en-PK')}`;
    }
    if (outstandingEl) {
        outstandingEl.textContent = `Rs. ${summary.userSummary.outstanding.toLocaleString('en-PK')}`;
    }
}

// Filter tabs functionality
function setupFilterTabs() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', async function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            currentFilter = this.getAttribute('data-filter');
            await loadBills();
        });
    });
}

// Setup bill form
function setupBillForm() {
    const form = document.getElementById('addBillForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('addBillForm')) {
                return;
            }
            
            const billData = {
                title: document.getElementById('billName').value,
                amount: parseFloat(document.getElementById('billAmount').value),
                category: document.getElementById('billCategory')?.value || 'other',
                due_date: document.getElementById('billDueDate').value,
                description: document.getElementById('billDescription')?.value || ''
            };
            
            try {
                const response = await FinancesAPI.createBill(billData);
                
                if (response.success) {
                    smartShare.showNotification(`Bill "${billData.title}" added successfully!`);
                    smartShare.closeModal('addBillModal');
                    form.reset();
                    await loadBills();
                    await loadFinancialSummary();
                } else {
                    smartShare.showNotification(response.message, 'error');
                }
                
            } catch (error) {
                console.error('Error creating bill:', error);
                smartShare.showNotification('Failed to add bill', 'error');
            }
        });
    }
}

// Mark bill as paid
async function markAsPaid(billId) {
    try {
        const response = await FinancesAPI.updateBill(billId, { is_paid: true });
        
        if (response.success) {
            smartShare.showNotification('Bill marked as paid!');
            await loadBills();
            await loadFinancialSummary();
        } else {
            smartShare.showNotification(response.message, 'error');
        }
        
    } catch (error) {
        console.error('Error updating bill:', error);
        smartShare.showNotification('Failed to update bill', 'error');
    }
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

// Export functions for global access
window.markAsPaid = markAsPaid;
