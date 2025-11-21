<?php
/**
 * EDU Career India - General Site Settings
 * Logo, favicon, contact info, and site-wide settings
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token');
    } else {
        // Update logo and favicon
        updatePageContent('settings', 'branding', 'logo_url', $_POST['logo_url'], 'image');
        updatePageContent('settings', 'branding', 'favicon_url', $_POST['favicon_url'], 'image');
        updatePageContent('settings', 'branding', 'site_name', $_POST['site_name'], 'text');
        updatePageContent('settings', 'branding', 'tagline', $_POST['tagline'], 'text');

        // Update contact info
        updatePageContent('settings', 'contact', 'phone', $_POST['contact_phone'], 'text');
        updatePageContent('settings', 'contact', 'email', $_POST['contact_email'], 'text');
        updatePageContent('settings', 'contact', 'address', $_POST['contact_address'], 'text');

        // Update social media links
        updatePageContent('settings', 'social', 'facebook', $_POST['social_facebook'], 'text');
        updatePageContent('settings', 'social', 'instagram', $_POST['social_instagram'], 'text');
        updatePageContent('settings', 'social', 'linkedin', $_POST['social_linkedin'], 'text');
        updatePageContent('settings', 'social', 'twitter', $_POST['social_twitter'], 'text');
        updatePageContent('settings', 'social', 'youtube', $_POST['social_youtube'], 'text');

        setSuccessMessage('Settings updated successfully!');
        redirect($_SERVER['PHP_SELF']);
    }
}

// Get current settings
$settings = [
    'logo_url' => getPageContent('settings', 'branding', 'logo_url'),
    'favicon_url' => getPageContent('settings', 'branding', 'favicon_url'),
    'site_name' => getPageContent('settings', 'branding', 'site_name') ?: 'EDU Career India',
    'tagline' => getPageContent('settings', 'branding', 'tagline') ?: 'Your Dream, Our Mission',
    'contact_phone' => getPageContent('settings', 'contact', 'phone') ?: '+91-XXXXXXXXXX',
    'contact_email' => getPageContent('settings', 'contact', 'email') ?: 'info@educareerindia.com',
    'contact_address' => getPageContent('settings', 'contact', 'address') ?: 'India',
    'social_facebook' => getPageContent('settings', 'social', 'facebook'),
    'social_instagram' => getPageContent('settings', 'social', 'instagram'),
    'social_linkedin' => getPageContent('settings', 'social', 'linkedin'),
    'social_twitter' => getPageContent('settings', 'social', 'twitter'),
    'social_youtube' => getPageContent('settings', 'social', 'youtube'),
];

// Get all uploaded images for selection
$allImages = getUploadedImages('');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings - Admin Panel</title>
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

        .logo-preview {
            width: 200px;
            height: auto;
            max-height: 100px;
            object-fit: contain;
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
                <h1>⚙️ Site Settings</h1>
                <p>Configure site-wide settings, logo, and contact information</p>
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

                <!-- Branding Section -->
                <div class="section">
                    <h2>🎨 Branding</h2>

                    <div class="form-group">
                        <label>Site Logo</label>
                        <input type="hidden" name="logo_url" id="logo_url" value="<?php echo escape($settings['logo_url']); ?>">
                        <div class="image-selector">
                            <?php if ($settings['logo_url']): ?>
                                <img src="<?php echo escape($settings['logo_url']); ?>" class="logo-preview" id="preview_logo_url">
                            <?php else: ?>
                                <div class="image-preview-small" id="preview_logo_url" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;">No logo</div>
                            <?php endif; ?>
                            <button type="button" class="btn-select-image" onclick="openImageSelector('logo_url')">📷 Select Logo</button>
                            <small class="text-muted">Recommended: PNG with transparent background, 200x60px</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Favicon</label>
                        <input type="hidden" name="favicon_url" id="favicon_url" value="<?php echo escape($settings['favicon_url']); ?>">
                        <div class="image-selector">
                            <?php if ($settings['favicon_url']): ?>
                                <img src="<?php echo escape($settings['favicon_url']); ?>" class="image-preview-small" id="preview_favicon_url">
                            <?php else: ?>
                                <div class="image-preview-small" id="preview_favicon_url" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;">No favicon</div>
                            <?php endif; ?>
                            <button type="button" class="btn-select-image" onclick="openImageSelector('favicon_url')">📷 Select Favicon</button>
                            <small class="text-muted">Recommended: PNG or ICO, 32x32px or 64x64px</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Site Name</label>
                        <input type="text" name="site_name" value="<?php echo escape($settings['site_name']); ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Tagline</label>
                        <input type="text" name="tagline" value="<?php echo escape($settings['tagline']); ?>" class="form-control">
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="section">
                    <h2>📞 Contact Information</h2>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="contact_phone" value="<?php echo escape($settings['contact_phone']); ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="contact_email" value="<?php echo escape($settings['contact_email']); ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="contact_address" class="form-control" rows="3"><?php echo escape($settings['contact_address']); ?></textarea>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="section">
                    <h2>🌐 Social Media Links</h2>

                    <div class="form-group">
                        <label>Facebook URL</label>
                        <input type="url" name="social_facebook" value="<?php echo escape($settings['social_facebook']); ?>" class="form-control" placeholder="https://facebook.com/yourpage">
                    </div>

                    <div class="form-group">
                        <label>Instagram URL</label>
                        <input type="url" name="social_instagram" value="<?php echo escape($settings['social_instagram']); ?>" class="form-control" placeholder="https://instagram.com/yourpage">
                    </div>

                    <div class="form-group">
                        <label>LinkedIn URL</label>
                        <input type="url" name="social_linkedin" value="<?php echo escape($settings['social_linkedin']); ?>" class="form-control" placeholder="https://linkedin.com/company/yourpage">
                    </div>

                    <div class="form-group">
                        <label>Twitter/X URL</label>
                        <input type="url" name="social_twitter" value="<?php echo escape($settings['social_twitter']); ?>" class="form-control" placeholder="https://twitter.com/yourpage">
                    </div>

                    <div class="form-group">
                        <label>YouTube URL</label>
                        <input type="url" name="social_youtube" value="<?php echo escape($settings['social_youtube']); ?>" class="form-control" placeholder="https://youtube.com/@yourpage">
                    </div>
                </div>

                <button type="submit" class="btn btn-success">💾 Save Settings</button>
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
            const preview = document.getElementById('preview_' + currentInputId);

            if (currentInputId === 'logo_url') {
                preview.style.background = 'none';
                preview.style.display = 'block';
                preview.src = imageUrl;
                preview.className = 'logo-preview';
            } else {
                preview.style.background = 'none';
                preview.style.display = 'block';
                preview.src = imageUrl;
                preview.className = 'image-preview-small';
            }

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
