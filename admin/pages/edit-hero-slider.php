<?php
/**
 * EDU Career India - Edit Hero Slider
 * Manage multiple hero banner slides
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

// Handle form submission - Add/Edit slide
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token');
    } else {
        $action = $_POST['action'];

        if ($action === 'add_slide' || $action === 'edit_slide') {
            $slideId = $_POST['slide_id'] ?? null;
            $title = $_POST['slide_title'];
            $subtitle = $_POST['slide_subtitle'];
            $imageUrl = $_POST['slide_image'];
            $buttonText = $_POST['slide_button_text'] ?? 'Learn More';
            $buttonLink = $_POST['slide_button_link'] ?? '#';
            $displayOrder = $_POST['display_order'] ?? 1;
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            try {
                if ($action === 'add_slide') {
                    // Insert new slide
                    $stmt = $pdo->prepare("INSERT INTO hero_slider (title, subtitle, image_url, button_text, button_link, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $subtitle, $imageUrl, $buttonText, $buttonLink, $displayOrder, $isActive]);
                    setSuccessMessage('Hero slide added successfully!');
                } else {
                    // Update existing slide
                    $stmt = $pdo->prepare("UPDATE hero_slider SET title = ?, subtitle = ?, image_url = ?, button_text = ?, button_link = ?, display_order = ?, is_active = ? WHERE id = ?");
                    $stmt->execute([$title, $subtitle, $imageUrl, $buttonText, $buttonLink, $displayOrder, $isActive, $slideId]);
                    setSuccessMessage('Hero slide updated successfully!');
                }
                redirect($_SERVER['PHP_SELF']);
            } catch (PDOException $e) {
                setErrorMessage('Database error: ' . $e->getMessage());
            }
        } elseif ($action === 'delete_slide') {
            $slideId = $_POST['slide_id'];
            try {
                $stmt = $pdo->prepare("DELETE FROM hero_slider WHERE id = ?");
                $stmt->execute([$slideId]);
                setSuccessMessage('Hero slide deleted successfully!');
                redirect($_SERVER['PHP_SELF']);
            } catch (PDOException $e) {
                setErrorMessage('Database error: ' . $e->getMessage());
            }
        }
    }
}

// Get all hero slides
try {
    $stmt = $pdo->query("SELECT * FROM hero_slider ORDER BY display_order ASC, id DESC");
    $slides = $stmt->fetchAll();
} catch (PDOException $e) {
    // Table might not exist yet - create it
    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS hero_slider (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                subtitle TEXT,
                image_url VARCHAR(500) NOT NULL,
                button_text VARCHAR(100) DEFAULT 'Learn More',
                button_link VARCHAR(500) DEFAULT '#',
                display_order INT DEFAULT 1,
                is_active BOOLEAN DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
        $slides = [];
        setSuccessMessage('Hero slider table created successfully! Now add your slides below.');
    } catch (PDOException $e2) {
        die("Error creating table: " . $e2->getMessage());
    }
}

// Get all uploaded images for selection
$allImages = getUploadedImages('');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Slider - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .slides-list {
            display: grid;
            gap: 20px;
            margin-bottom: 40px;
        }

        .slide-item {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            display: grid;
            grid-template-columns: 200px 1fr auto;
            gap: 20px;
            align-items: center;
        }

        .slide-item.inactive {
            opacity: 0.6;
            border-color: #fca5a5;
        }

        .slide-preview-img {
            width: 200px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .slide-details h3 {
            margin: 0 0 8px 0;
            color: #1f2937;
        }

        .slide-details p {
            margin: 0 0 12px 0;
            color: #6b7280;
            font-size: 14px;
        }

        .slide-meta {
            font-size: 12px;
            color: #9ca3af;
        }

        .slide-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #3b82f6;
            color: white;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .add-slide-form {
            background: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 30px;
            margin-top: 40px;
        }

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
                <h1>🎬 Edit Hero Slider</h1>
                <p>Manage multiple hero banner slides for the homepage</p>
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

            <!-- Existing Slides -->
            <h2>📊 Current Slides</h2>
            <?php if (empty($slides)): ?>
                <p style="color: #6b7280; padding: 20px; background: #f9fafb; border-radius: 8px;">
                    No slides yet. Add your first slide below!
                </p>
            <?php else: ?>
                <div class="slides-list">
                    <?php foreach ($slides as $slide): ?>
                        <div class="slide-item <?php echo $slide['is_active'] ? '' : 'inactive'; ?>">
                            <img src="<?php echo escape($slide['image_url']); ?>" alt="" class="slide-preview-img">

                            <div class="slide-details">
                                <h3><?php echo escape($slide['title']); ?></h3>
                                <p><?php echo escape($slide['subtitle']); ?></p>
                                <div class="slide-meta">
                                    Order: <?php echo $slide['display_order']; ?> |
                                    Button: "<?php echo escape($slide['button_text']); ?>" →
                                    <?php echo escape($slide['button_link']); ?> |
                                    <?php echo $slide['is_active'] ? '<strong style="color: #10b981;">Active</strong>' : '<strong style="color: #ef4444;">Inactive</strong>'; ?>
                                </div>
                            </div>

                            <div class="slide-actions">
                                <button class="btn-small btn-edit" onclick="editSlide(<?php echo htmlspecialchars(json_encode($slide)); ?>)">✏️ Edit</button>
                                <form method="POST" style="margin: 0;" onsubmit="return confirm('Delete this slide?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="delete_slide">
                                    <input type="hidden" name="slide_id" value="<?php echo $slide['id']; ?>">
                                    <button type="submit" class="btn-small btn-delete">🗑️ Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Add/Edit Slide Form -->
            <div class="add-slide-form">
                <h2 id="form-title">➕ Add New Slide</h2>

                <form method="POST" id="slideForm">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="action" value="add_slide" id="form_action">
                    <input type="hidden" name="slide_id" id="slide_id">

                    <div class="form-group">
                        <label>Slide Title *</label>
                        <input type="text" name="slide_title" id="slide_title" class="form-control" required placeholder="Your Gateway to Top Universities">
                    </div>

                    <div class="form-group">
                        <label>Slide Subtitle *</label>
                        <textarea name="slide_subtitle" id="slide_subtitle" class="form-control" rows="3" required placeholder="Expert career counseling and direct admission guidance..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Slide Image * (1920x1080px recommended)</label>
                        <input type="hidden" name="slide_image" id="slide_image" required>
                        <div class="image-selector">
                            <div class="image-preview-small" id="preview_slide_image" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;">No image</div>
                            <button type="button" class="btn-select-image" onclick="openImageSelector('slide_image')">📷 Select Image</button>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Button Text</label>
                            <input type="text" name="slide_button_text" id="slide_button_text" class="form-control" value="Learn More">
                        </div>

                        <div class="form-group">
                            <label>Button Link</label>
                            <input type="text" name="slide_button_link" id="slide_button_link" class="form-control" value="/contact">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Display Order</label>
                            <input type="number" name="display_order" id="display_order" class="form-control" value="<?php echo count($slides) + 1; ?>" min="1">
                        </div>

                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" name="is_active" id="is_active" checked>
                                <span>Active (show on homepage)</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success" id="form_submit_btn">💾 Add Slide</button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()" style="margin-left: 12px;">🔄 Reset</button>
                </form>
            </div>

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
            preview.style.background = 'none';
            preview.innerHTML = '<img src="' + imageUrl + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">';
            closeImageSelector();
        }

        function editSlide(slide) {
            // Switch to edit mode
            document.getElementById('form-title').textContent = '✏️ Edit Slide';
            document.getElementById('form_action').value = 'edit_slide';
            document.getElementById('slide_id').value = slide.id;
            document.getElementById('form_submit_btn').textContent = '💾 Update Slide';

            // Fill form with slide data
            document.getElementById('slide_title').value = slide.title;
            document.getElementById('slide_subtitle').value = slide.subtitle;
            document.getElementById('slide_image').value = slide.image_url;
            document.getElementById('slide_button_text').value = slide.button_text;
            document.getElementById('slide_button_link').value = slide.button_link;
            document.getElementById('display_order').value = slide.display_order;
            document.getElementById('is_active').checked = slide.is_active == 1;

            // Update preview
            const preview = document.getElementById('preview_slide_image');
            preview.style.background = 'none';
            preview.innerHTML = '<img src="' + slide.image_url + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">';

            // Scroll to form
            document.getElementById('slideForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function resetForm() {
            document.getElementById('form-title').textContent = '➕ Add New Slide';
            document.getElementById('form_action').value = 'add_slide';
            document.getElementById('slide_id').value = '';
            document.getElementById('form_submit_btn').textContent = '💾 Add Slide';
            document.getElementById('slideForm').reset();

            const preview = document.getElementById('preview_slide_image');
            preview.style.background = '#f3f4f6';
            preview.innerHTML = 'No image';
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
