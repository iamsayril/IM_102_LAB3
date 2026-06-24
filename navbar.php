<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<div class="container">
<nav class="navbar">
    <div class="navbar-links">
        <a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">Products</a>
        <a href="report.php" class="<?= $current === 'report.php' ? 'active' : '' ?>">Reports</a>
        <?php if (isAdmin()): ?>
            <a href="add.php" class="<?= $current === 'add.php' ? 'active' : '' ?>">Add</a>
            <a href="users.php" class="<?= $current === 'users.php' ? 'active' : '' ?>">Users</a>
        <?php endif; ?>
        <span style="margin-left:auto; display:flex; align-items:center; gap:10px;">
            <span style="font-size:0.85rem; color:#ffffff; font-weight:600;">
                <?= htmlspecialchars(getUsername()) ?> 
                <span style="
                    background: #f59e0b;
                    color: #1e3f33;
                    font-size: 0.75rem;
                    padding: 2px 8px;
                    border-radius: 20px;
                    font-weight: 700;
                    margin-left: 4px;
                "><?= htmlspecialchars($_SESSION['role'] ?? '') ?></span>
            </span>
            <a href="logout.php">Logout</a>
        </span>
    </div>
</nav>