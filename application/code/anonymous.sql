CREATE TABLE IF NOT EXISTS `anonymous` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `summary` text,
  `url` text,
  `outlet` varchar(255) DEFAULT NULL,
  `date_published` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `display` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_outlet` (`outlet`)
);
