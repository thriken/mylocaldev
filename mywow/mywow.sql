-- 导出 mywow 的数据库结构
CREATE DATABASE IF NOT EXISTS `mywow` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `mywow`;


-- 导出  表 mywow.class 结构
CREATE TABLE IF NOT EXISTS `class` (
  `cid` tinyint(4) NOT NULL,
  `cname` varchar(255) NOT NULL COMMENT '职业名称',
  `cname_en` varchar(255) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  mywow.class 的数据：~12 rows (大约)
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
INSERT INTO `class` (`cid`, `cname`, `cname_en`) VALUES
	(1, '战士', 'warrior'),
	(2, '法师', 'mage'),
	(3, '猎人', 'hunter'),
	(4, '潜行者', 'rogue'),
	(5, '牧师', 'priest'),
	(6, '术士', 'warlock'),
	(7, '萨满祭司', 'shaman'),
	(8, '德鲁伊', 'druid'),
	(9, '圣骑士', 'paladin'),
	(10, '死亡骑士', 'dk'),
	(11, '武僧', 'monk'),
	(12, '恶魔猎手', 'dh');
/*!40000 ALTER TABLE `class` ENABLE KEYS */;


-- 导出  表 mywow.hero 结构
CREATE TABLE IF NOT EXISTS `hero` (
  `hid` tinyint(6) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `hname` varchar(12) NOT NULL COMMENT '角色名',
  `rid` int(11) NOT NULL COMMENT '种族ID',
  `cid` int(11) NOT NULL COMMENT '职业id',
  `pid1` int(11) DEFAULT NULL,
  `p1` tinyint(3) DEFAULT NULL,
  `pid2` int(11) DEFAULT NULL,
  `p2` tinyint(4) DEFAULT NULL,
  `pp1` tinyint(4) NOT NULL DEFAULT '0',
  `pp2` tinyint(4) NOT NULL DEFAULT '0',
  `pp3` tinyint(4) NOT NULL DEFAULT '0',
  `pp4` tinyint(4) NOT NULL DEFAULT '0',
  `pp5` tinyint(4) NOT NULL DEFAULT '0',
  `pp6` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  mywow.hero 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `hero` DISABLE KEYS */;
/*!40000 ALTER TABLE `hero` ENABLE KEYS */;


-- 导出  表 mywow.profession 结构
CREATE TABLE IF NOT EXISTS `profession` (
  `pid` tinyint(4) NOT NULL,
  `profession` varchar(16) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  mywow.profession 的数据：~13 rows (大约)
/*!40000 ALTER TABLE `profession` DISABLE KEYS */;
INSERT INTO `profession` (`pid`, `profession`) VALUES
	(1, '附魔'),
	(2, '珠宝加工'),
	(3, '铭文'),
	(4, '工程'),
	(5, '锻造'),
	(6, '制皮'),
	(7, '裁缝'),
	(8, '炼金'),
	(9, '侏儒工程学'),
	(10, '地精工程学'),
	(11, '草药学'),
	(12, '剥皮'),
	(13, '采矿');
/*!40000 ALTER TABLE `profession` ENABLE KEYS */;


-- 导出  表 mywow.race 结构
CREATE TABLE IF NOT EXISTS `race` (
  `rid` tinyint(4) NOT NULL,
  `rname` varchar(255) NOT NULL COMMENT '种族名称',
  `faction` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,部落,1,联盟',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  mywow.race 的数据：~14 rows (大约)
/*!40000 ALTER TABLE `race` DISABLE KEYS */;
INSERT INTO `race` (`rid`, `rname`, `faction`) VALUES
	(1, '兽人', 0),
	(2, '牛头人', 0),
	(3, '巨魔', 0),
	(4, '亡灵', 0),
	(5, '血精灵', 0),
	(6, '地精', 0),
	(7, '熊猫人(部落)', 0),
	(8, '矮人', 1),
	(9, '侏儒', 1),
	(10, '人类', 1),
	(11, '暗夜精灵', 1),
	(12, '德莱尼', 1),
	(13, '狼人', 1),
	(14, '熊猫人(联盟)', 1);
/*!40000 ALTER TABLE `race` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
