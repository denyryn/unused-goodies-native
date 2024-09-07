<?php
require_once("../controllers/connection.php");
require_once("../controllers/manage_categories.php");
require_once("../controllers/admin_utility.php");

$utility = new admin_utility();

// Handle form submissions for adding or updating categories
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        // Add category form submitted
        $category_name = $_POST['category_name'];
        $category_description = $_POST['category_description'];

        $lastId = addCategory($category_name, $category_description, '', '');

        $lastData = getCategoryById($lastId);

        $imageCategory = $lastData['category_name']; // This var is for substituting it to the image path

        $targetDir = "../../../public/uploads/category_images/base/$category_name/";
        $hoverTargetDir = "../../../public/uploads/category_images/hover/$category_name/";  

        $fileInputName = 'image_base_path'; // Change this to match the name attribute of your file input
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

        $image_base_path = handleImageUpload($fileInputName, $targetDir);
        $image_hover_path = handleImageUpload($fileInputNameHover, $hoverTargetDir);


        if ($image_base_path !== false || $image_hover_path !== false) {
            addImageCategoryById($lastId, $fileInputName, $fileInputNameHover);

            header('Location: ./manage_categories_page.php');
            exit;
        } else {
            // Error adding category
            echo 'Error adding category.';
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
    <title>Manage Categories</title>
</head>

<body class="font-rubik">

    <div class="p-4 sm:ml-64">
        <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            <h2 class="text-xl font-bold select-none">Manage Categories</h2>
        </div>
    </div>

    <div class="p-4 sm:ml-64">
        <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            <h4 class="mb-2 font-semibold text-md ">Add Category</h4>
            <div class="">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"
                    enctype="multipart/form-data">
                    <table class="border-separate border-spacing-y-4" border="1" style="border-spacing: 0 10px;">
                        <tr>
                            <td><label for="category_name">Category Name &nbsp&nbsp&nbsp</label></td>
                            <td>: &nbsp&nbsp&nbsp</td>
                            <td>
                                <input
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                    type="text" id="category_name" name="category_name" required>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="category_description">Category Description &nbsp&nbsp&nbsp</label></td>
                            <td>:</td>
                            <td>
                                <textarea
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-sm border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    id="category_description" name="category_description" cols="30" required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="image_base_path">Image Base&nbsp&nbsp&nbsp</label></td>
                            <td>:</td>
                            <td>
                                <label class="block mb-1 text-sm font-medium text-gray-900" for="image_base_path">Upload
                                    an Image</label>
                                <input
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 "
                                    type="file" id="image_base_path" name="image_base_path" accept="image/*">
                                <p class="mt-1 text-sm text-gray-500" id="image_input_help">PNG, JPG or JPEG (PREF.
                                    1080x1080px).</p>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="image_hover_path">Image Hover &nbsp&nbsp&nbsp</label></td>
                            <td>:</td>
                            <td>
                                <label class="block mb-1 text-sm font-medium text-gray-900"
                                    for="image_hover_path">Upload an Image</label>
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
                            type="submit" name="add_category" value="Submit">
                    </div>
                </form>
                <img src="" alt="">
            </div>
        </div>
    </div>

    <div class="p-4 sm:ml-64">
        <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            <!-- Category List -->
            <h4 class="mb-2 font-semibold text-md ">Category List</h4>
            <table class="table table-zebra" border="1">
                <thead class="text-sm font-semibold">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image Base</th>
                        <th>Image Hover</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil semua data kategori
                    // Fetch all categories from the database
                    $categories = getAllCategories();
                    foreach ($categories as $category):
                        ?>
                        <tr>
                            <td><?php echo $category['category_id']; ?></td>
                            <td><?php echo $category['category_name']; ?></td>
                            <td><?php echo $category['category_description']; ?></td>
                            <td><img class="w-7"
                                    src="<?php $utility->categoryPicture('base', $category['image_base_path']) ?>" alt="">
                            </td>
                            <td><img class="w-7"
                                    src="<?php $utility->categoryPicture('hover', $category['image_hover_path']) ?>"
                                    alt=""></td>
                            <td>
                                <a class="hover:underline"
                                    href="edit_categories.php?action=edit&category_id=<?php echo $category['category_id']; ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>