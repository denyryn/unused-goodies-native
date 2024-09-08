<?php
require_once('../controllers/connection.php');
require_once(ROOT_DIR . "/src/admin/controllers/manage_products.php");
require_once(ROOT_DIR . "/src/admin/controllers/img_upload.php");

// mengambil semua data product dan kategori
// Fetch all products and categories from the database
$products = getAllProducts();
$categories = getAllCategories();

// Handle form submissions to add productd
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // form submit value = add_product maka ini akan dijalankan
    if (isset($_POST['add_product'])) {
        $product_name = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $category_id = $_POST['category_id'];

        // akan mengambil nilai id terakhir yang ditambahkan (bertujuan untuk hande upload gambar
        // supaya file gambar dapat disimpan ke path yang diinginkan. yang mana butuh variabel 
        // dari dataabse)
        $lastId = addProduct($product_name, $description, $price, $stock_quantity, $category_id, '');

        // Handle image upload
        $lastData = getProductById($lastId);
        $imageCategory = $lastData['category_name']; // This var is for substitute it to image path

        $targetDir = "../../../public/uploads/product_images/$imageCategory/$product_name/";
        $fileInputName = 'image';

        // buat folder jika tidak ada
        // Check if the directory exists
        if (!is_dir($targetDir)) {
            // Create the directory if it doesn't exist
            mkdir($targetDir, 0755, true);
            chmod($targetDir, 0755);
        }

        // memakai handleImageupload yang sama
        $imagePath = handleImageUpload($fileInputName, $targetDir);


        // jika return dari handleimageupload tidak flase maka gambar akan diupload
        if ($imagePath !== false) {
            $dbImagePath = $imageCategory . '/' . $product_name . '/' . $imagePath['filename'];
            addImageById($lastId, $dbImagePath);

            header('Location: ./manage_products_page.php');
            exit;
        } else {
            echo 'Error adding the product.';
        }
    }
}

// Handle delete producs
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Call the deleteProduct function to delete the product
    if (deleteProduct($product_id)) {
        // Redirect 
        header('Location: ./manage_products_page.php');
        exit;
    } else {
        echo 'Error deleting the product.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/app.css">
    <link rel="stylesheet" href="/public/css/extended.css">
    <link rel="stylesheet" href="/public/css/dashboard.css">
    <title>Manage Products</title>
</head>

<body class="font-rubik ">
    <div class="p-4 sm:ml-64">
        <div
            class="flex flex-row items-center justify-between h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            <h2 class="text-xl font-bold select-none">Manage Products</h2>
            <!-- <a href="#" class="btn">Test</a> -->
        </div>
    </div>

    <div class="p-4 sm:ml-64">
        <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 ">

            <!-- Add Product Form -->
            <h4 class="mb-2 font-semibold text-md ">Add Product</h4>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"
                enctype="multipart/form-data">
                <table class="border-separate border-spacing-y-4" border="1" style="border-spacing: 0 10px;">
                    <tr>
                        <td><label for="product_name">Product Name &nbsp&nbsp&nbsp</label></td>
                        <td class="">: &nbsp&nbsp&nbsp</td>
                        <td><input
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                type="text" id="product_name" name="product_name" required></td>
                    </tr>
                    <tr>
                        <td><label for="description">Description</label></td>
                        <td>:</td>
                        <td><textarea
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-sm border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                id="description" name="description" cols="30" required></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="price">Price</label></td>
                        <td>:</td>
                        <td><input
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                type="text" id="price" name="price" required></td>
                    </tr>
                    <tr>
                        <td><label for="stock_quantity">Stock Quantity</label></td>
                        <td>:</td>
                        <td><input
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                type="number" id="stock_quantity" name="stock_quantity" required></td>
                    </tr>
                    <tr>
                        <td><label for="category_id">Category</label></td>
                        <td>:</td>
                        <td>
                            <select
                                class="block p-2 text-gray-900 border border-gray-300 rounded-sm cursor-pointer ps-2 pe-2 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                id="category_id" name="category_id" required>
                                <option value="" disabled selected>Select a category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['category_id']; ?>">
                                        <?php echo $category['category_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="image">Product Image</label></td>
                        <td>:</td>
                        <td>
                            <label class="block mb-1 text-sm font-medium text-gray-900" for="image">Upload an
                                Image</label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 "
                                type="file" id="image" name="image" accept="image/*" required>
                            <p class="mt-1 text-sm text-gray-500" id="image_input_help">PNG, JPG or JPEG (PREF.
                                1080x1080px).</p>

                        </td>
                    </tr>
                </table>
                <div>
                    <input class="mt-4 text-white bg-black btn hover:bg-white hover:text-black hover:border-black"
                        type="submit" name="add_product" value="Submit">
                </div>
            </form>
        </div>
    </div>

    <div class="p-4 sm:ml-64">
        <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            <!-- Product List -->
            <h4 class="mb-2 font-semibold text-md">Product List</h4>
            <div class="overflow-auto">
                <table class="table table-zebra" border="1">
                    <thead class="text-sm font-semibold">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Picture</th>
                            <th class="w-28">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['product_id']; ?></td>
                                <td><?php echo $product['product_name']; ?></td>
                                <td class="sm:text-justify"><?php echo $product['description']; ?></td>
                                <td>Rp&nbsp<?php echo number_format($product['price'], 2); ?></td>
                                <td><?php echo $product['stock_quantity']; ?></td>
                                <td><?php echo $product['category_name']; ?></td>
                                <td><img class="w-7" src="<?php echo $product['image_path']; ?>" alt=""></td>
                                <td>
                                    <a class="hover:underline"
                                        href="./edit_products.php?action=edit&product_id=<?php echo $product['product_id']; ?>">Edit</a>
                                    |
                                    <a class="text-red-400 transition-colors hover:underline hover:text-red-700"
                                        href="?action=delete&product_id=<?php echo $product['product_id']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>