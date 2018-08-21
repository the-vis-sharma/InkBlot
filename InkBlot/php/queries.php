CREATE TABLE IF NOT EXISTS `exams` (
  `examId` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `max_marks` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `exp_date` datetime NOT NULL,
  `neg_marks` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `result` (
  `resultId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `examId` int(11) NOT NULL,
  `attempt_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `obtained_marks` decimal(10,2) NOT NULL,
  `is_disqualified` int(11) NOT NULL,
  `result` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` decimal(10,0) DEFAULT NULL,
  `is_first_time` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;