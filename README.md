# Smart-Share Full-Stack Implementation - Developer Guide

## 🎯 Project Status: Backend Implementation Complete

Smart-Share has been successfully transformed from a frontend-only application to a full-stack PHP/MySQL system with secure authentication, RESTful APIs, and database persistence.

---

## 📁 Project Structure (Updated)

```
Smart-Share/
├── index.html
├── .env.example                 # ✨ NEW - Environment configuration template
├── BACKEND_SETUP.md            # ✨ NEW - Comprehensive setup guide
├── Agents.md                   # Project context and documentation
│
├── backend/                    # ✨ NEW - Complete PHP backend
│   ├── config/
│   │   ├── config.php         # Application configuration
│   │   └── database.php       # PDO MySQL connection
│   │
│   ├── middleware/
│   │   └── auth.php           # Authentication & authorization middleware
│   │
│   ├── utils/
│   │   ├── response.php       # JSON response utilities
│   │   ├── validation.php     # Input validation helpers
│   │   └── security.php       # Security functions (CSRF, hashing, rate limiting)
│   │
│   ├── migrations/
│   │   └── 001_create_tables.sql  # Database schema (10 tables)
│   │
│   ├── seeds/
│   │   └── default_users.sql      # Demo users with hashed passwords
│   │
│   └── api/                   # RESTful API endpoints
│       ├── auth/
│       │   ├── login.php      # POST - User login
│       │   ├── logout.php     # POST - User logout
│       │   └── session.php    # GET - Session validation
│       │
│       ├── finances/
│       │   ├── bills.php      # GET/POST/PUT/DELETE - Bill management
│       │   └── summary.php    # GET - Financial summary
│       │
│       ├── chores/
│       │   ├── chores.php     # GET/POST/PUT/DELETE - Chore management
│       │   └── rotate.php     # POST - Rotate assignments
│       │
│       ├── maintenance/
│       │   └── tickets.php    # GET/POST/PUT/DELETE - Maintenance tickets
│       │
│       ├── shopping/
│       │   └── items.php      # GET/POST/PUT/DELETE - Shopping list
│       │
│       ├── users/
│       │   └── users.php      # GET/POST/PUT/DELETE - User management (Admin)
│       │
│       └── announcements/
│           ├── announcements.php  # GET/POST/PUT/DELETE - Announcements
│           └── reactions.php      # POST - Add/update reactions
│
├── js/
│   ├── app.js                 # ✅ UPDATED - Server-side auth
│   ├── login.js               # ✅ UPDATED - API-based login
│   ├── api-client.js          # ✨ NEW - Reusable API client
│   ├── finances.js            # ⏳ TODO - Connect to API
│   ├── chores.js              # ⏳ TODO - Connect to API
│   ├── maintenance.js         # ⏳ TODO - Connect to API
│   ├── shopping.js            # ⏳ TODO - Connect to API
│   ├── users.js               # ⏳ TODO - Connect to API
│   ├── announcements.js       # ⏳ TODO - Connect to API
│   └── ...
│
├── css/                       # (Unchanged)
├── pages/                     # (Unchanged)
└── templates/                 # (Unchanged)
```

---

## ✅ What Has Been Implemented

### 1. **Backend Infrastructure** ✨
- [x] Complete directory structure (`backend/config`, `backend/api`, `backend/middleware`, etc.)
- [x] Environment configuration with `.env` support
- [x] PDO MySQL database connection with singleton pattern
- [x] Error handling and logging
- [x] Timezone configuration (Asia/Karachi)

### 2. **Database Schema** 🗄️
- [x] 10 core tables with proper relationships:
  - `users` - User accounts and authentication
  - `sessions` - Server-side session management
  - `bills` & `bill_splits` - Financial tracking
  - `chores` - Task assignments
  - `maintenance_tickets` - Issue reporting
  - `shopping_items` - Shared shopping list
  - `announcements` & `announcement_reactions` - Communication
  - `activity_log` - Audit trail
- [x] Foreign keys, indexes, and constraints
- [x] UTF-8 (utf8mb4) encoding

### 3. **Security Implementation** 🔐
- [x] Bcrypt password hashing (cost factor 12)
- [x] Secure PHP sessions with httponly cookies
- [x] CSRF token protection
- [x] Rate limiting on login (5 attempts per 5 minutes)
- [x] Session timeout (2 hours)
- [x] SQL injection prevention via prepared statements
- [x] XSS prevention with HTML entity encoding
- [x] Role-based access control (RBAC)

### 4. **RESTful API Endpoints** 🌐
- [x] **Authentication** (3 endpoints)
  - `POST /auth/login.php` - User login
  - `POST /auth/logout.php` - User logout
  - `GET /auth/session.php` - Session validation
  
- [x] **Finances** (3 endpoints)
  - `GET/POST/PUT/DELETE /finances/bills.php` - Bill CRUD
  - `GET /finances/summary.php` - Financial summary
  
- [x] **Chores** (2 endpoints)
  - `GET/POST/PUT/DELETE /chores/chores.php` - Chore CRUD
  - `POST /chores/rotate.php` - Rotate assignments
  
- [x] **Maintenance** (1 endpoint)
  - `GET/POST/PUT/DELETE /maintenance/tickets.php` - Ticket CRUD
  
- [x] **Shopping** (1 endpoint)
  - `GET/POST/PUT/DELETE /shopping/items.php` - Item CRUD
  
- [x] **Users** (1 endpoint - Admin only)
  - `GET/POST/PUT/DELETE /users/users.php` - User management
  
- [x] **Announcements** (2 endpoints)
  - `GET/POST/PUT/DELETE /announcements/announcements.php` - Announcement CRUD
  - `POST /announcements/reactions.php` - Reactions

### 5. **Middleware & Utilities** 🛠️
- [x] `auth.php` - Authentication middleware with role checking
- [x] `response.php` - Consistent JSON responses
- [x] `validation.php` - Input validation helpers
- [x] `security.php` - Security utilities (CSRF, hashing, rate limiting)

### 6. **JavaScript Refactoring** 🔄
- [x] `api-client.js` - Reusable API wrapper with error handling
- [x] `login.js` - Updated to use backend authentication
- [x] `app.js` - Server-side session validation
- [x] Global API functions exported to `window` object
- ⏳ Other JS files pending (finances, chores, maintenance, shopping, users, announcements)

### 7. **Demo Data** 🎭
- [x] 5 seeded users with bcrypt hashed passwords:
  - **admin** / password123 - Ahmed Ali
  - **roommate1** / password123 - Hassan Khan
  - **roommate2** / password123 - Fatima Noor
  - **landlord** / password123 - Malik Ahmed
  - **maintenance** / password123 - Usman Tariq
- [x] Sample bills, chores, maintenance tickets, shopping items, announcements

---

## 🚀 Getting Started

### Prerequisites
- PHP 7.4+ with PDO, pdo_mysql, mbstring, json
- MySQL 5.7+ or MariaDB 10.2+
- Web server (Apache/Nginx) or PHP built-in server

### Quick Setup

1. **Clone and navigate to project:**
   ```bash
   cd "/mnt/d/Coding/University/Smart Share"
   ```

2. **Create environment file:**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` with your database credentials.

3. **Set up database:**
   ```bash
   mysql -u root -p < backend/migrations/001_create_tables.sql
   mysql -u root -p smart_share < backend/seeds/default_users.sql
   ```

4. **Start development server:**
   ```bash
   php -S localhost:8000
   ```

5. **Access application:**
   - Open http://localhost:8000
   - Login with demo credentials (e.g., `admin` / `password123`)

📖 **For detailed setup instructions, see [BACKEND_SETUP.md](BACKEND_SETUP.md)**

---

## 📚 API Documentation

### Response Format

All endpoints return JSON:

**Success:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

### Status Codes
- `200` - Success
- `201` - Created
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Rate Limit Exceeded
- `500` - Internal Server Error

### Example: Create a Bill

```javascript
const billData = {
    title: "Electricity Bill",
    amount: 5000,
    category: "electricity",
    due_date: "2024-12-31",
    description: "December electricity"
};

const response = await FinancesAPI.createBill(billData);
console.log(response);
```

📖 **For complete API documentation, see [BACKEND_SETUP.md](BACKEND_SETUP.md)**

---

## 🔧 Using the API Client

The `api-client.js` provides easy-to-use API wrappers:

```javascript
// Authentication
await AuthAPI.login(username, password);
await AuthAPI.logout();
const session = await AuthAPI.getSession();

// Finances
const bills = await FinancesAPI.getBills('unpaid');
await FinancesAPI.updateBill(billId, { is_paid: true });
const summary = await FinancesAPI.getSummary('2024-12');

// Chores
const chores = await ChoresAPI.getChores('pending');
await ChoresAPI.updateChore(choreId, { is_completed: true });
await ChoresAPI.rotateChores();

// Maintenance
const tickets = await MaintenanceAPI.getTickets('open');
await MaintenanceAPI.createTicket(ticketData);

// Shopping
const items = await ShoppingAPI.getItems();
await ShoppingAPI.updateItem(itemId, { is_purchased: true });

// Users (Admin only)
const users = await UsersAPI.getUsers();
await UsersAPI.createUser(userData);

// Announcements
const announcements = await AnnouncementsAPI.getAnnouncements();
await AnnouncementsAPI.addReaction(announcementId, '👍');
```

---

## ⏳ Remaining Work

The following JavaScript files need to be refactored to use the API:

1. **js/finances.js** - Replace hardcoded bills with API calls
2. **js/chores.js** - Connect to chores API
3. **js/maintenance.js** - Connect to maintenance API
4. **js/shopping.js** - Connect to shopping API
5. **js/users.js** - Connect to users API
6. **js/announcements.js** - Connect to announcements API

### Refactoring Pattern

For each file, follow this pattern:

```javascript
// 1. Add loading state
showLoadingState(container, 'Loading...');

// 2. Call API
try {
    const response = await <Module>API.get<Resource>();
    
    if (!response.success) {
        showErrorState(container, response.message);
        return;
    }
    
    // 3. Render data
    renderData(response.data);
    
} catch (error) {
    console.error('Error:', error);
    showErrorState(container, 'Failed to load data');
}
```

---

## 🛡️ Security Features

### Authentication Flow
1. User submits credentials via `/auth/login.php`
2. Backend validates credentials (bcrypt password verification)
3. Session created in `sessions` table with user ID
4. Session ID stored in httponly cookie
5. All subsequent requests include session cookie
6. Middleware validates session on each API request

### Authorization
- Role-based access control via `requireRole()` middleware
- Admin-only endpoints return `403 Forbidden` for non-admins
- Users can only modify their own data (announcements, etc.)

### Data Protection
- All inputs sanitized and validated
- Prepared statements prevent SQL injection
- HTML entity encoding prevents XSS
- HTTPS recommended for production

---

## 🧪 Testing

### Manual Testing

1. **Test Authentication:**
   ```bash
   curl -X POST http://localhost:8000/backend/api/auth/login.php \
     -H "Content-Type: application/json" \
     -d '{"username":"admin","password":"password123"}'
   ```

2. **Test with Session:**
   ```bash
   curl http://localhost:8000/backend/api/finances/bills.php \
     --cookie-jar cookies.txt --cookie cookies.txt
   ```

### Browser Testing
1. Open DevTools → Network tab
2. Login with demo credentials
3. Navigate through different pages
4. Verify API calls in Network tab
5. Check Console for errors

---

## 🚀 Deployment to Production

1. **Update Environment:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Secure Configuration:**
   - Use HTTPS
   - Set strong database passwords
   - Configure proper CORS policies
   - Enable error logging (not display)

3. **Database Optimization:**
   - Set up regular backups
   - Optimize indexes
   - Monitor query performance

4. **Web Server:**
   - Use PHP-FPM with Nginx/Apache
   - Configure rate limiting at server level
   - Set up SSL certificates

---

## 📝 Contributing Guidelines

### Code Style
- **PHP:** PSR-12 coding standard
- **JavaScript:** ES6+ features, async/await
- **SQL:** Uppercase keywords, snake_case naming

### Commit Messages
- Use conventional commits format
- Examples:
  - `feat: add shopping list pagination`
  - `fix: resolve session timeout issue`
  - `docs: update API documentation`

### Pull Request Process
1. Create feature branch
2. Implement changes with tests
3. Update documentation
4. Submit PR with clear description

---

## 🐛 Troubleshooting

### Database Connection Failed
- Check `.env` credentials
- Verify MySQL is running: `sudo systemctl status mysql`
- Test connection: `mysql -u root -p`

### Session Issues
- Check PHP session directory permissions
- Verify cookies are enabled in browser
- Clear browser cookies and retry

### CORS Errors
- Update `APP_URL` in `.env`
- Check `setCorsHeaders()` in `response.php`

### API 401 Unauthorized
- Session expired - login again
- Cookie not being sent - check `credentials: 'include'`

---

## 📞 Support

For issues or questions:
- Check [BACKEND_SETUP.md](BACKEND_SETUP.md) troubleshooting section
- Review PHP error logs: `tail -f /var/log/php/error.log`
- Check Apache/Nginx error logs
- Enable `APP_DEBUG=true` for detailed errors

---

## 📄 License

This project is for educational purposes.

---

## 🙏 Acknowledgments

- Built with PHP, MySQL, and Vanilla JavaScript
- Pakistani household management context
- Secure authentication practices
- RESTful API design principles

---

**Version:** 1.0.0  
**Status:** Backend Complete, Frontend Integration In Progress  
**Last Updated:** December 27, 2024

---

## 🎯 Next Steps for Developers

1. ✅ **Backend is complete and tested**
2. ⏳ **Refactor remaining JavaScript files** (finances, chores, maintenance, shopping, users, announcements)
3. ⏳ **Test full application flow** with real API calls
4. ⏳ **Add loading states and error handling** to all pages
5. ⏳ **Implement pagination** for long lists
6. ⏳ **Add real-time updates** (optional - WebSockets/SSE)
7. ⏳ **Deploy to production** server

Happy coding! 🚀
