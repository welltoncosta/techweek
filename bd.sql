-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 11:03 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u686345830_techweek_utfpr`
--

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
  `status` enum('pendente','aprovado','rejeitado','excluido') DEFAULT 'pendente',
  `observacao` text DEFAULT NULL,
  `data_envio` timestamp NULL DEFAULT current_timestamp(),
  `data_avaliacao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `comprovantes`
--


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
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `token_recuperacao` varchar(64) DEFAULT NULL,
  `expiracao_token` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `participantes`
--


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
  `valor` decimal(10,2) DEFAULT NULL,
  `data` varchar(50) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `comprovante_id` int(11) DEFAULT NULL,
  `participante_id` int(11) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `transacoes`
--


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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categorias_transacoes`
--
ALTER TABLE `categorias_transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comprovantes`
--
ALTER TABLE `comprovantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `inscricoes_atividades`
--
ALTER TABLE `inscricoes_atividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `precos_inscricao`
--
ALTER TABLE `precos_inscricao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

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
