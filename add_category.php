<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = trim($_POST["name"]);

    if (!empty($categoryName)) {
        $insertResult = $db->categories->insertOne(["name" => $categoryName]);

        if ($insertResult->getInsertedCount() > 0) {
            echo "<script>alert('Category added successfully!'); window.location.href='categories.php';</script>";
        } else {
            echo "<script>alert('Failed to add category!');</script>";
        }
    } else {
        echo "<script>alert('Category name cannot be empty!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Custom CSS -->
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Add New Category</h2>

        <form method="POST" action="add_category.php">
            <div class="mb-3">
                <label for="categoryName" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="categoryName" name="name" placeholder="Enter category name" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Add Category</button>
        </form>

        <div class="text-center mt-3">
            <a href="categories.php" class="btn btn-secondary">Back to Categories</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
