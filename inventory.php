<?php
require 'db.php';

// Fetch products and convert cursor to an array to prevent iteration issues
$products = iterator_to_array($db->products->find());

// Handle Add or Update Stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $product_id = $_POST['product_id'];
    $stock = (int)$_POST['stock'];

    if (!empty($product_id)) {
        $db->products->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($product_id)],
            ['$set' => ['stock' => $stock]]
        );
    }
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

// Handle Delete Stock (Set to 0)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_stock'])) {
    $product_id = $_POST['product_id'];

    if (!empty($product_id)) {
        $db->products->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($product_id)],
            ['$set' => ['stock' => 0]]
        );
    }
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input, select {
            padding: 10px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
        }
        .delete-btn {
            padding: 10px 15px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .home-btn {
            display: inline-block;
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .home-btn:hover {
            background: rgb(136, 33, 43);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Stock Management</h2>
        
        <!-- Add or Update Stock -->
        <form method="post">
            <select name="product_id" required>
                <option value="">Select Product</option>
                <?php foreach ($products as $product) {
                    echo "<option value='{$product['_id']}'>{$product['name']}</option>";
                } ?>
            </select>
            <input type="number" name="stock" placeholder="Stock Quantity" required>
            <button type="submit" name="update_stock">Update Stock</button>
        </form>

        <ul>
            <?php foreach ($products as $product) { ?>
                <li>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['_id']; ?>">
                        <?php echo "{$product['name']} - Stock: "; ?>
                        <input type="number" name="stock" value="<?php echo $product['stock'] ?? 0; ?>" required>
                        <button type="submit" name="update_stock">Update</button>
                    </form>
                    
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['_id']; ?>">
                        <button type="submit" name="delete_stock" class="delete-btn">Set Stock to 0</button>
                    </form>
                </li>
            <?php } ?>
        </ul>
        <a class="home-btn" href="index.php">Back to Home</a>
    </div>
</body>
</html>
