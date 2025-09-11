<?php
require_once 'config/session.php';
require_once 'models/Product.php';

SessionManager::requireLogin();

$error = '';
$product = new Product();

// Get product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product->id = $_GET['id'];
if (!$product->readOne()) {
    header('Location: products.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    
    // Handle image upload
    $image_name = $product->image; // Keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            // Delete old image if exists
            if ($product->image && file_exists($upload_dir . $product->image)) {
                unlink($upload_dir . $product->image);
            }
            
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
            $product->name = $name;
            $product->description = $description;
            $product->price = $price;
            $product->stock = $stock;
            $product->image = $image_name;
            
            if ($product->update()) {
                header('Location: products.php?success=updated');
                exit();
            } else {
                $error = 'Failed to update product.';
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
    <title>Edit Product - Mini Platform</title>
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
            <h2>Edit Product</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($product->name); ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($product->description); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price ($):</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required 
                               value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : htmlspecialchars($product->price); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock Quantity:</label>
                        <input type="number" id="stock" name="stock" min="0" required 
                               value="<?php echo isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : htmlspecialchars($product->stock); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image:</label>
                    <?php if ($product->image): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="uploads/<?php echo htmlspecialchars($product->image); ?>" 
                                 alt="Current Image" style="max-width: 200px; height: auto; border-radius: 4px;">
                            <p style="color: #7f8c8d; font-size: 14px;">Current image (upload new to replace)</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" accept="image/*">
                    <small style="color: #7f8c8d;">Supported formats: JPG, PNG, GIF</small>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-warning">Update Product</button>
                    <a href="products.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
