-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 10. Mai 2011 um 17:50
-- Server Version: 5.1.50
-- PHP-Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `iaudiotestdb`
--
DROP DATABASE IF EXISTS`iaudiotestdb`;
CREATE DATABASE `iaudiotestdb` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `iaudiotestdb`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `music`
--

DROP TABLE IF EXISTS `music`;
CREATE TABLE IF NOT EXISTS `music` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UID` int(11) NOT NULL,
  `Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Artist` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Link64` text COLLATE utf8_unicode_ci NOT NULL,
  `Link128` text COLLATE utf8_unicode_ci NOT NULL,
  `Link192` text COLLATE utf8_unicode_ci NOT NULL,
  `Location64` text COLLATE utf8_unicode_ci NOT NULL,
  `Location128` text COLLATE utf8_unicode_ci NOT NULL,
  `Location192` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `music`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `playlist`
--

DROP TABLE IF EXISTS `playlist`;
CREATE TABLE IF NOT EXISTS `playlist` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `UID` int(11) NOT NULL,
  `Count` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `playlist`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `playlistmusic`
--

DROP TABLE IF EXISTS `playlistmusic`;
CREATE TABLE IF NOT EXISTS `playlistmusic` (
  `MID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `Number` int(11) NOT NULL,
  PRIMARY KEY (`MID`,`PID`),
  KEY `PID` (`PID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `playlistmusic`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `sessionid` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `UID` int(11) NOT NULL,
  PRIMARY KEY (`sessionid`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `session`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Mail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `PW` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `Streamingrate` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '128',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `user`
--
INSERT INTO `user` (`ID`, `Name`, `Mail`, `PW`, `Streamingrate`) VALUES
(1, 'Christian Posselt', 'posselt@hm.edu', '07104e60ccaa86c5cd45f014802327f4', '128'),
(2, 'Tester1', 'test@iAudio.com', '098f6bcd4621d373cade4e832627b4f6', '128'),
(3, 'Tester2', 'tester2@iAudio.com', '098f6bcd4621d373cade4e832627b4f6', '128');
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `music_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `user` (`ID`);

--
-- Constraints der Tabelle `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `playlist_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `user` (`ID`);

--
-- Constraints der Tabelle `playlistmusic`
--
ALTER TABLE `playlistmusic`
  ADD CONSTRAINT `playlistmusic_ibfk_1` FOREIGN KEY (`MID`) REFERENCES `music` (`ID`),
  ADD CONSTRAINT `playlistmusic_ibfk_2` FOREIGN KEY (`PID`) REFERENCES `playlist` (`ID`);

--
-- Constraints der Tabelle `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `user` (`ID`);
