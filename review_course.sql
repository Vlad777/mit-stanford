-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 17, 2013 at 10:55 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `moocs`
--

-- --------------------------------------------------------

--
-- Table structure for table `review_course`
--

DROP TABLE IF EXISTS `review_course`;
CREATE TABLE IF NOT EXISTS `review_course` (
  `userID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `starRating` int(11) NOT NULL,
  `comments` varchar(140) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `review_course`
--

INSERT INTO `review_course` (`userID`, `courseID`, `starRating`, `comments`) VALUES
(6, 10, 0, 'awesome'),
(6, 6, 0, 'awesome'),
(6, 1, 0, 'awesome'),
(6, 8, 0, 'awesome');
