<?php
require 'db.php';

$uploadDir = "uploads/"; // Directory for image storage

// Fetch data from MongoDB
$categories = $db->categories->find();
$subcategories = $db->subcategories->find();
$products = $db->products->find();

// Convert subcategories into a JSON format for JavaScript usage
$subcategoryData = [];
foreach ($subcategories as $subcategory) {
    $subcategoryData[(string) $subcategory['category_id']][] = [
        'id' => (string) $subcategory['_id'],
        'name' => $subcategory['name']
    ];
}
$subcategoryJson = json_encode($subcategoryData);

// Handle Product Insertion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $price = floatval($_POST['price']);
    $description = $_POST['description'];
    $stock = intval($_POST['stock']);
    
    // Handle Image Upload
    $imagePath = "";
    if (!empty($_FILES["image"]["name"])) {
        $imageName = basename($_FILES["image"]["name"]);
        $imagePath = $uploadDir . time() . "_" . $imageName; // Unique filename
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
    }

    if (!empty($name) && !empty($category_id) && !empty($subcategory_id) && $price >= 0 && $stock >= 0) {
        $db->products->insertOne([
            'name' => $name,
            'category_id' => new MongoDB\BSON\ObjectId($category_id),
            'subcategory_id' => new MongoDB\BSON\ObjectId($subcategory_id),
            'price' => $price,
            'description' => $description,
            'stock' => $stock,
            'image' => $imagePath
        ]);
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
    <title>Product Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 { color: #333; }
        form { margin-bottom: 20px; }
        input, select, textarea {
            padding: 10px;
            width: 90%;
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
        button:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: left; }
        th { background: #007BFF; color: white; }
        .delete-btn {
            background: #dc3545; padding: 5px 10px; border-radius: 5px; border: none; color: white; cursor: pointer;
        }
        .delete-btn:hover { background: #c82333; }
        .edit-btn {
            background: #ffc107; padding: 5px 10px; border-radius: 5px; border: none; color: white; cursor: pointer;
        }
        .edit-btn:hover { background: #e0a800; }
        .view-btn {
            background: #28a745; padding: 5px 10px; border-radius: 5px; border: none; color: white; cursor: pointer;
        }
        .view-btn:hover { background: #218838; }
        img { width: 80px; height: 80px; object-fit: cover; border-radius: 5px; }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let subcategoryData = <?php echo $subcategoryJson; ?>;
            let categorySelect = document.getElementById("category");
            let subcategorySelect = document.getElementById("subcategory");

            categorySelect.addEventListener("change", function() {
                let selectedCategory = this.value;
                subcategorySelect.innerHTML = "<option value=''>Select Subcategory</option>";
                
                if (subcategoryData[selectedCategory]) {
                    subcategoryData[selectedCategory].forEach(sub => {
                        let option = document.createElement("option");
                        option.value = sub.id;
                        option.textContent = sub.name;
                        subcategorySelect.appendChild(option);
                    });
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Product Management</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required>
            <select name="category_id" id="category" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category) {
                    echo "<option value='{$category['_id']}'>{$category['name']}</option>";
                } ?>
            </select>
            <select name="subcategory_id" id="subcategory" required>
                <option value="">Select Subcategory</option>
            </select>
            <input type="number" name="price" step="0.01" placeholder="Price" required>
            <textarea name="description" placeholder="Product Description" required></textarea>
            <input type="file" name="image">
            <button type="submit" name="add_product">Add Product</button>
            <button type="button" class="view-btn" onclick="window.location.href='viewproduct.php'">View Products</button>
            <?php include 'viewproduct.php'; ?>
        </form>
    </div>
</body>
</html>
