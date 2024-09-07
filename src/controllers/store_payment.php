<?php 
    include ("config.php");
    include ("img_upload.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $order_id = $_POST['order_id'];

        $fileInputName = "payment";
        $targetDir = "../../uploads/user_payment/$order_id/";

        
        if (!is_dir($targetDir)) {
            // Create the directory if it doesn't exist
            mkdir($targetDir, 0755, true);
            chmod($targetDir, 0755);
        }

        list($newFileName, $targetFile) = handleImageUpload($fileInputName, $targetDir);

        $targetFDir = "../uploads/user_payment/$order_id/";

        $paymentPhotoPath = $targetFDir . $newFileName;

        // insert path foto ke kolom payment
        $pdo_statement = $pdo->prepare("UPDATE orders SET payment = :payment WHERE order_id = :order_id");

        $pdo_statement->bindParam(':payment', $paymentPhotoPath);
        $pdo_statement->bindParam(':order_id', $order_id);

        if ($pdo_statement->execute()) {
            // Update success
            header("Location: ../order_details.php"); // Redirect to the profile page
            exit();
        } else {
            // Update fail
            echo "Error updating profile. Please try again.";
        }
    }
?>