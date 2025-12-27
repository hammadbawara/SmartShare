# Smart-Share Backend Setup Guide

## Overview
This guide will help you set up the PHP/MySQL backend for Smart-Share. The backend includes secure authentication, RESTful API endpoints, and database integration.

---

## Prerequisites

Before you begin, ensure you have:

1. **PHP 7.4 or higher** with the following extensions:
   - PDO
   - pdo_mysql
   - mbstring
   - json

2. **MySQL 5.7+ or MariaDB 10.2+**

3. **Web Server** (Apache, Nginx, or PHP built-in server for development)

4. **Composer** (optional, for future dependencies)

---

## Quick Start

### Step 1: Environment Configuration

1. Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Edit `.env` with your database credentials:
   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=smart_share
   DB_USER=root
   DB_PASS=your_password_here
   
   APP_ENV=development
   APP_DEBUG=true
   APP_URL=http://localhost
   ```

### Step 2: Database Setup

1. **Create the database:**
   ```bash
   mysql -u root -p < backend/migrations/001_create_tables.sql
   ```

   Or manually via MySQL client:
   ```sql
   mysql -u root -p
   ```
   Then run the contents of `backend/migrations/001_create_tables.sql`

2. **Seed the database with demo data:**
   ```bash
   mysql -u root -p smart_share < backend/seeds/default_users.sql
   ```

   This will create 5 demo users:
   - **admin** / password123
   - **roommate1** / password123
   - **roommate2** / password123
   - **landlord** / password123
   - **maintenance** / password123

### Step 3: Start the Development Server

#### Option A: PHP Built-in Server (Recommended for Development)
```bash
cd /path/to/Smart Share
php -S localhost:8000
```

Then access the application at: `http://localhost:8000`

#### Option B: Apache/Nginx

Configure your web server to serve the Smart Share directory.

**Apache `.htaccess` (if not already present):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Allow API requests
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^backend/api/(.*)$ backend/api/$1 [L]
</IfModule>
```

### Step 4: Test the Backend

1. **Test Authentication:**
   ```bash
   curl -X POST http://localhost:8000/backend/api/auth/login.php \
     -H "Content-Type: application/json" \
     -d '{"username":"admin","password":"password123"}'
   ```

2. **Test Session Check:**
   ```bash
   curl http://localhost:8000/backend/api/auth/session.php \
     --cookie-jar cookies.txt \
     --cookie cookies.txt
   ```

3. **Test Bills API:**
   ```bash
   curl http://localhost:8000/backend/api/finances/bills.php \
     --cookie cookies.txt
   ```

---

## API Documentation

### Base URL
```
http://localhost:8000/backend/api
```

### Authentication Endpoints

#### POST /auth/login.php
Login user and create session.

**Request Body:**
```json
{
  "username": "admin",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "username": "admin",
      "email": "ahmed@smartshare.com",
      "fullName": "Ahmed Ali",
      "role": "admin",
      "avatar": "AA"
    },
    "csrfToken": "...",
    "sessionId": "..."
  }
}
```

#### POST /auth/logout.php
Logout user and destroy session.

#### GET /auth/session.php
Check current session validity.

---

### Finances Endpoints

#### GET /finances/bills.php
Get all bills with splits.

**Query Parameters:**
- `status` (optional): `all`, `paid`, `unpaid`

#### POST /finances/bills.php
Create new bill (Admin only).

**Request Body:**
```json
{
  "title": "Electricity Bill",
  "amount": 5000,
  "category": "electricity",
  "due_date": "2024-12-31",
  "description": "December electricity"
}
```

#### PUT /finances/bills.php?id={id}
Update bill (mark as paid/unpaid).

**Request Body:**
```json
{
  "is_paid": true
}
```

#### DELETE /finances/bills.php?id={id}
Delete bill (Admin only).

#### GET /finances/summary.php
Get financial summary for the month.

**Query Parameters:**
- `month` (optional): `YYYY-MM` format (default: current month)

---

### Chores Endpoints

#### GET /chores/chores.php
Get chores for the week.

**Query Parameters:**
- `status` (optional): `all`, `completed`, `pending`
- `week` (optional): `YYYY-WW` format (default: current week)

#### POST /chores/chores.php
Create new chore (Admin only).

#### PUT /chores/chores.php?id={id}
Update chore status.

#### DELETE /chores/chores.php?id={id}
Delete chore (Admin only).

#### POST /chores/rotate.php
Rotate chore assignments (Admin only).

---

### Maintenance Endpoints

#### GET /maintenance/tickets.php
Get all maintenance tickets.

**Query Parameters:**
- `status` (optional): `all`, `open`, `in_progress`, `completed`

#### POST /maintenance/tickets.php
Create new maintenance ticket.

#### PUT /maintenance/tickets.php?id={id}
Update ticket status or assignment.

#### DELETE /maintenance/tickets.php?id={id}
Delete ticket (Admin only).

---

### Shopping Endpoints

#### GET /shopping/items.php
Get shopping list items.

#### POST /shopping/items.php
Add new shopping item.

#### PUT /shopping/items.php?id={id}
Update item (mark purchased, claim).

#### DELETE /shopping/items.php?id={id}
Delete shopping item.

---

### Users Endpoints (Admin Only)

#### GET /users/users.php
Get all users.

#### POST /users/users.php
Create new user.

#### PUT /users/users.php?id={id}
Update user information.

#### DELETE /users/users.php?id={id}
Delete/deactivate user.

---

### Announcements Endpoints

#### GET /announcements/announcements.php
Get all announcements with reactions.

#### POST /announcements/announcements.php
Create new announcement.

#### PUT /announcements/announcements.php?id={id}
Update announcement.

#### DELETE /announcements/announcements.php?id={id}
Delete announcement.

#### POST /announcements/reactions.php
Add or update reaction to announcement.

**Request Body:**
```json
{
  "announcement_id": 1,
  "reaction": "👍"
}
```

---

## Response Format

All API endpoints return JSON in this format:

**Success Response:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

**HTTP Status Codes:**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests (Rate Limiting)
- `500` - Internal Server Error

---

## Security Features

### Authentication
- Bcrypt password hashing with cost factor 12
- Secure PHP sessions with httponly cookies
- Session timeout after 2 hours of inactivity
- Rate limiting on login (5 attempts per 5 minutes)

### CSRF Protection
- CSRF tokens generated for all authenticated sessions
- Token validation on state-changing operations

### SQL Injection Prevention
- PDO prepared statements for all database queries
- Parameterized queries throughout

### XSS Prevention
- HTML entity encoding on all user input
- Content-Type headers set correctly

### Session Security
- Session ID regeneration on login
- Secure cookie flags in production
- SameSite cookie attribute
- Single session per user (old sessions destroyed on login)

---

## Database Schema

### Tables Overview

1. **users** - User accounts and authentication
2. **sessions** - Active user sessions
3. **bills** - Household bills
4. **bill_splits** - Bill payment splits per user
5. **chores** - Chore assignments and tracking
6. **maintenance_tickets** - Maintenance requests
7. **shopping_items** - Shared shopping list
8. **announcements** - Household announcements
9. **announcement_reactions** - User reactions to announcements
10. **activity_log** - Audit trail of user actions

All tables use UTF-8 encoding (utf8mb4) and include proper foreign key constraints and indexes for performance.

---

## Troubleshooting

### Database Connection Issues

**Error: "Database connection failed"**

1. Check `.env` file exists and has correct credentials
2. Ensure MySQL is running: `sudo systemctl status mysql`
3. Test connection:
   ```bash
   mysql -u root -p -e "SHOW DATABASES;"
   ```

### Permission Issues

**Error: "Access denied"**

1. Grant proper permissions:
   ```sql
   GRANT ALL PRIVILEGES ON smart_share.* TO 'root'@'localhost';
   FLUSH PRIVILEGES;
   ```

### Session Issues

**Error: "Session expired"**

1. Check PHP session configuration:
   ```bash
   php -i | grep session
   ```

2. Ensure session directory is writable:
   ```bash
   sudo chmod 777 /var/lib/php/sessions
   ```

### CORS Issues

**Error: "CORS policy blocked"**

1. Update `APP_URL` in `.env` to match your frontend URL
2. CORS headers are automatically set in development mode
3. For production, configure proper CORS policies in `backend/utils/response.php`

---

## Next Steps

After backend setup, proceed to update the JavaScript frontend files:

1. **Update js/login.js** - Replace sessionStorage with API calls
2. **Update js/app.js** - Replace session checks with server validation
3. **Update js/finances.js** - Connect to bills API
4. **Update js/chores.js** - Connect to chores API
5. **Update js/maintenance.js** - Connect to maintenance API
6. **Update js/shopping.js** - Connect to shopping API
7. **Update js/users.js** - Connect to users API
8. **Update js/announcements.js** - Connect to announcements API

---

## Production Deployment

For production deployment:

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Use HTTPS for secure cookies
3. Configure proper CORS policies
4. Set up database backups
5. Enable error logging instead of displaying errors
6. Use a process manager like PHP-FPM with Nginx
7. Consider using a reverse proxy
8. Implement rate limiting at the web server level
9. Regular security updates for PHP and MySQL

---

## Support

For issues or questions:
- Check the troubleshooting section
- Review PHP error logs: `tail -f /var/log/php/error.log`
- Review Apache/Nginx error logs
- Check database query logs if needed

---

**Version:** 1.0.0  
**Last Updated:** December 2024
