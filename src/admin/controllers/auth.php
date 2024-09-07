<?php
session_start();

require_once('connection.php');

// Function to check if an admin is logged in
function isAdminLoggedIn()
{
    return isset($_SESSION['admin_id']);
}