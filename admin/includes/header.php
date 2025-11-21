<header class="admin-header">
    <div class="admin-header-left">
        <h2 class="admin-logo">EDU Career <span>Admin</span></h2>
    </div>
    <div class="admin-header-right">
        <span class="admin-user">👤 <?php echo escape($_SESSION['admin_username']); ?></span>
        <a href="<?php echo ADMIN_URL; ?>/logout.php" class="btn-logout">Logout</a>
    </div>
</header>
