<?php include 'uheader.php'; ?>
<?php
require 'db.php'; // Ensure this file properly connects to MongoDB

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Handle item removal
if (isset($_GET['remove_id'])) {
    $removeId = $_GET['remove_id'];
    $cartCollection->deleteOne([
        '_id' => new MongoDB\BSON\ObjectId($removeId),
        'username' => $username
    ]);
    header("Location: cart.php"); // Refresh the cart after removal
    exit();
}

// Fetch cart items for the logged-in user
$cartItems = $cartCollection->find(['username' => $username]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .remove-btn {
            background: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }
        .checkout-btn {
            display: inline-block;
            margin-top: 20px;
            background: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            font-size: 18px;
            text-decoration: none;
            transition: 0.3s ease-in-out;
        }
        .checkout-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Shopping Cart</h2>
    <?php if ($cartItems->isDead()): ?>
        <p>Your cart is empty!</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td>
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" width="50" height="50">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>
                        <a href="cart.php?remove_id=<?php echo $item['_id']; ?>" class="remove-btn" onclick="return confirm('Are you sure you want to remove this item?');">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    <?php endif; ?>
</div>

</body>
</html>
