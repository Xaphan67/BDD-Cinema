-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour cinema
CREATE DATABASE IF NOT EXISTS `cinema` /*!40100 DEFAULT CHARACTER SET latin1 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cinema`;

-- Listage de la structure de table cinema. acteur
CREATE TABLE IF NOT EXISTS `acteur` (
  `id_acteur` int NOT NULL AUTO_INCREMENT,
  `id_personne` int NOT NULL,
  PRIMARY KEY (`id_acteur`),
  UNIQUE KEY `id_personne` (`id_personne`),
  CONSTRAINT `acteur_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Listage des données de la table cinema.acteur : ~6 rows (environ)
INSERT INTO `acteur` (`id_acteur`, `id_personne`) VALUES
	(1, 1),
	(2, 2),
	(3, 3),
	(4, 4),
	(5, 5),
	(6, 6);

-- Listage de la structure de table cinema. film
CREATE TABLE IF NOT EXISTS `film` (
  `id_film` int NOT NULL AUTO_INCREMENT,
  `titre_film` varchar(50) NOT NULL,
  `anneeSortie_film` int NOT NULL,
  `duree_film` int NOT NULL,
  `synopsis_film` text,
  `note_film` int NOT NULL,
  `affiche_film` varchar(50) DEFAULT NULL,
  `id_realisateur` int NOT NULL,
  PRIMARY KEY (`id_film`),
  KEY `id_realisateur` (`id_realisateur`),
  CONSTRAINT `film_ibfk_1` FOREIGN KEY (`id_realisateur`) REFERENCES `realisateur` (`id_realisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Listage des données de la table cinema.film : ~3 rows (environ)
INSERT INTO `film` (`id_film`, `titre_film`, `anneeSortie_film`, `duree_film`, `synopsis_film`, `note_film`, `affiche_film`, `id_realisateur`) VALUES
	(1, 'Orange mécanique', 1971, 136, 'Au XXIème siècle, où règnent la violence et le sexe, Alex, jeune chef de bande, exerce avec sadisme une terreur aveugle. Après son emprisonnement, des psychanalystes l\'emploient comme cobaye dans des expériences destinées à juguler la criminalité...', 4, 'orangeMecanique.png', 1),
	(2, 'Eraserhead', 1977, 89, 'Un homme est abandonné par son amie qui lui laisse la charge d\'un enfant prématuré, fruit de leur union. Il s\'enfonce dans un univers fantasmatique pour fuir cette cruelle réalité.', 3, 'eraserhead.png', 2),
	(3, 'El Topo', 1970, 125, 'Hors-la-loi, El Topo défie pour l\'amour d\'une femme les Quatre Maîtres du Désert. Les ayant vaincus, sa conscience s\'élève jusqu\'à ce que sa femme le trahisse. Sa nouvelle vie d\'homme saint commence alors.', 3, 'elTopo.png', 3);

-- Listage de la structure de table cinema. genre_film
CREATE TABLE IF NOT EXISTS `genre_film` (
  `id_genre_film` int NOT NULL AUTO_INCREMENT,
  `libelle_genre_film` varchar(50) NOT NULL,
  PRIMARY KEY (`id_genre_film`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Listage des données de la table cinema.genre_film : ~6 rows (environ)
INSERT INTO `genre_film` (`id_genre_film`, `libelle_genre_film`) VALUES
	(1, 'Drame'),
	(2, 'Science-fiction'),
	(3, 'Epouvante-Horreur'),
	(4, 'Fantastique'),
	(5, 'Expérimental'),
	(6, 'Western');

-- Listage de la structure de table cinema. jouer
CREATE TABLE IF NOT EXISTS `jouer` (
  `id_film` int NOT NULL,
  `id_rôle` int NOT NULL,
  `id_acteur` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_rôle`,`id_acteur`),
  KEY `id_rôle` (`id_rôle`),
  KEY `id_acteur` (`id_acteur`),
  CONSTRAINT `jouer_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`),
  CONSTRAINT `jouer_ibfk_2` FOREIGN KEY (`id_rôle`) REFERENCES `rôle` (`id_rôle`),
  CONSTRAINT `jouer_ibfk_3` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id_acteur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Listage des données de la table cinema.jouer : ~9 rows (environ)
INSERT INTO `jouer` (`id_film`, `id_rôle`, `id_acteur`) VALUES
	(1, 1, 1),
	(1, 2, 2),
	(1, 3, 3),
	(2, 4, 2),
	(2, 5, 4),
	(2, 6, 5),
	(3, 7, 1),
	(3, 8, 4),
	(3, 9, 6);

-- Listage de la structure de table cinema. personne
CREATE TABLE IF NOT EXISTS `personne` (
  `id_personne` int NOT NULL AUTO_INCREMENT,
  `nom_personne` varchar(50) NOT NULL,
  `prenom_personne` varchar(50) NOT NULL,
  `sexe_personne` varchar(50) NOT NULL,
  `dateNaissance_personne` date NOT NULL,
  PRIMARY KEY (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Listage des données de la table cinema.personne : ~9 rows (environ)
INSERT INTO `personne` (`id_personne`, `nom_personne`, `prenom_personne`, `sexe_personne`, `dateNaissance_personne`) VALUES
	(1, 'Pacino', 'Al', 'Homme', '1940-04-25'),
	(2, 'De Niro', 'Robert', 'Homme', '1943-08-17'),
	(3, 'DiCaprio', 'Leonardo', 'Homme', '1974-11-11'),
	(4, 'Lawrence', 'Jennifer', 'Femme', '1990-08-15'),
	(5, 'Knightley', 'Keira', 'Femme', '1985-03-26'),
	(6, 'Theron', 'Charlize', 'Femme', '1975-08-07'),
	(7, 'Kubrick', 'Stanley', 'Homme', '1928-07-26'),
	(8, 'Lynch', 'David', 'Homme', '1946-01-20'),
	(9, 'Jodorowsky', 'Alejandro', 'Homme', '1929-02-07');

-- Listage de la structure de table cinema. posseder
CREATE TABLE IF NOT EXISTS `posseder` (
  `id_film` int NOT NULL,
  `id_genre_film` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_genre_film`),
  KEY `id_genre_film` (`id_genre_film`),
  CONSTRAINT `posseder_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`),
  CONSTRAINT `posseder_ibfk_2` FOREIGN KEY (`id_genre_film`) REFERENCES `genre_film` (`id_genre_film`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Listage des données de la table cinema.posseder : ~7 rows (environ)
INSERT INTO `posseder` (`id_film`, `id_genre_film`) VALUES
	(1, 1),
	(1, 2),
	(2, 3),
	(2, 4),
	(3, 4),
	(2, 5),
	(3, 6);

-- Listage de la structure de table cinema. realisateur
CREATE TABLE IF NOT EXISTS `realisateur` (
  `id_realisateur` int NOT NULL AUTO_INCREMENT,
  `id_personne` int NOT NULL,
  PRIMARY KEY (`id_realisateur`),
  UNIQUE KEY `id_personne` (`id_personne`),
  CONSTRAINT `realisateur_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Listage des données de la table cinema.realisateur : ~3 rows (environ)
INSERT INTO `realisateur` (`id_realisateur`, `id_personne`) VALUES
	(1, 7),
	(2, 8),
	(3, 9);

-- Listage de la structure de table cinema. rôle
CREATE TABLE IF NOT EXISTS `rôle` (
  `id_rôle` int NOT NULL AUTO_INCREMENT,
  `nom_rôle` varchar(50) NOT NULL,
  PRIMARY KEY (`id_rôle`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Listage des données de la table cinema.rôle : ~9 rows (environ)
INSERT INTO `rôle` (`id_rôle`, `nom_rôle`) VALUES
	(1, 'Alex'),
	(2, 'M. Alexander'),
	(3, 'M. DeLarge'),
	(4, 'Henry Spencer'),
	(5, 'Monsieur Roundheels'),
	(6, 'Paul'),
	(7, 'El Topo'),
	(8, 'Hijo'),
	(9, 'Moribundo');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
