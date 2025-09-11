<?php
require_once 'config/session.php';
require_once 'models/Product.php';

SessionManager::requireLogin();

$product = new Product();
$stmt = $product->readAll();
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Mini Platform</title>
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
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php 
                switch($success) {
                    case 'created': echo 'Product created successfully!'; break;
                    case 'updated': echo 'Product updated successfully!'; break;
                    case 'deleted': echo 'Product deleted successfully!'; break;
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="products-header">
            <h1>Product Management</h1>
            <a href="add_product.php" class="btn btn-success">Add New Product</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td>
                            <?php if ($row['image']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                                     alt="Product Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999;">No Image</div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars(substr($row['description'], 0, 50)) . (strlen($row['description']) > 50 ? '...' : ''); ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <span style="color: <?php echo $row['stock'] < 5 ? '#e74c3c' : '#27ae60'; ?>">
                                <?php echo $row['stock']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning" style="margin-right: 5px;">Edit</a>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
