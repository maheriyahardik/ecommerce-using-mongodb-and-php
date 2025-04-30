<?php
require 'db.php';

// Fetch products from MongoDB
$products = $db->products->find();
$categories = $db->categories->find();
$subcategories = $db->subcategories->find();

// Convert categories and subcategories into an associative array for display
$categoryMap = [];
foreach ($categories as $category) {
    $categoryMap[(string)$category['_id']] = $category['name'];
}

$subcategoryMap = [];
foreach ($subcategories as $subcategory) {
    $subcategoryMap[(string)$subcategory['_id']] = $subcategory['name'];
}

// Handle Product Deletion
if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];
    $db->products->deleteOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);
    header("Location: viewproduct.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 { color: #333; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #007BFF;
            color: white;
        }
        .delete-btn {
            background: #dc3545;
            padding: 5px 10px;
            border-radius: 5px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .delete-btn:hover { background: #c82333; }
        .edit-btn {
            background: #ffc107;
            padding: 5px 10px;
            border-radius: 5px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .edit-btn:hover { background: #e0a800; }
        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .back-btn {
            margin-top: 20px;
            display: inline-block;
            background: #007BFF;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Product List</h2>
        <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td>
                        <?php if (!empty($product['image'])) { ?>
                            <img src="<?php echo $product['image']; ?>" alt="Product Image">
                        <?php } else { ?>
                            No Image
                        <?php } ?>
                    </td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $categoryMap[(string)$product['category_id']] ?? 'N/A'; ?></td>
                    <td><?php echo $subcategoryMap[(string)$product['subcategory_id']] ?? 'N/A'; ?></td>
                    <td><?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td>
                        <a href="editproduct.php?id=<?php echo $product['_id']; ?>" class="edit-btn">Edit</a>
                        <a href="viewproduct.php?delete=<?php echo $product['_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <a href="products.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
