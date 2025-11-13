<?php
$servername = "localhost";
$username = "root";
$database = "company";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "";

mysqli_select_db($conn, $database);
?>