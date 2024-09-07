<?php
require_once("controllers/auth.php");

if (isAdminLoggedIn()) {
    header("location: pages/dashboard_page.php");
} else {
    header("location: pages/login_page.php");
}