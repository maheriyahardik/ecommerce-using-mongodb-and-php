<?php
require 'db.php';

if (!isset($_GET['id'])) {
    die("Invalid product ID.");
}

$productId = new MongoDB\BSON\ObjectId($_GET['id']);
$product = $db->products->findOne(['_id' => $productId]);

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }
        h2 {
            color: #333;
        }
        .price {
            color: #007BFF;
            font-size: 18px;
            margin: 10px 0;
        }
        .description {
            font-size: 16px;
            color: #555;
        }
        .home-btn {
            display: inline-block;
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p class="price">Price: ₹<?php echo number_format($product['price'], 2); ?></p>
        <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
        <a href="search.php" class="home-btn">Back to Search</a>
    </div>
</body>
</html>
