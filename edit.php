<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: auth.php");
    exit();
}

// Get post by ID
$post_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("
    SELECT p.*, u.username 
    FROM posts p 
    JOIN users u ON p.author_id = u.id 
    WHERE p.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

// Check if post exists and user has permission to edit
if (!$post || ($post['author_id'] !== $_SESSION['user_id'] && !isAdmin())) {
    header("Location: index.php");
    exit();
}

// Handle post update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $new_slug = createSlug($title);
    
    // Validate input
    $errors = [];
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($content)) {
        $errors[] = "Content is required";
    }
    
    // Check if new slug already exists (excluding current post)
    if ($new_slug !== $post['slug']) {
        $stmt = $pdo->prepare("SELECT id FROM posts WHERE slug = ? AND id != ?");
        $stmt->execute([$new_slug, $post_id]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "A post with this title already exists";
        }
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, slug = ?, content = ? WHERE id = ?");
        if ($stmt->execute([$title, $new_slug, $content, $post_id])) {
            header("Location: post.php?slug=" . $new_slug);
            exit();
        } else {
            $errors[] = "Error updating post";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Blog</title>
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
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Edit Post</h2>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Post</button>
                                <a href="post.php?slug=<?php echo $post['slug']; ?>" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html> 