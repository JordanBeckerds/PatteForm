-- Insert into group_elems
INSERT INTO `group_elems` (logo, date_creation, color_primary, color_secondary, color_tertiary, donation_link_bool, donation_link) VALUES
('https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061688/pexels-cdomingues10-731022_nybf0p.jpg', '2025-07-09', '#1E3A8A', '#3B82F6', '#60A5FA', TRUE, 'https://donate.example.org');

-- Insert actualite entries
INSERT INTO `actualite` (titre, img, date_publication) VALUES
('Adoption Drive Summer 2025', 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061695/pexels-pixabay-220938_j3hj3d.jpg', '2025-07-01'),
('New Shelter Opening', 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061694/pexels-pixabay-160846_fsaggw.jpg', '2025-06-15'),
('Fundraising Gala Highlights', 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061693/pexels-jagheterjohann-1254140_zxsety.jpg', '2025-05-30'),
('Volunteer Spotlight: Sarah', 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061692/pexels-helenalopes-2253275_t0wddd.jpg', '2025-07-05'),
('Lost Pets Awareness Campaign', 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061691/pexels-gilberto-reyes-259461-825947_moxtqh.jpg', '2025-06-20');

-- Insert actualite_secs (mixing types: carousel, mosaic, img, desc) for actualite_id 1-5
-- Actualite 1 sections
INSERT INTO `actualite_secs` (actualite_id, type, sec_id, position) VALUES
(1, 'carousel', 1, 1),
(1, 'desc', 1, 2),
(1, 'img', 1, 3);

-- Actualite 2 sections
INSERT INTO `actualite_secs` (actualite_id, type, sec_id, position) VALUES
(2, 'desc', 2, 1),
(2, 'mosaic', 2, 2);

-- Actualite 3 sections
INSERT INTO `actualite_secs` (actualite_id, type, sec_id, position) VALUES
(3, 'desc', 3, 1),
(3, 'carousel', 3, 2),
(3, 'img', 3, 3);

-- Actualite 4 sections
INSERT INTO `actualite_secs` (actualite_id, type, sec_id, position) VALUES
(4, 'desc', 4, 1),
(4, 'img', 4, 2);

-- Actualite 5 sections
INSERT INTO `actualite_secs` (actualite_id, type, sec_id, position) VALUES
(5, 'mosaic', 5, 1),
(5, 'desc', 5, 2);

-- Insert desc_secs
INSERT INTO `desc_secs` (actualite_sec_id, sec_title, sec_txt) VALUES
-- For actualite 1 description section (sec_id=1)
(2, 'Summer Adoption Drive is Here!', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum.'),
-- For actualite 2 description section (sec_id=2)
(4, 'Grand Opening of Our New Shelter', 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.'),
-- For actualite 3 description section (sec_id=3)
(6, 'Gala Night Success', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.'),
-- For actualite 4 description section (sec_id=4)
(8, 'Meet Our Volunteer Sarah', 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
-- For actualite 5 description section (sec_id=5)
(10, 'Lost Pets Awareness', 'Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Pellentesque in ipsum id orci porta dapibus.');

-- Insert carousel_secs
INSERT INTO `carousel_secs` (actualite_sec_id) VALUES
(1), -- for actualite 1 carousel sec (sec_id=1)
(7); -- for actualite 3 carousel sec (sec_id=3)

-- Insert carousel_imgs (linked to carousel_secs above)
INSERT INTO `carousel_imgs` (carousel_id, img_url) VALUES
(1, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061695/pexels-pixabay-220938_j3hj3d.jpg'),
(1, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061694/pexels-pixabay-160846_fsaggw.jpg'),
(1, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061693/pexels-jagheterjohann-1254140_zxsety.jpg'),
(2, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061689/pexels-charlesdeluvio-1851164_n7jmrq.jpg'),
(2, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061688/pexels-cdomingues10-731022_nybf0p.jpg');

-- Insert mosaic_secs
INSERT INTO `mosaic_secs` (actualite_sec_id) VALUES
(9), -- actualite 2 mosaic (sec_id=2)
(11); -- actualite 5 mosaic (sec_id=5)

-- Insert mosaic_imgs
INSERT INTO `mosaic_imgs` (mosaic_id, img_url) VALUES
(1, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061690/pexels-chevanon-1108099_bmfboy.jpg'),
(1, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061689/pexels-charlesdeluvio-1851164_n7jmrq.jpg'),
(1, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061688/pexels-cdomingues10-731022_nybf0p.jpg'),
(2, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061691/pexels-gilberto-reyes-259461-825947_moxtqh.jpg'),
(2, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061692/pexels-helenalopes-2253275_t0wddd.jpg');

-- Insert img_secs (single images)
INSERT INTO `img_secs` (actualite_sec_id, img_url) VALUES
(3, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061687/pexels-anthony-shkraba-4348404.jpg'), -- actualite 1 img sec
(9, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061690/pexels-chevanon-1108099_bmfboy.jpg'), -- actualite 3 img sec
(12, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061688/pexels-cdomingues10-731022_nybf0p.jpg'), -- actualite 4 img sec
(13, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061693/pexels-jagheterjohann-1254140_zxsety.jpg'); -- extra image if needed

-- equipe table: Sample team members
INSERT INTO equipe (nom, prenom, statut, benevole, img) VALUES
('Dupont', 'Marie', 'Responsable', TRUE, 'marie_dupont.jpg'),
('Martin', 'Jean', 'Bénévole', TRUE, 'jean_martin.jpg'),
('Leclerc', 'Sophie', 'Secrétaire', FALSE, 'sophie_leclerc.jpg');

-- homepage_sections table: Homepage content blocks
INSERT INTO homepage_sections (section_key, title, description, img_url, button_text, button_link, visible) VALUES
('hero', 'Bienvenue à la SPA', 'Nous aimons nos animaux', 'hero.jpg', 'En savoir plus', 'google.com', TRUE),
('adoption', 'Adoptez un animal', 'Trouvez votre compagnon idéal', 'adoption.jpg', 'Voir les animaux', 'google.com', TRUE),
('donation', 'Faire un don', 'Aidez-nous à sauver des vies', 'donation.jpg', 'Faire un don', 'google.com', TRUE);

-- contact table: Contact messages
INSERT INTO `contact` (nom, prenom, email, message, date_envoi) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', 'Je souhaite plus d\'informations sur les modalités d\'adoption.', '2025-07-07 15:30:00'),
('Lemoine', 'Sophie', 'sophie.lemoine@example.com', 'Comment puis-je devenir bénévole ?', '2025-07-08 10:15:00'),
('Bouchard', 'Alexandre', 'alex.bouchard@example.com', 'Merci pour votre travail incroyable.', '2025-07-09 09:45:00');

-- animaux_a_adopter table: Animals available for adoption
INSERT INTO `animaux_a_adopter` (nom, espece, race, age, description, sos, enfant, chat, chien, autre, prix, sexe, categoriser, date_arriver) VALUES
('Luna', 'chien', 'Golden Retriever', 3, 'Luna est une chienne très affectueuse et énergique.', TRUE, TRUE, TRUE, TRUE, FALSE, 300, 'femelle', 'aucune', '2025-07-01'),
('Milo', 'chat', 'Siamois', 2, 'Milo adore les câlins et est très sociable.', FALSE, TRUE, FALSE, FALSE, FALSE, 150, 'male', 'aucune', '2025-06-20'),
('Nina', 'chien', 'Bouledogue Français', 1, 'Nina est douce et aime jouer avec les enfants.', TRUE, TRUE, TRUE, TRUE, FALSE, 200, 'femelle', 'aucune', '2025-07-05');

-- photo_chiens table: Dog photos
INSERT INTO `photo_chiens` (id, img) VALUES
(1, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061690/pexels-chevanon-1108099_bmfboy.jpg'),
(2, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061689/pexels-charlesdeluvio-1851164_n7jmrq.jpg'),
(3, 'https://res.cloudinary.com/dhf6cd8jr/image/upload/v1752061688/pexels-cdomingues10-731022_nybf0p.jpg');

-- users table: Website users (admin and normal users)
INSERT INTO `users` (username, password, admin) VALUES
('admin', 'hashed_password_admin', TRUE),
('user1', 'hashed_password_user1', FALSE),
('user2', 'hashed_password_user2', FALSE);

-- animaux_adopter table: Adopted animals record 
INSERT INTO `animaux_adopter` (nom, extra) VALUES 
('Charlie', 'Tatoo: 001'),
('Simba', 'Tatoo: 002'); 