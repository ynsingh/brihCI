ALTER TABLE `staff_retirement` ADD `sre_status` VARCHAR(250) NULL AFTER `sre_reasondate`;
--
-- Table structure for table `employee_rejoin`
--

CREATE TABLE `employee_rejoin` (
  `emprj_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `emprj_empid` int(11)  NOT NULL,
  `emprj_doj` datetime NOT NULL,
  `emprj_leavedatefrom` datetime NOT NULL,
  `emprj_leavedateto` datetime NOT NULL,
  `emprj_reason` varchar(255) Default NULL,
  `emprj_rejoinreason` varchar(255) Default NULL,
  `emprj_remark` varchar(255) Default NULL,
  `emprj_creatorid` varchar(255) NOT NULL,
  `emprj_creatordate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `emprj_modifierid` varchar(255) NOT NULL,
  `emprj_modifydate` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------------------------------
