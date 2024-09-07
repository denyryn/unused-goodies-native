<?php 
    include ("./php/config.php");
    include ("./php/auth.php");

    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
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


    <title>Checkout</title>
</head>
<body class="font-rubik">
    <div class="h-screen py-8 bg-gray-50">
        <div class="container px-4 mx-auto">
            <h1 class="mb-4 text-2xl font-semibold">Checkout</h1>
            <div class="flex flex-col gap-4 md:flex-row">
                <div class="md:w-3/4">
                    <div class="p-6 mb-4 bg-white rounded-lg shadow-md">
                        <table class="w-full ">
                            <thead>
                                <tr>
                                    <th class="font-semibold text-left">Product</th>
                                    <th class="font-semibold text-left">Price</th>
                                    <th class="font-semibold text-left">Quantity</th>
                                    <th class="font-semibold text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Fetch user's cart items from the database
                                    $stmt = $pdo->prepare("SELECT products.*, shopping_cart.quantity AS cart_quantity 
                                                           FROM products 
                                                           INNER JOIN shopping_cart ON products.product_id = shopping_cart.product_id 
                                                           WHERE shopping_cart.user_id = :user_id");
                                    $stmt->bindParam(':user_id', $_SESSION['user_id']);
                                    $stmt->execute();
                                    $cartItems = $stmt->fetchAll();

                                    foreach ($cartItems as $cartItem) {
                                ?>
                                
                                    <tr>
                                        <td class="py-4">
                                            <div class="flex items-center">
                                                <img class="object-cover w-16 h-16 mr-4 rounded-sm aspect-square" src="<?php echo $cartItem['image_path']; ?>" alt="Product image">
                                                <span class="font-semibold"><?php echo $cartItem['product_name']; ?></span>
                                                
                                            </div>
                                        </td>
                                        <td class="py-4">Rp <?php echo number_format($cartItem['price'], 2, ',', '.'); ?></td>
                                        <td class="py-4">
                                            <div class="flex items-center">
                                                <span class="w-8"><?php echo $cartItem['cart_quantity']; ?></span>
                                            </div>
                                        </td>
                                        <td class="py-4">Rp <?php echo number_format($cartItem['price'] * $cartItem['cart_quantity'], 2, ',', '.'); ?></td>
                                        
                                    </tr>

                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="md:w-1/4">
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h2 class="mb-4 text-lg font-semibold">Summary</h2>
                        <div class="flex justify-between mb-2">
                            <span>Subtotal</span>
                            <span>
                                <?php
                                    // Calculate subtotal
                                    $subtotal = array_sum(array_map(function ($item) {
                                        return $item['price'] * $item['cart_quantity'];
                                    }, $cartItems));
                                    echo "Rp " . number_format($subtotal, 2, ',', '.');
                                ?>
                            </span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Shipping and Taxes</span>
                            <span>Rp 0,00</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">Total</span>
                            <span class="font-semibold">
                                <?php
                                    // Total is the sum of subtotal, shipping, and taxes
                                    $total = $subtotal; //+ shipping and taxes

                                    echo "Rp " . number_format($total, 2, ',', '.');
                                ?>
                            </span>
                        </div>
                        <form id="checkoutForm" action="./php/checkout.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <?php foreach ($cartItems as $eachProduct => $cartItem): ?>
                                <input type="hidden" name="products[<?php echo $eachProduct; ?>][product_id]" value="<?php echo $cartItem['product_id']; ?>">
                                <input type="hidden" name="products[<?php echo $eachProduct; ?>][quantity]" value="<?php echo $cartItem['cart_quantity']; ?>">
                                <input type="hidden" name="products[<?php echo $eachProduct; ?>][price]" value="<?php echo $cartItem['price']; ?>">
                            <?php endforeach; ?>
                            <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
                            <input type="hidden" name="status" value="pending">
                        </form>
                        <button onclick="document.getElementById('checkoutForm').submit();" 
                            class="w-full px-4 py-2 mt-4 text-white bg-blue-500 rounded-lg btn hover:bg-blue-600">
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
