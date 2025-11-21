<?php
/**
 * EDU Career India - Admin Helper Functions
 */

require_once __DIR__ . '/config.php';

/**
 * Upload image file
 */
function uploadImage($file, $subfolder = '') {
    global $pdo;

    // Check if file was uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'No file uploaded or upload error occurred'];
    }

    // Check file size
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'error' => 'File size exceeds maximum allowed size (10MB)'];
    }

    // Validate MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, WebP, and GIF allowed'];
    }

    // Validate extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'error' => 'Invalid file extension'];
    }

    // Generate unique filename
    $newFilename = uniqid() . '-' . time() . '.' . $extension;

    // Create upload directory if it doesn't exist
    $uploadPath = UPLOAD_DIR . $subfolder;
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $fullPath = $uploadPath . '/' . $newFilename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $fullPath)) {
        // Optimize image (basic optimization)
        optimizeImage($fullPath, $extension);

        return [
            'success' => true,
            'filename' => $newFilename,
            'path' => $subfolder . '/' . $newFilename,
            'url' => UPLOAD_URL . $subfolder . '/' . $newFilename
        ];
    }

    return ['success' => false, 'error' => 'Failed to move uploaded file'];
}

/**
 * Basic image optimization
 */
function optimizeImage($path, $extension) {
    $maxWidth = 1920;
    $quality = 85;

    list($width, $height) = @getimagesize($path);

    if (!$width || !$height) {
        return false;
    }

    // Skip if image is already small enough
    if ($width <= $maxWidth) {
        return true;
    }

    $newWidth = $maxWidth;
    $newHeight = floor($height * ($maxWidth / $width));

    try {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $source = imagecreatefromjpeg($path);
                $optimized = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($optimized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagejpeg($optimized, $path, $quality);
                imagedestroy($source);
                imagedestroy($optimized);
                break;

            case 'png':
                $source = imagecreatefrompng($path);
                $optimized = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($optimized, false);
                imagesavealpha($optimized, true);
                imagecopyresampled($optimized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagepng($optimized, $path, 8);
                imagedestroy($source);
                imagedestroy($optimized);
                break;
        }
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get all uploaded images
 */
function getUploadedImages($subfolder = '') {
    $uploadPath = UPLOAD_DIR . $subfolder;
    $images = [];

    if (!is_dir($uploadPath)) {
        return $images;
    }

    $files = scandir($uploadPath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;

        $filePath = $uploadPath . '/' . $file;
        if (is_file($filePath)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, ALLOWED_EXTENSIONS)) {
                $images[] = [
                    'filename' => $file,
                    'path' => $subfolder . '/' . $file,
                    'url' => UPLOAD_URL . $subfolder . '/' . $file,
                    'size' => filesize($filePath),
                    'modified' => filemtime($filePath)
                ];
            }
        }
    }

    // Sort by modified time (newest first)
    usort($images, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });

    return $images;
}

/**
 * Delete image
 */
function deleteImage($filename, $subfolder = '') {
    $filePath = UPLOAD_DIR . $subfolder . '/' . $filename;

    if (file_exists($filePath)) {
        return unlink($filePath);
    }

    return false;
}

/**
 * Get page content
 */
function getPageContent($page, $section = null, $key = null) {
    global $pdo;

    $query = "SELECT * FROM page_content WHERE page_name = ? AND is_active = 1";
    $params = [$page];

    if ($section !== null) {
        $query .= " AND section_name = ?";
        $params[] = $section;
    }

    if ($key !== null) {
        $query .= " AND content_key = ?";
        $params[] = $key;
    }

    $query .= " ORDER BY display_order ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    if ($key !== null) {
        $result = $stmt->fetch();
        return $result ? $result['content_value'] : '';
    }

    return $stmt->fetchAll();
}

/**
 * Update page content
 */
function updatePageContent($page, $section, $key, $value, $type = 'text') {
    global $pdo;

    // Check if content exists
    $stmt = $pdo->prepare("SELECT id FROM page_content WHERE page_name = ? AND section_name = ? AND content_key = ?");
    $stmt->execute([$page, $section, $key]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Update existing
        $stmt = $pdo->prepare("UPDATE page_content SET content_value = ?, content_type = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$value, $type, $existing['id']]);
    } else {
        // Insert new
        $stmt = $pdo->prepare("INSERT INTO page_content (page_name, section_name, content_key, content_value, content_type) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$page, $section, $key, $value, $type]);
    }
}

/**
 * Get SEO meta tags for a page
 */
function getSEOMeta($page) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM seo_meta WHERE page_name = ?");
    $stmt->execute([$page]);

    return $stmt->fetch();
}

/**
 * Update SEO meta tags
 */
function updateSEOMeta($page, $title, $description, $keywords, $canonical, $ogImage) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM seo_meta WHERE page_name = ?");
    $stmt->execute([$page]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt = $pdo->prepare("UPDATE seo_meta SET meta_title = ?, meta_description = ?, focus_keywords = ?, canonical_url = ?, og_image = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$title, $description, $keywords, $canonical, $ogImage, $existing['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO seo_meta (page_name, meta_title, meta_description, focus_keywords, canonical_url, og_image) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$page, $title, $description, $keywords, $canonical, $ogImage]);
    }
}

/**
 * Get site statistics
 */
function getSiteStats() {
    global $pdo;

    $stmt = $pdo->query("SELECT * FROM site_statistics ORDER BY display_order ASC");
    return $stmt->fetchAll();
}

/**
 * Update site statistic
 */
function updateSiteStat($key, $value) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE site_statistics SET stat_value = ? WHERE stat_key = ?");
    return $stmt->execute([$value, $key]);
}

/**
 * Get testimonials
 */
function getTestimonials($activeOnly = true) {
    global $pdo;

    $query = "SELECT * FROM testimonials";
    if ($activeOnly) {
        $query .= " WHERE is_active = 1";
    }
    $query .= " ORDER BY display_order ASC, created_at DESC";

    $stmt = $pdo->query($query);
    return $stmt->fetchAll();
}

/**
 * Get contact submissions
 */
function getContactSubmissions($unreadOnly = false) {
    global $pdo;

    $query = "SELECT * FROM contact_submissions";
    if ($unreadOnly) {
        $query .= " WHERE is_read = 0";
    }
    $query .= " ORDER BY submitted_at DESC";

    $stmt = $pdo->query($query);
    return $stmt->fetchAll();
}

/**
 * Mark contact submission as read
 */
function markContactAsRead($id) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE contact_submissions SET is_read = 1 WHERE id = ?");
    return $stmt->execute([$id]);
}
