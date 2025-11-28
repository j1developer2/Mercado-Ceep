-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/11/2025 às 18:23
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

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
(36, 'Notebook Thinkpad T480', 'Notebook muito bom, pouco usado, 8gb de memória ram, 256gb de ssd, bateria funcionando na faixa de 4 horas, muito top', '1499', '2025-11-28', '3D', '2', 0, 1, 2, 49, '6310470328_20230928_141052973_iOS-scaled.png', '41997698922', '2', '1'),
(37, 'Bicicleta vermelha aro 26', 'Bicicleta funcionando, vendendo porque troquei por uma melhor', '250', '2025-11-28', '3D', '3', 0, 1, 6, 49, '5088396084_589059565_804705819235847_4491084757463591751_n.jpg', '41997698922', '2', '1'),
(38, ' Notebook STI Semp Toshiba NA 1401', 'Notebook Semp Toshiba NA 1401 que acompanha o carregador original, processador AMD Dual Core C-60 1 GHz, 2 GB de RAM e HD de 320 GB, tela está intacta, é um computador antigo funciona lentamente, só funciona com o cabo e pega wi-fi para Google e YouTube, algumas teclas falham mas funcionam.', '230', '2025-11-28', '3D', '3', 0, 1, 2, 49, '2305116139_558741463_1344429790677343_7645120661062567450_n.jpg', '41992562056', '2', '2'),
(39, 'Fone Bluetooth Gm2 pro', 'Prepare-se para elevar sua experiência com o fone Bluetooth gamer mais desejado do momento.  O GM2 Pro combina design moderno, grave poderoso e latência ultrabaixa, garantindo que cada passo, tiro e batida chegue até você com precisão total.', '45', '2025-11-28', '3D', '1', 0, 1, 2, 49, '6156476917_588017148_25352411504445595_310767312915115522_n.jpg', '41997698922', '1', '1'),
(40, 'Doze molas a venda refletivel', 'Usado apenas uma vez tamanho 38', '80', '2025-11-28', '2D', '3', 0, 1, 5, 49, '3374847200_585898683_2608262336210438_4581258878407856528_n.jpg', '41992562056', '2', '2'),
(41, 'Bota Catphilos EPI', 'Bota Catphilos  NOVA  NÃO FOI USADA EPI pra serviço e para motoboy Anti derrapaste bota impermeável a água ,proteção de pedaleira Número 41 Valor : 120$  pra ir logo Motivo da venda ficou apertada em mim  Aceito troca em outro bota número 42 no mesmo nível', '120', '2025-11-28', '3C', '1', 0, 1, 5, 49, '7493846371_584799361_4256910834554607_8779699477694499866_n.jpg', '41992562056', '2', '3'),
(42, 'Mangás Demon Slayer', 'Vendo 13 mangá do Demon Slayer  1,2,3,4,5,6,9,10,11,12,13,14,20 300 reais troco tbm. Não vendo separado', '300', '2025-11-28', '1A', '3', 0, 1, 1, 49, '747954956_579547422_1522262685492585_2867082557628648261_n.jpg', '41997698922', '2', '2'),
(43, 'Mangá Fairy Tail – Volume 1 | Seminovo (Estado de Novo)', 'Este exemplar está seminovo e em estado de novo, com páginas limpas, capa intacta e sem sinais de uso, apenas fora do plástico original, por isso o preço está muito abaixo do mercado — uma oportunidade imperdível para colecionadores e leitores! ????', '20', '2025-11-28', '2C', '2', 0, 1, 1, 49, '5332995533_589788865_1388000626358762_5072458013489629481_n.jpg', '41997698922', '1', '2'),
(44, 'Brigadeiro Caseiro', 'Vende-se deliciosos brigadeiros de panela, ingredientes de excelentes qualidade.', '10', '2025-11-28', '3E', '1', 0, 1, 4, 49, '383979887_481430140_647044977795033_5359888438986742911_n.jpg', '41992562056', '1', '3'),
(45, 'Trufas', 'Caixinha de trufas, perfeito para presentear. Sabores: Brigadeiro branco, Brigadeiro de morango, Beijinho Paçoca', '14', '2025-11-28', '3B', '1', 0, 1, 4, 49, '2207256185_564219729_1903236697294163_8853117514891765644_n.jpg', '41997698922', '1', '1'),
(46, 'Hollow Knight de crochê', 'Personagens de hollow knight feito de crochê, tanto de silksong quanto do jogo original.', '25', '2025-11-28', '2B', '1', 0, 1, 3, 49, '137140830_d6f823a1192e46e16d5838090cf118bd.jpg', '41992562056', '1', '2');

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
(7, 49, 52, 'eae', '2025-09-02 09:20:18'),
(8, 52, 49, 'oi cara blz?', '2025-09-02 09:20:37'),
(9, 49, 52, 'eae', '2025-09-02 09:21:17'),
(10, 52, 49, 'eae', '2025-11-24 11:59:21'),
(11, 49, 49, 'eae', '2025-11-28 14:08:38'),
(12, 49, 49, 'bão', '2025-11-28 14:08:47');

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
(47, 'simsoueu', '222abfd959264bdf1cc7c49e569d2e207f13bcb9', 'kaueciesielski1@gmail.com', 'Kauê Ciesielski Stinglin ', 0, 0, 1, '2024-02-19', '8384084927_vito.jpeg', '1A', 'Manha'),
(48, 'samurai_sulista', '40bf696d25dd56ed44c864e05f75d33a4cface91', 'rodoviacentoedez@gmail.com', 'Marcelo Pereira', 0, 0, 0, '2025-08-05', '7725566749_download.jfif', '3D', 'Manha'),
(49, 'tonhao', 'e96788c619244d8785cb61a35097e9be6733f9f0', 'tonhao@gmail.com', 'tonhao pika', 0, 0, 0, '2025-08-05', '9068671_2609e8eafad4c4ab141b6233bac7cf3f.jpg', '1G', 'Noite'),
(50, 'joaoazinhodomorro', '4410d99cefe57ec2c2cdbd3f1d5cf862bb4fb6f8', 'tonhao@gmail.com', 'tonhao pika', 0, 0, 0, '2025-08-06', '3149951370_The_Owl_House_-_Luz.webp', '2A', 'Noite'),
(51, 'vinicius', '2b03d2afec0950eaa279059442d9df93611c2566', 'tonhao@gmail.com', 'tonhao pika', 0, 0, 0, '2025-08-06', '5856783744_download.jfif', '1F', 'Noite'),
(52, 'eaee', 'e75df26b556bb441f3571791cc6b17f34162f20f', 'eae@gmail.com', 'eae', 0, 0, 0, '2025-09-02', '8515603487_eae2.png', '1F', 'Tarde'),
(54, 'joaopedro_1a', '7288edd0fc3ffcbe93a0cf06e3568e28521687bc', 'joao.alves1a@example.com', 'João Pedro Alves', 0, 0, 0, '2025-10-10', '701828549_images.png', '1A', 'Manha');

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
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de tabela `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User', AUTO_INCREMENT=55;

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
