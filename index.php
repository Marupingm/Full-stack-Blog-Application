<?php
session_start();
require_once 'db.php';

// Get categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Pagination
$posts_per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Build query based on filters
$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "p.title LIKE ?";
    $params[] = "%$search%";
}

if ($category) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $category;
}

$where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total posts for pagination
$count_query = "SELECT COUNT(*) FROM posts p $where_clause";
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_posts = $stmt->fetchColumn();
$total_pages = ceil($total_posts / $posts_per_page);

// Get featured posts for slider
$stmt = $pdo->prepare("
    SELECT p.*, u.username, u.email, u.profile_image, c.name as category_name, c.slug as category_slug,
           (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as likes_count
    FROM posts p 
    JOIN users u ON p.author_id = u.id 
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.created_at DESC 
    LIMIT 4
");
$stmt->execute();
$featured_posts = $stmt->fetchAll();

// Get regular posts with pagination
$query = "
    SELECT p.*, u.username, u.email, u.profile_image, c.name as category_name, c.slug as category_slug,
           (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as likes_count
    FROM posts p 
    JOIN users u ON p.author_id = u.id 
    LEFT JOIN categories c ON p.category_id = c.id
    $where_clause
    ORDER BY p.created_at DESC 
    LIMIT " . (int)$offset . ", " . (int)$posts_per_page;

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Home</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Loading -->
    <div class="loading">
        <div class="loading__circle"></div>
    </div>

    <!-- Header -->
    <header class="header fixed-top navbar-expand-xl">
        <div class="container-fluid">
            <div class="header__main">
                <div class="logo">
                    <a href="index.php" class="logo__link logo--dark">Blog</a>
                </div>
                <div class="header__navbar">
                    <nav class="navbar">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" href="index.php">Home</a>
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
                                <?php endif; ?>
                            </ul>

                            <!-- Search Form -->
                            <form class="d-flex me-3" action="index.php" method="GET">
                                <input class="form-control me-2" type="search" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-outline-primary" type="submit">Search</button>
                            </form>

                            <!-- User Menu -->
                            <?php if (isLoggedIn()): ?>
                                <div class="dropdown">
                                    <button class="btn btn-link dropdown-toggle nav-link" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="uploads/profiles/<?php echo $_SESSION['profile_image'] ?? 'default.jpg'; ?>" alt="Profile" class="rounded-circle" width="32" height="32">
                                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                        <li><a class="dropdown-item" href="auth.php?logout">Logout</a></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a href="auth.php" class="btn btn-primary">Login/Register</a>
                            <?php endif; ?>
                        </div>
                    </nav>
                </div>
                
                <!-- Header Actions -->
                <div class="header__action-items">
                    <!-- Theme Switch -->
                    <div class="theme-switch">
                        <label class="theme-switch__label" for="checkbox">
                            <input type="checkbox" id="checkbox" class="theme-switch__checkbox">
                            <span class="theme-switch__slider round">
                                <i class="bi bi-sun icon-light theme-switch__icon theme-switch__icon--light"></i>
                                <i class="bi bi-moon icon-dark theme-switch__icon theme-switch__icon--dark"></i>
                            </span>
                        </label>
                    </div>

                    <!-- Navbar Toggler -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler__icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <!-- Featured Posts Slider -->
        <div class="slider slider--three">
            <div class="swiper-wrapper">
                <?php foreach ($featured_posts as $post): ?>
                    <div class="slider__item swiper-slide" style="background-image: url('uploads/posts/<?php echo $post['image'] ?? 'default.jpg'; ?>');">
                        <div class="slider__item-content">
                            <?php if ($post['category_name']): ?>
                                <a href="index.php?category=<?php echo $post['category_id']; ?>" class="category">
                                    <?php echo htmlspecialchars($post['category_name']); ?>
                                </a>
                            <?php endif; ?>
                            <h4 class="slider__title">
                                <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="slider__title-link">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h4>
                            <ul class="slider__meta list-inline">
                                <li class="slider__meta-item">
                                    <a href="#" class="slider__meta-link">
                                        <img src="uploads/profiles/<?php echo $post['profile_image'] ?? 'default.jpg'; ?>" alt="<?php echo htmlspecialchars($post['username']); ?>" class="slider__meta-img">
                                    </a>
                                </li>
                                <li class="slider__meta-item">
                                    <a href="#" class="slider__meta-link"><?php echo htmlspecialchars($post['username']); ?></a>
                                </li>
                                <li class="slider__meta-item">
                                    <span class="dot"></span> <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                </li>
                                <li class="slider__meta-item">
                                    <span class="dot"></span> <i class="bi bi-heart-fill text-danger"></i> <?php echo $post['likes_count']; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Slider Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>

        <!-- Categories Filter -->
        <div class="container-fluid mt-4">
            <div class="categories-filter">
                <a href="index.php" class="btn <?php echo !$category ? 'btn-primary' : 'btn-outline-primary'; ?> me-2">All</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="index.php?category=<?php echo $cat['id']; ?>" 
                       class="btn <?php echo $category == $cat['id'] ? 'btn-primary' : 'btn-outline-primary'; ?> me-2">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Blog Posts Grid -->
        <section class="mt-30 blog-home-3">
            <div class="container-fluid">
                <div class="row">
                    <?php if (empty($posts)): ?>
                        <div class="col-12 text-center">
                            <h3>No posts found</h3>
                            <?php if ($search || $category): ?>
                                <p>Try different search terms or categories</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="col-lg-6 col-md-6">
                                <div class="post-card post-card--default">
                                    <div class="post-card__image">
                                        <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                                            <img src="uploads/posts/<?php echo $post['image'] ?? 'default.jpg'; ?>" 
                                                 alt="<?php echo htmlspecialchars($post['title']); ?>">
                                        </a>
                                        <?php if ($post['category_name']): ?>
                                            <a href="index.php?category=<?php echo $post['category_id']; ?>" 
                                               class="category">
                                                <?php echo htmlspecialchars($post['category_name']); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="post-card__content">
                                        <h4 class="post-card__title">
                                            <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="post-card__title-link">
                                                <?php echo htmlspecialchars($post['title']); ?>
                                            </a>
                                        </h4>
                                        <p class="post-card__exerpt">
                                            <?php echo substr(strip_tags($post['content']), 0, 150) . '...'; ?>
                                        </p>
                                        <ul class="post-card__meta list-inline">
                                            <li class="post-card__meta-item">
                                                <a href="#" class="post-card__meta-link">
                                                    <img src="uploads/profiles/<?php echo $post['profile_image'] ?? 'default.jpg'; ?>" 
                                                         alt="<?php echo htmlspecialchars($post['username']); ?>" 
                                                         class="post-card__meta-img">
                                                </a>
                                            </li>
                                            <li class="post-card__meta-item">
                                                <a href="#" class="post-card__meta-link">
                                                    <?php echo htmlspecialchars($post['username']); ?>
                                                </a>
                                            </li>
                                            <li class="post-card__meta-item">
                                                <span class="dot"></span> <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                            </li>
                                            <li class="post-card__meta-item">
                                                <span class="dot"></span> 
                                                <i class="bi bi-heart-fill text-danger"></i> 
                                                <?php echo $post['likes_count']; ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="pagination list-inline">
                                <?php if ($page > 1): ?>
                                    <li class="pagination__item">
                                        <a class="pagination__link" href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category ? '&category=' . $category : ''; ?>">
                                            <i class="bi bi-arrow-left pagination__icon"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="pagination__item <?php echo $i === $page ? 'pagination__item--active' : ''; ?>">
                                        <a class="pagination__link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category ? '&category=' . $category : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                    <li class="pagination__item">
                                        <a class="pagination__link" href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category ? '&category=' . $category : ''; ?>">
                                            <i class="bi bi-arrow-right pagination__icon"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="newslettre__section">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 col-md-10 col-sm-11 m-auto">
                        <div class="newslettre">
                            <div class="newslettre__info">
                                <h3 class="newslettre__title">Get The Best Blog Stories into Your inbox!</h3>
                                <p class="newslettre__desc">Sign up for free and be the first to get notified about new posts.</p>
                            </div>

                            <form action="#" class="newslettre__form">
                                <input type="email" class="newslettre__form-input form-control" placeholder="Your email address" required>
                                <button class="newslettre__form-submit" type="submit">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/dark-mode.js"></script>
    <script>
        // Hide preloader when page is loaded
        window.addEventListener('load', function() {
            const loader = document.querySelector('.loading');
            loader.style.opacity = '0';
            setTimeout(function() {
                loader.style.display = 'none';
            }, 600);
        });

        // Initialize Swiper slider
        const swiper = new Swiper('.slider', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 5000,
            },
        });
    </script>
</body>
</html> 