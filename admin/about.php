<?php
session_start();
require_once '../config/database.php';
require_once 'check_auth.php';

$conn = getConnection();
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $title = $_POST['title'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';
    $github = $_POST['github'] ?? '';
    $photo_url = $_POST['photo_url'] ?? '';
    $resume_url = $_POST['resume_url'] ?? '';
    
    // Check if record exists
    $check = $conn->query("SELECT COUNT(*) as count FROM about")->fetch_assoc();
    
    if ($check['count'] > 0) {
        // Update existing
        $stmt = $conn->prepare("UPDATE about SET name=?, title=?, bio=?, email=?, phone=?, location=?, linkedin=?, github=?, photo_url=?, resume_url=? WHERE id=1");
        $stmt->bind_param("ssssssssss", $name, $title, $bio, $email, $phone, $location, $linkedin, $github, $photo_url, $resume_url);
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO about (name, title, bio, email, phone, location, linkedin, github, photo_url, resume_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $name, $title, $bio, $email, $phone, $location, $linkedin, $github, $photo_url, $resume_url);
    }
    
    $stmt->execute();
    $message = 'About information updated successfully';
}

// Get current about data
$about = $conn->query("SELECT * FROM about LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit About</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Edit About Information</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($about['name'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Title/Position</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($about['title'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio" rows="6"><?php echo htmlspecialchars($about['bio'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($about['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($about['phone'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($about['location'] ?? ''); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>LinkedIn URL</label>
                        <input type="text" name="linkedin" value="<?php echo htmlspecialchars($about['linkedin'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>GitHub URL</label>
                        <input type="text" name="github" value="<?php echo htmlspecialchars($about['github'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Photo URL</label>
                    <input type="text" name="photo_url" value="<?php echo htmlspecialchars($about['photo_url'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Resume URL</label>
                    <input type="text" name="resume_url" value="<?php echo htmlspecialchars($about['resume_url'] ?? ''); ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
