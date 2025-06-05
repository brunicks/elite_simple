-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/05/2025 às 15:37
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `concessionaria`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carros`
--

CREATE TABLE `carros` (
  `id` int(11) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `ano` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `km` int(11) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Ativo, 0 = Inativo (Soft Delete)',
  `cv` int(11) DEFAULT NULL,
  `motor` varchar(50) DEFAULT NULL,
  `torque` varchar(50) DEFAULT NULL,
  `combustivel` varchar(30) DEFAULT NULL,
  `transmissao` varchar(30) DEFAULT NULL,
  `portas` int(11) DEFAULT NULL,
  `cor` varchar(30) DEFAULT NULL,
  `consumo_medio` decimal(5,2) DEFAULT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `carros`
--

INSERT INTO `carros` (`id`, `modelo`, `marca`, `ano`, `preco`, `km`, `imagem`, `created_at`, `updated_at`, `ativo`, `cv`, `motor`, `torque`, `combustivel`, `transmissao`, `portas`, `cor`, `consumo_medio`, `descricao`) VALUES
(1, 'S 500', 'Mercedes-Benz', 2020, 185000.00, 22000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 450, 'V8', '650 Nm', 'Gasolina', 'Automática', 4, 'Preto', 8.50, 'Sedã de luxo, excelente estado, completo.'),
(2, '740i', 'BMW', 2019, 170000.00, 35000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 340, 'V6', '450 Nm', 'Gasolina', 'Automática', 4, 'Prata', 9.20, 'BMW topo de linha, tecnologia embarcada.'),
(3, 'A8 L', 'Audi', 2018, 160000.00, 40000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 340, 'V6', '500 Nm', 'Gasolina', 'Automática', 4, 'Branco', 9.80, 'Luxo, conforto e desempenho.'),
(4, 'LS 500h', 'Lexus', 2021, 195000.00, 18000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 359, 'V6 Híbrido', '350 Nm', 'Híbrido', 'Automática', 4, 'Cinza', 7.60, 'Híbrido de luxo, baixíssimo consumo.'),
(5, 'Panamera', 'Porsche', 2017, 200000.00, 32000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 550, 'V8', '700 Nm', 'Gasolina', 'Automática', 4, 'Azul', 8.00, 'Esportivo de luxo, alto desempenho.'),
(6, 'XJ 3.0', 'Jaguar', 2016, 130000.00, 50000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 340, 'V6', '450 Nm', 'Gasolina', 'Automática', 4, 'Preto', 10.10, 'Sedã inglês, acabamento premium.'),
(7, 'Model S Long Range', 'Tesla', 2022, 180000.00, 12000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 670, 'Elétrico', '900 Nm', 'Elétrico', 'Automática', 5, 'Branco', 0.00, 'Elétrico de luxo, autonomia estendida.'),
(8, 'Quattroporte', 'Maserati', 2019, 175000.00, 27000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 430, 'V6', '580 Nm', 'Gasolina', 'Automática', 4, 'Vermelho', 8.90, 'Design italiano, esportividade e luxo.'),
(9, 'CT6', 'Cadillac', 2018, 125000.00, 48000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 335, 'V6', '385 Nm', 'Gasolina', 'Automática', 4, 'Cinza', 10.50, 'Sedã americano, muito conforto.'),
(10, 'G90', 'Genesis', 2020, 145000.00, 22000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 365, 'V6', '510 Nm', 'Gasolina', 'Automática', 4, 'Prata', 9.00, 'Sedã executivo, acabamento refinado.'),
(11, 'S60 T8', 'Volvo', 2021, 120000.00, 21000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 407, 'Híbrido', '640 Nm', 'Híbrido', 'Automática', 4, 'Azul', 7.80, 'Híbrido plug-in, segurança e desempenho.'),
(12, 'A7 Sportback', 'Audi', 2019, 135000.00, 28000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 340, 'V6', '500 Nm', 'Gasolina', 'Automática', 4, 'Preto', 9.10, 'Design esportivo, tecnologia embarcada.'),
(13, 'CLS 450', 'Mercedes-Benz', 2018, 140000.00, 36000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 367, 'V6', '500 Nm', 'Gasolina', 'Automática', 4, 'Branco', 9.60, 'Coupé de luxo, visual marcante.'),
(14, '530e', 'BMW', 2020, 110000.00, 25000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 252, 'Híbrido', '420 Nm', 'Híbrido', 'Automática', 4, 'Prata', 8.30, 'Híbrido plug-in, economia e desempenho.'),
(15, 'XF 2.0', 'Jaguar', 2017, 95000.00, 48000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 250, 'I4', '365 Nm', 'Gasolina', 'Automática', 4, 'Cinza', 10.20, 'Sedã esportivo, ótimo custo-benefício.'),
(16, 'A6 2.0 TFSI', 'Audi', 2018, 90000.00, 52000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 252, 'I4', '370 Nm', 'Gasolina', 'Automática', 4, 'Preto', 11.20, 'Semi-luxo, acabamento refinado.'),
(17, 'E 250', 'Mercedes-Benz', 2017, 89000.00, 57000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 211, 'I4', '350 Nm', 'Gasolina', 'Automática', 4, 'Prata', 12.00, 'Sedã executivo, muito confortável.'),
(18, 'ES 300h', 'Lexus', 2019, 95000.00, 40000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 218, 'Híbrido', '221 Nm', 'Híbrido', 'Automática', 4, 'Branco', 7.50, 'Híbrido, conforto e economia.'),
(19, 'S90 T5', 'Volvo', 2019, 98000.00, 39000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 254, 'I4', '350 Nm', 'Gasolina', 'Automática', 4, 'Azul', 9.70, 'Sedã grande, tecnologia de ponta.'),
(20, 'A4 2.0 TFSI', 'Audi', 2017, 75000.00, 60000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 190, 'I4', '320 Nm', 'Gasolina', 'Automática', 4, 'Vermelho', 12.00, 'Compacto premium, ótimo desempenho.'),
(21, '320i', 'BMW', 2018, 78000.00, 55000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 184, 'I4', '270 Nm', 'Gasolina', 'Automática', 4, 'Preto', 12.10, 'Sedã esportivo, excelente dirigibilidade.'),
(22, 'C 200', 'Mercedes-Benz', 2016, 69000.00, 75000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 184, 'I4', '300 Nm', 'Gasolina', 'Automática', 4, 'Branco', 12.50, 'Sedã médio premium, ótimo estado.'),
(23, 'A3 Sedan', 'Audi', 2017, 70000.00, 60000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 150, 'I4', '250 Nm', 'Flex', 'Automática', 4, 'Prata', 13.00, 'Compacto premium, econômico.'),
(24, 'IS 300', 'Lexus', 2018, 85000.00, 48000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 245, 'I4', '350 Nm', 'Gasolina', 'Automática', 4, 'Cinza', 10.80, 'Sedã esportivo, acabamento refinado.'),
(25, 'S60 T5', 'Volvo', 2017, 76000.00, 65000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 254, 'I4', '350 Nm', 'Gasolina', 'Automática', 4, 'Branco', 11.00, 'Sedã médio, segurança e desempenho.'),
(26, 'Passat Highline', 'Volkswagen', 2018, 78000.00, 53000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 220, 'I4', '350 Nm', 'Gasolina', 'Automática', 4, 'Cinza', 12.20, 'Sedã executivo, ótimo custo-benefício.'),
(27, 'Fusion Titanium', 'Ford', 2019, 82000.00, 42000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 248, 'I4', '366 Nm', 'Gasolina', 'Automática', 4, 'Preto', 11.60, 'Sedã grande, completo e confortável.'),
(28, 'Camry XLE', 'Toyota', 2018, 90000.00, 39000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 178, 'I4', '231 Nm', 'Gasolina', 'Automática', 4, 'Prata', 11.80, 'Sedã grande, conforto e confiabilidade.'),
(29, 'Accord EX', 'Honda', 2017, 88000.00, 45000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 192, 'I4', '260 Nm', 'Gasolina', 'Automática', 4, 'Branco', 12.00, 'Sedã médio, excelente reputação.'),
(30, 'Altima SL', 'Nissan', 2017, 75000.00, 58000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 182, 'I4', '244 Nm', 'Gasolina', 'Automática', 4, 'Preto', 12.20, 'Sedã médio, confortável e potente.'),
(31, 'Civic Touring', 'Honda', 2018, 70000.00, 50000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 173, 'I4', '220 Nm', 'Flex', 'Automática', 4, 'Cinza', 13.50, 'Sedã compacto, muito econômico.'),
(32, 'Corolla Altis', 'Toyota', 2019, 75000.00, 42000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 154, 'I4', '200 Nm', 'Flex', 'Automática', 4, 'Prata', 13.80, 'Sedã médio, líder de mercado.'),
(33, 'Jetta GLI', 'Volkswagen', 2017, 68000.00, 60000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 211, 'I4', '280 Nm', 'Gasolina', 'Automática', 4, 'Vermelho', 11.90, 'Sedã esportivo, ótimo desempenho.'),
(34, 'Elantra GLS', 'Hyundai', 2018, 62000.00, 70000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 167, 'I4', '201 Nm', 'Flex', 'Automática', 4, 'Azul', 13.00, 'Sedã compacto, econômico e confortável.'),
(35, 'Focus Titanium', 'Ford', 2017, 58000.00, 65000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 178, 'I4', '240 Nm', 'Flex', 'Automática', 4, 'Branco', 13.50, 'Hatch médio, ótimo custo-benefício.'),
(36, 'Golf Highline', 'Volkswagen', 2017, 60000.00, 60000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 150, 'I4', '250 Nm', 'Flex', 'Automática', 4, 'Prata', 12.50, 'Hatch médio, referência em dirigibilidade.'),
(37, 'Cruze LTZ', 'Chevrolet', 2018, 57000.00, 65000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 153, 'I4', '240 Nm', 'Flex', 'Automática', 4, 'Cinza', 13.20, 'Sedã médio, completo e econômico.'),
(38, 'Sentra SL', 'Nissan', 2017, 55000.00, 70000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 140, 'I4', '201 Nm', 'Flex', 'Automática', 4, 'Preto', 13.80, 'Sedã médio, ótimo custo-benefício.'),
(39, 'C4 Lounge', 'Citroën', 2018, 52000.00, 75000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 173, 'I4', '240 Nm', 'Flex', 'Automática', 4, 'Branco', 13.70, 'Sedã médio, conforto e espaço interno.'),
(40, 'Cerato SX', 'Kia', 2019, 54000.00, 60000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 167, 'I4', '201 Nm', 'Flex', 'Automática', 4, 'Vermelho', 13.00, 'Sedã compacto, moderno e econômico.'),
(41, 'Corolla GLi', 'Toyota', 2015, 35000.00, 90000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 140, 'I4', '180 Nm', 'Flex', 'Automática', 4, 'Prata', 12.50, 'Sedã compacto, manutenção barata.'),
(42, 'Civic LXR', 'Honda', 2016, 32000.00, 95000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 150, 'I4', '190 Nm', 'Flex', 'Manual', 4, 'Preto', 13.20, 'Sedã médio, muito confiável.'),
(43, 'Focus SE', 'Ford', 2014, 28000.00, 110000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 135, 'I4', '170 Nm', 'Flex', 'Manual', 4, 'Branco', 14.00, 'Hatch médio, ótimo custo-benefício.'),
(44, 'Elantra', 'Hyundai', 2018, 37000.00, 70000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 160, 'I4', '200 Nm', 'Flex', 'Automática', 4, 'Cinza', 12.80, 'Sedã compacto, ótimo consumo.'),
(45, 'Golf Comfortline', 'Volkswagen', 2017, 39000.00, 60000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 170, 'I4', '250 Nm', 'Flex', 'Automática', 4, 'Prata', 11.90, 'Hatch médio, referência no segmento.'),
(46, 'Onix LTZ', 'Chevrolet', 2018, 42000.00, 80000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 116, 'I3', '160 Nm', 'Flex', 'Automática', 4, 'Branco', 14.50, 'Hatch compacto, econômico e moderno.'),
(47, 'HB20 Premium', 'Hyundai', 2017, 41000.00, 85000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 128, 'I4', '160 Nm', 'Flex', 'Automática', 4, 'Azul', 13.90, 'Hatch compacto, ótimo custo-benefício.'),
(48, 'Argo Precision', 'Fiat', 2019, 43000.00, 60000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 139, 'I4', '139 Nm', 'Flex', 'Automática', 4, 'Vermelho', 13.80, 'Hatch compacto, moderno e econômico.'),
(49, 'Sandero Stepway', 'Renault', 2018, 35000.00, 90000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 118, 'I4', '156 Nm', 'Flex', 'Manual', 4, 'Prata', 13.50, 'Hatch aventureiro, manutenção barata.'),
(50, 'Ka SE', 'Ford', 2019, 32000.00, 80000, NULL, '2025-05-30 13:30:39', '2025-05-30 13:30:39', 1, 85, 'I3', '110 Nm', 'Flex', 'Manual', 4, 'Preto', 14.80, 'Hatch compacto, econômico e prático.'),
(51, 'X1 sDrive20i GP', 'BMW', 2023, 285000.00, 18000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 204, '2.0 Turbo', '300 Nm', 'Gasolina', 'Automática', 4, 'Branco', 12.00, 'SUV premium seminovo, motor turbo eficiente, tecnologia embarcada.'),
(52, 'EX30 Ultra', 'Volvo', 2024, 245000.00, 8000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 272, 'Elétrico', '343 Nm', 'Elétrico', 'Automática', 5, 'Azul', 0.00, 'SUV elétrico compacto, autonomia de 450 km, topo de linha.'),
(53, 'Macan', 'Porsche', 2022, 389000.00, 21000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 265, '2.0 Turbo', '400 Nm', 'Gasolina', 'Automática', 5, 'Preto', 10.50, 'SUV esportivo de luxo, excelente desempenho e acabamento.'),
(54, 'Q6 e-tron', 'Audi', 2024, 495000.00, 5000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 387, 'Elétrico', '545 Nm', 'Elétrico', 'Automática', 5, 'Cinza', 0.00, 'SUV elétrico de luxo, autonomia de 411 km, tecnologia de ponta.'),
(55, 'EX90 Twin Motor', 'Volvo', 2025, 670000.00, 3000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 524, 'Elétrico', '900 Nm', 'Elétrico', 'Automática', 7, 'Prata', 0.00, 'SUV elétrico superluxo, 7 lugares, autonomia de 450 km, LiDAR e IA embarcados.'),
(56, 'iX2 xDrive30', 'BMW', 2024, 340000.00, 9000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 313, 'Elétrico', '494 Nm', 'Elétrico', 'Automática', 5, 'Vermelho', 0.00, 'SUV elétrico premium, desempenho esportivo e design inovador.'),
(57, 'Classe E 300', 'Mercedes-Benz', 2023, 395000.00, 12000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 258, '2.0 Turbo', '400 Nm', 'Gasolina', 'Automática', 4, 'Preto', 11.00, 'Sedã executivo superluxo, conforto e tecnologia de referência.'),
(58, 'Aston Martin Vantage Roadster', 'Aston Martin', 2023, 1480000.00, 7000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 665, '4.0 V8 Biturbo', '800 Nm', 'Gasolina', 'Automática', 2, 'Verde', 8.00, 'Conversível superesportivo, luxo britânico e alta performance.'),
(59, 'Ferrari 12Cilindri', 'Ferrari', 2024, 3200000.00, 4000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 830, '6.5 V12', '678 Nm', 'Gasolina', 'Automática', 2, 'Vermelho', 6.50, 'Supercarro V12 aspirado, exclusividade e desempenho extremo.'),
(60, 'Lamborghini Urus SE', 'Lamborghini', 2024, 2850000.00, 6000, NULL, '2025-05-30 13:32:37', '2025-05-30 13:32:37', 1, 800, 'V8 Biturbo Híbrido', '950 Nm', 'Híbrido', 'Automática', 5, 'Amarelo', 8.50, 'SUV superluxo híbrido plug-in, potência e exclusividade máximas.');

-- --------------------------------------------------------


CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `carro_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE `financing_simulations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) DEFAULT NULL,
  `car_name` varchar(255) NOT NULL,
  `car_price` decimal(10,2) NOT NULL,
  `down_payment` decimal(10,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `term_months` int(11) NOT NULL,
  `monthly_payment` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
CREATE TABLE `recently_viewed` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','usuario') DEFAULT 'usuario',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Ativo, 0 = Inativo (Soft Delete)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `created_at`, `ativo`) VALUES
(1, 'Administrador', 'admin@concessionaria.com', '$2y$10$gdDgRVjY79SvLP.rHs1.OuS1.M43KZkaPo8xk4cMbovuVmundKB5u', 'admin', '2025-05-29 16:14:59', 1),
(3, 'bruno', 'brunicks02@gmail.com', '$2y$10$gdDgRVjY79SvLP.rHs1.OuS1.M43KZkaPo8xk4cMbovuVmundKB5u', 'usuario', '2025-05-29 16:26:12', 1);


ALTER TABLE `carros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ativo` (`ativo`);

ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorite` (`usuario_id`,`carro_id`),
  ADD KEY `carro_id` (`carro_id`);

ALTER TABLE `financing_simulations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `idx_financing_user_id` (`user_id`);

ALTER TABLE `recently_viewed`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_car` (`user_id`,`car_id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `idx_recently_viewed_user_id` (`user_id`),
  ADD KEY `idx_recently_viewed_time` (`viewed_at`);


ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_ativo` (`ativo`);


ALTER TABLE `carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

ALTER TABLE `car_comparisons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `financing_simulations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `recently_viewed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`carro_id`) REFERENCES `carros` (`id`) ON DELETE CASCADE;

ALTER TABLE `financing_simulations`
  ADD CONSTRAINT `financing_simulations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `financing_simulations_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `carros` (`id`) ON DELETE SET NULL;

ALTER TABLE `recently_viewed`
  ADD CONSTRAINT `recently_viewed_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recently_viewed_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `carros` (`id`) ON DELETE CASCADE;
COMMIT;

