<?php
/**
 * EDU Career India - Admin Logout
 */

require_once __DIR__ . '/includes/config.php';

// Destroy session
session_destroy();

// Redirect to login
redirect(ADMIN_URL . '/login.php');
