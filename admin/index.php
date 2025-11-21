<?php
/**
 * EDU Career India - Admin Dashboard
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Require login
requireLogin();

// Get dashboard statistics
$stmt = $pdo->query("SELECT COUNT(*) as total FROM contact_submissions");
$totalContacts = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM contact_submissions WHERE is_read = 0");
$unreadContacts = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM testimonials WHERE is_active = 1");
$activeTestimonials = $stmt->fetch()['total'];

// Get recent contact submissions
$recentContacts = array_slice(getContactSubmissions(), 0, 5);

// Get site statistics
$siteStats = getSiteStats();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="page-header">
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo escape($_SESSION['admin_username']); ?>!</p>
            </div>

            <?php
            $successMsg = getSuccessMessage();
            if ($successMsg): ?>
                <div class="alert alert-success"><?php echo escape($successMsg); ?></div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #3b82f6;">📧</div>
                    <div class="stat-content">
                        <h3><?php echo $totalContacts; ?></h3>
                        <p>Total Contact Submissions</p>
                        <?php if ($unreadContacts > 0): ?>
                            <span class="badge badge-warning"><?php echo $unreadContacts; ?> unread</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #10b981;">💬</div>
                    <div class="stat-content">
                        <h3><?php echo $activeTestimonials; ?></h3>
                        <p>Active Testimonials</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #f59e0b;">🎓</div>
                    <div class="stat-content">
                        <h3><?php echo $siteStats[0]['stat_value'] ?? '0'; ?></h3>
                        <p>Students Counseled</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #8b5cf6;">📊</div>
                    <div class="stat-content">
                        <h3><?php echo $siteStats[1]['stat_value'] ?? '0'; ?>%</h3>
                        <p>Success Rate</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="section">
                <h2>Quick Actions</h2>
                <div class="quick-actions-grid">
                    <a href="media/upload.php" class="quick-action-card">
                        <span class="icon">🖼️</span>
                        <h3>Upload Images</h3>
                        <p>Manage website images</p>
                    </a>

                    <a href="pages/edit-home.php" class="quick-action-card">
                        <span class="icon">🏠</span>
                        <h3>Edit Homepage</h3>
                        <p>Update homepage content</p>
                    </a>

                    <a href="pages/contact-submissions.php" class="quick-action-card">
                        <span class="icon">📬</span>
                        <h3>View Messages</h3>
                        <p>Check contact submissions</p>
                        <?php if ($unreadContacts > 0): ?>
                            <span class="badge badge-danger"><?php echo $unreadContacts; ?></span>
                        <?php endif; ?>
                    </a>

                    <a href="seo/meta-tags.php" class="quick-action-card">
                        <span class="icon">🔍</span>
                        <h3>SEO Settings</h3>
                        <p>Manage meta tags</p>
                    </a>
                </div>
            </div>

            <!-- Recent Contact Submissions -->
            <div class="section">
                <div class="section-header">
                    <h2>Recent Contact Submissions</h2>
                    <a href="pages/contact-submissions.php" class="btn btn-secondary">View All</a>
                </div>

                <?php if (empty($recentContacts)): ?>
                    <p class="text-muted">No contact submissions yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentContacts as $contact): ?>
                                    <tr>
                                        <td><?php echo escape($contact['name']); ?></td>
                                        <td><?php echo escape($contact['email']); ?></td>
                                        <td><?php echo escape($contact['course']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($contact['submitted_at'])); ?></td>
                                        <td>
                                            <?php if ($contact['is_read']): ?>
                                                <span class="badge badge-success">Read</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Unread</span>
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

    <script src="assets/js/admin.js"></script>
</body>
</html>
