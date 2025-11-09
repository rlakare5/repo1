<?php
session_start();
require_once '../config/database.php';
require_once 'check_auth.php';

$conn = getConnection();

// Get counts
$projects_count = $conn->query("SELECT COUNT(*) as count FROM projects")->fetch_assoc()['count'];
$skills_count = $conn->query("SELECT COUNT(*) as count FROM skills")->fetch_assoc()['count'];
$certificates_count = $conn->query("SELECT COUNT(*) as count FROM certificates")->fetch_assoc()['count'];
$messages_count = $conn->query("SELECT COUNT(*) as count FROM contact_messages")->fetch_assoc()['count'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Dashboard</h1>
        
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'password_changed'): ?>
            <div class="alert alert-success">✅ Password changed successfully! Your account is now secure.</div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Projects</h3>
                <div class="stat-number"><?php echo $projects_count; ?></div>
                <a href="projects.php" class="btn btn-sm">Manage</a>
            </div>
            
            <div class="stat-card">
                <h3>Skills</h3>
                <div class="stat-number"><?php echo $skills_count; ?></div>
                <a href="skills.php" class="btn btn-sm">Manage</a>
            </div>
            
            <div class="stat-card">
                <h3>Certificates</h3>
                <div class="stat-number"><?php echo $certificates_count; ?></div>
                <a href="certificates.php" class="btn btn-sm">Manage</a>
            </div>
            
            <div class="stat-card">
                <h3>Messages</h3>
                <div class="stat-number"><?php echo $messages_count; ?></div>
                <a href="messages.php" class="btn btn-sm">View</a>
            </div>
        </div>
        
        <div class="quick-links">
            <h2>Quick Actions</h2>
            <div class="actions-grid">
                <a href="about.php" class="action-btn">Edit About</a>
                <a href="gallery.php" class="action-btn">Manage Gallery</a>
                <a href="projects.php?action=add" class="action-btn">Add Project</a>
                <a href="skills.php?action=add" class="action-btn">Add Skill</a>
            </div>
        </div>
    </div>
</body>
</html>
