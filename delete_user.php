<?php
require_once 'auth.php';
require_once 'config.php';
requireLogin();
requireAdmin();

$id = (int)($_GET['id'] ?? 0);

// Prevent admin from deleting themselves
if ($id === (int)$_SESSION['user_id']) {
    die("You cannot delete your own account.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM users WHERE id = $id");
    header('Location: users.php');
    exit;
}

$result = $conn->query("SELECT id, username, email, role, created_at FROM users WHERE id = $id");
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container form-page">

    <h1>Delete User</h1>

    <div class="error-box">
        <p>Are you sure you want to delete <strong><?= htmlspecialchars($user['username']) ?></strong>?</p>
        <p>This action cannot be undone.</p>
    </div>

    <table>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        <tr>
            <td><strong>ID</strong></td>
            <td><?= $user['id'] ?></td>
        </tr>
        <tr>
            <td><strong>Username</strong></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
        </tr>
        <tr>
            <td><strong>Email</strong></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
        </tr>
        <tr>
            <td><strong>Role</strong></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
        </tr>
        <tr>
            <td><strong>Created At</strong></td>
            <td><?= $user['created_at'] ?></td>
        </tr>
    </table>

    <form method="POST" style="margin-top: 20px;">
        <button type="submit" style="
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
        <a href="users.php" class="cancel">Cancel</a>
    </form>

</div>

</body>
</html>