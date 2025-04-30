<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic Store</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #e3e3e3;
            color: #333;
        }
        .header {
            background: linear-gradient(135deg, #007BFF, #004092);
            color: white;
            padding: 25px;
            text-align: center;
            font-size: 26px;
            font-weight: 600;
            letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .navbar {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #002f6c;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 0 0 10px 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 14px 25px;
            margin: 0 12px;
            font-size: 18px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }
        .navbar a:hover {
            background: rgba(196, 60, 60, 0.2);
            transform: translateY(-3px);
        }
        .logout-btn {
            background: #d9534f;
            color: white;
            padding: 14px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 500;
            margin-left: 20px;
            transition: all 0.3s ease-in-out;
        }
        .logout-btn:hover {
            background: #c9302c;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="header">
        Electronic Store
    </div>
    <div class="navbar">
       

    <?php if (isset($_SESSION['username'])): ?>
        <a href="home.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="cart.php">cart</a>
        <a href="vieworder.php">order</a>
        <a href="About.php">About</a>
        <form action="logout.php" method="post" style="display:inline; margin-left: auto;">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    <?php else: ?>
        
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    <?php endif; ?>
</div>

</body>

</html>
