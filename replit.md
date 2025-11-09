# PHP Portfolio Management System

## Overview

A complete portfolio management system with admin panel and user interface, built with PHP and MySQL. This application provides full CRUD (Create, Read, Update, Delete) operations for managing portfolio content including projects, skills, certificates, gallery images, and contact messages.

### Original Project
This project was converted from a React.js portfolio to a full-featured PHP application with database backend and admin panel.

## User Preferences

Preferred communication style: Simple, everyday language.
Technology preference: PHP with MySQL database for XAMPP server deployment.

## System Architecture

### Application Stack

**Backend Technology:**
- PHP 8.2 with mysqli for database operations
- MySQL database (designed for XAMPP)
- Session-based authentication
- Prepared statements for SQL injection prevention
- Password hashing with bcrypt

**Frontend Technology:**
- HTML5 with embedded PHP
- Custom CSS3 with responsive design
- No JavaScript frameworks (pure HTML/CSS/PHP)

**Database Schema:**
- `admins` - Admin user accounts with enforced password rotation
- `projects` - Portfolio projects with technologies and links
- `skills` - Skills with categories and proficiency levels
- `certificates` - Certifications with issuers and dates
- `gallery` - Image gallery with categories
- `about` - Personal information and bio
- `contact_messages` - Contact form submissions

**Security Features:**
- Random password generation for initial admin account
- Forced password change on first login
- SQL injection prevention via prepared statements
- Password hashing using PHP's password_hash() (bcrypt)
- .htaccess protection for sensitive files
- Session-based authentication
- Exception handling for graceful error recovery

### Application Structure

**User Panel (Front-end):**
- `index.php` - Portfolio homepage displaying all content
- `contact_submit.php` - Contact form handler
- `first_time_setup.php` - One-time password display page
- `setup.php` - Setup instructions and documentation
- `assets/css/style.css` - User-facing styles

**Admin Panel:**
- `admin/login.php` - Admin authentication
- `admin/dashboard.php` - Admin dashboard with statistics
- `admin/projects.php` - Manage projects (CRUD)
- `admin/skills.php` - Manage skills (CRUD)
- `admin/certificates.php` - Manage certificates (CRUD)
- `admin/gallery.php` - Manage gallery images (CRUD)
- `admin/about.php` - Edit about information
- `admin/messages.php` - View contact messages
- `admin/change_password.php` - Change password interface
- `admin/force_password_change.php` - Enforced password rotation
- `admin/header.php` - Admin navigation component
- `admin/check_auth.php` - Authentication guard
- `assets/css/admin.css` - Admin panel styles

**Configuration:**
- `config/database.php` - Database connection and initialization
- `database.sql` - MySQL schema for XAMPP import
- `.htaccess` - Apache security rules
- `.setup_required` - Setup flag (created during installation)
- `.admin_password` - Temporary password file (auto-deleted)

### Deployment

**Target Environment:**
- XAMPP (Apache + MySQL + PHP)
- Local development environment
- Not recommended for production without additional hardening

**Replit Environment:**
- PHP server running on port 5000
- No MySQL available (redirects to setup page)
- For demonstration and code review purposes

**Setup Process:**
1. Copy files to XAMPP htdocs folder
2. Start Apache and MySQL services
3. Access application (database auto-initializes)
4. View one-time random password
5. Login and change password
6. Manage portfolio content via admin panel

### Documentation

**Setup & Security:**
- `README_SETUP.md` - Complete installation guide for XAMPP
- `SECURITY.md` - Security considerations and best practices
- `setup.php` - In-app setup instructions with visual guide

**Key Features:**
- ✅ Complete CRUD operations for all content types
- ✅ Secure admin authentication with forced password rotation
- ✅ Random per-installation admin password
- ✅ Responsive design for all screen sizes
- ✅ Contact form with message management
- ✅ SQL injection prevention
- ✅ Password hashing and session security