<?php

function handleImageUpload($fileInputName, $targetDir)
{
    $originalFileName = $_FILES[$fileInputName]['name'];
    $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

    // Generate a unique filename based on the current timestamp
    $newFileName = time() . '_' . mt_rand(1000, 9999) . '.' . $imageFileType;

    $targetFile = $targetDir . $newFileName;
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES[$fileInputName]['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo 'File is not an image.';
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES[$fileInputName]['size'] > 5 * 1024 * 1024) { // Set max upload to 2MB
        echo 'Sorry, your file is too large.';
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
        echo 'Sorry, only JPG, JPEG, PNG files are allowed.';
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo 'Sorry, your file was not uploaded.';
        return false;
    } else {
        // If everything is ok, try to upload file with the new filename
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFile)) {
            return [$newFileName, $targetFile]; // Return the file path on successful upload
        } else {
            echo 'Sorry, there was an error uploading your file.';
            return false;
        }
    }
}
?>
