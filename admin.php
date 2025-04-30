<?php
?>

<!DOCTYPE html>
<html lang="en"><?php include 'header.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        
        .container {
            width: 100%;
            max-width: 1000px;
            background: white;
            padding: 200px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }
        .menu a {
            text-decoration: none;
            background: #007BFF;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            display: block;
            font-size: 18px;
            font-weight: bold;
            width: 220px;
            text-align: center;
            margin: 10px;
            transition: background 0.3s;
        }
        .menu a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Electronic Store Management</h2>
        <div class="menu">
            <a href='categories.php'>Manage Categories</a>
            <a href='subcategories.php'>Manage Subcategories</a>
            <a href='products.php'>Manage Products</a>
            <a href='inventory.php'>Manage Inventory</a>
            <a href='search.php'>Search Products</a>
            <a href='viewusers.php'>Manage User</a>
            <a href='admincartview.php'>Manage all the cart</a>
            <a href='adminorderview.php'>manage all order</a>
        </div>
    </div>
</body>
</html>
