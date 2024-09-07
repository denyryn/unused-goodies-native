<?php
    session_start();
    include ('./config.php');

    header('Content-Type: application/json'); 
    $response = array(); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Cek session
        if (!isset($_SESSION['user_id'])) {
            $response['success'] = false;
            $response['message'] = 'User not logged in';
        } else {
            $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;

            $user_id = $_SESSION['user_id'];

            if ($product_id) {
                $stmt = $pdo->prepare("SELECT * FROM shopping_cart WHERE user_id = :user_id AND product_id = :product_id");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->execute();
                $cartItem = $stmt->fetch();

                if ($cartItem) {

                    $quantity = $cartItem['quantity'] + 1;
                    $updateStmt = $pdo->prepare("UPDATE shopping_cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id");
                    $updateStmt->bindParam(':quantity', $quantity);
                    $updateStmt->bindParam(':user_id', $user_id);
                    $updateStmt->bindParam(':product_id', $product_id);
                    $updateStmt->execute();
                } else {
                    $insertStmt = $pdo->prepare("INSERT INTO shopping_cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)");
                    $insertStmt->bindParam(':user_id', $user_id);
                    $insertStmt->bindParam(':product_id', $product_id);
                    $insertStmt->execute();
                }

                $response['success'] = true;
                $response['message'] = 'Product added to cart';
            } else {
                $response['success'] = false;
                $response['message'] = 'Invalid product ID';
            }
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid request method';
    }
    // akan mengirim respon apakah produk ditambahkan
    echo json_encode($response);
?>
