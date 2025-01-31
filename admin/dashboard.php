<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");  // Redirect to login if not admin
    exit();
}

require '../includes/db.php';

// Fetch some data to display (e.g., total users)
$query = $conn->prepare("SELECT COUNT(*) AS user_count FROM users");
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, Admin!</p>
    <p>Total registered users: <?php echo $data['user_count']; ?></p>
    <a href="../logout.php">Logout</a>
</body>
</html>
