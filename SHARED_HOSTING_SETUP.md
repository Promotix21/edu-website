# EDU Career India - Shared Hosting Deployment Guide

## Prerequisites
- Shared hosting with PHP 8.0+ and MySQL 5.7+
- FTP/SFTP access or cPanel File Manager
- phpMyAdmin or database access
- Your domain pointed to hosting

## Step 1: Upload Files

### Files to Upload (via FTP or cPanel File Manager):

Upload all files EXCEPT:
- ❌ `docker-compose.yml`
- ❌ `Dockerfile`
- ❌ `*.backup` files
- ❌ `.git` folder (optional, but recommended to exclude)
- ❌ `SHARED_HOSTING_SETUP.md` (this file)

### Upload to public_html or www directory:
```
public_html/
├── admin/
│   ├── includes/
│   ├── assets/
│   ├── media/
│   ├── pages/
│   ├── login.php
│   └── index.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── database/
│   └── init.sql
├── uploads/          (will be created)
├── config.php        ⚠️ MUST CONFIGURE THIS!
├── index.php
├── about.php
├── courses.php
├── universities.php
├── contact.php
├── submit-contact.php
├── robots.txt
├── sitemap.xml
└── .htaccess         ⚠️ CREATE THIS!
```

---

## Step 2: Create Database

### Using phpMyAdmin:

1. **Login to phpMyAdmin** (usually via cPanel)

2. **Create a new database:**
   - Click "Databases" → "Create Database"
   - Name: `yourusername_educareer` (adjust based on your hosting)
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

3. **Create database user:**
   - Go to "MySQL Databases" in cPanel
   - Create user with strong password
   - Add user to database with ALL PRIVILEGES

4. **Import database structure:**
   - Go to phpMyAdmin
   - Select your database
   - Click "Import" tab
   - Choose file: `database/init.sql`
   - Click "Go"

5. **Note your credentials:**
   ```
   Database Host: localhost (usually)
   Database Name: yourusername_educareer
   Database User: yourusername_dbuser
   Database Password: [your password]
   ```

---

## Step 3: Configure config.php ⚠️ CRITICAL

Edit `/config.php` and update these lines:

```php
// ============================================
// DATABASE CONFIGURATION
// ============================================
define('DB_HOST', 'localhost');              // ✓ Change from 'db' to 'localhost'
define('DB_NAME', 'yourusername_educareer'); // ✓ Your actual database name
define('DB_USER', 'yourusername_dbuser');    // ✓ Your database username
define('DB_PASS', 'your_strong_password');   // ✓ Your database password

// ============================================
// SITE CONFIGURATION
// ============================================
define('SITE_URL', 'https://www.yourdomain.com'); // ✓ Your actual domain
define('SITE_EMAIL', 'info@yourdomain.com');      // ✓ Your email

// ============================================
// SECURITY CONFIGURATION (Production)
// ============================================
error_reporting(0);              // ✓ Disable error display
ini_set('display_errors', 0);    // ✓ Don't show errors to visitors
ini_set('log_errors', 1);        // ✓ Log errors instead
```

---

## Step 4: Configure Admin Config

Edit `/admin/includes/config.php` to use the main config:

Replace the database configuration section with:
```php
<?php
// Include main site config
require_once dirname(dirname(__DIR__)) . '/config.php';

// Admin-specific configurations
define('ADMIN_SESSION_NAME', 'educareer_admin');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 1800); // 30 minutes

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_name(ADMIN_SESSION_NAME);
    session_start();
}
```

---

## Step 5: Set Directory Permissions

Set the following permissions using FTP client or cPanel File Manager:

```bash
# Writable directories (755 or 775)
uploads/                    → 755 (rwxr-xr-x)
uploads/hero/              → 755
uploads/services/          → 755
uploads/courses/           → 755
uploads/testimonials/      → 755
uploads/icons/             → 755
uploads/backgrounds/       → 755
uploads/misc/              → 755

# Log file (if exists)
error.log                  → 644 (rw-r--r--)

# Config files (644 - readable but not writable by web)
config.php                 → 644
admin/includes/config.php  → 644

# PHP files (644)
*.php                      → 644

# Directories (755)
All directories/           → 755
```

**Important:** Make sure `uploads/` directory and all subdirectories are writable by the web server.

---

## Step 6: Create .htaccess File

Create `/home/user/edu-website/.htaccess` with the following content:

```apache
# EDU Career India - Apache Configuration for Shared Hosting

# Enable RewriteEngine
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /

# Force HTTPS (uncomment when SSL is active)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Force www or non-www (choose one)
# Force www:
# RewriteCond %{HTTP_HOST} !^www\. [NC]
# RewriteRule ^(.*)$ https://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Remove .php extension (optional - for clean URLs)
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}\.php -f
RewriteRule ^(.*)$ $1.php [L]

# Redirect old .html to .php (if needed)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)\.html$ /$1.php [R=301,L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Disable directory browsing
Options -Indexes

# Protect config files
<FilesMatch "^(config\.php|\.env)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Protect sensitive directories
<DirectoryMatch "^.*/admin/includes">
    Order Allow,Deny
    Deny from all
</DirectoryMatch>

# PHP settings
<IfModule mod_php7.c>
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
    php_value max_execution_time 300
    php_value max_input_time 300
</IfModule>

# Compress files for better performance
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Cache static files
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# Custom error pages (optional)
ErrorDocument 404 /404.php
ErrorDocument 403 /403.php
ErrorDocument 500 /500.php
```

---

## Step 7: Update Frontend PHP Files

Each frontend PHP file needs to include the config. Update the top of these files:

**index.php, about.php, courses.php, universities.php, contact.php:**

Replace the database configuration at the top with:
```php
<?php
// Include main configuration
require_once __DIR__ . '/config.php';

// Rest of the page code...
?>
```

**submit-contact.php:**

Replace the top section with:
```php
<?php
// Include main configuration
require_once __DIR__ . '/config.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/contact.php');
}

// Rest of the code...
```

---

## Step 8: Test Your Website

### 8.1 Test Frontend:
- Visit `https://yourdomain.com`
- Check all pages load correctly
- Verify statistics display (should show database values)
- Check navigation links work

### 8.2 Test Admin Panel:
- Visit `https://yourdomain.com/admin`
- Login with credentials:
  - Username: `admin`
  - Password: `admin123`
- **IMPORTANT:** Change password immediately after first login!

### 8.3 Test Admin Features:
- Upload a test image via Media Management
- Edit homepage content
- Save changes
- Verify changes appear on frontend

### 8.4 Test Contact Form:
- Visit `/contact.php`
- Submit a test inquiry
- Check if submission appears in admin panel

---

## Step 9: Post-Deployment Checklist

- [ ] Database connected successfully
- [ ] All pages load without errors
- [ ] Admin panel accessible
- [ ] Can upload images
- [ ] Admin changes appear on frontend
- [ ] Contact form works and saves to database
- [ ] Changed default admin password
- [ ] Enabled HTTPS (if available)
- [ ] Created database backups
- [ ] Set up cron job for backups (optional)

---

## Step 10: Security Hardening

### 10.1 Change Default Admin Password:
1. Login to admin panel
2. Go to Settings (create this page or use phpMyAdmin)
3. Update password in `admin_users` table

### 10.2 Protect admin directory (additional security):

Add `/admin/.htaccess`:
```apache
# Additional admin protection
AuthType Basic
AuthName "Admin Area"
AuthUserFile /full/path/to/.htpasswd
Require valid-user

# Or restrict by IP address:
# Order Deny,Allow
# Deny from all
# Allow from YOUR.IP.ADDRESS.HERE
```

### 10.3 Enable SSL/HTTPS:
- Get free SSL certificate (cPanel AutoSSL or Let's Encrypt)
- Update `config.php` → `SITE_URL` to use `https://`
- Uncomment HTTPS redirect in `.htaccess`

### 10.4 Regular Backups:
- Set up automatic database backups (cPanel Backup)
- Backup files regularly
- Store backups off-site

---

## Troubleshooting Common Issues

### Issue: "Database connection failed"
**Solution:**
- Check `config.php` credentials
- Verify database exists in phpMyAdmin
- Check if user has correct privileges
- Try `127.0.0.1` instead of `localhost`

### Issue: "404 Not Found" for pages
**Solution:**
- Check if `.htaccess` exists and is uploaded
- Verify mod_rewrite is enabled (contact hosting support)
- Check file permissions (should be 644 for PHP files)

### Issue: "500 Internal Server Error"
**Solution:**
- Check error.log file
- Verify PHP version is 8.0+ (check cPanel → PHP Selector)
- Check file permissions
- Temporarily enable error display in config.php

### Issue: Images not uploading
**Solution:**
- Check uploads directory permissions (should be 755 or 775)
- Check PHP upload limits in cPanel
- Verify disk space available

### Issue: Changes in admin don't appear on frontend
**Solution:**
- Clear browser cache
- Check if pages are using config.php correctly
- Verify database connection in frontend pages

---

## Important Files Summary

| File | Purpose | Must Configure? |
|------|---------|----------------|
| `config.php` | Main configuration | ✅ YES - Database credentials |
| `.htaccess` | Apache settings | ✅ YES - Create this file |
| `admin/includes/config.php` | Admin config | ✅ YES - Update to use main config |
| `database/init.sql` | Database structure | ⚠️ Import once |
| `robots.txt` | SEO | ✅ Update SITE_URL |
| `sitemap.xml` | SEO | ✅ Update domain |

---

## Support

If you encounter issues:
1. Check error.log file
2. Review this guide step by step
3. Contact your hosting support for server-specific issues
4. Verify PHP version and extensions are correct

---

## Default Credentials

**Admin Panel:**
- URL: `https://yourdomain.com/admin`
- Username: `admin`
- Password: `admin123`

**⚠️ CHANGE THESE IMMEDIATELY AFTER FIRST LOGIN!**

---

## Database Connection Examples

### Example 1: Standard cPanel Hosting
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'username_educareer');
define('DB_USER', 'username_dbuser');
define('DB_PASS', 'Str0ng!P@ssw0rd');
```

### Example 2: Remote Database
```php
define('DB_HOST', 'mysql.yourdomain.com');
define('DB_NAME', 'educareer_db');
define('DB_USER', 'remote_user');
define('DB_PASS', 'Your_Password_Here');
```

### Example 3: 127.0.0.1 (if localhost doesn't work)
```php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'username_educareer');
define('DB_USER', 'username_dbuser');
define('DB_PASS', 'your_password');
```

---

Good luck with your deployment! 🚀
