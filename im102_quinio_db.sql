-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2026 at 04:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `im102_quinio_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Electronics'),
(2, 'Food'),
(3, 'Clothing'),
(4, 'Sports'),
(5, 'Books');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category_id`, `supplier_id`, `created_at`, `added_by`) VALUES
(1, 'Wireless Mouse', 'Ergonomic wireless mouse', 499.99, 50, 1, 1, '2026-06-15 01:21:55', 1),
(2, 'Mechanical Keyboard', 'RGB backlit keyboard', 1500.00, 30, 1, 1, '2026-06-15 01:21:55', 1),
(3, 'Bluetooth Speaker', 'Portable mini speaker', 899.00, 40, 1, 1, '2026-06-15 01:21:55', 1),
(4, 'USB Flash Drive 64GB', 'High-speed USB 3.0 storage', 350.00, 100, 1, 1, '2026-06-15 01:21:55', 1),
(5, 'Chocolate Cake', 'Moist chocolate layered cake', 650.00, 20, 3, 2, '2026-06-15 01:21:55', 1),
(6, 'Banana Bread', 'Freshly baked banana loaf', 180.00, 35, 3, 2, '2026-06-15 01:21:55', 1),
(7, 'Croissant', 'Buttery flaky pastry', 75.00, 50, 3, 2, '2026-06-15 01:21:55', 1),
(8, 'Box of Cupcakes', '6 assorted cupcakes', 300.00, 25, 3, 2, '2026-06-15 01:21:55', 1),
(9, 'Fresh Milk 1L', 'Organic farm fresh milk', 90.00, 60, 3, 3, '2026-06-15 01:21:55', 1),
(10, 'Eggs (Dozen)', 'Free-range chicken eggs', 120.00, 80, 3, 3, '2026-06-15 01:21:55', 1),
(11, 'Carrots 1kg', 'Fresh organic carrots', 70.00, 100, 3, 3, '2026-06-15 01:21:55', 1),
(12, 'Lettuce', 'Crisp green lettuce', 50.00, 90, 3, 3, '2026-06-15 01:21:55', 1),
(13, 'franco', 'adsad', 2224.00, 19, 4, 3, '2026-06-25 01:24:13', 5),
(14, 'Banana Cake', 'banana cake', 250.00, 5, 2, 2, '2026-06-25 02:00:50', 6);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`) VALUES
(1, 'LexCorp', 'Lex Lauthor', '955889933'),
(2, 'BakeShop INC', 'Alyssa Gaton', '91122334455'),
(3, 'KentsFarm', 'Jonathan Kent', '9223334455');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$BrYJenw8brpRyiPi9TswquhWOGkMZsp7nRP4Kmms6BiIOOnBWo46S', 'admin', '2026-06-24 01:18:35'),
(2, 'test_staff', 'test_staff@gmail.com', '$2y$10$rOOfkH5r8D1qwju74nWZru9AZxf8FTdSbpWXbwZuAhssBnqVFfobm', 'staff', '2026-06-24 01:21:20'),
(3, 'test2_staff333', 'test2_staff@gmail.com', '$2y$10$W1zhPUMzP1LDUTLfIKDT4.F.WTvIwagDr5FwPX//aFh2ZKbaylNae', 'staff', '2026-06-24 01:51:21'),
(5, 'admin_2', 'admin_2@gmail.com', '$2y$10$f7Iw0poe4A4Y9HKsf1EMCu7pgMbTsQhbKrxoaGDozyxLnIWhIzF5y', 'admin', '2026-06-25 01:07:31'),
(6, 'Cyrell', 'cyrell@gmail.com', '$2y$10$cAhZ.lUti5AZR994Or11jezKHr5orjAOeKonN6yzHNVZIFxu5l9EC', 'admin', '2026-06-25 02:00:25'),
(7, 'test_m', 'test_m@gmail.com', '$2y$10$mOqIy/HJM7OZmouHf0J2/.UMCicXlcDgo3bNYGRlDOFxisMC6UrQm', 'staff', '2026-06-25 02:16:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
