-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 29, 2025 at 06:19 PM
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
(1, 'Recepção', 'credenciamento', 'CASIS e Voluntários	', NULL, 'Hall de entrada (Bloco A)', '20', '2025-08-26', NULL, '1', '14:48', '12:45', NULL),
(2, 'Apresentação da Orquestra da UTFPR	', 'workshop', 'Orquestra da UTFPR	', NULL, 'Anfiteatro (Bloco A)', '200', '2025-08-26', '20:55 - 22:55', '1', '20:55', '22:55', NULL),
(3, 'Palestra: Como programar um computador quântico', 'palestra', 'Evandro C. R. da Rosa (Co-fundador da Quantuloop, start-up brasileira de computação quântica, e membro do Grupo de Computação Quântica da UFSC (GCQ-UFSC))', NULL, 'Anfiteatro (Bloco A)', '200', '2025-08-27', NULL, '1', '13:02', '13:03', NULL),
(4, 'TESTABDIII', 'workshop', 'teste', NULL, 'teste', '33', '2025-08-25', '08:00 - 12:00', '1', '08:00', '12:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categorias_transacoes`
--

CREATE TABLE `categorias_transacoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `categorias_transacoes`
--

INSERT INTO `categorias_transacoes` (`id`, `nome`, `tipo`) VALUES
(1, 'Inscrições', 'entrada'),
(2, 'Patrocínios', 'entrada'),
(3, 'Passagens', 'saida'),
(4, 'Alimentação', 'saida'),
(5, 'Cortesias', 'saida'),
(6, 'Vouchers', 'saida'),
(7, 'Coffee Break', 'saida'),
(8, 'Outros', 'entrada'),
(9, 'Outros', 'saida');

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
(1, 1, 'comprovantes/68b1e1b9c5e686.20477136.png', 'imagem', 'aprovado', NULL, '2025-08-29 17:22:01', '2025-08-29 17:23:42'),
(2, 2, 'comprovantes/68b1e1f38d60b9.68946331.pdf', 'pdf', 'aprovado', NULL, '2025-08-29 17:22:59', '2025-08-29 17:23:40'),
(3, 2, 'comprovantes/68b1e276a90823.94169927.pdf', 'pdf', 'aprovado', NULL, '2025-08-29 17:25:10', '2025-08-29 17:32:55'),
(4, 3, 'comprovantes/68b1e516b2e027.45735985.pdf', 'pdf', 'aprovado', NULL, '2025-08-29 17:36:22', '2025-08-29 17:36:53'),
(5, 3, 'comprovantes/68b1e891ae62e9.46919825.jpeg', 'imagem', 'aprovado', NULL, '2025-08-29 17:51:13', '2025-08-29 17:51:24'),
(6, 1, 'comprovantes/68b1e9794918a6.12461843.png', 'imagem', 'aprovado', NULL, '2025-08-29 17:55:05', '2025-08-29 17:55:12'),
(7, 6, 'comprovantes/68b1e98446d2e3.08660517.jpeg', 'imagem', 'aprovado', NULL, '2025-08-29 17:55:16', '2025-08-29 17:55:31'),
(8, 5, 'comprovantes/68b1e9afdcff08.91165678.png', 'imagem', 'aprovado', NULL, '2025-08-29 17:55:59', '2025-08-29 17:56:15'),
(9, 7, 'comprovantes/68b1ea428282c5.77721656.png', 'imagem', 'aprovado', NULL, '2025-08-29 17:58:26', '2025-08-29 17:58:48'),
(10, 8, 'comprovantes/68b1eb18a0b205.02224311.png', 'imagem', 'aprovado', NULL, '2025-08-29 18:02:00', '2025-08-29 18:03:03'),
(11, 7, 'comprovantes/68b1eb7e8d1bd9.21509189.png', 'imagem', 'aprovado', NULL, '2025-08-29 18:03:42', '2025-08-29 18:04:10'),
(12, 9, 'comprovantes/68b1ee99e78279.61607144.png', 'imagem', 'rejeitado', NULL, '2025-08-29 18:16:57', '2025-08-29 18:17:13'),
(13, 10, 'comprovantes/68b1ef329bf554.24431036.png', 'imagem', 'rejeitado', NULL, '2025-08-29 18:19:30', '2025-08-29 18:19:39');

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

--
-- Dumping data for table `inscricoes_atividades`
--

INSERT INTO `inscricoes_atividades` (`id`, `participante_id`, `atividade_id`, `data_inscricao`) VALUES
(1, 2, 3, '2025-08-29 17:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `participantes`
--

CREATE TABLE `participantes` (
  `id` int(11) NOT NULL,
  `administrador` int(1) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `tipo_inscricao` varchar(50) DEFAULT NULL,
  `lote_inscricao` varchar(50) DEFAULT NULL,
  `valor_pago` decimal(10,2) DEFAULT NULL,
  `hash` varchar(100) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `cpf` varchar(14) NOT NULL,
  `codigo_barra` varchar(20) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `instituicao` varchar(255) NOT NULL,
  `preco_inscricao` decimal(10,2) DEFAULT NULL,
  `voucher` varchar(50) DEFAULT NULL,
  `isento_pagamento` tinyint(1) DEFAULT 0,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `participantes`
--

INSERT INTO `participantes` (`id`, `administrador`, `tipo`, `tipo_inscricao`, `lote_inscricao`, `valor_pago`, `hash`, `nome`, `email`, `senha`, `cpf`, `codigo_barra`, `telefone`, `instituicao`, `preco_inscricao`, `voucher`, `isento_pagamento`, `data_cadastro`) VALUES
(1, 1, 'administrador', 'universitario_ti', '1', 25.00, '0fba6bd9bc288bfa147aa283a54b40ec', 'Wellton Costa', 'wcoliveira@utfpr.edu.br', '$2y$12$/fwM0/2z8njU/.onj61fj.p03UWQ3kXIBGV/LcHo0a701dBeqXGUS', '857.922.682-15', 'TW20250001', '', '', 25.00, NULL, 0, '2025-08-29 17:11:42'),
(2, 1, 'administrador', 'universitario_ti', '1', 25.00, '6e5722f0fbb2b6d1c36cd9a6645c19bf', 'Marcos Mincov Ten&oacute;rio', 'marcostenorio@utfpr.edu.br', '$2y$12$0wPDjrIRLgjiyU7tFvBY8.JcJ7Dg4dzW0.uHBznS9Yz3BF73y9oTG', '066.178.719-28', 'TW20250002', '', '', 25.00, NULL, 0, '2025-08-29 17:14:05'),
(3, 0, 'participante', 'ensino_medio', 'regular', 50.00, '6d3a812f97e8795a0d133e23975ff13f', 'pessoa anormal', 'nnn@nnn.com', '$2y$12$.VFpxs2z7DOGRC5M6kmtcepSaB780gov2XB1dX4evatR.hHTt3zdC', '050.845.729-78', 'TW20250003', '', '', 0.00, NULL, 0, '2025-08-29 17:36:11'),
(4, 0, 'participante', 'ensino_medio', 'regular', NULL, '3f2cbd32f4e01f6a3f924bb3b99cebb9', 'teste', 'teste@teste.com', '$2y$12$pVjGrs.MvdOJKK3XVDjPgu0l.pKEHVmgJFATkodjeYL6H1PwJUvjW', '411.823.702-41', 'TW20250004', '', 'teste', 0.00, NULL, 0, '2025-08-29 17:52:01'),
(5, 0, 'participante', 'universitario_ti', '1', 25.00, '0b087d73e8eb4a041ee519ba96ad9be4', 'teste 2', 'teste2@teste.com', '$2y$12$5GuDM12rTz7YAI0oMLaC9u7sC9J4KT0q2wvlxWl40EDZxfcSNVaQ2', '785.080.587-99', 'TW20250005', '', '', 25.00, NULL, 0, '2025-08-29 17:53:07'),
(6, 0, 'participante', 'universitario_ti', '1', 25.00, 'f4ab3b05d87142d90c817fd9492c6e91', 'pessoa anormal 2', 'ano@ano.com', '$2y$12$MpIhRLurDarl97x9PpCFTO5KObrXGYA3cPr5BH3kDDZb8HIufEdEq', '276.823.070-72', 'TW20250006', '', '', 25.00, NULL, 0, '2025-08-29 17:55:01'),
(7, 0, 'participante', 'universitario_ti', '1', 25.00, '2a50246b0dff8906287321a130972b1c', 'teste3', 'teste3@teste.com', '$2y$12$NeUso/44he1B1OOHlk4Or.VImMTi5aVcmnQdZCZiYZm20TqBF4XDG', '073.503.112-65', 'TW20250007', '', '', 25.00, NULL, 0, '2025-08-29 17:58:19'),
(8, 0, 'participante', 'publico_geral', 'regular', NULL, '2ae5281e00f5c2d89b258201914404a8', 'pessoa externa para nao contabilizar no numero', 'aa@aa.com', '$2y$12$fVcPkxQOhgdA6r8xkLYZd.StbPMmHIiMo5u4pJQco/skYouqJq0NO', '715.541.050-05', 'TW20250008', '', '', 0.00, NULL, 0, '2025-08-29 17:59:04'),
(9, 0, 'participante', 'universitario_ti', '1', NULL, '300ba577ce238575bb0be837caa291aa', 'teste 4', 'teste4@teste.com', '$2y$12$IKa1iy2CuXNoUecVi4dKXuPP.lyaoGta/3uckMD7gfSVGDCPS3pia', '289.802.348-56', 'TW20250009', '', '', 25.00, NULL, 0, '2025-08-29 18:16:36'),
(10, 0, 'participante', 'universitario_ti', '1', NULL, '4be36b38d464fe4f30d1247ea6b5c00f', 'estudante de ti ti', 'ti@ti.com', '$2y$12$gKd3J.w/vXhpPupepczrx.pid41XwxycE77Y4SbexxfLciivTUqDi', '170.193.850-28', 'TW20250010', '', '', 25.00, NULL, 0, '2025-08-29 18:19:07');

-- --------------------------------------------------------

--
-- Table structure for table `precos_inscricao`
--

CREATE TABLE `precos_inscricao` (
  `id` int(11) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `lote` varchar(50) DEFAULT 'regular',
  `ativo` tinyint(1) DEFAULT 1,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `precos_inscricao`
--

INSERT INTO `precos_inscricao` (`id`, `categoria`, `descricao`, `valor`, `lote`, `ativo`, `data_inicio`, `data_fim`) VALUES
(1, 'universitario_ti', 'Universitário de TI - 1° Lote', 25.00, '1', 1, NULL, NULL),
(2, 'universitario_ti', 'Universitário de TI - 2° Lote', 35.00, '2', 1, NULL, NULL),
(3, 'ensino_medio', 'Ensino Médio - 1° Lote', 15.00, '1', 1, NULL, NULL),
(4, 'publico_geral', 'Público Geral - 1° Lote', 50.00, '1', 1, NULL, NULL),
(5, 'hackathon_inscrito', 'Hackathon (Inscritos no evento)', 15.00, 'regular', 1, NULL, NULL),
(6, 'hackathon_nao_inscrito', 'Hackathon (Não inscritos no evento)', 50.00, 'regular', 1, NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` varchar(50) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `comprovante_id` int(11) DEFAULT NULL,
  `participante_id` int(11) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `transacoes`
--

INSERT INTO `transacoes` (`id`, `categoria_id`, `descricao`, `valor`, `data`, `tipo`, `comprovante_id`, `participante_id`, `data_registro`) VALUES
(1, 1, 'Pagamento de inscrição - participante', 25.00, '2025-08-29', 'entrada', 2, 2, '2025-08-29 17:23:40'),
(2, 1, 'Pagamento de inscrição - participante', 25.00, '2025-08-29', 'entrada', 1, 1, '2025-08-29 17:23:42'),
(3, 1, 'Pagamento de inscrição - administrador', 25.00, '2025-08-29', 'entrada', 3, 2, '2025-08-29 17:32:55'),
(4, 1, 'Pagamento de inscrição - participante', 50.00, '2025-08-29', 'entrada', 4, 3, '2025-08-29 17:36:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atividades`
--
ALTER TABLE `atividades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorias_transacoes`
--
ALTER TABLE `categorias_transacoes`
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
-- Indexes for table `precos_inscricao`
--
ALTER TABLE `precos_inscricao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `comprovante_id` (`comprovante_id`),
  ADD KEY `participante_id` (`participante_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `atividades`
--
ALTER TABLE `atividades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categorias_transacoes`
--
ALTER TABLE `categorias_transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comprovantes`
--
ALTER TABLE `comprovantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `inscricoes_atividades`
--
ALTER TABLE `inscricoes_atividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `precos_inscricao`
--
ALTER TABLE `precos_inscricao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comprovantes`
--
ALTER TABLE `comprovantes`
  ADD CONSTRAINT `comprovantes_ibfk_1` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inscricoes_atividades`
--
ALTER TABLE `inscricoes_atividades`
  ADD CONSTRAINT `inscricoes_atividades_ibfk_1` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transacoes`
--
ALTER TABLE `transacoes`
  ADD CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_transacoes` (`id`),
  ADD CONSTRAINT `transacoes_ibfk_2` FOREIGN KEY (`comprovante_id`) REFERENCES `comprovantes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transacoes_ibfk_3` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
