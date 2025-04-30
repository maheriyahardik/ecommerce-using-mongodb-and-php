<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            background:rgb(207, 95, 117);
            text-decoration: none;
            padding: 14px 25px;
            margin: 0 12px;
            font-size: 18px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }
        .navbar a:hover {
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
        .container {
            max-width: 1200px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .container h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .container p {
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        Electronic Store Management
    </div>
    <div class="navbar">
        <a href="admin.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="subcategories.php">Subcategories</a>
        <a href="products.php">Products</a>
        <a href="inventory.php">Inventory</a>
        <a href="search.php">Search</a>
        <a href='viewusers.php'>User</a>
        <a href='admincartview.php'>cart</a>
        <a href='adminorderview.php'>order</a>
        <form action="logout.php" method="post" style="display:inline; margin-left: auto;">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </div>
  
</body>

</html>