<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../../login.php');

$pdo = new PDO("mysql:host=localhost;dbname=website_flower", "root", "");

// Mark as read
if(isset($_GET['mark_read'])) {
    $id = $_GET['mark_read'];
    $pdo->query("UPDATE messages SET is_read = 1 WHERE id = $id");
    header('Location: index.php');
    exit();
}

// Delete message
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->query("DELETE FROM messages WHERE id = $id");
    header('Location: index.php');
    exit();
}

$messages = $pdo->query("SELECT * FROM messages ORDER BY is_read ASC, id DESC")->fetchAll();
$unreadCount = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages Management</title>
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
        
        .message-unread { background-color: #fff9e6; border-left: 4px solid #ffc107; }
        .message-read { background-color: white; }
        
        .status-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .status-unread { background: #ffc107; color: #000; }
        .status-read { background: #28a745; color: white; }
        
        .btn-mark-read { background: #28a745; color: white; border: none; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .btn-delete { background: #ef5350; color: white; border: none; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .btn-view { background: #17a2b8; color: white; border: none; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        
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
        <a href="../users/index.php"><i class="fas fa-users"></i> users</a>
        <a href="index.php" class="active"><i class="fas fa-envelope"></i> messages</a>
    </div>
    <div class="user-info">
        <span><i class="fas fa-user-circle"></i> <?php echo $_SESSION['user']['name']; ?></span>
        <a href="../../logout.php" class="logout-btn">logout</a>
    </div>
</div>

<div class="container-custom">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2 style="color:#1e1e2f;"><i class="fas fa-envelope"></i> Customer Messages</h2>
        </div>
        <div class="col-md-6 text-end">
            <span class="status-badge status-unread">Unread: <?php echo $unreadCount; ?></span>
        </div>
    </div>
    
    <div class="card-white">
        <?php if(empty($messages)): ?>
            <div style="text-align:center; padding:50px;">No messages found!</div>
        <?php else: ?>
            <?php foreach($messages as $msg): ?>
            <div class="row message-<?php echo $msg['is_read']?'read':'unread'; ?>" style="padding:15px; border-bottom:1px solid #eee;">
                <div class="col-md-3">
                    <strong><?php echo htmlspecialchars($msg['name']); ?></strong><br>
                    <small><?php echo htmlspecialchars($msg['email']); ?></small>
                </div>
                <div class="col-md-5">
                    <?php echo htmlspecialchars(substr($msg['message'], 0, 80)); ?>...
                </div>
                <div class="col-md-2">
                    <span class="status-badge <?php echo $msg['is_read']?'status-read':'status-unread'; ?>">
                        <?php echo $msg['is_read']?'Read':'Unread'; ?>
                    </span><br>
                    <small><?php echo date('d M Y', strtotime($msg['created_at'])); ?></small>
                </div>
                <div class="col-md-2">
                    <button class="btn-view" data-bs-toggle="modal" data-bs-target="#msgModal<?php echo $msg['id']; ?>">View</button>
                    <?php if(!$msg['is_read']): ?>
                        <a href="?mark_read=<?php echo $msg['id']; ?>" class="btn-mark-read">Mark Read</a>
                    <?php endif; ?>
                    <a href="?delete=<?php echo $msg['id']; ?>" class="btn-delete" onclick="return confirm('Delete this message?')">Delete</a>
                </div>
            </div>
            
            <!-- Modal -->
            <div class="modal fade" id="msgModal<?php echo $msg['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background:#ff6b9d; color:white;">
                            <h5 class="modal-title">Message from <?php echo htmlspecialchars($msg['name']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($msg['email']); ?></p>
                            <p><strong>Date:</strong> <?php echo $msg['created_at']; ?></p>
                            <hr>
                            <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>