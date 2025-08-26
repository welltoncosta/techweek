-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 26, 2025 at 09:31 PM
-- Server version: 11.8.2-MariaDB-1 from Debian
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
  `ativa` varchar(10) DEFAULT NULL,
  `hora_inicio` varchar(30) DEFAULT NULL,
  `hora_fim` varchar(30) DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `atividades`
--

INSERT INTO `atividades` (`id`, `titulo`, `tipo`, `palestrante`, `singular_plural`, `sala`, `vagas`, `data`, `horario`, `ativa`, `hora_inicio`, `hora_fim`, `hash`) VALUES
(1, 'Recepção', 'credenciamento', 'CASIS e Voluntários	', NULL, 'Hall de entrada (Bloco A)', '20', '2025-08-26', NULL, '0', '14:48', '12:45', NULL),
(2, 'Apresentação da Orquestra da UTFPR	', 'mesa_redonda', 'Orquestra da UTFPR	', NULL, 'Anfiteatro (Bloco A)', '200', '2025-08-26', NULL, '0', '20:55', '22:55', NULL),
(3, 'Palestra: Como programar um computador quântico', 'palestra', 'Evandro C. R. da Rosa (Co-fundador da Quantuloop, start-up brasileira de computação quântica, e membro do Grupo de Computação Quântica da UFSC (GCQ-UFSC))', NULL, 'Anfiteatro (Bloco A)', '200', '2025-08-27', NULL, '0', '13:02', '13:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comprovantes`
--

CREATE TABLE `comprovantes` (
  `id` int(11) NOT NULL,
  `participante_id` int(11) NOT NULL,
  `arquivo` varchar(255) NOT NULL,
  `tipo_arquivo` enum('pdf','imagem') NOT NULL,
  `status` enum('pendente','aprovado','rejeitado') DEFAULT 'pendente',
  `observacao` text DEFAULT NULL,
  `data_envio` timestamp NULL DEFAULT current_timestamp(),
  `data_avaliacao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `comprovantes`
--

INSERT INTO `comprovantes` (`id`, `participante_id`, `arquivo`, `tipo_arquivo`, `status`, `observacao`, `data_envio`, `data_avaliacao`) VALUES
(1, 1, 'comprovantes/68acef96c50576.71638245.png', 'imagem', 'aprovado', NULL, '2025-08-25 23:19:50', '2025-08-25 23:24:45'),
(2, 1, 'comprovantes/68acf0850db977.82233658.png', 'imagem', 'aprovado', NULL, '2025-08-25 23:23:49', '2025-08-25 23:24:42'),
(3, 1, 'comprovantes/68acf1133f3576.19367857.jpg', 'imagem', 'aprovado', NULL, '2025-08-25 23:26:11', '2025-08-25 23:27:01'),
(4, 1, 'comprovantes/68ad9e6db56b04.34447812.jpeg', 'imagem', 'aprovado', NULL, '2025-08-26 11:45:49', '2025-08-26 21:01:56');

-- --------------------------------------------------------

--
-- Table structure for table `inscricoes_atividades`
--

CREATE TABLE `inscricoes_atividades` (
  `id` int(11) NOT NULL,
  `participante_id` int(11) NOT NULL,
  `atividade_id` int(11) NOT NULL,
  `data_inscricao` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participantes`
--

CREATE TABLE `participantes` (
  `id` int(11) NOT NULL,
  `administrador` int(1) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `hash` varchar(100) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `cpf` varchar(14) NOT NULL,
  `codigo_barra` varchar(20) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `instituicao` varchar(255) NOT NULL,
  `voucher` varchar(50) DEFAULT NULL,
  `isento_pagamento` tinyint(1) DEFAULT 0,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `participantes`
--

INSERT INTO `participantes` (`id`, `administrador`, `tipo`, `hash`, `nome`, `email`, `senha`, `cpf`, `codigo_barra`, `telefone`, `instituicao`, `voucher`, `isento_pagamento`, `data_cadastro`) VALUES
(1, 1, 'administrador', 'b715770d9ed1f0705fc06079769d1af0', 'Wellton Costa de Oliveira', 'contato@wellton.com.br', '$2y$12$EORNhANBcuYV8uxK/I4Gb.DX4ouWnPjSrmtU12dnJenFoZ.2wxIWK', '857.922.682-15', 'TW20250001', '(46) 99105-7348', 'UTFPR FB', NULL, 0, '2025-08-25 22:04:04');

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
-- Indexes for dumped tables
--

--
-- Indexes for table `atividades`
--
ALTER TABLE `atividades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comprovantes`
--
ALTER TABLE `comprovantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `participante_id` (`participante_id`);

--
-- Indexes for table `inscricoes_atividades`
--
ALTER TABLE `inscricoes_atividades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_inscricao` (`participante_id`,`atividade_id`),
  ADD KEY `atividade_id` (`atividade_id`);

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
-- AUTO_INCREMENT for table `atividades`
--
ALTER TABLE `atividades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comprovantes`
--
ALTER TABLE `comprovantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inscricoes_atividades`
--
ALTER TABLE `inscricoes_atividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comprovantes`
--
ALTER TABLE `comprovantes`
  ADD CONSTRAINT `comprovantes_ibfk_1` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
