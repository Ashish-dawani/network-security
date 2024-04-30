<?php
$host = 'localhost';
$username = 'pm_user';
$password = 'Sec0t!@09';
$database = 'password_manager';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
