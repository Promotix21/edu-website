<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Site Settings</title><link rel="stylesheet" href="../assets/css/admin.css"></head>
<body>
<?php include '../includes/header.php'; ?>
<div class="admin-container"><?php include '../includes/sidebar.php'; ?>
<main class="admin-main"><div class="page-header"><h1>⚙️ Site Settings</h1><p>Configure site settings...</p></div>
<div class="section"><p>This page will allow you to edit site-wide settings, contact information, and social media links.</p></div>
</main></div>
<script src="../assets/js/admin.js"></script>
</body></html>
