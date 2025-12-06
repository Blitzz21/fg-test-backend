<?php
// src/Controllers/UploadController.php

namespace App\Controllers;

use App\Core\Response;

class UploadController
{
    // Max file size ~25MB (in bytes)
    private int $maxSize = 25 * 1024 * 1024;

    // Allowed MIME types
    private array $allowedTypes = [
        'image/png',
        'image/jpeg',
        'image/jpg',
        'image/svg+xml',
        'application/pdf',
    ];

    public function upload(): void
    {
        // Check existence
        if (!isset($_FILES['file'])) {
            Response::json(['error' => 'No file uploaded (expected field "file")'], 400);
        }

        $file = $_FILES['file'];

        // Check upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Response::json(['error' => 'Upload error code: '.$file['error']], 400);
        }

        // Check size
        if ($file['size'] > $this->maxSize) {
            Response::json(['error' => 'File too large. Max 25MB'], 400);
        }

        // Basic MIME/type validation
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']) ?: 'application/octet-stream';

        if (!in_array($mime, $this->allowedTypes, true)) {
            Response::json(['error' => 'Unsupported file type: '.$mime], 400);
        }

        // Create safe filename
        $originalName = $file['name'];
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $slugBase = preg_replace('/[^a-zA-Z0-9_-]/', '-', pathinfo($originalName, PATHINFO_FILENAME));

        if (!$slugBase) {
            $slugBase = 'artwork';
        }

        $unique  = bin2hex(random_bytes(6));
        $newName = $slugBase.'-'.$unique.($ext ? '.'.$ext : '');

        // Upload directory (inside public)
        $uploadDir = __DIR__.'/../../public/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $newName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            Response::json(['error' => 'Failed to move uploaded file'], 500);
        }

        /**
         * IMPORTANT FIX:
         * Since the PHP server serves the "public" folder as the document root,
         * the correct public URL for files in public/uploads is:
         *
         *     /uploads/<filename>
         *
         * Not /NewShopify/public/uploads
         */
        $publicUrl = '/uploads/' . $newName;

        Response::json([
            'success'        => true,
            'fileName'       => $originalName,
            'storedFileName' => $newName,
            'url'            => $publicUrl,
        ]);
    }
}
