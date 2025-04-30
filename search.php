<?php
require 'db.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';
$suggestions = [];
$recommended = [];

if (!empty($search)) {
    $cursor = $db->products->find(['name' => new MongoDB\BSON\Regex($search, 'i')]);
    $products = iterator_to_array($cursor);

    foreach ($products as $product) {
        $suggestions[] = $product['name'];
    }
} else {
    $cursor = $db->products->find([], ['limit' => 8]);
    $recommended = iterator_to_array($cursor);
}

// Handle AJAX live search
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    echo json_encode($suggestions);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .home-btn {
            display: inline-block;
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .suggestions {
            background: white;
            border: 1px solid #ccc;
            position: absolute;
            max-width: 70%;
            margin: auto;
            text-align: left;
            z-index: 10;
            display: none;
        }
        .suggestions div {
            padding: 10px;
            cursor: pointer;
        }
        .suggestions div:hover {
            background: #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Search Products</h2>
        <form method="get">
            <input type="text" name="q" id="search-box" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>" autocomplete="off">
            <button type="submit">Search</button>
            <div id="suggestions" class="suggestions"></div>
        </form>

        <ul>
            <?php if (!empty($search)): ?>
                <?php foreach ($products as $product): ?>
                    <li>
                        <a href="product.php?id=<?php echo $product['_id']; ?>">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <h3>Recommended Products:</h3>
                <?php foreach ($recommended as $product): ?>
                    <li>
                        <a href="product.php?id=<?php echo $product['_id']; ?>">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <a href="index.php" class="home-btn">Home</a>
    </div>

    <script>
        document.getElementById("search-box").addEventListener("keyup", function() {
            let query = this.value.trim();
            let suggestionsBox = document.getElementById("suggestions");

            if (query.length > 1) {
                fetch(`?q=${query}&ajax=1`)
                    .then(response => response.json())
                    .then(data => {
                        let suggestionsHTML = "";
                        data.forEach(item => {
                            suggestionsHTML += `<div onclick="selectSuggestion('${item}')">${item}</div>`;
                        });
                        suggestionsBox.innerHTML = suggestionsHTML;
                        suggestionsBox.style.display = "block";
                    });
            } else {
                suggestionsBox.style.display = "none";
            }
        });

        function selectSuggestion(value) {
            document.getElementById("search-box").value = value;
            document.getElementById("suggestions").style.display = "none";
        }
    </script>
</body>
</html>
