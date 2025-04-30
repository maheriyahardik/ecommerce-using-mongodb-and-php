<?php
include 'header.php'; // Admin header file
require 'db.php'; // Ensure MongoDB connection

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['status'];

    $ordersCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($orderId)],
        ['$set' => ['status' => $newStatus]]
    );

    header("Location: adminorderview.php"); // Redirect to refresh the page
    exit();
}

// Fetch all orders
$orders = $ordersCollection->find([]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Orders</title>
    <style>
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1000px;
            margin: 20px auto;
        }
        h2 {
            color: #333;
            font-size: 26px;
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
    </style>
</head>
<body>
<div class="container">
    <h2>All Orders</h2>
    <table class="order-table">
        <tr>
            <th>Username</th>
            <th>Status</th>
            <th>Total Amount</th>
            <th>Update Status</th>
        </tr>
        <?php 
        foreach ($orders as $order): 
            $orderId = (string) $order['_id'];
        ?>
        <tr>
            <td><?php echo htmlspecialchars($order['username']); ?></td>
            <td><?php echo htmlspecialchars($order['status']); ?></td>
            <td><?php echo number_format($order['total_amount'], 2); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
                    <select name="status">
                        <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Processing" <?php echo ($order['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                        <option value="Completed" <?php echo ($order['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="Cancelled" <?php echo ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </td>
            </tr>
        <tr class="order-details" id="details-<?php echo $orderId; ?>">
            <td colspan="5">
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
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>  
</body>
</html>
