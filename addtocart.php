<?php
require 'db.php'; // Ensure this file properly connects to MongoDB

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if (isset($_GET['id'])) {
    $productId = new MongoDB\BSON\ObjectId($_GET['id']);

    // Fetch product details
    $product = $productsCollection->findOne(['_id' => $productId]);

    if ($product && ($product['stock'] ?? 0) > 0) {
        // Check if the product is already in the cart
        $existingCartItem = $cartCollection->findOne([
            'username' => $username,
            'product_id' => $productId
        ]);

        if ($existingCartItem) {
            // Increase the quantity in the cart
            $cartCollection->updateOne(
                ['_id' => $existingCartItem['_id']],
                ['$inc' => ['quantity' => 1]]
            );
        } else {
            // Add a new item to the cart
            $cartCollection->insertOne([
                'username' => $username,
                'product_id' => $productId,
                'product_name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'image' => $product['image'] ?? null
            ]);
        }

        // Decrease stock by 1
        $productsCollection->updateOne(
            ['_id' => $productId],
            ['$inc' => ['stock' => -1]]
        );

        header("Location: cart.php"); // Redirect to cart after adding
        exit();
    } else {
        echo "<script>alert('Sorry, this product is out of stock!'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid product!'); window.location.href='index.php';</script>";
    exit();
}
?>
