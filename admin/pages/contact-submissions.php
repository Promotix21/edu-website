<?php
/**
 * EDU Career India - View Contact Form Submissions
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

// Handle mark as read
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    markContactAsRead($_GET['read']);
    setSuccessMessage('Contact marked as read');
    redirect($_SERVER['PHP_SELF']);
}

// Get all submissions
$submissions = getContactSubmissions();
$unreadCount = count(array_filter($submissions, fn($s) => !$s['is_read']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Submissions - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="page-header">
                <h1>📬 Contact Form Submissions</h1>
                <p>Total: <?php echo count($submissions); ?> | Unread: <?php echo $unreadCount; ?></p>
            </div>

            <?php
            $successMsg = getSuccessMessage();
            if ($successMsg): ?>
                <div class="alert alert-success"><?php echo escape($successMsg); ?></div>
            <?php endif; ?>

            <div class="section">
                <?php if (empty($submissions)): ?>
                    <p class="text-muted">No contact submissions received yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Course</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($submissions as $sub): ?>
                                    <tr style="<?php echo !$sub['is_read'] ? 'font-weight: bold; background: #fef3c7;' : ''; ?>">
                                        <td><?php echo date('M d, Y H:i', strtotime($sub['submitted_at'])); ?></td>
                                        <td><?php echo escape($sub['name']); ?></td>
                                        <td><a href="mailto:<?php echo escape($sub['email']); ?>"><?php echo escape($sub['email']); ?></a></td>
                                        <td><a href="tel:<?php echo escape($sub['phone']); ?>"><?php echo escape($sub['phone']); ?></a></td>
                                        <td><?php echo escape($sub['course']); ?></td>
                                        <td><?php echo escape(substr($sub['message'], 0, 100)); ?><?php echo strlen($sub['message']) > 100 ? '...' : ''; ?></td>
                                        <td>
                                            <?php if ($sub['is_read']): ?>
                                                <span class="badge badge-success">Read</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Unread</span>
                                                <a href="?read=<?php echo $sub['id']; ?>" style="font-size: 11px; margin-left: 4px;">Mark Read</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
