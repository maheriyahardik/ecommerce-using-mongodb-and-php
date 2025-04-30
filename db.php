<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->ecommerce; // Replace with your actual database name

$usersCollection = $db->selectCollection("users"); // Users collection
$productsCollection = $db->selectCollection("products"); // Products collection
$adminCollection = $db->selectCollection("admin"); // Admin collection
$cartCollection = $db->selectCollection("cart"); 
$ordersCollection = $db->selectCollection("orders");

?>
