<?php

require_once('connection.php');
include('img_upload.php');

// Fungsi untuk menampilkan semua kategori dari database
// Function to fetch all categories from the database
function getAllCategories()
{
    global $pdo;
    $sql = 'SELECT * FROM categories';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Fungsi untuk menyimpan sebuah kategori ke database dan akan return nilai id terakhir dari data yang dimasukkan
// Function to add a new category to the database
function addCategory($category_name, $category_description, $image_base_path, $image_hover_path)
{
    global $pdo;
    $sql = 'INSERT INTO categories (category_name, category_description, image_base_path, image_hover_path)
                VALUES (:category_name, :category_description, :image_base_path, :image_hover_path)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':category_name', $category_name);
    $stmt->bindParam(':category_description', $category_description);
    $stmt->bindParam(':image_base_path', $image_base_path);
    $stmt->bindParam(':image_hover_path', $image_hover_path);

    $success = $stmt->execute();

    if ($success) {
        $lastInsertedId = getCategoryLastId();
        return $lastInsertedId;
    } else {
        return false;
    }
}

// function for adding Image based last id (used on adding to fix bug)
function addImageCategoryById($category_id, $image_base_path, $image_hover_path)
{
    global $pdo;

    $sql = 'UPDATE categories 
                SET 
                image_base_path = :image_base_path,
                image_hover_path = :image_hover_path
                WHERE category_id = :category_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':image_base_path', $image_base_path);
    $stmt->bindParam(':image_hover_path', $image_hover_path);
    $stmt->bindParam(':category_id', $category_id);

    return $stmt->execute();
}


// Function to get category details by ID
function getCategoryById($category_id)
{
    global $pdo;
    $sql = 'SELECT * FROM categories 
                WHERE category_id = :category_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();
    return $stmt->fetch();
}

// Get the last inserted data id
function getCategoryLastId()
{
    global $pdo;
    // Use lastInsertId to get the last inserted ID
    $lastInsertId = $pdo->lastInsertId();
    return $lastInsertId;
}

// Function to update a category in the database
function updateCategory($category_id, $category_name, $category_description, $image_base_path, $image_hover_path)
{
    global $pdo;
    $sql = 'UPDATE categories
                SET category_name = :category_name,
                    category_description = :category_description,
                    image_base_path = :image_base_path,
                    image_hover_path = :image_hover_path
                WHERE category_id = :category_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':category_name', $category_name);
    $stmt->bindParam(':category_description', $category_description);
    $stmt->bindParam(':image_base_path', $image_base_path);
    $stmt->bindParam(':image_hover_path', $image_hover_path);
    $stmt->bindParam(':category_id', $category_id);

    return $stmt->execute();
}

// Call the getCurrentImagePath function to get the current image path
function getCurrentCategoryImagePath($category_id)
{
    global $pdo;
    $sql = 'SELECT image_base_path, image_hover_path 
                FROM categories
                WHERE category_id = :category_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();

    // Fetch the result as an associative array
    $result = $stmt->fetch();

    // Return an array with image paths or an empty array if not found
    return $result ? $result : array('image_base_path' => '', 'image_hover_path' => '');
}
