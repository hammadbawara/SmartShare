// ===== SMART-SHARE API CLIENT =====
// Reusable API functions for all modules

const API_BASE_URL = window.location.origin + '/backend/api';

/**
 * Generic API request wrapper with error handling
 */
async function apiRequest(endpoint, options = {}) {
    const defaultOptions = {
        credentials: 'include', // Important for cookies/sessions
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    const config = { ...defaultOptions, ...options };
    
    // Merge headers properly
    if (options.headers) {
        config.headers = { ...defaultOptions.headers, ...options.headers };
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        
        // Check if response is ok
        if (!response.ok && response.status !== 401) {
            console.error(`HTTP Error ${response.status}:`, response.statusText);
        }
        
        let data;
        try {
            data = await response.json();
        } catch (jsonError) {
            console.error('Failed to parse JSON response:', jsonError);
            throw new Error('Invalid server response');
        }
        
        // Check if session expired (401 Unauthorized)
        if (response.status === 401) {
            // Clear session and redirect to login
            sessionStorage.removeItem('currentUser');
            window.location.href = 'login.html';
            throw new Error('Session expired');
        }
        
        // Log API errors for debugging
        if (!data.success) {
            console.error('API Error:', data.message, data.errors);
        }
        
        return data;
        
    } catch (error) {
        console.error('API Request Error:', error);
        // Re-throw with more context
        if (error.message === 'Failed to fetch') {
            throw new Error('Network error - please check your connection');
        }
        throw error;
    }
}

/**
 * GET request
 */
async function apiGet(endpoint) {
    return await apiRequest(endpoint, { method: 'GET' });
}

/**
 * POST request
 */
async function apiPost(endpoint, body) {
    return await apiRequest(endpoint, {
        method: 'POST',
        body: JSON.stringify(body)
    });
}

/**
 * PUT request
 */
async function apiPut(endpoint, body) {
    return await apiRequest(endpoint, {
        method: 'PUT',
        body: JSON.stringify(body)
    });
}

/**
 * DELETE request
 */
async function apiDelete(endpoint) {
    return await apiRequest(endpoint, { method: 'DELETE' });
}

// ===== AUTH API =====
const AuthAPI = {
    async login(username, password) {
        return await apiPost('/auth/login.php', { username, password });
    },
    
    async logout() {
        return await apiPost('/auth/logout.php', {});
    },
    
    async getSession() {
        return await apiGet('/auth/session.php');
    }
};

// ===== FINANCES API =====
const FinancesAPI = {
    async getBills(status = 'all') {
        return await apiGet(`/finances/bills.php?status=${status}`);
    },
    
    async createBill(billData) {
        return await apiPost('/finances/bills.php', billData);
    },
    
    async updateBill(id, updates) {
        return await apiPut(`/finances/bills.php?id=${id}`, updates);
    },
    
    async deleteBill(id) {
        return await apiDelete(`/finances/bills.php?id=${id}`);
    },
    
    async getSummary(month = null) {
        const query = month ? `?month=${month}` : '';
        return await apiGet(`/finances/summary.php${query}`);
    }
};

// ===== CHORES API =====
const ChoresAPI = {
    async getChores(status = 'all', week = null) {
        const params = new URLSearchParams({ status });
        if (week) params.append('week', week);
        return await apiGet(`/chores/chores.php?${params}`);
    },
    
    async createChore(choreData) {
        return await apiPost('/chores/chores.php', choreData);
    },
    
    async updateChore(id, updates) {
        return await apiPut(`/chores/chores.php?id=${id}`, updates);
    },
    
    async deleteChore(id) {
        return await apiDelete(`/chores/chores.php?id=${id}`);
    },
    
    async rotateChores() {
        return await apiPost('/chores/rotate.php', {});
    }
};

// ===== MAINTENANCE API =====
const MaintenanceAPI = {
    async getTickets(status = 'all') {
        return await apiGet(`/maintenance/tickets.php?status=${status}`);
    },
    
    async createTicket(ticketData) {
        return await apiPost('/maintenance/tickets.php', ticketData);
    },
    
    async updateTicket(id, updates) {
        return await apiPut(`/maintenance/tickets.php?id=${id}`, updates);
    },
    
    async deleteTicket(id) {
        return await apiDelete(`/maintenance/tickets.php?id=${id}`);
    }
};

// ===== SHOPPING API =====
const ShoppingAPI = {
    async getItems(status = 'all') {
        return await apiGet(`/shopping/items.php?status=${status}`);
    },
    
    async createItem(itemData) {
        return await apiPost('/shopping/items.php', itemData);
    },
    
    async updateItem(id, updates) {
        return await apiPut(`/shopping/items.php?id=${id}`, updates);
    },
    
    async deleteItem(id) {
        return await apiDelete(`/shopping/items.php?id=${id}`);
    }
};

// ===== USERS API =====
const UsersAPI = {
    async getUsers(includeInactive = false) {
        const query = includeInactive ? '?include_inactive=true' : '';
        return await apiGet(`/users/users.php${query}`);
    },
    
    async createUser(userData) {
        return await apiPost('/users/users.php', userData);
    },
    
    async updateUser(id, updates) {
        return await apiPut(`/users/users.php?id=${id}`, updates);
    },
    
    async deleteUser(id) {
        return await apiDelete(`/users/users.php?id=${id}`);
    }
};

// ===== ANNOUNCEMENTS API =====
const AnnouncementsAPI = {
    async getAnnouncements() {
        return await apiGet('/announcements/announcements.php');
    },
    
    async createAnnouncement(announcementData) {
        return await apiPost('/announcements/announcements.php', announcementData);
    },
    
    async updateAnnouncement(id, updates) {
        return await apiPut(`/announcements/announcements.php?id=${id}`, updates);
    },
    
    async deleteAnnouncement(id) {
        return await apiDelete(`/announcements/announcements.php?id=${id}`);
    },
    
    async addReaction(announcementId, reaction) {
        return await apiPost('/announcements/reactions.php', {
            announcement_id: announcementId,
            reaction: reaction
        });
    }
};

// ===== LOADING STATE HELPERS =====
function showLoadingState(element, message = 'Loading...') {
    if (!element) return;
    
    element.innerHTML = `
        <div class="loading-state">
            <div class="spinner"></div>
            <p>${message}</p>
        </div>
    `;
}

function showErrorState(element, message = 'An error occurred') {
    if (!element) return;
    
    element.innerHTML = `
        <div class="error-state">
            <p class="error-icon">⚠️</p>
            <p>${message}</p>
            <button onclick="location.reload()" class="btn-secondary">Retry</button>
        </div>
    `;
}

function showEmptyState(element, message = 'No items found', icon = '📭') {
    if (!element) return;
    
    element.innerHTML = `
        <div class="empty-state">
            <p class="empty-icon">${icon}</p>
            <p>${message}</p>
        </div>
    `;
}

// Export for global use
if (typeof window !== 'undefined') {
    window.API_BASE_URL = API_BASE_URL;
    window.apiRequest = apiRequest;
    window.apiGet = apiGet;
    window.apiPost = apiPost;
    window.apiPut = apiPut;
    window.apiDelete = apiDelete;
    window.AuthAPI = AuthAPI;
    window.FinancesAPI = FinancesAPI;
    window.ChoresAPI = ChoresAPI;
    window.MaintenanceAPI = MaintenanceAPI;
    window.ShoppingAPI = ShoppingAPI;
    window.UsersAPI = UsersAPI;
    window.AnnouncementsAPI = AnnouncementsAPI;
    window.showLoadingState = showLoadingState;
    window.showErrorState = showErrorState;
    window.showEmptyState = showEmptyState;
}
