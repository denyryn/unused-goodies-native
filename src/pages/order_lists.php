<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ref/css/styles.css">
    <link rel="stylesheet" href="../ref/css/tailwind.min.css">
    <link rel="stylesheet" href="../ref/css/extended.css">
    <link rel="icon" type="image/x-icon" href="../assets/ico/favicon.ico">

    <title>Order List</title>
</head>
<body class="font-rubik">

    <?php 
        include ("./navbar.php");
        include ('../php/config.php');

        // Check if user is logged un
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // Fetch order lists from the database
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $orderLists = $stmt->fetchAll();
        } else {
            // Redirect the user if user_id is not set
            header('Location: ./login_page.php');
            exit;
        }
    ?>
    <div class="flex flex-col m-8">
        <span class="text-xl font-bold text-center">
            View Your Order History
        </span>
    </div>
    <div class="flex-col">
        <?php
            if ($orderLists){ 
                foreach ($orderLists as $orderList): 
        ?>

        <div class="mx-3">
            <a href="order_details.php?order_id=<?php echo $orderList['order_id']; ?>" class="flex flex-row p-3 my-4 border cursor-pointer">
                <div class="flex items-center px-2">
                    <div class="">
                        <div class="">
                            Order Id : #<?php echo $orderList['order_id']; ?>
                        </div>
                        <div class="">
                            Total : Rp <?php echo number_format($orderList['total_amount'], 2, ',', '.'); ?>
                        </div>
                        <div class="">
                            Status : <?php echo $orderList['status']; ?>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php 
                endforeach;
        } else {
            ?>
            <p class="flex items-center justify-center h-[80vh] text-center font-bold text-lg">
                No Order List. Lets make one!!
            </p>
        <?php
        } 
        ?>
        
    </div>
    
</body>
</html>