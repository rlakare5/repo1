<?php
session_start();
require_once '../config/database.php';
require_once 'check_auth.php';

$conn = getConnection();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';

// Handle delete
if ($action == 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = 'Message deleted successfully';
    $action = 'list';
}

// Get single message
$msg = null;
if ($action == 'view' && $id) {
    $stmt = $conn->prepare("SELECT * FROM contact_messages WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $msg = $stmt->get_result()->fetch_assoc();
}

// Get all messages
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Contact Messages</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($action == 'view' && $msg): ?>
            <div class="message-view">
                <h2><?php echo htmlspecialchars($msg['subject']); ?></h2>
                <div class="message-meta">
                    <p><strong>From:</strong> <?php echo htmlspecialchars($msg['name']); ?> (<?php echo htmlspecialchars($msg['email']); ?>)</p>
                    <p><strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($msg['created_at'])); ?></p>
                </div>
                <div class="message-content">
                    <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                </div>
                <div class="form-actions">
                    <a href="messages.php" class="btn btn-secondary">Back to Messages</a>
                    <a href="?action=delete&id=<?php echo $msg['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
                </div>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $messages->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="?action=view&id=<?php echo $row['id']; ?>" class="btn btn-sm">View</a>
                                <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
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
