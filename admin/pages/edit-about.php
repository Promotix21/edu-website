<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Edit About Page</title><link rel="stylesheet" href="../assets/css/admin.css"></head>
<body>
<?php include '../includes/header.php'; ?>
<div class="admin-container"><?php include '../includes/sidebar.php'; ?>
<main class="admin-main"><div class="page-header"><h1>📄 Edit About Page</h1><p>Content editor coming soon...</p></div>
<div class="section"><p>This page will allow you to edit About page content, images, and team information.</p>
<p>For now, you can manage images via <a href="../media/upload.php">Image Management</a>.</p></div>
</main></div>
<script src="../assets/js/admin.js"></script>
</body></html>
