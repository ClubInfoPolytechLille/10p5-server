SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE IF NOT EXISTS `Clients` (
  `idCarte` char(8) NOT NULL,
  `solde` float(7,2) DEFAULT NULL,
  `decouvert` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idCarte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Prix` (
  `produit` char(30) DEFAULT NULL,
  `prix` float(7,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Prix` (`produit`, `prix`) VALUES
('biere', 1.80);

CREATE TABLE IF NOT EXISTS `Sessions` (
  `jeton` char(30) NOT NULL,
  `utilisateur` char(30) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`jeton`),
  KEY `utilisateur` (`utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` char(15) DEFAULT NULL,
  `client` char(8) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `montant` float(7,2) DEFAULT NULL,
  `quantite` int(2) DEFAULT NULL,
  `utilisateur` char(30) DEFAULT NULL,
  `valide` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `client` (`client`),
  KEY `utilisateur` (`utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Utilisateurs` (
  `login` char(30) NOT NULL,
  `mdp` char(255) DEFAULT NULL,
  `idCarte` char(8) DEFAULT NULL,
  `droit` int(11) DEFAULT '0',
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Utilisateurs` (`login`, `mdp`, `idCarte`, `droit`) VALUES
('default', '$2y$10$rc6Cht/F2DM4grcOcRAGBOtd.BD56kJgkC/IiGcnV6Oqa8hjRCxSW', 'OHCEE7UH', 3);


ALTER TABLE `Sessions`
  ADD CONSTRAINT `Sessions_utilisateur` FOREIGN KEY (`utilisateur`) REFERENCES `Utilisateurs` (`login`);

ALTER TABLE `Transactions`
  ADD CONSTRAINT `Transactions_client` FOREIGN KEY (`client`) REFERENCES `Clients` (`idCarte`),
  ADD CONSTRAINT `Transactions_utilisateur` FOREIGN KEY (`utilisateur`) REFERENCES `Utilisateurs` (`login`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
