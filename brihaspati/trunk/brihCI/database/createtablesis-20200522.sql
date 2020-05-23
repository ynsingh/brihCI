--
-- Table structure for table `employee_ottsd`
--

CREATE TABLE `employee_ottsd` (
  `empottsd_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `empottsd_empid` int(11)  NOT NULL,
  `empottsd_datefrom` datetime NOT NULL,
  `empottsd_dateto` datetime NOT NULL,
  `empottsd_post` varchar(255) Default NULL,
  `empottsd_estd` varchar(255) Default NULL,
  `empottsd_uso` varchar(255) Default NULL,
  `empottsd_creatorid` varchar(255) NOT NULL,
  `empottsd_creatordate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `empottsd_modifierid` varchar(255) NOT NULL,
  `empottsd_modifydate` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------------------------------
