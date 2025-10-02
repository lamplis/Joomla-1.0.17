-- Initialize Joomla 1.0 database schema
-- This creates the basic tables needed for Joomla 1.0

CREATE DATABASE IF NOT EXISTS joomla_test;
USE joomla_test;

-- Core Joomla 1.0 tables
CREATE TABLE IF NOT EXISTS `jos_components` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `menuid` int(11) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  `admin_menu_link` varchar(255) NOT NULL default '',
  `admin_menu_alt` varchar(255) NOT NULL default '',
  `option` varchar(50) NOT NULL default '',
  `ordering` int(11) NOT NULL default '0',
  `admin_menu_img` varchar(255) NOT NULL default '',
  `iscore` tinyint(4) NOT NULL default '0',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_content` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `title_alias` varchar(255) NOT NULL default '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL default '0',
  `sectionid` int(11) NOT NULL default '0',
  `mask` int(11) NOT NULL default '0',
  `catid` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL default '0',
  `created_by_alias` varchar(255) NOT NULL default '',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL default '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` text NOT NULL,
  `version` int(11) NOT NULL default '1',
  `parentid` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(11) NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `metadata` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_menu` (
  `id` int(11) NOT NULL auto_increment,
  `menutype` varchar(75) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `type` varchar(20) NOT NULL default '',
  `published` tinyint(1) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  `componentid` int(11) NOT NULL default '0',
  `sublevel` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pollid` int(11) NOT NULL default '0',
  `browserNav` tinyint(4) NOT NULL default '0',
  `access` int(11) NOT NULL default '0',
  `utaccess` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `lft` int(11) NOT NULL default '0',
  `rgt` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_componentid` (`componentid`),
  KEY `idx_menutype` (`menutype`),
  KEY `idx_parent` (`parent`),
  KEY `idx_published` (`published`),
  KEY `idx_access` (`access`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `username` varchar(150) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `usertype` varchar(25) NOT NULL default '',
  `block` tinyint(4) NOT NULL default '0',
  `sendEmail` tinyint(4) default '0',
  `gid` tinyint(3) NOT NULL default '1',
  `registerDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL default '',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_usertype` (`usertype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_sessions` (
  `username` varchar(50) NOT NULL default '',
  `time` varchar(14) NOT NULL default '',
  `session_id` varchar(200) NOT NULL default '',
  `guest` tinyint(4) NOT NULL default '1',
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Insert test data
INSERT INTO `jos_users` (`id`, `name`, `username`, `email`, `password`, `usertype`, `block`, `gid`, `registerDate`) VALUES
(1, 'Administrator', 'admin', 'admin@test.com', 'admin', 'Super Administrator', 0, 25, NOW());

INSERT INTO `jos_menu` (`id`, `menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `ordering`, `access`) VALUES
(1, 'mainmenu', 'Home', 'home', 'index.php?option=com_frontpage', 'component', 1, 0, 1, 1, 0);

INSERT INTO `jos_content` (`id`, `title`, `alias`, `introtext`, `fulltext`, `state`, `sectionid`, `catid`, `created`, `created_by`, `publish_up`, `access`, `hits`) VALUES
(1, 'Welcome to Joomla!', 'welcome-to-joomla', 'This is a test article for Joomla 1.0.', 'This is the full text of the test article for Joomla 1.0.', 1, 1, 1, NOW(), 1, NOW(), 0, 0);
