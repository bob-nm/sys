<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../index.php");  // Redirect to login if not a client
    exit();
}

require '../includes/db.php';
require '../includes/currency.php';

// Fetch the products and their prices
$query = $conn->prepare("SELECT * FROM products");
$query->execute();
$result = $query->get_result();

echo "<h2>Client Dashboard</h2>";
echo "<h3>Product Prices:</h3>";
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Base Price</th><th>Final Price (Your Currency)</th></tr>";

while ($product = $result->fetch_assoc()) {
    // Assume USD as the base currency and convert to the client's local currency
    $basePrice = $product['base_price'];
    $currencyRate = getExchangeRate('USD', 'EUR');  // Change EUR to client currency
    $finalPrice = calculatePrice($basePrice, $currencyRate, 20);  // Example 20% markup

    echo "<tr>";
    echo "<td>{$product['name']}</td>";
    echo "<td>{$basePrice} USD</td>";
    echo "<td>{$finalPrice} EUR</td>";  // Adjust for client's currency
    echo "</tr>";
}

echo "</table>";
?>

<a href="../logout.php">Logout</a>
