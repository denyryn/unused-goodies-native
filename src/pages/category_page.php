<?php
require_once("../controllers/connection.php");
require_once("../controllers/utility.php");

$utility = new utility();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/app.css">
    <link rel="icon" type="image/x-icon" href="../assets/ico/favicon.ico">
    <title>Category Page</title>
</head>

<body>
    <?php
    include("navbar.php");

    try {

        // Fetch all categories
        $stmt = $pdo->query("SELECT * FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll();

        // Check if there are any categories
        if (!$categories) {
            echo "<p class=\"h-[80vh] flex justify-center items-center text-xl font-extrabold\">No categories found!</p>";
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
        Categories
    </span>

    <div class="p-3">
        <div class="grid grid-cols-1 gap-4 my-3 lg:grid-cols-4 lg:gap-8" id="categoryContainer">

            <?php foreach ($categories as $category) { ?>

                <a href="product_page.php?category_id=<?php echo $category['category_id']; ?>" class="relative block group">
                    <div class="relative h-[200px] sm:h-[300px]">
                        <img src="<?php $utility->categoryPicture('base', $category['image_base_path']) ?>"
                            alt="<?php echo $category['category_name']; ?>"
                            class="absolute inset-0 object-cover w-full h-full duration-150 opacity-100 group-hover:opacity-0 brightness-50">
                        <img src="<?php $utility->categoryPicture('hover', $category['image_hover_path']) ?>"
                            alt="<?php echo $category['category_name']; ?>"
                            class="absolute inset-0 object-cover w-full h-full duration-150 opacity-0 group-hover:opacity-100 brightness-50">
                    </div>
                    <div class="absolute inset-0 flex flex-col items-start justify-end p-6">
                        <h3 class="text-xl font-medium text-white"><?php echo $category['category_name']; ?></h3>
                        <p class="mt-1.5 max-w-[40ch] text-xs text-white"><?php echo $category['category_description']; ?>
                        </p>
                        <span
                            class="inline-block px-5 py-3 mt-3 text-xs font-medium tracking-wide text-white uppercase bg-black">Shop
                            Now</span>
                    </div>
                </a>

            <?php } ?>

        </div>
    </div>

    <?php include("footer.php"); ?>
</body>

</html>