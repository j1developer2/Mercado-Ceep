-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02/09/2025 às 16:07
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
-- Banco de dados: `shop`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(1, 'Livros', 'Livros de todos os tipos, desde didáticos até recreativos ou pdf\'s', 0, 1, 0, 0, 0),
(2, 'Eletrônicos', 'Todos os tipos de eletrônicos, desde foninhos até mouses', 0, 2, 0, 0, 0),
(3, 'Artesanato', 'Todo tipo de artesanato ou coisas feitas sob medida', 0, 3, 0, 0, 0),
(4, 'Comida', 'Para todo tipo de comida', 0, 4, 0, 0, 0),
(5, 'Roupas', 'Para todo tipo de roupa', 0, 5, 0, 0, 0),
(6, 'Outros', 'Para coisas que não se encaixam em nenhuma outra categoria', 0, 6, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(22, 'Gostei, recomendo!', 1, '2024-02-15', 28, 45),
(28, 'Caramba João Caetano ???????????? Não sabia que você era assim!!!', 1, '2024-02-19', 32, 47),
(30, 'Podia ser melhor, produto veio com defeitos e tem um pessimo gosto', 1, '2025-08-05', 31, 48),
(31, 'foda demaise', 1, '2025-08-05', 31, 49);

-- --------------------------------------------------------

--
-- Estrutura para tabela `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `Sob_Encomenda` varchar(255) NOT NULL,
  `Turno` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `picture`, `contact`, `Sob_Encomenda`, `Turno`) VALUES
(28, 'Paçoca', 'Paçoca doce e de qualidade, vendido por unidade', '0,75', '2024-02-14', '1G', '1', 0, 1, 4, 1, '3411587230_pacoca.jpeg', '41997698922', '1', '2'),
(31, 'Ryan', 'Lava louça ', '15000', '2024-02-15', '2G', '4', 0, 1, 4, 46, '4242883624_17080211234631790845481446491607.jpg', '41991054839', '2', '2'),
(32, 'Apostolado de João Caetano', 'João Caetano roubou a namorada do seu irmão??? Veja agora!!!', '1069', '2024-02-19', '1A', '1', 0, 1, 2, 47, '1747594773_images (11) (5).jpeg', '0412289', '1', '1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `timestamp`) VALUES
(1, 47, 52, 'ola', '2025-09-02 08:28:15'),
(2, 52, 49, 'eae', '2025-09-02 08:40:48'),
(3, 49, 52, 'blz?', '2025-09-02 08:40:53'),
(4, 52, 49, 'kkeaeman', '2025-09-02 08:41:14'),
(5, 49, 52, 'legal, agora manda foto da pika', '2025-09-02 08:41:43'),
(6, 49, 52, 'alert(1)', '2025-09-02 09:00:51'),
(7, 49, 52, 'eae', '2025-09-02 09:20:18'),
(8, 52, 49, 'oi cara blz?', '2025-09-02 09:20:37'),
(9, 49, 52, 'eae', '2025-09-02 09:21:17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To Identify User',
  `Username` varchar(255) NOT NULL COMMENT 'Username To Login',
  `Password` varchar(255) NOT NULL COMMENT 'Password To Login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT 0 COMMENT 'Identify User Group',
  `TrustStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'Seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'User Approval',
  `Date` date NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `Turma` varchar(2) NOT NULL,
  `Turno` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `avatar`, `Turma`, `Turno`) VALUES
(1, 'Admin', 'e96788c619244d8785cb61a35097e9be6733f9f0', 'Admin@gmail.com', 'Admin Admin', 1, 1, 1, '2020-08-27', '../default.png', '', ''),
(45, 'Jeanzitos', '7fa232b334c265e0b3f62594139d0164ec8b37e1', 'pao.org321@gmail.com', 'Jean Joacir de Souza Pinto', 0, 0, 1, '2024-02-15', '9476787358_flopa.jpg', '2G', 'Tarde'),
(46, 'Renato Cardoso Ayres ', '25458f1e530feb5248b97c958f75dc0ea983c9af', 'renato.cardos.ayres@gmail.com', 'Renato Cardoso Ayres ', 0, 0, 1, '2024-02-15', '4217065735_1708020998574170031266453017642.jpg', '2G', 'Tarde'),
(47, 'simsoueu', 'e67b9f0eabfb226f9e2792202ddd4de3c98f1feb', 'kaueciesielski1@gmail.com', 'Kauê Ciesielski Stinglin ', 0, 0, 1, '2024-02-19', '8384084927_vito.jpeg', '1A', 'Manha'),
(48, 'samurai_sulista', '40bf696d25dd56ed44c864e05f75d33a4cface91', 'rodoviacentoedez@gmail.com', 'Marcelo Pereira', 0, 0, 0, '2025-08-05', '7725566749_download.jfif', '3D', 'Manha'),
(49, 'tonhao', 'e96788c619244d8785cb61a35097e9be6733f9f0', 'tonhao@gmail.com', 'tonhao pika', 0, 0, 0, '2025-08-05', '9068671_2609e8eafad4c4ab141b6233bac7cf3f.jpg', '1G', 'Noite'),
(50, 'joaoazinhodomorro', '4410d99cefe57ec2c2cdbd3f1d5cf862bb4fb6f8', 'tonhao@gmail.com', 'tonhao pika', 0, 0, 0, '2025-08-06', '3149951370_The_Owl_House_-_Luz.webp', '2A', 'Noite'),
(51, 'vinicius', '2b03d2afec0950eaa279059442d9df93611c2566', 'tonhao@gmail.com', 'tonhao pika', 0, 0, 0, '2025-08-06', '5856783744_download.jfif', '1F', 'Noite'),
(52, 'eaee', 'e75df26b556bb441f3571791cc6b17f34162f20f', 'eae@gmail.com', 'eae', 0, 0, 0, '2025-09-02', '8515603487_eae2.png', '1F', 'Tarde');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Índices de tabela `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `items_comment` (`item_id`),
  ADD KEY `comment_user` (`user_id`);

--
-- Índices de tabela `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Índices de tabela `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User', AUTO_INCREMENT=53;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
