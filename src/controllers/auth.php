<?php
session_start();

// Function to check if a user is logged in
function isUserLoggedIn()
{
    return isset($_SESSION['user_id']);
}