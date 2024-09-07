<?php
    session_start();

    include './config.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // cek session login
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
        // menetapkan user saat ini dari session
        $user_id = $_SESSION['user_id'];

        // cek jika product id ada
        if ($product_id) {
            $stmt = $pdo->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();

            // You can send a success response if needed
            echo 'Product deleted successfully';
            exit;
        }
    }
?>
