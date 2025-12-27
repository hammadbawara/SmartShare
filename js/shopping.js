// ===== SHOPPING LIST PAGE JAVASCRIPT =====

let itemsData = [];

document.addEventListener('DOMContentLoaded', async function() {
    setupQuickAddForm();
    setupAddItemForm();
    await loadShoppingItems();
});

// Load shopping items from API
async function loadShoppingItems() {
    const containers = {
        groceries: document.getElementById('groceriesItems'),
        household: document.getElementById('householdItems'),
        personal: document.getElementById('personalItems'),
        other: document.getElementById('otherItems')
    };
    
    // Show loading for all containers
    Object.values(containers).forEach(container => {
        if (container) showLoadingState(container, 'Loading items...');
    });
    
    try {
        const response = await ShoppingAPI.getItems();
        
        if (!response.success) {
            Object.values(containers).forEach(container => {
                if (container) showErrorState(container, response.message);
            });
            return;
        }
        
        itemsData = response.data.items || [];
        renderShoppingItems(itemsData, containers);
        
    } catch (error) {
        console.error('Error loading shopping items:', error);
        Object.values(containers).forEach(container => {
            if (container) showErrorState(container, 'Failed to load items');
        });
    }
}

// Render shopping items by category
function renderShoppingItems(items, containers) {
    const itemsByCategory = {
        groceries: items.filter(i => i.category === 'groceries'),
        household: items.filter(i => i.category === 'household'),
        personal: items.filter(i => i.category === 'personal'),
        other: items.filter(i => i.category === 'other')
    };
    
    Object.keys(containers).forEach(category => {
        const container = containers[category];
        if (!container) return;
        
        const categoryItems = itemsByCategory[category];
        
        if (categoryItems.length === 0) {
            container.innerHTML = '<p class="empty-category">No items in this category</p>';
            return;
        }
        
        container.innerHTML = categoryItems.map(item => `
            <div class="shopping-item ${item.is_purchased ? 'purchased' : ''}">
                <input type="checkbox" 
                       id="item${item.id}" 
                       class="item-checkbox" 
                       ${item.is_purchased ? 'checked' : ''}
                       onchange="toggleItemPurchased(${item.id}, this.checked)">
                <label for="item${item.id}" class="item-name">${escapeHtml(item.item_name)}</label>
                <div class="item-meta">
                    ${item.claimed_by_name ? `
                        <div class="item-claimed">
                            <div class="user-avatar tiny">${item.claimed_by_avatar || 'U'}</div>
                            <span class="claimed-text">${escapeHtml(item.claimed_by_name)}</span>
                        </div>
                    ` : `
                        <button class="btn-claim btn-sm" onclick="claimItem(${item.id})">Claim</button>
                    `}
                    <span class="item-quantity">${escapeHtml(item.quantity)}</span>
                    <button class="btn-delete-item" onclick="deleteItem(${item.id})" title="Delete item">🗑️</button>
                </div>
            </div>
        `).join('');
    });
}

// Setup quick add form
function setupQuickAddForm() {
    const form = document.getElementById('quickAddForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const input = document.getElementById('quickItemName');
            const itemName = input.value.trim();
            
            if (!itemName) return;
            
            try {
                const response = await ShoppingAPI.createItem({
                    item_name: itemName,
                    quantity: '1',
                    category: 'groceries'
                });
                
                if (response.success) {
                    smartShare.showNotification(`"${itemName}" added to shopping list!`);
                    input.value = '';
                    await loadShoppingItems();
                } else {
                    smartShare.showNotification(response.message, 'error');
                }
                
            } catch (error) {
                console.error('Error adding item:', error);
                smartShare.showNotification('Failed to add item', 'error');
            }
        });
    }
}

// Setup add item form
function setupAddItemForm() {
    const form = document.getElementById('addItemForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!smartShare.validateForm('addItemForm')) {
                return;
            }
            
            const itemData = {
                item_name: document.getElementById('itemName').value,
                quantity: document.getElementById('itemQuantity').value,
                category: document.getElementById('itemCategory').value,
                notes: document.getElementById('itemNotes')?.value || ''
            };
            
            const claimSelf = document.getElementById('itemClaimSelf')?.checked;
            
            try {
                const response = await ShoppingAPI.createItem(itemData);
                
                if (response.success) {
                    let message = `"${itemData.item_name}" added to shopping list!`;
                    
                    // If user wants to claim it, make another API call
                    if (claimSelf && currentUser?.id) {
                        const claimResponse = await ShoppingAPI.updateItem(response.data.id, {
                            claimed_by: currentUser.id
                        });
                        if (claimResponse.success) {
                            message += ' You claimed this item.';
                        }
                    }
                    
                    smartShare.showNotification(message);
                    smartShare.closeModal('addItemModal');
                    form.reset();
                    await loadShoppingItems();
                } else {
                    smartShare.showNotification(response.message, 'error');
                }
                
            } catch (error) {
                console.error('Error adding item:', error);
                smartShare.showNotification('Failed to add item', 'error');
            }
        });
    }
}

// Toggle item purchased status
async function toggleItemPurchased(itemId, isPurchased) {
    try {
        const response = await ShoppingAPI.updateItem(itemId, { is_purchased: isPurchased });
        
        if (response.success) {
            const item = itemsData.find(i => i.id === itemId);
            if (item) {
                smartShare.showNotification(`"${item.item_name}" ${isPurchased ? 'checked off' : 'unchecked'}!`);
            }
            await loadShoppingItems();
        } else {
            smartShare.showNotification(response.message, 'error');
            await loadShoppingItems(); // Reload to revert checkbox
        }
        
    } catch (error) {
        console.error('Error updating item:', error);
        smartShare.showNotification('Failed to update item', 'error');
        await loadShoppingItems(); // Reload to revert checkbox
    }
}

// Claim item
async function claimItem(itemId) {
    if (!currentUser) {
        smartShare.showNotification('Please login to claim items', 'error');
        return;
    }
    
    try {
        const response = await ShoppingAPI.updateItem(itemId, { 
            claimed_by: currentUser.id 
        });
        
        if (response.success) {
            const item = itemsData.find(i => i.id === itemId);
            if (item) {
                smartShare.showNotification(`You claimed "${item.item_name}"!`);
            }
            await loadShoppingItems();
        } else {
            smartShare.showNotification(response.message, 'error');
        }
        
    } catch (error) {
        console.error('Error claiming item:', error);
        smartShare.showNotification('Failed to claim item', 'error');
    }
}

// Delete item
async function deleteItem(itemId) {
    const item = itemsData.find(i => i.id === itemId);
    if (!item) return;
    
    const confirmed = confirm(`Delete "${item.item_name}" from shopping list?`);
    if (!confirmed) return;
    
    try {
        const response = await ShoppingAPI.deleteItem(itemId);
        
        if (response.success) {
            smartShare.showNotification('Item deleted successfully');
            await loadShoppingItems();
        } else {
            smartShare.showNotification(response.message, 'error');
        }
        
    } catch (error) {
        console.error('Error deleting item:', error);
        smartShare.showNotification('Failed to delete item', 'error');
    }
}

// Helper function
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Export functions for global access
window.toggleItemPurchased = toggleItemPurchased;
window.claimItem = claimItem;
window.deleteItem = deleteItem;
