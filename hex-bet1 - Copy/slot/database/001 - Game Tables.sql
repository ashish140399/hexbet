CREATE TABLE `luckscript_slots_game_types` (
  `id` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `min_bet` decimal(20,6) NOT NULL DEFAULT '1.00',
  `max_bet` decimal(20,6) NOT NULL DEFAULT '1.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `luckscript_slots_prizes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_type` varchar(50) NOT NULL,
  `reel1` varchar(50) NOT NULL COMMENT 'Number | * (anything) | *.0 (any non-blank) | *.5 (any blank) | Options combined with / (5/2.5/*.5)',
  `reel2` varchar(50) NOT NULL,
  `reel3` varchar(50) NOT NULL,
  `probability` decimal(20,6) NOT NULL,
  `payout_credits` decimal(20,6) NOT NULL,
  `payout_winnings` decimal(20,6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `luckscript_slots_spins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_type` varchar(50) NOT NULL,
  `window_id` varchar(50) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `bet` decimal(20,6) NOT NULL,
  `reel1` decimal(5,1) NOT NULL,
  `reel2` decimal(5,1) NOT NULL,
  `reel3` decimal(5,1) NOT NULL,
  `prize_id` int(11) DEFAULT NULL,
  `payout_credits` decimal(20,6) DEFAULT NULL,
  `payout_winnings` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

ALTER TABLE `luckscript_slots_spins`
ADD INDEX `index_slots_spins_user_id_game_type` (`user_id` ASC, `game_type` ASC);

INSERT INTO `luckscript_slots_game_types` (`id`, `enabled`, `min_bet`, `max_bet`) VALUES ('default', 1, 1, 10);
INSERT INTO `luckscript_slots_prizes` (`game_type`, `reel1`, `reel2`, `reel3`, `probability`, `payout_credits`, `payout_winnings`) VALUES
        ('default', 6, 6, 6, 0.0003, 200, 200),
        ('default', 4, 4, 4, 0.0015, 50, 50),
        ('default', 2, 2, 2, 0.0035, 20, 20),
        ('default', '1/3', '5/2', '4/6', 0.0045, 15, 15),
        ('default', 5, 5, 5, 0.0055, 13, 13),
        ('default', 1, 1, 1, 0.0080, 12, 12),
        ('default', 3, 3, 3, 0.0100, 10, 10),
        ('default', '1/3/5', '1/3/5', '1/3/5', 0.0900, 4, 4);

-- By default, we don't allow reels to fall in blank spaces
--        ('default', '*.5', '*.5', '*.5', 0.0107403, 1, 1);
