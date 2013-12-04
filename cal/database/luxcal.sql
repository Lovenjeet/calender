-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2013 at 10:29 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `luxcal`
--
CREATE DATABASE IF NOT EXISTS `luxcal` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `luxcal`;

-- --------------------------------------------------------

--
-- Table structure for table `mycal_categories`
--

CREATE TABLE IF NOT EXISTS `mycal_categories` (
  `category_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `sequence` int(2) unsigned NOT NULL DEFAULT '1',
  `rpeat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `approve` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `public` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `color` varchar(10) DEFAULT NULL,
  `background` varchar(10) DEFAULT NULL,
  `chbox` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `chlabel` varchar(40) NOT NULL DEFAULT 'approved',
  `chmark` varchar(10) NOT NULL DEFAULT '&#10003;',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `mycal_categories`
--

INSERT INTO `mycal_categories` (`category_id`, `name`, `sequence`, `rpeat`, `approve`, `public`, `color`, `background`, `chbox`, `chlabel`, `chmark`, `status`) VALUES
(1, 'Phase 1', 1, 0, 0, 1, '', '#FFFF77', 0, 'approved', '&#10003;', 0),
(2, 'Phase 2', 1, 0, 1, 1, '', '#BBFFBB', 1, 'approved', '&#10003;', 0),
(3, 'Phase 3', 1, 0, 1, 1, '', '#FFcc77', 0, 'approved', '&#10003;', 0),
(4, 'Phase 4', 1, 0, 1, 1, '', '#CCCCFF', 0, 'approved', '&#10003;', 0),
(5, 'Phase 5', 1, 0, 1, 1, '', '#BBCCdd', 0, 'approved', '&#10003;', 0),
(6, 'Phase 6', 1, 0, 1, 1, '', '#eBcAFF', 0, 'approved', '&#10003;', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mycal_circuit`
--

CREATE TABLE IF NOT EXISTS `mycal_circuit` (
  `CIRCUITID` bigint(4) NOT NULL,
  `CIRCUITNOM` char(32) DEFAULT NULL,
  `CIRCUITDESCRIPTION` char(32) DEFAULT NULL,
  PRIMARY KEY (`CIRCUITID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mycal_circuit`
--

INSERT INTO `mycal_circuit` (`CIRCUITID`, `CIRCUITNOM`, `CIRCUITDESCRIPTION`) VALUES
(1, 'Casa', 'Casa'),
(2, 'Rabat', 'Rabat'),
(3, 'Tanger', 'Tanger'),
(4, 'Fes', 'Fes'),
(5, 'Montreal', 'Montreal'),
(6, 'Autre', 'Autre');

-- --------------------------------------------------------

--
-- Table structure for table `mycal_eleve`
--

CREATE TABLE IF NOT EXISTS `mycal_eleve` (
  `ELEVEID` bigint(4) NOT NULL,
  `ELEVEPRENOM` char(32) DEFAULT NULL,
  `ELEVENOM` char(32) DEFAULT NULL,
  `ELEVETELEPHONE` char(32) DEFAULT NULL,
  PRIMARY KEY (`ELEVEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mycal_eleve`
--

INSERT INTO `mycal_eleve` (`ELEVEID`, `ELEVEPRENOM`, `ELEVENOM`, `ELEVETELEPHONE`) VALUES
(1, 'David', 'Bourassa', '5146884697'),
(2, 'Nancy', 'Agram', '5147895263'),
(3, 'Lili', 'Gary', '5144862234');

-- --------------------------------------------------------

--
-- Table structure for table `mycal_employe`
--

CREATE TABLE IF NOT EXISTS `mycal_employe` (
  `EMPLOYEID` bigint(4) NOT NULL,
  `EMPLOYEPRENOM` char(32) DEFAULT NULL,
  `EMPLOYENOM` char(32) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mycal_employe`
--

INSERT INTO `mycal_employe` (`EMPLOYEID`, `EMPLOYEPRENOM`, `EMPLOYENOM`) VALUES
(1, 'Cameron', 'Cartio'),
(2, 'Bob', 'Danco'),
(3, 'Chady', 'Adis');

-- --------------------------------------------------------

--
-- Table structure for table `mycal_events`
--

CREATE TABLE IF NOT EXISTS `mycal_events` (
  `event_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `event_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) DEFAULT NULL,
  `description` text,
  `employe` varchar(150) DEFAULT NULL,
  `category_id` int(4) unsigned NOT NULL DEFAULT '1',
  `venue` varchar(64) DEFAULT NULL,
  `user_id` int(6) unsigned DEFAULT NULL,
  `editor` varchar(32) NOT NULL DEFAULT '',
  `approved` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `private` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checked` text,
  `s_date` date DEFAULT NULL,
  `e_date` date NOT NULL DEFAULT '9999-00-00',
  `x_dates` text,
  `s_time` time DEFAULT NULL,
  `e_time` time NOT NULL DEFAULT '99:00:00',
  `r_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `r_interval` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `r_period` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `r_month` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `r_until` date NOT NULL DEFAULT '9999-00-00',
  `notify` tinyint(1) NOT NULL DEFAULT '-1',
  `not_mail` varchar(255) DEFAULT NULL,
  `a_date` date NOT NULL DEFAULT '9999-00-00',
  `m_date` date NOT NULL DEFAULT '9999-00-00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `employee_id` int(11) DEFAULT NULL,
  `eleve_id` int(11) DEFAULT NULL,
  `circuit_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

--
-- Dumping data for table `mycal_events`
--

INSERT INTO `mycal_events` (`event_id`, `event_type`, `title`, `description`, `employe`, `category_id`, `venue`, `user_id`, `editor`, `approved`, `private`, `checked`, `s_date`, `e_date`, `x_dates`, `s_time`, `e_time`, `r_type`, `r_interval`, `r_period`, `r_month`, `r_until`, `notify`, `not_mail`, `a_date`, `m_date`, `status`, `employee_id`, `eleve_id`, `circuit_id`) VALUES
(67, 0, 'Gary', '', '', 1, '', 2, 'admin', 0, 0, NULL, '2013-12-04', '9999-00-00', NULL, '14:00:00', '99:00:00', 0, 0, 0, 0, '9999-00-00', -1, 'admin@admin.ca', '2013-12-03', '2013-12-03', 0, 1, 3, 3),
(68, 0, 'Gary', '', '', 3, '', 2, '', 0, 0, NULL, '2013-12-03', '2013-12-18', NULL, '13:00:00', '16:00:00', 0, 0, 0, 0, '9999-00-00', -1, 'admin@admin.ca', '2013-12-04', '9999-00-00', 0, 2, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mycal_settings`
--

CREATE TABLE IF NOT EXISTS `mycal_settings` (
  `name` varchar(15) NOT NULL DEFAULT '',
  `value` text,
  `description` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mycal_settings`
--

INSERT INTO `mycal_settings` (`name`, `value`, `description`) VALUES
('adminCronSum', '1', 'Send cron job summary to admin (0:no, 1:yes)'),
('backLinkUrl', '', 'Nav bar Back button URL (blank: no button, url: Back button)'),
('calendarEmail', 'admin@admin.ca', 'Sender in and receiver of email notifications'),
('calendarTitle', 'My Web Calendar', 'Calendar title displayed in the top bar'),
('calendarUrl', 'http://localhost/luxcal312/luxcal312-calendar/?cal=mycal', 'Calendar link (URL)'),
('catMenu', '1', 'Display category filter menu'),
('chgEmailList', '', 'Recipient email addresses for calendar changes'),
('chgNofDays', '1', 'Number of days to look back for calendar changes'),
('colsToShow', '3', 'Number of months to show per row in year view'),
('cookieExp', '30', 'Number of days before a Remember Me cookie expires'),
('dateFormat', 'd.m.y', 'Date format: yyyy-mm-dd (y:yyyy, m:mm, d:dd)'),
('defaultView', '2', 'Calendar view at start-up (1:year, 2:month, 3:work month, 4:week, 5:work week 6:day, 7:upcoming, 8:changes)'),
('details4All', '1', 'Show event details to all users (0:no, 1:yes)'),
('DMdFormat', 'WD d M', 'Date format: weekday dd month (WD:weekday d:dd, M:month)'),
('DMdyFormat', 'WD d M y', 'Date format: weekday dd month yyyy (WD:weekday d:dd, M:month, y:yyyy)'),
('dwEndHour', '18', 'Day/week view end hour'),
('dwStartHour', '6', 'Day/week view start hour'),
('dwTimeSlot', '30', 'Day/week time slot in minutes'),
('dwTsHeight', '20', 'Day/week time slot height in pixels'),
('eventColor', '1', 'Event colors (0:user color, 1:cat color)'),
('eventExp', '0', 'Number of days after due when an event expires / can be deleted (0:never)'),
('eventHBox', '1', 'Event details hover box (0:no, 1:yes)'),
('icsExport', '0', 'Daily export of events in iCal format (0:no 1:yes)'),
('langMenu', '0', 'Display ui-language selection menu'),
('language', 'English', 'Default user interface language'),
('lookaheadDays', '14', 'Days to look ahead in upcoming view, todo list and RSS feeds'),
('mailServer', '1', 'Mail server (0:mail disabled, 1:PHP mail, 2:SMTP mail)'),
('maxNoLogin', '0', 'Number of days not logged in, before deleting user account (0:never delete)'),
('mCalUrlFull', '', 'Mini calendar link to full calendar'),
('MdFormat', 'd M', 'Date format: dd month (d:dd, M:month)'),
('MdyFormat', 'd M y', 'Date format: dd month yyyy (d:dd, M:month, y:yyyy)'),
('miniCalHBox', '1', 'Mini calendar event hover box (0:no, 1:yes)'),
('miniCalPost', '0', 'Mini calendar event posting (0:no, 1:yes)'),
('miniCalView', '1', 'Mini calendar view (1:full month, 2:work month)'),
('monthInDCell', '0', 'Show in month view month for each day (0:no, 1:yes)'),
('MyFormat', 'M y', 'Date format: month yyyy (M:month, y:yyyy)'),
('navButText', '0', 'Navigation buttons with text or icons (0:icons, 1:text)'),
('navTodoList', '1', 'Display Todo List button in nav bar (0:no, 1:yes)'),
('navUpcoList', '1', 'Display Upco List button in nav bar (0:no, 1:yes)'),
('notifSender', '0', 'Sender of notification emails (0:calendar, 1:user)'),
('privEvents', '1', 'Private events (0:disabled 1:enabled, 2:default, 3:always)'),
('rowsToShow', '4', 'Number of rows to show in year view'),
('rssFeed', '1', 'Display RSS feed links in footer and HTML head (0:no, 1:yes)'),
('selfReg', '0', 'Self-registration (0:no, 1:yes)'),
('selfRegNot', '0', 'User self-reg notification to admin (0:no, 1:yes)'),
('selfRegPrivs', '1', 'Self-reg rights (1:view, 2:post self, 3:post all)'),
('showAdEd', '1', 'Show event owner (0:no, 1:yes)'),
('showCatName', '1', 'Show cat name in various views (0:no, 1:yes)'),
('showLinkInMV', '1', 'Show URL-links in month view (0:no, 1:yes)'),
('showLinkInSB', '1', 'Show URL-links in sidebar (0:no, 1:yes)'),
('sideBarDays', '14', 'Days to look ahead in sidebar'),
('sideBarHBox', '1', 'Sidebar event hover box (0:no, 1:yes)'),
('smtpAuth', '1', 'Use SMTP authentication (0:no, 1:yes)'),
('smtpPass', '', 'SMTP password'),
('smtpPort', '465', 'SMTP port number'),
('smtpServer', '', 'SMTP mail server name'),
('smtpSsl', '1', 'Use SSL (Secure Sockets Layer) (0:no, 1:yes)'),
('smtpUser', '', 'SMTP username'),
('timeFormat', 'h:m', 'Time format (H:hh, h:h, m:mm, a:am|pm, A:AM|PM)'),
('timeZone', 'Europe/Amsterdam', 'Calendar time zone'),
('userMenu', '1', 'Display user filter menu'),
('weekNumber', '1', 'Week numbers on(1) or off(0)'),
('weekStart', '1', 'Week starts on Sunday(0) or Monday(1)'),
('weeksToShow', '10', 'Number of weeks to show in month view'),
('workWeekDays', '12345', 'Days to show in work weeks (1:mo - 7:su)'),
('yearStart', '0', 'Start month in year view (1-12 or 0, 0:current month)');

-- --------------------------------------------------------

--
-- Table structure for table `mycal_users`
--

CREATE TABLE IF NOT EXISTS `mycal_users` (
  `user_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `temp_password` varchar(32) DEFAULT NULL,
  `email` varchar(64) NOT NULL DEFAULT '',
  `privs` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `login_0` date NOT NULL DEFAULT '9999-00-00',
  `login_1` date NOT NULL DEFAULT '9999-00-00',
  `login_cnt` int(8) NOT NULL DEFAULT '0',
  `language` varchar(32) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `mycal_users`
--

INSERT INTO `mycal_users` (`user_id`, `user_name`, `password`, `temp_password`, `email`, `privs`, `login_0`, `login_1`, `login_cnt`, `language`, `color`, `status`) VALUES
(1, 'Public Access', '', NULL, ' ', 1, '9999-00-00', '9999-00-00', 0, NULL, NULL, 0),
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'admin@admin.ca', 9, '2013-11-25', '2013-12-04', 56, 'francais', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `x_sessdata`
--

CREATE TABLE IF NOT EXISTS `x_sessdata` (
  `cal_id` varchar(22) NOT NULL DEFAULT '',
  `sess_id` varchar(32) DEFAULT NULL,
  `value` text,
  `tStamp` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
