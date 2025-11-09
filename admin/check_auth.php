<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Force password change if required (except on password change pages)
$current_page = basename($_SERVER['PHP_SELF']);
if (isset($_SESSION['must_change_password']) && 
    $_SESSION['must_change_password'] == 1 && 
    $current_page != 'force_password_change.php' && 
    $current_page != 'logout.php') {
    header('Location: force_password_change.php');
    exit();
}
?>
