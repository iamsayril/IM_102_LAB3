<?php
require_once 'auth.php';
require_once 'config.php';
requireLogin();
requireAdmin();

$username = "";
$email = "";
$role = "staff";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'] ?? 'staff';

    // Sanitize role
    if (!in_array($role, ['admin', 'staff'])) {
        $role = 'staff';
    }

    if (empty($username))
        $errors[] = "Username is required.";
    if (empty($email))
        $errors[] = "Email is required.";
    if (empty($password))
        $errors[] = "Password is required.";
    if (empty($confirm_password))
        $errors[] = "Confirm Password is required.";

    if (!empty($username) && strlen($username) < 3)
        $errors[] = "Username must be at least 3 characters.";

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Invalid email format.";

    if (!empty($password) && strlen($password) < 6)
        $errors[] = "Password must be at least 6 characters.";

    if ($password !== $confirm_password)
        $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE username = ? OR email = ?"
        );
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0)
            $errors[] = "Username or Email already exists.";
        $stmt->close();
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $username, $email, $password_hash, $role);
        if ($stmt->execute()) {
            echo "<script>
                alert('User added successfully!');
                window.location='users.php';
            </script>";
            exit();
        } else {
            $errors[] = "Failed to add user.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add User</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container auth">
        <div class="form-page">

            <h1>Add User</h1>

            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $error): ?>
                        <p>
                            <?= htmlspecialchars($error) ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>"
                    placeholder="e.g. john_doe">

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"
                    placeholder="e.g. john@example.com">

                <label>Password</label>
                <input type="password" name="password" placeholder="Min. 6 characters">

                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Re-enter password">

                <label>Role</label>
                <select name="role">
                    <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="staff" <?= $role === 'staff' ? 'selected' : '' ?>>Staff</option>
                </select>

                <button type="submit">Add User</button>
                <a href="users.php" class="cancel">Cancel</a>

            </form>

        </div>
    </div>

</body>

</html>