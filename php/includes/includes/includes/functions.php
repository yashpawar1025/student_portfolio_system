<?php
require_once 'config.php';

// Handle file uploads (certificates, profile pics)
function handleFileUpload($file, $uploadDir = 'uploads/') {
    // Create uploads folder if missing
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

    // Generate unique filename
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    // Validate file type (images/PDFs only)
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['error' => 'Invalid file type. Only images/PDFs allowed.'];
    }

    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['error' => 'File too large. Max 5MB allowed.'];
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => $targetPath];
    } else {
        return ['error' => 'Upload failed.'];
    }
}
?>