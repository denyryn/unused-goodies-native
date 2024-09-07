<?php 
    include ('../php/config.php');
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

    <title>Product Details</title>
</head>
<body class="font-rubik">
    <?php

        include ("navbar.php");

        $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

        try {
            // Fetch product details from the database based on $product_id
            $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            $product = $stmt->fetch();

            // Check if product exists
            if ($product) {
                $product_name = $product['product_name'];
                $stock_quantity = $product['stock_quantity'];
                $price = $product['price'];
                $description = $product['description'];
                $image_path = $product['image_path'];
            } else {
                // Handle case where product is not found
                echo "<p class=\"h-[80vh] flex justify-center items-center text-xl font-extrabold\">Product not found!</p>";

                include("footer.php");
                exit;
            }
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
            exit;
        }
    ?>

    <div class="py-6 bg-white sm:py-8 lg:py-12">
        <div class="max-w-screen-lg px-4 mx-auto md:px-8">
            <div class="grid gap-8 md:grid-cols-2">
                <!-- images - start -->
                <div class="space-y-4">
                <div class="relative overflow-hidden bg-gray-100 rounded-md aspect-square">
                    <img src="<?php echo $image_path; ?>" loading="lazy" alt="<?php echo $product_name; ?>" class="object-cover object-center h-full w-ful l" />
                </div>
                </div>
                <!-- images - end -->

                <!-- content - start -->
                <div class="md:flex md:items-center md:justify-center">
                    <div>
                            <!-- name - start -->
                    <div class="mb-2 md:mb-3">
                        <h2 class="text-2xl font-bold text-gray-800 lg:text-3xl"><?php echo $product_name; ?></h2>
                    </div>
                    <!-- name - end -->

                    <!-- size - start -->
                    <div class="mb-5 md:mb-8">
                        <span class="inline-block text-sm font-normal text-gray-500 md:text-base">Stocks <?php echo $stock_quantity; ?></span>
                    </div>
                    <!-- size - end -->

                    <!-- price - start -->
                    <div class="mb-4">
                        <div class="flex items-end gap-2">
                            <span class="text-xl font-bold text-gray-800 md:text-2xl">Rp <?php echo number_format($price, 2, ',', '.'); ?></span>
                        </div>
                        <span class="text-sm text-gray-500">*Shipping cost may vary</span>
                    </div>
                    <!-- price - end -->

                    <!-- shipping notice - start -->
                    <!-- Your shipping notice code here -->
                    <!-- shipping notice - end -->

                    <!-- buttons - start -->
                    <div class="flex gap-2.5">
                        <a onclick="addToCart(<?php echo $product_id; ?>)" class="flex-1 inline-block px-8 py-3 text-sm font-semibold text-center text-white transition duration-100 bg-blue-500 rounded-lg outline-none btn ring-blue-300 hover:bg-blue-600 focus-visible:ring active:bg-indigo-700 sm:flex-none md:text-base">Add to cart</a>
                    </div>
                    <!-- buttons - end -->
                    </div>
        
                </div>
            </div>
            <!-- description - start -->
            <div class="mt-5 md:mt-8 lg:mt-10">
                <div class="mb-3 text-lg font-semibold text-gray-800">Description</div>
                <p class="text-gray-500">
                    <?php echo $description; ?>
                </p>
            </div>
            <!-- description - end -->
        </div>
        <!-- content - end -->
    </div>

    <?php 
        include("footer.php");
    ?>


    <script>
        
        function addToCart(productId) {
            
            fetch('./php/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    product_id: productId,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    
                    window.location.reload();
                } else {
                    
                    alert('Failed to add product to cart. Login if you are currently not logged in.');
                }
            })
        }
    </script>
</body>
</html>
