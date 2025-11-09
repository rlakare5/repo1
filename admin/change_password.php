<?php
session_start();
require_once '../config/database.php';
require_once 'check_auth.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if ($new_password !== $confirm_password) {
        $error = 'New passwords do not match';
    } elseif (strlen($new_password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['admin_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($current_password, $admin['password'])) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE admins SET password = ?, must_change_password = 0 WHERE id = ?");
                $update->bind_param("si", $new_hash, $_SESSION['admin_id']);
                $update->execute();
                $_SESSION['must_change_password'] = 0;
                $message = 'Password changed successfully';
            } else {
                $error = 'Current password is incorrect';
            }
        }
        
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Change Password</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label>New Password (min 8 characters)</label>
                    <input type="password" name="new_password" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
