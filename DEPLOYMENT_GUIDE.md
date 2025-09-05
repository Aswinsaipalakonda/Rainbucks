# 🚀 Rainbucks Website Deployment Guide

This guide will help you deploy the Rainbucks Company website to a live server.

## 📋 Pre-Deployment Checklist

### Required Files for Deployment
- ✅ All PHP files and folders
- ✅ `deployment_database.sql` (complete database structure)
- ✅ `public/` folder with all assets
- ✅ `admin/` folder with admin panel
- ✅ `includes/` folder with database connections
- ✅ `assets/` folder (will be created for uploads)

## 🗄️ Database Setup

### Step 1: Create Database
1. **Login to your hosting control panel** (cPanel, Plesk, etc.)
2. **Go to MySQL Databases** or **Database Manager**
3. **Create a new database**:
   - Database name: `rainbucks_db` (or your preferred name)
   - Note down the database name

### Step 2: Create Database User
1. **Create a database user**:
   - Username: `rainbucks_user` (or your preferred name)
   - Password: Generate a strong password
   - Grant ALL privileges to the database

### Step 3: Import Database
1. **Go to phpMyAdmin** in your hosting control panel
2. **Select your database**
3. **Click "Import" tab**
4. **Choose file**: Upload `deployment_database.sql`
5. **Click "Go"** to import

## ⚙️ File Configuration

### Step 1: Update Database Connection
Edit `includes/db.php`:

```php
// Update these values with your hosting details
$host = 'localhost'; // Usually 'localhost'
$username = 'your_db_username'; // Your database username
$password = 'your_db_password'; // Your database password
$database = 'your_db_name'; // Your database name
```

### Step 2: Create Upload Directories
Create these folders with write permissions (755):
```
assets/
├── images/
    ├── packages/
    ├── courses/
    ├── testimonials/
    └── content/
```

## 📁 File Upload Process

### Option 1: FTP/SFTP Upload
1. **Connect to your server** using FTP client (FileZilla, WinSCP)
2. **Upload all files** to your domain's public folder:
   - Usually: `public_html/` or `www/` or `htdocs/`
3. **Set permissions**:
   - Files: 644
   - Folders: 755
   - Upload folders: 755 or 777

### Option 2: File Manager (cPanel)
1. **Open File Manager** in cPanel
2. **Navigate to public_html**
3. **Upload and extract** your website files
4. **Set proper permissions**

## 🔐 Security Configuration

### Step 1: Change Admin Credentials
**Default credentials** (CHANGE IMMEDIATELY):
- Email: `admin@rainbucks.com`
- Password: `admin123`

**To change**:
1. Login to admin panel: `yourdomain.com/admin/login.php`
2. Go to user management (or use database)
3. Update email and password

### Step 2: Secure Database Credentials
Consider using environment variables or a separate config file:

```php
// Example: config.php (outside public folder)
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
?>
```

## 🌐 Domain Configuration

### Step 1: DNS Settings
- Point your domain to your hosting server
- Wait for DNS propagation (24-48 hours)

### Step 2: SSL Certificate
1. **Install SSL certificate** through hosting control panel
2. **Force HTTPS** by adding to `.htaccess`:

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## 🧪 Testing After Deployment

### Step 1: Basic Functionality
- [ ] Website loads correctly
- [ ] All images display properly
- [ ] Navigation works
- [ ] Contact forms work

### Step 2: Admin Panel Testing
- [ ] Admin login works
- [ ] Can add/edit packages
- [ ] Can add/edit courses
- [ ] Can add/edit testimonials
- [ ] Image uploads work
- [ ] Changes reflect on public site

### Step 3: Performance Testing
- [ ] Page load speed
- [ ] Mobile responsiveness
- [ ] Cross-browser compatibility

## 🔧 Common Issues & Solutions

### Issue 1: Database Connection Error
**Solution**: Check database credentials in `includes/db.php`

### Issue 2: Image Upload Fails
**Solutions**:
- Check folder permissions (755 or 777)
- Verify PHP upload settings
- Check available disk space

### Issue 3: Admin Panel Not Accessible
**Solutions**:
- Check file permissions
- Verify database tables exist
- Check error logs

### Issue 4: Broken Images/CSS
**Solutions**:
- Check file paths in code
- Verify all files uploaded correctly
- Check .htaccess rules

## 📊 Performance Optimization

### Step 1: Enable Compression
Add to `.htaccess`:
```apache
# Enable Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### Step 2: Browser Caching
Add to `.htaccess`:
```apache
# Browser Caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"
    ExpiresByType application/x-shockwave-flash "access plus 1 month"
    ExpiresByType image/x-icon "access plus 1 year"
    ExpiresDefault "access plus 2 days"
</IfModule>
```

## 🔄 Maintenance

### Regular Tasks
- [ ] **Database backups** (weekly)
- [ ] **File backups** (weekly)
- [ ] **Update admin password** (monthly)
- [ ] **Check error logs** (weekly)
- [ ] **Monitor disk space** (monthly)

### Updates
- [ ] **PHP version** updates
- [ ] **Security patches**
- [ ] **Content updates**
- [ ] **Feature additions**

## 📞 Support

### Hosting Requirements
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Extensions**: PDO, GD, mbstring
- **Memory**: 128MB minimum
- **Storage**: 1GB minimum

### Backup Strategy
1. **Database**: Export via phpMyAdmin weekly
2. **Files**: Download via FTP monthly
3. **Images**: Backup upload folders regularly

---

## 🎯 Quick Deployment Summary

1. **Create database** and import `deployment_database.sql`
2. **Upload all files** to your hosting
3. **Update** `includes/db.php` with your database details
4. **Create upload folders** with proper permissions
5. **Test admin login** and functionality
6. **Change default admin password**
7. **Install SSL certificate**
8. **Test everything thoroughly**

**Your website should now be live and fully functional!**

For any issues, check the error logs in your hosting control panel or contact your hosting provider's support team.
