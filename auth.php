<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is admin
function isAdmin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: ../login.php');
        exit();
    }
}

// Check if user is logged in
function isLoggedIn() {
    if (!isset($_SESSION['user'])) {
        header('Location: ../login.php');
        exit();
    }
}
?>