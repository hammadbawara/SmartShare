# Smart-Share Project Context

## Project Overview

**Smart-Share** is a Pakistani household management system designed to help roommates and family members manage shared responsibilities, finances, and household operations. The application provides a comprehensive solution for coordinating finances, chores, maintenance requests, shopping lists, and household announcements.

**Current Status:** UI/Frontend Implementation Only - Backend is not yet implemented.

---

## Project Structure

```
Smart-Share/
├── index.html                    # Main landing/dashboard page
├── css/                          # Styling files
│   ├── styles.css              # Global styles
│   ├── login.css               # Login page styling
│   ├── admin-dashboard.css     # Admin dashboard styling
│   ├── finances.css            # Finances page styling
│   ├── chores.css              # Chores page styling
│   ├── maintenance.css         # Maintenance page styling
│   ├── shopping.css            # Shopping list styling
│   ├── users.css               # User management styling
│   ├── announcements.css       # Announcements styling
│   └── guest.css               # Guest view styling
├── js/                           # JavaScript files
│   ├── app.js                  # Main application logic
│   ├── login.js                # Authentication & login logic
│   ├── finances.js             # Finances page logic
│   ├── chores.js               # Chores management logic
│   ├── maintenance.js          # Maintenance tickets logic
│   ├── shopping.js             # Shopping list logic
│   ├── users.js                # User management logic
│   ├── announcements.js        # Announcements logic
│   ├── guest.js                # Guest view logic
│   └── navigation.js           # Navigation utilities
├── pages/                        # HTML pages
│   ├── login.html              # Login page
│   ├── admin-dashboard.html    # Admin dashboard
│   ├── roommate-dashboard.html # Roommate dashboard
│   ├── landlord-dashboard.html # Landlord dashboard
│   ├── maintenance-dashboard.html # Maintenance staff dashboard
│   ├── finances.html           # Finances management
│   ├── chores.html             # Chore schedule & management
│   ├── maintenance.html        # Maintenance requests & tickets
│   ├── shopping.html           # Shared shopping list
│   ├── users.html              # User management
│   ├── announcements.html      # Household announcements
│   ├── guest-view.html         # Guest access view
│   └── guest.html              # Guest login page
└── templates/                    # HTML components
    └── SIDEBAR-TEMPLATE.html   # Sidebar navigation template
```

---

## Key Features

### 1. **Authentication & User Management** 🔐
- **Login System** (`pages/login.html`, `js/login.js`)
  - Session-based authentication using `sessionStorage`
  - Role-based access control
  - Predefined user accounts with credentials displayed on login page
  - Password visibility toggle
  - Loading states and error handling

- **User Roles:**
  - **House Admin:** Full access to all features, manages users and finances
  - **Roommate:** Access to finances, chores, maintenance, shopping, and announcements
  - **Landlord:** View maintenance requests, access announcements
  - **Maintenance Staff:** Specialized view for maintenance tickets

### 2. **Dashboard Views** 📊
- **Admin Dashboard** (`pages/admin-dashboard.html`)
  - Welcome message with date display
  - Quick statistics: Monthly expenses, pending chores, maintenance issues, active members
  - Recent finances summary
  - Pending chores overview
  - Active maintenance tickets
  - Latest announcements
  - Quick action links to main features

- **Role-Based Dashboards:**
  - `roommate-dashboard.html` - Simplified view for roommates
  - `landlord-dashboard.html` - Landlord-specific information
  - `maintenance-dashboard.html` - Maintenance staff ticket focus

### 3. **Finances Management** 💰
- **File:** `pages/finances.html`, `js/finances.js`, `css/finances.css`
- **Features:**
  - Track monthly bills and shared expenses
  - Financial summary (total expenses, user share, outstanding amounts)
  - Bill categories: Rent, Electricity, Internet, Gas Bill
  - Add new bills
  - Mark bills as paid/unpaid
  - Filter by status (all, paid, unpaid)
  - Visual indicators for payment status
  - Cost splitting among roommates
  - Outstanding payments tracking

### 4. **Chore Management** ✓
- **File:** `pages/chores.html`, `js/chores.js`, `css/chores.css`
- **Features:**
  - Chore schedule with weekly view
  - Rotating chore assignments among roommates
  - Completion rate statistics
  - Mark chores as complete/incomplete
  - Filter chores by status (all, pending, completed)
  - Previous/next week navigation
  - Add new chores to schedule
  - Automatic chore rotation
  - Pending tasks counter

### 5. **Maintenance Ticketing System** 🔧
- **File:** `pages/maintenance.html`, `js/maintenance.js`, `css/maintenance.css`
- **Features:**
  - Create maintenance request tickets
  - Track ticket status (open, in-progress, completed)
  - Priority levels
  - Assigned to maintenance staff
  - Detailed ticket information
  - Filter tickets by status
  - View ticket history and updates
  - Multiple issue categories

### 6. **Shopping List** 🛒
- **File:** `pages/shopping.html`, `js/shopping.js`, `css/shopping.css`
- **Features:**
  - Shared shopping list management
  - Quick add items form
  - Add detailed items with categories
  - Check off purchased items
  - Claim items (user assignment)
  - Delete items from list
  - Item filtering
  - Category organization

### 7. **User Management** 👥
- **File:** `pages/users.html`, `js/users.js`, `css/users.css`
- **Features:**
  - Admin-only page for managing household members
  - Add new roommates
  - Edit user information
  - View user activity
  - Remove users from household
  - Display member statistics
  - User roles and access levels
  - Member lease information

### 8. **Announcements** 📢
- **File:** `pages/announcements.html`, `js/announcements.js`, `css/announcements.css`
- **Features:**
  - Post household announcements
  - View all announcements
  - Emoji reactions to announcements
  - Timestamp for each announcement
  - Important/pinned announcements
  - Accessibility for all roles (admin, roommate, landlord)

### 9. **Guest Access** 🔓
- **File:** `pages/guest-view.html`, `pages/guest.html`, `js/guest.js`, `css/guest.css`
- **Features:**
  - Public guest view without authentication
  - WiFi password sharing
  - Copy to clipboard functionality
  - Guest information display
  - Fallback copy method for browsers without clipboard API

---

## Core JavaScript Files & Functions

### `js/app.js` - Main Application Logic
**Key Functions:**
- `checkAuthentication()` - Validates user session, redirects to login if needed
- `initializeApp()` - Initializes app, loads theme, sets up event listeners
- `updateUserDisplay()` - Updates user info in header/sidebar
- `setupEventListeners()` - Sets up global event handlers
- `switchRole(role)` - Changes user role for testing different views
- `updateNavigationForRole(role)` - Shows/hides nav items based on role
- `updateActiveNavItem()` - Highlights current page in navigation
- `toggleDarkMode()` - Switches between light/dark themes
- `loadThemePreference()` - Loads saved theme from localStorage
- `openModal(modalId)` - Opens dialog/modal windows
- `closeModal(modalId)` - Closes modal windows
- `confirmLogout()` - Logs out user and clears session

**Global State:**
- `currentUser` - Currently logged-in user object
- `darkMode` - Dark mode toggle state

### `js/login.js` - Authentication Logic
**Key Functions:**
- `setupLoginForm()` - Initializes login form listeners
- `handleLogin()` - Authenticates user with credentials
- `setupCredentialCards()` - Displays available demo accounts
- `togglePasswordVisibility()` - Shows/hides password
- `showLoading()` / `hideLoading()` - Loading state animations
- `shakeForm()` - Error animation for failed login
- `showNotification()` - Toast notifications

**Demo Credentials:**
- House Admin: `admin` / `password123`
- Roommate: `roommate1` / `password123`
- Landlord: `landlord` / `password123`
- Maintenance: `maintenance` / `password123`

### `js/finances.js` - Finances Page Logic
**Key Functions:**
- `setupFilterTabs()` - Sets up filter buttons (all, paid, unpaid)
- `setupBillForm()` - Initializes bill addition form
- `markAsPaid(button)` - Marks bill as paid/unpaid
- `updateFinancialSummary()` - Recalculates summary totals

### `js/chores.js` - Chore Management Logic
**Key Functions:**
- `setupFilterTabs()` - Filter by status
- `filterChores(filter)` - Applies filter to chore list
- `completeChore(choreId)` - Marks chore as complete/incomplete
- `updateChoreStats()` - Updates completion rate statistics
- `rotateChores()` - Rotates chore assignments
- `previousWeek()` / `nextWeek()` - Navigate weeks
- `updateWeekDisplay()` - Updates week display
- `setupChoreForm()` - Initializes form for adding chores

### `js/maintenance.js` - Maintenance System Logic
**Key Functions:**
- `setupFilterTabs()` - Filter tickets by status
- `setupNewTicketForm()` - Form for creating tickets
- `setupUpdateStatusForm()` - Form for status updates
- `viewTicketDetails(ticketId)` - Shows ticket details
- `updateTicketStatus(ticketId)` - Updates ticket status

### `js/shopping.js` - Shopping List Logic
**Key Functions:**
- `setupQuickAddForm()` - Quick item entry form
- `setupAddItemForm()` - Detailed item form
- `setupItemCheckboxes()` - Item completion tracking
- `claimItem(itemId)` - Assigns item to user
- `deleteItem(itemId)` - Removes item from list

### `js/users.js` - User Management Logic
**Key Functions:**
- `setupAddUserForm()` - Form for adding new users
- `editUser(userId)` - Edit user information
- `viewActivity(userId)` - View user activity log
- `removeUser(userId)` - Delete user from system

### `js/announcements.js` - Announcements Logic
**Key Functions:**
- `setupAnnouncementForm()` - Form for posting announcements
- `setupReactions()` - Emoji reaction system

### `js/guest.js` - Guest View Logic
**Key Functions:**
- `togglePassword()` - Show/hide WiFi password
- `copyToClipboard(text)` - Modern copy API
- `fallbackCopy(text)` - Fallback for older browsers

---

## Navigation Structure

### Sidebar Navigation
Present on all authenticated pages with dynamic content based on user role:

**Common to All Roles:**
- 📊 Dashboard
- 🔧 Maintenance
- 🔓 Guest View (public access)

**Admin & Roommate Only:**
- 💰 Finances
- ✓ Chores
- 🛒 Shopping List
- 📢 Announcements

**Admin Only:**
- 👥 User Management

**Additional Roles:**
- Landlord: Maintenance + Announcements
- Maintenance Staff: Maintenance + Announcements

### Page Relationships
```
index.html (Main Dashboard)
    ├── pages/finances.html
    ├── pages/chores.html
    ├── pages/maintenance.html
    ├── pages/shopping.html
    ├── pages/users.html (Admin only)
    ├── pages/announcements.html
    ├── pages/admin-dashboard.html
    ├── pages/roommate-dashboard.html
    ├── pages/landlord-dashboard.html
    ├── pages/maintenance-dashboard.html
    ├── pages/guest-view.html (Public)
    └── pages/guest.html (Public)
```

---

## Styling System

### CSS Structure
- **styles.css** - Global styles, variables, layout framework
- **Role-specific CSS** - Dedicated stylesheets for pages

### Color Palette & Variables (from styles.css)
- Primary colors: Blue variants
- Secondary colors: Green (success), Red (danger), Orange (warning), Purple (info)
- Background colors: Light and dark modes
- Spacing system: Consistent margins/padding

### Responsive Design
- Mobile-first approach
- Breakpoints for tablets and desktops
- Flexible grid layouts
- Touch-friendly buttons and interactions

---

## Data Storage & Session Management

### Session Storage (Current Implementation)
- **Key:** `currentUser`
- **Value:** JSON object containing:
  ```javascript
  {
    id: "user-id",
    fullName: "User Name",
    email: "user@example.com",
    role: "admin|roommate|landlord|maintenance",
    avatar: "initials"
  }
  ```

### Local Storage
- **darkMode:** Theme preference (true/false)

### Notes on Backend Integration
- All data is currently hardcoded in HTML/JavaScript
- No API calls or backend database
- Session data resets on page refresh
- Ready to integrate with backend API once available

---

## UI Components & Patterns

### Common Components
- **Modals/Dialogs** - For forms and confirmations
- **Cards** - Statistics, listings, information blocks
- **Badges** - Status indicators, counters
- **Buttons** - Primary (primary action), Secondary (alternate action), Icon buttons
- **Forms** - Input validation, required fields
- **Notifications** - Toast messages for feedback

### Status Indicators
- Color-coded badges for status (pending, completed, paid, unpaid)
- Progress bars for metrics
- Icons with text labels

---

## Authentication Flow

1. **Login Page** (`pages/login.html`)
   - User enters credentials
   - Validates against demo accounts
   - Stores user info in sessionStorage
   
2. **Session Validation** (in `app.js`)
   - Checks sessionStorage for `currentUser`
   - Redirects to login if not authenticated
   - Public pages bypass authentication

3. **Public Pages** (No auth required)
   - `pages/login.html`
   - `pages/guest-view.html`
   - `pages/guest.html`

---

## Role-Based Access Control

### Permission Matrix

| Feature | Admin | Roommate | Landlord | Maintenance |
|---------|-------|----------|----------|-------------|
| Dashboard | ✓ | ✓ | ✗ | ✗ |
| Finances | ✓ | ✓ | ✗ | ✗ |
| Chores | ✓ | ✓ | ✗ | ✗ |
| Maintenance | ✓ | ✓ | ✓ | ✓ |
| Shopping | ✓ | ✓ | ✗ | ✗ |
| Users | ✓ | ✗ | ✗ | ✗ |
| Announcements | ✓ | ✓ | ✓ | ✗ |
| Guest View | ✓ | ✓ | ✓ | ✓ |

---

## Known Limitations (Frontend Only)

1. **No Data Persistence**
   - All data resets on page refresh
   - No database backend
   - Changes not saved

2. **No Real Authentication**
   - Demo accounts only
   - No password hashing or validation
   - No session expiration

3. **No Real-Time Features**
   - No notifications
   - No live updates
   - No multi-user synchronization

4. **Mock Data**
   - Financial transactions are hardcoded
   - Chore assignments are simulated
   - User activity logs are static

---

## Future Backend Integration Points

When implementing the backend, the following will need API integration:

### APIs to Implement
1. **Authentication**
   - POST `/api/auth/login`
   - POST `/api/auth/logout`
   - GET `/api/auth/me` (current user)

2. **Finances**
   - GET `/api/finances/summary`
   - GET `/api/bills`
   - POST `/api/bills`
   - PUT `/api/bills/:id/status`

3. **Chores**
   - GET `/api/chores`
   - POST `/api/chores`
   - PUT `/api/chores/:id/status`
   - POST `/api/chores/rotate`

4. **Maintenance**
   - GET `/api/maintenance/tickets`
   - POST `/api/maintenance/tickets`
   - PUT `/api/maintenance/tickets/:id/status`

5. **Shopping**
   - GET `/api/shopping/items`
   - POST `/api/shopping/items`
   - PUT `/api/shopping/items/:id`
   - DELETE `/api/shopping/items/:id`

6. **Users**
   - GET `/api/users`
   - POST `/api/users`
   - PUT `/api/users/:id`
   - DELETE `/api/users/:id`

7. **Announcements**
   - GET `/api/announcements`
   - POST `/api/announcements`
   - POST `/api/announcements/:id/reactions`

---

## Development Notes

### How to Test Different Roles
1. Go to login page
2. Select from demo credentials displayed
3. Click "Sign In"
4. Navigation updates based on role

### Theme Toggle
- Click moon icon (🌙) in sidebar footer
- Preference saved to localStorage
- Persists across sessions

### Mobile Responsiveness
- Sidebar collapses on smaller screens
- Touch-friendly interface
- Responsive grid layouts

---

## Technology Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript (ES6+)
- **Storage:** SessionStorage (temporary), LocalStorage (persistent)
- **Fonts:** Google Fonts (Inter)
- **Icons:** Unicode emoji
- **No external frameworks or libraries** (lightweight implementation)

---

## File Purpose Summary

| File | Purpose |
|------|---------|
| `index.html` | Main landing page & dashboard hub |
| `pages/login.html` | User authentication entry point |
| `pages/admin-dashboard.html` | Admin role dashboard with overview |
| `pages/finances.html` | Expense tracking & bill management |
| `pages/chores.html` | Chore schedule & assignment management |
| `pages/maintenance.html` | Maintenance request ticketing system |
| `pages/shopping.html` | Shared shopping list management |
| `pages/users.html` | Household member management (admin only) |
| `pages/announcements.html` | Household announcements & updates |
| `pages/guest-view.html` | Public guest access view |
| `js/app.js` | Core app logic, auth, navigation |
| `js/login.js` | Login form handling & authentication |
| `js/finances.js` | Finance page interactions |
| `js/chores.js` | Chore management interactions |
| `js/maintenance.js` | Maintenance ticket interactions |
| `js/shopping.js` | Shopping list interactions |
| `js/users.js` | User management interactions |
| `js/announcements.js` | Announcement interactions & reactions |
| `js/guest.js` | Guest view interactions |
| `css/styles.css` | Global styling & layout |
| `css/*.css` | Page-specific styling |

---

## Summary

Smart-Share is a comprehensive household management platform with a complete UI implementation ready for backend integration. The system supports multiple user roles with appropriate access controls, manages household finances, chores, maintenance, shopping, and communications. The clean separation of concerns between HTML (structure), CSS (styling), and JavaScript (logic) makes it maintainable and extensible. Once backend APIs are implemented, this frontend is ready to scale to production use.
