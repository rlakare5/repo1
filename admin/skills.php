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
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $proficiency = $_POST['proficiency'] ?? 50;
    $icon_url = $_POST['icon_url'] ?? '';
    
    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO skills (name, category, proficiency, icon_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $name, $category, $proficiency, $icon_url);
        $stmt->execute();
        $message = 'Skill added successfully';
        $action = 'list';
    } elseif ($_POST['action'] == 'edit') {
        $edit_id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE skills SET name=?, category=?, proficiency=?, icon_url=? WHERE id=?");
        $stmt->bind_param("ssisi", $name, $category, $proficiency, $icon_url, $edit_id);
        $stmt->execute();
        $message = 'Skill updated successfully';
        $action = 'list';
    }
}

// Handle delete
if ($action == 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM skills WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = 'Skill deleted successfully';
    $action = 'list';
}

// Get skill for editing
$skill = null;
if ($action == 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM skills WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $skill = $stmt->get_result()->fetch_assoc();
}

// Get all skills
$skills = $conn->query("SELECT * FROM skills ORDER BY category, name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Manage Skills</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($action == 'add' || $action == 'edit'): ?>
            <div class="form-container">
                <h2><?php echo $action == 'add' ? 'Add New' : 'Edit'; ?> Skill</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $action; ?>">
                    <?php if ($action == 'edit'): ?>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Skill Name *</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($skill['name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="Programming" <?php echo ($skill['category'] ?? '') == 'Programming' ? 'selected' : ''; ?>>Programming</option>
                            <option value="Web Development" <?php echo ($skill['category'] ?? '') == 'Web Development' ? 'selected' : ''; ?>>Web Development</option>
                            <option value="AI/ML" <?php echo ($skill['category'] ?? '') == 'AI/ML' ? 'selected' : ''; ?>>AI/ML</option>
                            <option value="Database" <?php echo ($skill['category'] ?? '') == 'Database' ? 'selected' : ''; ?>>Database</option>
                            <option value="Tools" <?php echo ($skill['category'] ?? '') == 'Tools' ? 'selected' : ''; ?>>Tools</option>
                            <option value="Other" <?php echo ($skill['category'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Proficiency (0-100)</label>
                        <input type="number" name="proficiency" min="0" max="100" value="<?php echo htmlspecialchars($skill['proficiency'] ?? '50'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Icon URL</label>
                        <input type="text" name="icon_url" value="<?php echo htmlspecialchars($skill['icon_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="skills.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="table-actions">
                <a href="?action=add" class="btn btn-primary">Add New Skill</a>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Proficiency</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $skills->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['proficiency']); ?>%</td>
                            <td>
                                <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm">Edit</a>
                                <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this skill?')">Delete</a>
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
