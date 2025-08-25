-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 25, 2025 at 09:37 PM
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
  `hora_inicio` timestamp NOT NULL,
  `hora_fim` timestamp NULL DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `atividades`
--

INSERT INTO `atividades` (`id`, `titulo`, `tipo`, `palestrante`, `singular_plural`, `sala`, `vagas`, `data`, `horario`, `ativa`, `hora_inicio`, `hora_fim`, `hash`) VALUES
(1, 'Palestra: Como programar um computador quÃ¢ntico', 'Palestra', 'Evandro C. R. da Rosa - Co-fundador da Quantuloop, start-up brasileira de computaÃ§Ã£o quÃ¢ntica, e membro do Grupo de ComputaÃ§Ã£o QuÃ¢ntica da UFSC (GCQ-UFSC)', NULL, 'Anfiteatro (Bloco A)', '20', 'Segunda-feira - 22/04/2024', '19:15', '1', '2025-08-25 18:41:19', '2025-08-25 19:41:19', NULL),
(3, 'Palestra: Carreira na Ã¡rea de TI e experiÃªncia no Parque TecnolÃ³gico de Itaipu (PTI) ', 'Palestra', 'Felipe Theodoro GuimarÃ£es', NULL, 'Anfiteatro (Bloco A)', '20', 'Quarta-feira - 24/04/2024', '19:15', '1', '2025-08-25 18:41:19', '2025-08-25 19:41:19', NULL),
(4, 'Momento Cultural - Campeonatos de Xadrez, Quake e TÃªnis de mesa; FIFA no playstation 5; MÃºsica.', 'Palestra', 'ResponsÃ¡veis: Wellton Costa, Marcos Tenório e CASIS', NULL, 'Centro de ConvivÃªncia', '20', 'Quarta-feira - 24/04/2024', '21:20', '1', '2025-08-25 18:41:19', '2025-08-25 19:41:19', NULL);

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
  `data_cadastro` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comprovantes`
--
ALTER TABLE `comprovantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inscricoes_atividades`
--
ALTER TABLE `inscricoes_atividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
