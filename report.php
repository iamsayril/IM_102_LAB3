<?php
require_once 'auth.php';
require_once 'config.php';
requireLogin();

$summary = $conn->query("
    SELECT
        COUNT(id)            AS total_products,
        SUM(stock)           AS total_stock,
        SUM(price * stock)   AS total_value,
        AVG(price)           AS avg_price
    FROM products
")->fetch_assoc();

$by_category = $conn->query("
    SELECT
        c.name                               AS category,
        COUNT(p.id)                          AS product_count,
        COALESCE(SUM(p.stock), 0)            AS total_stock,
        COALESCE(SUM(p.price * p.stock), 0)  AS total_value,
        COALESCE(AVG(p.price), 0)            AS avg_price
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    GROUP BY c.id, c.name
    ORDER BY total_value DESC
");

$by_supplier = $conn->query("
    SELECT
        s.name                      AS supplier,
        COUNT(p.id)                 AS product_count,
        COALESCE(SUM(p.stock), 0)   AS total_stock
    FROM suppliers s
    LEFT JOIN products p ON s.id = p.supplier_id
    GROUP BY s.id, s.name
    ORDER BY product_count DESC
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container">

        <div class="nav">
            <a href="index.php">&#8592; Back to Products</a>
        </div>

        <h1>Inventory Report</h1>

        <div class="report-cards">
            <div class="report-card">
                <div class="report-card-value"><?= number_format($summary['total_products']) ?></div>
                <div class="report-card-label">Total Products</div>
            </div>
            <div class="report-card">
                <div class="report-card-value"><?= number_format($summary['total_stock']) ?></div>
                <div class="report-card-label">Total Stock</div>
            </div>
            <div class="report-card">
                <div class="report-card-value">₱<?= number_format($summary['total_value'], 2) ?></div>
                <div class="report-card-label">Inventory Value</div>
            </div>
            <div class="report-card">
                <div class="report-card-value">₱<?= number_format($summary['avg_price'], 2) ?></div>
                <div class="report-card-label">Average Price</div>
            </div>
        </div>

        <div class="report-section">
            <h2 class="report-heading">By Category</h2>
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="num">Products</th>
                        <th class="num">Total Stock</th>
                        <th class="num">Total Value</th>
                        <th class="num">Avg Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $by_category->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td class="num <?= $row['product_count'] == 0 ? 'muted' : '' ?>">
                                <?= $row['product_count'] ?>
                            </td>
                            <td class="num"><?= number_format($row['total_stock']) ?></td>
                            <td class="num">₱<?= number_format($row['total_value'], 2) ?></td>
                            <td class="num">
                                <?= $row['product_count'] > 0
                                    ? '₱' . number_format($row['avg_price'], 2)
                                    : '<span class="muted">—</span>' ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <p class="count"><?= $by_category->num_rows ?> categories</p>
        </div>

        <div class="report-section">
            <h2 class="report-heading">By Supplier</h2>
            <table>
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th class="num">Products</th>
                        <th class="num">Total Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $by_supplier->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['supplier']) ?></td>
                            <td class="num <?= $row['product_count'] == 0 ? 'muted' : '' ?>">
                                <?= $row['product_count'] ?>
                            </td>
                            <td class="num"><?= number_format($row['total_stock']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <p class="count"><?= $by_supplier->num_rows ?> suppliers</p>
        </div>

    </div>
</body>

</html>