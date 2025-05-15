<?php
require_once '../config/database.php';

// is data already exists?
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM tb_inventory");
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    $dummy_data = [
        ['KS001', 'R134a Freon Refrigerant', 15, 'kg', 120000.00, 1],
        ['KS002', 'R404a Freon Refrigerant', 10, 'kg', 180000.00, 1],
        ['KS003', 'R22 Freon Refrigerant', 12, 'kg', 95000.00, 1],
        ['KS004', 'Araldite Epoxy Glue', 25, 'pcs', 45000.00, 1],
        ['KS005', 'A/C Thinner', 18, 'liter', 35000.00, 1],
        ['KS006', 'Aquaproof Waterproofing', 8, 'kg', 75000.00, 1],
        ['KS007', 'Gas Hose 1/4"', 30, 'meter', 25000.00, 1],
        ['KS008', 'PTZ Regulator Valve', 5, 'pcs', 320000.00, 1],
        ['KS009', 'Compressor Oil', 20, 'liter', 65000.00, 1],
        ['KS010', 'Thermostat Controller', 7, 'pcs', 450000.00, 1],
        ['KS011', 'Refrigerator Door Gasket', 12, 'pcs', 120000.00, 1],
        ['KS012', 'Freezer Evaporator Fan', 6, 'pcs', 210000.00, 1],
        ['KS013', 'Commercial Range Igniter', 8, 'pcs', 175000.00, 1],
        ['KS014', 'Oven Temperature Sensor', 10, 'pcs', 95000.00, 1],
        ['KS015', 'Kitchen Hood Filter', 15, 'pcs', 85000.00, 1]
    ];

    foreach ($dummy_data as $item) {
        $sql = "INSERT INTO tb_inventory (kode_barang, nama_barang, jumlah_barang, satuan_barang, harga_beli, status_barang) 
                VALUES ('$item[0]', '$item[1]', $item[2], '$item[3]', $item[4], $item[5])";
        
        if (mysqli_query($conn, $sql)) {
            echo "Dummy data inserted: $item[1]<br>";
        } else {
            echo "Error inserting data: " . mysqli_error($conn) . "<br>";
        }
    }
    
    echo "Kitchen equipment spare parts data has been added successfully!<br>";
} else {
    echo "Data already exists in the table. Skipping dummy data insertion.<br>";
}

echo "Seeder completed successfully!";
echo "<p>Now you can back to home <a href='./../'>Home</a>.</p>";
?> 