<?php
// Include the database connection file
require_once 'connection.php';
require_once 'utility.php';

session_start(); // Start session to use $_SESSION

$utility = new utility();

// Define variables and initialize with empty values
$username_err = $password_err = $confirm_password_err = $email_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $utility->trimFilter($_POST['username']);
    $full_name = $utility->trimFilter($_POST['full_name']);
    $email = $utility->trimFilter($_POST['email']);
    $password = $utility->trimFilter($_POST['password']);
    $confirm_password = $utility->trimFilter($_POST['confirm_password']);

    $hasError = false;

    // Validate username
    if (empty($username)) {
        $_SESSION['username_err'] = 'Please enter a username.';
        $hasError = true;
    }

    // Validate fullname
    if (empty($full_name)) {
        $_SESSION['full_name_err'] = 'Please enter a full name.';
        $hasError = true;
    }

    // Validate email
    if (empty($email)) {
        $_SESSION['email_err'] = 'Please enter an email.';
        $hasError = true;
    }

    // Validate password
    if (empty($password)) {
        $_SESSION['password_err'] = 'Please enter a password.';
        $hasError = true;
    } elseif (strlen($password) < 6) {
        $_SESSION['password_err'] = 'Password must have at least 6 characters.';
        $hasError = true;
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $_SESSION['confirm_password_err'] = 'Please confirm your password.';
        $hasError = true;
    } elseif ($password !== $confirm_password) {
        $_SESSION['confirm_password_err'] = 'Password did not match.';
        $hasError = true;
    }

    // Redirect only if there is an error
    if ($hasError) {
        header('Location: ' . PAGE_SOURCE);
        exit();
    }

    // Check for errors before inserting into the database

    // Prepare an INSERT statement
    $sql = 'INSERT INTO users (username, email, full_name, password) 
                    VALUES (:username, :email, :full_name, :password)';

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(':username', $param_username);
        $stmt->bindParam(':full_name', $param_username);
        $stmt->bindParam(':email', $param_email);
        $stmt->bindParam(':password', $param_password);

        // Set parameters
        $param_full_name = $full_name;
        $param_username = $username;
        $param_email = $email;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            header('location: ../pages/login_page.php');
        } else {
            header('Location: ' . PAGE_SOURCE);
            exit();
        }
    }
}