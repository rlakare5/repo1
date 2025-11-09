# PHP Portfolio - Setup Guide

A complete portfolio management system with admin panel and user interface, built with PHP and MySQL.

## 🎯 Features

- **Admin Panel** with full CRUD operations
- **User Panel** to display portfolio content
- **Secure Authentication** for admin access
- **Database Management** for:
  - Projects
  - Skills
  - Certificates
  - Gallery
  - About Information
  - Contact Messages

## 📋 Requirements

- **For XAMPP:**
  - Apache Server
  - MySQL Database
  - PHP 7.4 or higher

- **For Replit:**
  - This project is configured for PHP
  - Note: MySQL is not available on Replit by default
  - For Replit deployment, consider using an external MySQL database

## 🚀 Installation (XAMPP)

### Step 1: Start XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** services

### Step 2: Setup Database

**Option A: Automatic Setup (Recommended)**
- The application will automatically create the database and tables on first run

**Option B: Manual Setup**
1. Open phpMyAdmin at `http://localhost/phpmyadmin`
2. Create a new database named `portfolio_db`
3. Import the `database.sql` file from this project

### Step 3: Configure Files
1. Copy all project files to XAMPP's `htdocs` folder
   - Default path: `C:\xampp\htdocs\portfolio`
2. Verify database settings in `config/database.php`:
   ```php
   DB_HOST = 'localhost'
   DB_USER = 'root'
   DB_PASS = ''
   DB_NAME = 'portfolio_db'
   ```

### Step 4: Access the Application
- **User Portal:** `http://localhost/portfolio/index.php`
- **Admin Panel:** `http://localhost/portfolio/admin/login.php`

## 🔐 Admin Account & Security

**Username:** admin  
**Password:** Randomly generated (shown ONCE during first setup)

**🔒 Secure Setup Process:**

1. **Install the application** following the steps above
2. **First access** - When you first access the application after database creation, you'll see a **one-time password display**
3. **SAVE THE PASSWORD** - This random password is shown only once and then permanently deleted
4. **Log in** with the displayed password
5. **Forced password change** - You will be required to change the password immediately
6. **Secure** - After changing the password, your admin panel is secured with credentials only you know

**Security Features:**
- ✅ Random unique password generated per installation (not a shared default)
- ✅ Password displayed only once during setup
- ✅ Temporary password file deleted after viewing
- ✅ Forced password change on first login
- ✅ Cannot reuse temporary password
- ✅ No publicly-known default credentials

## 📁 Project Structure

```
portfolio/
├── index.php              # User-facing homepage
├── contact_submit.php     # Contact form handler
├── setup.php             # Setup instructions page
├── database.sql          # MySQL database schema
├── admin/                # Admin panel
│   ├── login.php        # Admin login
│   ├── dashboard.php    # Admin dashboard
│   ├── projects.php     # Manage projects
│   ├── skills.php       # Manage skills
│   ├── certificates.php # Manage certificates
│   ├── gallery.php      # Manage gallery
│   ├── about.php        # Edit about info
│   └── messages.php     # View contact messages
├── config/
│   └── database.php     # Database configuration
└── assets/
    └── css/
        ├── style.css    # User panel styles
        └── admin.css    # Admin panel styles
```

## 🎨 Usage

### Admin Panel

1. **Login:** Navigate to `/admin/login.php`
2. **Dashboard:** View statistics and quick actions
3. **Manage Content:**
   - Projects: Add/Edit/Delete portfolio projects
   - Skills: Manage skills with proficiency levels
   - Certificates: Add certifications
   - Gallery: Upload and manage images
   - About: Edit personal information
   - Messages: View contact form submissions

### CRUD Operations

All admin pages support complete CRUD operations:
- **Create:** Click "Add New" button
- **Read:** View list of all items
- **Update:** Click "Edit" on any item
- **Delete:** Click "Delete" (with confirmation)

## 🛠️ Database Configuration

### Environment Variables (Optional)

You can override database settings using environment variables:

```
DB_HOST=localhost
DB_USER=root
DB_PASS=your_password
DB_NAME=portfolio_db
```

### Custom Configuration

Edit `config/database.php` to change database settings:

```php
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
```

## 🔧 Troubleshooting

### Connection Error
- Verify MySQL service is running in XAMPP
- Check database credentials in `config/database.php`
- Ensure `portfolio_db` database exists

### Page Not Found
- Verify files are in XAMPP's `htdocs` folder
- Check Apache service is running
- Access via `localhost` not `127.0.0.1`

### Admin Login Issues
- Database must be initialized first
- Default admin is created automatically
- Check `admins` table exists in database

## 📧 Contact Form

The contact form on the user portal saves messages to the database. Admin can view all messages in the admin panel under "Messages".

## 🎓 Technologies Used

- **Backend:** PHP 8.2
- **Database:** MySQL
- **Frontend:** HTML5, CSS3
- **Styling:** Custom CSS with responsive design
- **Security:** Password hashing, prepared statements

## 📄 License

This project is open source and available for educational purposes.

## 🤝 Support

For issues or questions:
1. Check the setup.php page for common solutions
2. Verify all XAMPP services are running
3. Check database connection settings
4. Review error logs in XAMPP

---

**Enjoy building your portfolio! 🚀**
