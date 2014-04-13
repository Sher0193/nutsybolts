-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Host: mysql.markandrewgoetz.com
-- Generation Time: Apr 13, 2014 at 02:22 PM
-- Server version: 5.1.56
-- PHP Version: 5.3.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nb_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatlog`
--

CREATE TABLE IF NOT EXISTS `chatlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(10) unsigned NOT NULL,
  `message` varchar(200) NOT NULL,
  `stamp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_player_id` (`id`,`player_id`),
  KEY `player_id` (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `chatlog`
--


-- --------------------------------------------------------

--
-- Table structure for table `decks`
--

CREATE TABLE IF NOT EXISTS `decks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `decks`
--


-- --------------------------------------------------------

--
-- Table structure for table `gamelog`
--

CREATE TABLE IF NOT EXISTS `gamelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(10) unsigned NOT NULL,
  `type` char(10) NOT NULL,
  `extra_info` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `gamelog`
--


-- --------------------------------------------------------

--
-- Table structure for table `greencards`
--

CREATE TABLE IF NOT EXISTS `greencards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `deck_id` int(10) unsigned NOT NULL,
  `description` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `greencards`
--


-- --------------------------------------------------------

--
-- Table structure for table `hands`
--

CREATE TABLE IF NOT EXISTS `hands` (
  `player_id` int(10) unsigned NOT NULL,
  `card_id` int(10) unsigned NOT NULL,
  `card_order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`player_id`,`card_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hands`
--


-- --------------------------------------------------------

--
-- Table structure for table `ignores`
--

CREATE TABLE IF NOT EXISTS `ignores` (
  `player_id` int(10) unsigned NOT NULL,
  `ignored_player_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`player_id`,`ignored_player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ignores`
--


-- --------------------------------------------------------

--
-- Table structure for table `playedcards`
--

CREATE TABLE IF NOT EXISTS `playedcards` (
  `player_id` int(10) unsigned NOT NULL,
  `round` int(10) unsigned NOT NULL,
  `card_id` int(10) unsigned NOT NULL,
  `winner` tinyint(1) NOT NULL,
  PRIMARY KEY (`player_id`,`round`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `playedcards`
--


-- --------------------------------------------------------

--
-- Table structure for table `playerlastseen`
--

CREATE TABLE IF NOT EXISTS `playerlastseen` (
  `player_id` int(11) NOT NULL,
  `last_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `playerlastseen`
--


-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(10) unsigned NOT NULL,
  `name` char(20) NOT NULL,
  `score` int(10) unsigned NOT NULL DEFAULT '0',
  `is_away` tinyint(1) NOT NULL,
  `judge_order` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_address` char(15) NOT NULL,
  `password` char(6) NOT NULL,
  `color` char(6) NOT NULL,
  `is_creator` tinyint(1) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `skipped` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `room_id_deleted_id` (`room_id`,`deleted`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `players`
--


-- --------------------------------------------------------

--
-- Table structure for table `redcards`
--

CREATE TABLE IF NOT EXISTS `redcards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `deck_id` int(10) unsigned NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `redcards`
--


-- --------------------------------------------------------

--
-- Table structure for table `roomgreencards`
--

CREATE TABLE IF NOT EXISTS `roomgreencards` (
  `room_id` int(10) unsigned NOT NULL,
  `card_id` int(10) unsigned NOT NULL,
  `card_order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`room_id`,`card_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roomgreencards`
--


-- --------------------------------------------------------

--
-- Table structure for table `roomlastseen`
--

CREATE TABLE IF NOT EXISTS `roomlastseen` (
  `room_id` int(10) unsigned NOT NULL,
  `last_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`room_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roomlastseen`
--


-- --------------------------------------------------------

--
-- Table structure for table `roomredcards`
--

CREATE TABLE IF NOT EXISTS `roomredcards` (
  `room_id` int(10) unsigned NOT NULL,
  `card_id` int(10) unsigned NOT NULL,
  `card_order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`room_id`,`card_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roomredcards`
--


-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `max_players` int(10) unsigned NOT NULL,
  `round_num` int(10) unsigned NOT NULL DEFAULT '0',
  `phase` tinyint(4) NOT NULL,
  `max_rounds` int(10) unsigned NOT NULL,
  `password` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `judge_num` int(10) unsigned NOT NULL,
  `deck_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rooms`
--


-- --------------------------------------------------------

--
-- Table structure for table `skipvote`
--

CREATE TABLE IF NOT EXISTS `skipvote` (
  `voting_player_id` int(10) unsigned NOT NULL,
  `skipped_player_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`voting_player_id`,`skipped_player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skipvote`
--

