<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Manage Testimonials</title><link rel="stylesheet" href="../assets/css/admin.css"></head>
<body>
<?php include '../includes/header.php'; ?>
<div class="admin-container"><?php include '../includes/sidebar.php'; ?>
<main class="admin-main"><div class="page-header"><h1>💬 Manage Testimonials</h1><p>Add, edit, and delete testimonials...</p></div>
<div class="section"><p>This page will allow you to manage student testimonials.</p>
<p>For now, you can manage photos via <a href="../media/upload.php">Image Management</a>.</p></div>
</main></div>
<script src="../assets/js/admin.js"></script>
</body></html>
