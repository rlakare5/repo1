<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if ($new_password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($new_password) < 8) {
        $error = 'Password must be at least 8 characters';
    } elseif ($new_password == 'admin123') {
        $error = 'You cannot use the default password. Please choose a different password.';
    } else {
        try {
            $conn = getConnection();
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admins SET password = ?, must_change_password = 0 WHERE id = ?");
            $stmt->bind_param("si", $new_hash, $_SESSION['admin_id']);
            $stmt->execute();
            $_SESSION['must_change_password'] = 0;
            $conn->close();
            header('Location: dashboard.php?msg=password_changed');
            exit();
        } catch (Exception $e) {
            $error = 'Failed to update password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password Required</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>⚠️ Password Change Required</h1>
            <p style="margin-bottom: 20px; color: #e74c3c; font-weight: bold;">
                You are using the default password. For security reasons, you must change it before continuing.
            </p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>New Password (minimum 8 characters)</label>
                    <input type="password" name="new_password" required minlength="8" autofocus>
                </div>
                
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required minlength="8">
                </div>
                
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
            
            <div style="margin-top: 20px; text-align: center;">
                <a href="logout.php" style="color: #7f8c8d;">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
