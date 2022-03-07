CREATE TABLE `logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
    `log` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;