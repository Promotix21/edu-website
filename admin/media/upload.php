<?php
/**
 * EDU Career India - Image Upload & Management
 * This is where you can upload and manage all website images
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token');
    } else if (!isset($_FILES['image'])) {
        setErrorMessage('No image file selected');
    } else {
        $subfolder = $_POST['subfolder'] ?? '';
        $result = uploadImage($_FILES['image'], $subfolder);

        if ($result['success']) {
            setSuccessMessage('Image uploaded successfully!');
        } else {
            setErrorMessage($result['error']);
        }
    }

    redirect($_SERVER['PHP_SELF']);
}

// Handle image deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token');
    } else {
        $filename = $_POST['filename'] ?? '';
        $subfolder = $_POST['subfolder'] ?? '';

        if (deleteImage($filename, $subfolder)) {
            setSuccessMessage('Image deleted successfully!');
        } else {
            setErrorMessage('Failed to delete image');
        }
    }

    redirect($_SERVER['PHP_SELF'] . '?folder=' . urlencode($subfolder));
}

// Get current folder
$currentFolder = $_GET['folder'] ?? '';

// Get uploaded images
$images = getUploadedImages($currentFolder);

// Image categories
$categories = [
    '' => 'All Images',
    'hero' => 'Hero Banners',
    'services' => 'Service Images',
    'courses' => 'Course Images',
    'testimonials' => 'Testimonial Photos',
    'icons' => 'Icons & Graphics',
    'backgrounds' => 'Background Images',
    'misc' => 'Miscellaneous'
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Management - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .image-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .image-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f3f4f6;
        }

        .image-info {
            padding: 12px;
        }

        .image-filename {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            word-break: break-all;
        }

        .image-meta {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .image-url {
            font-size: 11px;
            background: #f3f4f6;
            padding: 6px 8px;
            border-radius: 4px;
            word-break: break-all;
            margin-bottom: 10px;
            font-family: monospace;
        }

        .image-actions {
            display: flex;
            gap: 8px;
        }

        .btn-copy {
            flex: 1;
            padding: 6px 12px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-delete {
            padding: 6px 12px;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-copy:hover {
            background: #2563eb;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        .upload-zone {
            background: white;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
        }

        .folder-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .folder-tab {
            padding: 8px 16px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .folder-tab:hover,
        .folder-tab.active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="page-header">
                <h1>📸 Image Management</h1>
                <p>Upload and manage all website images from here</p>
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

            <!-- Upload Form -->
            <div class="upload-zone">
                <h2>📤 Upload New Image</h2>
                <p style="color: #6b7280; margin-bottom: 20px;">Maximum file size: 10MB | Supported formats: JPG, PNG, WebP, GIF</p>

                <form method="POST" enctype="multipart/form-data" style="max-width: 500px; margin: 0 auto;">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="action" value="upload">

                    <div class="form-group">
                        <label>Select Category:</label>
                        <select name="subfolder" class="form-control">
                            <?php foreach ($categories as $folder => $label): ?>
                                <option value="<?php echo $folder; ?>" <?php echo $currentFolder === $folder ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="file" name="image" accept="image/*" required class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </form>
            </div>

            <!-- Folder Tabs -->
            <div class="folder-tabs">
                <?php foreach ($categories as $folder => $label): ?>
                    <a href="?folder=<?php echo urlencode($folder); ?>"
                       class="folder-tab <?php echo $currentFolder === $folder ? 'active' : ''; ?>">
                        <?php echo $label; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Images Grid -->
            <div class="section">
                <h2>Images <?php echo $currentFolder ? "in " . $categories[$currentFolder] : ""; ?> (<?php echo count($images); ?>)</h2>

                <?php if (empty($images)): ?>
                    <p class="text-muted">No images uploaded yet in this category.</p>
                <?php else: ?>
                    <div class="image-grid">
                        <?php foreach ($images as $image): ?>
                            <div class="image-card">
                                <img src="<?php echo escape($image['url']); ?>"
                                     alt="<?php echo escape($image['filename']); ?>"
                                     class="image-preview">

                                <div class="image-info">
                                    <div class="image-filename"><?php echo escape($image['filename']); ?></div>

                                    <div class="image-meta">
                                        Size: <?php echo round($image['size'] / 1024, 1); ?> KB<br>
                                        Uploaded: <?php echo date('M d, Y', $image['modified']); ?>
                                    </div>

                                    <div class="image-url" id="url-<?php echo md5($image['url']); ?>">
                                        <?php echo escape($image['url']); ?>
                                    </div>

                                    <div class="image-actions">
                                        <button class="btn-copy" onclick="copyURL('<?php echo $image['url']; ?>', 'url-<?php echo md5($image['url']); ?>')">
                                            📋 Copy URL
                                        </button>

                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="filename" value="<?php echo escape($image['filename']); ?>">
                                            <input type="hidden" name="subfolder" value="<?php echo escape($currentFolder); ?>">
                                            <button type="submit" class="btn-delete">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <script>
        function copyURL(url, elementId) {
            navigator.clipboard.writeText(url).then(function() {
                const element = document.getElementById(elementId);
                const originalBg = element.style.backgroundColor;
                element.style.backgroundColor = '#10b981';
                element.style.color = 'white';

                setTimeout(function() {
                    element.style.backgroundColor = originalBg;
                    element.style.color = '';
                }, 1000);

                alert('Image URL copied to clipboard!');
            });
        }
    </script>
    <script src="../assets/js/admin.js"></script>
</body>
</html>
