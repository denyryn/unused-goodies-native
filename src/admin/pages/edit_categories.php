<?php

require_once("../controllers/connection.php");
require_once("../controllers/manage_categories.php");
require_once("../controllers/admin_utility.php");

$utility = new admin_utility();


if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['category_id'])) {
    $category_id = $_GET['category_id']; //variabel di-set disini

    // mengampil kategori berdasarkan id yang diset pada url
    // Fetch category details by ID
    $category = getCategoryById($category_id);

    if (!$category) {
        // Redirect jika error (kategori tidak ditemukan)
        header('Location: ./dashboard_page.php');
        exit;
    }

    // jika file ini melakukan req post ke file ini sendiri, maka akan melakukan proses-proses di bawah 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_category'])) {
            // Update category form submitted
            $category_id = $_POST['category_id'];
            $category_name = $_POST['category_name'];
            $category_description = $_POST['category_description'];

            $category = getCategoryById($category_id);

            $imageCategory = $category['category_name']; // This var is for substituting it to the image path

            $targetDir = "../../../public/uploads/category_images/base/$category_name/";
            $hoverTargetDir = "../../../public/uploads/category_images/hover/$category_name/";

            $fileInputName = 'image_base_path';
            $fileInputNameHover = 'image_hover_path';

            // Check if the directory exists
            if (!is_dir($targetDir)) {
                // Create the directory if it doesn't exist
                mkdir($targetDir, 0755, true);
                chmod($targetDir, 0755);
            }

            if (!is_dir($hoverTargetDir)) {
                // Create the directory if it doesn't exist
                mkdir($hoverTargetDir, 0755, true);
                chmod($hoverTargetDir, 0755);
            }

            // Call the getCurrentImagePath
            $getCurrent = getCurrentCategoryImagePath($category_id);

            $currentImgPath = $getCurrent['image_base_path'];
            $currentImgHoverPath = $getCurrent['image_hover_path'];



            // cek apakah ada file yang terupload (tidak kosong)
            // Check if an image file was uploaded
            if (!empty($_FILES[$fileInputName]['name']) || !empty($_FILES[$fileInputNameHover]['name'])) {
                // jika salah satu atau keduanya dari file tersebut diisi maka akan melakukan proses ini
                $imagePath = handleImageUpload($fileInputName, $targetDir);
                $imageHoverPath = handleImageUpload($fileInputNameHover, $hoverTargetDir);

                // jika handleimage salah satu atau kedua file diatas return false (uploadOK = 0) maka akan muncul error dan
                // gambar tidak akan di-upload (langsung exit)
                // Check if image upload was successful
                if ($imagePath === false && $imageHoverPath === false) {
                    echo 'Error uploading the image.';
                    exit;
                }

                // Jika kategori yang diedit telah memiliki gambar dan handleimage di atas tidak false
                // maka fungsi unlink akan dijalankan untuk menghapus gambar yang ada di path saat ini
                // Delete the previous image file
                if (!empty($currentImgPath) && file_exists($currentImgPath)) {
                    unlink($currentImgPath);
                }

                if (!empty($currentImgHoverPath) && file_exists($currentImgHoverPath)) {
                    unlink($currentImgHoverPath);
                }

            } else {
                // Jika salah satu atau keduanya dari input image yang ada terisi, maka path yang akan 
                // dimasukkan ke database adalah path saat ini
                // No image file uploaded, set $imagePath and $imageHoverPath to the current paths
                $imagePath = $currentImgPath;
                $imageHoverPath = $currentImgHoverPath;
            }

            $dbImagePath = $category_name . '/' . $imagePath['filename'];
            $dbImageHoverPath = $category_name . '/' . $imageHoverPath['filename'];

            // nilai edit baru dari form terhadap kategori saat ini akan diupdate
            // Call the updateCategory function with the image paths if they are not empty
            if (updateCategory($category_id, $category_name, $category_description, $dbImagePath, $dbImageHoverPath)) {
                // Redirect ke file manage_categories
                header('Location: ./manage_categories_page.php');
                exit;
            } else {
                // jika upload gagal akan redirect langsung (tanpa ada perubahan)
                header("Location: ./manage_categories_page.php");
                exit;
            }

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
        <title>Edit Category</title>
    </head>

    <body class="font-rubik">
        <div class="p-4 sm:ml-64">
            <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <h4 class="text-xl font-bold select-none">Manage Category</h4>
            </div>
        </div>

        <div class="p-4 sm:ml-64">
            <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <h2 class="mb-2 font-semibold text-md">Edit Category</h2>
                <!-- Edit Category Form -->
                <form
                    action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?action=edit&category_id=' . $category_id); ?>"
                    method="post" enctype="multipart/form-data">
                    <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                    <table class="border-separate border-spacing-y-4" border="1" style="border-spacing: 0 10px;">
                        <tr>
                            <td><label for="category_name">Category Name &nbsp&nbsp&nbsp&nbsp&nbsp</label></td>
                            <td>: &nbsp&nbsp&nbsp</td>
                            <td><input
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                    type="text" id="category_name" name="category_name"
                                    value="<?php echo $category['category_name']; ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="category_description">Description</label></td>
                            <td>:</td>
                            <td><textarea
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-sm border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    id="category_description" name="category_description"
                                    required><?php echo $category['category_description']; ?></textarea></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <?php if ($category['image_base_path'] !== null): ?>
                                    <img class="object-cover w-32 aspect-square"
                                        src="<?php echo $utility->categoryPicture('base', $category['image_base_path']); ?>"
                                        alt="">
                                <?php else: ?>
                                    <p class="text-red-500">No Photo Currently Available.</p>
                                <?php endif; ?>

                            </td>
                        </tr>
                        <tr>
                            <td><label for="image_base_path">Image Path</label></td>
                            <td>:</td>
                            <td>
                                <label class="block mb-1 text-sm font-medium text-gray-900" for="image">Upload an
                                    Image</label>
                                <input
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 "
                                    type="file" id="image_base_path" name="image_base_path" accept="image/*">
                                <p class="mt-1 text-sm text-gray-500" id="image_input_help">PNG, JPG or JPEG (PREF.
                                    1080x1080px).</p>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <?php if ($category['image_hover_path'] !== null): ?>
                                    <img class="object-cover w-32 aspect-square"
                                        src="<?php echo $utility->categoryPicture('hover', $category['image_hover_path']); ?>"
                                        alt="">
                                <?php else: ?>
                                    <p class="text-red-500">No Photo Currently Available.</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="image_hover_path">Image Hover Path</label></td>
                            <td>:</td>
                            <td>
                                <label class="block mb-1 text-sm font-medium text-gray-900" for="image">Upload an
                                    Image</label>
                                <input
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 "
                                    type="file" id="image_hover_path" name="image_hover_path" accept="image/*">
                                <p class="mt-1 text-sm text-gray-500" id="image_input_help">PNG, JPG or JPEG (PREF.
                                    1080x1080px).</p>
                            </td>
                        </tr>
                    </table>
                    <div>
                        <input class="mt-4 text-white bg-black btn hover:bg-white hover:text-black hover:border-black"
                            type="submit" name="update_category" value="Update Category">
                    </div>
                </form>
            </div>
        </div>


        <!-- Add your additional HTML content and styling here -->
    </body>

    </html>

    <?php
}
?>