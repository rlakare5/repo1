<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First Time Setup - Admin Password</title>
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
            max-width: 700px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { 
            color: #2c3e50; 
            margin-bottom: 20px; 
            text-align: center;
        }
        .warning-box {
            background: #fff3cd;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .password-box {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 5px;
            margin: 30px 0;
            text-align: center;
            border: 3px solid #667eea;
        }
        .password-display {
            font-size: 2rem;
            font-weight: bold;
            color: #e74c3c;
            font-family: 'Courier New', monospace;
            padding: 20px;
            background: white;
            border-radius: 5px;
            margin: 20px 0;
            user-select: all;
            word-break: break-all;
        }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #764ba2;
        }
        .instructions {
            line-height: 1.8;
            margin: 20px 0;
        }
        .instructions li {
            margin: 10px 0;
        }
        .critical {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <h1>🔐 First Time Setup Complete!</h1>
        
        <div class="warning-box">
            <strong>⚠️ CRITICAL: Save This Password Now!</strong><br>
            This is your ONE-TIME admin password. It will be deleted after you click "Continue".
        </div>
        
        <?php
        $password = '';
        if (file_exists('.admin_password')) {
            $password = file_get_contents('.admin_password');
        } else {
            echo '<p class="critical">Password file not found. Please check your setup.</p>';
            echo '<a href="setup.php" class="btn">Back to Setup</a>';
            exit();
        }
        ?>
        
        <div class="password-box">
            <h2>Your Admin Password:</h2>
            <div class="password-display"><?php echo htmlspecialchars($password); ?></div>
            <p><strong>Username:</strong> admin</p>
        </div>
        
        <div class="instructions">
            <h3>Next Steps:</h3>
            <ol>
                <li class="critical">COPY this password somewhere safe (it will be deleted after you continue)</li>
                <li>Click "Continue to Admin Login" below</li>
                <li>Log in with username <code>admin</code> and the password above</li>
                <li>You will be forced to change the password to something you choose</li>
                <li>After changing the password, your admin panel will be secure</li>
            </ol>
        </div>
        
        <div class="warning-box">
            <strong>Security Note:</strong><br>
            This random password was generated specifically for your installation.
            Unlike default passwords, it is unique and cannot be guessed.
            After you click Continue, this password file will be permanently deleted.
        </div>
        
        <form method="POST" style="text-align: center;">
            <input type="hidden" name="confirm" value="1">
            <button type="submit" class="btn">Continue to Admin Login →</button>
        </form>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm'])) {
            // Delete the password file
            if (file_exists('.admin_password')) {
                unlink('.admin_password');
            }
            header('Location: admin/login.php');
            exit();
        }
        ?>
    </div>
</body>
</html>
