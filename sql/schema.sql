-- ------------------------
-- Table: actualite
-- ------------------------
CREATE TABLE `actualite` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `img` VARCHAR(255) DEFAULT NULL,
  `date_publication` DATE DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: actualite_secs
-- ------------------------
CREATE TABLE `actualite_secs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `actualite_id` INT(11) NOT NULL,
  `type` ENUM('carousel', 'mosaic', 'img', 'desc') NOT NULL,
  `sec_id` INT(11) NOT NULL,
  `position` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`actualite_id`) REFERENCES `actualite` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: carousel_secs
-- ------------------------
CREATE TABLE `carousel_secs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `actualite_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`actualite_id`) REFERENCES `actualite` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: carousel_imgs
-- ------------------------
CREATE TABLE `carousel_imgs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `carousel_id` INT(11) NOT NULL,
  `img_url` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`carousel_id`) REFERENCES `carousel_secs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: mosaic_secs
-- ------------------------
CREATE TABLE `mosaic_secs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `actualite_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`actualite_id`) REFERENCES `actualite` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: mosaic_imgs
-- ------------------------
CREATE TABLE `mosaic_imgs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `mosaic_id` INT(11) NOT NULL,
  `img_url` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`mosaic_id`) REFERENCES `mosaic_secs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: img_secs
-- ------------------------
CREATE TABLE `img_secs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `actualite_id` INT(11) NOT NULL,
  `img_url` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`actualite_id`) REFERENCES `actualite` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: desc_secs
-- ------------------------
CREATE TABLE `desc_secs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `actualite_id` INT(11) NOT NULL,
  `sec_title` VARCHAR(255) DEFAULT NULL,
  `sec_txt` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`actualite_id`) REFERENCES `actualite` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: contact
-- ------------------------
CREATE TABLE `contact` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(255) DEFAULT NULL,
  `prenom` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `telephone` VARCHAR(20) DEFAULT NULL,
  `sujet` VARCHAR(255) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `date_envoi` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: equipe
-- ------------------------
CREATE TABLE `equipe` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(255) DEFAULT NULL,
  `prenom` VARCHAR(255) DEFAULT NULL,
  `statut` VARCHAR(255) DEFAULT NULL,
  `benevole` BOOLEAN DEFAULT NULL,
  `img` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: users
-- ------------------------
CREATE TABLE `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `admin` BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: homepage_sections
-- ------------------------
CREATE TABLE `homepage_sections` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `section_key` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `img_url` VARCHAR(255) DEFAULT NULL,
  `button_text` VARCHAR(100) DEFAULT NULL,
  `button_link` VARCHAR(255) DEFAULT NULL,
  `visible` BOOLEAN DEFAULT TRUE,
  PRIMARY KEY (`id`),
  INDEX idx_section (`section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: animaux_a_adopter
-- ------------------------
CREATE TABLE `animaux_a_adopter` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(255) DEFAULT NULL,
  `espece` ENUM('chien','chat','autre') DEFAULT NULL,
  `race` VARCHAR(255) DEFAULT NULL,
  `prix` INT DEFAULT NULL,
  `sexe` ENUM('male','femelle') DEFAULT NULL,
  `age` INT DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `autre` BOOLEAN DEFAULT NULL,
  `enfant` BOOLEAN DEFAULT NULL,
  `chat` BOOLEAN DEFAULT NULL,
  `chien` BOOLEAN DEFAULT NULL,
  `categoriser` ENUM('aucune','1','2') DEFAULT NULL,
  `sos` BOOLEAN DEFAULT NULL,
  `date_arriver` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX idx_espece (`espece`),
  INDEX idx_sos (`sos`),
  INDEX idx_date_arriver (`date_arriver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: animaux_adopter
-- ------------------------
CREATE TABLE `animaux_adopter` (
  `id` INT(11),
  `nom` VARCHAR(255),
  `extra` TEXT,
  PRIMARY KEY (`id`),
  CONSTRAINT `animaux_adopter_ibfk_1` FOREIGN KEY (`id`) REFERENCES `animaux_a_adopter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: photo_chiens
-- ------------------------
CREATE TABLE `photo_chiens` (
  `img_id` INT(11) NOT NULL AUTO_INCREMENT,
  `id` INT(11) DEFAULT NULL,
  `img` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`img_id`),
  KEY `id` (`id`),
  CONSTRAINT `photo_chiens_ibfk_1` FOREIGN KEY (`id`) REFERENCES `animaux_a_adopter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------
-- Table: group_elems
-- ------------------------
CREATE TABLE `group_elems` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `logo` VARCHAR(255) DEFAULT NULL,
  `date_creation` DATE DEFAULT NULL,
  `color_primary` VARCHAR(7) DEFAULT NULL,
  `color_secondary` VARCHAR(7) DEFAULT NULL,
  `color_tertiary` VARCHAR(7) DEFAULT NULL,
  `donation_link_bool` BOOLEAN DEFAULT FALSE,
  `donation_link` VARCHAR(255) DEFAULT NULL,
  `homepage_main_bg` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;