-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2023 at 01:09 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `om_sai_electricals`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_tokens`
--

CREATE TABLE `login_tokens` (
  `token` text NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_tokens`
--

INSERT INTO `login_tokens` (`token`, `user_id`, `created_on`) VALUES
('f4201d8772a206fc1850b0d08046106b385c13eff9f651c62a0d542852b0d285', '', '2023-05-25 19:58:44'),
('699508a8f7d2bd35539d443c3131eb153f6217e487468fe3ba29c1de0eb5d571', 'WY6724', '2023-05-25 20:00:41'),
('e5a1c2cfa9fd8b34ae97c844ffe98f16cd17570a7f77a6abb76ed37a4cf9cb68', 'WY6724', '2023-05-25 20:02:04'),
('466119f871e44064c1e2b3c9721417a1e0f701ccb2cb38fde4367964855cdaa1', 'WY6724', '2023-05-25 20:08:56'),
('a66e3af7a8d1961c70ed92bbc900abccff97ed7291dfa13187f3058304282749', 'WY6724', '2023-05-25 20:16:03'),
('7d63563c804ff65a0becbb847d4e3f8ad8c358ccdaf199c2dab66aea994bcf27', 'WY6724', '2023-05-25 20:20:30'),
('c27b686063a564f130b05c9cf87fbcdfbb393dafba2b48778ec2f682a928691d', 'WY6724', '2023-05-25 20:41:11'),
('077e8448f8a51945da4b18b147a9e610b28869ad765535ef347209fc96c8c19e', 'WY6724', '2023-05-25 20:49:42'),
('a1462e865f75361829d2f898c54dd5b402e9d7420c1c6650a2e8bedff4508fa3', 'WY6724', '2023-05-26 09:02:18'),
('8496807993e910255f205068607d1d276f0208bfd580720690f3fbd495878087', 'WY6724', '2023-05-26 12:19:20');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orders_tb_id` int(11) NOT NULL,
  `orders_id` varchar(25) NOT NULL,
  `users_id` varchar(30) NOT NULL,
  `products_id` varchar(30) NOT NULL,
  `ordered_qty` int(11) NOT NULL,
  `ordered_amount` varchar(30) NOT NULL,
  `order_status` int(11) NOT NULL COMMENT '0:-placed,1:-completed,2:-cancelled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orders_tb_id`, `orders_id`, `users_id`, `products_id`, `ordered_qty`, `ordered_amount`, `order_status`) VALUES
(1, 'ORD6179', 'WY6724', 'PROD2601', 5, '', 0),
(2, 'ORD6179', 'WY6724', 'PROD4444', 3, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `pro_tb_id` int(11) NOT NULL,
  `product_id` varchar(30) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` int(11) NOT NULL,
  `product_img` text NOT NULL,
  `product_qty` int(11) NOT NULL,
  `product_desc` text NOT NULL,
  `product_status` int(11) NOT NULL COMMENT '0:-Inactive,1:-Active,2:-Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pro_tb_id`, `product_id`, `product_name`, `product_price`, `product_img`, `product_qty`, `product_desc`, `product_status`) VALUES
(1, 'PROD8890', 'Men\'s Black Jacket T-shirt', 1000, 'images/img-pro-01.jpg', 20, ' Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit non aperiam nemo a incidunt, facere ipsa, impedit id, velit nesciunt aut quisquam omnis consequuntur quo exercitationem voluptatum aliquid repellendus nam labore dicta quod obcaecati quas! Quo, ipsum vitae voluptatem quia minus qui ad sit accusantium, cupiditate, ipsam exercitationem quam cumque.', 1),
(2, 'PROD0753', 'Woman\'s Top  ', 500, 'images/img-pro-02.jpg', -6, ' Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit non aperiam nemo a incidunt, facere ipsa, impedit id, velit nesciunt aut quisquam omnis consequuntur quo exercitationem voluptatum aliquid repellendus nam labore dicta quod obcaecati quas! Quo, ipsum vitae voluptatem quia minus qui ad sit accusantium, cupiditate, ipsam exercitationem quam cumque.', 1),
(3, 'PROD2601', 'Woman\'s Red and Black Gown', 1500, 'images/big-img-01.jpg', -38, ' Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit non aperiam nemo a incidunt, facere ipsa, impedit id, velit nesciunt aut quisquam omnis consequuntur quo exercitationem voluptatum aliquid repellendus nam labore dicta quod obcaecati quas! Quo, ipsum vitae voluptatem quia minus qui ad sit accusantium, cupiditate, ipsam exercitationem quam cumque.', 1),
(4, 'PROD4467', 'Man\'s Shirts Combo', 700, 'images/shirt-img.jpg', 14, ' Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit non aperiam nemo a incidunt, facere ipsa, impedit id, velit nesciunt aut quisquam omnis consequuntur quo exercitationem voluptatum aliquid repellendus nam labore dicta quod obcaecati quas! Quo, ipsum vitae voluptatem quia minus qui ad sit accusantium, cupiditate, ipsam exercitationem quam cumque.', 1),
(5, 'PROD3330', 'Brown Shoes', 1350, 'images/shoes-img.jpg', -3, ' Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit non aperiam nemo a incidunt, facere ipsa, impedit id, velit nesciunt aut quisquam omnis consequuntur quo exercitationem voluptatum aliquid repellendus nam labore dicta quod obcaecati quas! Quo, ipsum vitae voluptatem quia minus qui ad sit accusantium, cupiditate, ipsam exercitationem quam cumque.', 1),
(6, 'PROD4444', 'Watch', 4000, 'images/watch.jpg', -15, 'Watch', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_tb_id` int(11) NOT NULL,
  `users_id` varchar(30) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(30) NOT NULL,
  `user_mobile` varchar(30) NOT NULL,
  `user_address` text NOT NULL,
  `user_password` text NOT NULL,
  `user_status` int(11) NOT NULL COMMENT '0:-Inactive,1:-Active,2:-Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_tb_id`, `users_id`, `user_name`, `user_email`, `user_mobile`, `user_address`, `user_password`, `user_status`) VALUES
(1, 'WY9876', 'Karan Gohil', 'karangohil08@gmail.com', '9086734536', 'Trident', 'karan', 1),
(2, 'WY6724', 'Maulik Gohil', 'maulikg9@gmail.com', '9967735690', 'Trident', 'maulik', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orders_tb_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pro_tb_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_tb_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orders_tb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pro_tb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_tb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
