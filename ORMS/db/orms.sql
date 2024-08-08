-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2024 at 09:52 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orms`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_id` char(16) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_img` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_id`, `category_name`, `category_img`, `status`, `created_at`, `updated_at`) VALUES
(1, '6MDY4s', 'Pizza', 'assets/img/pizza.jpg', 1, '2024-08-07 16:45:00', '2024-08-07 16:45:00'),
(3, 'vm0QXk', 'Pasta', 'assets/img/One-Pot-Alfredo-Pasta-RECIPE-CARD2[1].jpg', 1, '2024-08-07 16:50:12', '2024-08-07 17:16:34'),
(4, 'udwrPn', 'Fries', 'assets/img/Crispy-Fries_8[2].jpg', 1, '2024-08-07 18:15:03', '2024-08-07 18:15:20'),
(5, 'KhjcQN', 'Soup', 'assets/img/hot-and-sour-chicken-soup-1-500x375[1].jpg', 1, '2024-08-07 18:16:13', '2024-08-07 18:16:13'),
(6, 'ieOPR7', 'Sizzler', 'assets/img/Matar-Tikki-Chaat-Sizzler[1].jpg', 1, '2024-08-07 18:16:59', '2024-08-07 18:18:20'),
(7, 'bvXxM7', 'Breads', 'assets/img/cb21-organic-loaves-1_560x388[1].jpg', 1, '2024-08-07 18:19:06', '2024-08-07 18:19:06'),
(8, '1VUTrj', 'Desert', 'assets/img/e8bbfef4616d2bbee9a9f0cd3f35ded5[1].jpg', 1, '2024-08-07 18:19:42', '2024-08-07 18:19:42'),
(9, '3xCQXf', 'Shake', 'assets/img/DSC_110711[1].jpg', 1, '2024-08-07 18:20:40', '2024-08-07 18:20:40'),
(10, '1T4BNc', 'Coolers', 'assets/img/HD-wallpaper-blue-mojito-beverage-blue-drinks-food-green-healthy-black-wood-background-wodden-blur-ultra-high-definition-trending-popular-mint-leaves-mojito-orange-thumbnail[1].jpg', 1, '2024-08-07 18:22:03', '2024-08-07 18:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `id` int(11) NOT NULL,
  `order_id` char(255) NOT NULL,
  `user_id` text NOT NULL,
  `products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`products`)),
  `total_price` decimal(10,2) NOT NULL,
  `order_is_fulfilled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`id`, `order_id`, `user_id`, `products`, `total_price`, `order_is_fulfilled`, `created_at`, `updated_at`) VALUES
(1, 'ORDER_BAAB43849394', '1757b02c6ecf178c', '[{\"product_id\":\"P7rNnK5J8a\",\"quantity\":1},{\"product_id\":\"ZPk79QILV0\",\"quantity\":1},{\"product_id\":\"bgqFKa5wP0\",\"quantity\":2},{\"product_id\":\"rYkUlG6jL9\",\"quantity\":1},{\"product_id\":\"lcSei3hFOm\",\"quantity\":1},{\"product_id\":\"c3VtkihTaq\",\"quantity\":1},{\"product_id\":\"Yuk1xFCogJ\",\"quantity\":2},{\"product_id\":\"e4KnzSlw6f\",\"quantity\":2},{\"product_id\":\"HBGk28eWaF\",\"quantity\":1},{\"product_id\":\"rObgKIwe53\",\"quantity\":1}]', 3907.00, 1, '2024-08-07 19:37:07', '2024-08-07 19:39:15'),
(2, 'ORDER_3AE625155843', '1757b02c6ecf178c', '[{\"product_id\":\"tjWrwSEP4n\",\"quantity\":2},{\"product_id\":\"F4jSBPZoCk\",\"quantity\":3},{\"product_id\":\"TgeW8uX1iR\",\"quantity\":1},{\"product_id\":\"mvHIRcEhFL\",\"quantity\":2},{\"product_id\":\"HBGk28eWaF\",\"quantity\":2}]', 1870.00, 0, '2024-08-07 19:38:16', '2024-08-07 19:38:16'),
(3, 'ORDER_FBF7590ADA5A', '1757b02c6ecf178c', '[{\"product_id\":\"78ChD5mngV\",\"quantity\":1},{\"product_id\":\"yCOtzG0Vbh\",\"quantity\":1},{\"product_id\":\"HBGk28eWaF\",\"quantity\":1}]', 737.00, 1, '2024-08-07 19:41:58', '2024-08-07 19:50:06'),
(4, 'ORDER_630919106434', '39ef8a89defd6a5b', '[{\"product_id\":\"9HuWOfyJh1\",\"quantity\":1},{\"product_id\":\"qWYKjpQhN2\",\"quantity\":1},{\"product_id\":\"xO5jmPbVTy\",\"quantity\":1},{\"product_id\":\"rObgKIwe53\",\"quantity\":1}]', 786.00, 1, '2024-08-07 19:43:06', '2024-08-07 19:49:59'),
(5, 'ORDER_099BB3DEF22F', '39ef8a89defd6a5b', '[{\"product_id\":\"j0sav1CyON\",\"quantity\":1},{\"product_id\":\"mwNKqEncjC\",\"quantity\":1}]', 518.00, 0, '2024-08-07 19:45:28', '2024-08-07 19:45:28');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_id` char(16) NOT NULL,
  `product_img` varchar(255) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_description` text DEFAULT NULL,
  `product_category_id` char(16) NOT NULL,
  `isveg` tinyint(1) NOT NULL DEFAULT 0,
  `product_price` decimal(10,2) NOT NULL,
  `product_status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_id`, `product_img`, `product_name`, `product_description`, `product_category_id`, `isveg`, `product_price`, `product_status`, `created_at`, `updated_at`) VALUES
(1, 'ahjsfnPgFR', 'assets/img/20220211142754-margherita-9920[1].jpg', 'Margherita', 'A classic and simple pizza topped with a smooth tomato sauce, fresh mozzarella cheese, and basil leaves. Finished with a drizzle of olive oil and a touch of sea salt, it\'s a timeless favorite that celebrates the essence of Italian flavors.', '6MDY4s', 1, 499.00, 1, '2024-08-07 18:37:01', '2024-08-07 18:37:01'),
(2, 'tjWrwSEP4n', 'assets/img/20230108172302-1_1600x_92769e39-5808-444d-8223-89f27f9d7bcd[1].jpg', 'Indo Pesto', 'A unique fusion pizza featuring a vibrant pesto sauce made from fresh basil, cilantro, and mint, topped with mozzarella cheese, roasted vegetables, and a sprinkle of spicy paneer cubes. Finished with a drizzle of tangy tamarind sauce and a touch of chili flakes for a bold and flavorful twist on traditional pizza.', '6MDY4s', 1, 359.00, 1, '2024-08-07 18:38:04', '2024-08-07 18:38:04'),
(3, '8MIVRoFvBA', 'assets/img/CA-Grown-Olive-Pizza-Psrty-8723-copy[1].jpg', 'Italian Crust Olive', 'A pizza with a crispy, golden Italian crust enriched with the subtle flavor of olives baked right into the dough. Topped with a savory tomato sauce, fresh mozzarella cheese, and a generous scatter of sliced black and green olives, finished with a sprinkle of oregano and a drizzle of olive oil for an extra touch of Mediterranean charm.', '6MDY4s', 1, 499.00, 1, '2024-08-07 18:40:39', '2024-08-07 18:40:39'),
(4, '78ChD5mngV', 'assets/img/10[1].jpg', 'Paneer Makhani', 'A delicious fusion pizza featuring a creamy and tangy paneer makhani sauce as the base, topped with tender paneer cubes, bell peppers, onions, and a generous layer of melted mozzarella cheese. Finished with a sprinkle of fresh cilantro and a drizzle of makhani sauce for an indulgent blend of Indian flavors and classic pizza goodness.', '6MDY4s', 1, 399.00, 1, '2024-08-07 18:41:56', '2024-08-07 18:41:56'),
(5, 'P7rNnK5J8a', 'assets/img/princestreetpizzalasvegas5[1].jpg', 'Las Vegas Street', 'An exciting and flavorful pizza featuring a blend of gourmet toppings inspired by the vibrant street food of Las Vegas. Topped with succulent grilled chicken, spicy jalapeños, crispy bacon, and a mix of colorful bell peppers, all layered over a rich tomato sauce and melted cheese. Finished with a drizzle of tangy barbecue sauce and a sprinkle of fresh herbs for a taste of the city\'s lively culinary scene.', '6MDY4s', 0, 699.00, 1, '2024-08-07 18:43:11', '2024-08-07 18:43:11'),
(6, '9HuWOfyJh1', 'assets/img/Red-Sauce-Pasta-008[1].jpg', 'Red Sauce', 'A classic and comforting pasta dish featuring perfectly cooked pasta tossed in a rich, tangy tomato sauce. The sauce is seasoned with garlic, onions, and Italian herbs, creating a flavorful and aromatic base. Finished with a sprinkle of Parmesan cheese and fresh basil for a deliciously satisfying meal.', 'vm0QXk', 1, 299.00, 1, '2024-08-07 18:44:31', '2024-08-07 18:44:31'),
(7, 'DVaSOh1n6m', 'assets/img/white-sauce-pasta-2[1].jpg', 'White Sauce ', 'A creamy and indulgent pasta dish with perfectly cooked pasta enveloped in a rich white sauce made from butter, cream, and Parmesan cheese. Infused with a touch of garlic and nutmeg, and garnished with fresh herbs, this dish offers a smooth and velvety texture that\'s both comforting and elegant.', 'vm0QXk', 1, 329.00, 1, '2024-08-07 18:45:56', '2024-08-07 18:45:56'),
(8, 'ZPk79QILV0', 'assets/img/Spaghetti-arrabbiata-eb58aa1[1].jpg', 'Arrabiata', 'A spicy and flavorful pasta dish featuring a zesty tomato sauce infused with garlic, red chili flakes, and a hint of olive oil. The pasta is tossed in this fiery sauce, offering a bold and tangy kick, and is garnished with fresh parsley and a sprinkle of Parmesan cheese for a perfectly balanced and satisfying meal.', 'vm0QXk', 1, 359.00, 1, '2024-08-07 18:47:14', '2024-08-07 18:47:40'),
(9, 'F4jSBPZoCk', 'assets/img/crispy-classic-french-friesjpg[1].jpg', 'Classic', 'Crispy, golden-brown fries made from freshly cut potatoes, seasoned lightly with salt. These fries offer a perfect balance of crunch on the outside and tender on the inside, served hot for a timeless, satisfying snack or side dish.', 'udwrPn', 1, 89.00, 1, '2024-08-07 18:51:36', '2024-08-07 18:53:10'),
(10, 'qsAeXiyD5t', 'assets/img/peri-peri[1].jpg', 'Peri Peri', 'Crispy fries seasoned with a spicy peri peri blend, delivering a flavorful kick with each bite. The zesty and tangy peri peri seasoning is complemented by a touch of garlic and paprika, making these fries a bold and satisfying choice.', 'udwrPn', 1, 129.00, 1, '2024-08-07 18:52:36', '2024-08-07 18:52:36'),
(11, 'bgqFKa5wP0', 'assets/img/1_GASYbyKBhQL3rpUWtanZoA[1].jpeg', 'Cheesy', 'Golden, crispy fries generously topped with melted cheese, creating a gooey and indulgent treat. Finished with a sprinkle of chives or fresh herbs, and optionally drizzled with a savory cheese sauce for an extra layer of richness.', 'udwrPn', 1, 169.00, 1, '2024-08-07 18:54:08', '2024-08-07 18:54:08'),
(12, 'rYkUlG6jL9', 'assets/img/Roasted-tomato-garlic-soup.jpg', 'Tomato', 'A comforting and classic soup made with ripe tomatoes, onions, and garlic, simmered to perfection and blended into a smooth, velvety texture. Enhanced with a touch of basil and a hint of cream, it’s served hot with a drizzle of olive oil and a sprinkle of fresh herbs for a warm and satisfying bowl.', 'KhjcQN', 1, 139.00, 1, '2024-08-07 18:56:31', '2024-08-07 18:56:31'),
(13, 'qWYKjpQhN2', 'assets/img/Hot-and-Sour-Soup-3[1].jpg', 'Hot And Sour', 'A flavorful and tangy soup that combines spicy and sour notes with a rich broth. Features ingredients like tofu, mushrooms, bamboo shoots, and black vinegar, with a touch of white pepper for heat. Garnished with scallions and cilantro for a refreshing and balanced taste experience.', 'KhjcQN', 1, 159.00, 1, '2024-08-07 18:57:58', '2024-08-07 18:57:58'),
(14, 'yCOtzG0Vbh', 'assets/img/lemon-coriander-soup-recipe[1].jpg', 'Lemon Coriander', 'A light and refreshing soup with a zesty lemon flavor and aromatic coriander. The broth is infused with fresh lemon juice and coriander leaves, complemented by a mix of vegetables and a hint of ginger. Served hot with a garnish of fresh coriander and a squeeze of lemon for a bright, invigorating flavor.', 'KhjcQN', 1, 189.00, 1, '2024-08-07 18:58:45', '2024-08-07 18:58:45'),
(15, 'k7RBvpfVXe', 'assets/img/Italian_Pasta_Sizzler[1].jpg', 'Italian', 'A sizzling dish featuring a vibrant mix of Italian flavors, including juicy grilled chicken or vegetables, served on a hot plate with a bed of sautéed vegetables and a rich tomato or cream-based sauce. Accompanied by a side of garlic bread or pasta, and garnished with fresh herbs and a sprinkle of Parmesan cheese, this dish delivers a delightful combination of sizzling heat and Italian flair.', 'ieOPR7', 1, 459.00, 1, '2024-08-07 19:00:06', '2024-08-07 19:00:38'),
(16, 'qt420sdlu6', 'assets/img/Paneer_Sizzler_in_Chilli_Garlic_Sauce_with_Rice[1].jpg', 'Mexican', 'A sizzling plate featuring a hearty mix of Mexican flavors, including spiced grilled chicken or beef, sautéed bell peppers, onions, and zucchini. Served with a side of savory Mexican rice, refried beans, and warm tortillas. Garnished with fresh cilantro, a squeeze of lime, and a dollop of sour cream for a vibrant and flavorful experience.', 'ieOPR7', 1, 499.00, 1, '2024-08-07 19:01:39', '2024-08-07 19:01:39'),
(17, 'K0eEkpGVQW', 'assets/img/Chicken-Sizzler[1].jpg', 'Thai', 'A flavorful and aromatic dish served on a sizzling hot plate, featuring a blend of Thai ingredients such as tender grilled chicken or shrimp, bell peppers, onions, and snap peas. Tossed in a savory Thai sauce with hints of lemongrass, ginger, and chili, and accompanied by fragrant jasmine rice or noodles. Garnished with fresh basil, cilantro, and a sprinkle of crushed peanuts for a deliciously spicy and authentic Thai experience.', 'ieOPR7', 0, 699.00, 1, '2024-08-07 19:03:15', '2024-08-07 19:03:15'),
(18, 'lcSei3hFOm', 'assets/img/4bd08e1dade74eb05051de15bf767c9a[1].jpeg', 'Singh', 'A robust and hearty Punjabi-inspired sizzler featuring a variety of spiced and grilled meats such as tender chicken tikka, succulent lamb chops, and juicy kebabs. Served on a sizzling hot plate with a generous portion of sautéed vegetables, accompanied by a side of fragrant basmati rice or naan bread. Garnished with fresh coriander, a drizzle of tangy mint chutney, and a wedge of lemon for a flavorful and authentic Punjabi dining experience.', 'ieOPR7', 0, 759.00, 1, '2024-08-07 19:04:34', '2024-08-07 19:04:34'),
(19, 'dCbc3jUYrB', 'assets/img/21080-great-garlic-bread-DDMFS-4x3-e1c7b5c79fde4d629a9b7bce6c0702ed[1].jpg', 'Classic Garlic', 'Crispy, toasted slices of bread spread with a generous layer of buttery garlic herb mixture. Infused with minced garlic, fresh parsley, and a hint of salt, this savory bread is baked until golden brown, offering a perfect blend of crunchy texture and rich, aromatic flavor. Ideal as a side or appetizer.', 'bvXxM7', 1, 129.00, 1, '2024-08-07 19:06:23', '2024-08-07 19:06:23'),
(20, 'TgeW8uX1iR', 'assets/img/DSC_0297[1].jpg', 'Chees Garlic', 'A delicious twist on classic garlic bread, featuring crispy toasted slices topped with a blend of melted cheeses and a rich garlic herb butter. Infused with minced garlic and fresh parsley, and finished with a gooey layer of melted mozzarella and Parmesan, this savory bread combines the perfect balance of creamy, cheesy goodness with aromatic garlic for an irresistible treat.', 'bvXxM7', 1, 249.00, 1, '2024-08-07 19:15:57', '2024-08-07 19:15:57'),
(21, 'c3VtkihTaq', 'assets/img/Tomato-Bruschetta-2[1].jpg', 'Bruschetta', 'A classic Italian appetizer featuring toasted slices of crusty baguette topped with a fresh mixture of diced tomatoes, basil, garlic, and olive oil. Lightly seasoned with salt and pepper, and drizzled with a balsamic glaze, this flavorful and vibrant dish offers a delightful combination of crispy bread and refreshing, tangy toppings.', 'bvXxM7', 1, 189.00, 1, '2024-08-07 19:17:01', '2024-08-07 19:17:01'),
(22, 'NECV2xoAM3', 'assets/img/black-forest-ice-cream-2[1].jpg', 'Sundae', 'A classic and indulgent dessert featuring a scoop of creamy vanilla ice cream topped with a generous drizzle of rich chocolate or caramel sauce. Garnished with a dollop of whipped cream, a cherry on top, and a sprinkle of nuts or sprinkles for a perfect combination of textures and flavors.', '1VUTrj', 1, 179.00, 1, '2024-08-07 19:18:39', '2024-08-07 19:18:39'),
(23, 'j0sav1CyON', 'assets/img/blueberry-and-custard-pancakes-with-caramel-sauce-104431-1[1].jpg', 'Caramel Pancake ', ' Fluffy, golden-brown pancakes drizzled with a luscious, warm caramel sauce. Topped with a dollop of whipped cream, a sprinkle of sea salt, and a few caramelized nuts for added texture. Served with a side of fresh fruit or a scoop of vanilla ice cream for a decadent and satisfying treat.', '1VUTrj', 1, 289.00, 1, '2024-08-07 19:20:00', '2024-08-07 19:20:17'),
(24, 'Yuk1xFCogJ', 'assets/img/rum-balls-scaled[1].jpg', 'Sweep Balls', 'Delightful bite-sized treats made from a sweet, sticky mixture of ingredients like condensed milk, coconut, or chocolate, rolled into small balls and coated with powdered sugar or crushed nuts. These sweet balls offer a satisfying burst of flavor and texture in every bite, perfect for a quick, indulgent snack or dessert.', '1VUTrj', 1, 339.00, 1, '2024-08-07 19:22:29', '2024-08-07 19:22:29'),
(25, 'mvHIRcEhFL', 'assets/img/Chocolate-Pudding_Recipe_2023-08-10_1286_3x2[1].jpg', 'Pudding', 'A creamy and smooth dessert made from a rich blend of milk, sugar, and vanilla, cooked until it reaches a velvety consistency. Served chilled, it can be topped with a variety of delicious options like whipped cream, fresh fruit, or a sprinkle of chocolate shavings for a classic and comforting treat.', '1VUTrj', 1, 169.00, 1, '2024-08-07 19:23:43', '2024-08-07 19:23:43'),
(26, 'xO5jmPbVTy', 'assets/img/0360c05e27f88e9589f01bb951c299d6[1].jpg', 'Kit-Kat', 'A decadent milkshake blended with creamy vanilla ice cream and chunks of Kit-Kat bars, creating a rich and chocolatey treat. Topped with whipped cream, drizzled with chocolate syrup, and garnished with extra Kit-Kat pieces for a deliciously indulgent experience.', '3xCQXf', 1, 189.00, 1, '2024-08-07 19:25:33', '2024-08-07 19:25:33'),
(27, 'e4KnzSlw6f', 'assets/img/ferrero-rocher-nutella-milkshake-2.jpg', 'Ferrero Rocher', 'A luxurious milkshake made with creamy vanilla ice cream blended with crushed Ferrero Rocher chocolates, creating a rich, nutty flavor with hints of hazelnut and chocolate. Topped with whipped cream, drizzled with chocolate and hazelnut sauce, and garnished with whole Ferrero Rocher chocolates for an indulgent and delightful treat.', '3xCQXf', 1, 229.00, 1, '2024-08-07 19:27:43', '2024-08-07 19:27:43'),
(28, 'mwNKqEncjC', 'assets/img/4dce66a90db29e3294b38048922fd21c[1].jpg', 'Charcoal Black', 'A strikingly unique milkshake made with creamy vanilla or chocolate ice cream blended with activated charcoal, creating a rich, jet-black color. Enhanced with a touch of vanilla or chocolate syrup, and topped with whipped cream and a sprinkle of edible gold dust or a garnish of black sesame seeds for a visually stunning and deliciously smooth treat.', '3xCQXf', 1, 229.00, 1, '2024-08-07 19:29:04', '2024-08-07 19:29:04'),
(29, 'HBGk28eWaF', 'assets/img/virgin-mojito-recipe_2c958116f927e75831eacfcbf30c631d[1].jpeg', 'Virgin Mojito', 'A refreshing non-alcoholic drink made with muddled mint leaves, fresh lime juice, and a touch of sugar, topped with sparkling soda water. Served over ice and garnished with lime wedges and mint sprigs for a crisp and invigorating experience.', '1T4BNc', 1, 149.00, 1, '2024-08-07 19:30:25', '2024-08-07 19:30:25'),
(30, 'rObgKIwe53', 'assets/img/Sea-Breeze-Cocktail-Culinary-Hill-1200x800-1[1].jpg', 'Water Breeze', 'A refreshing and hydrating beverage featuring a blend of chilled water infused with a hint of citrus, such as lemon or lime, and a touch of mint. Served over ice, this light and invigorating drink is perfect for quenching thirst and providing a crisp, clean taste.', '1T4BNc', 1, 139.00, 1, '2024-08-07 19:32:07', '2024-08-07 19:32:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` char(16) NOT NULL,
  `contactnumber` varchar(15) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `contactnumber`, `fullname`, `password`, `is_admin`, `status`, `created_at`, `updated_at`) VALUES
(1, '76ffde11dd798153', '1122334455', 'Mirali Admin', '$2y$10$ipk59FuxmC45nbc.USjiLe2mtTkhhI1vV9cMyfonvepfn00kmdid6', 1, 1, '2024-08-06 18:18:47', '2024-08-07 19:40:48'),
(2, '1757b02c6ecf178c', '9988776655', 'Mirali', '$2y$10$gV0Lb9lV0OkiYSQ/Dl9mz.2QCvGVU8ys0kyYxkkZ29jyH39G89lFW', 0, 1, '2024-08-06 18:20:56', '2024-08-07 19:40:03'),
(3, '39ef8a89defd6a5b', '2233445566', 'Janvi', '$2y$10$E5fFDhrt4etNYT9mvGl7G.bMvvXEo8678hp7HRAa8U5FiWsoQjTWm', 0, 1, '2024-08-07 19:42:40', '2024-08-07 19:42:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_id` (`category_id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `product_category_id` (`product_category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`product_category_id`) REFERENCES `category` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
