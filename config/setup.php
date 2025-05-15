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
    $database = "production_database";
}

$conn = mysqli_connect($host, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS ". $database;
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

mysqli_select_db($conn, $database);

$sql = "CREATE TABLE IF NOT EXISTS tb_inventory (
    id_barang INT(10) AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(20) NOT NULL,
    nama_barang VARCHAR(50) NOT NULL,
    jumlah_barang INT(10) NOT NULL,
    satuan_barang VARCHAR(20) NOT NULL,
    harga_beli DOUBLE(20,2) NOT NULL,
    status_barang BOOLEAN NOT NULL
)";

if (mysqli_query($conn, $sql)) {
    echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);

echo "Database and table setup completed successfully!";
echo "<p>To populate the database with sample data, please run the <a href='../seeder/inventory_seeder.php'>Inventory Seeder</a>.</p>";
?> 