<?php
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);

// Handle the actual delete (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM products WHERE id = $id");
    header('Location: index.php');
    exit;
}

// Show confirmation (GET)
$result = $conn->query("SELECT name, description, price, stock, category_id, supplier_id FROM products WHERE id = $id");
$products = $result->fetch_assoc();


if (!$products) {
    die("Product not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Product</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { font-family:Arial; max-width: 450px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .warning { color: #f44336; font-size: 1.1em; margin: 20px 0; }
        .name { font-size: 1.3em; font-weight: bold; color: #333; }
        .details { color: #666; margin: 10px 0; }
        .btn-delete { padding: 12px 30px; background: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .btn-delete:hover { background: #d32f2f; }
        .btn-cancel { display: inline-block; padding: 12px 30px; background: #9e9e9e; color: white; text-decoration: none; border-radius: 4px; margin-left: 10px; }
        .name { text-align: center; font-family: cursive; color: #000000; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Product</h1>
        
        <p>Are you sure you want to delete:</p>
        <p class="name"><?= htmlspecialchars($products['name']) ?></p>
        <p class="description">Description: <?= $products['description'] ?></p>
        <p class="price">Price: ₱ <?= $products['price'] ?></p>
        <p class="stock">Stock: <?= $products['stock'] ?></p>
        <p class="warning">This action cannot be undone.</p>
        
        <form method="POST" style="display: inline;">
            <button type="submit" class="btn-delete">Yes, Delete</button>
        </form>
        <a href="index.php" class="btn-cancel">Cancel</a>
    </div>
</body>
</html>