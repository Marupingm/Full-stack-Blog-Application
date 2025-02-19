<?php
// Create directories if they don't exist
$dirs = [
    'uploads',
    'uploads/posts',
    'uploads/profiles'
];

foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        echo "Created directory: $dir\n";
    }
}

// Demo post images URLs (using placeholder images)
$post_images = [
    'post1.jpg' => 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=800&h=400&fit=crop',
    'post2.jpg' => 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=800&h=400&fit=crop',
    'post3.jpg' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&h=400&fit=crop',
    'post4.jpg' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=400&fit=crop',
    'post5.jpg' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&h=400&fit=crop',
    'post6.jpg' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=400&fit=crop',
    'post7.jpg' => 'https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?w=800&h=400&fit=crop',
    'post8.jpg' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&h=400&fit=crop',
];

// Demo profile images URLs
$profile_images = [
    'profile1.jpg' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&h=200&fit=crop',
    'profile2.jpg' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=200&fit=crop',
    'profile3.jpg' => 'https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=200&h=200&fit=crop',
    'profile4.jpg' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&h=200&fit=crop',
];

// Download post images
foreach ($post_images as $filename => $url) {
    $filepath = 'uploads/posts/' . $filename;
    if (!file_exists($filepath)) {
        $image = file_get_contents($url);
        if ($image !== false) {
            file_put_contents($filepath, $image);
            echo "Downloaded post image: $filename\n";
        }
    }
}

// Download profile images
foreach ($profile_images as $filename => $url) {
    $filepath = 'uploads/profiles/' . $filename;
    if (!file_exists($filepath)) {
        $image = file_get_contents($url);
        if ($image !== false) {
            file_put_contents($filepath, $image);
            echo "Downloaded profile image: $filename\n";
        }
    }
}

// Update database with demo images
require_once 'db.php';

try {
    // Update random posts with demo images
    $post_image_files = array_keys($post_images);
    $posts = $pdo->query("SELECT id FROM posts ORDER BY RAND() LIMIT " . count($post_image_files))->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($posts as $index => $post_id) {
        if (isset($post_image_files[$index])) {
            $stmt = $pdo->prepare("UPDATE posts SET image = ? WHERE id = ?");
            $stmt->execute([$post_image_files[$index], $post_id]);
            echo "Updated post $post_id with image: {$post_image_files[$index]}\n";
        }
    }

    // Update random users with demo profile images
    $profile_image_files = array_keys($profile_images);
    $users = $pdo->query("SELECT id FROM users ORDER BY RAND() LIMIT " . count($profile_image_files))->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($users as $index => $user_id) {
        if (isset($profile_image_files[$index])) {
            $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
            $stmt->execute([$profile_image_files[$index], $user_id]);
            echo "Updated user $user_id with profile image: {$profile_image_files[$index]}\n";
        }
    }

    echo "Demo images setup completed successfully!\n";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?> 