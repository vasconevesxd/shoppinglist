-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2023 at 09:12 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopping_list`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Fruits & Vegetables'),
(2, 'Clothing and Accessories'),
(3, 'Hygiene and Health'),
(4, 'Grocery'),
(5, 'Fish and Meat'),
(6, 'Drinks'),
(7, 'Bakery and Pastry');

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE `list` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` longtext NOT NULL DEFAULT 'https://t4.ftcdn.net/jpg/04/90/90/53/360_F_490905361_YlHXpmr7oVsOUnguMIkafS81IKG5Hgtv.jpg',
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `list`
--

INSERT INTO `list` (`id`, `name`, `user_id`, `image`, `date_time`) VALUES
(28, 'compras Casa', 7, 'https://t4.ftcdn.net/jpg/04/90/90/53/360_F_490905361_YlHXpmr7oVsOUnguMIkafS81IKG5Hgtv.jpg', '2023-02-03 15:13:10'),
(29, 'Carro', 7, 'https://t4.ftcdn.net/jpg/04/90/90/53/360_F_490905361_YlHXpmr7oVsOUnguMIkafS81IKG5Hgtv.jpg', '2023-02-03 15:13:49'),
(32, 'coisas vasco', 8, 'https://t4.ftcdn.net/jpg/04/90/90/53/360_F_490905361_YlHXpmr7oVsOUnguMIkafS81IKG5Hgtv.jpg', '2023-02-03 19:40:58'),
(33, 'Casa', 9, 'https://t4.ftcdn.net/jpg/04/90/90/53/360_F_490905361_YlHXpmr7oVsOUnguMIkafS81IKG5Hgtv.jpg', '2023-02-03 19:56:20'),
(34, 'veggies', 9, 'https://t4.ftcdn.net/jpg/04/90/90/53/360_F_490905361_YlHXpmr7oVsOUnguMIkafS81IKG5Hgtv.jpg', '2023-02-03 19:57:19'),
(35, 'ii', 9, 'https://t4.ftcdn.net/jpg/04/90/90/53/360_F_490905361_YlHXpmr7oVsOUnguMIkafS81IKG5Hgtv.jpg', '2023-02-03 20:02:01'),
(39, 'yhuhuhu', 9, 'https://t4.ftcdn.net/jpg/04/90/90/53/360_F_490905361_YlHXpmr7oVsOUnguMIkafS81IKG5Hgtv.jpg', '2023-02-03 21:12:03'),
(40, 'Coisas', 7, 'https://t4.ftcdn.net/jpg/04/90/90/53/360_F_490905361_YlHXpmr7oVsOUnguMIkafS81IKG5Hgtv.jpg', '2023-02-04 00:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `list_product`
--

CREATE TABLE `list_product` (
  `id` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `state` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `list_product`
--

INSERT INTO `list_product` (`id`, `list_id`, `product_id`, `state`) VALUES
(100, 28, 22, 1),
(101, 28, 25, 1),
(102, 28, 26, 1),
(105, 29, 31, 1),
(106, 32, 22, 1),
(107, 32, 25, 1),
(108, 32, 27, 1),
(109, 32, 30, 1),
(110, 32, 66, 1),
(111, 33, 21, 1),
(112, 34, 1, 1),
(113, 34, 2, 1),
(114, 34, 3, 1),
(116, 35, 2, 1),
(132, 29, 22, 1),
(133, 29, 25, 1),
(134, 29, 30, 1),
(135, 29, 1, 1),
(136, 29, 2, 1),
(137, 39, 3, 1),
(138, 39, 4, 1),
(139, 40, 33, 1),
(140, 40, 35, 1),
(141, 40, 37, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `category_id`) VALUES
(1, 'Apples', 1),
(2, 'Bananas', 1),
(3, 'Oranges', 1),
(4, 'Strawberries', 1),
(5, 'Lemons', 1),
(6, 'Blueberries', 1),
(7, 'Grapes', 1),
(8, 'Pineapples', 1),
(9, 'Mangoes', 1),
(10, 'Pears', 1),
(11, 'Tomatoes', 1),
(12, 'Carrots', 1),
(13, 'Lettuce', 1),
(14, 'Onions', 1),
(15, 'Potatoes', 1),
(16, 'Cucumbers', 1),
(17, 'Bell peppers', 1),
(18, 'Broccoli', 1),
(19, 'Cauliflower', 1),
(20, 'Spinach', 1),
(21, 'Shirts', 2),
(22, 'Pants', 2),
(23, 'Dresses', 2),
(24, 'Shoes', 2),
(25, 'Handbags', 2),
(26, 'Sunglasses', 2),
(27, 'Watches', 2),
(28, 'Jewelry', 2),
(29, 'Belts', 2),
(30, 'Hats', 2),
(31, 'Soap', 3),
(32, 'Shampoo', 3),
(33, 'Toothpaste', 3),
(34, 'Conditioner', 3),
(35, 'Deodorant', 3),
(36, 'Lotion', 3),
(37, 'Vitamins', 3),
(38, 'Pain relievers', 3),
(39, 'First Aid Kit', 3),
(40, 'Hand sanitizer', 3),
(41, 'Bread', 4),
(42, 'Eggs', 4),
(43, 'Milk', 4),
(44, 'Cheese', 4),
(45, 'Cereal', 4),
(46, 'Butter', 4),
(47, 'Ground beef', 4),
(48, 'Rice', 4),
(49, 'Pasta', 4),
(50, 'Sugar', 4),
(51, 'Salmon', 5),
(52, 'Tuna', 5),
(53, 'Shrimp', 5),
(54, 'Beef', 5),
(55, 'Chicken', 5),
(56, 'Pork', 5),
(57, 'Lamb', 5),
(58, 'Sausages', 5),
(59, 'Bacon', 5),
(60, 'Ham', 5),
(61, 'Water', 6),
(62, 'Soda', 6),
(63, 'Juice', 6),
(64, 'Coffee', 6),
(65, 'Tea', 6),
(66, 'Beer', 6),
(67, 'Wine', 6),
(68, 'Spirits', 6),
(69, 'Energy drinks', 6),
(70, 'Sports drinks', 6),
(71, 'Bread', 7),
(72, 'Croissants', 7),
(73, 'Muffins', 7),
(74, 'Cakes', 7),
(75, 'Cookies', 7),
(76, 'Pies', 7),
(77, 'Bagels', 7),
(78, 'Donuts', 7),
(79, 'Pretzels', 7),
(80, 'Tarts', 7);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`) VALUES
(5, 'Manel', 'xykijuhy@mailinator.com', 'Pa$$w0rd!'),
(6, 'Sofia', 'xaqi@mailinator.com', 'Pa$$w0rd!'),
(7, 'daniel', 'daniel@gmail.com', 'Pa$$w0rd!'),
(8, 'Vasco Maria', 'vasco@sapo.pt', 'Pa$$w0rd!'),
(9, 'SG', 'sandra.gama@my.istec.pt', 'password'),
(10, 'Pedro', 'pedro@sapo.pt', 'password'),
(11, 'Ana', 'ana@test.pt', 'password');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list`
--
ALTER TABLE `list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `list_product`
--
ALTER TABLE `list_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `list`
--
ALTER TABLE `list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `list_product`
--
ALTER TABLE `list_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `list`
--
ALTER TABLE `list`
  ADD CONSTRAINT `list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `list_product`
--
ALTER TABLE `list_product`
  ADD CONSTRAINT `list_product_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
