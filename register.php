<?php
session_start();
require 'includes/db.php';

// Define a fixed password (change this to something more secure)
$adminPassword = "Ovvs2020";  // Set your fixed password here

// Check if the password is correct before proceeding with registration
if (isset($_POST['password_check']) && $_POST['password_check'] === $adminPassword) {
    if (isset($_POST['register'])) {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role']; // 'admin' or 'client'
            
            $query = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $query->bind_param("sss", $email, $password, $role);
            
            if ($query->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
} else {
    // Show error if password doesn't match
    $error = "Invalid registration password.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="register.php">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Role:</label>
        <select name="role">
            <option value="client">Client</option>
            <option value="admin">Admin</option>
        </select><br>
        <label>Registration Password:</label>
        <input type="password" name="password_check" required><br>  <!-- Fixed password field -->
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="index.php">Login here</a></p>
</body>
</html>
