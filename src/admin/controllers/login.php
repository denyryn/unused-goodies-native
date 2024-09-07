<?php

require_once '../controllers/connection.php';

// Initialize variables
$username = $password = '';
$username_err = $password_err = '';

// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter your username.';
    } else {
        $username = trim($_POST['username']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    if (empty($username_err) && empty($password_err)) {

        $sql = 'SELECT admin_id, username, password FROM admin_users WHERE username = :username';

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':username', $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Check if username exists, verify password
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $hashed_password = $row['password'];
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session

                            // Store data in session variables
                            $_SESSION['admin_id'] = $row['admin_id'];
                            $_SESSION['username'] = $row['username'];

                            // Redirect to user dashboard or homepage
                            header('location: ../pages/dashboard_page.php');
                            exit();
                        } else {
                            $_SESSION['password_err'] = 'The password you entered is not valid.';
                        }
                    }
                } else {
                    $_SESSION['username_or_email_err'] = 'No account found with that username';
                }
            }
        }
    }

    // Redirect back to the source page if there are errors or if the user was not authenticated
    header('Location: ' . PAGE_SOURCE);
    exit();
}