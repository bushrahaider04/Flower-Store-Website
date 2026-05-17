<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../../login.php');

$pdo = new PDO("mysql:host=localhost;dbname=website_flower", "root", "");

// Get orders with user names
$orders = $pdo->query("
    SELECT o.*, u.name as user_name 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.id DESC
")->fetchAll();

// Update order status
if(isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $pdo->query("UPDATE orders SET status = '$status' WHERE id = $order_id");
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:#f0f2f5; }
        
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
        
        .container-custom { padding: 25px 30px; }
        .card-white { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending { background: #ffc107; color: #000; }
        .status-processing { background: #17a2b8; color: #fff; }
        .status-shipped { background: #007bff; color: #fff; }
        .status-delivered { background: #28a745; color: #fff; }
        
        .btn-update-status {
            background: #ff6b9d;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        @media (max-width: 768px) { .nav-links { display: none; } }
    </style>
</head>
<body>

<div class="top-nav">
    <div class="logo"><i class="fas fa-crown"></i> AdminPanel</div>
    <div class="nav-links">
        <a href="../dashboard.php"><i class="fas fa-home"></i> home</a>
        <a href="../products/index.php"><i class="fas fa-box"></i> products</a>
        <a href="index.php" class="active"><i class="fas fa-shopping-cart"></i> orders</a>
        <a href="../users/index.php"><i class="fas fa-users"></i> users</a>
        <a href="../messages/index.php"><i class="fas fa-envelope"></i> messages</a>
    </div>
    <div class="user-info">
        <span><i class="fas fa-user-circle"></i> <?php echo $_SESSION['user']['name']; ?></span>
        <a href="../../logout.php" class="logout-btn">logout</a>
    </div>
</div>

<div class="container-custom">
    <h2 style="color:#1e1e2f; margin-bottom:20px;"><i class="fas fa-shopping-cart"></i> Orders Management</h2>
    
    <div class="card-white">
        <?php if(empty($orders)): ?>
            <div style="text-align:center; padding:50px;">No orders found!</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead style="background:#ff6b9d; color:white;">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $order): ?>
                        <tr>
                            <td>#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo htmlspecialchars($order['user_name'] ?? 'Guest'); ?></td>
                            <td>₨ <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" class="form-select form-select-sm" style="width:120px; display:inline-block;">
                                        <option value="pending" <?php echo $order['status']=='pending'?'selected':''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status']=='processing'?'selected':''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status']=='shipped'?'selected':''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status']=='delivered'?'selected':''; ?>>Delivered</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn-update-status">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>