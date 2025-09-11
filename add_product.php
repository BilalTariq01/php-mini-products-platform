<?php
require_once 'config/session.php';
require_once 'models/Product.php';

SessionManager::requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    
    // Handle image upload
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $image_name;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $error = 'Failed to upload image.';
            }
        } else {
            $error = 'Invalid image type. Please upload JPG, PNG, or GIF files only.';
        }
    }
    
    if (empty($error)) {
        if (empty($name) || empty($description) || $price <= 0 || $stock < 0) {
            $error = 'Please fill in all fields with valid values.';
        } else {
            $product = new Product();
            $product->name = $name;
            $product->description = $description;
            $product->price = $price;
            $product->stock = $stock;
            $product->image = $image_name;
            
            if ($product->create()) {
                header('Location: products.php?success=created');
                exit();
            } else {
                $error = 'Failed to create product.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Mini Platform</title>
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
        <div class="form-container">
            <h2>Add New Product</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price ($):</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required 
                               value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock Quantity:</label>
                        <input type="number" id="stock" name="stock" min="0" required 
                               value="<?php echo isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <small style="color: #7f8c8d;">Supported formats: JPG, PNG, GIF</small>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success">Add Product</button>
                    <a href="products.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
