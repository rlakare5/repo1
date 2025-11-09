<?php
session_start();
require_once '../config/database.php';
require_once 'check_auth.php';

$conn = getConnection();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $issuer = $_POST['issuer'] ?? '';
    $issue_date = $_POST['issue_date'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $certificate_url = $_POST['certificate_url'] ?? '';
    
    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO certificates (title, issuer, issue_date, image_url, certificate_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $issuer, $issue_date, $image_url, $certificate_url);
        $stmt->execute();
        $message = 'Certificate added successfully';
        $action = 'list';
    } elseif ($_POST['action'] == 'edit') {
        $edit_id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE certificates SET title=?, issuer=?, issue_date=?, image_url=?, certificate_url=? WHERE id=?");
        $stmt->bind_param("sssssi", $title, $issuer, $issue_date, $image_url, $certificate_url, $edit_id);
        $stmt->execute();
        $message = 'Certificate updated successfully';
        $action = 'list';
    }
}

// Handle delete
if ($action == 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM certificates WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = 'Certificate deleted successfully';
    $action = 'list';
}

// Get certificate for editing
$cert = null;
if ($action == 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM certificates WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $cert = $stmt->get_result()->fetch_assoc();
}

// Get all certificates
$certificates = $conn->query("SELECT * FROM certificates ORDER BY issue_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Certificates</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Manage Certificates</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($action == 'add' || $action == 'edit'): ?>
            <div class="form-container">
                <h2><?php echo $action == 'add' ? 'Add New' : 'Edit'; ?> Certificate</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action; ?>">
                    <?php if ($action == 'edit'): ?>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($cert['title'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Issuer</label>
                        <input type="text" name="issuer" value="<?php echo htmlspecialchars($cert['issuer'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Issue Date</label>
                        <input type="date" name="issue_date" value="<?php echo htmlspecialchars($cert['issue_date'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" name="image_url" value="<?php echo htmlspecialchars($cert['image_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Certificate URL</label>
                        <input type="text" name="certificate_url" value="<?php echo htmlspecialchars($cert['certificate_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="certificates.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="table-actions">
                <a href="?action=add" class="btn btn-primary">Add New Certificate</a>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Issuer</th>
                        <th>Issue Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $certificates->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['issuer']); ?></td>
                            <td><?php echo $row['issue_date'] ? date('M d, Y', strtotime($row['issue_date'])) : 'N/A'; ?></td>
                            <td>
                                <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm">Edit</a>
                                <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this certificate?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>
