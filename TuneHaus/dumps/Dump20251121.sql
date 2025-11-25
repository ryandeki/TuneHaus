-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: tunehausdb
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS tunehausdb;
CREATE DATABASE tunehausdb;
USE tunehausdb;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Guitarras'),(2,'Violões'),(3,'Baixos'),(4,'Teclados'),(5,'Flautas');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_banner`
--

DROP TABLE IF EXISTS `home_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `home_banner` (
  `id` tinyint NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_banner`
--

LOCK TABLES `home_banner` WRITE;
/*!40000 ALTER TABLE `home_banner` DISABLE KEYS */;
INSERT INTO `home_banner` VALUES (1,'69236933347ad-fundoinicial.jpg');
/*!40000 ALTER TABLE `home_banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_slots`
--

DROP TABLE IF EXISTS `home_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `home_slots` (
  `id` tinyint NOT NULL,
  `produto_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_home_slots_produto` (`produto_id`),
  CONSTRAINT `fk_home_slots_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_slots`
--

LOCK TABLES `home_slots` WRITE;
/*!40000 ALTER TABLE `home_slots` DISABLE KEYS */;
INSERT INTO `home_slots` VALUES (1,41),(2,43);
/*!40000 ALTER TABLE `home_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `informacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `preco` decimal(10,2) NOT NULL,
  `categoria_id` int NOT NULL,
  `musica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagem` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES (41,'Guitarra elétrica Evh Wolfgang - Dark Purple Met','Roxa; 6 Cordas; Destro','Inclui 2 microfones humbucker.\r\nAlavanca incluída.',10000.00,1,'https://www.youtube.com/watch?v=PIgiRy4F1fc&pp=ygUYZ3VpdGFycmEgY2lmcmEgY2x1YiByb2Nr','img_692115b17b4350.88017936.webp'),(43,'Teclado Pegasus P222','Arranjador 62 Ritmos Brasileiros','Teclado (Keyboard): 61 teclas sensíveis.\r\nVisor (Display): Visor LCD.\r\n\r\nTimbres (Tone): 427 tipos de timbres, incluindo alguns sons gravados no Brasil.\r\n\r\nRitmos (Rhythm): 250 tipos (210 + 62 ritmos brasileiros programados no Brasil).\r\n\r\nMúsicas de Demonstração (Demo Songs): 120 músicas de demonstração.',1800.00,4,'https://www.youtube.com/watch?v=3SX_Tq-RLNQ&pp=ygUYc29tZW9uZSBsaWtlIHlvdSB0ZWNsYWRv0gcJCQsKAYcqIYzv','img_69211713e46939.18108062.webp'),(45,'Guitarra GIBSON Les Paul Studio 50.s','Preta; 6 Cordas; Destro','Captadores: Humbucker 490R na posição do braço e Humbucker 498T na posição da ponte\r\n\r\nControles: 2 Volumes e 2 Tones, Chave Seletora de 3 posições\r\n\r\nCor: Preta\r\n\r\nCorpo: Mogno\r\n\r\nDimensões da embalagem: 0.000 x 0.000 x 0.000\r\n\r\nEscala: Baked Maple\r\n\r\nEscudo: Creme\r\n\r\nFerragens: Cromadas',30000.00,1,'https://www.youtube.com/watch?v=b2dYQAejgqQ&pp=ygUYZ3VpdGFycmEgY2lmcmEgY2x1YiByb2Nr','img_692380f83bbc06.76704978.webp'),(46,'Violão Yamaha C45ii Clássico Acústico Natural','Nylon; 6 Cordas; Destro','Formato CG da Yamaha\r\nTampo em abeto\r\nQualidade Yamaha por um preço acessível',800.00,2,'https://www.youtube.com/watch?v=MifSvS6DKyI&pp=ygUWdmlvbGFvIGNpZnJhIGNsdWIgIG1wYg%3D%3D','img_692381ca1f7538.18424938.jpg'),(47,'Contra Baixo Tagima Millenium','Preto Metálico;  4 Cordas; Destro','Acabamento Envernizado\r\n1 controle de volume, 1 de balanço, 1 de agudo, 1 de médio, 1 de grave',1800.00,3,'https://www.youtube.com/watch?v=pbzoW8YeAsc&pp=ygUbZmVlbCBnb29kIGluYyBiYXNzIHR1dG9yaWFs','img_692382fd479dd9.21497294.jpg'),(48,'Contra Baixo Tagima Natural','4 Cordas; Destro','CORPO: Basswood\r\nBRAÇO: Maple\r\nESCALA: Rosewood, com 22 trastes médio jumbo\r\nNUT (CAPO TRASTE): Osso\r\nMEDIDA NUT: 40mm\r\nCAPTADORES: Modelo MM\r\nCONTROLES: 1 controle de volume, 1 de agudo, 1 de médio, 1 de grave',1800.00,3,'https://www.youtube.com/watch?v=pbzoW8YeAsc&pp=ygUbZmVlbCBnb29kIGluYyBiYXNzIHR1dG9yaWFs','img_692383b14b4803.84378052.jpg'),(49,'Violao Eletroacustico FLAT NYLON NF14 Giannini','Preto; 6 Cordas; Destro','Cordas: Nylon \r\nTampo: Linden\r\nAcabamento: Verniz brilhante \r\nBraço: Catalpa com Tensor Bi-Direcional \r\nEscala: Maple Escurecido \r\nEqualização: Captador de rastilho',800.00,2,'https://www.youtube.com/watch?v=x4MKV8j-1DA&list=RDx4MKV8j-1DA&start_radio=1&pp=ygUTdmlvbGFvIG1wYiB0dXRvcmlhbKAHAQ%3D%3D','img_6923847c0886b2.72118999.jpg'),(50,'Ibanez Guitarra elétrica GIO Series','Azul; 6 Cordas; Destro','Marca	Ibanez\r\nCor	Explosão azul transparente\r\nTipo de material da parte superior	Bordo, álamo\r\nMaterial da estrutura	Álamo',2000.00,1,'https://www.youtube.com/watch?v=yuKGo4MhUc8&pp=ygUcY2lmcmEgY2x1YiB0dXRvcmlhbCBndWl0YXJyYQ%3D%3D','img_692394f9769ba6.41722769.jpg');
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `perfil` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuarios_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (41,'Clarissa','Admin','clarissa@exemplo.com','$2y$10$AEyG8/pblS9bb8CnK.1i8u9xxTVdWizIpNBAgSe/dxTfnkZCGSIO6','2025-10-06 21:00:22','2025-10-06 21:00:22'),(45,'Mateo','User','mateo@exemplo.com','$2y$10$m7oGQ2xeko5BM6ZMPJ.mBOOrheE5Ecq0xBGtLWcDyjJCr2sBxwCAy','2025-11-21 22:05:16','2025-11-21 22:05:16'),(46,'Rafa','Admin','rafa@exemplo.com','$2y$10$W.Y.RVuSp2DcD8THYigrfePqALqQ.yvlJZDRC1iwOiaEuYZILIUXS','2025-11-21 22:12:44','2025-11-23 17:06:49'),(49,'ryan','User','ryan@exemplo.com','$2y$10$OK5RLOM41DAiKyW.OylRse0HnSQ/vaC84L0r7HFjyCjWlbuOqrBie','2025-11-23 19:32:41','2025-11-23 19:32:41');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-23 20:14:05
