<?php
require_once("../controllers/auth.php");
require_once(ROOT_DIR . "/src/controllers/utility.php");

$utility = new utility();

// Check session
if (isset($_SESSION['user_id'])) {
    // If the session is set, display the navigation bar for logged-in users(cart)                                                                        
    ?>

    <div class="navbar bg-base-100">
        <div class="navbar-start">
            <a href="main_page.php" class="btn hover:bg-transparent btn-ghost w-fit h-fit">
                <img class="flex items-center justify-center w-14" src="<?php $utility->svgAssets("logo") ?>"
                    alt="Unused Goodies Logo">
            </a>
        </div>
        <div class="hidden navbar-center lg:flex ">
            <ul class="px-1 font-bold menu menu-horizontal font-rubik">
                <li><a href="main_page.php">Home</a></li>
                <li><a href="category_page.php">Categories</a></li>
                <li><a href="product_page.php">Products</a></li>
                <li><a href="order_lists.php?id=<?php echo $_SESSION['user_id']; ?>">Orders</a></li>
                <li><a href="profile_page.php?id=<?php echo $_SESSION['user_id']; ?>">Profile</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            <a data-modal-target="cart-modal" data-modal-toggle="cart-modal"
                class="flex bg-transparent border-none shadow-none outline-none btn lg:hidden" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="black"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 1.92 1.61h10.8a2 2 0 0 0 1.92-1.61L23 6H6" />
                </svg>
            </a>
            <div class="font-semibold dropdown dropdown-end font-rubik">
                <div tabindex="0" role="button" class=" btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-fit">
                    <li><a href="main_page.php">Home</a></li>
                    <li><a href="category_page.php">Categories</a></li>
                    <li><a href="product_page.php">Products</a></li>
                    <li><a href="order_details.php?id=<?php echo $_SESSION['user_id']; ?>">Orders</a></li>
                    <li><a href="profile_page.php?id=<?php echo $_SESSION['user_id']; ?>">Profile</a></li>
                </ul>
            </div>
            <a data-modal-target="cart-modal" data-modal-toggle="cart-modal"
                class="hidden bg-transparent border-none shadow-none outline-none lg:flex btn" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="black"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 1.92 1.61h10.8a2 2 0 0 0 1.92-1.61L23 6H6" />
                </svg>
            </a>
        </div>
    </div>

    <?php

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect the user to the login page or handle the case where the user is not logged in
        header('Location: login.php');
        exit;
    }

    // Fetch cart items from the shopping_cart table
    $stmt = $pdo->prepare("SELECT sc.*, p.product_name, p.price, p.image_path, p.stock_quantity 
                            FROM shopping_cart sc
                            JOIN products p ON sc.product_id = p.product_id
                            WHERE sc.user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $cartItems = $stmt->fetchAll();

    foreach ($cartItems as $cartItem) {
        //jika ada barang stok 0, barang akan otomatis dihapus dari cart
        if ($cartItem['stock_quantity'] === 0) {
            $deleteStmt = $pdo->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id AND product_id = :product_id");
            $deleteStmt->bindParam(':user_id', $_SESSION['user_id']);
            $deleteStmt->bindParam(':product_id', $cartItem['product_id']);
            $deleteStmt->execute();
        }
    }

    // Bakal menghitung dari 0 lagi jika ada barang yang dihapus
    $totalCartCost = 0;
    foreach ($cartItems as $cartItem) {
        $totalCartCost += $cartItem['quantity'] * $cartItem['price'];
    }

    ?>

    <div id="cart-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full p-4">
            <!-- Modals Content -->
            <div class="flex items-center justify-center">
                <!-- Close Buttons -->
                <div class="relative items-center w-full max-w-lg px-5 py-6 bg-white rounded-lg shadow-lg">
                    <a data-modal-hide="cart-modal">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-3 cursor-pointer shrink-0 fill-[#333] hover:fill-red-500 float-right"
                            viewBox="0 0 320.591 320.591">
                            <path
                                d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"
                                data-original="#000000"></path>
                            <path
                                d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"
                                data-original="#000000"></path>
                        </svg>
                    </a>

                    <h4 class="text-sm text-[#333] mt-4">
                        <!-- Add the number of items and total cost in the cart here -->
                        <!-- Example: "3 items in your cart - Total: Rp xxx.xxx" -->
                    </h4>

                    <!-- Loop through cart items and display all items -->
                    <?php foreach ($cartItems as $cartItem): ?>
                        <div class="mt-6 space-y-6">
                            <div class="flex items-center">
                                <!-- Display product image -->
                                <img src='<?php echo $cartItem['image_path']; ?>'
                                    class="object-cover w-16 h-16 p-2 bg-gray-100 aspect-square shrink-0" />

                                <div class="flex-1 ml-4">
                                    <!-- Display product name -->
                                    <p class="text-sm text-black"><?php echo $cartItem['product_name']; ?></p>

                                    <!-- Display product quantity -->
                                    <p class="mt-1 text-xs tracking-wide text-gray-400"><?php echo $cartItem['quantity']; ?>
                                        in cart</p>
                                </div>

                                <div>
                                    <!-- Display product total cost -->
                                    <span class="mr-5 text-sm font-semibold">
                                        Rp
                                        <?php echo number_format($cartItem['quantity'] * $cartItem['price'], 2, ',', '.'); ?>
                                    </span>

                                    <!-- Add button to remove all items with the product id in cart -->
                                    <a remove-product="<?php echo $cartItem['product_id']; ?>" class="remove-product">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 cursor-pointer fill-red-500"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M19 7a1 1 0 0 0-1 1v11.191A1.92 1.92 0 0 1 15.99 21H8.01A1.92 1.92 0 0 1 6 19.191V8a1 1 0 0 0-2 0v11.191A3.918 3.918 0 0 0 8.01 23h7.98A3.918 3.918 0 0 0 20 19.191V8a1 1 0 0 0-1-1Zm1-3h-4V2a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v2H4a1 1 0 0 0 0 2h16a1 1 0 0 0 0-2ZM10 4V3h4v1Z"
                                                data-original="#000000"></path>
                                            <path
                                                d="M11 17v-7a1 1 0 0 0-2 0v7a1 1 0 0 0 2 0Zm4 0v-7a1 1 0 0 0-2 0v7a1 1 0 0 0 2 0Z"
                                                data-original="#000000"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Display total cost of all items in the cart -->
                    <div class="flex my-3 mt-8">
                        <span class="flex-1 text-sm ">Total</span>
                        <span class="text-sm font-semibold">
                            <!-- Calculate the total cost of all items in the cart -->
                            Rp <?php echo number_format($totalCartCost, 2, ',', '.'); ?>
                        </span>
                    </div>

                    <!-- checkout button -->
                    <div class="flex max-sm:flex-col gap-4 !mt-6 w-full">
                        <a href="./checkout_products.php?user_id=<?php echo $_SESSION['user_id']; ?>" type="button"
                            class="btn px-6 py-2.5 w-full transition duration-200 ease-in-out active:scale-95 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                            Check out
                        </a>
                    </div>
                </div>
            </div>
            <!-- Modals End -->
        </div>
    </div>



    <?php
} else {
    // If the session is not set, display an alternative navigation bar for non-logged-in users (no cart, just login)
    ?>

    <div class="navbar bg-base-100">
        <div class="navbar-start">
            <a href="main_page.php" class="btn hover:bg-transparent btn-ghost w-fit h-fit">
                <img class="flex items-center justify-center w-14" src="<?php $utility->svgAssets("logo") ?>"
                    alt="Unused Goodies Logo">
            </a>
        </div>
        <div class="hidden navbar-center lg:flex ">
            <ul class="px-1 font-bold menu menu-horizontal font-rubik">
                <li><a href="main_page.php">Home</a></li>
                <li><a href="category_page.php">Categories</a></li>
                <li><a href="product_page.php">Products</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            <a class="flex bg-transparent border-none shadow-none outline-none btn lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="black"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 1.92 1.61h10.8a2 2 0 0 0 1.92-1.61L23 6H6" />
                </svg>
            </a>
            <div class="font-semibold dropdown dropdown-end font-rubik">
                <div tabindex="0" role="button" class=" btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-fit">
                    <li><a href="main_page.php">Home</a></li>
                    <li><a href="category_page.php">Categories</a></li>
                    <li><a href="product_page.php">Products</a></li>
                </ul>
            </div>
            <a href="./login_page.php"
                class="hidden bg-transparent border-none shadow-none outline-none lg:flex btn hover:bg-transparent">
                <img class="w-5 h-5" src="<?php $utility->svgAssets("user") ?>" alt="">
            </a>
        </div>
    </div>

    <?php

}

?>

<script src="../../node_modules/flowbite/dist/flowbite.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add a click event listener to all elements with the class 'remove-product'
        document.querySelectorAll('.remove-product').forEach(function (element) {
            element.addEventListener('click', function () {
                // Retrieve the product_id from the data attribute
                var productId = this.getAttribute('remove-product');

                fetch('./php/remove_from_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + productId,
                })
                    .then(response => response.text())
                    .then(data => {

                        window.location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>