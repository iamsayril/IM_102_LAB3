<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<div class="container">
<nav class="navbar">
    <div class="navbar-links">
        <a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">Products</a>
        <a href="add.php"   class="<?= $current === 'add.php'   ? 'active' : '' ?>">Add Product</a>
        <a href="report.php" class="<?= $current === 'report.php' ? 'active' : '' ?>">Reports</a>
        <a href="register.php" class="<?= $current === 'register.php' ? 'active' : '' ?>">Register</a>
        <a href="logout.php">Logout (<?= htmlspecialchars(getUsername()) ?>)</a>
    </div>
</nav>