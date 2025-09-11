<?php
require_once 'config/session.php';
require_once 'models/Product.php';

SessionManager::requireLogin();

// Get product statistics
$product = new Product();
$stmt = $product->readAll();
$total_products = $stmt->rowCount();

$total_value = 0;
$low_stock_count = 0;
$stmt->execute(); // Re-execute to get data
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $total_value += $row['price'] * $row['stock'];
    if ($row['stock'] < 5) {
        $low_stock_count++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mini Platform</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">Mini Platform</div>
            <nav class="nav">
                <a href="dashboard.php">Dashboard</a>
                <a href="products.php">Products</a>
                <a href="logout.php">Logout (<?php echo htmlspecialchars(SessionManager::getEmail()); ?>)</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="dashboard">
            <h1>Welcome to the Dashboard</h1>
            <p>Hello, <?php echo htmlspecialchars(SessionManager::getEmail()); ?>! Welcome to your mini platform back-office.</p>
            
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_products; ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">$<?php echo number_format($total_value, 2); ?></div>
                    <div class="stat-label">Inventory Value</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $low_stock_count; ?></div>
                    <div class="stat-label">Low Stock Items</div>
                </div>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="products.php" class="btn btn-primary">Manage Products</a>
                <a href="add_product.php" class="btn btn-success">Add New Product</a>
            </div>
        </div>
    </div>
</body>
</html>
