<?php
include 'uheader.php';
require 'db.php'; // Ensure MongoDB connection

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch only the logged-in user's orders
$orders = $ordersCollection->find(['username' => $username]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
        }
        h2 {
            color: #333;
            font-size: 26px;
            margin-bottom: 20px;
        }
        .order-box {
            padding: 20px;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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
        .order-details {
            display: none;
            margin-top: 10px;
        }
        .toggle-btn {
            cursor: pointer;
            color: #007BFF;
            text-decoration: underline;
        }
        .toggle-btn:hover {
            color: #0056b3;
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
    <script>
        function toggleDetails(orderId) {
            var details = document.getElementById("details-" + orderId);
            if (details.style.display === "none" || details.style.display === "") {
                details.style.display = "block";
            } else {
                details.style.display = "none";
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h2>My Orders</h2>
    <?php 
    $hasOrders = false;
    foreach ($orders as $order): 
        $hasOrders = true;
        $orderId = (string) $order['_id']; // Keeping for internal use
    ?>
        <div class="order-box">
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            <p><strong>Total Amount:</strong> <?php echo number_format($order['total_amount'], 2); ?></p>
            <p class="toggle-btn" onclick="toggleDetails('<?php echo $orderId; ?>')">View Order Items</p>

            <div class="order-details" id="details-<?php echo $orderId; ?>">
                <h3>Order Items</h3>
                <table class="order-table">
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo (int) $item['quantity']; ?></td>
                            <td><?php echo number_format((float) $item['price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (!$hasOrders): ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <a href="home.php" class="home-btn">Back to Home</a>
</div>

</body>
</html>
