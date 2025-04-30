<?php include 'uheader.php'; ?>
<?php
require 'db.php'; // Ensure this file properly connects to MongoDB

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch products from the 'products' collection
$products = $productsCollection->find();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic Store - Home</title>
    <style>
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #f8f9fa;
    color: #333;
    text-align: center;
}

/* Header */
.header {
    background: linear-gradient(135deg, #0056b3, #002d72);
    color: white;
    padding: 20px;
    font-size: 28px;
    font-weight: 700;
    text-transform: uppercase;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #002f6c;
    padding: 15px;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 12px 20px;
    margin: 0 15px;
    font-size: 18px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
}

.navbar a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
}

/* Container */
.container {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 1200px;
    margin: 50px auto;
}

/* Product Grid */
.products {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-top: 20px;
    padding: 20px;
}

/* Product Card */
.product {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease-in-out;
    position: relative;
}

.product:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

/* Product Image */
.product img {
    width: 100%;
    max-width: 220px;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
    background: #f4f4f4;
    margin-bottom: 15px;
}

/* Product Name */
.product h3 {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

/* Product Description */
.product p {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
}

/* Product Price */
.price {
    font-size: 18px;
    color: #007BFF;
    font-weight: bold;
    margin: 10px 0;
}

/* Stock Status */
.stock {
    font-size: 14px;
    color: #28a745;
    font-weight: bold;
}

.out-of-stock {
    color: red;
    font-weight: bold;
}

/* Add to Cart Button */
.add-to-cart {
    display: inline-block;
    background: linear-gradient(135deg, #28a745, #218838);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    transition: all 0.3s ease-in-out;
    text-decoration: none;
}

.add-to-cart:hover {
    background: linear-gradient(135deg, #218838, #19692c);
    transform: translateY(-3px);
}

.add-to-cart:disabled {
    background: gray;
    cursor: not-allowed;
}

/* Responsive Design */
@media (max-width: 768px) {
    .products {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
}

    </style>
</head>
<body>



    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>Explore our latest electronic products and enjoy shopping with us!</p>
    </div>

    <div class="container">
        <h2>Our Products</h2>
        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                <td>
                        <?php if (!empty($product['image'])) { ?>
                            <img src="<?php echo $product['image']; ?>" alt="Product Image">
                        <?php } else { ?>
                            No Image
                        <?php } ?>
                    </td>

                    <h3><?php echo htmlspecialchars($product['name'] ?? 'Unknown Product'); ?></h3>
                    <p><?php echo htmlspecialchars($product['description'] ?? 'No description available'); ?></p>
                    <p class="price"><?php echo number_format($product['price'] ?? 0, 2); ?></p>
                    <p class="<?php echo ($product['stock'] ?? 0) > 0 ? 'stock' : 'out-of-stock'; ?>">
                        Stock: <?php echo htmlspecialchars($product['stock'] ?? 'Out of stock'); ?>
                    </p>
                    <?php if (($product['stock'] ?? 0) > 0): ?>
                        <a href="addtocart.php?id=<?php echo $product['_id']; ?>" class="add-to-cart">Add to Cart</a>
                    <?php else: ?>
                        <button class="add-to-cart" disabled>Out of Stock</button>
                    <?php endif; ?>
                       </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>                     