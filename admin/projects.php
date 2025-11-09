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
    $technologies = $_POST['technologies'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $project_url = $_POST['project_url'] ?? '';
    $github_url = $_POST['github_url'] ?? '';
    
    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO projects (title, description, technologies, image_url, project_url, github_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $title, $description, $technologies, $image_url, $project_url, $github_url);
        $stmt->execute();
        $message = 'Project added successfully';
        $action = 'list';
    } elseif ($_POST['action'] == 'edit') {
        $edit_id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE projects SET title=?, description=?, technologies=?, image_url=?, project_url=?, github_url=? WHERE id=?");
        $stmt->bind_param("ssssssi", $title, $description, $technologies, $image_url, $project_url, $github_url, $edit_id);
        $stmt->execute();
        $message = 'Project updated successfully';
        $action = 'list';
    }
}

// Handle delete
if ($action == 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM projects WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = 'Project deleted successfully';
    $action = 'list';
}

// Get project for editing
$project = null;
if ($action == 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $project = $stmt->get_result()->fetch_assoc();
}

// Get all projects
$projects = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Manage Projects</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($action == 'add' || $action == 'edit'): ?>
            <div class="form-container">
                <h2><?php echo $action == 'add' ? 'Add New' : 'Edit'; ?> Project</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action; ?>">
                    <?php if ($action == 'edit'): ?>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($project['title'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="5"><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Technologies (comma separated)</label>
                        <input type="text" name="technologies" value="<?php echo htmlspecialchars($project['technologies'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" name="image_url" value="<?php echo htmlspecialchars($project['image_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Project URL</label>
                        <input type="text" name="project_url" value="<?php echo htmlspecialchars($project['project_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>GitHub URL</label>
                        <input type="text" name="github_url" value="<?php echo htmlspecialchars($project['github_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="projects.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="table-actions">
                <a href="?action=add" class="btn btn-primary">Add New Project</a>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Technologies</th>
                        <th>URLs</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $projects->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['technologies']); ?></td>
                            <td>
                                <?php if ($row['project_url']): ?>
                                    <a href="<?php echo htmlspecialchars($row['project_url']); ?>" target="_blank">Demo</a>
                                <?php endif; ?>
                                <?php if ($row['github_url']): ?>
                                    <a href="<?php echo htmlspecialchars($row['github_url']); ?>" target="_blank">GitHub</a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm">Edit</a>
                                <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this project?')">Delete</a>
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
