<?php
session_start();
require_once 'db.php';

// Handle Login
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'] ?? false;
        $_SESSION['profile_image'] = $user['profile_image'];
        
        // Handle remember me
        if (isset($_POST['remember_me'])) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + 30 * 24 * 60 * 60, '/');
            
            $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmt->execute([$token, $user['id']]);
        }
        
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

// Handle Signup
if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $error = "Username or email already exists";
        } else {
            // Create new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Error creating account";
            }
        }
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    setcookie('remember_token', '', time() - 3600, '/');
    header("Location: index.php");
    exit();
}

// If already logged in, redirect to index
if (isset($_SESSION['user_id']) && !isset($_POST['login']) && !isset($_POST['signup'])) {
    header("Location: index.php");
    exit();
}

$is_signup = isset($_GET['signup']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $is_signup ? 'Sign Up' : 'Login'; ?> - Blog</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
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
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <!-- Authentication Section -->
        <section class="m-top mb-60">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-auto">
                        <div class="widget">
                            <h5 class="widget__title"><?php echo $is_signup ? 'Create Account' : 'Login'; ?></h5>
                            
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <?php if ($is_signup): ?>
                                <!-- Sign Up Form -->
                                <form action="auth.php" method="POST" class="widget__form">
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control widget__form-input" placeholder="Username*" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control widget__form-input" placeholder="Email*" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control widget__form-input" placeholder="Password*" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="confirm_password" class="form-control widget__form-input" placeholder="Confirm Password*" required>
                                    </div>
                                    
                                    <div class="widget__form-btn">
                                        <button type="submit" name="signup" class="btn-custom">Create Account</button>
                                    </div>
                                    
                                    <p class="widget__form-text">
                                        Already have an account? <a href="auth.php" class="widget__form-link">Login</a>
                                    </p>
                                </form>
                            <?php else: ?>
                                <!-- Login Form -->
                                <form action="auth.php" method="POST" class="widget__form">
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control widget__form-input" placeholder="Username*" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control widget__form-input" placeholder="Password*" required>
                                    </div>
                                    
                                    <div class="widget__form-controls form-group">
                                        <div class="widget__form-controls-checkbox">
                                            <input type="checkbox" name="remember_me" class="widget__form-controls-input" id="rememberMe">
                                            <label class="widget__form-controls-label" for="rememberMe">Remember Me</label>
                                        </div>
                                        <a href="#" class="widget__form-link ml-auto">Forgot Password?</a>
                                    </div>
                                    
                                    <div class="widget__form-btn">
                                        <button type="submit" name="login" class="btn-custom">Login Now</button>
                                    </div>
                                    
                                    <p class="widget__form-text">
                                        Don't have an account? <a href="auth.php?signup" class="widget__form-link">Create One</a>
                                    </p>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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
    </script>
</body>
</html> //  
