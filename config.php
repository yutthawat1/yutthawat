<?php
$servername = "localhost";
$username = "root";  // ใช้ข้อมูลการเชื่อมต่อของคุณ
$password = "";      // ใช้ข้อมูลการเชื่อมต่อของคุณ
$dbname = "library";

$conn = new mysqli("localhost", "root", "", "library");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
