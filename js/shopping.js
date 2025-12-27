// ===== SHOPPING LIST PAGE JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    setupQuickAddForm();
    setupAddItemForm();
    setupItemCheckboxes();
});

function setupQuickAddForm() {
    const form = document.getElementById('quickAddForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const input = document.getElementById('quickItemName');
            const itemName = input.value.trim();
            
            if (itemName) {
                smartShare.showNotification(`"${itemName}" added to shopping list!`);
                input.value = '';
                
                // In a real app, this would add item to the appropriate category
                console.log('Quick add item:', itemName);
            }
        });
    }
}

function setupAddItemForm() {
    const form = document.getElementById('addItemForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('addItemForm')) {
                return;
            }
            
            const itemName = document.getElementById('itemName').value;
            const quantity = document.getElementById('itemQuantity').value;
            const category = document.getElementById('itemCategory').value;
            const claimSelf = document.getElementById('itemClaimSelf').checked;
            
            let message = `"${itemName}" added to shopping list!`;
            if (claimSelf) {
                message += ' You claimed this item.';
            }
            
            smartShare.showNotification(message);
            smartShare.closeModal('addItemModal');
            
            // In a real app, this would add item to the list
            console.log('Item added:', { itemName, quantity, category, claimSelf });
        });
    }
}

function setupItemCheckboxes() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const itemName = this.nextElementSibling.textContent;
            
            if (this.checked) {
                smartShare.showNotification(`"${itemName}" checked off!`);
                
                // In a real app, item would be moved to purchased history
                setTimeout(() => {
                    const item = this.closest('.shopping-item');
                    item.style.opacity = '0.5';
                }, 300);
            }
        });
    });
}

function claimItem(itemId) {
    const item = document.querySelector(`.shopping-item:has([id="item${itemId}"])`);
    if (item) {
        const itemName = item.querySelector('.item-name').textContent;
        const metaSection = item.querySelector('.item-meta');
        const claimBtn = item.querySelector('.btn-claim');
        
        // Replace claim button with claimed indicator
        if (claimBtn) {
            claimBtn.remove();
        }
        
        // Add claimed indicator
        const claimedDiv = document.createElement('div');
        claimedDiv.className = 'item-claimed';
        claimedDiv.innerHTML = `
            <div class="user-avatar tiny" style="background: var(--primary);">JD</div>
            <span class="claimed-text">John D.</span>
        `;
        metaSection.insertBefore(claimedDiv, metaSection.firstChild);
        
        smartShare.showNotification(`You claimed "${itemName}"!`);
    }
}

function deleteItem(itemId) {
    const item = document.querySelector(`.shopping-item:has([id="item${itemId}"])`);
    if (item) {
        const itemName = item.querySelector('.item-name').textContent;
        
        if (confirm(`Remove "${itemName}" from the shopping list?`)) {
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                item.remove();
                smartShare.showNotification(`"${itemName}" removed from list`);
            }, 300);
        }
    }
}

// Export functions for global access
window.claimItem = claimItem;
window.deleteItem = deleteItem;
