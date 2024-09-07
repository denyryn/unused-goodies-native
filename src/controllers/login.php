<?php
// Include the database connection file
require_once 'connection.php';
require_once 'utility.php';

session_start(); // Start session to use $_SESSION

$utility = new Utility(); // Ensure correct class name (case-sensitive)

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim and sanitize input
    $username_or_email = $utility->trimFilter($_POST['username_or_email']);
    $password = $utility->trimFilter($_POST['password']);

    $hasError = false; // Flag to track errors

    // Validate username or email
    if (empty($username_or_email)) {
        $_SESSION['username_or_email_err'] = 'Please enter a username or email.';
        $hasError = true;
    }

    // Validate password
    if (empty($password)) {
        $_SESSION['password_err'] = 'Please enter your password.';
        $hasError = true;
    }

    // Redirect if there is an error
    if ($hasError) {
        header('Location: ' . PAGE_SOURCE);
        exit();
    }

    // Prepare a SELECT statement
    $sql = 'SELECT user_id, username, email, password FROM users 
                WHERE username = :username_or_email OR email = :username_or_email';

    if ($stmt = $pdo->prepare($sql)) {
        // Bind parameter to the prepared statement as both username and email
        $stmt->bindParam(':username_or_email', $username_or_email);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Check if username or email exists
            if ($stmt->rowCount() == 1) {
                if ($row = $stmt->fetch()) {
                    $hashed_password = $row['password'];

                    // Verify the password
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['email'] = $row['email'];

                        // Redirect to user dashboard or homepage
                        header('location:../pages/main_page.php');
                        exit();
                    } else {
                        // Password is not valid
                        $_SESSION['password_err'] = 'The password you entered is not valid.';
                        header('Location: ' . PAGE_SOURCE);
                        exit();
                    }
                }
            } else {
                // No account found with that username or email
                $_SESSION['username_or_email_err'] = 'No account found with that username or email.';
                header('Location: ' . PAGE_SOURCE);
                exit();
            }
        }
    }
    // If execution fails for any reason, redirect (as a fallback)
    header('Location: ' . PAGE_SOURCE);
    exit();
}
