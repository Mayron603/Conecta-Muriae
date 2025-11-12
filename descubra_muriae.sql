-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 11/11/2025 às 13:10
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `descubra_muriae`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargo`
--

DROP TABLE IF EXISTS `cargo`;
CREATE TABLE IF NOT EXISTS `cargo` (
  `cargo_id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`cargo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `cargo`
--

INSERT INTO `cargo` (`cargo_id`, `descricao`) VALUES
(1, 'Desenvolvedor(a) Back-End'),
(2, 'Desenvolvedor(a) Front-End'),
(3, 'Desenvolvedor(a) Full-Stack'),
(4, 'Analista de Sistemas'),
(5, 'Analista de Qualidade (QA)'),
(6, 'Gerente de Projetos'),
(7, 'Designer UX/UI'),
(8, 'Analista de Marketing Digital'),
(9, 'Vendedor(a)'),
(10, 'Assistente Administrativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria_estabelecimento`
--

DROP TABLE IF EXISTS `categoria_estabelecimento`;
CREATE TABLE IF NOT EXISTS `categoria_estabelecimento` (
  `categoria_estabelecimento_id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `categoria_id` int NOT NULL,
  PRIMARY KEY (`categoria_estabelecimento_id`),
  KEY `idx_categoria_estabelecimento` (`estabelecimento_id`,`categoria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1249 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cidade`
--

DROP TABLE IF EXISTS `cidade`;
CREATE TABLE IF NOT EXISTS `cidade` (
  `cidade_id` int NOT NULL AUTO_INCREMENT,
  `cidade` varchar(200) NOT NULL,
  `uf` char(2) NOT NULL,
  PRIMARY KEY (`cidade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5573 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `clique_celular`
--

DROP TABLE IF EXISTS `clique_celular`;
CREATE TABLE IF NOT EXISTS `clique_celular` (
  `clique_celular_id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `visitante_id` int NOT NULL,
  `celular` char(11) NOT NULL,
  `telefone_id` int DEFAULT NULL,
  `data_clique` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`clique_celular_id`),
  KEY `fk_estabelecimento_clique_celular_idx` (`estabelecimento_id`),
  KEY `fk_telefone_clique_celular_idx` (`telefone_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `clique_telefone`
--

DROP TABLE IF EXISTS `clique_telefone`;
CREATE TABLE IF NOT EXISTS `clique_telefone` (
  `clique_telefone_id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `visitante_id` int NOT NULL,
  `telefone` char(11) NOT NULL,
  `telefone_id` int DEFAULT NULL,
  `data_clique` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`clique_telefone_id`),
  KEY `fk_estabelecimento_clique_telefone_idx` (`estabelecimento_id`),
  KEY `fk_telefone_clique_telefone_idx` (`telefone_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `conversa`
--

DROP TABLE IF EXISTS `conversa`;
CREATE TABLE IF NOT EXISTS `conversa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `id_candidato` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculum`
--

DROP TABLE IF EXISTS `curriculum`;
CREATE TABLE IF NOT EXISTS `curriculum` (
  `curriculum_id` int NOT NULL AUTO_INCREMENT,
  `pessoa_fisica_id` int NOT NULL,
  `logradouro` varchar(60) NOT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(20) DEFAULT NULL,
  `bairro` varchar(50) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `cidade_id` int NOT NULL,
  `celular` varchar(11) NOT NULL,
  `dataNascimento` date NOT NULL,
  `sexo` char(1) NOT NULL COMMENT 'M=Masculino;F=Feminino',
  `foto` varchar(100) DEFAULT NULL,
  `email` varchar(120) NOT NULL,
  `apresentacaoPessoal` text,
  `arquivo_curriculo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`curriculum_id`),
  KEY `fk_curriculum_cidade1_idx` (`cidade_id`),
  KEY `fk_curriculum_pessoa_fisica1_idx` (`pessoa_fisica_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculum_escolaridade`
--

DROP TABLE IF EXISTS `curriculum_escolaridade`;
CREATE TABLE IF NOT EXISTS `curriculum_escolaridade` (
  `curriculum_escolaridade_id` int NOT NULL AUTO_INCREMENT,
  `curriculum_curriculum_id` int NOT NULL,
  `inicioMes` int NOT NULL,
  `inicioAno` int NOT NULL,
  `fimMes` int NOT NULL,
  `fimAno` int NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `instituicao` varchar(60) NOT NULL,
  `cidade_id` int NOT NULL,
  `escolaridade_id` int NOT NULL,
  PRIMARY KEY (`curriculum_escolaridade_id`),
  KEY `fk_curriculum_escolaridade_cidade1_idx` (`cidade_id`),
  KEY `fk_curriculum_escolaridade_curriculum1_idx` (`curriculum_curriculum_id`),
  KEY `fk_curriculum_escolaridade_escolaridade1_idx` (`escolaridade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculum_experiencia`
--

DROP TABLE IF EXISTS `curriculum_experiencia`;
CREATE TABLE IF NOT EXISTS `curriculum_experiencia` (
  `curriculum_experiencia_id` int NOT NULL AUTO_INCREMENT,
  `curriculum_id` int NOT NULL,
  `inicioMes` int NOT NULL,
  `inicioAno` int NOT NULL,
  `fimMes` int DEFAULT NULL,
  `fimAno` int DEFAULT NULL,
  `estabelecimento` varchar(60) DEFAULT NULL,
  `cargo_id` int DEFAULT NULL,
  `cargoDescricao` varchar(50) DEFAULT NULL,
  `atividadesExercidas` text,
  PRIMARY KEY (`curriculum_experiencia_id`),
  KEY `fk_curriculum_experiencia_curriculum1_idx` (`curriculum_id`),
  KEY `fk_curriculum_experiencia_cargo1_idx` (`cargo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculum_qualificacao`
--

DROP TABLE IF EXISTS `curriculum_qualificacao`;
CREATE TABLE IF NOT EXISTS `curriculum_qualificacao` (
  `curriculum_qualificacao_id` int NOT NULL AUTO_INCREMENT,
  `curriculum_id` int NOT NULL,
  `mes` int NOT NULL,
  `ano` int NOT NULL,
  `cargaHoraria` int NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `estabelecimento` varchar(60) NOT NULL,
  PRIMARY KEY (`curriculum_qualificacao_id`),
  KEY `fk_curriculum_qualificacao_curriculum1_idx` (`curriculum_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolaridade`
--

DROP TABLE IF EXISTS `escolaridade`;
CREATE TABLE IF NOT EXISTS `escolaridade` (
  `escolaridade_id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`escolaridade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `escolaridade`
--

INSERT INTO `escolaridade` (`escolaridade_id`, `descricao`) VALUES
(1, 'Ensino Fundamental Completo'),
(2, 'Ensino Médio Completo'),
(3, 'Técnico'),
(4, 'Graduação'),
(5, 'Pós-graduação / Especialização'),
(6, 'Mestrado'),
(7, 'Doutorado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estabelecimento`
--

DROP TABLE IF EXISTS `estabelecimento`;
CREATE TABLE IF NOT EXISTS `estabelecimento` (
  `estabelecimento_id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `latitude` char(12) NOT NULL,
  `longitude` char(12) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `sobre` text,
  `website` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`estabelecimento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1222 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagem`
--

DROP TABLE IF EXISTS `mensagem`;
CREATE TABLE IF NOT EXISTS `mensagem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_conversa` int NOT NULL,
  `id_remetente` int NOT NULL,
  `id_destinatario` int NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

DROP TABLE IF EXISTS `notificacoes`;
CREATE TABLE IF NOT EXISTS `notificacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pessoa_fisica_id` int NOT NULL COMMENT 'ID do usuário que recebe a notificação',
  `titulo` varchar(255) NOT NULL COMMENT 'O título da notificação',
  `mensagem` text NOT NULL COMMENT 'A mensagem completa da notificação',
  `link` varchar(255) DEFAULT NULL COMMENT 'Link opcional para onde o usuário será redirecionado',
  `lida` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 para não lida, 1 para lida',
  `dataCriacao` datetime NOT NULL COMMENT 'Data e hora de criação da notificação',
  `dataUpdate` datetime NOT NULL COMMENT 'Data e hora da última atualização',
  PRIMARY KEY (`id`),
  KEY `idx_pessoa_fisica_id` (`pessoa_fisica_id`),
  KEY `idx_lida` (`lida`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoa_fisica`
--

DROP TABLE IF EXISTS `pessoa_fisica`;
CREATE TABLE IF NOT EXISTS `pessoa_fisica` (
  `pessoa_fisica_id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `cpf` char(11) DEFAULT NULL,
  `visitante_id` int DEFAULT NULL,
  PRIMARY KEY (`pessoa_fisica_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1341 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `telefone`
--

DROP TABLE IF EXISTS `telefone`;
CREATE TABLE IF NOT EXISTS `telefone` (
  `telefone_id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `numero` char(11) NOT NULL,
  `tipo` enum('m','f') NOT NULL,
  PRIMARY KEY (`telefone_id`),
  KEY `fk_estabelecimento_telefone` (`estabelecimento_id`),
  KEY `fk_usuario_telefone_idx` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2447 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `termodeuso`
--

DROP TABLE IF EXISTS `termodeuso`;
CREATE TABLE IF NOT EXISTS `termodeuso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `textoTermo` longtext NOT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1=Ativo;2=Inativo;3=Alterado',
  `rascunho` int DEFAULT '1' COMMENT '1=Sim; 2=Não',
  `usuario_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_termodeuso_usuario1` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `termodeusoaceite`
--

DROP TABLE IF EXISTS `termodeusoaceite`;
CREATE TABLE IF NOT EXISTS `termodeusoaceite` (
  `termodeuso_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `dataHoraAceite` datetime NOT NULL,
  PRIMARY KEY (`termodeuso_id`,`usuario_id`),
  KEY `fk_termodeuso_has_usuario_usuario1_idx` (`usuario_id`),
  KEY `fk_termodeuso_has_usuario_termodeuso1_idx` (`termodeuso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `usuario_id` int NOT NULL AUTO_INCREMENT,
  `pessoa_fisica_id` int DEFAULT NULL,
  `estabelecimento_id` int DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` char(2) NOT NULL COMMENT 'A = Anunciante, G = Gestor, CN = Contribuinte normativo',
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`usuario_id`),
  KEY `fk_pessoa_fisica_usuario1_idx` (`pessoa_fisica_id`),
  KEY `fk_usuario_estabelecimento1_idx` (`estabelecimento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1309 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vaga`
--

DROP TABLE IF EXISTS `vaga`;
CREATE TABLE IF NOT EXISTS `vaga` (
  `vaga_id` int NOT NULL AUTO_INCREMENT,
  `cargo_id` int NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `sobreaVaga` text NOT NULL,
  `modalidade` int NOT NULL COMMENT '1=Presencial; 2=Remoto;',
  `vinculo` int NOT NULL COMMENT '1=CLT; 2=Pessoa Jurídica;',
  `dtInicio` date NOT NULL,
  `dtFim` date NOT NULL,
  `estabelecimento_id` int NOT NULL,
  `statusVaga` int NOT NULL COMMENT '1=Pré Vaga; 11=Em aberto;91=Suspensa; 99=Finalizada;',
  `salario` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`vaga_id`),
  KEY `fk_vaga_cargo1_idx` (`cargo_id`),
  KEY `fk_vaga_estabelecimento1_idx` (`estabelecimento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vaga_curriculum`
--

DROP TABLE IF EXISTS `vaga_curriculum`;
CREATE TABLE IF NOT EXISTS `vaga_curriculum` (
  `vaga_id` int NOT NULL,
  `curriculum_id` int NOT NULL,
  `dateCandidatura` datetime NOT NULL,
  PRIMARY KEY (`vaga_id`,`curriculum_id`),
  KEY `fk_vaga_has_curriculum_curriculum1_idx` (`curriculum_id`),
  KEY `fk_vaga_has_curriculum_vaga1_idx` (`vaga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `estabelecimento`
--
ALTER TABLE `estabelecimento` ADD FULLTEXT KEY `ft_busca` (`nome`);

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `categoria_estabelecimento`
--
ALTER TABLE `categoria_estabelecimento`
  ADD CONSTRAINT `fk_estabelecimento_categoria` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`estabelecimento_id`);

--
-- Restrições para tabelas `clique_celular`
--
ALTER TABLE `clique_celular`
  ADD CONSTRAINT `fk_estabelecimento_clique_celular` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`estabelecimento_id`),
  ADD CONSTRAINT `fk_telefone_clique_celular` FOREIGN KEY (`telefone_id`) REFERENCES `telefone` (`telefone_id`);

--
-- Restrições para tabelas `clique_telefone`
--
ALTER TABLE `clique_telefone`
  ADD CONSTRAINT `fk_estabelecimento_clique_telefone` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`estabelecimento_id`),
  ADD CONSTRAINT `fk_telefone_clique_telefone` FOREIGN KEY (`telefone_id`) REFERENCES `telefone` (`telefone_id`);

--
-- Restrições para tabelas `curriculum`
--
ALTER TABLE `curriculum`
  ADD CONSTRAINT `fk_curriculum_cidade1` FOREIGN KEY (`cidade_id`) REFERENCES `cidade` (`cidade_id`),
  ADD CONSTRAINT `fk_curriculum_pessoa_fisica1` FOREIGN KEY (`pessoa_fisica_id`) REFERENCES `pessoa_fisica` (`pessoa_fisica_id`);

--
-- Restrições para tabelas `curriculum_escolaridade`
--
ALTER TABLE `curriculum_escolaridade`
  ADD CONSTRAINT `fk_curriculum_escolaridade_cidade1` FOREIGN KEY (`cidade_id`) REFERENCES `cidade` (`cidade_id`),
  ADD CONSTRAINT `fk_curriculum_escolaridade_curriculum1` FOREIGN KEY (`curriculum_curriculum_id`) REFERENCES `curriculum` (`curriculum_id`),
  ADD CONSTRAINT `fk_curriculum_escolaridade_escolaridade1` FOREIGN KEY (`escolaridade_id`) REFERENCES `escolaridade` (`escolaridade_id`);

--
-- Restrições para tabelas `curriculum_experiencia`
--
ALTER TABLE `curriculum_experiencia`
  ADD CONSTRAINT `fk_curriculum_experiencia_cargo1` FOREIGN KEY (`cargo_id`) REFERENCES `cargo` (`cargo_id`),
  ADD CONSTRAINT `fk_curriculum_experiencia_curriculum1` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculum` (`curriculum_id`);

--
-- Restrições para tabelas `curriculum_qualificacao`
--
ALTER TABLE `curriculum_qualificacao`
  ADD CONSTRAINT `fk_curriculum_qualificacao_curriculum1` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculum` (`curriculum_id`);

--
-- Restrições para tabelas `telefone`
--
ALTER TABLE `telefone`
  ADD CONSTRAINT `fk_estabelecimento_telefone` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`estabelecimento_id`),
  ADD CONSTRAINT `fk_usuario_telefone` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Restrições para tabelas `termodeuso`
--
ALTER TABLE `termodeuso`
  ADD CONSTRAINT `fk_termodeuso_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Restrições para tabelas `termodeusoaceite`
--
ALTER TABLE `termodeusoaceite`
  ADD CONSTRAINT `fk_termodeuso_has_usuario_termodeuso1` FOREIGN KEY (`termodeuso_id`) REFERENCES `termodeuso` (`id`),
  ADD CONSTRAINT `fk_termodeuso_has_usuario_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_pessoa_fisica_usuario1` FOREIGN KEY (`pessoa_fisica_id`) REFERENCES `pessoa_fisica` (`pessoa_fisica_id`),
  ADD CONSTRAINT `fk_usuario_estabelecimento1` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`estabelecimento_id`);

--
-- Restrições para tabelas `vaga`
--
ALTER TABLE `vaga`
  ADD CONSTRAINT `fk_vaga_cargo1` FOREIGN KEY (`cargo_id`) REFERENCES `cargo` (`cargo_id`),
  ADD CONSTRAINT `fk_vaga_estabelecimento1` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`estabelecimento_id`);

--
-- Restrições para tabelas `vaga_curriculum`
--
ALTER TABLE `vaga_curriculum`
  ADD CONSTRAINT `fk_vaga_has_curriculum_curriculum1` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculum` (`curriculum_id`),
  ADD CONSTRAINT `fk_vaga_has_curriculum_vaga1` FOREIGN KEY (`vaga_id`) REFERENCES `vaga` (`vaga_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
