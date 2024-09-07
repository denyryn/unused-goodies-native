<?php
// Function to fetch all products from the database
function getAllProducts()
{
    global $pdo;
    $sql = 'SELECT a.*, b.* 
                FROM products a
                INNER JOIN categories b ON  a.category_id = b.category_id ';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

//Function to fetch specific products by ID from database
function getProductById($product_id)
{
    global $pdo;
    $sql = 'SELECT a.*, b.* 
                FROM products a
                INNER JOIN categories b ON  a.category_id = b.category_id 
                WHERE product_id = :product_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    return $stmt->fetch();
}

// Function to fetch all categories from the database
function getAllCategories()
{
    global $pdo;
    $sql = 'SELECT * FROM categories';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getProductLastId()
{
    global $pdo;
    // Menggunakan lastInsertId(); untuk mendapatkan index id data yang terakhir dimasukkan ke database
    // Use lastInsertId to get the last inserted ID
    $lastInsertId = $pdo->lastInsertId();
    return $lastInsertId;
}


//Function to get image_path column based on product_id
function getCurrentImagePath($product_id)
{
    global $pdo;
    $sql = 'SELECT image_path 
                FROM products
                WHERE product_id = :product_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $result = $stmt->fetch();

    // if else akan return kolom image_path jika ada dan string kosong jika tidak dan nilainya tidak false
    // Return the image path or an empty string if not found
    return $result ? $result['image_path'] : '';
}

// Function to add a new product to the database
function addProduct($product_name, $description, $price, $stock_quantity, $category_id, $image_path)
{
    global $pdo;
    $sql = 'INSERT INTO products (product_name, description, price, stock_quantity, category_id, image_path)
                VALUES (:product_name, :description, :price, :stock_quantity, :category_id, :image_path)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock_quantity', $stock_quantity);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':image_path', $image_path);

    // Will always execute the query add
    $success = $stmt->execute();

    if ($success) {
        // Get the last inserted ID
        $lastInsertedId = getProductLastId();
        return $lastInsertedId;
    } else {
        return false;
    }
}

// function for adding Image based last id (used on adding to fix bug)
function addImageById($product_id, $image_path)
{
    global $pdo;
    $sql = 'UPDATE products 
                SET image_path = :image_path
                WHERE product_id = :product_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':image_path', $image_path);
    $stmt->bindParam(':product_id', $product_id);

    return $stmt->execute();
}

// Function to update a product in the database
function updateProduct($product_id, $product_name, $description, $price, $stock_quantity, $category_id, $image_path)
{
    global $pdo;
    $sql = 'UPDATE products
                SET product_name = :product_name,
                    description = :description,
                    price = :price,
                    stock_quantity = :stock_quantity,
                    category_id = :category_id,
                    image_path = :image_path
                WHERE product_id = :product_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock_quantity', $stock_quantity);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':image_path', $image_path);
    $stmt->bindParam(':product_id', $product_id);

    return $stmt->execute();
}


// Function to delete a product from the database
function deleteProduct($product_id)
{
    global $pdo;
    $sql = 'DELETE FROM products WHERE product_id = :product_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);

    return $stmt->execute();
}