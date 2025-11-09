<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        header('Location: index.php?msg=success#contact');
    } else {
        header('Location: index.php?msg=error#contact');
    }
    
    $stmt->close();
    $conn->close();
} else {
    header('Location: index.php');
}
exit();
?>
