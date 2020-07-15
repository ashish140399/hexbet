CREATE TABLE IF NOT EXISTS  `luckscript_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` varchar(50) NOT NULL,
  `balance` decimal(20,6) NOT NULL DEFAULT '0.00',
  `created` datetime DEFAULT NULL,
  `last_request` datetime DEFAULT NULL,
  `ip_address` varchar(50) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- If you have your own users table, run this query:
-- ALTER TABLE {{your_table_name}} ADD balance decimal(20,6) DEFAULT 0 NULL;
