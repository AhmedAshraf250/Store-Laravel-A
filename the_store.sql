-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2025 at 12:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `the_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','archived') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'Clothes', 'clothes', 'Clothes Clothes ClothesClothes\r\nClothes Clothes', 'uploads/2pAeNM92WIW9BG1vNVsyq6q9tdevNJOlYTebUeCJ.jpg', 'active', '2024-05-25 11:10:33', '2024-06-05 09:19:18', NULL),
(2, 1, 'Baby Clothes', 'baby-clothes', 'Baby Clothes\r\nBaby Clothes\r\nedit 1\r\nBaby Clothes', 'uploads/wzq2XV6DUV0TO3mbpIyVdD5mVMuKOEKvkXR8LgkT.jpg', 'active', '2024-05-25 11:23:42', '2024-06-05 09:18:45', NULL),
(3, 2, 'boys', 'boys', 'Boys Baby Clothes\r\nBoys Baby Clothes', NULL, 'active', '2024-05-26 17:01:01', '2024-05-26 17:01:01', NULL),
(11, NULL, 'Fooods', 'foods', 'Foods\r\nFoods', 'uploads/j0PxDS2smiNZDbIt7Opv0HHOgdZWqCuSmUxNXWb0.png', 'archived', '2024-05-28 08:54:17', '2024-06-14 15:34:47', NULL),
(18, NULL, 'est at', 'est-at', 'Repellendus et et nemo quia quam inventore ut velit nihil et alias animi laudantium fuga rerum sapiente rerum dolorem totam.', 'https://via.placeholder.com/640x480.png/00bbcc?text=consequatur', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL),
(19, NULL, 'mollitia totam', 'mollitia-totam', 'Accusamus quis eveniet quo error ut neque minima delectus consequuntur quo culpa veniam.', 'https://via.placeholder.com/640x480.png/001166?text=aut', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL),
(20, NULL, 'expedita ut', 'expedita-ut', 'Rem et cupiditate adipisci doloribus quis debitis repudiandae inventore voluptas aliquid vel nostrum sint eius amet.', 'https://via.placeholder.com/640x480.png/00bb33?text=voluptas', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL),
(21, NULL, 'labore temporibus', 'labore-temporibus', 'Magnam laborum sunt harum in nam doloribus ab excepturi ut animi.', 'https://via.placeholder.com/640x480.png/009955?text=facere', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL),
(22, NULL, 'adipisci iste', 'adipisci-iste', 'Et magnam aut exercitationem sunt autem vel vitae ut debitis dolor distinctio fuga reprehenderit et optio dignissimos iure veritatis hic.', 'https://via.placeholder.com/640x480.png/00ddaa?text=aut', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL),
(23, NULL, 'quis possimus', 'quis-possimus', 'Enim sunt odio necessitatibus repellat velit aspernatur nesciunt vel quos doloremque amet fugiat.', 'https://via.placeholder.com/640x480.png/001100?text=quo', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL),
(24, NULL, 'dolores ab', 'dolores-ab', 'Velit id sequi molestiae rerum dolorum sapiente saepe qui dolores molestias aut.', 'https://via.placeholder.com/640x480.png/00aadd?text=ab', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL),
(25, NULL, 'ab occaecati', 'ab-occaecati', 'Quidem tenetur et enim autem et omnis cum quasi sint libero assumenda.', 'https://via.placeholder.com/640x480.png/00aa00?text=non', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL),
(26, NULL, 'vel nobis', 'vel-nobis', 'Sapiente suscipit eum cupiditate eveniet iusto quo aperiam iusto sed vitae et unde pariatur.', 'https://via.placeholder.com/640x480.png/00ddcc?text=laboriosam', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL),
(27, NULL, 'neque inventore', 'neque-inventore', 'Aliquam qui est eveniet assumenda repudiandae dolore sequi non debitis magnam debitis.', 'https://via.placeholder.com/640x480.png/006688?text=asperiores', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_05_23_115332_create_stores_table', 1),
(6, '2024_05_23_124541_create_categories_table', 2),
(7, '2024_05_24_031335_create_products_table', 2),
(8, '2024_06_05_105655_add_soft_deletes_to_categories_table', 3),
(9, '2024_06_05_155020_add_some_columns_to_products_table', 4),
(10, '2024_06_05_172020_add_store_id_to_users_table', 5),
(11, '2024_06_08_083621_create_profiles_table', 6),
(12, '2024_06_08_162048_create_tags_table', 7),
(13, '2024_06_08_162330_create_product_tag_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` double(8,2) NOT NULL DEFAULT 0.00,
  `compare_price` double(8,2) DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `rating` double(8,2) NOT NULL DEFAULT 0.00,
  `features` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','draft','archived') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `store_id`, `category_id`, `name`, `slug`, `description`, `image`, `price`, `compare_price`, `options`, `rating`, `features`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 2, 'labore id et animi odit', 'labore-id-et-animi-odit', 'Consequuntur quia unde magnam molestiae quia sed eveniet vel ratione tempora deserunt sed nulla.', 'https://via.placeholder.com/640x480.png/0022aa?text=vitae', 265.80, 829.30, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(2, 5, 19, 'molestiae expedita esse blanditiis odio', 'molestiae-expedita-esse-blanditiis-odio', 'Tempora atque enim rerum est qui dolore fugit ipsa dolores ab est corporis sed unde ea incidunt eum.', 'https://via.placeholder.com/640x480.png/004477?text=distinctio', 141.20, 799.10, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(3, 5, 24, 'laborum repellendus sapiente occaecati optio', 'laborum-repellendus-sapiente-occaecati-optio', 'Delectus aliquam iste ipsa qui inventore optio porro doloremque culpa perferendis.', 'https://via.placeholder.com/640x480.png/00aadd?text=vitae', 437.50, 559.60, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(4, 1, 24, 'dolore sit iure enim totam', 'dolore-sit-iure-enim-totam', 'Iste aperiam et explicabo porro vitae pariatur voluptatem voluptates vel et blanditiis quis autem voluptatem fugiat inventore inventore corrupti.', 'https://via.placeholder.com/640x480.png/0022bb?text=non', 82.50, 925.60, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(5, 4, 18, 'dignissimos aut ullam soluta sit', 'dignissimos-aut-ullam-soluta-sit', 'Laboriosam voluptas debitis incidunt consectetur a facilis ut quasi fugit animi maiores itaque quasi.', 'https://via.placeholder.com/640x480.png/005500?text=voluptatem', 386.20, 522.50, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(6, 3, 23, 'nihil aliquid ex est ut', 'nihil-aliquid-ex-est-ut', 'Est nisi repellat officia consequatur repudiandae velit iure officiis sit deleniti qui consequuntur.', 'https://via.placeholder.com/640x480.png/00ddcc?text=suscipit', 103.10, 648.90, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(7, 1, 19, 'facere quidem praesentium voluptas nisi', 'facere-quidem-praesentium-voluptas-nisi', 'Optio et consequatur sunt totam qui omnis ut impedit non voluptatem impedit dignissimos.', 'https://via.placeholder.com/640x480.png/00bb66?text=vel', 381.70, 921.50, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(8, 2, 11, 'recusandae quod magnam quis cumque', 'recusandae-quod-magnam-quis-cumque', 'Quo eos aliquid maiores harum qui non aut qui eos sint est unde molestiae sint consequatur impedit.', 'https://via.placeholder.com/640x480.png/00ff33?text=quas', 146.90, 671.90, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(9, 5, 20, 'laborum non impedit quos voluptatem', 'laborum-non-impedit-quos-voluptatem', 'Qui earum autem sunt rem et et accusantium dicta modi sapiente.', 'https://via.placeholder.com/640x480.png/0077cc?text=est', 384.30, 635.50, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(10, 2, 20, 'vel sed voluptate velit ut', 'vel-sed-voluptate-velit-ut', 'Eos sequi esse qui aut quia et a ut .', 'https://via.placeholder.com/640x480.png/009977?text=repellendus', 482.70, 733.90, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-09 09:56:18', NULL),
(11, 2, 22, 'beatae hic at ut dolor', 'beatae-hic-at-ut-dolor', 'Omnis aut aut ratione reiciendis dolores corrupti qui qui cum incidunt quaerat.', 'https://via.placeholder.com/640x480.png/006677?text=omnis', 36.90, 739.70, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(12, 4, 25, 'reprehenderit iste culpa cum minima', 'reprehenderit-iste-culpa-cum-minima', 'Quia suscipit fuga accusamus veritatis ipsum quod consectetur tempora ipsum voluptas est maiores a adipisci eius ipsam quam doloremque.', 'https://via.placeholder.com/640x480.png/0099aa?text=nemo', 201.00, 812.90, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(13, 3, 23, 'blanditiis soluta quas repudiandae voluptate', 'blanditiis-soluta-quas-repudiandae-voluptate', 'Et beatae earum quo saepe qui exercitationem vero sit vel ducimus voluptas quaerat enim reiciendis dolor ab minima laboriosam dolorum eaque.', 'https://via.placeholder.com/640x480.png/001155?text=quia', 11.30, 689.30, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(14, 3, 18, 'quisquam maxime et non quis', 'quisquam-maxime-et-non-quis', 'Omnis ab suscipit et modi harum eligendi aut et laboriosam amet quidem illo numquam non et sunt dolore necessitatibus.', 'https://via.placeholder.com/640x480.png/0011bb?text=itaque', 352.10, 555.40, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(15, 2, 1, 'et et facilis quasi temporibus', 'et-et-facilis-quasi-temporibus', 'Rerum a dignissimos impedit hic mollitia recusandae corporis repudiandae qui minima in.', 'https://via.placeholder.com/640x480.png/003333?text=nostrum', 473.00, 847.40, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(16, 2, 11, 'corporis dolore explicabo cumque quis', 'corporis-dolore-explicabo-cumque-quis', 'Et consectetur natus rerum reprehenderit rerum animi nam veniam autem aliquid libero sunt perferendis.', 'https://via.placeholder.com/640x480.png/00ee55?text=repellat', 489.10, 827.30, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(17, 4, 22, 'mollitia voluptate sed minus commodi', 'mollitia-voluptate-sed-minus-commodi', 'Dolores perspiciatis qui nisi quia et nihil unde aut sequi voluptas quasi consequuntur sint.', 'https://via.placeholder.com/640x480.png/009900?text=quia', 422.80, 775.50, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(18, 3, 22, 'tenetur et illo in minus', 'tenetur-et-illo-in-minus', 'Modi quo nemo corrupti delectus aut aut consequatur numquam et culpa sunt suscipit praesentium modi qui assumenda iusto aut.', 'https://via.placeholder.com/640x480.png/0044dd?text=quas', 413.30, 874.40, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(19, 3, 1, 'ut ullam ut ut non', 'ut-ullam-ut-ut-non', 'Inventore enim magni voluptatibus ut sunt doloremque et soluta temporibus voluptatem facere sed error sint.', 'https://via.placeholder.com/640x480.png/0000cc?text=numquam', 214.90, 967.70, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(20, 4, 27, 'et magni rerum sint tempora', 'et-magni-rerum-sint-tempora', 'Ut iste ratione ipsum nostrum quod quo dolore perferendis quibusdam maiores officia quo officia.', 'https://via.placeholder.com/640x480.png/00ccdd?text=et', 270.60, 791.30, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(21, 2, 26, 'in nihil vel deleniti velit', 'in-nihil-vel-deleniti-velit', 'Est at dolor minima molestiae non fuga perspiciatis dolorem adipisci quasi asperiores est beatae qui ex suscipit nihil quia porro.', 'https://via.placeholder.com/640x480.png/00cc55?text=qui', 182.60, 796.30, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(22, 4, 21, 'et dolorem blanditiis enim et', 'et-dolorem-blanditiis-enim-et', 'Amet laudantium dolor ratione tempora provident labore vel id voluptatem in deserunt culpa ea.', 'https://via.placeholder.com/640x480.png/0088bb?text=aut', 376.80, 936.90, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(23, 4, 20, 'sed deleniti voluptate aperiam quidem', 'sed-deleniti-voluptate-aperiam-quidem', 'Quis ducimus mollitia distinctio hic esse animi voluptates id minima veniam minus dolore ab soluta explicabo culpa id.', 'https://via.placeholder.com/640x480.png/00aaaa?text=facere', 385.40, 549.30, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(24, 2, 21, 'aut dicta fuga blanditiis dicta', 'aut-dicta-fuga-blanditiis-dicta', 'Eos quos est voluptas adipisci ipsa molestiae facilis et qui debitis culpa distinctio sit maiores corporis id facilis.', 'https://via.placeholder.com/640x480.png/007733?text=molestias', 252.20, 919.00, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(25, 2, 1, 'est sed et ab enim', 'est-sed-et-ab-enim', 'Modi et deserunt quam facere nisi voluptate repellendus quidem itaque quo laborum dicta eum quidem error doloribus tempore.', 'https://via.placeholder.com/640x480.png/001177?text=neque', 344.70, 678.10, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(26, 5, 18, 'aliquam id consequatur quo qui', 'aliquam-id-consequatur-quo-qui', 'Voluptatem voluptates quam fugiat placeat ut voluptate aspernatur tenetur soluta rerum illum rerum.', 'https://via.placeholder.com/640x480.png/003388?text=maxime', 177.30, 718.20, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(27, 4, 18, 'dolorum quia sunt vel provident', 'dolorum-quia-sunt-vel-provident', 'Omnis suscipit praesentium minima autem sit et enim ratione aut optio corrupti sed occaecati in quis similique sed.', 'https://via.placeholder.com/640x480.png/00eedd?text=nihil', 223.00, 792.10, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(28, 3, 2, 'harum dolorum cum optio doloremque', 'harum-dolorum-cum-optio-doloremque', 'Veniam molestias aut animi sed tempora aut qui aut rem exercitationem aut ea quae enim commodi esse.', 'https://via.placeholder.com/640x480.png/003388?text=eos', 172.20, 984.60, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(29, 4, 1, 'fuga et aut porro mollitia', 'fuga-et-aut-porro-mollitia', 'Consequuntur expedita nesciunt aut aut et cumque autem repellendus iste et est amet qui est voluptates.', 'https://via.placeholder.com/640x480.png/006655?text=quia', 329.40, 862.30, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(30, 5, 18, 'sequi aliquam quidem soluta doloribus', 'sequi-aliquam-quidem-soluta-doloribus', 'Sit impedit repudiandae eligendi commodi provident et sequi autem sit in nobis eos ullam id sunt itaque sequi.', 'https://via.placeholder.com/640x480.png/00ff33?text=id', 185.10, 637.00, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(31, 2, 11, 'itaque aut qui vel harum', 'itaque-aut-qui-vel-harum', 'Earum impedit voluptatem eligendi sed optio accusamus laboriosam nobis perferendis qui repellat ad.', 'https://via.placeholder.com/640x480.png/00cc55?text=molestias', 31.40, 556.50, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(32, 1, 3, 'molestiae illum sit debitis id', 'molestiae-illum-sit-debitis-id', 'Aut facere modi in minima voluptates nesciunt quia neque itaque non recusandae sit.', 'https://via.placeholder.com/640x480.png/00dd33?text=eligendi', 237.40, 913.20, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(33, 2, 24, 'magnam rerum praesentium rerum illum', 'magnam-rerum-praesentium-rerum-illum', 'Fuga est et est maxime consequatur sint in qui accusamus quibusdam omnis qui hic atque aliquam ut amet et.', 'https://via.placeholder.com/640x480.png/00ffaa?text=et', 153.10, 726.80, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(34, 4, 3, 'id architecto eveniet expedita distinctio', 'id-architecto-eveniet-expedita-distinctio', 'Ea nesciunt aspernatur tempora laboriosam iure veritatis perspiciatis dolorem officia non voluptatibus fuga eos sequi non.', 'https://via.placeholder.com/640x480.png/001122?text=fuga', 286.90, 982.00, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(35, 1, 19, 'soluta amet nihil illo amet', 'soluta-amet-nihil-illo-amet', 'Deserunt voluptatibus reiciendis non ad est aliquid minima qui eligendi.', 'https://via.placeholder.com/640x480.png/00ddcc?text=ratione', 259.20, 755.40, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(36, 5, 1, 'necessitatibus voluptatibus debitis et architecto', 'necessitatibus-voluptatibus-debitis-et-architecto', 'Recusandae voluptas qui commodi et tempore molestiae cumque sapiente sint quidem et ut autem commodi cumque.', 'https://via.placeholder.com/640x480.png/00ccff?text=magni', 367.00, 782.10, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(37, 2, 24, 'et quod officia nesciunt velit', 'et-quod-officia-nesciunt-velit', 'Et ab praesentium dolorem ducimus enim expedita quis corrupti dignissimos maxime sed voluptatem delectus et sed non rerum.', 'https://via.placeholder.com/640x480.png/0066dd?text=pariatur', 420.10, 805.70, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(38, 3, 21, 'recusandae aperiam maiores et ut', 'recusandae-aperiam-maiores-et-ut', 'Omnis ratione veniam consequatur omnis quis nisi quia vero ab et quibusdam et officia velit quia maiores tenetur soluta.', 'https://via.placeholder.com/640x480.png/004466?text=laborum', 198.10, 940.30, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(39, 3, 27, 'nam sit id consequatur quidem', 'nam-sit-id-consequatur-quidem', 'Repudiandae quae ut aut ipsum fugiat impedit a itaque earum ea quidem possimus.', 'https://via.placeholder.com/640x480.png/009955?text=animi', 414.20, 896.00, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(40, 1, 2, 'error veniam placeat quia autem', 'error-veniam-placeat-quia-autem', 'Aut sint est fugiat voluptas impedit enim quo recusandae nemo odio voluptatem assumenda rerum consequatur.', 'https://via.placeholder.com/640x480.png/00cc99?text=ipsa', 59.00, 610.50, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(41, 1, 25, 'non a vel est sunt', 'non-a-vel-est-sunt', 'Debitis fuga incidunt voluptas sit et quasi explicabo facilis blanditiis laudantium temporibus voluptates occaecati voluptas quo omnis consequatur.', 'https://via.placeholder.com/640x480.png/003355?text=sequi', 24.70, 675.20, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(42, 1, 11, 'reprehenderit et sint ipsum quo', 'reprehenderit-et-sint-ipsum-quo', 'In illo nesciunt magnam consequatur nisi qui dolore atque itaque consequatur est porro.', 'https://via.placeholder.com/640x480.png/001111?text=officiis', 17.80, 777.50, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(43, 5, 3, 'sunt odit rem voluptas odit', 'sunt-odit-rem-voluptas-odit', 'Officiis laboriosam cum nemo recusandae est et iusto cumque modi.', 'https://via.placeholder.com/640x480.png/0066ff?text=molestiae', 437.40, 987.80, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(44, 1, 1, 'neque nihil quia similique hic', 'neque-nihil-quia-similique-hic', 'Unde occaecati aut animi officia id impedit itaque quos rerum quisquam.', 'https://via.placeholder.com/640x480.png/00aaaa?text=voluptate', 475.20, 563.40, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(45, 2, 2, 'perferendis assumenda quos facere quia', 'perferendis-assumenda-quos-facere-quia', 'Ab ea quisquam dolores cumque laborum provident officia voluptatum doloribus nemo ex ipsam itaque necessitatibus qui blanditiis illo.', 'https://via.placeholder.com/640x480.png/008888?text=molestiae', 279.60, 680.80, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(46, 2, 1, 'facere amet sed quae sed', 'facere-amet-sed-quae-sed', 'Qui qui beatae consequatur et non molestiae ut veritatis repudiandae cumque non.', 'https://via.placeholder.com/640x480.png/008877?text=mollitia', 141.50, 940.60, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(47, 4, 25, 'sit mollitia ea tempora dolorum', 'sit-mollitia-ea-tempora-dolorum', 'Et itaque ut ut rem culpa ut enim labore sequi minus dolor eaque culpa quis distinctio iure mollitia.', 'https://via.placeholder.com/640x480.png/003322?text=aut', 320.30, 523.00, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(48, 4, 20, 'sint est laudantium consequatur et', 'sint-est-laudantium-consequatur-et', 'Id suscipit reiciendis qui eum quas sequi atque ut maxime similique officia blanditiis eos quibusdam perspiciatis et quo quae.', 'https://via.placeholder.com/640x480.png/0044aa?text=voluptatem', 420.30, 867.30, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(49, 1, 21, 'aut repudiandae vitae eius possimus', 'aut-repudiandae-vitae-eius-possimus', 'Repellendus voluptas voluptatum dolor ea mollitia quod quia hic quaerat.', 'https://via.placeholder.com/640x480.png/00ee22?text=consequatur', 32.50, 582.90, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(50, 1, 1, 'corrupti minus quis qui consectetur', 'corrupti-minus-quis-qui-consectetur', 'Consectetur aut sit eum dolorum harum placeat voluptas saepe alias explicabo est.', 'https://via.placeholder.com/640x480.png/00aa88?text=omnis', 328.80, 645.40, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(51, 3, 25, 'rem officiis magni aperiam rerum', 'rem-officiis-magni-aperiam-rerum', 'Sed autem commodi ut dolor consequatur a ut illo dolorem delectus atque quo.', 'https://via.placeholder.com/640x480.png/0055ff?text=doloremque', 453.20, 583.80, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(52, 3, 18, 'sit quis sequi quis sapiente', 'sit-quis-sequi-quis-sapiente', 'Dignissimos sed et sed id molestias quo maiores unde esse iure voluptate quibusdam.', 'https://via.placeholder.com/640x480.png/009966?text=perspiciatis', 371.90, 721.50, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(53, 5, 22, 'qui in est et eum', 'qui-in-est-et-eum', 'Ex officiis voluptatem incidunt ullam earum neque dignissimos debitis alias ad nam dicta iusto fuga rerum aliquam sunt.', 'https://via.placeholder.com/640x480.png/003388?text=ipsa', 145.10, 826.70, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(54, 3, 22, 'ea aut aut non est', 'ea-aut-aut-non-est', 'Nihil vel quasi et facilis officiis vel reprehenderit consequatur ut consequatur consequuntur.', 'https://via.placeholder.com/640x480.png/0033ff?text=sit', 42.20, 754.30, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(55, 1, 20, 'temporibus consectetur dignissimos sed tempore', 'temporibus-consectetur-dignissimos-sed-tempore', 'Qui illo ab aut in corrupti molestiae distinctio enim blanditiis aut mollitia quod consequatur corporis consequatur et.', 'https://via.placeholder.com/640x480.png/002211?text=quas', 387.60, 897.30, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(56, 5, 22, 'non architecto eos dignissimos et', 'non-architecto-eos-dignissimos-et', 'Nam doloribus ipsa minima et impedit expedita nostrum eum quod quibusdam odit inventore molestiae minus deleniti maxime.', 'https://via.placeholder.com/640x480.png/00aa88?text=et', 272.40, 834.10, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(57, 4, 1, 'natus ut quis aliquam voluptatem', 'natus-ut-quis-aliquam-voluptatem', 'Totam qui omnis autem recusandae quibusdam reprehenderit eveniet sunt facilis ea ducimus minima enim rerum est facilis.', 'https://via.placeholder.com/640x480.png/0044bb?text=quasi', 336.70, 907.10, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(58, 5, 2, 'labore id assumenda nemo dolor', 'labore-id-assumenda-nemo-dolor', 'Maxime repellendus occaecati animi ea perferendis expedita at omnis dolorum earum quod dolorum fugit laboriosam recusandae nihil nostrum.', 'https://via.placeholder.com/640x480.png/00ddcc?text=est', 494.90, 662.00, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(59, 5, 11, 'dolorem veritatis nesciunt dignissimos consequatur', 'dolorem-veritatis-nesciunt-dignissimos-consequatur', 'Ratione perspiciatis asperiores at ea omnis sint doloribus rem deserunt.', 'https://via.placeholder.com/640x480.png/00aaaa?text=et', 477.40, 998.70, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(60, 5, 21, 'praesentium temporibus ut autem officiis', 'praesentium-temporibus-ut-autem-officiis', 'Ex asperiores itaque voluptatum dicta voluptatibus et sed voluptas nesciunt nisi ratione.', 'https://via.placeholder.com/640x480.png/006688?text=esse', 175.00, 976.50, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(61, 2, 22, 'et aut eligendi eligendi ab', 'et-aut-eligendi-eligendi-ab', 'Consectetur culpa animi id ea nulla consectetur eum delectus non tempore quam et vero voluptatibus voluptatum aut aliquam sit accusantium.', 'https://via.placeholder.com/640x480.png/0066ff?text=commodi', 140.90, 690.40, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(62, 5, 19, 'ratione quo ut molestias quia', 'ratione-quo-ut-molestias-quia', 'Repudiandae voluptates perferendis quasi suscipit necessitatibus omnis mollitia voluptatibus rerum odit.', 'https://via.placeholder.com/640x480.png/005500?text=officiis', 377.30, 957.60, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(63, 4, 19, 'enim aut ipsum culpa suscipit', 'enim-aut-ipsum-culpa-suscipit', 'Non enim vero magnam officiis iure quidem sed et corrupti nihil sunt dolores sint alias autem sunt magnam sed non maiores.', 'https://via.placeholder.com/640x480.png/005522?text=possimus', 195.20, 745.40, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(64, 3, 25, 'modi libero cupiditate perferendis saepe', 'modi-libero-cupiditate-perferendis-saepe', 'Veniam dicta eius animi provident laborum ut quia vero cumque repudiandae illum consequatur consequatur facilis ea ut ipsum explicabo omnis.', 'https://via.placeholder.com/640x480.png/0088aa?text=qui', 356.90, 913.80, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(65, 5, 27, 'non enim nisi modi et', 'non-enim-nisi-modi-et', 'Nobis ab ut consequatur quam molestiae omnis ut quibusdam aut.', 'https://via.placeholder.com/640x480.png/0077aa?text=deserunt', 100.50, 779.50, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(66, 4, 27, 'enim inventore modi nostrum ut', 'enim-inventore-modi-nostrum-ut', 'Voluptas velit quis aperiam necessitatibus rerum consequatur hic cum velit numquam est provident perspiciatis mollitia atque.', 'https://via.placeholder.com/640x480.png/000033?text=quia', 497.70, 660.30, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(67, 4, 20, 'delectus dolorem ullam odio autem', 'delectus-dolorem-ullam-odio-autem', 'A illum dolor autem animi ea dolorem laborum consequatur eos dolor qui doloribus et facere nemo reprehenderit odio itaque.', 'https://via.placeholder.com/640x480.png/00ee99?text=harum', 238.10, 568.70, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(68, 3, 20, 'officiis expedita aperiam unde minus', 'officiis-expedita-aperiam-unde-minus', 'Quas quis nisi sed voluptate repudiandae fugiat sequi eligendi incidunt perspiciatis quisquam et adipisci.', 'https://via.placeholder.com/640x480.png/002299?text=ut', 444.70, 875.80, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(69, 1, 25, 'quasi aspernatur et id veniam', 'quasi-aspernatur-et-id-veniam', 'Est laboriosam qui et quibusdam veniam minima aut excepturi omnis est debitis.', 'https://via.placeholder.com/640x480.png/0022cc?text=eos', 496.10, 926.70, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(70, 2, 24, 'doloribus est eum excepturi in', 'doloribus-est-eum-excepturi-in', 'Soluta sed voluptatum praesentium quos ducimus magnam et earum iure non asperiores reprehenderit excepturi nisi quod sapiente est cupiditate ipsam.', 'https://via.placeholder.com/640x480.png/000088?text=quia', 44.10, 967.10, NULL, 0.00, 1, 'draft', '2024-06-05 14:23:09', '2024-06-09 09:56:55', NULL),
(71, 4, 1, 'rerum iure amet quae qui', 'rerum-iure-amet-quae-qui', 'Et consequatur alias molestiae qui itaque est commodi rerum ipsam.', 'https://via.placeholder.com/640x480.png/00ddaa?text=sit', 358.00, 902.90, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(72, 3, 26, 'consectetur quisquam in et est', 'consectetur-quisquam-in-et-est', 'Veniam qui voluptate qui optio et quo non eos non.', 'https://via.placeholder.com/640x480.png/00ee66?text=non', 221.10, 815.30, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(73, 1, 2, 'a rem quisquam rem sint', 'a-rem-quisquam-rem-sint', 'Magnam culpa nobis quia accusamus in dolores sit praesentium odit aut distinctio non voluptatem deserunt et et animi molestias.', 'https://via.placeholder.com/640x480.png/00aa11?text=veritatis', 358.20, 799.60, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(74, 2, 11, 'et consectetur alias quo ab', 'et-consectetur-alias-quo-ab', 'Hic in et eos mollitia consequatur aut et expedita officia officia totam tempora ea exercitationem.', 'https://via.placeholder.com/640x480.png/00cc55?text=facere', 63.40, 986.00, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(75, 2, 2, 'ex magnam ex sequi doloremque', 'ex-magnam-ex-sequi-doloremque', 'Voluptatem nesciunt corrupti quo totam fuga et ipsam sed qui ea provident similique totam dicta.', 'https://via.placeholder.com/640x480.png/00aa33?text=ducimus', 297.50, 965.10, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(76, 3, 26, 'ducimus ex praesentium et cumque', 'ducimus-ex-praesentium-et-cumque', 'Nesciunt ex velit assumenda a doloribus a dolor praesentium est molestiae aspernatur dolorum distinctio aut minima error optio ut.', 'https://via.placeholder.com/640x480.png/00cc33?text=quas', 113.70, 580.00, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(77, 4, 1, 'laudantium repellendus est ut vel', 'laudantium-repellendus-est-ut-vel', 'Ab consequatur voluptatum id pariatur vero praesentium officia recusandae consequatur praesentium dolores qui qui.', 'https://via.placeholder.com/640x480.png/0033bb?text=ea', 109.90, 541.40, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(78, 3, 22, 'quia consectetur minus omnis consectetur', 'quia-consectetur-minus-omnis-consectetur', 'Dolorum deserunt illum ipsum exercitationem sequi rerum nostrum ut enim provident perferendis sed et eos esse.', 'https://via.placeholder.com/640x480.png/0033dd?text=dolores', 110.60, 748.00, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(79, 2, 11, 'fugiat voluptatum et omnis et', 'fugiat-voluptatum-et-omnis-et', 'Qui velit rerum dolorem labore quaerat consequuntur sapiente et asperiores rerum dolorem a iste consectetur eum.', 'https://via.placeholder.com/640x480.png/0055cc?text=iusto', 125.90, 587.10, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(80, 4, 27, 'velit veritatis architecto enim illum', 'velit-veritatis-architecto-enim-illum', 'Animi illo omnis nisi vero facilis doloribus ut vero deleniti harum quisquam cumque distinctio officia molestiae aperiam.', 'https://via.placeholder.com/640x480.png/0033bb?text=quos', 72.50, 757.80, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(81, 3, 23, 'incidunt unde explicabo aut voluptatem', 'incidunt-unde-explicabo-aut-voluptatem', 'Aut sed aperiam distinctio vel neque dolorum illum culpa fugit excepturi rerum maiores et facere sint qui.', 'https://via.placeholder.com/640x480.png/004455?text=veritatis', 432.70, 890.40, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(82, 5, 22, 'veniam et sit repudiandae et', 'veniam-et-sit-repudiandae-et', 'Quibusdam voluptas expedita corporis culpa veritatis omnis amet pariatur perspiciatis et.', 'https://via.placeholder.com/640x480.png/00ff22?text=qui', 250.30, 727.60, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(83, 4, 25, 'sequi soluta eum ut nulla', 'sequi-soluta-eum-ut-nulla', 'Velit fugiat doloremque aut aut eos corrupti esse provident dolorum optio optio mollitia officiis eius.', 'https://via.placeholder.com/640x480.png/00bb77?text=ipsam', 492.80, 756.00, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(84, 1, 22, 'sit eligendi molestiae fugit ipsam', 'sit-eligendi-molestiae-fugit-ipsam', 'Sit et ut alias repellat recusandae fuga debitis possimus molestias nostrum.', 'https://via.placeholder.com/640x480.png/008800?text=ut', 440.60, 824.20, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(85, 5, 21, 'est vitae fuga consequatur repudiandae', 'est-vitae-fuga-consequatur-repudiandae', 'Harum consequatur tenetur quas magnam explicabo aspernatur voluptatibus ullam praesentium consequuntur.', 'https://via.placeholder.com/640x480.png/00ff55?text=accusamus', 427.10, 991.70, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(86, 5, 22, 'earum voluptas qui est temporibus', 'earum-voluptas-qui-est-temporibus', 'Iusto enim vel odit corrupti voluptatum voluptas et dolores deleniti ratione nesciunt omnis consequatur alias non.', 'https://via.placeholder.com/640x480.png/00bb00?text=sed', 448.60, 978.70, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(87, 3, 18, 'quo consequatur distinctio magni illo', 'quo-consequatur-distinctio-magni-illo', 'Ab reprehenderit est deleniti et deserunt aut aut nihil voluptas dolorum et possimus reiciendis ut at beatae earum blanditiis.', 'https://via.placeholder.com/640x480.png/00ff77?text=quasi', 402.90, 529.10, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(88, 1, 24, 'distinctio ipsam eum explicabo fugit', 'distinctio-ipsam-eum-explicabo-fugit', 'Sunt modi iste modi autem qui nihil in sint reiciendis sit recusandae autem id nemo veritatis.', 'https://via.placeholder.com/640x480.png/006655?text=molestiae', 214.90, 923.90, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(89, 2, 27, 'occaecati velit vitae molestiae fuga', 'occaecati-velit-vitae-molestiae-fuga', 'Aperiam id vero suscipit delectus dolorem suscipit laudantium ratione nemo voluptatem omnis voluptas voluptatem veniam quam quas nemo voluptatibus.', 'https://via.placeholder.com/640x480.png/009944?text=nemo', 467.80, 714.20, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(90, 2, 1, 'eveniet quibusdam voluptas illo voluptatem', 'eveniet-quibusdam-voluptas-illo-voluptatem', 'Dolores eius cum sint nulla est ut ut quia vel voluptatem.', 'https://via.placeholder.com/640x480.png/0033ff?text=autem', 351.20, 870.30, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(91, 1, 1, 'incidunt debitis et recusandae quisquam', 'incidunt-debitis-et-recusandae-quisquam', 'Voluptatem eos corrupti consequatur vel reiciendis optio porro suscipit incidunt vitae.', 'https://via.placeholder.com/640x480.png/00aa88?text=quam', 212.50, 695.80, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(92, 2, 21, 'autem asperiores commodi odit rerum', 'autem-asperiores-commodi-odit-rerum', 'Praesentium aut corporis dolorum natus consequatur voluptatem recusandae eligendi similique sed sit aut velit iste officiis recusandae.', 'https://via.placeholder.com/640x480.png/000077?text=et', 271.70, 604.10, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(93, 5, 25, 'dolor aut officiis illum ut', 'dolor-aut-officiis-illum-ut', 'Molestias quia voluptatibus itaque et suscipit dolores rerum quibusdam corporis molestiae nulla corporis vero et quisquam voluptas possimus optio voluptates.', 'https://via.placeholder.com/640x480.png/0011dd?text=voluptas', 398.40, 723.10, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(94, 1, 24, 'rerum fugit sint quia odit', 'rerum-fugit-sint-quia-odit', 'Officiis fugiat quis sequi tempora in at dolorem ipsam esse optio.', 'https://via.placeholder.com/640x480.png/0033cc?text=velit', 406.90, 946.10, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(95, 2, 26, 'voluptas consequatur cum at nihil', 'voluptas-consequatur-cum-at-nihil', 'Dicta in aperiam sed placeat distinctio non ut aliquam officia excepturi voluptatem.', 'https://via.placeholder.com/640x480.png/0033ee?text=consequatur', 34.80, 785.10, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(96, 4, 22, 'totam hic id illum vitae', 'totam-hic-id-illum-vitae', 'Ipsum dignissimos consequuntur eveniet mollitia est temporibus reiciendis laboriosam odit et quasi molestiae dolor qui maiores.', 'https://via.placeholder.com/640x480.png/001155?text=odit', 493.30, 855.70, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(97, 2, 20, 'provident magnam inventore sed hic', 'provident-magnam-inventore-sed-hic', 'Non et nulla modi quas voluptatem doloribus rerum quo quis sapiente corrupti consectetur qui distinctio quia delectus et.', 'https://via.placeholder.com/640x480.png/0033dd?text=libero', 475.70, 659.10, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(98, 2, 22, 'dignissimos consequatur non amet suscipit', 'dignissimos-consequatur-non-amet-suscipit', 'Ut ipsa nulla quo commodi aut sit at quod sint explicabo ratione facere enim qui quia quia aut.', 'https://via.placeholder.com/640x480.png/0022ff?text=vitae', 107.10, 526.50, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(99, 1, 2, 'non nobis qui officiis ipsum', 'non-nobis-qui-officiis-ipsum', 'Officiis aut expedita nesciunt incidunt tenetur quidem est nihil quaerat.', 'https://via.placeholder.com/640x480.png/00ff99?text=et', 191.00, 925.40, NULL, 0.00, 1, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL),
(100, 5, 3, 'neque voluptatibus autem at velit', 'neque-voluptatibus-autem-at-velit', 'Deleniti vel perferendis amet omnis omnis quidem sunt nemo et inventore et ut harum mollitia est mollitia qui et eum quidem alias.', 'https://via.placeholder.com/640x480.png/007755?text=aut', 440.40, 852.70, NULL, 0.00, 0, 'active', '2024-06-05 14:23:09', '2024-06-05 14:23:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_tag`
--

CREATE TABLE `product_tag` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_tag`
--

INSERT INTO `product_tag` (`product_id`, `tag_id`) VALUES
(8, 1),
(8, 5),
(10, 2),
(10, 3),
(61, 2),
(70, 4);

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` char(2) NOT NULL,
  `locale` char(2) NOT NULL DEFAULT 'en',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`user_id`, `first_name`, `last_name`, `birthday`, `gender`, `street_address`, `city`, `state`, `postal_code`, `country`, `locale`, `created_at`, `updated_at`) VALUES
(1, 'Ahmed', 'Ashraf', '1994-09-25', 'male', 'al-kashief', 'damietta', 'al-zarqa', '1000', 'EG', 'ar', '2024-06-08 13:15:41', '2024-06-08 13:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `logo_image` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `slug`, `description`, `logo_image`, `cover_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ipsam sapiente', 'ipsam-sapiente', 'Et voluptatem corrupti ad ut et quo et eligendi eligendi et similique possimus dolorem adipisci.', 'https://via.placeholder.com/90000x480.png/00ee99?text=cupiditate', 'https://via.placeholder.com/480000x480.png/008800?text=eos', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07'),
(2, 'quis eaque', 'quis-eaque', 'Nostrum rerum sit voluptatem aperiam voluptas quam sit sapiente quas iure enim esse aut voluptatem voluptatem.', 'https://via.placeholder.com/90000x480.png/00bb66?text=veniam', 'https://via.placeholder.com/480000x480.png/000033?text=rerum', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07'),
(3, 'porro sunt', 'porro-sunt', 'Id voluptatem quia amet repudiandae et beatae qui architecto sunt vel unde a ut quisquam eum voluptatibus vitae in sint debitis.', 'https://via.placeholder.com/90000x480.png/0011ff?text=officiis', 'https://via.placeholder.com/480000x480.png/005511?text=nesciunt', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07'),
(4, 'dolorem incidunt', 'dolorem-incidunt', 'Qui provident voluptatem veniam molestiae aut adipisci debitis consequatur necessitatibus consequatur excepturi tempora voluptatem et repellendus ipsum enim voluptas.', 'https://via.placeholder.com/90000x480.png/002255?text=modi', 'https://via.placeholder.com/480000x480.png/0088ff?text=debitis', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07'),
(5, 'maxime quas', 'maxime-quas', 'Eos labore expedita nulla ex repellendus facilis laudantium eos atque optio.', 'https://via.placeholder.com/90000x480.png/0077aa?text=est', 'https://via.placeholder.com/480000x480.png/004466?text=ut', 'active', '2024-06-05 14:23:07', '2024-06-05 14:23:07');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`) VALUES
(1, 'cotton', 'cotton'),
(2, 'test', 'test'),
(3, 'hey', 'hey'),
(4, 'fastest', 'fastest'),
(5, 'fiber', 'fiber');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone_number`, `store_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ahmed Ashraf', 'ahmed@mail.com', NULL, '$2y$10$kfo2uHi8G4erzHYUn4MwPeOCqwm6q4gYzRxiAoYiUlhNL.A5lQ/6q', '201060701145', 2, '74uK4gI11X5IcAJfhmaKog8FnpwLluD9Hlgfc288hUbXnxMcY5axubVTbCKr', '2024-05-24 00:43:18', '2024-05-24 00:43:18'),
(2, 'ghaith', 'ghaith@mail.com', NULL, '$2y$10$UZt7v7jNQXAqp24UB6f1YepcwFkynQzhkd.WRabsL2BqutN2JAD7.', '201060702525', 3, NULL, '2024-05-24 00:43:18', '2024-05-24 00:43:18'),
(3, 'ahmed2', 'ahmed2@mail.com', NULL, '$2y$10$xdp2.YxsY6leXJSlD6jj.eR1cZWa8eQIpjuEhxp26o5hkt9V8/gda', NULL, NULL, NULL, '2024-06-05 18:25:14', '2024-06-05 18:25:14'),
(4, 'Dr. Lazaro Friesen III', 'wilford04@example.net', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'lZdsXeGJx7', '2024-06-06 13:28:27', '2024-06-06 13:28:27'),
(5, 'Ethan Hartmann MD', 'blaze.mante@example.net', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'kMW89v1Kkf', '2024-06-06 13:28:27', '2024-06-06 13:28:27'),
(6, 'Audra Crona', 'aidan06@example.net', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'fqSpze6sNN', '2024-06-06 13:28:27', '2024-06-06 13:28:27'),
(7, 'Mr. Kory Bogan III', 'jcrooks@example.org', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'oDQF1vlmKu', '2024-06-06 13:28:27', '2024-06-06 13:28:27'),
(8, 'Mr. Deontae Hodkiewicz', 'franecki.rozella@example.org', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'cDfPiGhfxD', '2024-06-06 13:28:27', '2024-06-06 13:28:27'),
(9, 'Dr. Letitia Schamberger', 'anibal.fisher@example.com', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'bpkVzzzuuO', '2024-06-06 13:28:27', '2024-06-06 13:28:27'),
(10, 'Mr. Devante O\'Kon', 'mccullough.gloria@example.net', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'KtDw4YDMvP', '2024-06-06 13:28:27', '2024-06-06 13:28:27'),
(11, 'Dr. Fredrick Collier', 'bailey.barrows@example.net', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'BmQGnv0ge9', '2024-06-06 13:28:27', '2024-06-06 13:28:27'),
(12, 'Alexa Stanton', 'koepp.shania@example.net', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '5x6jjfWWbv', '2024-06-06 13:28:27', '2024-06-06 13:28:27'),
(13, 'Myrna Monahan', 'ebba51@example.org', '2024-06-06 13:28:27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'P5RZgjz48X', '2024-06-06 13:28:27', '2024-06-06 13:28:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_store_id_foreign` (`store_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`product_id`,`tag_id`),
  ADD KEY `product_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stores_slug_unique` (`slug`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_number_unique` (`phone_number`),
  ADD KEY `users_store_id_foreign` (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD CONSTRAINT `product_tag_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
