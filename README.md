# SmartShare — Minimal Role-Based PHP UI

This is a minimal, responsive PHP app scaffold for the SmartShare project.

Features:
- Role-based dashboards for `admin`, `roommate`, `landlord`, and `maintenance`.
- Uses the existing `smartshare_db` MySQL database (see `PROJECT_DESCRIPTION.txt` for schema).
- Bootstrap UI and a simple 60/30/10 color scheme (`assets/css/styles.css`).

Quick start (XAMPP):

1. Place this folder under `c:/xampp/htdocs/SmartShare` (already here).
2. Ensure MySQL has the `smartshare_db` database (import `backend/migrations/001_create_tables.sql` if needed).
3. Update DB credentials in `config.php` if your MySQL root password isn't empty.
4. Start Apache + MySQL in XAMPP.
5. Open your browser: `http://localhost/SmartShare/index.php` (or `http://localhost:8080/SmartShare/` if Apache uses 8080).

Demo login:
- Use existing usernames from your `users` table. Demo password for all accounts: `password123`.

Notes:
- Authentication is simplistic for demo purposes. For production, implement hashed passwords and secure session handling.
- The scaffold is intentionally minimal; pages contain placeholders and simple read-only views.
