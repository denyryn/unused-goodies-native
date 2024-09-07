<?php
session_start();

include './config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $total_amount = $_POST['total_amount'];

    // cek apakah ada produk yang dikirim dari page asal
    if (isset($_POST['products']) && is_array($_POST['products'])) {
        $pdo->beginTransaction();

        // buat order id
        $stmtOrder = $pdo->prepare("INSERT INTO orders (user_id, status, total_amount) VALUES (:user_id, 'pending', :total_amount)");
        $stmtOrder->bindParam(':user_id', $user_id);
        $stmtOrder->bindParam(':total_amount', $total_amount);
        $stmtOrder->execute();

        //mengambil order id terbaru
        $order_id = $pdo->lastInsertId();

        // perulangan untuk memasukkan product ke order details
        foreach ($_POST['products'] as $product) {
            $product_id = $product['product_id'];
            $quantity = $product['quantity'];
            $price = $product['price'];

            $stmtOrderDetails = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
            $stmtOrderDetails->bindParam(':order_id', $order_id);
            $stmtOrderDetails->bindParam(':product_id', $product_id);
            $stmtOrderDetails->bindParam(':quantity', $quantity);
            $stmtOrderDetails->bindParam(':price', $price);
            $stmtOrderDetails->execute();

            // Update stok sesuai jumlah yang diorder
            $stmtUpdateStock = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE product_id = :product_id");
            $stmtUpdateStock->bindParam(':quantity', $quantity);
            $stmtUpdateStock->bindParam(':product_id', $product_id);
            $stmtUpdateStock->execute();
        }


        $stmtEmptyCart = $pdo->prepare("DELETE FROM shopping_Cart
                                        WHERE user_id = :user_id");
        $stmtEmptyCart -> bindParam(':user_id', $user_id);
        $stmtEmptyCart -> execute();
        $pdo->commit();

        //redirect ke order details page dari order yang telah dibuat
        header('Location: ../order_details.php');
        exit;
    }
} else {
    header('Location: ../checkout_products.php');
    exit;
}
?>
