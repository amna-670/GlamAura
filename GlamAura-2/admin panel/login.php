<?php
ob_start(); 
session_start();
include("connect.php");

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $sql = "SELECT * FROM admin_users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);

        if ($password === $admin['password']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            header("Location: index.php");
            exit();
        } else {
            $error = "❌ Invalid username or password.";
        }
    } else {
        $error = "❌ User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Secure Access</title>
    <link rel="icon" href="uploads/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        
        .login-container {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .logo i {
            font-size: 42px;
            color: #6a11cb;
            background: rgba(106, 17, 203, 0.1);
            padding: 15px;
            border-radius: 50%;
        }
        
        .logo h2 {
            margin-top: 15px;
            color: #333;
            font-size: 24px;
            font-weight: 600;
        }
        
        .input-group {
            margin-bottom: 15px;
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6a11cb;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 35px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .input-group input:focus {
            border-color: #6a11cb;
            outline: none;
            box-shadow: 0 0 0 2px rgba(106, 17, 203, 0.2);
        }
        
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .error {
            background: #ffe6e6;
            color: #cc0000;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .options {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 14px;
        }
        
        .options a {
            color: #6a11cb;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .options a:hover {
            text-decoration: underline;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #777;
            font-size: 13px;
        }
        
        .demo-note {
            background: #f0f5ff;
            padding: 12px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 14px;
            color: #6a11cb;
            text-align: center;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6a11cb;
            cursor: pointer;
        }
        
        .admin-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        
        .admin-title i {
            font-size: 24px;
            color: #6a11cb;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="admin-title">
        <i class="fas fa-lock"></i> Admin Login
    </div>

    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <i class="fas fa-key"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Log In</button>
</div>

</body>
</html>
<?php ob_end_flush(); ?>
