-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Apr 2025 um 20:18
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `gestion_budget`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `type` enum('revenu','depense') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `categories`
--

INSERT INTO `categories` (`id`, `nom`, `type`) VALUES
(1, 'Salaire', 'revenu'),
(2, 'Bourse', 'revenu'),
(3, 'Ventes', 'revenu'),
(4, 'Autres', 'revenu'),
(5, 'Logement', 'depense'),
(6, 'Transport', 'depense'),
(7, 'Alimentation', 'depense'),
(8, 'Santé', 'depense'),
(9, 'Divertissement', 'depense'),
(10, 'Éducation', 'depense'),
(11, 'Autres', 'depense');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `date_transaction` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `category_id`, `montant`, `description`, `date_transaction`) VALUES
(2, 2, 4, 4555.00, 'revuni', '2025-04-23'),
(3, 2, 2, 0.00, '556666', '0000-00-00'),
(4, 2, 1, 1500.00, 'Vente de produits', '2025-04-17'),
(5, 2, 2, 200.00, 'Bourse universitaire', '2025-04-16'),
(6, 2, 5, 1200.50, 'Loyer mensuel', '2025-04-15'),
(7, 2, 6, 400.00, 'Transport en commun', '2025-04-14'),
(8, 2, 7, 350.00, 'Achat alimentaire', '2025-04-13'),
(9, 2, 8, 250.00, 'Consultation médicale', '2025-04-12'),
(10, 2, 9, 100.00, 'Cinéma et loisirs', '2025-04-11'),
(11, 2, 10, 150.00, 'Frais de scolarité', '2025-04-10'),
(12, 2, 5, 1200.00, 'Loyer mensuel', '2025-03-25'),
(13, 2, 6, 450.00, 'Transport en taxi', '2025-03-20'),
(14, 2, 7, 300.00, 'Courses alimentaires', '2025-03-18'),
(15, 2, 8, 200.00, 'Médecine générale', '2025-03-15'),
(16, 2, 9, 120.00, 'Sortie au restaurant', '2025-03-10'),
(17, 2, 10, 180.00, 'Cahiers et livres scolaires', '2025-03-05'),
(18, 2, 1, 2500.00, 'Vente de marchandises', '2025-02-28'),
(19, 2, 2, 350.00, 'Bourse du mois', '2025-02-25'),
(20, 2, 5, 1000.00, 'Logement et facture', '2025-02-20'),
(21, 2, 6, 300.00, 'Transport urbain', '2025-02-18'),
(22, 2, 7, 450.00, 'Supermarché', '2025-02-15'),
(23, 2, 8, 180.00, 'Consultation médicale', '2025-02-12');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `password`, `created_at`) VALUES
(1, 'soufiane', 'azizsoufiane@gmail.com', '12345678', '2025-04-13 19:56:58'),
(2, 'salim', 'salim@gmail.com', '12345678v', '2025-04-18 11:04:29'),
(3, 'aziz', 'aziz@gmail.com', '12345678', '2025-04-18 19:17:47');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT für Tabelle `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
