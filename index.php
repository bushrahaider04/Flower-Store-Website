<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../../login.php');

$pdo = new PDO("mysql:host=localhost;dbname=website_flower", "root", "");
$users = $pdo->query("SELECT * FROM users WHERE role='user' ORDER BY id DESC")->fetchAll();
$adminCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->query("DELETE FROM users WHERE id = $id");
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users Management</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-bottom: 1px solid #eee;
        }
        .logo { font-size: 22px; font-weight: 700; color: #ff6b9d; }
        .logo i { margin-right: 10px; }
        .nav-links a {
            color: #1e1e2f;
            text-decoration: none;
            margin: 0 15px;
            padding: 8px 0;
            font-weight: 500;
        }
        .nav-links a:hover, .nav-links a.active { color: #ff6b9d; border-bottom: 2px solid #ff6b9d; }
        .user-info { display: flex; align-items: center; gap: 20px; }
        .logout-btn { background: #ff6b9d; padding: 8px 20px; border-radius: 25px; color: white; text-decoration: none; }
        
        .container-custom { padding: 25px 30px; }
        .card-white { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        
        .user-avatar {
            width: 40px; height: 40px;
            background: #ff6b9d;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .btn-delete { background: #ef5350; color: white; border: none; padding: 5px 15px; border-radius: 20px; }
        
        @media (max-width: 768px) { .nav-links { display: none; } }
    </style>
</head>
<body>

<div class="top-nav">
    <div class="logo"><i class="fas fa-crown"></i> AdminPanel</div>
    <div class="nav-links">
        <a href="../dashboard.php"><i class="fas fa-home"></i> home</a>
        <a href="../products/index.php"><i class="fas fa-box"></i> products</a>
        <a href="../orders/index.php"><i class="fas fa-shopping-cart"></i> orders</a>
        <a href="index.php" class="active"><i class="fas fa-users"></i> users</a>
        <a href="../messages/index.php"><i class="fas fa-envelope"></i> messages</a>
    </div>
    <div class="user-info">
        <span><i class="fas fa-user-circle"></i> <?php echo $_SESSION['user']['name']; ?></span>
        <a href="../../logout.php" class="logout-btn">logout</a>
    </div>
</div>

<div class="container-custom">
    <h2 style="color:#1e1e2f; margin-bottom:20px;"><i class="fas fa-users"></i> Users Management</h2>
    
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card-white" style="text-align:center;">
                <h3><?php echo count($users); ?></h3>
                <p>Normal Users</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-white" style="text-align:center;">
                <h3><?php echo $adminCount; ?></h3>
                <p>Admin Users</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-white" style="text-align:center;">
                <h3><?php echo count($users) + $adminCount; ?></h3>
                <p>Total Accounts</p>
            </div>
        </div>
    </div>
    
    <div class="card-white">
        <?php if(empty($users)): ?>
            <div style="text-align:center; padding:50px;">No users found!</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead style="background:#ff6b9d; color:white;">
                        <tr><th>ID</th><th>User</th><th>Email</th><th>Registered Date</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td>#<?php echo str_pad($user['id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td>
                                <div class="user-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
                                <?php echo htmlspecialchars($user['name']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <a href="?delete=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Delete this user?')">Delete</a>
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