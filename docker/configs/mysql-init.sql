-- Init for MySQL 8.4 with legacy mode for Joomla 1.0 compatibility
-- This script sets up the database with maximum compatibility settings

-- Set global SQL mode to empty for maximum legacy compatibility
SET GLOBAL sql_mode = '';
SET SESSION sql_mode = '';

-- Set global authentication plugin to native password for legacy compatibility
SET GLOBAL default_authentication_plugin = 'mysql_native_password';

-- Create database with legacy-compatible settings
CREATE DATABASE IF NOT EXISTS `joomla_test` 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_general_ci;

-- Create user with native password authentication (legacy mode)
CREATE USER IF NOT EXISTS 'joomla'@'%' 
  IDENTIFIED WITH mysql_native_password BY 'joomlapassword';

-- Grant all privileges on the joomla_test database
GRANT ALL PRIVILEGES ON `joomla_test`.* TO 'joomla'@'%';

-- Also grant privileges for any future databases that might be created
GRANT CREATE ON *.* TO 'joomla'@'%';

-- Flush privileges to ensure changes take effect
FLUSH PRIVILEGES;

-- Initialize Joomla 1.0 database schema
-- This creates the basic tables needed for Joomla 1.0
USE joomla_test;

-- Core Joomla 1.0 tables (MySQL 8.4 compatible with legacy mode)
CREATE TABLE IF NOT EXISTS `jos_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `menuid` int(11) NOT NULL DEFAULT 0,
  `parent` int(11) NOT NULL DEFAULT 0,
  `admin_menu_link` varchar(255) NOT NULL DEFAULT '',
  `admin_menu_alt` varchar(255) NOT NULL DEFAULT '',
  `option` varchar(50) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT 0,
  `admin_menu_img` varchar(255) NOT NULL DEFAULT '',
  `iscore` tinyint(4) NOT NULL DEFAULT 0,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `jos_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `title_alias` varchar(255) NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT 0,
  `sectionid` int(11) NOT NULL DEFAULT 0,
  `mask` int(11) NOT NULL DEFAULT 0,
  `catid` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT 0,
  `checked_out` int(11) NOT NULL DEFAULT 0,
  `checked_out_time` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` text NOT NULL,
  `version` int(11) NOT NULL DEFAULT 1,
  `parentid` int(11) NOT NULL DEFAULT 0,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(11) NOT NULL DEFAULT 0,
  `hits` int(11) NOT NULL DEFAULT 0,
  `metadata` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `jos_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(75) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `parent` int(11) NOT NULL DEFAULT 0,
  `componentid` int(11) NOT NULL DEFAULT 0,
  `sublevel` int(11) NOT NULL DEFAULT 0,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `checked_out` int(11) NOT NULL DEFAULT 0,
  `checked_out_time` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `pollid` int(11) NOT NULL DEFAULT 0,
  `browserNav` tinyint(4) NOT NULL DEFAULT 0,
  `access` int(11) NOT NULL DEFAULT 0,
  `utaccess` int(11) NOT NULL DEFAULT 0,
  `params` text NOT NULL,
  `lft` int(11) NOT NULL DEFAULT 0,
  `rgt` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_componentid` (`componentid`),
  KEY `idx_menutype` (`menutype`),
  KEY `idx_parent` (`parent`),
  KEY `idx_published` (`published`),
  KEY `idx_access` (`access`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `jos_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `usertype` varchar(25) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT 0,
  `sendEmail` tinyint(4) DEFAULT 0,
  `gid` tinyint(3) NOT NULL DEFAULT 1,
  `registerDate` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_usertype` (`usertype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `jos_sessions` (
  `username` varchar(50) NOT NULL DEFAULT '',
  `time` varchar(14) NOT NULL DEFAULT '',
  `session_id` varchar(200) NOT NULL DEFAULT '',
  `guest` tinyint(4) NOT NULL DEFAULT 1,
  `userid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert test data
INSERT INTO `jos_users` (`id`, `name`, `username`, `email`, `password`, `usertype`, `block`, `gid`, `registerDate`) VALUES
(1, 'Administrator', 'admin', 'admin@test.com', 'admin', 'Super Administrator', 0, 25, NOW());

INSERT INTO `jos_menu` (`id`, `menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `ordering`, `access`) VALUES
(1, 'mainmenu', 'Home', 'home', 'index.php?option=com_frontpage', 'component', 1, 0, 1, 1, 0);

INSERT INTO `jos_content` (`id`, `title`, `alias`, `introtext`, `fulltext`, `state`, `sectionid`, `catid`, `created`, `created_by`, `publish_up`, `access`, `hits`) VALUES
(1, 'Welcome to Joomla!', 'welcome-to-joomla', 'This is a test article for Joomla 1.0.', 'This is the full text of the test article for Joomla 1.0.', 1, 1, 1, NOW(), 1, NOW(), 0, 0);
