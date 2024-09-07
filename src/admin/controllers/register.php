<?php
require_once 'connection.php';
// require_once 'utility.php';

// Mendefinisikan variabel untuk menandakan eror pada inputtan user
// Define variables and initialize with empty values
$username = $password = $confirm_password = '';
$username_err = $password_err = $confirm_password_err = '';

// Jika ada request post dari form yang disubmit
// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = 'Empty Username.';
    } else {
        $username = trim($_POST['username']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Empty Password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Empty Confirm.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password !== $confirm_password) {
            $confirm_password_err = 'Password didnt match.';
        }
    }

    // Cek jika variabel yang akan diisi jika ada eror, apakah sekarang bernilai kosong, jika iya maka query akan dijalankan
    // Check for errors before inserting into the database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

        $sql = 'INSERT INTO admin_users (username, password) VALUES (:username, :password)';

        if ($stmt = $pdo->prepare($sql)) {

            // Parameter untuk query
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

            // Paramter diatas dikaitkan, bindparam duridam
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username);
            $stmt->bindParam(':password', $param_password);

            // Execute
            if ($stmt->execute()) {
                // Menuju login page jika register berhasil
                // Redirect to login page if success
                header('location: ../pages/login_page.php');
            }
        }
    }
    // If execution fails for any reason, redirect (as a fallback)
    header('Location: ' . PAGE_SOURCE);
    exit();
}