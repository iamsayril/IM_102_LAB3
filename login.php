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

        $stmt = $conn->prepare(
            "SELECT * FROM users WHERE username = ?"
        );
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        $stmt->close();

        if ($user !== null && password_verify($password, $user['password_hash'])) {

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'] ?? 'staff';

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
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container auth">
<div class="container">

<nav class="navbar">
        <div class="navbar-links">
            <a href="index.php">Home</a>
            <a href="report.php">Report</a>
            <a href="register.php">Register</a>
            <a href="login.php" class="active">Login</a>
        </div>
    </nav>

    <div class="form-page">

        <h1>Login</h1>

        <?php if (!empty($error)): ?>
        <div class="error-box">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
        <?php endif; ?>

        <form method="POST">

            <label>Username</label>
            <input
                type="text"
                name="username"
                value="<?= htmlspecialchars($username) ?>"
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
            >

            <button type="submit">Login</button>

        </form>

    </div>

</div>

</body>
</html>