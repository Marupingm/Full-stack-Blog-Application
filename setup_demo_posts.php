<?php
require_once 'db.php';

// Demo post images (web development related from Unsplash)
$post_images = [
    'frontend.jpg' => 'https://images.unsplash.com/photo-1547658719-da2b51169166?w=800&h=400&fit=crop',
    'backend.jpg' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&h=400&fit=crop',
    'devops.jpg' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&h=400&fit=crop',
    'ui-ux.jpg' => 'https://images.unsplash.com/photo-1559028012-481c04fa702d?w=800&h=400&fit=crop',
    'fullstack.jpg' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&h=400&fit=crop',
    'mobile.jpg' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&h=400&fit=crop',
    'cloud.jpg' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&h=400&fit=crop',
    'security.jpg' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=800&h=400&fit=crop',
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

// Demo blog posts data
$posts = [
    [
        'title' => 'Modern JavaScript Features Every Developer Should Know',
        'content' => "JavaScript has evolved significantly over the years, introducing powerful features that make coding more efficient and maintainable. In this comprehensive guide, we'll explore essential modern JavaScript features that every developer should be familiar with.

First, let's dive into arrow functions. These concise function expressions have become a standard in modern JavaScript development. Not only do they provide a more readable syntax, but they also handle the 'this' binding differently from traditional functions, making them particularly useful in callback scenarios.

Another game-changing feature is destructuring assignment. This elegant syntax allows you to unpack values from arrays or properties from objects into distinct variables. It's particularly useful when working with complex data structures or API responses.

The spread and rest operators (...) have revolutionized how we handle arrays and objects. The spread operator lets you expand elements, making array manipulation and function arguments more flexible. Meanwhile, the rest parameter syntax allows you to represent an indefinite number of arguments as an array.

Template literals have transformed string interpolation in JavaScript. Gone are the days of complex string concatenation – now you can embed expressions directly within backtick quotes, making your code more readable and maintainable.

Promises and async/await syntax have dramatically improved asynchronous programming in JavaScript. These features provide a more intuitive way to handle asynchronous operations, replacing the callback hell of the past with clean, synchronous-looking code.

The introduction of modules (import/export) has brought better code organization and dependency management to JavaScript. This feature allows you to split your code into separate files, making it more maintainable and reusable.

Let's not forget about the enhanced object literals, which provide shorthand syntax for object property assignment and method definition. This feature reduces boilerplate code and makes object creation more concise.

Map and Set data structures offer new ways to handle collections in JavaScript. Maps provide a true key-value store with any type of key, while Sets help you work with unique values efficiently.

These features are just the tip of the iceberg. Modern JavaScript continues to evolve, with new proposals and features being added regularly. Staying up-to-date with these developments is crucial for writing efficient, maintainable, and modern JavaScript code.",
        'category_id' => 1, // Frontend
        'image' => 'frontend.jpg'
    ],
    [
        'title' => 'Building Scalable APIs with Laravel 10',
        'content' => "Laravel has established itself as one of the most popular PHP frameworks for building robust web applications and APIs. In this comprehensive guide, we'll explore best practices for building scalable APIs using Laravel 10.

API architecture is crucial for scalability. Laravel's built-in API resources and resource collections provide an elegant way to transform your models into JSON responses. This feature helps maintain consistency in your API responses and makes them easier to version and modify.

Authentication is a critical aspect of API development. Laravel Sanctum offers a lightweight authentication system perfect for SPAs and mobile applications. It provides a simple way to issue API tokens and handle token-based authentication.

Rate limiting is essential for protecting your API from abuse. Laravel's built-in rate limiting features allow you to easily implement request throttling. You can customize the number of requests allowed per minute and handle rate limit exceptions gracefully.

Database optimization plays a vital role in API performance. Laravel's eloquent ORM provides powerful features like eager loading, which helps prevent the N+1 query problem. Proper indexing and query optimization are crucial for maintaining performance as your data grows.

Caching is another crucial aspect of API scalability. Laravel provides a robust caching system that supports various cache drivers. Implementing cache strategies for frequently accessed data can significantly improve response times.

Error handling in APIs requires special attention. Laravel's exception handling system allows you to create consistent error responses. Custom exception handlers help you maintain a uniform error reporting format across your API.

API versioning becomes important as your application evolves. Laravel's routing system makes it easy to implement versioning through URL prefixes or headers, allowing you to maintain backward compatibility while adding new features.

Queue systems help handle time-consuming tasks asynchronously. Laravel's queue system integrates seamlessly with various queue drivers, allowing you to offload heavy processes and improve API response times.

Testing is crucial for maintaining API reliability. Laravel's testing utilities make it easy to write feature tests for your API endpoints. PHPUnit integration allows you to ensure your API behaves correctly under various scenarios.

Documentation is often overlooked but is crucial for API adoption. Tools like Laravel OpenAPI (formerly L5-Swagger) help you generate comprehensive API documentation directly from your code.

Monitoring and logging help you maintain and troubleshoot your API. Laravel's logging system, combined with tools like Telescope, provides detailed insights into your application's behavior.

Security should never be an afterthought. Implementing proper validation, sanitization, and authorization using Laravel's built-in features helps protect your API from common vulnerabilities.",
        'category_id' => 2, // Backend
        'image' => 'backend.jpg'
    ],
    [
        'title' => 'DevOps Best Practices for Modern Web Applications',
        'content' => "DevOps has revolutionized how we build and deploy web applications. This guide explores essential DevOps practices that can streamline your development workflow and improve application reliability.

Continuous Integration and Continuous Deployment (CI/CD) form the backbone of modern DevOps practices. Implementing automated testing and deployment pipelines ensures code quality and reduces the risk of deployment-related issues.

Infrastructure as Code (IaC) has transformed how we manage server infrastructure. Tools like Terraform and Ansible allow you to version control your infrastructure configuration, making it reproducible and maintainable.

Containerization with Docker has become standard practice in DevOps. Containers provide consistency across different environments and make it easier to scale applications horizontally. Docker Compose helps manage multi-container applications effectively.

Kubernetes has emerged as the de-facto standard for container orchestration. It provides powerful features for managing containerized applications, including automatic scaling, rolling updates, and self-healing capabilities.

Monitoring and logging are crucial for maintaining application health. Tools like Prometheus and Grafana provide insights into application performance, while ELK stack helps centralize and analyze logs effectively.

Security should be integrated throughout the DevOps pipeline. Implementing security scanning in your CI/CD pipeline helps identify vulnerabilities early in the development cycle. Regular security audits and updates are essential.

Database management in DevOps requires special attention. Implementing database migrations, backups, and monitoring ensures data integrity and availability. Tools like Flyway help manage database changes effectively.

Performance optimization is an ongoing process in DevOps. Regular performance testing and optimization ensure your application can handle increasing loads. Tools like JMeter help simulate various load scenarios.

Disaster recovery planning is essential for business continuity. Implementing backup strategies, failover mechanisms, and recovery procedures helps minimize downtime in case of failures.

Documentation and knowledge sharing are vital for DevOps success. Maintaining up-to-date documentation and fostering a culture of knowledge sharing helps teams work more effectively.",
        'category_id' => 5, // DevOps
        'image' => 'devops.jpg'
    ],
    [
        'title' => 'Mastering UI/UX Design Principles for Web Applications',
        'content' => "Creating intuitive and engaging user interfaces is crucial for web application success. This comprehensive guide explores essential UI/UX design principles that can elevate your web applications.

User-centered design should be at the core of your development process. Understanding your users' needs, behaviors, and preferences helps create interfaces that resonate with your target audience. Regular user testing and feedback collection are essential.

Visual hierarchy guides users through your interface. Proper use of typography, color, and spacing helps users understand the relationship between different elements and find information quickly. Consistency in design elements creates a cohesive user experience.

Responsive design is no longer optional. Your interface should adapt seamlessly to different screen sizes and devices. Mobile-first design ensures your application remains usable across all platforms.

Accessibility should be considered from the start. Following WCAG guidelines helps make your application usable by people with different abilities. This includes proper color contrast, keyboard navigation, and screen reader compatibility.

Performance impacts user experience significantly. Optimizing load times, implementing lazy loading, and minimizing UI blocking operations helps maintain user engagement. Progressive enhancement ensures basic functionality works even under poor conditions.

Micro-interactions add polish to your interface. Small animations and feedback mechanisms make your application feel more responsive and engaging. However, these should be used judiciously to avoid overwhelming users.

Form design requires special attention. Clear labels, helpful validation messages, and logical field ordering make forms easier to complete. Implementing inline validation provides immediate feedback to users.

Navigation should be intuitive and efficient. Clear menu structures, breadcrumbs, and search functionality help users find what they're looking for. Consistent navigation patterns across the application reduce cognitive load.

Error handling should be user-friendly. Clear error messages, suggested solutions, and easy recovery paths help users overcome problems. Empty states should provide guidance on how to proceed.

Performance monitoring from a UX perspective is crucial. Tools like Google Analytics and heatmaps provide insights into how users interact with your interface. Regular analysis helps identify areas for improvement.",
        'category_id' => 4, // UI/UX
        'image' => 'ui-ux.jpg'
    ],
    [
        'title' => 'Full Stack Development with the MERN Stack',
        'content' => "The MERN (MongoDB, Express.js, React, Node.js) stack has become increasingly popular for full-stack development. This guide explores best practices and patterns for building robust applications with MERN.

Starting with MongoDB, proper database design is crucial. Understanding document structure, relationships, and indexing helps optimize database performance. Mongoose provides a powerful ODM layer for MongoDB in Node.js applications.

Express.js forms the backend foundation. Implementing proper middleware, route organization, and error handling creates a maintainable API structure. RESTful principles help create intuitive API endpoints.

React manages the frontend efficiently. Component organization, state management with hooks, and proper prop drilling alternatives like Context API or Redux help create maintainable frontend code. Code splitting and lazy loading optimize initial load times.

Node.js powers the backend runtime. Understanding event-driven architecture, asynchronous programming, and memory management helps create efficient backend services. Proper error handling and logging are crucial for production applications.

Authentication and authorization require careful implementation. JWT-based authentication, role-based access control, and secure password handling protect your application. Implementing refresh tokens improves security.

API design connects frontend and backend. Consistent error handling, response formatting, and documentation make your API developer-friendly. GraphQL can provide more flexible data fetching capabilities.

State management on the frontend requires careful consideration. Context API handles simple state effectively, while Redux or MobX manage complex application state. Local component state should be used appropriately.

Testing covers both frontend and backend. Unit tests, integration tests, and end-to-end tests ensure application reliability. Jest and React Testing Library handle frontend testing, while Mocha or Jest cover backend testing.

Deployment and DevOps considerations are important. Docker containerization, CI/CD pipelines, and proper environment configuration management streamline deployment. Monitoring and logging help maintain application health.

Security must be implemented at all levels. Input validation, XSS prevention, CSRF protection, and secure headers protect your application. Regular security audits and updates are essential.",
        'category_id' => 3, // Full Stack
        'image' => 'fullstack.jpg'
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

    echo "\nDemo posts created successfully!\n";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?> //  
