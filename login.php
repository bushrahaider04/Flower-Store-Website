<?php
session_start();

require_once 'config/database.php';

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    header('Location: modules/dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email']);
    $password = md5($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);

    $user = $stmt->fetch();

    if ($user) {

        $_SESSION['user'] = $user;

        // Redirect all users to dashboard
        header('Location: modules/dashboard.php');
        exit();

    } else {
        $error = 'Invalid email or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #f5e6f0 0%, #ffe6f0 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(233,30,99,0.15);
            border: 1px solid rgba(233,30,99,0.1);
        }

        .login-card h2 {
            color: #e91e63;
            font-weight: 600;
        }

        .btn-login {
            background: linear-gradient(135deg, #e91e63, #f06292);
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233,30,99,0.3);
        }

        .form-control {
            border-radius: 50px;
            padding: 12px 20px;
            border: 1px solid #f0e0e8;
        }

        .form-control:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 3px rgba(233,30,99,0.1);
        }

        .login-card a {
            color: #e91e63;
        }

        .login-card a:hover {
            color: #f06292;
        }
    </style>
</head>

<body>

<div class="login-card">

    <h2 class="text-center mb-4">
        <i class="fas fa-shield-alt me-2"></i>Admin Login
    </h2>

    <?php if($error): ?>
        <div class="alert alert-danger text-center" style="border-radius: 50px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>Login
        </button>

    </form>

    <div class="text-center mt-3">
        <a href="register.php" class="text-decoration-none">Create an account</a>
    </div>
</div>

</body>
</html>