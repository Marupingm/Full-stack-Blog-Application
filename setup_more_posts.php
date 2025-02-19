<?php
require_once 'db.php';

// Additional post images
$post_images = [
    'react.jpg' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800&h=400&fit=crop',
    'vue.jpg' => 'https://images.unsplash.com/photo-1607706189992-eae578626c86?w=800&h=400&fit=crop',
    'python.jpg' => 'https://images.unsplash.com/photo-1526379879527-8559ecfcaec0?w=800&h=400&fit=crop',
    'mobile-dev.jpg' => 'https://images.unsplash.com/photo-1526498460520-4c246339dccb?w=800&h=400&fit=crop',
    'web-security.jpg' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&h=400&fit=crop',
    'aws.jpg' => 'https://images.unsplash.com/photo-1549605659-32d82da3a059?w=800&h=400&fit=crop',
    'design-system.jpg' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=400&fit=crop',
    'accessibility.jpg' => 'https://images.unsplash.com/photo-1623479322729-28b25c16b011?w=800&h=400&fit=crop'
];

// Download new post images
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

// Additional blog posts
$posts = [
    // Frontend Category
    [
        'title' => 'Building Modern Web Apps with React and TypeScript',
        'content' => "React and TypeScript have become an increasingly popular combination for building robust web applications. This comprehensive guide explores how to leverage these technologies effectively...",
        'category_id' => 1,
        'image' => 'react.jpg'
    ],
    [
        'title' => 'Vue.js 3 Composition API: A Deep Dive',
        'content' => "The Vue.js 3 Composition API represents a major shift in how we structure Vue applications. This guide explores the benefits and best practices of using the Composition API...",
        'category_id' => 1,
        'image' => 'vue.jpg'
    ],
    
    // Backend Category
    [
        'title' => 'Python FastAPI: Building High-Performance APIs',
        'content' => "FastAPI has emerged as a powerful framework for building Python APIs. This guide explores how to create efficient, type-safe APIs using FastAPI and its modern features...",
        'category_id' => 2,
        'image' => 'python.jpg'
    ],
    [
        'title' => 'Securing Web Applications: Best Practices and Common Pitfalls',
        'content' => "Security is paramount in modern web applications. This comprehensive guide covers essential security practices, common vulnerabilities, and how to protect your applications...",
        'category_id' => 2,
        'image' => 'web-security.jpg'
    ],
    
    // Full Stack Category
    [
        'title' => 'Building Cross-Platform Mobile Apps with React Native',
        'content' => "React Native enables developers to build native mobile applications using JavaScript. This guide explores the fundamentals of React Native development...",
        'category_id' => 3,
        'image' => 'mobile-dev.jpg'
    ],
    [
        'title' => 'AWS for Full Stack Developers: A Comprehensive Guide',
        'content' => "Amazon Web Services offers a vast array of services for full stack applications. This guide helps developers navigate AWS services and best practices...",
        'category_id' => 3,
        'image' => 'aws.jpg'
    ],
    
    // UI/UX Category
    [
        'title' => 'Creating Effective Design Systems for Web Applications',
        'content' => "Design systems are crucial for maintaining consistency across large applications. This guide explores how to create and maintain effective design systems...",
        'category_id' => 4,
        'image' => 'design-system.jpg'
    ],
    [
        'title' => 'Web Accessibility: Building Inclusive Applications',
        'content' => "Accessibility is not just a legal requirement but a moral imperative. This guide covers WCAG guidelines, aria attributes, and best practices for building accessible web applications...",
        'category_id' => 4,
        'image' => 'accessibility.jpg'
    ]
];

try {
    // Get author IDs
    $authors = $pdo->query("SELECT id FROM users")->fetchAll(PDO::FETCH_COLUMN);
    if (empty($authors)) {
        die("No users found in the database. Please create some users first.\n");
    }

    // Insert posts
    $stmt = $pdo->prepare("
        INSERT INTO posts (title, slug, content, author_id, category_id, image, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($posts as $post) {
        // Generate slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $post['title'])));
        
        // Random date from last 6 months
        $date = date('Y-m-d H:i:s', strtotime('-' . rand(1, 180) . ' days'));
        
        // Random author
        $author_id = $authors[array_rand($authors)];

        $stmt->execute([
            $post['title'],
            $slug,
            $post['content'],
            $author_id,
            $post['category_id'],
            $post['image'],
            $date
        ]);

        echo "Created post: {$post['title']}\n";
    }

    echo "\nAdditional demo posts created successfully!\n";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?> 