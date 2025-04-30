<?php
include 'header.php'; // Admin header
require 'db.php'; // MongoDB connection

// Handle Delete Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cartid'])) {
    $cartid = new MongoDB\BSON\ObjectId($_POST['cartid']);
    $cartCollection->deleteOne(['_id' => $cartid]);
    // Refresh the page to update the list
    header("Location: admincartview.php");
    exit();
}

// Fetch all cart items from the 'cartid' collection
$cartItems = $cartCollection->find([]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Cart View</title>
    <style>
        body {
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
            max-width: 900px;
            margin: 20px auto;
        }
        h2 {
            color: #333;
            font-size: 24px;
        }
        .cart-table {
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
        img {
            width: 80px;
            height: 80px;
            border-radius: 5px;
        }
        .delete-btn {
            background: red;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Cart View</h2>
    <table class="cart-table">
        <tr>
            <th>Username</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php foreach ($cartItems as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['username']); ?></td>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image"></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="cartid" value="<?php echo $item['_id']; ?>">
                        <button type="submit" class="delete-btn">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
