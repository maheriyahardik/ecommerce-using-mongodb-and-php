<?php
require 'db.php';

$subcategories = $db->subcategories->find();
$categories = $db->categories->find();

// Handle Add Subcategory
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subcategory'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];

    $db->subcategories->insertOne([
        'name' => $name,
        'category_id' => new MongoDB\BSON\ObjectId($category_id)
    ]);
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Handle Delete Subcategory
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_subcategory'])) {
    $subcategory_id = $_POST['subcategory_id'];

    if (!empty($subcategory_id)) {
        $db->subcategories->deleteOne(['_id' => new MongoDB\BSON\ObjectId($subcategory_id)]);
    }
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Handle Update Subcategory
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_subcategory'])) {
    $subcategory_id = $_POST['subcategory_id'];
    $updated_name = $_POST['updated_name'];

    if (!empty($subcategory_id) && !empty($updated_name)) {
        $db->subcategories->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($subcategory_id)],
            ['$set' => ['name' => $updated_name]]
        );
    }
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subcategory Management</title>
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
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], select {
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
        }
        .delete-btn, .update-btn {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .update-btn {
            background: #28a745;
        }
        .update-btn:hover {
            background: #218838;
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
        <h2>Subcategory Management</h2>

        <!-- Add Subcategory Form -->
        <form method="post">
            <input type="text" name="name" placeholder="Subcategory Name" required>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category) {
                    echo "<option value='{$category['_id']}'>{$category['name']}</option>";
                } ?>
            </select>
            <button type="submit" name="add_subcategory">Add</button>
        </form>

        <!-- Subcategory List -->
        <ul>
            <?php foreach ($subcategories as $subcategory) { ?>
                <li>
                    <?php echo $subcategory['name']; ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="subcategory_id" value="<?php echo $subcategory['_id']; ?>">
                        <button type="submit" name="delete_subcategory" class="delete-btn">Delete</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="subcategory_id" value="<?php echo $subcategory['_id']; ?>">
                        <input type="text" name="updated_name" placeholder="New Name" required>
                        <button type="submit" name="update_subcategory" class="update-btn">Update</button>
                    </form>
                </li>
            <?php } ?>
        </ul>

        <a class="home-btn" href="index.php">Back to Home</a>
    </div>
</body>
</html>