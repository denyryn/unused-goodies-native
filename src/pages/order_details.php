    <?php
    include ('./php/config.php');
    include ('./php/auth.php');

    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];

        $stmt = $pdo->prepare("SELECT orders.*, order_details.*, products.*
                                FROM orders
                                INNER JOIN order_details ON orders.order_id = order_details.order_id
                                INNER JOIN products ON order_details.product_id = products.product_id
                                WHERE orders.order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        $orderDetails = $stmt->fetchAll();
    } 
    else {
        // kembali ke order list page jika order id tidak diset
        header('Location: order_lists.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ref/css/styles.css">
    <link rel="stylesheet" href="../ref/css/tailwind.min.css">
    <link rel="stylesheet" href="../ref/css/extended.css">
    <link rel="icon" type="image/x-icon" href="../assets/ico/favicon.ico">


    <title>Order_Details</title>
</head>
<body class="font-rubik">
    <div class="flex flex-col p-8 text-center md:py-12 md:px-10 md:text-left">
        <div class="my-2 text-xl font-bold ">
            Order Details
        </div>
        <div class="text-base">
            Thank you for your order! Our treasure is your feast!
        </div>
    </div>
    <div class="md:px-8">
        
        <div class="flex flex-col grid-cols-2 p-3 m-2 mb-0 text-sm text-center border md:flex-row md:items-center md:text-left md:justify-between">
            
            <div class="">
                <span class="inline-block font-base">
                    Order : 
                    <div class="inline font-semibold">
                        #<?php echo $orderDetails[0]['order_id']; ?>
                    </div>
                </span>
                <br>
                <div class="inline text-sm">
                    Status :
                    <div class="inline text-sm font-semibold">
                        <?php echo $orderDetails[0]['status']; ?>
                    </div>
                </div>
                <br>
                <span class="inline-block font-base">
                    Order Placement : 
                    <div class="inline font-semibold">
                        <?php echo $orderDetails[0]['order_date']; ?>
                    </div>
                </span>
            </div>

            <?php 
                if($orderDetails[0]['payment'] == null && $orderDetails[0]['status'] !== 'rejected' && $orderDetails[0]['status'] !== 'accepted') {

            ?>
            
            <!-- upload bukti bayar -->
            <form action="./php/store_payment.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="order_id" value="<?php echo $orderDetails[0]['order_id']; ?>">
                <input type="file" name="payment" accept="image/*" required>
                <input type="submit" class="m-2 btn" value="Store payment">
            </form>

            <?php 
                } else if ($orderDetails[0]['status'] == 'rejected') {
            ?>

            <p class="text-red-500">Payment Doesnt Valid and or Blocked</p>

            <?php
                } else if ($orderDetails[0]['status'] == 'accepted') {
            ?>

            <p class="text-green-500">Payment Valid and Accepted</p>

            <?php
                }else {
            ?>

            <p class="text-green-500">Payment Stored</p>

            <?php 
                }
            ?>
            
    
        </div>
        
        <?php
            $totalOrderPrice = 0; 
            foreach ($orderDetails as $orderDetail): 
        ?>
        <div class="flex flex-col p-3 m-2 my-0 border border-t-0 ">
            <div class="relative h-[150px] sm:h-[300px]">
                <img src="<?php echo $orderDetail['image_path']; ?>" class="absolute inset-0 object-cover w-full h-full duration-150 opacity-100 group-hover:opacity-0 brightness-50" alt="">
            </div>
            <div class="my-2 text-base font-bold">
                <?php echo $orderDetail['product_name']; ?>
            </div>
            <div class="flex flex-row w-full text-xs font-base">
                <div class="flex justify-start w-1/2 my-2 ">
                    Qty : <?php echo $orderDetail['quantity']; ?>
                </div>
                <div class="my-2 font-semibold">
                    Price : <?php echo number_format($orderDetail['price'], 2, ',', '.'); ?>
                </div>
            </div>
        </div>
        
        <?php 
            $totalProductPrice = $orderDetail['price'] * $orderDetail['quantity'];
            $totalOrderPrice += $totalProductPrice;
            endforeach; 
        ?>
        <div class="flex flex-col p-3 m-2 my-0 border border-t-0">
            <div class="inline-block text-center font-base">
                Total Price : 
                <div class="inline text-base font-semibold">
                    <?php echo 'Rp ' . number_format($totalOrderPrice, 2, ',', '.'); ?>
                </div>
            </div>
        </div>
        <div class="flex flex-col p-3 m-2 mt-0 mb-20 border border-t-0">
            <div class="inline-block text-center font-base">
                <a href="./main_page.php" class="hover:underline">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</body>
</html>
