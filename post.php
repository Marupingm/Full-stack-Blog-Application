<?php
session_start();
require_once 'db.php';

// Get post by slug
$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("
    SELECT p.*, u.username, u.email 
    FROM posts p 
    JOIN users u ON p.author_id = u.id 
    WHERE p.slug = ?
");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header("Location: index.php");
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$post['id'], $_SESSION['user_id'], $content]);
        header("Location: post.php?slug=" . $slug . "#comments");
        exit();
    }
}

// Get comments for this post
$stmt = $pdo->prepare("
    SELECT c.*, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.post_id = ? 
    ORDER BY c.created_at DESC
");
$stmt->execute([$post['id']]);
$comments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Blog</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
</head>
<body>
    <!-- Header -->
    <header class="header fixed-top navbar-expand-xl">
        <div class="container-fluid">
            <div class="header__main">
                <div class="logo">
                    <a href="index.php" class="logo__link">Blog</a>
                </div>
                <div class="header__navbar">
                    <nav class="navbar">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">Home</a>
                                </li>
                                <?php if (isLoggedIn()): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="create.php">Create Post</a>
                                    </li>
                                    <?php if (isAdmin()): ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="admin.php">Admin Panel</a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="auth.php?logout">Logout</a>
                                    </li>
                                <?php else: ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="auth.php">Login/Register</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container mt-5">
            <article class="blog-post">
                <div class="blog-post__header">
                    <h1 class="blog-post__title"><?php echo htmlspecialchars($post['title']); ?></h1>
                    <div class="blog-post__meta">
                        <span class="blog-post__author">By <?php echo htmlspecialchars($post['username']); ?></span>
                        <span class="blog-post__date"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                    </div>
                </div>

                <div class="blog-post__content">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>

                <?php if (isLoggedIn() && ($_SESSION['user_id'] === $post['author_id'] || isAdmin())): ?>
                    <div class="blog-post__actions mt-4">
                        <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Edit Post</a>
                        <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</a>
                    </div>
                <?php endif; ?>

                <!-- Comments Section -->
                <section id="comments" class="comments-section mt-5">
                    <h3 class="comments-section__title">Comments (<?php echo count($comments); ?>)</h3>

                    <?php if (isLoggedIn()): ?>
                        <form method="POST" class="comment-form mt-4">
                            <div class="mb-3">
                                <label for="content" class="form-label">Add a Comment</label>
                                <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Comment</button>
                        </form>
                    <?php else: ?>
                        <p class="text-muted">Please <a href="auth.php">login</a> to leave a comment.</p>
                    <?php endif; ?>

                    <div class="comments-list mt-4">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-card mb-3">
                                <div class="comment-card__header">
                                    <span class="comment-card__author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                    <span class="comment-card__date"><?php echo date('F j, Y', strtotime($comment['created_at'])); ?></span>
                                </div>
                                <div class="comment-card__content">
                                    <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </article>
        </div>
    </main>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html> 