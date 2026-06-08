-- PatteForm database schema
-- Compatible with MySQL 5.7+ and MariaDB 10.4+
-- Run this file once via the setup wizard or manually: mysql -u root -p patteform < sql/patteform.sql

SET FOREIGN_KEY_CHECKS = 0;

-- Shelter / organization identity and branding
CREATE TABLE IF NOT EXISTS `group_elems` (
  `id`                     INT          NOT NULL AUTO_INCREMENT,
  `group_name`             VARCHAR(75)  DEFAULT NULL,
  `adress`                 VARCHAR(255) DEFAULT NULL,
  `telephone`              VARCHAR(20)  DEFAULT NULL,
  `logo`                   VARCHAR(255) DEFAULT NULL,
  `date_creation`          DATE         DEFAULT NULL,
  `color_primary`          VARCHAR(9)   DEFAULT '#FFFFFF',
  `color_secondary`        VARCHAR(9)   DEFAULT '#FEF4EE',
  `color_tertiary`         VARCHAR(9)   DEFAULT '#F97316',
  `color_title`            VARCHAR(9)   DEFAULT '#1e293b',
  `homepage_main_color_text` VARCHAR(9) DEFAULT '#F97316',
  `homepage_main_bg`       VARCHAR(255) DEFAULT NULL,
  `social_facebook`        VARCHAR(255) DEFAULT '#',
  `social_instagram`       VARCHAR(255) DEFAULT '#',
  `social_twitter`         VARCHAR(255) DEFAULT '#',
  `horaires_ouvert`        VARCHAR(255) DEFAULT '',
  `donation_link_bool`     BOOLEAN      DEFAULT FALSE,
  `donation_link`          VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin users
CREATE TABLE IF NOT EXISTS `users` (
  `id`              INT          NOT NULL AUTO_INCREMENT,
  `username`        VARCHAR(100) NOT NULL,
  `email`           VARCHAR(255) NOT NULL DEFAULT '',
  `password`        VARCHAR(255) NOT NULL,
  `role`            ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  `failed_attempts` TINYINT      NOT NULL DEFAULT 0,
  `locked_until`    DATETIME     DEFAULT NULL,
  `created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Animals available for adoption
CREATE TABLE IF NOT EXISTS `animaux_a_adopter` (
  `id`           INT          NOT NULL AUTO_INCREMENT,
  `nom`          VARCHAR(255) DEFAULT NULL,
  `espece`       ENUM('chien','chat','autre') DEFAULT NULL,
  `race`         VARCHAR(255) DEFAULT NULL,
  `prix`         INT          DEFAULT NULL,
  `sexe`         ENUM('male','femelle') DEFAULT NULL,
  `age`          INT          DEFAULT NULL,
  `description`  TEXT         DEFAULT NULL,
  `enfant`       BOOLEAN      DEFAULT NULL COMMENT 'Compatible with children',
  `chat`         BOOLEAN      DEFAULT NULL COMMENT 'Compatible with cats',
  `chien`        BOOLEAN      DEFAULT NULL COMMENT 'Compatible with dogs',
  `autre`        BOOLEAN      DEFAULT NULL COMMENT 'Compatible with other animals',
  `categorie`    ENUM('aucune','1','2') DEFAULT 'aucune',
  `sos`          BOOLEAN      NOT NULL DEFAULT FALSE,
  `date_arriver` DATE         DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_espece` (`espece`),
  INDEX `idx_sos`    (`sos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Animals that have been adopted (references animaux_a_adopter)
CREATE TABLE IF NOT EXISTS `animaux_adopter` (
  `id`    INT          NOT NULL,
  `nom`   VARCHAR(255) DEFAULT NULL,
  `extra` TEXT         DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_adopter_id`
    FOREIGN KEY (`id`) REFERENCES `animaux_a_adopter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Animal photos
CREATE TABLE IF NOT EXISTS `photo_chiens` (
  `img_id` INT          NOT NULL AUTO_INCREMENT,
  `id`     INT          DEFAULT NULL,
  `img`    VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`img_id`),
  INDEX `idx_animal_id` (`id`),
  CONSTRAINT `fk_photo_animal`
    FOREIGN KEY (`id`) REFERENCES `animaux_a_adopter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- News articles
CREATE TABLE IF NOT EXISTS `actualite` (
  `id`               INT          NOT NULL AUTO_INCREMENT,
  `titre`            VARCHAR(255) DEFAULT NULL,
  `description`      TEXT         DEFAULT NULL,
  `img`              VARCHAR(255) DEFAULT NULL,
  `date_publication` DATE         DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sections within a news article
CREATE TABLE IF NOT EXISTS `actualite_secs` (
  `id`          INT  NOT NULL AUTO_INCREMENT,
  `actualite_id` INT NOT NULL,
  `type`        ENUM('carousel','mosaic','img','desc') NOT NULL,
  `position`    INT  DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_actu_id` (`actualite_id`),
  CONSTRAINT `fk_actu_sec_actu`
    FOREIGN KEY (`actualite_id`) REFERENCES `actualite` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `desc_secs` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `actualite_id` INT         NOT NULL,
  `sec_title`   VARCHAR(255) DEFAULT NULL,
  `sec_txt`     TEXT         DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_desc_sec_actu`
    FOREIGN KEY (`actualite_id`) REFERENCES `actualite` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `carousel_secs` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `actualite_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_carousel_sec`
    FOREIGN KEY (`actualite_id`) REFERENCES `actualite_secs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `carousel_imgs` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `carousel_id` INT          NOT NULL,
  `img_url`     VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_carousel_img`
    FOREIGN KEY (`carousel_id`) REFERENCES `carousel_secs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mosaic_secs` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `actualite_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_mosaic_sec`
    FOREIGN KEY (`actualite_id`) REFERENCES `actualite_secs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mosaic_imgs` (
  `id`        INT          NOT NULL AUTO_INCREMENT,
  `mosaic_id` INT          NOT NULL,
  `img_url`   VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_mosaic_img`
    FOREIGN KEY (`mosaic_id`) REFERENCES `mosaic_secs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `img_secs` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `actualite_id` INT         NOT NULL,
  `img_url`     VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_img_sec`
    FOREIGN KEY (`actualite_id`) REFERENCES `actualite_secs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vid_secs` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `actualite_id` INT         NOT NULL,
  `vid_url`     VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_vid_sec`
    FOREIGN KEY (`actualite_id`) REFERENCES `actualite_secs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Homepage configurable sections
CREATE TABLE IF NOT EXISTS `homepage_sections` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `section_key` VARCHAR(100) NOT NULL,
  `title`       VARCHAR(255) DEFAULT NULL,
  `description` TEXT         DEFAULT NULL,
  `img_url`     VARCHAR(255) DEFAULT NULL,
  `button_text` VARCHAR(100) DEFAULT NULL,
  `button_link` VARCHAR(255) DEFAULT NULL,
  `visible`     BOOLEAN      NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`id`),
  INDEX `idx_section_key` (`section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Visit scheduling requests
CREATE TABLE IF NOT EXISTS `Rencontrer` (
  `id`             INT         NOT NULL AUTO_INCREMENT,
  `dog_id`         INT         NOT NULL,
  `nom`            VARCHAR(255) NOT NULL,
  `prenom`         VARCHAR(255) NOT NULL,
  `email`          VARCHAR(255) NOT NULL,
  `telephone`      VARCHAR(20)  NOT NULL,
  `date_de_visite` DATE         NOT NULL,
  `heure_de_visite` SMALLINT   NOT NULL COMMENT 'Format: 1130 = 11h30',
  PRIMARY KEY (`id`),
  INDEX `idx_dog_id` (`dog_id`),
  CONSTRAINT `fk_rencontrer_animal`
    FOREIGN KEY (`dog_id`) REFERENCES `animaux_a_adopter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact form submissions
CREATE TABLE IF NOT EXISTS `contact` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `nom`        VARCHAR(255) DEFAULT NULL,
  `prenom`     VARCHAR(255) DEFAULT NULL,
  `email`      VARCHAR(255) DEFAULT NULL,
  `telephone`  VARCHAR(20)  DEFAULT NULL,
  `sujet`      VARCHAR(255) DEFAULT NULL,
  `message`    TEXT         DEFAULT NULL,
  `date_envoi` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu`         BOOLEAN      NOT NULL DEFAULT FALSE COMMENT 'Mark as read in dashboard',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Team members
CREATE TABLE IF NOT EXISTS `equipe` (
  `id`       INT          NOT NULL AUTO_INCREMENT,
  `nom`      VARCHAR(255) DEFAULT NULL,
  `prenom`   VARCHAR(255) DEFAULT NULL,
  `statut`   VARCHAR(255) DEFAULT NULL,
  `benevole` BOOLEAN      DEFAULT FALSE,
  `img`      VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
