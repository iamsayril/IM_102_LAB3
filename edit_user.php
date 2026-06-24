<?php
require_once 'auth.php';
require_once 'config.php';
requireLogin();
requireAdmin();

$id = (int)($_GET['id'] ?? 0);

$result = $conn->query("SELECT id, username, email, role FROM users WHERE id = $id");
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username'] ?? ''));
    $email    = $conn->real_escape_string(trim($_POST['email'] ?? ''));
    $role     = $_POST['role'] ?? 'staff';
    $password = $_POST['password'] ?? '';

    // Validate role value
    if (!in_array($role, ['admin', 'staff'])) {
        $role = 'staff';
    }

    // Prevent admin from changing their own role
    if ($id === (int)$_SESSION['user_id'] && $role !== $_SESSION['role']) {
        $message = '<div class="error-box"><p>You cannot change your own role.</p></div>';
    } elseif (empty($username)) {
        $message = '<div class="error-box"><p>Username is required.</p></div>';
    } elseif (empty($email) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="error-box"><p>A valid email is required.</p></div>';
    } elseif (!empty($password) && strlen($password) < 6) {
        $message = '<div class="error-box"><p>New password must be at least 6 characters.</p></div>';
    } else {
        // Check if username or email is taken by another user
        $check = $conn->query("SELECT id FROM users WHERE (username='$username' OR email='$email') AND id != $id");
        if ($check->num_rows > 0) {
            $message = '<div class="error-box"><p>Username or email is already taken by another user.</p></div>';
        } else {
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $hash_escaped = $conn->real_escape_string($hash);
                $sql = "UPDATE users SET username='$username', email='$email', role='$role', password_hash='$hash_escaped' WHERE id=$id";
            } else {
                $sql = "UPDATE users SET username='$username', email='$email', role='$role' WHERE id=$id";
            }

            if ($conn->query($sql)) {
                header('Location: users.php');
                exit;
            } else {
                $message = '<div class="error-box"><p>Error: ' . $conn->error . '</p></div>';
            }
        }
    }
} else {
    // Pre-fill from existing user
    $username = $user['username'];
    $email    = $user['email'];
    $role     = $user['role'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container form-page">
    <h1>Edit User #<?= $user['id'] ?></h1>

    <?= $message ?>

    <form method="POST" action="edit_user.php?id=<?= $id ?>">

        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label>Role</label>
        <select name="role" <?= $id === (int)$_SESSION['user_id'] ? 'disabled' : '' ?> required>
            <option value="staff"  <?= $role === 'staff'  ? 'selected' : '' ?>>Staff</option>
            <option value="admin"  <?= $role === 'admin'  ? 'selected' : '' ?>>Admin</option>
        </select>
        <?php if ($id === (int)$_SESSION['user_id']): ?>
            <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
            <small style="color:#888; font-size:0.82rem;">You cannot change your own role.</small>
        <?php endif; ?>

        <label>New Password <small style="color:#888; font-weight:400;">(leave blank to keep current)</small></label>
        <input type="password" name="password" placeholder="Enter new password to change it">

        <button type="submit">Update User</button>
        <a href="users.php" class="cancel">Cancel</a>

    </form>
</div>

</body>
</html>