<?php
session_start();

if(!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=website_flower", "root", "");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    
    $pdo->query("INSERT INTO products (name, price, stock, description) VALUES ('$name', '$price', '$stock', '$description')");
    
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Pink Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #ffe6f0 0%, #ffd6e8 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        
        .container-custom {
            max-width: 600px;
            margin: auto;
        }
        
        .card-pink {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(255, 77, 122, 0.15);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-out;
        }
        
        .card-header-pink {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff4d7a 100%);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        .card-header-pink h2 {
            margin: 0;
            font-weight: 600;
            font-size: 28px;
        }
        
        .card-header-pink p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .card-body-pink {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 500;
            color: #5a2a4a;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-label i {
            margin-right: 8px;
            color: #ff6b9d;
        }
        
        .form-control-pink {
            width: 100%;
            padding: 12px 18px;
            border: 2px solid #f0e0e8;
            border-radius: 15px;
            font-size: 14px;
            transition: all 0.3s;
            background: #fef9fc;
        }
        
        .form-control-pink:focus {
            outline: none;
            border-color: #ff6b9d;
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
        }
        
        textarea.form-control-pink {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn-pink {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff4d7a 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .btn-pink:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 77, 122, 0.4);
        }
        
        .btn-secondary-pink {
            background: #f0e0e8;
            color: #5a2a4a;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-secondary-pink:hover {
            background: #e5d0dc;
            transform: translateY(-2px);
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .button-group .btn-pink,
        .button-group .btn-secondary-pink {
            flex: 1;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .floating-heart {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 40px;
            color: #ff6b9d;
            opacity: 0.6;
            animation: float 3s ease-in-out infinite;
            pointer-events: none;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #ff4d7a;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .back-link:hover {
            color: #ff6b9d;
            transform: translateX(-5px);
        }
        
        .required-star {
            color: #ff4d7a;
            margin-left: 4px;
        }
    </style>
</head>
<body>

<div class="container-custom">
    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>Back to Products
    </a>
    
    <div class="card-pink">
        <div class="card-header-pink">
            <i class="fas fa-heart" style="font-size: 40px; margin-bottom: 10px;"></i>
            <h2>Add New Product</h2>
            <p>Fill in the details to add a new product to your collection</p>
        </div>
        
        <div class="card-body-pink">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tag"></i>Product Name <span class="required-star">*</span>
                    </label>
                    <input type="text" name="name" class="form-control-pink" placeholder="Enter product name" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign"></i>Price <span class="required-star">*</span>
                    </label>
                    <input type="number" step="0.01" name="price" class="form-control-pink" placeholder="0.00" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-boxes"></i>Stock Quantity <span class="required-star">*</span>
                    </label>
                    <input type="number" name="stock" class="form-control-pink" placeholder="Enter stock quantity" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-align-left"></i>Description
                    </label>
                    <textarea name="description" class="form-control-pink" placeholder="Enter product description..."></textarea>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn-pink">
                        <i class="fas fa-save me-2"></i>Save Product
                    </button>
                    <a href="index.php" class="btn-secondary-pink">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="floating-heart">
    <i class="fas fa-heart"></i>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>