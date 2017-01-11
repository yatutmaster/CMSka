

--
-- Структура таблицы `a_about`
--

CREATE TABLE IF NOT EXISTS `a_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `uname` varchar(30) NOT NULL,
  `about` text NOT NULL,
  `unit` varchar(30) NOT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `name` (`name`),
  KEY `tname` (`uname`),
  KEY `unit` (`unit`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  ;

--
-- Структура таблицы `a_modules`
--

CREATE TABLE IF NOT EXISTS `a_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mname` varchar(30) NOT NULL,
  `tname` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `mname` (`mname`),
  KEY `tname` (`tname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Структура таблицы `a_paths`
--

CREATE TABLE IF NOT EXISTS `a_paths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `url` text NOT NULL,
  `pdesc` text NOT NULL,
  `pkeys` text NOT NULL,
  `module` varchar(30) NOT NULL,
  `level` int(5) NOT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `sort` int(10) NOT NULL,
  `createp` tinyint(1) NOT NULL,
  `changep` tinyint(1) NOT NULL,
  `deletep` tinyint(1) NOT NULL,
  `params` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `template` (`module`),
  KEY `name` (`name`),
  KEY `visible` (`visible`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


--
-- Дамп данных таблицы `a_paths`
--

INSERT INTO `a_paths` (`id`, `pid`, `lft`, `rgt`, `name`, `title`, `url`, `pdesc`, `pkeys`, `module`, `level`, `visible`, `sort`, `createp`, `changep`, `deletep`, `params`) VALUES
(1, 0, 1, 2, 'Главная админки', '', '/', '', '', 'main', 0, 1, 1, 0, 0, 0, 0);


--
-- Структура таблицы `a_tables`
--

CREATE TABLE IF NOT EXISTS `a_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `tname` varchar(30) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `ftype` varchar(30) NOT NULL,
  `fparams` text NOT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `sort` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `name` (`name`),
  KEY `tname` (`tname`),
  KEY `fname` (`fname`),
  KEY `ftype` (`ftype`),
  KEY `fparams` (`fparams`(255)),
  KEY `visible` (`visible`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


--
-- Структура таблицы `a_users`
--

CREATE TABLE IF NOT EXISTS `a_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `email` char(50) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `password` char(50) NOT NULL,
  `theme` char(100) NOT NULL,
  `rank` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `name` (`name`),
  KEY `email` (`email`),
  KEY `password` (`password`),
  KEY `active` (`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Дамп данных таблицы `a_users`
--

INSERT INTO `a_users` (`id`, `name`, `email`, `active`, `password`, `theme`, `rank`) VALUES
(1, 'root', '', 1, 'dbb1c112a931eeb16299d9de1f30161d', 'hot-sneaks', 1),
(2, 'admin', '', 1, 'a66abb5684c45962d887564f08346e8d', 'hot-sneaks', 2);


--
-- Структура таблицы `a_sessions`
--

CREATE TABLE IF NOT EXISTS `a_sessions` (
  `session_id` varchar(24) NOT NULL,
  `last_active` int(10) unsigned NOT NULL,
  `contents` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_active` (`last_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
