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
    $description = $_POST['description'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $category = $_POST['category'] ?? '';
    
    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO gallery (title, description, image_url, category) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $description, $image_url, $category);
        $stmt->execute();
        $message = 'Gallery item added successfully';
        $action = 'list';
    } elseif ($_POST['action'] == 'edit') {
        $edit_id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE gallery SET title=?, description=?, image_url=?, category=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $description, $image_url, $category, $edit_id);
        $stmt->execute();
        $message = 'Gallery item updated successfully';
        $action = 'list';
    }
}

// Handle delete
if ($action == 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM gallery WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = 'Gallery item deleted successfully';
    $action = 'list';
}

// Get gallery item for editing
$item = null;
if ($action == 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM gallery WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
}

// Get all gallery items
$gallery = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Manage Gallery</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($action == 'add' || $action == 'edit'): ?>
            <div class="form-container">
                <h2><?php echo $action == 'add' ? 'Add New' : 'Edit'; ?> Gallery Item</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action; ?>">
                    <?php if ($action == 'edit'): ?>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Image URL *</label>
                        <input type="text" name="image_url" value="<?php echo htmlspecialchars($item['image_url'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" value="<?php echo htmlspecialchars($item['category'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="gallery.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="table-actions">
                <a href="?action=add" class="btn btn-primary">Add New Item</a>
            </div>
            
            <div class="gallery-grid">
                <?php while ($row = $gallery->fetch_assoc()): ?>
                    <div class="gallery-item">
                        <?php if ($row['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['category']); ?></p>
                        <div class="gallery-actions">
                            <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm">Edit</a>
                            <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>
