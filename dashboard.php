<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../login.php');

$pdo = new PDO("mysql:host=localhost;dbname=website_flower", "root", "");

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
$totalMessages = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
$totalAdmins = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
$totalAccounts = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$totalPendings = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status='pending'")->fetchColumn();
$completedPayments = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status='delivered'")->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
        .user-info span i { margin-right: 8px; color: #ff6b9d; }
        .logout-btn { background: #ff6b9d; padding: 8px 20px; border-radius: 25px; color: white; text-decoration: none; font-size: 14px; }
        .logout-btn:hover { background: #ff4d7a; }
        
        /* Dashboard Container */
        .dashboard-container { padding: 25px 30px; }
        
        /* Big Bold Middle Text */
        .welcome-text {
            text-align: center;
            margin: 40px 0;
        }
        .welcome-text h1 {
            font-size: 48px;
            font-weight: 800;
            color: #1e1e2f;
        }
        .welcome-text p {
            font-size: 18px;
            color: #888;
            margin-top: 10px;
        }
        
        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .stat-title { color: #888; font-size: 13px; margin-bottom: 8px; letter-spacing: 0.5px; }
        .stat-value { font-size: 28px; font-weight: 700; color: #1e1e2f; }
        
        @media (max-width: 1000px) { .stats-row { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 768px) { .nav-links { display: none; } }
    </style>
</head>
<body>

<!-- Top Navigation - White -->
<div class="top-nav">
    <div class="logo">
        <i class="fas fa-crown"></i> AdminPanel
    </div>
    <div class="nav-links">
        <a href="dashboard.php" class="active"><i class="fas fa-home"></i> home</a>
        <a href="products/index.php"><i class="fas fa-box"></i> products</a>
        <a href="orders/index.php"><i class="fas fa-shopping-cart"></i> orders</a>
        <a href="users/index.php"><i class="fas fa-users"></i> users</a>
        <a href="messages/index.php"><i class="fas fa-envelope"></i> messages</a>
    </div>
    <div class="user-info">
        <span><i class="fas fa-user-circle"></i> <?php echo $_SESSION['user']['name']; ?></span>
        <a href="../logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> logout</a>
    </div>
</div>

<!-- Dashboard Content -->
<div class="dashboard-container">
    
    <!-- Big Bold Middle Text -->
    <div class="welcome-text">
        <h1>Welcome to Admin Panel</h1>
        <p>Manage your store efficiently</p>
    </div>
    
    <!-- First Row Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-title">TOTAL PENDINGS</div>
            <div class="stat-value">₨ <?php echo number_format($totalPendings ?? 0, 2); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-title">COMPLETED PAYMENTS</div>
            <div class="stat-value">₨ <?php echo number_format($completedPayments ?? 0, 2); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-title">ORDERS PLACED</div>
            <div class="stat-value"><?php echo $totalOrders; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-title">PRODUCTS ADDED</div>
            <div class="stat-value"><?php echo $totalProducts; ?></div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-title">NORMAL USERS</div>
            <div class="stat-value"><?php echo $totalUsers; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-title">ADMIN USERS</div>
            <div class="stat-value"><?php echo $totalAdmins; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-title">TOTAL ACCOUNTS</div>
            <div class="stat-value"><?php echo $totalAccounts; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-title">NEW MESSAGES</div>
            <div class="stat-value"><?php echo $totalMessages; ?></div>
        </div>
    </div>
</div>

</body>
</html>