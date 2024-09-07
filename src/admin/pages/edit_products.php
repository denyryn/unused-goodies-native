<?php
// Include the necessary files
require_once('../controllers/connection.php');
require_once(ROOT_DIR . "/src/admin/controllers/manage_products.php");
require_once(ROOT_DIR . "/src/admin/controllers/img_upload.php");

// Cek action dan product id pada url
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['product_id'])) {
    // set variabel diisi product id dr url
    $product_id = $_GET['product_id'];

    // Fetch the product details by id
    $product = getProductById($product_id);

    if (!$product) {
        // jika produk tidak ada
        header('Location: ./dashboard_page.php');
        exit;
    }

    // ambil semua data kategori
    // Fetch all categories from the database
    $categories = getAllCategories();

    // Handle form edit post
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_name = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $category_id = $_POST['category_id'];

        $imageCategory = $product['category_name']; // This var is for substituting it to the image path
        $targetDir = "../uploads/product_images/$imageCategory/$product_name/";
        $fileInputName = 'image';

        // cek apakah folder ada jika tidak, maka akan dibuat
        // Check if the directory exists
        if (!is_dir($targetDir)) {
            // Create the directory if it doesn't exist
            mkdir($targetDir, 0755, true);
            chmod($targetDir, 0755);
        }

        // Call the getCurrentImagePath function to get the current image path
        $currentImagePath = getCurrentImagePath($product_id);

        // jika tidak kosong
        if (!empty($_FILES[$fileInputName]['name'])) {
            // diupload dan dicek
            $imagePath = handleImageUpload($fileInputName, $targetDir);

            // cek jika hasil keluaran false maka
            if ($imagePath === false) {
                echo 'Error uploading the image.';
                exit;
            }

            // hapus file path sebelumnya pada database
            // Delete the previous image file
            if (!empty($currentImagePath) && file_exists($currentImagePath)) {
                unlink($currentImagePath);
            }
        } else {
            // jika tidak ada input gambar maka nilai sebelumnya akan ditetapkan
            $imagePath = $currentImagePath;
        }

        // update data
        if (updateProduct($product_id, $product_name, $description, $price, $stock_quantity, $category_id, $imagePath)) {
            // Redirect ke halaman utama.
            header('Location: ./manage_products_page.php');
            exit;
        } else {
            echo 'Error updating the product.';
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
        <title>Edit Product</title>
    </head>

    <body class="font-rubik">
        <div class="p-4 sm:ml-64">
            <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <h4 class="text-xl font-bold select-none">Manage Products</h4>
            </div>
        </div>

        <div class="p-4 sm:ml-64">
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <h2 class="mb-2 font-semibold text-md ">Edit Product</h2>
                <!-- Edit Product Form -->
                <form
                    action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?action=edit&product_id=' . $product_id); ?>"
                    method="post" enctype="multipart/form-data">
                    <table class="border-separate border-spacing-y-4" border="1" style="border-spacing: 0 10px;">
                        <tr>
                            <td><label for="product_name">Product Name &nbsp&nbsp&nbsp</label></td>
                            <td>: &nbsp&nbsp&nbsp</td>
                            <td><input
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                    type="text" id="product_name" name="product_name"
                                    value="<?php echo $product['product_name']; ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="description">Description</label></td>
                            <td>:</td>
                            <td><textarea
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-sm border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    id="description" name="description" cols="30"
                                    required><?php echo $product['description']; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="price">Price</label></td>
                            <td>:</td>
                            <td>
                                <div class="relative">
                                    <p
                                        class="absolute inset-y-0 top-0 flex items-center text-sm text-gray-900 pointer-events-none start-0 ps-2">
                                        Rp</p>
                                    <input
                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5 pl-7"
                                        type="text" id="price" name="price"
                                        value="<?php echo str_replace([',', '.00'], '', number_format($product['price'], 2)); ?>"
                                        required>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><label for="stock_quantity">Stock Quantity</label></td>
                            <td>:</td>
                            <td><input
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                    type="number" id="stock_quantity" name="stock_quantity"
                                    value="<?php echo $product['stock_quantity']; ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="category_id">Category</label></td>
                            <td>:</td>
                            <td>
                                <select
                                    class="block p-2 text-sm text-gray-900 border border-gray-300 rounded-sm ps-2 pe-2 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    id="category_id" name="category_id" required>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $product['category_id'])
                                               echo 'selected'; ?>>
                                            <?php echo $category['category_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <?php
                                if ($product['image_path'] !== null) {
                                    echo '<img class="w-32" src="' . $product['image_path'] . '" alt="">';
                                } else {
                                    echo '<p class="text-red-500">No Photo Currently Available.</p>';
                                }
                                ?>
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
                                    type="file" id="image" name="image" accept="image/*">
                                <p class="mt-1 text-sm text-gray-500" id="image_input_help">PNG, JPG or JPEG (PREF.
                                    1080x1080px).</p>
                            </td>
                        </tr>
                    </table>
                    <div>
                        <input class="mt-4 text-white bg-black btn hover:bg-white hover:text-black hover:border-black"
                            type="submit" name="update_product" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </body>

    </html>

    <?php

}
?>