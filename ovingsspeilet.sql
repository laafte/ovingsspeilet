CREATE DATABASE  IF NOT EXISTS `ovingsspeilet`;

USE `ovingsspeilet`;

CREATE TABLE `ovingsspeilet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `contact_name` text,
  `contact_phone` text,
  `title` text,
  `details` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
