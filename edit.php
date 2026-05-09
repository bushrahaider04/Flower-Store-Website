<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../../login.php');

$pdo = new PDO("mysql:host=localhost;dbname=website_flower", "root", "");
$id = $_GET['id'];
$product = $pdo->query("SELECT * FROM products WHERE id = $id")->fetch();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo->query("UPDATE products SET name='{$_POST['name']}', price='{$_POST['price']}', description='{$_POST['description']}' WHERE id=$id");
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:#f0f2f5; }
        .top-nav {
            background: #1e1e2f;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .logo { font-size: 22px; font-weight: 700; color: #ff6b9d; }
        .nav-links a { color: white; text-decoration: none; margin: 0 15px; }
        .logout-btn { background: #ff6b9d; padding: 8px 20px; border-radius: 25px; color: white; text-decoration: none; }
        .container-custom { max-width: 550px; margin: 50px auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .form-input { width: 100%; padding: 12px 15px; margin-bottom: 15px; border: 1px solid #e0e0e0; border-radius: 10px; }
        .btn-update { background: #ff6b9d; color: white; border: none; padding: 12px 25px; border-radius: 25px; width: 100%; font-weight: 500; }
        h2 { color: #1e1e2f; margin-bottom: 25px; text-align: center; font-size: 22px; }
        h2 i { color: #ff6b9d; margin-right: 10px; }
    </style>
</head>
<body>

<div class="top-nav">
    <div class="logo"><i class="fas fa-crown"></i> AdminPanel</div>
    <div class="nav-links">
        <a href="../dashboard.php">home</a> <a href="index.php">products</a> <a href="../orders/index.php">orders</a>
        <a href="../users/index.php">users</a> <a href="../messages/index.php">messages</a>
    </div>
    <div><a href="../../logout.php" class="logout-btn">logout</a></div>
</div>

<div class="container-custom">
    <h2><i class="fas fa-edit"></i> UPDATE PRODUCT</h2>
    <form method="POST">
        <input type="text" name="name" class="form-input" value="<?php echo $product['name']; ?>" placeholder="product name">
        <input type="number" step="0.01" name="price" class="form-input" value="<?php echo $product['price']; ?>" placeholder="price (PKR)">
        <textarea name="description" class="form-input" rows="5" placeholder="product details"><?php echo $product['description']; ?></textarea>
        <button type="submit" class="btn-update"><i class="fas fa-save"></i> Update Product</button>
    </form>
</div>

</body>
</html>