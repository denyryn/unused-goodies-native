<?php
include('../controllers/manage_orders.php');

if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $details = getOrderDetailById($order_id);

    if (!$order_id) {
        echo "error.";
        header('Location: ./manage_orders_page.php');
        exit;
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
        <title>Order Details</title>
    </head>

    <body class="font-rubik">
        <div class="p-4 sm:ml-64">
            <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <h2 class="text-xl font-bold select-none">Manage Orders</h2>
            </div>
        </div>

        <div class="p-4 sm:ml-64">
            <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <h4 class="mb-2 font-semibold text-md">Order #<?php echo $details[0]['order_id']; ?></h4>
                <table class="table table-zebra" border="1">
                    <thead class="text-sm font-semibold">
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </thead>
                    <?php
                    foreach ($details as $detail):
                        ?>
                        <tbody>
                            <td><?php echo $detail['product_name']; ?></td>
                            <td><?php echo $detail['quantity']; ?></td>
                            <td>Rp <?php echo number_format($detail['price'], 2); ?></td>
                        </tbody>
                        <?php
                    endforeach;
                    ?>
                </table>
            </div>
        </div>

        <div class="p-4 sm:ml-64">
            <div class="h-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <a class="text-base hover:underline" href="./manage_orders_page.php">Back to Manage</a>
            </div>
        </div>
    </body>

    </html>

    <?php
}
?>