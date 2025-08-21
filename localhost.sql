-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 21, 2025 at 05:57 PM
-- Server version: 11.8.3-MariaDB-1+b1 from Debian
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techweek`
--
CREATE DATABASE IF NOT EXISTS `techweek` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
USE `techweek`;

-- --------------------------------------------------------

--
-- Table structure for table `atividades`
--

CREATE TABLE `atividades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `palestrante` varchar(1000) DEFAULT NULL,
  `singular_plural` varchar(40) DEFAULT NULL,
  `sala` varchar(100) DEFAULT NULL,
  `vagas` varchar(100) DEFAULT NULL,
  `data` varchar(100) DEFAULT NULL,
  `horario` varchar(100) DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `atividades`
--

INSERT INTO `atividades` (`id`, `titulo`, `tipo`, `palestrante`, `singular_plural`, `sala`, `vagas`, `data`, `horario`, `hash`) VALUES
(1, 'Palestra: Como programar um computador quÃ¢ntico', 'Palestra', 'Evandro C. R. da Rosa - Co-fundador da Quantuloop, start-up brasileira de computaÃ§Ã£o quÃ¢ntica, e membro do Grupo de ComputaÃ§Ã£o QuÃ¢ntica da UFSC (GCQ-UFSC)', NULL, 'Anfiteatro (Bloco A)', '', 'Segunda-feira - 22/04/2024', '19:15', NULL),
(3, 'Palestra: Carreira na Ã¡rea de TI e experiÃªncia no Parque TecnolÃ³gico de Itaipu (PTI) ', 'Palestra', 'Felipe Theodoro GuimarÃ£es', NULL, 'Anfiteatro (Bloco A)', '', 'Quarta-feira - 24/04/2024', '19:15', NULL),
(4, 'Momento Cultural - Campeonatos de Xadrez, Quake e TÃªnis de mesa; FIFA no playstation 5; MÃºsica.', 'Palestra', 'ResponsÃ¡veis: Wellton Costa, Marcos Tenório e CASIS', NULL, 'Centro de ConvivÃªncia', '', 'Quarta-feira - 24/04/2024', '21:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `participantes`
--

CREATE TABLE `participantes` (
  `id` int(11) NOT NULL,
  `administrador` int(11) DEFAULT NULL,
  `hash` varchar(100) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `cpf` varchar(14) NOT NULL,
  `ra` varchar(20) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `instituicao` varchar(255) NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `participantes`
--

INSERT INTO `participantes` (`id`, `administrador`, `hash`, `nome`, `email`, `senha`, `cpf`, `ra`, `telefone`, `instituicao`, `data_cadastro`) VALUES
(1, 1, 'deaf2d29d5520b3ed2f832ae92851b2a', 'Wellton Costa', 'wcoliveira@utfpr.edu.br', '857.922.682-15', '857.922.682-15', NULL, '(33) 43344-3434', 'UTFPR', '2025-08-21 16:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `participantes_oficinas`
--

CREATE TABLE `participantes_oficinas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idParticipante` int(10) NOT NULL,
  `idOficina` int(10) NOT NULL,
  `presenca` int(1) DEFAULT NULL,
  `certificado` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presencas`
--

CREATE TABLE `presencas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_atividade` int(3) NOT NULL,
  `id_participante` int(3) NOT NULL,
  `data_hora` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `presencas`
--

INSERT INTO `presencas` (`id`, `id_atividade`, `id_participante`, `data_hora`) VALUES
(1, 1, 1, '22/04/2024 17:38:43'),
(2, 1, 149, '22/04/2024 17:40:20'),
(3, 1, 85, '22/04/2024 17:40:27'),
(4, 1, 7, '22/04/2024 17:40:35'),
(5, 1, 182, '22/04/2024 17:45:06'),
(6, 1, 86, '22/04/2024 17:52:38'),
(7, 1, 59, '22/04/2024 17:56:49'),
(8, 1, 226, '22/04/2024 18:10:29'),
(9, 1, 5, '22/04/2024 18:11:11'),
(10, 1, 174, '22/04/2024 18:25:17'),
(11, 1, 33, '22/04/2024 18:25:27'),
(12, 1, 142, '22/04/2024 18:32:45'),
(13, 1, 22, '22/04/2024 18:35:59'),
(14, 1, 128, '22/04/2024 18:41:46'),
(15, 1, 148, '22/04/2024 18:42:02'),
(16, 1, 192, '22/04/2024 18:42:19'),
(17, 1, 124, '22/04/2024 18:42:47'),
(18, 1, 56, '22/04/2024 18:43:07'),
(19, 1, 131, '22/04/2024 18:43:37'),
(20, 1, 147, '22/04/2024 18:43:40'),
(21, 1, 176, '22/04/2024 18:44:13'),
(22, 1, 31, '22/04/2024 18:44:19'),
(23, 1, 69, '22/04/2024 18:44:26'),
(24, 1, 125, '22/04/2024 18:44:50'),
(25, 1, 72, '22/04/2024 18:44:57'),
(26, 1, 156, '22/04/2024 18:45:00'),
(27, 1, 25, '22/04/2024 18:45:07'),
(28, 1, 66, '22/04/2024 18:45:15'),
(29, 1, 65, '22/04/2024 18:45:43'),
(30, 1, 67, '22/04/2024 18:46:10'),
(31, 1, 24, '22/04/2024 18:46:55'),
(32, 1, 116, '22/04/2024 18:47:02'),
(33, 1, 76, '22/04/2024 18:47:19'),
(34, 1, 163, '22/04/2024 18:48:28'),
(35, 1, 87, '22/04/2024 18:48:59'),
(36, 1, 50, '22/04/2024 18:49:06'),
(37, 1, 129, '22/04/2024 18:50:39'),
(38, 1, 13, '22/04/2024 18:51:48'),
(39, 1, 10, '22/04/2024 18:52:31'),
(40, 1, 14, '22/04/2024 18:52:36'),
(41, 1, 172, '22/04/2024 18:53:09'),
(42, 1, 171, '22/04/2024 18:53:39'),
(43, 1, 11, '22/04/2024 18:53:45'),
(44, 1, 130, '22/04/2024 18:54:18'),
(45, 1, 152, '22/04/2024 18:55:27'),
(46, 1, 68, '22/04/2024 18:55:37'),
(47, 1, 150, '22/04/2024 19:00:52'),
(48, 1, 64, '22/04/2024 19:00:58'),
(49, 1, 186, '22/04/2024 19:01:06'),
(50, 1, 77, '22/04/2024 19:04:23'),
(51, 1, 121, '22/04/2024 19:04:36'),
(52, 1, 36, '22/04/2024 19:05:15'),
(53, 1, 180, '22/04/2024 19:05:24'),
(54, 1, 93, '22/04/2024 19:07:02'),
(55, 1, 35, '22/04/2024 19:07:18'),
(56, 1, 146, '22/04/2024 19:09:22'),
(57, 1, 118, '22/04/2024 19:10:44'),
(58, 1, 39, '22/04/2024 19:12:15'),
(59, 1, 37, '22/04/2024 19:14:04'),
(60, 1, 32, '22/04/2024 19:14:10'),
(61, 1, 38, '22/04/2024 19:14:31'),
(62, 1, 48, '22/04/2024 19:18:39'),
(63, 1, 168, '22/04/2024 19:22:11'),
(64, 1, 126, '22/04/2024 19:22:32'),
(65, 1, 170, '22/04/2024 19:24:23'),
(66, 1, 45, '22/04/2024 19:24:36'),
(67, 1, 158, '22/04/2024 19:30:04'),
(68, 1, 178, '22/04/2024 19:31:41'),
(69, 1, 44, '22/04/2024 19:33:58'),
(70, 1, 30, '22/04/2024 19:34:18'),
(71, 1, 26, '22/04/2024 19:39:01'),
(72, 1, 143, '22/04/2024 19:43:59'),
(73, 1, 75, '22/04/2024 19:45:37'),
(74, 1, 191, '22/04/2024 19:46:47'),
(75, 1, 188, '22/04/2024 19:54:25'),
(76, 1, 155, '22/04/2024 20:49:59'),
(77, 1, 43, '22/04/2024 20:50:10'),
(78, 1, 154, '22/04/2024 20:50:40'),
(79, 3, 1, '23/04/2024 21:27:41'),
(80, 3, 185, '23/04/2024 21:55:28'),
(81, 3, 37, '24/04/2024 19:12:50'),
(82, 3, 24, '24/04/2024 19:16:00'),
(83, 3, 83, '24/04/2024 19:16:57'),
(84, 3, 85, '24/04/2024 19:17:04'),
(85, 3, 149, '24/04/2024 19:18:14'),
(86, 3, 129, '24/04/2024 19:18:28'),
(87, 3, 77, '24/04/2024 19:18:39'),
(88, 3, 177, '24/04/2024 19:18:44'),
(89, 3, 50, '24/04/2024 19:18:49'),
(90, 3, 31, '24/04/2024 19:18:55'),
(91, 3, 128, '24/04/2024 19:19:01'),
(92, 3, 147, '24/04/2024 19:19:14'),
(93, 3, 56, '24/04/2024 19:19:22'),
(94, 3, 176, '24/04/2024 19:19:37'),
(95, 3, 35, '24/04/2024 19:19:44'),
(96, 3, 156, '24/04/2024 19:19:50'),
(97, 3, 182, '24/04/2024 19:19:54'),
(98, 3, 33, '24/04/2024 19:19:57'),
(99, 3, 222, '24/04/2024 19:20:10'),
(100, 3, 124, '24/04/2024 19:22:07'),
(101, 3, 126, '24/04/2024 19:22:27'),
(102, 3, 14, '24/04/2024 19:22:52'),
(103, 3, 192, '24/04/2024 19:23:56'),
(104, 3, 5, '24/04/2024 19:24:27'),
(105, 3, 142, '24/04/2024 19:24:32'),
(106, 3, 150, '24/04/2024 19:25:27'),
(107, 3, 197, '24/04/2024 19:34:06'),
(108, 3, 59, '24/04/2024 19:39:06'),
(109, 3, 120, '24/04/2024 19:39:47'),
(110, 3, 158, '24/04/2024 19:54:47'),
(111, 3, 188, '24/04/2024 19:54:52'),
(112, 3, 30, '24/04/2024 19:59:36'),
(113, 3, 7, '24/04/2024 20:01:58'),
(114, 3, 232, '24/04/2024 20:02:41'),
(115, 3, 22, '24/04/2024 20:06:23'),
(116, 3, 25, '24/04/2024 20:26:03'),
(118, 4, 243, '14/05/2024 15:47:28'),
(120, 4, 242, '14/05/2024 15:52:44'),
(121, 4, 1, '14/05/2024 16:00:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
