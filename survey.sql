#
# Structure for table "answer"
#

CREATE TABLE `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionnaire_id` int(11) NOT NULL DEFAULT '0' COMMENT '问卷id',
  `question_id` int(11) NOT NULL DEFAULT '0' COMMENT '题目id',
  `option_index` varchar(5) NOT NULL DEFAULT '' COMMENT '选项索引',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT '作答者ip',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '作答时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='问卷回答表';

#
# Data for table "answer"
#

INSERT INTO `answer` VALUES (16,14,3,'A','127.0.0.1',1551844022),(17,14,4,'A','127.0.0.1',1551844022),(18,14,4,'B','127.0.0.1',1551844022),(19,14,3,'A','127.0.0.1',1551844052),(20,14,4,'B','127.0.0.1',1551844052),(21,14,4,'C','127.0.0.1',1551844052),(22,14,3,'A','127.0.0.1',1551844060),(23,14,4,'A','127.0.0.1',1551844060),(24,14,4,'B','127.0.0.1',1551844060),(25,14,3,'B','127.0.0.1',1551844386),(26,14,4,'C','127.0.0.1',1551844386),(27,14,3,'A','127.0.0.1',1551849304),(28,14,4,'A','127.0.0.1',1551849304),(29,14,4,'B','127.0.0.1',1551849304);

#
# Structure for table "option"
#

CREATE TABLE `option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属题目id',
  `option_index` varchar(5) NOT NULL DEFAULT '' COMMENT '选项索引(A,B,C,D.....)',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间(时间戳)',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='题目选项表';

#
# Data for table "option"
#

INSERT INTO `option` VALUES (6,3,'A','是',1551771096,0),(7,3,'B','否',1551771096,0),(8,4,'A','新房',1551771096,0),(9,4,'B','二手房',1551771096,0),(10,4,'C','均可',1551771096,0);

#
# Structure for table "question"
#

CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionnaire_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属问卷id',
  `content` text NOT NULL COMMENT '题目内容',
  `question_type` int(11) NOT NULL DEFAULT '0' COMMENT '题型id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间(时间戳)',
  `delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='问卷题目表';

#
# Data for table "question"
#

INSERT INTO `question` VALUES (3,14,'请问您未来2年是否打算在本地买房呢？',1,1551771096,0),(4,14,'请问您未来2年内打算购买新房还是二手房？',2,1551771096,0);

#
# Structure for table "question_type"
#

CREATE TABLE `question_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '题型名称',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='题型表';

#
# Data for table "question_type"
#

INSERT INTO `question_type` VALUES (1,'单选题',0),(2,'多选题',0);

#
# Structure for table "questionnaire"
#

CREATE TABLE `questionnaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '问卷标题',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建者id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '问卷状态 0:已结束 1:进行中',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间(时间戳)',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间(时间戳)',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  `answer_count` int(11) NOT NULL DEFAULT '0' COMMENT '问卷作答次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='问卷表';

#
# Data for table "questionnaire"
#

INSERT INTO `questionnaire` VALUES (14,'购房需求调查问卷',1,1,1551771096,0,0,5);

#
# Structure for table "user"
#

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码(md5加密)',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户登录表';

#
# Data for table "user"
#

INSERT INTO `user` VALUES (1,'admin','e10adc3949ba59abbe56e057f20f883e',1551678505);
