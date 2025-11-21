<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>SEO Meta Tags</title><link rel="stylesheet" href="../assets/css/admin.css"></head>
<body>
<?php include '../includes/header.php'; ?>
<div class="admin-container"><?php include '../includes/sidebar.php'; ?>
<main class="admin-main"><div class="page-header"><h1>🔍 SEO Meta Tags</h1><p>Manage meta tags for all pages...</p></div>
<div class="section"><p>This page will allow you to edit SEO meta tags, descriptions, keywords, and Open Graph images for each page.</p></div>
</main></div>
<script src="../assets/js/admin.js"></script>
</body></html>
