-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 30, 2024 at 11:17 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sklepp`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kosz`
--

CREATE TABLE `kosz` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `owoce_id` int(11) NOT NULL,
  `ilosc` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkty`
--

CREATE TABLE `produkty` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL,
  `typ` enum('owoc','warzywo') NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `ilosc` int(11) NOT NULL,
  `sciezka_do_obrazka` varchar(255) DEFAULT NULL,
  `opis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produkty`
--

INSERT INTO `produkty` (`id`, `nazwa`, `typ`, `cena`, `ilosc`, `sciezka_do_obrazka`, `opis`) VALUES
(1, 'Jabłko', 'owoc', 2.50, 0, 'images/apple.jpg', 'Świeże i soczyste jabłka.'),
(2, 'Banan', 'owoc', 1.20, 97, 'images/banana.jpg', 'Słodkie i dojrzałe banany.'),
(3, 'Marchewka', 'warzywo', 0.80, 190, 'images/carrot.jpg', 'Chrupiące i pożywne marchewki.'),
(4, 'Pomidor', 'warzywo', 1.50, 180, 'images/tomato.jpg', 'Świeże i dojrzałe pomidory.');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `user` text NOT NULL,
  `pass` text NOT NULL,
  `email` text NOT NULL,
  `role` enum('administrator','uzytkownik','sprzedawca') NOT NULL DEFAULT 'uzytkownik'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `user`, `pass`, `email`, `role`) VALUES
(2, 'marek', 'asdfg', 'marek@gmail.com', 'uzytkownik'),
(3, 'anna', 'zxcvb', 'anna@gmail.com', 'sprzedawca'),
(14, 'Lew635', 'Bartek10', 'bdembowski1010@gmail.com', 'administrator');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE `zamowienia` (
  `id_p` int(11) NOT NULL,
  `user` varchar(11) NOT NULL,
  `status` enum('realizowane','zrealizowane') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zamowienia`
--

INSERT INTO `zamowienia` (`id_p`, `user`, `status`) VALUES
(4, 'user', 'realizowane'),
(5, 'Lew635', 'realizowane');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kosz`
--
ALTER TABLE `kosz`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD PRIMARY KEY (`id_p`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kosz`
--
ALTER TABLE `kosz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `id_p` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
