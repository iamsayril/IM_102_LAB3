<?php
require_once 'auth.php';
require_once 'config.php';
requireLogin();
requireAdmin();

$result = $conn->query("
    SELECT u.id, u.username, u.email, u.role, u.created_at,
           COUNT(p.id) AS product_count
    FROM users u
    LEFT JOIN products p ON u.id = p.added_by
    GROUP BY u.id
    ORDER BY u.id ASC
");
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>User Management</h1>

        <div class="filters" style="justify-content: flex-end; margin-bottom: 16px;">
            <a href="add_user.php">
                <button type="button" class="btn-add">Add User</button>
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="text-align:center;">ID</th>
                    <th style="text-align:center;">Username</th>
                    <th style="text-align:center;">Email</th>
                    <th style="text-align:center;">Role</th>
                    <th style="text-align:center;">Products Added</th>
                    <th style="text-align:center;">Created At</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; color:#888; padding: 24px;">
                            No users found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="text-align:center; vertical-align:middle;"><?= $row['id'] ?></td>
                            <td style="text-align:center; vertical-align:middle;"><?= htmlspecialchars($row['username']) ?></td>
                            <td style="text-align:center; vertical-align:middle;"><?= htmlspecialchars($row['email']) ?></td>
                            <td style="text-align:center; vertical-align:middle;">
                                <span style="
                                background: <?= $row['role'] === 'admin' ? '#2f5f4d' : '#e7f3ed' ?>;
                                color: <?= $row['role'] === 'admin' ? 'white' : '#2f5f4d' ?>;
                                font-size: 0.78rem;
                                font-weight: 700;
                                padding: 3px 10px;
                                border-radius: 20px;
                                text-transform: uppercase;
                                letter-spacing: 0.05em;
                                border: 1px solid <?= $row['role'] === 'admin' ? '#1e3f33' : '#b2d9c4' ?>;
                            "><?= htmlspecialchars($row['role']) ?></span>
                            </td>
                            <td style="text-align:center; vertical-align:middle;">
                                <?= $row['product_count'] ?>
                            </td>
                            <td style="text-align:center; vertical-align:middle;"><?= $row['created_at'] ?></td>
                            <td class="actions" style="text-align:center; vertical-align:middle; white-space:nowrap;">
                                <div style="display:flex; justify-content:center; align-items:center; gap:8px;">
                                    <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                                    <?php if ((int) $row['id'] !== (int) $_SESSION['user_id']): ?>
                                        <a href="delete_user.php?id=<?= $row['id'] ?>"
                                            onclick="return confirm('Delete user <?= htmlspecialchars(addslashes($row['username'])) ?>?')"
                                            class="btn-delete">Delete</a>
                                    <?php else: ?>
                                        <span
                                            style="color:#bbb; font-size:0.82rem; padding: 8px 6px; display:inline-block;">You</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <p class="count">Showing <?= $result->num_rows ?> user(s)</p>
    </div>

</body>

</html>