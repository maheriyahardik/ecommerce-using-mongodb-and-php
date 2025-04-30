<?php
require 'db.php';
session_start();

// Ensure product ID is provided
if (!isset($_GET['id'])) {
    header("Location: viewproduct.php");
    exit();
}

$productId = $_GET['id'];
$product = $db->products->findOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);

if (!$product) {
    echo "Product not found!";
    exit();
}

// Fetch categories and subcategories
$categories = $db->categories->find();
$subcategories = $db->subcategories->find();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allowed file types
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = $targetFilePath; // Store the image path
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Invalid file format. Only JPG, JPEG, PNG & GIF are allowed.";
            exit();
        }
    } else {
        $imagePath = $product['image']; // Keep existing image if no new one is uploaded
    }

    // Update product data
    $updateData = [
        'name' => $name,
        'category_id' => new MongoDB\BSON\ObjectId($category_id),
        'subcategory_id' => new MongoDB\BSON\ObjectId($subcategory_id),
        'price' => $price,
        'stock' => $stock,
        'image' => $imagePath
    ];

    $db->products->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($productId)],
        ['$set' => $updateData]
    );

    header("Location: viewproduct.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 { color: #333; }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background: #218838; }
        .back-btn {
            display: inline-block;
            margin-top: 10px;
            background: #007BFF;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover { background: #0056b3; }
        img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
            
            <select name="category_id" required>
                <?php foreach ($categories as $category) { 
                    $selected = ($category['_id'] == $product['category_id']) ? 'selected' : '';
                    echo "<option value='{$category['_id']}' $selected>{$category['name']}</option>";
                } ?>
            </select>

            <select name="subcategory_id" required>
                <?php foreach ($subcategories as $subcategory) { 
                    $selected = ($subcategory['_id'] == $product['subcategory_id']) ? 'selected' : '';
                    echo "<option value='{$subcategory['_id']}' $selected>{$subcategory['name']}</option>";
                } ?>
            </select>

            <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>
            <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

            <label>Current Image:</label>
            <br>
            <?php if (!empty($product['image'])) { ?>
                <img src="<?php echo $product['image']; ?>" alt="Product Image">
            <?php } else { ?>
                No Image
            <?php } ?>

            <input type="file" name="image">

            <button type="submit">Update Product</button>
        </form>
        <a href="viewproduct.php" class="back-btn">Cancel</a>
    </div>
</body>
</html>
