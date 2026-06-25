<?php
require_once 'auth.php';
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$username = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter username and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user !== null && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'] ?? 'staff';

            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login — Inventory System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

    <div class="auth-wrapper">

        <div class="auth-logo">
            <div class="auth-logo-text">Inventory System</div>
        </div>

        <div class="auth-card">

            <h1>Welcome back</h1>
            <p class="auth-subtitle">Sign in to your account to continue.</p>

            <?php if (!empty($error)): ?>
                <div class="error-box">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <form method="POST">

                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>"
                    placeholder="Enter your username" autocomplete="username">

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password"
                    autocomplete="current-password">

                <button type="submit">Sign In</button>

            </form>

            <div class="auth-divider"></div>
            <div class="auth-footer">
                Don't have an account? <a href="register.php">Register here</a>
            </div>

        </div>
    </div>

</body>

</html>