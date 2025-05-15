-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 15, 2025 at 07:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_inventory`
--

CREATE TABLE `tb_inventory` (
  `id_barang` int NOT NULL,
  `kode_barang` varchar(20) NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `jumlah_barang` int NOT NULL,
  `satuan_barang` varchar(20) NOT NULL,
  `harga_beli` double(20,2) NOT NULL,
  `status_barang` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_inventory`
--

INSERT INTO `tb_inventory` (`id_barang`, `kode_barang`, `nama_barang`, `jumlah_barang`, `satuan_barang`, `harga_beli`, `status_barang`) VALUES
(1, 'KS001', 'R134a Freon Refrigerant', 15, 'kg', 120000.00, 1),
(2, 'KS002', 'R404a Freon Refrigerant', 10, 'kg', 180000.00, 1),
(3, 'KS003', 'R22 Freon Refrigerant', 12, 'kg', 95000.00, 1),
(4, 'KS004', 'Araldite Epoxy Glue', 25, 'pcs', 45000.00, 1),
(5, 'KS005', 'A/C Thinner', 18, 'liter', 35000.00, 1),
(6, 'KS006', 'Aquaproof Waterproofing', 8, 'kg', 75000.00, 1),
(7, 'KS007', 'Gas Hose 1/4\"', 30, 'meter', 25000.00, 1),
(8, 'KS008', 'PTZ Regulator Valve', 5, 'pcs', 320000.00, 1),
(9, 'KS009', 'Compressor Oil', 20, 'liter', 65000.00, 1),
(10, 'KS010', 'Thermostat Controller', 7, 'pcs', 450000.00, 1),
(11, 'KS011', 'Refrigerator Door Gasket', 12, 'pcs', 120000.00, 1),
(12, 'KS012', 'Freezer Evaporator Fan', 6, 'pcs', 210000.00, 1),
(13, 'KS013', 'Commercial Range Igniter', 8, 'pcs', 175000.00, 1),
(14, 'KS014', 'Oven Temperature Sensor', 10, 'pcs', 95000.00, 1),
(15, 'KS015', 'Kitchen Hood Filter', 17, 'pcs', 85000.00, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  ADD PRIMARY KEY (`id_barang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
