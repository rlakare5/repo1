<?php
// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'portfolio_db');

// Create connection
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Initialize database and tables if they don't exist
function initDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    if ($conn->query($sql) === FALSE) {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    $conn->select_db(DB_NAME);
    
    // Create admin table
    $sql = "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(150) NOT NULL,
        must_change_password TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    // Create default admin if not exists and setup file exists
    $setup_flag = file_exists(__DIR__ . '/../.setup_required');
    $check = $conn->query("SELECT * FROM admins WHERE username = 'admin'");
    if ($check->num_rows == 0 && $setup_flag) {
        // Generate random password
        $random_password = bin2hex(random_bytes(8)); // 16 character random password
        $password_hash = password_hash($random_password, PASSWORD_DEFAULT);
        
        // Must change password on first login
        $conn->query("INSERT INTO admins (username, password, email, must_change_password) VALUES ('admin', '$password_hash', 'admin@portfolio.com', 1)");
        
        // Save password to temporary file for display (one-time only)
        file_put_contents(__DIR__ . '/../.admin_password', $random_password);
        
        // Remove setup flag after creating default admin
        unlink(__DIR__ . '/../.setup_required');
    }
    
    // Create projects table
    $sql = "CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        technologies VARCHAR(500),
        image_url VARCHAR(255),
        project_url VARCHAR(255),
        github_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    // Create skills table
    $sql = "CREATE TABLE IF NOT EXISTS skills (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50),
        proficiency INT DEFAULT 50,
        icon_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    // Create certificates table
    $sql = "CREATE TABLE IF NOT EXISTS certificates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        issuer VARCHAR(150),
        issue_date DATE,
        image_url VARCHAR(255),
        certificate_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    // Create gallery table
    $sql = "CREATE TABLE IF NOT EXISTS gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200),
        description TEXT,
        image_url VARCHAR(255) NOT NULL,
        category VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    // Create about table
    $sql = "CREATE TABLE IF NOT EXISTS about (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        title VARCHAR(150),
        bio TEXT,
        email VARCHAR(150),
        phone VARCHAR(20),
        location VARCHAR(100),
        linkedin VARCHAR(255),
        github VARCHAR(255),
        photo_url VARCHAR(255),
        resume_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    // Create contact messages table
    $sql = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        subject VARCHAR(200),
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    $conn->close();
}
?>
