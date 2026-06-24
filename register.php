<?php
require_once 'config.php';
require_once 'auth.php';

if (isLoggedIn() && !isAdmin()) {
    header('Location: index.php');
    exit;
}

$username = "";
$email = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username))         $errors[] = "Username is required.";
    if (empty($email))            $errors[] = "Email is required.";
    if (empty($password))         $errors[] = "Password is required.";
    if (empty($confirm_password)) $errors[] = "Confirm Password is required.";

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
            "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $username, $email, $password_hash);
        if ($stmt->execute()) {
            echo "<script>
                alert('Registration Successful!');
                window.location='register.php';
            </script>";
            exit();
        } else {
            $errors[] = "Registration failed.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php if (isLoggedIn()): ?>
    <?php include 'navbar.php'; ?>
<?php else: ?>
    <div class="container">
        <nav class="navbar">
            <div class="navbar-links">
                <a href="register.php" class="active">Register</a>
                <a href="login.php">Login</a>
            </div>
        </nav>
    </div>
<?php endif; ?>

<div class="container auth">
    <div class="form-page">

        <h1>Register User</h1>

        <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST">

            <label>Username</label>
            <input
                type="text"
                name="username"
                value="<?= htmlspecialchars($username) ?>"
            >

            <label>Email</label>
            <input
                type="email"
                name="email"
                value="<?= htmlspecialchars($email) ?>"
            >

            <label>Password</label>
            <input type="password" name="password">

            <label>Confirm Password</label>
            <input type="password" name="confirm_password">

            <button type="submit">Register</button>
            <a href="index.php" class="cancel">Cancel</a>

        </form>

    </div>
</div>

</body>
</html>