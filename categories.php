<?php
require 'db.php';

// Handle category deletion
if (isset($_GET['delete_id'])) {
    $deleteId = new MongoDB\BSON\ObjectId($_GET['delete_id']);
    $db->categories->deleteOne(['_id' => $deleteId]);
    header("Location: categories.php"); // Redirect to prevent form resubmission
    exit;
}

// Handle category update
if (isset($_POST['update_id']) && isset($_POST['new_name'])) {
    $updateId = new MongoDB\BSON\ObjectId($_POST['update_id']);
    $newName = $_POST['new_name'];
    $db->categories->updateOne(['_id' => $updateId], ['$set' => ['name' => $newName]]);
    header("Location: categories.php"); // Redirect to prevent form resubmission
    exit;
}

// Fetch categories
$categories = $db->categories->find();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
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
        input[type="text"] {
            padding: 10px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 5px;
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
        a {
            text-decoration: none;
            color: white;
            background: #dc3545;
            padding: 5px 10px;
            border-radius: 5px;
        }
        a:hover {
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
            outline: none;
            border: none;
        }
        .home-btn:hover {
            background: rgb(136, 33, 43);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Category Management</h2>
        <form action="add_category.php" method="post">
            <input type="text" name="name" placeholder="Category Name" required>
            <button type="submit">Add</button>
        </form>
        <ul>
            <?php foreach ($categories as $category) { ?>
                <li>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="update_id" value="<?php echo $category['_id']; ?>">
                        <input type="text" name="new_name" value="<?php echo $category['name']; ?>" required>
                        <button type="submit">Update</button>
                    </form>
                    <a href="?delete_id=<?php echo $category['_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </li>
            <?php } ?>
        </ul>
        <a class="home-btn" href="categories.php">Back to Home</a>
    </div>
</body>
</html>