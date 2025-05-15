<?php
$server_name = $_SERVER['SERVER_NAME'];

if (strpos($server_name, 'localhost') !== false || strpos($server_name, '127.0.0.1') !== false) {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "db_inventory";
} else {
    $host = "production_host";
    $username = "production_user";
    $password = "production_password";
    $database = "db_inventory";
}

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?> 