<?php
/**
 * EDU Career India - Edit Homepage Content
 * Here you can change ALL homepage content including images
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token');
    } else {
        // Update hero section
        updatePageContent('home', 'hero', 'title', $_POST['hero_title'], 'text');
        updatePageContent('home', 'hero', 'subtitle', $_POST['hero_subtitle'], 'text');
        updatePageContent('home', 'hero', 'image', $_POST['hero_image'], 'image');

        // Update statistics
        updateSiteStat('students_counseled', $_POST['stat_students']);
        updateSiteStat('success_rate', $_POST['stat_success']);
        updateSiteStat('partner_institutions', $_POST['stat_institutions']);
        updateSiteStat('years_experience', $_POST['stat_experience']);

        // Update service images
        updatePageContent('home', 'services', 'mbbs_image', $_POST['service_mbbs_image'], 'image');
        updatePageContent('home', 'services', 'btech_image', $_POST['service_btech_image'], 'image');
        updatePageContent('home', 'services', 'bpharma_image', $_POST['service_bpharma_image'], 'image');
        updatePageContent('home', 'services', 'agriculture_image', $_POST['service_agriculture_image'], 'image');
        updatePageContent('home', 'services', 'mba_image', $_POST['service_mba_image'], 'image');

        setSuccessMessage('Homepage content updated successfully!');
        redirect($_SERVER['PHP_SELF']);
    }
}

// Get current content
$heroTitle = getPageContent('home', 'hero', 'title') ?: 'Your Gateway to Top Universities in India & Abroad';
$heroSubtitle = getPageContent('home', 'hero', 'subtitle') ?: 'Expert career counseling and direct admission guidance for MBBS, B.Tech, B.Pharma, Agriculture, and MBA programs.';
$heroImage = getPageContent('home', 'hero', 'image') ?: '';

// Get statistics
$stats = getSiteStats();
$statValues = [];
foreach ($stats as $stat) {
    $statValues[$stat['stat_key']] = $stat['stat_value'];
}

// Get service images
$serviceImages = [
    'mbbs' => getPageContent('home', 'services', 'mbbs_image'),
    'btech' => getPageContent('home', 'services', 'btech_image'),
    'bpharma' => getPageContent('home', 'services', 'bpharma_image'),
    'agriculture' => getPageContent('home', 'services', 'agriculture_image'),
    'mba' => getPageContent('home', 'services', 'mba_image')
];

// Get all uploaded images for selection
$allImages = getUploadedImages('');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Homepage - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .image-selector {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 8px;
        }

        .image-preview-small {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #e5e7eb;
        }

        .btn-select-image {
            padding: 8px 16px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .image-grid-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            overflow-y: auto;
            padding: 40px 20px;
        }

        .image-grid-modal.active {
            display: block;
        }

        .modal-content {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-close-modal {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        .image-grid-small {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
        }

        .selectable-image {
            cursor: pointer;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s;
            border: 3px solid transparent;
        }

        .selectable-image:hover {
            transform: scale(1.05);
            border-color: #2563eb;
        }

        .selectable-image img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="page-header">
                <h1>🏠 Edit Homepage</h1>
                <p>Update homepage content, images, and statistics</p>
            </div>

            <?php
            $successMsg = getSuccessMessage();
            $errorMsg = getErrorMessage();

            if ($successMsg): ?>
                <div class="alert alert-success"><?php echo escape($successMsg); ?></div>
            <?php endif; ?>

            <?php if ($errorMsg): ?>
                <div class="alert alert-error"><?php echo escape($errorMsg); ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <!-- Hero Section -->
                <div class="section">
                    <h2>Hero Section</h2>

                    <div class="form-group">
                        <label>Hero Title</label>
                        <input type="text" name="hero_title" value="<?php echo escape($heroTitle); ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Hero Subtitle</label>
                        <textarea name="hero_subtitle" class="form-control" rows="3" required><?php echo escape($heroSubtitle); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Hero Background Image</label>
                        <input type="hidden" name="hero_image" id="hero_image" value="<?php echo escape($heroImage); ?>">
                        <div class="image-selector">
                            <?php if ($heroImage): ?>
                                <img src="<?php echo escape($heroImage); ?>" class="image-preview-small" id="preview_hero_image">
                            <?php else: ?>
                                <div class="image-preview-small" id="preview_hero_image" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;">No image</div>
                            <?php endif; ?>
                            <button type="button" class="btn-select-image" onclick="openImageSelector('hero_image')">📷 Select Image</button>
                            <small class="text-muted">Recommended: hero-banner-home.jpg (1920x1080px)</small>
                        </div>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div class="section">
                    <h2>Statistics</h2>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                        <div class="form-group">
                            <label>Students Counseled</label>
                            <input type="number" name="stat_students" value="<?php echo $statValues['students_counseled'] ?? 5000; ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Success Rate (%)</label>
                            <input type="number" name="stat_success" value="<?php echo $statValues['success_rate'] ?? 95; ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Partner Institutions</label>
                            <input type="number" name="stat_institutions" value="<?php echo $statValues['partner_institutions'] ?? 200; ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Years of Experience</label>
                            <input type="number" name="stat_experience" value="<?php echo $statValues['years_experience'] ?? 15; ?>" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Service Images -->
                <div class="section">
                    <h2>Service Section Images</h2>

                    <?php
                    $services = [
                        'mbbs' => 'MBBS Program',
                        'btech' => 'B.Tech Engineering',
                        'bpharma' => 'B.Pharma',
                        'agriculture' => 'Agriculture',
                        'mba' => 'MBA/PGDM'
                    ];

                    foreach ($services as $key => $label): ?>
                        <div class="form-group">
                            <label><?php echo $label; ?> Image</label>
                            <input type="hidden" name="service_<?php echo $key; ?>_image" id="service_<?php echo $key; ?>_image" value="<?php echo escape($serviceImages[$key]); ?>">
                            <div class="image-selector">
                                <?php if ($serviceImages[$key]): ?>
                                    <img src="<?php echo escape($serviceImages[$key]); ?>" class="image-preview-small" id="preview_service_<?php echo $key; ?>_image">
                                <?php else: ?>
                                    <div class="image-preview-small" id="preview_service_<?php echo $key; ?>_image" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;">No image</div>
                                <?php endif; ?>
                                <button type="button" class="btn-select-image" onclick="openImageSelector('service_<?php echo $key; ?>_image')">📷 Select Image</button>
                                <small class="text-muted">Recommended: service-<?php echo $key; ?>.jpg (1200x675px)</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn btn-success">💾 Save Changes</button>
            </form>

        </main>
    </div>

    <!-- Image Selector Modal -->
    <div id="imageModal" class="image-grid-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Select an Image</h2>
                <button type="button" class="btn-close-modal" onclick="closeImageSelector()">✕ Close</button>
            </div>

            <p class="text-muted mb-3">💡 Upload images from <a href="../media/upload.php" target="_blank">Image Management</a> first if you don't see your image here.</p>

            <div class="image-grid-small">
                <?php foreach ($allImages as $image): ?>
                    <div class="selectable-image" onclick="selectImage('<?php echo escape($image['url']); ?>')">
                        <img src="<?php echo escape($image['url']); ?>" alt="">
                        <small style="display: block; padding: 4px; font-size: 10px; text-align: center;"><?php echo escape($image['filename']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        let currentInputId = '';

        function openImageSelector(inputId) {
            currentInputId = inputId;
            document.getElementById('imageModal').classList.add('active');
        }

        function closeImageSelector() {
            document.getElementById('imageModal').classList.remove('active');
        }

        function selectImage(imageUrl) {
            document.getElementById(currentInputId).value = imageUrl;
            document.getElementById('preview_' + currentInputId).src = imageUrl;
            closeImageSelector();
        }

        // Close modal on outside click
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageSelector();
            }
        });
    </script>
    <script src="../assets/js/admin.js"></script>
</body>
</html>
