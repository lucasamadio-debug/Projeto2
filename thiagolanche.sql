-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/06/2026 às 03:41
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
-- Banco de dados: `thiagolanche`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nome_categoria`) VALUES
(1, 'PRENSADOS'),
(2, 'HOT DOGS'),
(3, 'LANCHES GOURMET');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ingredientes`
--

CREATE TABLE `ingredientes` (
  `id_ingredientes` int(11) NOT NULL,
  `nome_ingrediente` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ingredientes`
--

INSERT INTO `ingredientes` (`id_ingredientes`, `nome_ingrediente`) VALUES
(1, 'Pão'),
(2, 'Queijo'),
(3, 'Presunto'),
(4, 'Tomate'),
(5, 'Alface'),
(6, 'Hamburguer'),
(7, 'Bacon'),
(8, 'Frango'),
(9, 'Catupiry'),
(10, 'Cheddar'),
(11, 'Salsicha'),
(12, 'Milho'),
(13, 'Batata palha'),
(14, 'Calabresa'),
(15, 'Egg'),
(16, 'Pão brioche'),
(17, 'Molho da casa'),
(18, 'Cebola roxa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nome_lanches` varchar(100) NOT NULL,
  `preco` float(10,2) NOT NULL,
  `popular` tinyint(1) DEFAULT 0,
  `ingredientes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `id_categoria`, `nome_lanches`, `preco`, `popular`, `ingredientes`) VALUES
(1, 1, 'x-salada', 22.00, 0, 'Pão, queijo, presunto, tomate, hamburguer e alface'),
(2, 1, 'x-bacon', 30.00, 0, 'Pão, queijo, presunto, tomate, hamburguer, bacon e alface'),
(3, 1, 'x-tudo', 55.00, 0, 'Frango, bacon, milho, catupiry, cheddar, tomate, alface, salamesa e bacon'),
(4, 1, 'x-calabresa', 30.00, 0, 'Pão, queijo, presunto, tomate, hamburguer, calabresa e alface'),
(5, 1, 'x-frango', 29.00, 0, 'Pão, salsicha, frango, tomate, hamburguer e alface'),
(6, 1, 'x-frango-catupiry', 33.00, 0, 'Pão, salsicha, frango, tomate, hamburguer, alface e catupiry'),
(7, 1, 'x-frango-cheddar', 33.00, 0, 'Pão, salsicha, frango, tomate, hamburguer, alface e cheddar'),
(8, 1, 'x-itapema', 23.00, 0, 'Pão, queijo, presunto, tomate, hamburguer, batata palha e alface'),
(9, 2, 'dog Carne', 22.00, 0, 'Pão, salsicha, carne e batata palha'),
(10, 2, 'dog Bacon', 27.00, 0, 'Pão, 2 salsichas, milho, queijo, tomate, bacon e batata palha'),
(11, 2, 'dog-misto', 24.00, 0, 'Pão, salsichas, carne, frango e batata palha'),
(12, 2, 'dog-calabresa', 27.00, 0, 'Pão, salsicha, milho, queijo, tomate, calabresa e batata palha'),
(13, 2, 'dog-frango', 22.00, 0, 'Pão, 2 salsichas, frango e batata palha'),
(14, 2, 'dog-egg-calabresa', 30.00, 0, 'Pão, 2 salsichas, milho, queijo, tomate, calabresa, 2 eggs e batata palha'),
(15, 3, 'gourme-cheddar', 30.00, 0, 'Pão brioche, hamburguer, queijo, tomate, alface, cheddar, molho da casa e cebola roxa'),
(16, 3, 'gourmet-triplo-bacon', 28.00, 0, 'Pão brioche, hamburguer, queijo, tomate, alface, bacon, molho da casa e cebola roxa'),
(17, 3, 'gourmet-da-casa', 22.00, 0, 'Pão brioche, hamburguer, queijo, tomate, alface, molho da casa e cebola roxa'),
(18, 3, 'smash Burguer', 16.00, 0, 'Pão brioche, hamburguer e queijo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto_ingredientes`
--

CREATE TABLE `produto_ingredientes` (
  `id_produto` int(11) NOT NULL,
  `id_ingredientes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto_ingredientes`
--

INSERT INTO `produto_ingredientes` (`id_produto`, `id_ingredientes`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 6),
(2, 7),
(3, 4),
(3, 5),
(3, 7),
(3, 8),
(3, 9),
(3, 12),
(3, 14),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 6),
(4, 14),
(5, 1),
(5, 4),
(5, 5),
(5, 6),
(5, 8),
(5, 11),
(6, 1),
(6, 4),
(6, 6),
(6, 8),
(6, 9),
(6, 11),
(7, 1),
(7, 4),
(7, 6),
(7, 8),
(7, 10),
(7, 11),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 6),
(8, 13),
(9, 1),
(9, 6),
(9, 11),
(9, 13),
(10, 1),
(10, 7),
(10, 11),
(10, 13),
(11, 1),
(11, 6),
(11, 8),
(11, 11),
(11, 13),
(12, 1),
(12, 11),
(12, 12),
(12, 13),
(12, 14),
(13, 1),
(13, 8),
(13, 11),
(13, 13),
(14, 1),
(14, 11),
(14, 12),
(14, 13),
(14, 14),
(14, 15),
(15, 2),
(15, 4),
(15, 5),
(15, 6),
(15, 10),
(15, 16),
(15, 17),
(15, 18),
(16, 2),
(16, 4),
(16, 5),
(16, 6),
(16, 7),
(16, 16),
(16, 17),
(16, 18),
(17, 2),
(17, 4),
(17, 5),
(17, 6),
(17, 16),
(17, 17),
(17, 18),
(18, 2),
(18, 6),
(18, 16);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`id_ingredientes`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Índices de tabela `produto_ingredientes`
--
ALTER TABLE `produto_ingredientes`
  ADD PRIMARY KEY (`id_produto`,`id_ingredientes`),
  ADD KEY `id_ingredientes` (`id_ingredientes`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `id_ingredientes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Restrições para tabelas `produto_ingredientes`
--
ALTER TABLE `produto_ingredientes`
  ADD CONSTRAINT `produto_ingredientes_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  ADD CONSTRAINT `produto_ingredientes_ibfk_2` FOREIGN KEY (`id_ingredientes`) REFERENCES `ingredientes` (`id_ingredientes`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
