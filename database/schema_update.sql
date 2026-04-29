-- Maslaki Professional Platform - Schema Update
-- Focus: Categories, Villes, Filieres, and Detailed Institutions

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- 1. Create Villes table
CREATE TABLE IF NOT EXISTS `villes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Create Categories table
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Create Filieres table
CREATE TABLE IF NOT EXISTS `filieres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_filiere_category` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Create Institution_Filieres pivot table
CREATE TABLE IF NOT EXISTS `institution_filieres` (
  `institution_id` int(11) NOT NULL,
  `filiere_id` int(11) NOT NULL,
  PRIMARY KEY (`institution_id`, `filiere_id`),
  CONSTRAINT `fk_pivot_institution` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pivot_filiere` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. Update Institutions table structure
-- We add columns if they don't exist
ALTER TABLE `institutions` 
ADD COLUMN IF NOT EXISTS `ville_id` int(11) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `image` varchar(255) DEFAULT 'default_school.jpg',
ADD COLUMN IF NOT EXISTS `site_web` varchar(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `seuil` float DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `duree_etudes` varchar(50) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `diplome` varchar(150) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `is_popular` boolean DEFAULT false;

-- Add foreign key for ville
-- Note: In a real migration, we would map the existing 'city' text to 'ville_id'
-- For this setup, we'll do it in the seed data

-- --------------------------------------------------------
-- SEED DATA
-- --------------------------------------------------------

-- Villes
INSERT IGNORE INTO `villes` (`id`, `nom`) VALUES
(1, 'Casablanca'), (2, 'Rabat'), (3, 'Marrakech'), (4, 'Fes'), (5, 'Tanger'),
(6, 'Agadir'), (7, 'Oujda'), (8, 'Kenitra'), (9, 'Settat'), (10, 'Meknes');

-- Categories
INSERT IGNORE INTO `categories` (`id`, `nom`) VALUES
(1, 'Sciences'), (2, 'Économie & Gestion'), (3, 'Lettres'), (4, 'Sciences Humaines'),
(5, 'Informatique'), (6, 'Santé'), (7, 'Droit'), (8, 'Arts'), (9, 'Technologie');

-- Filieres
INSERT IGNORE INTO `filieres` (`id`, `nom`, `description`, `categorie_id`) VALUES
(1, 'Génie Informatique', 'Conception et développement de systèmes logiciels', 5),
(2, 'Finance', 'Gestion financière et marchés de capitaux', 2),
(3, 'Marketing', 'Stratégies commerciales et communication', 2),
(4, 'Droit Français', 'Étude du système juridique francophone', 7),
(5, 'Médecine', 'Études médicales générales', 6),
(6, 'Architecture', 'Conception architecturale et urbanisme', 8),
(7, 'Data Science', 'Analyse de données et intelligence artificielle', 5),
(8, 'Gestion des Entreprises', 'Management et administration', 2);

-- Update existing institutions with new data
UPDATE `institutions` SET `ville_id` = 1, `image` = 'ensa_casa.jpg', `seuil` = 12, `duree_etudes` = '5 ans', `diplome` = 'Diplôme d\'Ingénieur', `is_popular` = true WHERE `name` LIKE '%ENSA Casablanca%';
UPDATE `institutions` SET `ville_id` = 1, `image` = 'encg_casa.jpg', `seuil` = 11, `duree_etudes` = '5 ans', `diplome` = 'Diplôme ENCG', `is_popular` = true WHERE `name` LIKE '%ENCG Casablanca%';
UPDATE `institutions` SET `ville_id` = 2, `image` = 'emi_rabat.jpg', `seuil` = 15, `duree_etudes` = '3 ans', `diplome` = 'Diplôme d\'Ingénieur' WHERE `name` LIKE '%EMI Rabat%';
UPDATE `institutions` SET `ville_id` = 2, `image` = 'uir_rabat.jpg', `seuil` = 12, `duree_etudes` = '5 ans', `diplome` = 'Master / Ingénieur' WHERE `name` LIKE '%UIR%';

-- Link some institutions to filieres (Sample)
INSERT IGNORE INTO `institution_filieres` (`institution_id`, `filiere_id`) VALUES
(1, 1), (1, 7), -- ENSA Casa -> Genie Info, Data Science
(2, 2), (2, 3), -- ENCG Casa -> Finance, Marketing
(10, 1);         -- EMI Rabat -> Genie Info

COMMIT;
