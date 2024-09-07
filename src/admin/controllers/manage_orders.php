<?php
require_once("connection.php");

//Function to get all orders data from orders and orders_details table
function getAllOrders()
{
    global $pdo;
    $sql = 'SELECT a.*, b.* 
        FROM orders a
        INNER JOIN users b ON a.user_id = b.user_id';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

//Function to get order details data based on order_id
function getOrderDetailById($order_id)
{
    global $pdo;
    $sql = 'SELECT a.*, b.*, c.* 
                FROM order_details a
                INNER JOIN orders b ON a.order_id = b.order_id
                INNER JOIN products c ON a.product_id = c.product_id
                WHERE a.order_id = :order_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

//Function to confirm order
function confirmOrder($order_id)
{
    global $pdo;
    $confirm = 'accepted';
    $sql = 'UPDATE orders
                SET status = :confirm
                WHERE order_id = :order_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':confirm', $confirm);
    $stmt->bindParam(':order_id', $order_id);
    return $stmt->execute();
}

function rejectOrder($order_id)
{
    global $pdo;
    $reject = 'rejected';
    $sql = 'UPDATE orders
                SET status = :reject
                WHERE order_id = :order_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':reject', $reject);
    $stmt->bindParam(':order_id', $order_id);
    return $stmt->execute();
}