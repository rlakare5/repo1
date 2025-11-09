<?php
require_once 'config/database.php';

try {
    initDatabase();
    $conn = getConnection();
} catch (Exception $e) {
    header('Location: setup.php');
    exit();
}

// Check if first-time password needs to be displayed (after database init)
if (file_exists('.admin_password')) {
    header('Location: first_time_setup.php');
    exit();
}

// Get about information
$about = $conn->query("SELECT * FROM about LIMIT 1")->fetch_assoc();

// Get recent projects
$projects = $conn->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 6");

// Get skills by category
$skills = $conn->query("SELECT * FROM skills ORDER BY category, proficiency DESC");

// Get recent certificates
$certificates = $conn->query("SELECT * FROM certificates ORDER BY issue_date DESC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($about['name'] ?? 'Portfolio'); ?> - Portfolio</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"><?php echo htmlspecialchars($about['name'] ?? 'Portfolio'); ?></div>
            <ul class="nav-menu">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#certificates">Certificates</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="admin/login.php">Admin</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <?php if ($about): ?>
                <h1>Hi, I'm <?php echo htmlspecialchars($about['name']); ?></h1>
                <h2><?php echo htmlspecialchars($about['title']); ?></h2>
                <p><?php echo htmlspecialchars(substr($about['bio'], 0, 200)); ?>...</p>
                <div class="hero-buttons">
                    <a href="#projects" class="btn btn-primary">View Projects</a>
                    <a href="#contact" class="btn btn-secondary">Contact Me</a>
                </div>
            <?php else: ?>
                <h1>Welcome to My Portfolio</h1>
                <p>Please configure your portfolio in the admin panel.</p>
                <a href="admin/login.php" class="btn btn-primary">Go to Admin</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section">
        <div class="container">
            <h2 class="section-title">About Me</h2>
            <?php if ($about): ?>
                <div class="about-content">
                    <?php if ($about['photo_url']): ?>
                        <div class="about-image">
                            <img src="<?php echo htmlspecialchars($about['photo_url']); ?>" alt="<?php echo htmlspecialchars($about['name']); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="about-text">
                        <p><?php echo nl2br(htmlspecialchars($about['bio'])); ?></p>
                        <div class="about-info">
                            <?php if ($about['email']): ?>
                                <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($about['email']); ?>"><?php echo htmlspecialchars($about['email']); ?></a></p>
                            <?php endif; ?>
                            <?php if ($about['location']): ?>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($about['location']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="social-links">
                            <?php if ($about['linkedin']): ?>
                                <a href="<?php echo htmlspecialchars($about['linkedin']); ?>" target="_blank">LinkedIn</a>
                            <?php endif; ?>
                            <?php if ($about['github']): ?>
                                <a href="<?php echo htmlspecialchars($about['github']); ?>" target="_blank">GitHub</a>
                            <?php endif; ?>
                            <?php if ($about['resume_url']): ?>
                                <a href="<?php echo htmlspecialchars($about['resume_url']); ?>" target="_blank">Resume</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="section section-dark">
        <div class="container">
            <h2 class="section-title">Projects</h2>
            <div class="projects-grid">
                <?php while ($project = $projects->fetch_assoc()): ?>
                    <div class="project-card">
                        <?php if ($project['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <?php if ($project['technologies']): ?>
                            <div class="tech-tags">
                                <?php foreach (explode(',', $project['technologies']) as $tech): ?>
                                    <span class="tag"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="project-links">
                            <?php if ($project['project_url']): ?>
                                <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank">Live Demo</a>
                            <?php endif; ?>
                            <?php if ($project['github_url']): ?>
                                <a href="<?php echo htmlspecialchars($project['github_url']); ?>" target="_blank">GitHub</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="section">
        <div class="container">
            <h2 class="section-title">Skills</h2>
            <div class="skills-container">
                <?php
                $current_category = '';
                while ($skill = $skills->fetch_assoc()):
                    if ($current_category != $skill['category']):
                        if ($current_category != '') echo '</div>';
                        $current_category = $skill['category'];
                        echo '<div class="skill-category">';
                        echo '<h3>' . htmlspecialchars($current_category) . '</h3>';
                        echo '<div class="skills-list">';
                    endif;
                ?>
                    <div class="skill-item">
                        <div class="skill-header">
                            <span><?php echo htmlspecialchars($skill['name']); ?></span>
                            <span><?php echo htmlspecialchars($skill['proficiency']); ?>%</span>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-progress" style="width: <?php echo htmlspecialchars($skill['proficiency']); ?>%"></div>
                        </div>
                    </div>
                <?php
                endwhile;
                if ($current_category != '') echo '</div></div>';
                ?>
            </div>
        </div>
    </section>

    <!-- Certificates Section -->
    <section id="certificates" class="section section-dark">
        <div class="container">
            <h2 class="section-title">Certificates</h2>
            <div class="certificates-grid">
                <?php while ($cert = $certificates->fetch_assoc()): ?>
                    <div class="cert-card">
                        <?php if ($cert['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($cert['image_url']); ?>" alt="<?php echo htmlspecialchars($cert['title']); ?>">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($cert['title']); ?></h3>
                        <p><?php echo htmlspecialchars($cert['issuer']); ?></p>
                        <?php if ($cert['issue_date']): ?>
                            <p class="cert-date"><?php echo date('M Y', strtotime($cert['issue_date'])); ?></p>
                        <?php endif; ?>
                        <?php if ($cert['certificate_url']): ?>
                            <a href="<?php echo htmlspecialchars($cert['certificate_url']); ?>" target="_blank" class="btn btn-sm">View Certificate</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <div class="contact-content">
                <form action="contact_submit.php" method="POST" class="contact-form">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" placeholder="Subject" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($about['name'] ?? 'Portfolio'); ?>. All rights reserved.</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>
