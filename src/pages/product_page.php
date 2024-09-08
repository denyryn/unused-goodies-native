<?php
require_once("../controllers/connection.php");
require_once("../controllers/utility.php");

$utility = new utility();


function getCategoryById($category_id)
{
    global $pdo;

    $sql = 'SELECT * FROM categories WHERE category_id = :category_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();

    return $stmt->fetchAll();
}

$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Validate and sanitize category_id
$category_id = filter_var($category_id, FILTER_VALIDATE_INT);

// Fetch the category name based on the category ID
$category_data = getCategoryById($category_id);

// Check if the category exists
$category_name = !empty($category_data) ? $category_data[0]['category_name'] : 'All';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/app.css">
    <link rel="icon" type="image/x-icon" href="../assets/ico/favicon.ico">

    <title>Product Page</title>
</head>

<body class="flex flex-col min-h-screen font-rubik">

    <?php

    include("navbar.php");

    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

    try {
        if ($category_id) {
            // Fetch products from the database based on $category_id
            $stmt = $pdo->prepare("SELECT * FROM products 
                                       WHERE category_id = :category_id");
            $stmt->bindParam(':category_id', $category_id);
            $stmt->execute();
            $products = $stmt->fetchAll();
        } else {
            // Fetch all products if no category_id is provided
            $stmt = $pdo->query("SELECT * FROM products");
            $products = $stmt->fetchAll();
        }

        // Check if there are any products
        if (!$products) {
            echo "<p class=\"h-[80vh] flex justify-center items-center text-xl font-extrabold\">No products found!</p>";
            include("footer.php");
            exit;
        }

    } catch (PDOException $e) {
        // Handle database error
        echo "Error: " . $e->getMessage();
        exit;
    }
    ?>

    <span class="flex justify-center text-lg font-semibold select-none font-rubik bg-base-100">
        <?php echo htmlspecialchars($category_name); ?> &nbspCategory
    </span>

    <div class="max-w-2xl px-4 py-16 mx-auto sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">

            <?php
            foreach ($products as $product) {
                if ($product['stock_quantity'] > 0) {
                    ?>

                    <a href="detailed_product_page.php?product_id=<?php echo $product['product_id']; ?>"
                        class="p-3 transition duration-200 ease-in-out border rounded-md group active:scale-95">
                        <div
                            class="w-full overflow-hidden bg-gray-200 rounded-md aspect-h-1 aspect-w-1 aspect-square xl:aspect-h-8 xl:aspect-w-7">
                            <img src="<?php $utility->productImage($product['image_path']) ?>"
                                alt="<?php $product['product_name'] ?>."
                                class="object-cover object-center w-full h-full group-hover:opacity-75">
                        </div>
                        <h3 class="mt-4 text-sm text-gray-700"><?php echo $product['product_name']; ?></h3>
                        <p class="mt-1 text-lg font-medium text-gray-900">Rp
                            <?php echo number_format($product['price'], 2, ',', '.'); ?>
                        </p>
                    </a>

                    <?php
                }
            }
            ?>

        </div>
    </div>



    <?php
    include("footer.php");
    ?>

    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</body>

</html>