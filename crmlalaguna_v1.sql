-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-03-2026 a las 03:19:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crmlalaguna_v1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `active_sessions`
--

CREATE TABLE `active_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `last_activity` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `active_sessions`
--

INSERT INTO `active_sessions` (`id`, `user_id`, `session_id`, `ip_address`, `user_agent`, `last_activity`, `created_at`) VALUES
(5, 1, '2p9aqsrrthrkk3bggif7amffak', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-01 23:46:35', '2026-02-01 23:46:34'),
(6, 2, 'tfui6h2lus3q1973eqnu0btv4q', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-01 23:48:33', '2026-02-01 23:48:33'),
(7, 3, 'a8ljdb1t072ij2opm5lho4etl3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-01 23:49:06', '2026-02-01 23:49:06'),
(8, 3, 'kjut47mutlmo2jl7g33e3d3aub', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-02 00:12:43', '2026-02-01 23:49:48'),
(9, 1, 'a6mdtclb6rr2rotj875sitsl8h', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-02 00:14:05', '2026-02-02 00:12:55'),
(10, 1, 'avs7vh5q67gd0d4cdj81srspd4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-02 22:47:20', '2026-02-02 22:45:03'),
(11, 1, '6ub78194hlatgdqim14h8fdd4m', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-02 23:24:36', '2026-02-02 22:47:29'),
(12, 3, '8i3g28cn935r2fv9tv61u7mq2a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-02 23:24:45', '2026-02-02 23:24:45'),
(13, 2, 'ilcf9rv0t8r6a80p3g5jr9b5kj', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-02 23:24:57', '2026-02-02 23:24:57'),
(14, 1, '795coeck12putqtl226im8nhab', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 01:00:04', '2026-02-02 23:25:09'),
(16, 1, 'n2s4fuvji8hs7krtu8n03hqkkk', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 12:59:44', '2026-02-14 12:41:54'),
(17, 1, 'j5lcmn04r9ui73i6k9862861u7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 19:01:07', '2026-02-23 19:00:09'),
(18, 1, '24m8ejqsm16e7t9erb7nka92ah', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-24 12:00:27', '2026-02-24 11:59:36'),
(20, 1, 'rrl3dq07ksfbh6fnf75nc18ffg', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-24 12:40:08', '2026-02-24 12:01:03'),
(27, 1, 'ulhcvibj0u2usg3qdo7uofmbog', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-28 21:11:26', '2026-02-28 16:58:18'),
(28, 1, '2fno9bb4si40j3i3ji3qq9bjoi', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-28 18:57:57', '2026-02-28 18:57:51'),
(29, 1, 'rsda89t7fuillugct4r979vbn1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-01 17:50:35', '2026-03-01 15:29:22'),
(30, 1, 'i1m2b58a593qj3ce374mf6oute', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-01 15:44:22', '2026-03-01 15:44:17'),
(31, 1, 'ec0vgugg24l21g6ed4jgeuuod2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 18:11:54', '2026-03-03 17:47:43'),
(32, 1, '06rqlc1om7n7nv4u9uo9geobqj', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 22:02:53', '2026-03-03 19:52:37'),
(33, 3, '1blm4r8od29g4h0ic9g1d889tl', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-04 21:28:09', '2026-03-04 21:28:09'),
(34, 1, 'hm3gtpvc5r8c8uvur7hgb5qbdf', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-04 22:55:25', '2026-03-04 21:28:24'),
(35, 1, 'tq5t0tm96lp5smq71en31vmqau', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 09:27:33', '2026-03-05 09:27:33'),
(36, 3, 'b4mqn4rsb3brf2fc2do5qrhtsv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 09:27:58', '2026-03-05 09:27:57'),
(37, 1, '8tpfp46qrebd2jm33b2l0hc7ho', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 09:30:51', '2026-03-05 09:28:26'),
(38, 1, 'g3uj6nfuhrnlgi9rpp4euvbluu', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 19:39:46', '2026-03-05 18:04:13'),
(39, 1, 'sk8cp75o7cvv0vucf7hppvmldb', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 19:29:26', '2026-03-05 19:29:26'),
(40, 1, 'isdvgn501kvqnktb1hgltrb5g0', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 20:42:42', '2026-03-05 19:39:51'),
(41, 1, 'j3j07iiu4acuv6738v1nouahfv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-06 15:09:45', '2026-03-06 15:09:35'),
(42, 1, 'cn8ervhfrdval6tbfkl819q8ss', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-09 18:59:58', '2026-03-09 18:00:20'),
(43, 1, 'nddm5nibvb6s4411ffglbbm80a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-10 10:52:27', '2026-03-10 10:16:46'),
(44, 1, 'evf2p1kj3sthgeh7gsmv4hue75', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-10 18:35:48', '2026-03-10 18:28:26'),
(45, 1, '4pim2j1bmti2ui0ln9oomt464g', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-10 22:06:15', '2026-03-10 20:38:23'),
(46, 1, '6u4kf3te4au8hbto9ibsrcp4s2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 12:37:48', '2026-03-11 12:08:55'),
(47, 1, '416913all8a73k9gpgqktmgvon', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 18:32:46', '2026-03-11 16:41:47'),
(48, 1, 'cc8i6dqiga43olaasg44g8tvic', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 18:39:12', '2026-03-11 18:33:06'),
(49, 3, 'akllpf0f2enoc29ujcdn6nso3t', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 18:41:44', '2026-03-11 18:41:44'),
(50, 3, 'tp17lrorsahg19nc2hp355fg9v', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 18:42:35', '2026-03-11 18:42:35'),
(51, 1, 'ifnq6ika97m0d582mf81nhd1bi', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 18:42:45', '2026-03-11 18:42:45'),
(52, 3, '4b9mrtrdcn0bb71liuu843ptl6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 18:43:08', '2026-03-11 18:43:08'),
(54, 1, 'vr9bni52e8a3vehd2cs2flgm56', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 18:51:25', '2026-03-11 18:43:46'),
(56, 1, 'phodi3p3326g66i82m1jt25amo', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 19:04:05', '2026-03-11 18:56:25'),
(58, 7, '6ir0u4id731heffle3e6drlvbr', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 18:58:41', '2026-03-11 18:58:41'),
(59, 1, 'jvq72vsts7nk2ulpc7khc621cj', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 10:57:11', '2026-03-14 10:56:19'),
(60, 1, 'igis1v8cqrostl315pb9u3bp8n', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 11:16:36', '2026-03-14 10:58:09'),
(61, 1, 'f7qdo89lv5hacj0ifmrrkolb1d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-16 12:54:19', '2026-03-16 12:06:01'),
(62, 1, '8cjbtbb1a3rfsv86gmp3o60mt9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-16 19:03:13', '2026-03-16 18:54:01'),
(63, 1, '4ke9gsqajq3r33j842brnou31f', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-17 13:00:17', '2026-03-17 11:05:17'),
(64, 1, 'o67pr05j5ep3t7nftsbh71k8eq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-17 18:56:01', '2026-03-17 16:30:52'),
(65, 1, 'ii540h593l4o8rh6fn8ci3n6il', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-17 21:12:24', '2026-03-17 20:39:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blocks`
--

CREATE TABLE `blocks` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL COMMENT 'A, B, C...',
  `description` text DEFAULT NULL,
  `plano_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `total_lots` int(11) NOT NULL DEFAULT 0,
  `min_monthly_payment` decimal(10,2) NOT NULL,
  `initial_payment` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `blocks`
--

INSERT INTO `blocks` (`id`, `project_id`, `name`, `description`, `plano_url`, `status`, `total_lots`, `min_monthly_payment`, `initial_payment`, `created_at`, `updated_at`) VALUES
(1, 4, 'Manzana A', 'Manzana frente al parque, total de 17 lotes. Los cuales 15 lotes son de 100 m2 y 2 lotes de 150.', 'assets/planos/residencial-la-laguna-manzana-A.png', 'active', 17, 350.00, 36000.00, '2026-03-03 17:50:53', '2026-03-04 22:14:38'),
(2, 4, 'Manzana B', 'Lotes en el jirón principal', 'assets/planos/residencial-la-laguna-manzana-B.png', 'active', 15, 450.00, 36000.00, '2026-03-04 21:38:10', '2026-03-04 22:14:38'),
(3, 4, 'Manzana C', 'Lotes que estan en la calle n1', 'assets/planos/residencial-la-laguna-manzana-C.png', 'active', 16, 400.00, 36000.00, '2026-03-04 21:38:50', '2026-03-04 22:14:38'),
(4, 4, 'Manzana D', 'Lotes que están en al calle n2', 'assets/planos/residencial-la-laguna-manzana-D.png', 'active', 13, 350.00, 36000.00, '2026-03-04 21:39:20', '2026-03-11 17:44:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `company_name` varchar(191) DEFAULT NULL,
  `tax_id` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id`, `user_id`, `phone`, `address`, `city`, `state`, `postal_code`, `company_name`, `tax_id`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '+51954515472', 'calle triunfo, 1370', 'Chepén', 'La Libertad', '13871', 'La Laguna', '74123212', 'Cliente tiene un lote en residencial la laguna, manzana D lote 11', 'active', '2026-02-03 00:29:16', '2026-03-11 18:56:55'),
(2, 6, '+51959867531', 'Aderlín Perez', 'Trujillo', 'Huanchaco', '13000', 'La Laguna', '74123322', 'pruebas', 'active', '2026-02-24 12:26:24', '2026-02-24 12:26:24'),
(3, 7, '987654321', 'San luis', 'Chepen', 'La Libertad', '13871', '-', '7654321', 'Cliente', 'active', '2026-03-11 18:58:22', '2026-03-11 18:58:22'),
(4, 8, '98765321', 'prueba calle 122', 'Chepen', 'Libertad', '13871', 'no hay', '7654321', 'Prueba', 'active', '2026-03-14 10:59:40', '2026-03-14 10:59:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client_services`
--

CREATE TABLE `client_services` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','suspended','finished') DEFAULT 'active',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `client_services`
--

INSERT INTO `client_services` (`id`, `company_id`, `client_id`, `service_id`, `project_id`, `start_date`, `end_date`, `status`, `metadata`, `created_at`, `updated_at`) VALUES
(4, NULL, 1, 1, 4, '2026-03-01', NULL, 'active', NULL, '2026-03-01 17:49:58', '2026-03-01 17:49:58'),
(5, NULL, 2, 1, 4, '2026-03-01', NULL, 'active', NULL, '2026-03-01 17:50:09', '2026-03-01 17:50:09'),
(6, NULL, 4, 1, 4, '2026-03-14', NULL, 'active', NULL, '2026-03-14 11:00:05', '2026-03-14 11:00:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `primary_color` varchar(7) DEFAULT NULL,
  `secondary_color` varchar(7) DEFAULT NULL,
  `smtp_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`smtp_config`)),
  `sms_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sms_config`)),
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `client_service_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `signed_date` date DEFAULT NULL,
  `status` enum('uploaded','signed','archived') DEFAULT 'uploaded',
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lots`
--

CREATE TABLE `lots` (
  `id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  `lot_number` varchar(10) NOT NULL,
  `area` decimal(10,2) NOT NULL,
  `front` decimal(8,2) DEFAULT NULL,
  `depth` decimal(8,2) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `status` enum('disponible','reservado','vendido','mora','cancelado') NOT NULL DEFAULT 'disponible',
  `is_corner` tinyint(1) DEFAULT 0,
  `faces_park` tinyint(1) DEFAULT 0,
  `faces_main_street` tinyint(1) DEFAULT 0,
  `jiron_principal` tinyint(1) DEFAULT 0,
  `calle_1` tinyint(1) DEFAULT 0,
  `calle_2` tinyint(1) DEFAULT 0,
  `pasaje_1_parque` tinyint(1) DEFAULT 0,
  `pasaje_2` tinyint(1) DEFAULT 0,
  `special_features` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `map_left` decimal(5,2) DEFAULT 0.00,
  `map_top` decimal(5,2) DEFAULT 0.00,
  `map_width` decimal(5,2) DEFAULT 5.00,
  `map_height` decimal(5,2) DEFAULT 5.00,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lots`
--

INSERT INTO `lots` (`id`, `block_id`, `lot_number`, `area`, `front`, `depth`, `price`, `status`, `is_corner`, `faces_park`, `faces_main_street`, `jiron_principal`, `calle_1`, `calle_2`, `pasaje_1_parque`, `pasaje_2`, `special_features`, `notes`, `map_left`, `map_top`, `map_width`, `map_height`, `created_at`, `updated_at`) VALUES
(1, 1, 'A01', 100.00, 6.50, 15.50, 128000.00, 'vendido', 1, 0, 0, 0, 0, 0, 0, 0, '', '', 7.00, 55.00, 6.00, 10.00, '2026-03-03 20:54:23', '2026-03-17 20:43:34'),
(2, 1, 'A02', 100.00, 6.50, 15.50, 116000.00, 'vendido', 1, 1, 0, 0, 0, 0, 0, 0, '', '', NULL, NULL, NULL, NULL, '2026-03-03 20:55:21', '2026-03-17 18:32:14'),
(3, 1, 'A03', 100.00, 6.50, 15.50, 115000.00, 'disponible', 0, 1, 0, 0, 0, 0, 0, 0, '', '', NULL, NULL, NULL, NULL, '2026-03-04 21:30:00', '2026-03-17 17:12:29'),
(4, 2, 'B01', 183.00, 7.12, 22.19, 226887.00, 'disponible', 0, 1, 0, 0, 0, 0, 0, 0, '', 'Lotes que es grande', 0.00, 0.00, 5.00, 5.00, '2026-03-04 21:40:07', '2026-03-04 21:40:07'),
(5, 2, 'B02', 179.00, 7.12, 22.11, 218999.00, 'disponible', 0, 1, 0, 0, 0, 0, 0, 0, '', '', 0.00, 0.00, 5.00, 5.00, '2026-03-04 21:40:39', '2026-03-04 21:40:39'),
(6, 2, 'B03', 172.00, 7.10, 22.00, 17899.00, 'vendido', 0, 1, 0, 0, 0, 0, 0, 0, '', '', 0.00, 0.00, 5.00, 5.00, '2026-03-04 21:41:15', '2026-03-17 20:39:29'),
(7, 3, 'C01', 143.00, 7.50, 19.00, 138978.00, 'disponible', 1, 0, 0, 0, 0, 0, 0, 0, '', '', 0.00, 0.00, 5.00, 5.00, '2026-03-04 21:41:43', '2026-03-17 18:31:09'),
(8, 3, 'C02', 143.00, 7.50, 19.00, 138978.00, 'disponible', 0, 0, 0, 0, 1, 0, 0, 0, '', '', 0.00, 0.00, 8.00, 8.00, '2026-03-04 21:42:13', '2026-03-04 22:24:28'),
(9, 3, 'C03', 143.00, 7.50, 19.00, 138978.00, 'vendido', 0, 0, 0, 0, 1, 0, 0, 0, '', '', 0.00, 0.00, 5.00, 5.00, '2026-03-04 21:42:32', '2026-03-17 18:44:30'),
(10, 4, 'D01', 100.00, 6.50, 15.50, 85769.00, 'disponible', 1, 0, 0, 0, 0, 0, 0, 0, '', '', 0.00, 0.00, 5.00, 5.00, '2026-03-04 21:45:27', '2026-03-04 21:45:27'),
(11, 4, 'D02', 100.00, 6.50, 15.50, 85769.00, 'vendido', 0, 0, 0, 0, 0, 1, 0, 0, '', '', 0.00, 0.00, 5.00, 5.00, '2026-03-04 21:45:46', '2026-03-17 18:55:40'),
(12, 4, 'D03', 100.00, 6.50, 15.50, 85769.00, 'disponible', 0, 0, 0, 0, 0, 1, 0, 0, '', '', 0.00, 0.00, 5.00, 5.00, '2026-03-04 21:46:15', '2026-03-10 20:39:56'),
(13, 2, 'B10', 153.20, 7.02, 22.60, 138000.00, 'vendido', 0, 0, 0, 1, 0, 0, 0, 0, '', '', 0.00, 0.00, 8.00, 8.00, '2026-03-11 19:00:26', '2026-03-17 18:47:11'),
(14, 1, 'A04', 100.00, 6.50, 15.50, 85555.00, 'vendido', 0, 1, 0, 0, 0, 0, 1, 0, '', '', 10.00, 30.00, 8.00, 8.00, '2026-03-14 11:01:19', '2026-03-17 18:34:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lot_payments`
--

CREATE TABLE `lot_payments` (
  `id` int(11) NOT NULL,
  `lot_sale_id` int(11) NOT NULL,
  `lot_reservation_id` int(11) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('reserva','inicial','cuota_fija','cuota_minima','adelanto','saldo_final','mora') NOT NULL,
  `payment_method` varchar(100) DEFAULT 'efectivo',
  `receipt_number` varchar(100) DEFAULT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `is_late` tinyint(1) DEFAULT 0,
  `late_fee` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `registered_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lot_reservations`
--

CREATE TABLE `lot_reservations` (
  `id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `reservation_date` datetime NOT NULL,
  `expiration_date` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 300.00,
  `status` enum('activa','confirmada','expirada','cancelada') DEFAULT 'activa',
  `applied_to_sale` tinyint(1) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lot_sales`
--

CREATE TABLE `lot_sales` (
  `id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `total_with_interest` decimal(12,2) DEFAULT NULL,
  `initial_payment` decimal(12,2) NOT NULL,
  `balance` decimal(12,2) NOT NULL,
  `payment_term` int(11) NOT NULL COMMENT 'meses',
  `due_day_of_month` tinyint(3) UNSIGNED DEFAULT NULL,
  `grace_days` tinyint(3) UNSIGNED DEFAULT 7,
  `late_fee_rate` decimal(5,2) DEFAULT 10.00,
  `interest_rate` decimal(5,2) DEFAULT 0.00,
  `monthly_fixed_payment` decimal(10,2) NOT NULL,
  `projected_monthly_payment` decimal(12,2) DEFAULT NULL,
  `monthly_min_payment` decimal(10,2) NOT NULL,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `payment_status` enum('al_dia','atrasado','mora','cancelado') DEFAULT 'al_dia',
  `consecutive_missed` int(11) DEFAULT 0,
  `total_missed` int(11) DEFAULT 0,
  `final_payment_deadline` date DEFAULT NULL,
  `contract_file` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `canceled_at` datetime DEFAULT NULL,
  `canceled_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lot_sales`
--

INSERT INTO `lot_sales` (`id`, `lot_id`, `client_id`, `sale_date`, `total_price`, `total_with_interest`, `initial_payment`, `balance`, `payment_term`, `due_day_of_month`, `grace_days`, `late_fee_rate`, `interest_rate`, `monthly_fixed_payment`, `projected_monthly_payment`, `monthly_min_payment`, `discount_percent`, `payment_status`, `consecutive_missed`, `total_missed`, `final_payment_deadline`, `contract_file`, `notes`, `created_by`, `created_at`, `updated_at`, `canceled_at`, `canceled_reason`) VALUES
(29, 1, 3, '2026-03-18', 138000.00, 138000.00, 0.00, 138000.00, 60, 18, 1, 10.00, 0.00, 0.00, 2300.00, 0.00, 0.00, 'al_dia', 0, 0, '0000-00-00', '', '', 1, '2026-03-17 20:43:34', '2026-03-17 20:44:44', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lot_status_history`
--

CREATE TABLE `lot_status_history` (
  `id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `old_status` enum('disponible','reservado','vendido','mora','cancelado') NOT NULL,
  `new_status` enum('disponible','reservado','vendido','mora','cancelado') NOT NULL,
  `reason` text DEFAULT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lot_status_history`
--

INSERT INTO `lot_status_history` (`id`, `lot_id`, `old_status`, `new_status`, `reason`, `changed_by`, `created_at`) VALUES
(1, 12, 'vendido', 'disponible', 'Venta cancelada (ID 2): Cliente dejo de pagar', 1, '2026-03-10 20:38:58'),
(2, 12, 'vendido', 'disponible', 'Venta cancelada (ID 3): Cliente ya no quiere proseguir la venta', 1, '2026-03-10 20:39:56'),
(3, 1, 'reservado', 'disponible', 'Reserva cancelada (ID 2): ', 1, '2026-03-10 21:31:40'),
(4, 1, 'reservado', 'disponible', 'Reserva cancelada (ID 3): ', 1, '2026-03-10 21:54:43'),
(5, 1, 'reservado', 'disponible', 'Reserva cancelada (ID 4): ', 1, '2026-03-10 22:03:37'),
(6, 1, 'vendido', 'disponible', 'Venta cancelada (ID 4): ', 1, '2026-03-10 22:03:48'),
(7, 1, 'reservado', 'disponible', 'Reserva cancelada (ID 5): ', 1, '2026-03-11 12:09:09'),
(8, 1, 'reservado', 'disponible', 'Reserva cancelada (ID 6): ', 1, '2026-03-11 12:18:46'),
(9, 1, 'reservado', 'vendido', 'Reserva confirmada como venta (ID 5)', 1, '2026-03-11 12:33:26'),
(10, 1, 'reservado', 'disponible', 'Reserva cancelada (ID 8): ', 1, '2026-03-11 12:33:42'),
(11, 1, 'vendido', 'disponible', 'Venta cancelada (ID 5): ', 1, '2026-03-11 12:34:02'),
(12, 1, 'vendido', 'disponible', 'Venta cancelada (ID 6): ', 1, '2026-03-11 17:43:27'),
(13, 1, 'reservado', 'vendido', 'Reserva confirmada como venta (ID 7)', 1, '2026-03-11 18:36:08'),
(14, 14, 'reservado', 'vendido', 'Reserva confirmada como venta (ID 11)', 1, '2026-03-14 11:02:54'),
(15, 1, 'vendido', 'disponible', 'Venta cancelada (ID 7): ', 1, '2026-03-17 11:17:29'),
(16, 13, 'vendido', 'disponible', 'Venta cancelada (ID 10): ', 1, '2026-03-17 11:17:31'),
(17, 6, 'vendido', 'disponible', 'Venta cancelada (ID 9): ', 1, '2026-03-17 11:17:34'),
(18, 2, 'vendido', 'disponible', 'Venta cancelada (ID 8): ', 1, '2026-03-17 11:17:37'),
(19, 14, 'vendido', 'disponible', 'Venta cancelada (ID 11): ', 1, '2026-03-17 11:17:39'),
(20, 1, 'vendido', 'disponible', 'Venta cancelada (ID 12): ', 1, '2026-03-17 12:18:15'),
(21, 14, 'vendido', 'disponible', 'Venta cancelada (ID 16): ', 1, '2026-03-17 16:54:24'),
(22, 3, 'vendido', 'disponible', 'Venta cancelada (ID 15): ', 1, '2026-03-17 16:54:26'),
(23, 2, 'vendido', 'disponible', 'Venta cancelada (ID 14): ', 1, '2026-03-17 16:54:28'),
(24, 1, 'vendido', 'disponible', 'Venta cancelada (ID 13): ', 1, '2026-03-17 16:54:29'),
(25, 1, 'vendido', 'disponible', 'Venta cancelada (ID 17): ', 1, '2026-03-17 16:56:15'),
(26, 3, 'vendido', 'disponible', 'Venta cancelada (ID 18): ', 1, '2026-03-17 17:12:29'),
(27, 7, 'vendido', 'disponible', 'Venta cancelada (ID 22): ', 1, '2026-03-17 18:31:09'),
(28, 2, 'vendido', 'disponible', 'Venta cancelada (ID 20): ', 1, '2026-03-17 18:31:10'),
(29, 14, 'vendido', 'disponible', 'Venta cancelada (ID 21): ', 1, '2026-03-17 18:31:12'),
(30, 1, 'vendido', 'disponible', 'Venta cancelada (ID 19): ', 1, '2026-03-17 18:31:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `label` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '["admin","socio"] o ["admin"] etc.' CHECK (json_valid(`roles`)),
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id`, `parent_id`, `label`, `url`, `icon`, `roles`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Dashboard', '/dashboard', 'bi bi-speedometer2', '[\"admin\",\"socio\",\"cliente\"]', 10, 1, '2026-01-31 16:59:32', '2026-01-31 16:59:32'),
(2, NULL, 'Proyectos', '/projects', 'bi bi-building', '[\"admin\",\"socio\"]', 15, 1, '2026-01-31 16:59:32', '2026-02-28 17:12:12'),
(3, 2, 'Listar lotes', 'lots', 'bi bi-list-ul', '[\"admin\",\"socio\"]', 21, 1, '2026-01-31 16:59:32', '2026-03-03 21:29:28'),
(4, 2, 'Mapa de lotes', 'map', 'bi bi-map', '[\"admin\",\"socio\"]', 22, 1, '2026-01-31 16:59:32', '2026-03-04 22:01:09'),
(5, NULL, 'Ventas / Contratos', 'sales', 'bi bi-cart-check', '[\"admin\",\"socio\"]', 30, 1, '2026-01-31 16:59:32', '2026-03-05 18:18:09'),
(6, NULL, 'Clientes', '/clients', 'bi bi-people', '[\"admin\",\"socio\"]', 40, 1, '2026-01-31 16:59:32', '2026-01-31 16:59:32'),
(7, NULL, 'Socios / Comisiones', '/partners', 'bi bi-person-badge', '[\"admin\"]', 50, 1, '2026-01-31 16:59:32', '2026-01-31 16:59:32'),
(8, NULL, 'Reportes', '/reports', 'bi bi-file-earmark-bar-graph', '[\"admin\",\"socio\"]', 60, 1, '2026-01-31 16:59:32', '2026-01-31 16:59:32'),
(9, NULL, 'Configuración', '/settings', 'bi bi-gear', '[\"admin\"]', 100, 1, '2026-01-31 16:59:32', '2026-01-31 16:59:32'),
(10, NULL, 'Mi Proyecto', 'dashboard', 'bi bi-house-door', '[\"cliente\"]', 10, 1, '2026-02-01 21:56:33', '2026-02-01 21:56:33'),
(13, NULL, 'Reportes', 'reports', 'bi bi-graph-up', '[\"cliente\"]', 40, 1, '2026-02-01 21:56:33', '2026-02-01 21:56:33'),
(14, NULL, 'Actualizaciones', 'updates', 'bi bi-bell', '[\"cliente\"]', 50, 1, '2026-02-01 21:56:33', '2026-02-01 21:56:33'),
(15, NULL, 'Soporte', 'support', 'bi bi-headset', '[\"cliente\"]', 60, 1, '2026-02-01 21:56:33', '2026-02-01 21:56:33'),
(16, 2, 'Manzanas / Bloques', '/blocks', 'bi bi-grid-3x3-gap', '[\"admin\",\"socio\"]', 16, 1, '2026-02-28 20:48:18', '2026-02-28 20:48:18'),
(17, 16, 'Listar manzanas', '/blocks/list', 'bi bi-list-ul', '[\"admin\",\"socio\"]', 17, 1, '2026-02-28 20:48:18', '2026-02-28 20:48:18'),
(18, 16, 'Nueva manzana', '/blocks/create', 'bi bi-plus-circle', '[\"admin\"]', 18, 1, '2026-02-28 20:48:18', '2026-02-28 20:48:18'),
(19, NULL, 'Lotes', '/lots', 'bi bi-grid-3x3', '[\"admin\",\"socio\"]', 25, 0, '2026-02-28 20:48:18', '2026-03-03 21:29:28'),
(20, 19, 'Listar lotes', 'lots', 'bi bi-list-ul', '[\"admin\",\"socio\"]', 26, 0, '2026-02-28 20:48:18', '2026-03-03 21:05:07'),
(21, 18, 'Mapa de lotes', 'map', 'bi bi-map', '[\"admin\",\"socio\"]', 27, 1, '2026-02-28 20:48:18', '2026-03-04 22:01:09'),
(31, 5, 'Listar Ventas', 'lot-sales', 'bi bi-list-ul', '[\"admin\",\"socio\"]', 10, 1, '2026-03-05 18:25:36', '2026-03-05 18:25:36'),
(32, 5, 'Listar Reservas', 'lot-reservations', 'bi bi-calendar-check', '[\"admin\",\"socio\"]', 20, 1, '2026-03-05 18:25:36', '2026-03-05 18:25:36'),
(33, 5, 'Contratos', 'lot-contracts', 'bi bi-file-earmark-text', '[\"admin\",\"socio\"]', 30, 1, '2026-03-05 18:25:36', '2026-03-05 18:25:36'),
(35, 5, 'Pagos', 'lot-payments', 'bi bi-cash-coin', '[\"admin\",\"socio\"]', 35, 1, '2026-03-11 16:47:07', '2026-03-11 16:47:07'),
(36, 5, 'Boletas', 'lot-receipts', 'bi bi-receipt', '[\"admin\",\"socio\"]', 40, 1, '2026-03-11 16:47:07', '2026-03-11 16:47:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commission_percent` decimal(5,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `client_service_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `method` varchar(100) DEFAULT 'manual',
  `receipt_path` varchar(255) DEFAULT NULL,
  `state` enum('registered','pending','reconciled') DEFAULT 'registered',
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('planificacion','ejecucion','entregado','cancelado') DEFAULT 'planificacion',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `progress` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `projects`
--

INSERT INTO `projects` (`id`, `company_id`, `title`, `description`, `status`, `start_date`, `end_date`, `progress`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(4, NULL, 'Residencial \"La Laguna\"', 'Ubicación en chepén', 'planificacion', '2023-01-12', '0000-00-00', 50, 1, 1, '2026-03-01 17:49:47', '2026-03-03 17:48:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rate_limits`
--

CREATE TABLE `rate_limits` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `action` varchar(100) NOT NULL DEFAULT 'login',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rate_limits`
--

INSERT INTO `rate_limits` (`id`, `ip_address`, `action`, `created_at`) VALUES
(1, '127.0.0.1', 'login', '2026-02-01 22:15:18'),
(2, '127.0.0.1', 'login', '2026-02-01 22:15:23'),
(3, '127.0.0.1', 'login', '2026-02-01 22:15:28'),
(4, '127.0.0.1', 'login', '2026-02-01 22:15:35'),
(5, '127.0.0.1', 'login', '2026-02-01 22:15:40'),
(6, '127.0.0.1', 'login', '2026-02-01 22:30:17'),
(7, '127.0.0.1', 'login', '2026-02-01 22:30:21'),
(8, '127.0.0.1', 'login', '2026-02-01 22:30:25'),
(9, '127.0.0.1', 'login', '2026-02-01 22:39:15'),
(10, '127.0.0.1', 'login', '2026-02-01 23:30:44'),
(11, '127.0.0.1', 'login', '2026-02-01 23:31:25'),
(12, '127.0.0.1', 'login', '2026-02-01 23:32:08'),
(13, '127.0.0.1', 'login', '2026-02-01 23:39:20'),
(14, '127.0.0.1', 'login', '2026-02-01 23:39:26'),
(15, '127.0.0.1', 'login', '2026-02-01 23:39:46'),
(16, '127.0.0.1', 'login', '2026-02-01 23:47:19'),
(17, '127.0.0.1', 'login', '2026-02-01 23:47:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role` enum('admin','socio','cliente') NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `default_price` decimal(12,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id`, `company_id`, `name`, `description`, `default_price`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Proyectos inmobiliarios', 'Desarrollo y gestión de proyectos inmobiliarios', 0.00, '2026-03-01 16:22:10', '2026-03-01 16:22:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` enum('admin','socio','cliente') NOT NULL DEFAULT 'cliente',
  `status` enum('active','inactive','blocked') NOT NULL DEFAULT 'active',
  `last_login_at` datetime DEFAULT NULL,
  `failed_login_attempts` int(11) DEFAULT 0,
  `password_changed_at` datetime DEFAULT NULL,
  `profile_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`profile_data`)),
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `company_id`, `name`, `email`, `password_hash`, `phone`, `role`, `status`, `last_login_at`, `failed_login_attempts`, `password_changed_at`, `profile_data`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Administrador Principal', 'admin@lalaguna.pe', '$2y$10$8voIqkwJDqb6wuH6XT4THOeS6hlrbj2SSy6JOxEgAL4jpZquBZuFW', NULL, 'admin', 'active', '2026-03-17 20:39:05', 0, NULL, NULL, '2026-02-01 23:17:38', '2026-03-17 20:39:05'),
(2, NULL, 'Socio Ejemplo', 'socio@lalaguna.pe', '$2y$10$Uvc5SBc17pxHaz0agyDFTuyeyGwavqsSe5T2uzEhGeOPoRyO2crZe', NULL, 'socio', 'active', '2026-02-02 23:24:57', 0, NULL, NULL, '2026-02-01 23:17:38', '2026-02-02 23:24:57'),
(3, NULL, 'Cliente Demo', 'cliente@lalaguna.pe', '$2y$10$GUFsKIh/i/y3ZaQ0eO0jUuxxlt.rGqGZOn736sIvx/GOgz4x7U3mK', NULL, 'cliente', 'active', '2026-03-11 18:43:08', 0, NULL, NULL, '2026-02-01 23:17:38', '2026-03-11 18:43:08'),
(4, NULL, 'Junior Leonardo Mestanza Sanchez', 'juniorleonahero@gmail.com', '$2y$10$2Q0UUAr5bFlUKucQFRq7i.tqPzeQ7slIQLJ7uiNBXrCeQ8SobkznO', '954515477', 'cliente', 'active', '2026-03-11 18:56:49', 0, NULL, NULL, '2026-02-03 00:29:16', '2026-03-11 18:56:49'),
(5, NULL, 'Jhonny Lucano', 'lucano@gmail.com', '$2y$10$InTPwPh80EXaEa4qWvymMedqZcyRGOOKnyooU4S4ko98z.RqmI.NW', '987654321', 'cliente', 'active', NULL, 0, NULL, NULL, '2026-02-24 12:15:02', '2026-02-24 12:15:02'),
(6, NULL, 'Margarita Isabel Chafloque Cerrepe', 'michc3097@gmail.com', '$2y$10$L7YlOBubaCObSf7uJqVX6edMdwgF8a47j/JgQ4i9OJbv6sBp4a7S6', '+51959867531', 'cliente', 'active', NULL, 0, NULL, NULL, '2026-02-24 12:26:24', '2026-02-24 12:26:24'),
(7, NULL, 'David Salazar', 'david@lalaguna.pe', '$2y$10$zt6lV7K4wr7lCgefo19LWuQ7pO4q9vN0U7i7yd6c26H9b2zrrV6ci', '987654321', 'cliente', 'active', '2026-03-11 18:58:41', 0, NULL, NULL, '2026-03-11 18:58:22', '2026-03-11 18:58:41'),
(8, NULL, 'Junior Sanchez', 'prueba@lalaguna.pe', '$2y$10$toeOr3NNj1Hn1EUDIKkjR.5bjBdJiX1lBD8luHsZVZ53jlJEIsoQy', '98765321', 'cliente', 'active', NULL, 0, NULL, NULL, '2026-03-14 10:59:40', '2026-03-14 10:59:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_active_sessions_user` (`user_id`);

--
-- Indices de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_user` (`user_id`);

--
-- Indices de la tabla `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_blocks_project_name` (`project_id`,`name`),
  ADD KEY `idx_project_id` (`project_id`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_clients_user` (`user_id`);

--
-- Indices de la tabla `client_services`
--
ALTER TABLE `client_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cs_client` (`client_id`),
  ADD KEY `fk_cs_service` (`service_id`),
  ADD KEY `fk_cs_project` (`project_id`),
  ADD KEY `fk_cs_company` (`company_id`);

--
-- Indices de la tabla `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contracts_cs` (`client_service_id`),
  ADD KEY `fk_contracts_company` (`company_id`);

--
-- Indices de la tabla `lots`
--
ALTER TABLE `lots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_lots_block_number` (`block_id`,`lot_number`),
  ADD KEY `idx_lots_status` (`status`),
  ADD KEY `idx_block_id` (`block_id`);

--
-- Indices de la tabla `lot_payments`
--
ALTER TABLE `lot_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lp_sale` (`lot_sale_id`),
  ADD KEY `fk_lp_registered_by` (`registered_by`),
  ADD KEY `fk_lp_reservation` (`lot_reservation_id`);

--
-- Indices de la tabla `lot_reservations`
--
ALTER TABLE `lot_reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reservations_expiration` (`expiration_date`),
  ADD KEY `fk_res_lot` (`lot_id`),
  ADD KEY `fk_res_client` (`client_id`),
  ADD KEY `fk_res_created_by` (`created_by`);

--
-- Indices de la tabla `lot_sales`
--
ALTER TABLE `lot_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lot_sales_status` (`payment_status`),
  ADD KEY `fk_lot_sales_lot` (`lot_id`),
  ADD KEY `fk_lot_sales_client` (`client_id`),
  ADD KEY `fk_lot_sales_created_by` (`created_by`);

--
-- Indices de la tabla `lot_status_history`
--
ALTER TABLE `lot_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lsh_lot` (`lot_id`),
  ADD KEY `fk_lsh_changed_by` (`changed_by`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menus_parent` (`parent_id`);

--
-- Indices de la tabla `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_partners_user` (`user_id`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payments_cs` (`client_service_id`),
  ADD KEY `fk_payments_company` (`company_id`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_projects_company` (`company_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indices de la tabla `rate_limits`
--
ALTER TABLE `rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_rate_ip_action` (`ip_address`,`action`);

--
-- Indices de la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role`,`permission_id`),
  ADD KEY `fk_rp_permission` (`permission_id`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_services_company` (`company_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_company` (`company_id`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_status` (`status`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `active_sessions`
--
ALTER TABLE `active_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `blocks`
--
ALTER TABLE `blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `client_services`
--
ALTER TABLE `client_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lots`
--
ALTER TABLE `lots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `lot_payments`
--
ALTER TABLE `lot_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `lot_reservations`
--
ALTER TABLE `lot_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `lot_sales`
--
ALTER TABLE `lot_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `lot_status_history`
--
ALTER TABLE `lot_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rate_limits`
--
ALTER TABLE `rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD CONSTRAINT `fk_active_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `blocks`
--
ALTER TABLE `blocks`
  ADD CONSTRAINT `fk_blocks_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_clients_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `client_services`
--
ALTER TABLE `client_services`
  ADD CONSTRAINT `fk_cs_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cs_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_cs_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_cs_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `fk_contracts_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_contracts_cs` FOREIGN KEY (`client_service_id`) REFERENCES `client_services` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `lots`
--
ALTER TABLE `lots`
  ADD CONSTRAINT `fk_lots_block` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lot_payments`
--
ALTER TABLE `lot_payments`
  ADD CONSTRAINT `fk_lp_registered_by` FOREIGN KEY (`registered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_lp_sale` FOREIGN KEY (`lot_sale_id`) REFERENCES `lot_sales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lot_reservations`
--
ALTER TABLE `lot_reservations`
  ADD CONSTRAINT `fk_res_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_res_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_res_lot` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lot_sales`
--
ALTER TABLE `lot_sales`
  ADD CONSTRAINT `fk_lot_sales_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lot_sales_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_lot_sales_lot` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lot_status_history`
--
ALTER TABLE `lot_status_history`
  ADD CONSTRAINT `fk_lsh_changed_by` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_lsh_lot` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `fk_menus_parent` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `partners`
--
ALTER TABLE `partners`
  ADD CONSTRAINT `fk_partners_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_payments_cs` FOREIGN KEY (`client_service_id`) REFERENCES `client_services` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_projects_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fk_rp_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `fk_services_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
