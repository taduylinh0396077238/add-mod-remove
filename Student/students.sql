CREATE  TABLE IF NOT EXISTS `students` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `address` varchar(255) NOT NULL,
    `class` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)

    )ENGINE = InnoDB DEFAULT CHARSET = latin1 AUTO_INCREMENT = 4;


INSERT INTO `students` (`id`, `name`, `address`, `class`) values
                                                               (1, 'Roland Mendel ', 'C/Araquil, 67, Madrid', 'T2109M'),
                                                               (2, 'Victoria Ashworth', '35 King George , London', 'T2100A'),
                                                               (3, 'Martin Blank ', '25, Rue Lauriston, Paris', 'T3939M');