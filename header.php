<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Business Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/website_flower/modules/dashboard.php">
                <i class="fas fa-store me-2"></i>Admin Dashboard
            </a>
            <div class="d-flex">
                <span class="text-white me-3">
                    <i class="fas fa-user me-1"></i><?php echo $_SESSION['user']['name']; ?>
                </span>
                <a href="/website_flower/logout.php" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <div class="mt-3">
                    
                    <a href="/website_flower/modules/dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="/website_flower/modules/products/index.php">
                        <i class="fas fa-box me-2"></i>Products
                    </a>
                    <a href="/website_flower/modules/orders/index.php">
                        <i class="fas fa-shopping-cart me-2"></i>Orders
                    </a>
                    <a href="/website_flower/modules/users/index.php">
                        <i class="fas fa-users me-2"></i>Users
                    </a>
                    <a href="/website_flower/modules/messages/index.php">
                        <i class="fas fa-envelope me-2"></i>Messages
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">