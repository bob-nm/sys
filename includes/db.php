<?php
$servername = "localhost";
$username = "agsomuta_sys";
$password = "+5a6g3(Ay6NG";
$database = "agsomuta_sys";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
