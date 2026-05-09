<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../../login.php');

$pdo = new PDO("mysql:host=localhost;dbname=website_flower", "root", "");
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:#f0f2f5; }
        
        /* Top Navbar - White */
        .top-nav {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #1e1e2f;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-bottom: 1px solid #eee;
        }
        .logo { font-size: 22px; font-weight: 700; color: #ff6b9d; }
        .logo i { margin-right: 10px; color: #ff6b9d; }
        .nav-links a {
            color: #1e1e2f;
            text-decoration: none;
            margin: 0 15px;
            padding: 8px 0;
            font-weight: 500;
            transition: 0.3s;
        }
        .nav-links a:hover, .nav-links a.active { color: #ff6b9d; border-bottom: 2px solid #ff6b9d; }
        .user-info { display: flex; align-items: center; gap: 20px; }
        .user-info span { color: #1e1e2f; }
        .user-info span i { color: #ff6b9d; margin-right: 8px; }
        .logout-btn { background: #ff6b9d; padding: 8px 20px; border-radius: 25px; color: white; text-decoration: none; font-size: 14px; }
        .logout-btn:hover { background: #ff4d7a; }
        
        .container-custom { padding: 25px 30px; }
        
        .add-product-form {
            background: white; border-radius: 15px; padding: 25px; margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .add-product-form h3 { color: #1e1e2f; margin-bottom: 20px; font-size: 18px; }
        .add-product-form h3 i { color: #ff6b9d; margin-right: 10px; }
        .form-input {
            width: 100%; padding: 12px 15px; margin-bottom: 15px; border: 1px solid #e0e0e0;
            border-radius: 10px; font-size: 14px;
        }
        .form-input:focus { outline: none; border-color: #ff6b9d; }
        .btn-add {
            background: #ff6b9d; color: white; border: none; padding: 12px 25px;
            border-radius: 25px; cursor: pointer; font-weight: 500;
        }
        .btn-add:hover { background: #ff4d7a; }
        
        .products-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px; margin-top: 20px;
        }
        .product-card {
            background: white; border-radius: 15px; overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: 0.3s;
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        
        /* Blank Image Placeholder */
        .product-img {
            background: #faf0f5;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #eee;
        }
        .product-img i {
            font-size: 60px;
            color: #ff6b9d;
        }
        
        .product-info { padding: 15px; }
        .product-name { font-size: 16px; font-weight: 700; color: #1e1e2f; margin-bottom: 5px; }
        .product-price { color: #ff6b9d; font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .product-desc { color: #888; font-size: 12px; margin-bottom: 15px; line-height: 1.4; }
        
        .btn-update { background: #ffc107; color: #000; border: none; padding: 6px 15px; border-radius: 20px; margin-right: 8px; font-size: 12px; }
        .btn-delete { background: #ef5350; color: white; border: none; padding: 6px 15px; border-radius: 20px; font-size: 12px; }
        
        @media (max-width: 768px) { .nav-links { display: none; } }
    </style>
</head>
<body>

<!-- Top Navbar - White -->
<div class="top-nav">
    <div class="logo"><i class="fas fa-crown"></i> AdminPanel</div>
    <div class="nav-links">
        <a href="../dashboard.php"><i class="fas fa-home"></i> home</a>
        <a href="index.php" class="active"><i class="fas fa-box"></i> products</a>
        <a href="../orders/index.php"><i class="fas fa-shopping-cart"></i> orders</a>
        <a href="../users/index.php"><i class="fas fa-users"></i> users</a>
        <a href="../messages/index.php"><i class="fas fa-envelope"></i> messages</a>
    </div>
    <div class="user-info">
        <span><i class="fas fa-user-circle"></i> <?php echo $_SESSION['user']['name']; ?></span>
        <a href="../../logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> logout</a>
    </div>
</div>

<div class="container-custom">
    <!-- Add Product Section -->
    <div class="add-product-form">
        <h3><i class="fas fa-plus-circle"></i> ADD PRODUCT</h3>
        <form action="create.php" method="POST">
            <input type="text" name="name" class="form-input" placeholder="product name" required>
            <input type="number" step="0.01" name="price" class="form-input" placeholder="enter product price (PKR)" required>
            <input type="number" name="stock" class="form-input" placeholder="enter product stock" required>
            <textarea name="description" class="form-input" rows="3" placeholder="enter product details"></textarea>
            <button type="submit" class="btn-add"><i class="fas fa-plus"></i> Add Product</button>
        </form>
    </div>

    <!-- Products List -->
    <h3 style="color:#1e1e2f; margin-bottom:15px;"><i class="fas fa-box"></i> SHOP PRODUCTS</h3>
    
    <?php if(empty($products)): ?>
        <div style="background:white; text-align:center; padding:50px; border-radius:15px;">No products added yet!</div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach($products as $p): ?>
            <div class="product-card">
                <!-- Blank Image Placeholder -->
                <div class="product-img">
                    <i class="fas fa-image"></i>
                </div>
                <div class="product-info">
                    <div class="product-name"><?php echo $p['name']; ?></div>
                    <div class="product-price">₨ <?php echo number_format($p['price'], 2); ?>/-</div>
                    <div class="product-desc"><?php echo substr($p['description'], 0, 70); ?>...</div>
                    <div>
                        <a href="edit.php?id=<?php echo $p['id']; ?>" class="btn-update"><i class="fas fa-edit"></i> Update</a>
                        <a href="delete.php?id=<?php echo $p['id']; ?>" class="btn-delete" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>