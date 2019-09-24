-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2019 at 03:16 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 5.6.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kpasar`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `timestamp` int(11) NOT NULL,
  `image` text NOT NULL,
  `preview` text NOT NULL,
  `file_content` text NOT NULL,
  `hit` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `category_id`, `title`, `user_id`, `timestamp`, `image`, `preview`, `file_content`, `hit`) VALUES
(1, 108, 'organik', 14, 1569199960, 'BLOG_asdf_1569199750.JPG', '-', 'BLOG_organik_1569199960.html', 0);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `_order` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_id`, `name`, `description`, `_order`) VALUES
(103, 0, 'Pasar komoditi', 'Pasar komoditi', 1),
(104, 103, 'pertanian', 'pertanian', 1),
(105, 103, 'Perkebunan', 'Perkebunan', 1),
(106, 103, 'Peternakan', 'Peternakan', 1),
(107, 103, 'Hidroponik', 'Hidroponik', 1),
(108, 103, 'Organik', 'Organik', 1),
(109, 0, 'Suplier', 'Suplier', 1),
(110, 109, 'Pupuk', 'Pupuk', 1),
(111, 109, 'Mesin Pertanian', 'Mesin Pertanian', 1),
(112, 109, 'Pembasmi Hama', 'Pembasmi Hama', 1),
(113, 109, 'Lain Lain', 'Lain Lain', 1),
(114, 0, 'Transportasi', 'Transportasi', 1),
(115, 114, 'Pick Up', '-', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` int(5) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `file` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `type`, `name`, `description`, `file`) VALUES
(1, 4, 'iklana', '-', 'Iklan_iklana_1569197550.JPG'),
(11, 1, 'qwe', '-', 'Gallery_qwe_1569311057.JPG'),
(12, 1, 'yuhu', '-', 'Gallery_yuhu_1569318097.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'farmer', 'Petani'),
(3, 'suplier', 'suplier'),
(4, 'transporter', 'transporter'),
(5, 'uadmin', 'uadmin');

-- --------------------------------------------------------

--
-- Table structure for table `group_category`
--

CREATE TABLE `group_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_category`
--

INSERT INTO `group_category` (`id`, `group_id`, `category_id`) VALUES
(1, 2, 103),
(2, 3, 109),
(3, 4, 114);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(1, '::1', '081342989181', 1569289437),
(3, '::1', '081342989181', 1569330539),
(4, '::1', '081342989181', 1569330758);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(50) NOT NULL,
  `list_id` varchar(200) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `position` int(4) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `menu_id`, `name`, `link`, `list_id`, `icon`, `status`, `position`, `description`) VALUES
(101, 1, 'Beranda', 'admin/', 'home_index', 'home', 1, 1, '-'),
(102, 1, 'Group', 'admin/group', 'group_index', 'home', 1, 2, '-'),
(103, 1, 'Setting', 'admin/menus', '-', 'cogs', 1, 3, '-'),
(104, 1, 'User', 'admin/user_management', 'user_management_index', 'users', 1, 4, '-'),
(106, 103, 'Menu', 'admin/menus', 'menus_index', 'circle', 1, 1, '-'),
(107, 2, 'Beranda', 'user/home', 'home_index', 'home', 1, 1, '-'),
(108, 2, 'Usaha Saya', 'user/store', 'store_index', 'home', 1, 2, '-'),
(109, 2, 'Produk Saya', 'user/product', 'product_index', 'home', 1, 3, '-'),
(110, 5, 'Beranda', 'uadmin/home', 'home_index', 'home', 1, 1, '-'),
(111, 5, 'User', 'uadmin/users', 'users_index', 'home', 1, 2, '-'),
(112, 111, 'Petani', 'uadmin/users/farmer', 'users_farmer', 'home', 1, 1, '-'),
(113, 111, 'Suplier', 'uadmin/users/suplier', 'users_suplier', 'home', 1, 2, '-'),
(114, 111, 'Transporter', 'uadmin/users/transporter', 'users_transporter', 'home', 1, 3, '-'),
(115, 5, 'Kategori', 'uadmin/category', 'category_index', 'home', 1, 3, '-'),
(116, 5, 'Iklan', 'uadmin/iklan', 'iklan_index', 'home', 1, 4, '-'),
(117, 111, 'Tambah User', 'uadmin/users/add', 'users_add', 'home', 1, 4, '-'),
(118, 5, 'blog', 'uadmin/blog', 'blog_index', 'home', 1, 5, '-'),
(119, 5, 'Group Kategori', 'uadmin/group_category', 'group_category_index', 'home', 1, 3, '-'),
(120, 3, 'Beranda', 'user/home', 'home_index', 'home', 1, 1, '-'),
(121, 3, 'Usaha Saya', 'user/store', 'store_index', 'home', 1, 2, '-'),
(122, 3, 'Produk Saya', 'user/product', 'product_index', 'home', 1, 3, '-'),
(123, 2, 'Galeri', 'user/gallery', 'gallery_index', 'home', 1, 4, '-'),
(124, 3, 'Galeri', 'user/gallery', 'gallery_index', 'home', 1, 4, '-'),
(125, 4, 'Beranda', 'user/home', 'home_index', 'home', 1, 1, '-'),
(126, 4, 'Usaha Saya', 'user/store', 'store_index', 'home', 1, 2, '-'),
(127, 4, 'Transportasi Saya', 'user/vehicle', 'vehicle_index', 'home', 1, 3, '-'),
(128, 4, 'Galeri', 'user/gallery', 'gallery_index', 'home', 1, 4, '-');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(10) UNSIGNED NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `images` text NOT NULL,
  `unit` varchar(100) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `store_id`, `name`, `description`, `price`, `category_id`, `images`, `unit`, `timestamp`) VALUES
(8, 5, 'sayur', 'sayur', 10000, 106, 'PRODUCT_qwe_1569302450.JPG;PRODUCT_qwe_1569302338.JPG;PRODUCT_qwe_1569302359.JPG', 'ikat', 0),
(9, 6, 'traktor', 'traktor', 3000000, 111, 'PRODUCT_traktor_1569307442_0.jpg;PRODUCT_traktor_1569307442_1.JPG;PRODUCT_traktor_1569307442_2.JPG', 'unit', 0);

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(200) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `hit` int(5) NOT NULL,
  `latitude` varchar(200) NOT NULL,
  `longitude` varchar(200) NOT NULL,
  `image` text NOT NULL,
  `start_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`id`, `user_id`, `name`, `description`, `address`, `timestamp`, `hit`, `latitude`, `longitude`, `image`, `start_date`) VALUES
(5, 15, 'tani indah', 'teni jess', 'jln mutiara no 8', 1569240338, 0, '', '', 'STORE_tani_indah_1569240349.JPG', 1567548000),
(6, 25, 'pembasmi greget', 'pembasmi greget', 'Jl. mutiara', 1569307252, 0, '', '', 'STORE_pembasmi_greget_1569307252.jpg', 1567461600),
(7, 23, 'tani yuhu', 'asdf', 'jln mutiara', 1569310929, 0, '', '', 'STORE_tani_yuhu_1569310929.jpg', 1568239200),
(8, 24, 'alan kurir', 'kurir cepat', 'alamat', 1569314134, 0, '', '', 'STORE_alan_kurir_1569314134.JPG', 1567634400);

-- --------------------------------------------------------

--
-- Table structure for table `store_gallery`
--

CREATE TABLE `store_gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL,
  `gallery_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_gallery`
--

INSERT INTO `store_gallery` (`id`, `store_id`, `gallery_id`) VALUES
(4, 6, 11),
(5, 8, 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `image` text NOT NULL,
  `address` varchar(200) NOT NULL DEFAULT 'alamat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `phone`, `image`, `address`) VALUES
(1, '127.0.0.1', 'admin@fixl.com', '$2y$12$8vyWKnAYbuWEDf2x2sVwDuV3Spm9wNulOJeVE2kUmdOpQ/9a1R/E2', 'admin@fixl.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1569313983, 1, 'Admin', 'istrator', '081342989185', 'USER_1_1568678001.jpeg', 'alamat'),
(14, '::1', 'uadmin@gmail.com', '$2y$10$AdvTNWS7tmyY8a/1frHDzug4RtpDqHOlqn2l5hrWwbIyQcZ5Ksvtm', 'uadmin@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1569033287, 1569311537, 1, 'user', 'admin', '000', 'USER_14_1569237654.jpeg', 'alamat'),
(15, '::1', '081342989111', '$2y$10$eDBfnio0vrTApEDpa2ExOe/HGazmyyYIWYCY57XeHjpOFR9CNLipW', 'allan@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1569108799, 1569318022, 1, 'alin', 'a', '081342989111', 'USER_15_1569233017.PNG', 'alamat'),
(23, '::1', '123434654567', '$2y$10$RlYeDxRrHTmWfujeLh8c4.lt/horaJdOAX89oSDi4CtYVOSXuFXKC', 'qallan@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1569111752, 1569311097, 1, 'w', 'w', '123434654567', '', 'w'),
(24, '::1', '3456656545', '$2y$10$7JOw4sHEzl72/B0LSm7Ua.TcE/cNdowpYw8Waqzzy2FovN7LnjczO', 'energinasantaramandiri@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1569112198, 1569318077, 1, 'e', 'e', '3456656545', 'USER_24_1569313962.jpg', 'e'),
(25, '::1', '123409871234', '$2y$10$iWIgl9DauGj8tYpSnGb2ceb5Snuy.maaQQxwQB9/juSlrwbIbADDm', 'yuhu@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1569307002, 1569318009, 1, 'yuhu', 'yuhu', '123409871234', 'USER_25_1569307206.jpeg', 'yuhu'),
(26, '::1', '123443211234', '$2y$10$IUtortdeFyU4q7qY4S8qn.5tWAjfl7XByvSF4lT5lKIn5A7vnO2Im', 'qqqwe@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1569311573, 1569311605, 1, 'qwe', 'qwe', '123443211234', 'USER_26_1569311840.PNG', 'qqqwe@gmail.com'),
(27, '::1', '123498763456', '$2y$10$zW4FM.zUn4dj5wcpMi9C9u2B9AyG00d6gybcDhryw7kXU0jNHyvCS', 'alan@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1569330150, 1569330207, 1, 'alan', 'alin', '123498763456', 'default.jpg', 'alamat');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(32, 1, 1),
(33, 14, 5),
(31, 15, 2),
(28, 23, 2),
(37, 24, 4),
(34, 25, 3),
(35, 26, 3),
(38, 27, 2);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `store_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `capacity` int(11) NOT NULL,
  `unit` varchar(200) NOT NULL,
  `images` text NOT NULL,
  `police_number` varchar(20) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`id`, `category_id`, `store_id`, `name`, `description`, `capacity`, `unit`, `images`, `police_number`, `timestamp`) VALUES
(2, 115, 8, 'nissan', 'mobil cepat', 123, 'ton', 'VEHICLE_nissan_1569317577.JPG;VEHICLE_nissan_1569317591.jpg;VEHICLE_qwe_1569317415_2.JPG', 'qw 12 qw', 1569317415);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_category`
--
ALTER TABLE `group_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `store_gallery`
--
ALTER TABLE `store_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_id` (`gallery_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_email` (`email`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `store_id` (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `group_category`
--
ALTER TABLE `group_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `store_gallery`
--
ALTER TABLE `store_gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `group_category`
--
ALTER TABLE `group_category`
  ADD CONSTRAINT `group_category_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `group_category_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`);

--
-- Constraints for table `store`
--
ALTER TABLE `store`
  ADD CONSTRAINT `store_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `store_gallery`
--
ALTER TABLE `store_gallery`
  ADD CONSTRAINT `store_gallery_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`id`),
  ADD CONSTRAINT `store_gallery_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`);

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `vehicle_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
