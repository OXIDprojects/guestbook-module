CREATE TABLE IF NOT EXISTS `oeguestbookentry` (
  `OXID` char(32) NOT NULL COMMENT 'Entry id',
  `OXSHOPID` char(32) NOT NULL default '' COMMENT 'Shop id (oxshops)',
  `OXUSERID` char(32) NOT NULL DEFAULT '' COMMENT 'User id (oxuser)',
  `OXCONTENT` text NOT NULL COMMENT 'Content',
  `OXCREATE` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Creation time',
  `OXACTIVE` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is active',
  `OXVIEWED` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the entry was checked by admin',
  `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
  PRIMARY KEY (`OXID`),
  KEY (`OXUSERID`)
) ENGINE=InnoDB COMMENT='Guestbook`s entries' DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;;

INSERT INTO `oxseo` (`OXOBJECTID`, `OXIDENT`, `OXSHOPID`, `OXLANG`, `OXSTDURL`, `OXSEOURL`, `OXTYPE`, `OXFIXED`, `OXEXPIRED`, `OXPARAMS`) VALUES
('d12b7badee1037e7c1a5a7a245a14e11', '7c8aa72aa16b7d4a859b22d8b8328412', 1, 0, 'index.php?cl=oeguestbookguestbook', 'gaestebuch/', 'static', 0, 0, '')
ON DUPLICATE KEY UPDATE `OXSTDURL` = 'index.php?cl=oeguestbookguestbook';

INSERT INTO `oxseo` (`OXOBJECTID`, `OXIDENT`, `OXSHOPID`, `OXLANG`, `OXSTDURL`, `OXSEOURL`, `OXTYPE`, `OXFIXED`, `OXEXPIRED`, `OXPARAMS`) VALUES
('d12b7badee1037e7c1a5a7a245a14e11', 'ded4f3786c6f4d6d14e3034834ebdcaf', 1, 1, 'index.php?cl=oeguestbookguestbook', 'en/guestbook/', 'static', 0, 0, '')
ON DUPLICATE KEY UPDATE `OXSTDURL` = 'index.php?cl=oeguestbookguestbook';