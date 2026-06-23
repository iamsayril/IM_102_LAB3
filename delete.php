<?php
require_once 'auth.php';
require_once 'config.php';
requireLogin();
requireAdmin();

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM products WHERE id = $id");
    header('Location: index.php');
    exit;
}

$result = $conn->query("SELECT name, description, price, stock FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container form-page">

    <h1>Delete Product</h1>

    <div class="error-box">
        <p>Are you sure you want to delete <strong><?= htmlspecialchars($product['name']) ?></strong>?</p>
        <p>This action cannot be undone.</p>
    </div>

    <table>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td><?= htmlspecialchars($product['description']) ?></td>
        </tr>
        <tr>
            <td><strong>Price</strong></td>
            <td>₱<?= number_format($product['price'], 2) ?></td>
        </tr>
        <tr>
            <td><strong>Stock</strong></td>
            <td><?= $product['stock'] ?></td>
        </tr>
    </table>

    <form method="POST" style="margin-top: 20px;">
        <button type="submit" class="btn-delete" style="
            padding: 10px 20px;
            background: #cc2222;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: 0.2s;
        ">Yes, Delete</button>
        <a href="index.php" class="cancel">Cancel</a>
    </form>

</div>

</body>
</html>