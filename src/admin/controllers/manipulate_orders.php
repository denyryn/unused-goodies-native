<?php

require_once("connection.php");
require_once("manage_orders.php");


// Jika ada request ke file ini dan actionnya confirm (action dan order_id harus disertakan)
// If theres a request "confirm" and it providing order_id
if (isset($_GET['action']) && $_GET['action'] === 'confirm' && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    confirmOrder($order_id);

    header('Location: ../manage_orders_page.php');

    if (!$order_id) {
        echo "false order_id";
        header('Location: ../manage_orders_page.php');
        exit;
    }
}

// Jika ada request ke file ini dan actionnya reject (action dan order_id harus disertakan)
// If theres a request "reject" and it providing order_id
if (isset($_GET['action']) && $_GET['action'] === 'reject' && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $sql = 'SELECT * FROM order_details WHERE order_id = :order_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $sql1 = 'SELECT * FROM orders WHERE order_id = :order_id';
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->bindParam(':order_id', $order_id);
    $stmt1->execute();
    $result1 = $stmt1->fetchAll();

    $status = $result1;

    // jika status pada array row pertama tidak senilai dengan 'reject' maka akan  dieksekusi
    // ini dibuat untuk mencegah order yang telah reject dan direject lagi, tidak akan mengembalikan stock yang dibeli
    if ($status[0]['status'] != 'rejected') {
        $products = $result;
        foreach ($products as $product) {
            $product_id = $product['product_id'];
            $quantity = $product['quantity'];

            // Update product stock
            $stmtUpdateStock = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity + :quantity WHERE product_id = :product_id");
            $stmtUpdateStock->bindParam(':quantity', $quantity);
            $stmtUpdateStock->bindParam(':product_id', $product_id);
            $stmtUpdateStock->execute();
        }
        rejectOrder($order_id);
        header('Location: ../manage_orders_page.php');
    } else {
        header('Location: ../manage_orders_page.php');
    }
}

?>