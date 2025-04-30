<?php
include 'uheader.php';
require 'db.php'; // Ensure MongoDB connection

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch cart items for the logged-in user
$cartItems = $cartCollection->find(['username' => $username]);

$totalAmount = 0;
$orderItems = [];

foreach ($cartItems as $item) {
    $orderItems[] = [
        'product_id' => $item['product_id'],
        'product_name' => $item['product_name'],
        'price' => $item['price'],
        'quantity' => $item['quantity'],
        'subtotal' => $item['price'] * $item['quantity']
    ];
    $totalAmount += $item['price'] * $item['quantity'];
}

// If the cart is empty, redirect to the cart page
if (empty($orderItems)) {
    header("Location: cart.php");
    exit();
}

// Fetch user details (Ensure 'users' collection exists)
$user = $usersCollection->findOne(['username' => $username]);

if ($user && isset($user['username'])) {
    $buyerName = $user['username']; // Assuming 'username' is the field for the user's name
} else {
    $buyerName = "Guest"; // Default name if not found
    error_log("Warning: User record not found or missing 'name' field for username: " . $username);
}

// Create order document with buyer's name
$orderData = [
    'buyer_name' => $buyerName,
    'username' => $username,
    'items' => $orderItems,
    'total_amount' => $totalAmount,
    'status' => 'Pending',
    'created_at' => new MongoDB\BSON\UTCDateTime()
];

// Insert order into 'orders' collection
$ordersCollection->insertOne($orderData);

// Clear the cart after checkout
$cartCollection->deleteMany(['username' => $username]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Success</title>
    <style>
        .custom-body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
        }
        h2 {
            color: #333;
            font-size: 24px;
        }
        .success-message {
            font-size: 20px;
            color: #28a745;
            font-weight: bold;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .total-amount {
            font-size: 22px;
            font-weight: bold;
            color: #28a745;
            margin-top: 15px;
        }
        .home-btn {
            display: inline-block;
            margin-top: 20px;
            background: #007BFF;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }
        .home-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body class="custom-body">


<div class="container">
    <h2>Checkout Successful</h2>
    <p class="success-message">Thank you for your purchase, <?php echo htmlspecialchars($buyerName); ?>!</p>

    <div class="order-details">
        <h3>Order Summary</h3>
        <table class="order-table">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p class="total-amount">Total Amount:<?php echo number_format($totalAmount, 2); ?></p>
    </div>

    <a href="home.php" class="home-btn">Continue Shopping</a>
</div>

</body>
</html>
