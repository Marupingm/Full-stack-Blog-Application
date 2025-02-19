<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: auth.php");
    exit();
}

$post_id = $_GET['id'] ?? 0;

// Get post information
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

// Check if post exists and user has permission to delete
if (!$post || ($post['author_id'] !== $_SESSION['user_id'] && !isAdmin())) {
    header("Location: index.php");
    exit();
}

// Delete comments first (due to foreign key constraint)
$stmt = $pdo->prepare("DELETE FROM comments WHERE post_id = ?");
$stmt->execute([$post_id]);

// Delete the post
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$post_id]);

// Redirect to homepage
header("Location: index.php");
exit();
?> 