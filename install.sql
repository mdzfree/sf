SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sf_memory`
-- ----------------------------
DROP TABLE IF EXISTS `sf_memory`;
CREATE TABLE `sf_memory` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `area` varchar(20) NOT NULL DEFAULT '0' COMMENT '数据区',
  `key` varchar(200) NOT NULL COMMENT '键名',
  `value` text NOT NULL COMMENT '键值，对象数据存储用序列化或数组JSON',
  `effective` int(11) NOT NULL COMMENT '有效时间戳',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `area` (`area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='记忆表，用于临时存储数据，类似内存缓存';


-- ----------------------------
-- Table structure for `sf_queue`
-- ----------------------------
DROP TABLE IF EXISTS `sf_queue`;
CREATE TABLE `sf_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `exchange` varchar(50) NOT NULL DEFAULT 'default' COMMENT '交换机名称',
  `routing_key` varchar(50) NOT NULL DEFAULT '' COMMENT '路由key',
  `props` varchar(500) NOT NULL DEFAULT '' COMMENT '消息属性字段',
  `body` text NOT NULL COMMENT '消息体',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消息状态，0为未消费，1为已消费',
  `session_id` varchar(50) NOT NULL COMMENT '消费会话编号',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='消息队列表';

-- ----------------------------
-- Records of sf_queue
-- ----------------------------

-- ----------------------------
-- Table structure for `sf_queue_log`
-- ----------------------------
DROP TABLE IF EXISTS `sf_queue_log`;
CREATE TABLE `sf_queue_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `exchange` varchar(50) NOT NULL DEFAULT 'default' COMMENT '交换机名称',
  `routing_key` varchar(50) NOT NULL DEFAULT '' COMMENT '路由key',
  `props` varchar(500) NOT NULL DEFAULT '' COMMENT '消息属性字段',
  `body` text NOT NULL COMMENT '消息体',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消息状态，0为未消费，1为已消费',
  `session_id` varchar(50) NOT NULL COMMENT '消费会话编号',
  `log` varchar(500) NOT NULL DEFAULT '' COMMENT '消息日志',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='消息队列表';

-- ----------------------------
-- Records of sf_queue_log
-- ----------------------------
