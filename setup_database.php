<?php
require_once 'db.php';

try {
    // Add image field to posts table
    $pdo->exec("ALTER TABLE posts ADD COLUMN IF NOT EXISTS image VARCHAR(255) NULL");

    // Create likes table
    $pdo->exec("CREATE TABLE IF NOT EXISTS likes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        post_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_like (user_id, post_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
    )");

    // Create categories table
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) UNIQUE NOT NULL,
        slug VARCHAR(255) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Add category_id to posts table
    $pdo->exec("ALTER TABLE posts ADD COLUMN IF NOT EXISTS category_id INT NULL");
    $pdo->exec("ALTER TABLE posts ADD FOREIGN KEY IF NOT EXISTS (category_id) REFERENCES categories(id) ON DELETE SET NULL");

    // Add profile_image to users table
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) NULL DEFAULT 'default.jpg'");

    // Insert default categories if they don't exist
    $categories = [
        ['Technology', 'technology'],
        ['Lifestyle', 'lifestyle'],
        ['Travel', 'travel'],
        ['Food', 'food'],
        ['Health', 'health']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name, slug) VALUES (?, ?)");
    foreach ($categories as $category) {
        $stmt->execute($category);
    }

    echo "Database setup completed successfully!";
} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
} 