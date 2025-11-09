<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Setup</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .setup-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            max-width: 800px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { color: #2c3e50; margin-bottom: 20px; }
        h2 { color: #667eea; margin: 30px 0 15px; font-size: 1.3rem; }
        p { margin-bottom: 15px; line-height: 1.6; }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .warning-box {
            background: #fff3cd;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        code {
            background: #2c3e50;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #764ba2;
        }
        ol { margin-left: 20px; margin-bottom: 15px; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="setup-container">
        <h1>Portfolio Setup Instructions</h1>
        
        <p>Welcome to your PHP Portfolio Application! This application requires a MySQL database to function.</p>
        
        <div class="warning-box">
            <strong>⚠️ Note for Replit Environment:</strong><br>
            Replit does not have MySQL built-in. This application is designed to work with XAMPP's MySQL server.
            To run this on Replit, you would need to use an external MySQL database or modify the code to use SQLite/PostgreSQL.
        </div>
        
        <h2>📋 Setup for XAMPP (Recommended)</h2>
        
        <ol>
            <li><strong>Start XAMPP:</strong> Open XAMPP Control Panel and start both Apache and MySQL services.</li>
            
            <li><strong>Create Database:</strong>
                <ul>
                    <li>Open phpMyAdmin (usually at <code>http://localhost/phpmyadmin</code>)</li>
                    <li>Import the <code>database.sql</code> file from this project, OR</li>
                    <li>The application will auto-create the database when first accessed</li>
                </ul>
            </li>
            
            <li><strong>Configure Database Connection:</strong>
                <p>Edit <code>config/database.php</code> if needed. Default settings:</p>
                <ul>
                    <li>Host: <code>localhost</code></li>
                    <li>Username: <code>root</code></li>
                    <li>Password: <code>(empty)</code></li>
                    <li>Database: <code>portfolio_db</code></li>
                </ul>
            </li>
            
            <li><strong>Move Files:</strong>
                <p>Copy all project files to XAMPP's <code>htdocs</code> folder (usually <code>C:\xampp\htdocs\portfolio</code>)</p>
            </li>
            
            <li><strong>Access Application:</strong>
                <p>Open your browser and go to: <code>http://localhost/portfolio</code></p>
            </li>
        </ol>
        
        <h2>🔐 Admin Account Setup</h2>
        <div class="info-box">
            <strong>Secure Password Generation:</strong><br>
            When you first run the application and the database is initialized, a <strong>random, unique password</strong> will be automatically generated for the admin account.
            <br><br>
            <strong>Username:</strong> admin<br>
            <strong>Password:</strong> A random 16-character password (shown once during first setup)
            <br><br>
            <em>⚠️ The password will be displayed ONCE on the first-time setup page. Save it immediately!</em>
        </div>
        
        <div class="warning-box">
            <strong>🔒 Security Features:</strong><br>
            ✅ Random password generated per installation (not a shared default)<br>
            ✅ Password shown only once and then permanently deleted<br>
            ✅ Forced password change on first login<br>
            ✅ Cannot reuse the temporary password<br>
            ✅ Setup flag prevents unauthorized account creation
        </div>
        
        <h2>📁 Project Structure</h2>
        <ul>
            <li><code>index.php</code> - User-facing portfolio homepage</li>
            <li><code>admin/</code> - Admin panel for managing content</li>
            <li><code>config/database.php</code> - Database configuration</li>
            <li><code>database.sql</code> - Database schema for XAMPP</li>
            <li><code>assets/css/</code> - Stylesheets</li>
        </ul>
        
        <h2>🚀 Features</h2>
        <ul>
            <li>✅ Complete CRUD operations for Projects, Skills, Certificates, and Gallery</li>
            <li>✅ Admin authentication system</li>
            <li>✅ Contact form with message management</li>
            <li>✅ Responsive design</li>
            <li>✅ User-friendly admin panel</li>
        </ul>
        
        <div class="info-box">
            <strong>💡 Tip:</strong> After setting up the database, access the admin panel at <code>/admin/login.php</code> to start managing your portfolio content!
        </div>
        
        <a href="index.php" class="btn">Continue to Portfolio →</a>
    </div>
</body>
</html>
