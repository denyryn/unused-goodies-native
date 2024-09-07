<?php
function handleImageUpload($fileInputName, $targetDir)
{
    // nama file akan diubah menjadi huruf kecil (termasuk ekstensi)
    $originalFileName = $_FILES[$fileInputName]['name'];
    $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

    // akan generate nama file baru untuk file yang diupload dengan patokan 
    // waktu saat ini ditambah value random.extensi
    $newFileName = time() . '_' . mt_rand(1000, 9999) . '.' . $imageFileType;

    $targetFile = $targetDir . $newFileName;
    $uploadOk = 1;

    // Cek file yang diupload apakah gambar atau bukan dengan menggunakan getimagesize
    // yang dapat membaca ukuran gambar yang diupload
    // Check if image file is a actual image or not
    $check = getimagesize($_FILES[$fileInputName]['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // membatasi ukuran file
    // Check file size
    if ($_FILES[$fileInputName]['size'] > 5 * 1024 * 1024) { // Set max upload to 5MB
        $uploadOk = 0;
    }

    // hanya bisa upload format tertentu
    // Allow certain file formats
    if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
        $uploadOk = 0;
    }

    // Jika satu saja error diatas terjadi, maka program akan langsung diarahkan ke sini
    if ($uploadOk == 0) {
        return false;
    } else {
        // Jika uploadOk tidak bernilai 0 maka akan mencoba uplot
        // If everything is ok, try to upload file with the new filename
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFile)) {
            return ['path' => $targetFile, 'filename' => $newFileName]; // Return the file path on successful upload
        } else {
            echo 'Error.';
            return false;
        }
    }
}