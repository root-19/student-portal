<?php

require_once 'Module.php';
require_once __DIR__ . '/../Database/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create database connection
    $database = new Database();
    $db = $database->connect();

    // Pass the database connection to the Module class
    $module = new Module($db);

    $title = $_POST['title'];
    $images = $_FILES['images'];

    $uploadedImages = [];
    $uploadDir = __DIR__ . '/../uploads/';

    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

  // Validate and process each uploaded image
foreach ($images['tmp_name'] as $key => $tmpName) {
    if ($images['error'][$key] === UPLOAD_ERR_OK) {
        $fileName = uniqid() . "_" . basename($images['name'][$key]); 
        $targetPath = $uploadDir . $fileName; // Define the target path

        // Validate if the file is an image
        $fileType = mime_content_type($tmpName);
        if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            if (move_uploaded_file($tmpName, $targetPath)) {
                // Save relative path to database
                $uploadedImages[] = 'uploads/' . $fileName;
            } else {
                echo "Failed to move the file: " . $fileName;
            }
        } else {
            echo "Invalid file type for: " . $fileName;
        }
    } else {
        echo "Error uploading file: " . $images['name'][$key];
    }
}

    // Save module with title and uploaded images
    if ($module->createModule($title, $uploadedImages)) {
        header('Location: ../Views/admin/module.php?success=1');
        exit;
    } else {
        echo "Failed to save the module.";
    }
}  