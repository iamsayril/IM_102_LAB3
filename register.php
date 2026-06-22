<?php
require_once 'config.php';

$username = "";
$email = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Empty fields
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($confirm_password)) {
        $errors[] = "Confirm Password is required.";
    }

    // Username length
    if (!empty($username) && strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters.";
    }

    // Email validation
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password length
    if (!empty($password) && strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Password match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check username or email exists
    if (empty($errors)) {

        $stmt = $conn->prepare(
            "SELECT id FROM users 
             WHERE username = ? OR email = ?"
        );

        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Username or Email already exists.";
        }

        $stmt->close();
    }

    // Insert user
    if (empty($errors)) {

        $password_hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare(
            "INSERT INTO users
            (username, email, password_hash)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "sss",
            $username,
            $email,
            $password_hash
        );

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

<div class="container form-page">

    <h1>Register User</h1>

    <?php

    if (!empty($errors)) {

        echo "<div class='error-box'>";

        foreach ($errors as $error) {

            echo "<p>$error</p>";

        }

        echo "</div>";
    }

    ?>

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
        <input
            type="password"
            name="password"
        >

        <label>Confirm Password</label>
        <input
            type="password"
            name="confirm_password"
        >

        <button type="submit">
            Register
        </button>

    </form>

</div>

</body>
</html>