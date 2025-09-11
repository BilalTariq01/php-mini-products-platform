<?php
require_once 'config/session.php';
require_once 'models/Product.php';

SessionManager::requireLogin();

// Get product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product = new Product();
$product->id = $_GET['id'];

// Get product details to delete image file
if ($product->readOne()) {
    // Delete image file if exists
    if ($product->image && file_exists('uploads/' . $product->image)) {
        unlink('uploads/' . $product->image);
    }
    
    // Delete product from database
    if ($product->delete()) {
        header('Location: products.php?success=deleted');
    } else {
        header('Location: products.php?error=delete_failed');
    }
} else {
    header('Location: products.php');
}
exit();
?>
