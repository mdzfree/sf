/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : sf

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-12-03 14:29:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sf_entity`
-- ----------------------------
DROP TABLE IF EXISTS `sf_entity`;
CREATE TABLE `sf_entity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL COMMENT '实体类型',
  `type_id` int(11) NOT NULL COMMENT '类型外键编号',
  `data` text NOT NULL COMMENT '数据集',
  `field1` varchar(200) NOT NULL DEFAULT '' COMMENT '附加字段1',
  `field2` varchar(200) NOT NULL DEFAULT '' COMMENT '附加字段2',
  `field3` varchar(200) NOT NULL DEFAULT '' COMMENT '附加字段3',
  `field4` varchar(200) NOT NULL DEFAULT '' COMMENT '附加字段4',
  `field5` varchar(200) NOT NULL DEFAULT '' COMMENT '附加字段5',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7716224 DEFAULT CHARSET=utf8mb4 COMMENT='实体表，主要为其他数据表提供拓展数据';

-- ----------------------------
-- Records of sf_entity
-- ----------------------------
INSERT INTO `sf_entity` VALUES ('7716211', 'list', '10', '<div id=\"wrapper\" class=\"wrapper_l\">\r\n	<div id=\"head\">\r\n		<div class=\"head_wrapper\">\r\n			<div class=\"s_form\">\r\n				<div class=\"s_form_wrapper soutu-env-nomac soutu-env-result\">\r\n					<a href=\"https://www.baidu.com/\" id=\"result_logo\"><img class=\"index-logo-src\" src=\"https://www.baidu.com/img/baidu_jgylogo3.gif\" alt=\"到百度首页\" title=\"到百度首页\" /></a><span class=\"bg s_ipt_wr quickdelete-wrap\"><span class=\"soutu-btn\"></span></span><span class=\"bg s_btn_wr\"></span> \r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n</div>\r\n<div id=\"wrapper\" class=\"wrapper_l\">\r\n	<div id=\"head\">\r\n		<div class=\"head_wrapper\">\r\n			<div class=\"s_form\">\r\n				<div class=\"s_form_wrapper soutu-env-nomac soutu-env-result\">\r\n					<span class=\"tools\"></span> \r\n				</div>\r\n			</div>\r\n			<div id=\"u\">\r\n				<a class=\"toindex\" href=\"https://www.baidu.com/\">百度首页</a><a name=\"tj_settingicon\"></a>设置<a href=\"https://passport.baidu.com/v2/?login&tpl=mn&u=http%3A%2F%2Fwww.baidu.com%2F\" name=\"tj_login\" class=\"lb\">登录</a> \r\n			</div>\r\n		</div>\r\n	</div>\r\n	<div class=\"s_tab\" id=\"s_tab\">\r\n		<div class=\"s_tab_inner\">\r\n			<b>网页</b><a href=\"https://www.baidu.com/s?rtt=1&bsst=1&cl=2&tn=news&word=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82\">资讯</a><a href=\"https://www.baidu.com/sf/vsearch?pd=video&tn=vsearch&lid=87017838000262e3&ie=utf-8&wd=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_spt=7&rsv_bp=1&f=8&oq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">视频</a><a href=\"http://image.baidu.com/i?tn=baiduimage&ps=1&ct=201326592&lm=-1&cl=2&nc=1&ie=utf-8&word=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82\">图片</a><a href=\"http://zhidao.baidu.com/q?ct=17&pn=0&tn=ikaslist&rn=10&fr=wwwt&word=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82\">知道</a><a href=\"http://wenku.baidu.com/search?lm=0&od=0&ie=utf-8&word=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82\">文库</a><a href=\"http://tieba.baidu.com/f?fr=wwwt&kw=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82\">贴吧</a><a href=\"https://b2b.baidu.com/s?fr=wwwt&q=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82\">采购</a><a href=\"http://map.baidu.com/m?fr=ps01000&word=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82\">地图</a><a href=\"http://www.baidu.com/more/\">更多»</a> \r\n		</div>\r\n	</div>\r\n	<div id=\"wrapper_wrapper\">\r\n		<div id=\"container\" class=\"container_l\">\r\n			<div id=\"content_right\" class=\"cr-offset\">\r\n				<table cellspacing=\"0\" cellpadding=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td align=\"left\">\r\n								<div id=\"con-ar\" class=\"result_hidden\">\r\n									<div class=\"result-op xpath-log\">\r\n										<div class=\"cr-content \">\r\n											<div class=\"FYB_RD\">\r\n												<div class=\"cr-title opr-toplist1-title\">\r\n													<div class=\"opr-toplist1-update\">\r\n														<a class=\"OP_LOG_BTN opr-toplist1-refresh\">换一换</a> \r\n													</div>\r\n搜索热点\r\n												</div>\r\n												<table class=\"c-table opr-toplist1-table\">\r\n													<tbody>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-index-hot1 c-gap-icon-right-small\">1</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E5%85%A8%E7%90%83%E6%9C%80%E5%A4%A7%E9%80%A0%E8%88%B9%E9%9B%86%E5%9B%A2&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_1&rqid=87017838000262e3\">全球最大造船集团</a></span><span class=\"c-text c-text-danger c-gap-icon-left-small opr-toplist1-new\">新</span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																511万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-index-hot2 c-gap-icon-right-small\">2</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E4%BF%84%E8%88%B0%E9%98%9F%E5%9C%B0%E4%B8%AD%E6%B5%B7%E6%BC%94%E4%B9%A0&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_2&rqid=87017838000262e3\">俄舰队地中海演习</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																436万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-index-hot3 c-gap-icon-right-small\">3</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E5%85%B7%E8%8D%B7%E6%8B%89%E5%AE%B6%E4%B8%AD%E8%BA%AB%E4%BA%A1&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_3&rqid=87017838000262e3\">具荷拉家中身亡</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																430万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">4</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E7%BD%91%E6%98%93%E5%90%91%E5%91%98%E5%B7%A5%E8%87%B4%E6%AD%89&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_4&rqid=87017838000262e3\">网易向员工致歉</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																430万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">5</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E4%BA%BA%E6%B0%91%E6%97%A5%E6%8A%A5%E8%AF%84%E5%BC%A0%E4%BA%91%E9%9B%B7&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_5&rqid=87017838000262e3\">人民日报评张云雷</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																426万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">6</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E7%8E%8B%E6%80%9D%E8%81%AA%E5%85%AC%E5%8F%B8%E6%96%B0%E7%94%B5%E5%BD%B1&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_6&rqid=87017838000262e3\">王思聪公司新电影</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																422万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">7</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E7%B4%AB%E5%85%89%E9%98%81%E6%80%92%E6%89%B9%E5%BC%A0%E4%BA%91%E9%9B%B7&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_7&rqid=87017838000262e3\">紫光阁怒批张云雷</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																412万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">8</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E9%82%B1%E6%B7%91%E8%B4%9E%E5%A5%B3%E5%84%BF%E5%B0%81%E9%9D%A2&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_8&rqid=87017838000262e3\">邱淑贞女儿封面</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																395万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">9</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E5%A4%A7%E7%90%86%E6%B4%B1%E6%BA%90%E5%8F%91%E7%94%9F%E5%9C%B0%E9%9C%87&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_9&rqid=87017838000262e3\">大理洱源发生地震</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																327万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">10</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E5%8D%B7%E8%B5%B010%E4%BA%BF%E6%8B%A523%E5%A5%97%E6%88%BF&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_10&rqid=87017838000262e3\">卷走10亿拥23套房</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																282万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">11</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E5%B4%94%E5%A7%8B%E6%BA%90%E9%81%93%E6%AD%89&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_11&rqid=87017838000262e3\">崔始源道歉</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																281万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">12</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E9%9B%B6%E4%B8%8B40%E5%BA%A6%E4%B8%8D%E7%BB%93%E5%86%B0&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_12&rqid=87017838000262e3\">零下40度不结冰</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																264万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">13</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E7%94%B7%E6%80%A7%E4%BF%9D%E6%8A%A4%E4%BB%A4&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_13&rqid=87017838000262e3\">男性保护令</a></span><span class=\"c-text c-text-danger c-gap-icon-left-small opr-toplist1-new\">新</span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																258万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">14</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=11%E5%B2%81%E7%94%B7%E5%AD%A9%E8%A2%AB%E7%88%B6%E6%9D%80%E5%AE%B3&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_14&rqid=87017838000262e3\">11岁男孩被父杀害</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																235万\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td>\r\n																<span><span class=\"c-index c-gap-icon-right-small\">15</span><a target=\"_blank\" href=\"https://www.baidu.com/s?wd=%E8%B6%8A%E5%8D%97%E9%9E%8B%E5%8E%82%E7%99%BE%E4%BA%BA%E4%B8%AD%E6%AF%92&tn=monline_7_dg&ie=utf-8&rsv_cq=soulTable+%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&rsv_dl=0_right_fyb_pchot_20811_01&rsf=60cf158d5de2df0f90c2a21dce6b1803_1_15_15&rqid=87017838000262e3\">越南鞋厂百人中毒</a></span> \r\n															</td>\r\n															<td class=\"opr-toplist1-right\">\r\n																230万\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n												<div class=\"OP_LOG_BTN c-gap-top-small opr-toplist1-from\">\r\n													<a target=\"_blank\" href=\"http://www.baidu.com/link?url=1LcA7xBSlB-zcC2jM_rtqxE-xwgZFNaoTJ03ujjhBmeiaoewFYFnXJKRxD0W_GJH\">查看更多&gt;&gt;</a> \r\n												</div>\r\n											</div>\r\n										</div>\r\n									</div>\r\n								</div>\r\n								<div>\r\n								</div>\r\n							</td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n			</div>\r\n			<div class=\"head_nums_cont_outer OP_LOG\">\r\n				<div class=\"head_nums_cont_inner\">\r\n					<div class=\"search_tool_conter\">\r\n						<span class=\"c-gap-left-samll search_tool_close\">收起工具</span><span class=\"search_tool_tf \">时间不限</span><span class=\"search_tool_ft c-gap-left\">所有网页和文件</span><span class=\"search_tool_si c-gap-left\">站点内检索</span> \r\n					</div>\r\n					<div class=\"nums\">\r\n						<div class=\"search_tool\">\r\n							搜索工具\r\n						</div>\r\n<span class=\"nums_text\">百度为您找到相关结果约72个</span> \r\n					</div>\r\n				</div>\r\n			</div>\r\n			<div id=\"content_left\">\r\n				<div class=\"result c-container \" id=\"1\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=pdJQxokyjvwONqJH28-Al1I5l_trypbWN89w2PvFa96-zTjxq80n-O8sQMMmz1KYB-tPnHIjkdwUquZ97LjXJsJzR3G8obBjdznFZ1YpKwq\" target=\"_blank\">layui-<em>soul</em>-<em>table</em>插件的使用详解 - yms的博客 - CSDN博客</a> \r\n					</h3>\r\n					<div class=\"c-abstract\">\r\n						<span class=\" newTimeFactor_before_abs m\">2019年9月20日&nbsp;- </span>[\'form\', \'table\',\'<em>soulTable</em>\'], function () ...参数转一下,放到body里面去,poster并不会<em>自动</em>将para...3.51.0.js的用法就是可以发送异步的ajaxForm<em>请</em>...\r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=pdJQxokyjvwONqJH28-Al1I5l_trypbWN89w2PvFa96-zTjxq80n-O8sQMMmz1KYB-tPnHIjkdwUquZ97LjXJsJzR3G8obBjdznFZ1YpKwq\" class=\"c-showurl\"><span><img class=\"source-icon\" src=\"https://cambrian-images.cdn.bcebos.com/ea7e0c7af4673ed4cd13dc1c2b27c1eb_1562913917952.jpeg@w_100,h_100\" />CSDN技术社区</span></a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9d78d513d9821ce806fa950e4b4587384e4380122ba1d50209d2843897732835506793ac56270773a3d20d6216db4848adb0687d6d4566f5dadf8939c0a6c27672dd3034074dc7174ec419d8971163dc63d34deedb1fe7b8fb3798acd6ce8c141591025b2d9da6dc1c534f942eed1733e0a49d42175e14&p=9d49c54ad6c008fc57ef8a224e54&newp=8b2a971acadd1efd04bd9b7e0e7a92695d0fc20e38d6db01298ffe0cc4241a1a1a3aecbf22261300d9c57f640aaf4956e1f331763d0034f1f689df08d2ecce7e7094396b2240&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=1\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n				<div class=\"result c-container \" id=\"2\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=vIzZlPf71sWVOzWGePBU9y1nIaGA-2NK311u43d_rqS09LcGhcjOy6NVPhFH6fbP\" target=\"_blank\">使用<em>soultable</em>报错,但我数据都获取到了,求救!!! - Fly社区</a> \r\n					</h3>\r\n					<div class=\"bbs f13\">\r\n						3条回复&nbsp;-&nbsp;发帖时间:&nbsp;2019年10月14日\r\n					</div>\r\n					<div class=\"c-abstract\">\r\n						使用<em>soultable</em>报错,但我数据都获取到了,求救!!!提问 未结 4 67 Cheung_KingWai VIP4 2019-10-14  悬赏:100飞吻 版本:扩展组件 浏览器:谷歌 回帖...\r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=vIzZlPf71sWVOzWGePBU9y1nIaGA-2NK311u43d_rqS09LcGhcjOy6NVPhFH6fbP\" class=\"c-showurl\">https://fly.layui.com/jie/586... </a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9d78d513d9821ce806fa950e4b4587384e4380122ba1d50209d2843897732835506793ac56270773a3d20d6216db4848adb0687d6d4566f5dadf8939c0a6c676649f27432e4fcd0649c419d89a1b7adc78cb0df4a813e3bdfb2f&p=9f3fc54ad7c009e910f5c7710f53&newp=84728416d9c11bf303bd9b7f0e0092695d0fc20e3bd3db01298ffe0cc4241a1a1a3aecbf22261300d9c57f640aaf4956e1f331763d0034f1f689df08d2ecce7e75d47c&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=2\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n				<div class=\"result c-container \" id=\"3\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=c1MUfPx4CcXPE3FrhkXxJJfFyuUIY9vcHuWTjRjcym_wEZkO_Fok6W9r43qfRwYORucyRPzqqh9aYStuS7rtEq\" target=\"_blank\"><em>soulTable</em> 表格筛选、列拖动、子表、excel导出 <em>soulTable</em> - layui...</a> \r\n					</h3>\r\n					<div class=\"c-abstract\">\r\n						功能点: 1. 表头筛选、自定义条件(支持前台筛选、后台筛选[mysql|oracle]) 2. 拖动列调整顺序、隐藏显示列 3. excel导出(根据筛选条件和列顺序导出) 4. 子表...\r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=c1MUfPx4CcXPE3FrhkXxJJfFyuUIY9vcHuWTjRjcym_wEZkO_Fok6W9r43qfRwYORucyRPzqqh9aYStuS7rtEq\" class=\"c-showurl\">https://fly.layui.com/extend/<b>s</b>... </a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9d78d513d9821ce806fa950e4b4587384e4380122ba1d50209d2843897732835506793ac56270773a3d20d6216db4848adb0687d6d4566f5dadf8939c0a6c676649f27432e4fcd0649c419d89a1b7adc77da1cbef34ffafbad75cdc8818381034e&p=9f3fc54ad5c341e71ea58c2d021492&newp=84728416d9c11bf303bd9b7d0d16c1231610db2151d0d001298ffe0cc4241a1a1a3aecbf2226100ed3c17c6c0ba84a58edf534703d0034f1f689df08d2ecce7e75d47c&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=3\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n				<div class=\"result c-container \" id=\"4\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=tLs0qNtqewro7d6aO3KWUpNiXOZFve9LY415k6bUrIwMm_hpXM4XIYLSzaSn9hxp\" target=\"_blank\">示例文档 | layui-<em>soul</em>-<em>table</em></a> \r\n					</h3>\r\n					<div class=\"c-abstract\">\r\n						layui-<em>soul</em>-<em>table</em> 为layui <em>table</em> 扩展的 表头筛选, 表格筛选, 子表, 父子表, 列拖拽, excel导出\r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=tLs0qNtqewro7d6aO3KWUpNiXOZFve9LY415k6bUrIwMm_hpXM4XIYLSzaSn9hxp\" class=\"c-showurl\">https://<b>soultable</b>.yelog.org/ </a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9f65cb4a8c8507ed19fa950d100b963b5e0ac6306c8987027fa3d81fcd390e564711befb723f04418e852a6840f20e02fdf1463464537ee08cc8f95dabbe855e299f5730676ff25613a30edece5152b137e15ffedb18&p=9949831e8e934eac58eace2d021482&newp=8b2a9701a49b11a05bed9321515f85231610db2151d1d701298ffe0cc4241a1a1a3aecbf2226100ed3c17c6c0ba84a58edf534703d0034f1f689df08d2ecce7e6bfa7f&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=4\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n				<div class=\"result c-container \" id=\"5\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=ws6cxNIhWmVA1gbrUgFMRtACQhCMdYvcwZtLWOZfWZ1KI36OgwH-RhOaom-_mNDGD9TBOyYTYIs3TtSiN_-Jzgfm_lcRXf2c9MO6Y9y-02u\" target=\"_blank\">对<em>Soul</em> 安卓App的一次 api<em>请求</em> 抓取记录 - weixin_3434..._CSDN博客</a> \r\n					</h3>\r\n					<div class=\"c-abstract\">\r\n						<span class=\" newTimeFactor_before_abs m\">2018年9月18日&nbsp;- </span>2 - 打开Fillder,对<em>soul</em> 的数据<em>请求</em>api进行跟踪,抓到下面的<em>请求</em>: 1. <em>请求</em>...第二篇:淘宝<em>自动</em>登录2.0,新增Cookies序列化,教大... 博文 来自: 猪哥  七...\r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=ws6cxNIhWmVA1gbrUgFMRtACQhCMdYvcwZtLWOZfWZ1KI36OgwH-RhOaom-_mNDGD9TBOyYTYIs3TtSiN_-Jzgfm_lcRXf2c9MO6Y9y-02u\" class=\"c-showurl\"><span><img class=\"source-icon\" src=\"https://cambrian-images.cdn.bcebos.com/ea7e0c7af4673ed4cd13dc1c2b27c1eb_1562913917952.jpeg@w_100,h_100\" />CSDN技术社区</span></a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9d78d513d9821ce806fa950e4b4587384e4380122ba1d50209d2843897732835506793ac56270773a3d20d6216db4848adb0687d6d4566f5dadf8939c0a6c27672dd3034074dc7174ec419d8971163dc65c701a3f445f0bd843395afd4d3df5656d700453cdba1d50d1d429d29a34f6fa2bb9342105811b1ee3a&p=8b2a9718c5db11a05bed9e0c530080&newp=9c618f16d9c116ff57ee9474450a8b231610db2151d4d11f6b82c825d7331b001c3bbfb423271404d9c578670aa54e5feef73370350923a3dda5c91d9fb4c57479df&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=5\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n				<div class=\"result c-container \" id=\"6\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=qngGxhJEGQujwJEaqxVBNnQ-2WxT0bx3BaDy4vU76EQhEKFiMjzDi_Xzvl_RPKBo\" target=\"_blank\">layui-<em>soul</em>-<em>table</em>:给 layui-<em>table</em> 注入点灵魂 | 码农网</a> \r\n					</h3>\r\n					<div class=\"c-abstract\">\r\n						<span class=\" newTimeFactor_before_abs m\">2019年4月30日&nbsp;- </span>本文转载自:https://github.com/yelog/layui-<em>soul</em>-<em>table</em>,本站转载出于传递更多信息之目的,版权归原作者或者来源机构所有。...\r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=qngGxhJEGQujwJEaqxVBNnQ-2WxT0bx3BaDy4vU76EQhEKFiMjzDi_Xzvl_RPKBo\" class=\"c-showurl\">https://www.codercto.com/a/758... </a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9f65cb4a8c8507ed19fa950d100b92235c4380146d8a86423f85d51584642c101a39fee83a27170ed8c66b6776f50f03b4e4732f77552ff6c68fd65ddccbd47b7fd67023706d913717c46fa9dc3621d653e44de8df0e96bfe745e3b9a3d6c82052&p=b477c64ad4934eae4ef6de3d59&newp=c673ca16d9c105f608e295285a53d8224216ed673cd4c44324b9d71fd325001c1b69e7be2321140ed3c67f6c01af4357e9f73278341766dada9fca458ae7c46c76&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=6\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n				<div class=\"result c-container \" id=\"7\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=HYRLBEHiXFKCMSu0mmYS4HlTIeRuAUh8Wgeh5HPGCPy-yX2tc4mSa7axQvzOG-Xtwgj8B3UCTDCuMlW9A-smTK\" target=\"_blank\">扫地羊/layui-<em>soul</em>-<em>table</em></a> \r\n					</h3>\r\n					<div class=\"c-abstract\">\r\n						<span class=\" newTimeFactor_before_abs m\">7天前&nbsp;- </span>3.引入 <em>soulTable</em>.css 到自己项目中。(在项目根目录可找到) 4.在 table.render...where Object/Function 子表<em>请求</em>参数1: 赋值json数据2: 方法返回json...\r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=HYRLBEHiXFKCMSu0mmYS4HlTIeRuAUh8Wgeh5HPGCPy-yX2tc4mSa7axQvzOG-Xtwgj8B3UCTDCuMlW9A-smTK\" class=\"c-showurl\">https://gitee.com/saodiyang/la... </a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"><span class=\"c-trust-as baozhang-new c-icon c-icon-baozhang-new\"></span></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9d78d513d9821ce806fa950e4b4587384e4380122ba1d50209d2843897732835506793ac56270773a3d20d6216db4848adb0687d6d4566f5dadf8939c0a6c77369df7023706bd71c4dce58fc96107e8a73cc0ff4f14aacfdab2593d8938e980a44ca254329d0aedc&p=cb60c64ad4d201eb19bd9b7d0d149d&newp=8b2a975b97dd11a05bed962259578c231610db2151d2d201298ffe0cc4241a1a1a3aecbf2226100ed3c17c6c0ba84a58edf534703d0034f1f689df08d2ecce7e31c939&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=7\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n				<div class=\"result c-container \" id=\"8\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=Z0N-bFNtg_ScrfCAUsmODsN3nhXgntbHDaNdb5mavtEvQ6GWnVGV7BplbC_KggqtLbUf2RruCc53tVFckhhm5lGCx8yma8VFD_HUTqlaUiq\" target=\"_blank\">对<em>Soul</em> 安卓App的一次 api<em>请求</em> 抓取记录 - - SegmentFault 思否</a> \r\n					</h3>\r\n					<div class=\"c-abstract\">\r\n						<span class=\" newTimeFactor_before_abs m\">2018年9月18日&nbsp;- </span>之前注册玩过一段时间的社交app--<em>soul</em>,发现其没有网页版也没有桌面版,app里...sign的生成方法是怎样的,然后根据自己的ID和token组合header进行爬取的<em>...</em> \r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=Z0N-bFNtg_ScrfCAUsmODsN3nhXgntbHDaNdb5mavtEvQ6GWnVGV7BplbC_KggqtLbUf2RruCc53tVFckhhm5lGCx8yma8VFD_HUTqlaUiq\" class=\"c-showurl\">https://segmentfault.com/a/119... </a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9d78d513d9821ce806fa950e4b4587384e4380122ba1d50209d2843897732835506793ac56270773a3d20d6216db4848adb0687d6d4566f5dadf8939c0a6d37f7ad770683648d5064c950eafbc17789e3dc347eaac12e5b8f23091add6d5d95450cc54127bf4b7cd051713be2ead5371b2f18e49631943effa3013a30e327ede65&p=882a9645ddd208fd0be2963161548f&newp=8c759a46d6c859b10be296245505c1231610db2151d7db126b82c825d7331b001c3bbfb423271407d7cf7c6402a4495ce0fb3575330923a3dda5c91d9fb4c574799039&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=8\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n				<div class=\"result c-container \" id=\"9\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=DM66wpwpxYLtkmu6x2hwpEtVCgTZ-ZoCN2OJcJ3WlzRU6_UCA0YT4q1DcofdGAi1\" target=\"_blank\"><em>soul</em>-<em>table</em>_叶落阁_作品-程序员客栈</a> \r\n					</h3>\r\n					<div class=\"c-abstract\">\r\n						表头筛选、自定义条件(支持前端筛选、后台筛选介绍请看三、后台筛选)拖动列调整顺序、隐藏显示列excel导出(根据筛选条件和列顺序导出)子表(\r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=DM66wpwpxYLtkmu6x2hwpEtVCgTZ-ZoCN2OJcJ3WlzRU6_UCA0YT4q1DcofdGAi1\" class=\"c-showurl\"><span><img class=\"source-icon\" src=\"https://cambrian-images.cdn.bcebos.com/b6a431f35dbc2c40d9d693df0cf23124_1575310547710586.jpeg@w_100,h_100\" />程序员客栈</span></a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9d78d513d9821ce806fa950e4b4587384e4380122ba1d50209d2843897732835506793ac56270773a3d20d6216db4848adb0687d6d4566f5dadf8939c0a6d76d6a9f2743325cdb14498f45b8cb31749c7f8d1ff4a91aecbbfb358e&p=882a9540c78a13fc57efdb124f4e&newp=8c759a45d3db5ffc57efce261e0092695d0fc20e3ad5d701298ffe0cc4241a1a1a3aecbf2226100ed3c17c6c0ba84a58edf534703d0034f1f689df08d2ecce7e3796&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=9\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n				<div class=\"result c-container \" id=\"10\">\r\n					<h3 class=\"t\">\r\n						<a href=\"http://www.baidu.com/link?url=TnLW1rqNuAxt1NwIKExW212JbqQlqIA46wyVa3_kkVI1PUslEFQO7K0wHTaNibmV64dnI31wFcuYbn-Gc9QO__\" target=\"_blank\">刚刚<em>Soul</em>怎么突然登不上了,老是提示网络<em>请求</em>超时? - 知乎</a> \r\n					</h3>\r\n					<div class=\"c-abstract\">\r\n						<span class=\" newTimeFactor_before_abs m\">2018年9月13日&nbsp;- </span>刚刚<em>Soul</em>怎么突然登不上了,老是提示网络<em>请求</em>超时? 显示全部 关注者5 被浏览2,040 关注问题写回答 邀请回答 添加评论 分享 暂时...\r\n					</div>\r\n					<div class=\"f13\">\r\n						<a target=\"_blank\" href=\"http://www.baidu.com/link?url=TnLW1rqNuAxt1NwIKExW212JbqQlqIA46wyVa3_kkVI1PUslEFQO7K0wHTaNibmV64dnI31wFcuYbn-Gc9QO__\" class=\"c-showurl\">www.zhihu.com/question... </a><span class=\"c-icons-outer\"><span class=\"c-icons-inner\"></span></span> - <a href=\"http://cache.baiducontent.com/c?m=9d78d513d9821ce806fa950e4b4587384e4380122ba1d50209d2843897732835506793ac56270773a3d20d6216db4848adb0687d6d4566f58cc9fb57c0fed76d3888507c2a47dc0605d36efe9619388267c71baff444bba7f03995a9d5d1d85458&p=8c748b0a85cc43ff57eb9637495c&newp=882a9347c8b11eec02be9b7c1b5192695d0fc20e3adcda01298ffe0cc4241a1a1a3aecbf2226100ed3c17c6c0ba84a58edf534703d0034f1f689df08d2ecce7e31fa78772a&user=baidu&fm=sc&query=soulTable++%D7%D4%B6%AF%C7%EB%C7%F3&qid=87017838000262e3&p1=10\" target=\"_blank\" class=\"m\">百度快照</a> \r\n					</div>\r\n				</div>\r\n			</div>\r\n			<div id=\"rs\">\r\n				<div class=\"tt\">\r\n					相关搜索\r\n				</div>\r\n				<table cellpadding=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<th>\r\n								<a href=\"https://www.baidu.com/s?wd=soul&rsf=62040005&rsp=0&f=1&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rqlang=cn&rs_src=0&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">soul</a> \r\n							</th>\r\n							<td>\r\n								<br />\r\n							</td>\r\n							<th>\r\n								<a href=\"https://www.baidu.com/s?wd=soul%E4%B8%BA%E4%BB%80%E4%B9%88%E8%80%81%E6%98%AF%E8%87%AA%E5%8A%A8%E5%85%B3%E9%97%AD&rsf=62040005&rsp=1&f=1&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rqlang=cn&rs_src=0&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">soul为什么老是自动关闭</a> \r\n							</th>\r\n							<td>\r\n								<br />\r\n							</td>\r\n							<th>\r\n								<a href=\"https://www.baidu.com/s?wd=soul%E5%AE%98%E6%96%B9%E7%BD%91%E7%AB%99&rsf=62040005&rsp=2&f=1&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rqlang=cn&rs_src=0&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">soul官方网站</a> \r\n							</th>\r\n						</tr>\r\n						<tr>\r\n							<th>\r\n								<a href=\"https://www.baidu.com/s?wd=soul%E7%99%BB%E5%BD%95%E4%B8%8D%E4%BA%86&rsf=62040005&rsp=3&f=1&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rqlang=cn&rs_src=0&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">soul登录不了</a> \r\n							</th>\r\n							<td>\r\n								<br />\r\n							</td>\r\n							<th>\r\n								<a href=\"https://www.baidu.com/s?wd=soul%E4%B8%8B%E7%BA%BF%E4%BA%86%E5%90%97&rsf=62040005&rsp=4&f=1&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rqlang=cn&rs_src=0&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">soul下线了吗</a> \r\n							</th>\r\n							<td>\r\n								<br />\r\n							</td>\r\n							<th>\r\n								<a href=\"https://www.baidu.com/s?wd=%E4%B8%BA%E4%BB%80%E4%B9%88soul%E6%89%93%E4%B8%8D%E5%BC%80%E4%BA%86&rsf=62040005&rsp=5&f=1&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rqlang=cn&rs_src=0&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">为什么soul打不开了</a> \r\n							</th>\r\n						</tr>\r\n						<tr>\r\n							<th>\r\n								<a href=\"https://www.baidu.com/s?wd=soul%E6%89%93%E4%B8%8D%E5%BC%80&rsf=62040005&rsp=6&f=1&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rqlang=cn&rs_src=0&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">soul打不开</a> \r\n							</th>\r\n							<td>\r\n								<br />\r\n							</td>\r\n							<th>\r\n								<a href=\"https://www.baidu.com/s?wd=soul%E4%BB%80%E4%B9%88%E6%97%B6%E5%80%99%E6%81%A2%E5%A4%8D&rsf=62040005&rsp=7&f=1&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rqlang=cn&rs_src=0&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">soul什么时候恢复</a> \r\n							</th>\r\n							<td>\r\n								<br />\r\n							</td>\r\n							<th>\r\n								<a href=\"https://www.baidu.com/s?wd=soul%E6%9C%80%E6%96%B0%E7%89%88%E5%9C%A8%E5%93%AA%E9%87%8C&rsf=62040005&rsp=8&f=1&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rqlang=cn&rs_src=0&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\">soul最新版在哪里</a> \r\n							</th>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n			</div>\r\n			<div id=\"page\">\r\n				<strong><span class=\"fk fk_cur\"></span><span class=\"pc\">1</span></strong><a href=\"https://www.baidu.com/s?wd=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&pn=10&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\"><span class=\"fk fkd\"></span><span class=\"pc\">2</span></a><a href=\"https://www.baidu.com/s?wd=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&pn=20&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\"><span class=\"fk\"></span><span class=\"pc\">3</span></a><a href=\"https://www.baidu.com/s?wd=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&pn=30&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\"><span class=\"fk fkd\"></span><span class=\"pc\">4</span></a><a href=\"https://www.baidu.com/s?wd=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&pn=40&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\"><span class=\"fk\"></span><span class=\"pc\">5</span></a><a href=\"https://www.baidu.com/s?wd=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&pn=50&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\"><span class=\"fk fkd\"></span><span class=\"pc\">6</span></a><a href=\"https://www.baidu.com/s?wd=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&pn=60&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\"><span class=\"fk\"></span><span class=\"pc\">7</span></a><a href=\"https://www.baidu.com/s?wd=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&pn=70&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L\"><span class=\"fk fkd\"></span><span class=\"pc\">8</span></a><a href=\"https://www.baidu.com/s?wd=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&pn=10&oq=soulTable%20%E8%87%AA%E5%8A%A8%E8%AF%B7%E6%B1%82&tn=monline_7_dg&ie=utf-8&rsv_pq=87017838000262e3&rsv_t=319fjl0Mj1Aw9VxqM0zl2nsB0vUcqOMitFywE3z1WR5OBIwGRy%2FJVFiO1fnzyfDDQU7L&rsv_page=1\" class=\"n\">下一页&gt;</a> \r\n			</div>\r\n			<div id=\"content_bottom\">\r\n			</div>\r\n		</div>\r\n		<div id=\"foot\">\r\n			<div class=\"foot-inner\">\r\n				<span id=\"help\"><a href=\"http://help.baidu.com/question\" target=\"_blank\">帮助</a><a href=\"http://www.baidu.com/search/jubao.html\" target=\"_blank\">举报</a><a class=\"feedback\" target=\"_blank\">用户反馈</a></span> \r\n			</div>\r\n		</div>\r\n	</div>\r\n</div>\r\n<br />', '', '', '', '', '', '2019-11-26 11:11:46', '2019-11-26 14:53:03');
INSERT INTO `sf_entity` VALUES ('7716213', 'list', '12', '/', '_self', '', '', '', '', '0000-00-00 00:00:00', '2019-11-27 18:52:57');
INSERT INTO `sf_entity` VALUES ('7716214', 'ad', '13', '/', '_blank', '/asset/image/ad/GQPDL5QB.jpg', '0', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_entity` VALUES ('7716215', 'ad', '14', '/', '_blank', '', '0', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_entity` VALUES ('7716216', 'list', '15', '#', '_self', '', '', '', '', '0000-00-00 00:00:00', '2019-11-27 19:03:37');
INSERT INTO `sf_entity` VALUES ('7716217', 'list', '16', '/', '_self', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_entity` VALUES ('7716218', 'list', '17', '/', '_self', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_entity` VALUES ('7716219', 'list', '18', '<blockquote class=\"layui-elem-quote\">oppcms是共同自由、免费，主要宗旨是让每个开发者能够根据自己的需求自由灵活配置、开发和使用。 该系统通过八年打造不断简化，升级；到目前开源已经做到入门简单、灵活配置和易于扩展的稳定版本，只需花五分钟学习即可快速搭建前后台的完善体系，非常适合中小企业敏捷开发的首选框架。</blockquote><a name=\"get\"></a>获得 oppcms<div class=\"site-text\"><p>1. 官网首页下载</p><blockquote class=\"layui-elem-quote layui-quote-nm\">你可以在我们的<a href=\"http://www.samefree.com/\">官网首页</a> 下载到 oppcms 的最新版，它经过了自动化构建，更适合用于生产环境。目录结构如下：</blockquote><pre class=\"layui-code layui-box layui-code-view\"><h3 class=\"layui-code-h3\">code</h3><ol class=\"layui-code-ol\"><li>├─asset //资源目录</li><li>│ │─cache //缓存资源输出</li><li>│ ├─forge //伪代码资源，sfgetforge函数专用</li><li>│ ├─internal //内部文件</li><li>│ │ ├─data //内部数据</li><li>│ │ │ ├─debug //shell/debug.php调试数据</li><li>│ │ │ └─patch //shell/patch.php发布数据</li><li>│ │ ├─debug //sfdebug函数调试数据</li><li>│ │ └─report //系统错误报告数据</li><li>├─class //类库目录</li><li>├─core //核心目录</li><li>│─etc //自定义配置目录</li><li>│ └─config.php //全局配置文件</li><li>│─<strong>module</strong>//功能模块目录</li><li>│ ├─模块名称 //小写</li><li>│ │ ├─tpl //模板目录，控制其中使用$this-&gt;view-&gt;setTemplate(路径)来指定</li><li>│ │ │ └─xxx.phtml //模板文件</li><li>│ │ ├─DefaultController.php //默认控制器文件，其它控制器可 &lt;控制器名&gt;Controoler.php，访问则：/模块名_控制器名/[index]</li><li>│ │ ├─CommonBean.php //模块公共业务类，用于根据业务处理数据</li><li>│ │ ├─CommonModel.php //模块公共模型类，用户操作数据</li><li>│ │ └─autoload.php //自动加载文件，用于注册事件或自动执行逻辑</li><li>│ ├─...</li><li>│ └─list.cache.php //模块列表缓存文件，默认每小时自动生成，可删除</li><li>│─shell //执行脚本目录</li><li>│─<strong>theme</strong>//模板目录</li><li>│ ├─模板名 //config.php中配置THEME_NAME</li><li>│ │ ├─asset //模板资源目录，phtml中 $this-&gt;getAssetUrl(相对路径) 获取</li><li>│ │ ├─layout //页面布局配置目录</li><li>│ │ ├─tpl //模板目录</li><li>│ │ └─config.xml //模板配置文件</li><li>│ ├─...</li><li>│ └─error.phtml //系统错误模板</li><li>│─vendor //第三方安装目录</li><li>│─.htaccess //重定向文件（Apache）</li><li>│─web.config //重定向文件（IIS）</li><li>│─<strong>config.php</strong>//系统配置文件</li><li>│─index.php //首页引擎文件</li><li>│─local.xml //系统支撑配置文件</li><li>└─var.php //自定义函数文件</li><li></li></ol></pre><blockquote class=\"layui-elem-quote layui-quote-nm\">解压后可直接访问<br />路由为：/模块名称[_控制器名称]/[方法名称]/参数1/值1<br />默认情况下加载 /module/default/DefaultControll.php 中的 indexAction方法（Action为固定函数名） <br />$this-&gt;view-&gt;parsePage(\'页面名\');<br />$this-&gt;view-&gt;render();<br />会注册 /theme/模板名/layout/页面名.xml 并渲染 template 名称中的 tpl 目录模板文件<br /><strong>备注:一般开发只需关注module和theme目录即可</strong></blockquote><p>2. Git 仓库下载</p><blockquote class=\"layui-elem-quote layui-quote-nm\">你也可以通过<a href=\"https://github.com/mdzfree/sf/\"target=\"_blank\">GitHub</a> 得到 oppcms 的完整开发包，以便于你进行二次开发，为我们贡献方案 <br /><br /><iframe src=\"http://ghbtns.com/github-btn.html?user=mdzfree&repo=sf&type=watch&count=true&size=large\"width=\"156px\"height=\"30px\"frameborder=\"0\"></iframe><br /><br /><div class=\"layui-btn-container\"><a class=\"layui-btn layui-btn-normal\"href=\"https://github.com/mdzfree/sf/\"target=\"_blank\"rel=\"nofollow\"style=\"background-color: #24292E; color: #fff;\">GitHub</a><a class=\"layui-btn layui-btn-normal\"href=\"https://gitee.com/samefree/cms/\"target=\"_blank\"rel=\"nofollow\"style=\"background-color: #C71D23; color: #fff;\">码云</a></div></blockquote></div>', '', '', '', '', '', '2019-11-28 08:20:43', '2019-11-28 09:35:27');
INSERT INTO `sf_entity` VALUES ('7716212', 'list', '11', '<p class=\"one-p\">\r\n	近日，美国国会通过所谓“香港人权与民主法案”，不但不谴责令人发指的暴力犯罪，反而借“人权”“民主”之名为香港激进暴力犯罪分子撑腰打气，再度暴露其妄图乱港制华的险恶用心。\r\n</p>\r\n<p class=\"one-p\">\r\n	曾经美丽的东方之珠，如今满目疮痍，令人痛心。5个多月以来，暴力的阴霾一步步地蚕食着香港的蓝天，暴徒们打砸抢烧、残害市民、暴力袭警、大肆破坏，导致至少145个地铁及轻铁站被破坏，港铁设施被破坏近5800次，被损毁的公共设施数不胜数……接连不断的大规模违法暴力行径，已经将香港推到了极为危险的境地。\r\n</p>\r\n<p class=\"one-p\">\r\n	在这些触目惊心的事实面前，任何有良知的人都会义愤填膺。然而，美国一些政客非但对暴行熟视无睹，反而与反中乱港势力狼狈为奸，在背后煽风点火、推波助澜，极力推动暴力活动在香港不断升级。\r\n</p>\r\n<p class=\"one-p\">\r\n	在他们眼里，血淋淋的暴行变成了“美丽的风景线”，骇人听闻的恶性犯罪被粉饰成追求“人权”和“民主”，而香港警察的正当执法却被抹黑为“暴力镇压”。对他们来说，越是把香港搞乱，他们就越能趁火打劫、捞取政治资本，以逞其私利。\r\n</p>\r\n<p class=\"one-p\">\r\n	美国一些人处心积虑炮制的所谓“香港人权与民主法案”，从今年6月被国会议员重提，到10月、11月接连在众议院、参议院快速表决通过，这一过程伴随着香港暴力活动不断升级和蔓延。这种“同步”恰恰说明，这一法案根本不是为了什么“人权”和“民主”，而是在为违法暴徒提供“助推器”，给特区政府止暴制乱制造“绊脚石”，更是向维护法治秩序的正义人士发出“恐吓信”，其险恶用心昭然若揭。\r\n</p>\r\n<p class=\"one-p\">\r\n	“任何国家都不会允许暴力破坏社会，扰乱经济。美国参议院这一法案对美国、对中国、对世界都是有害的。”美国库恩基金会主席罗伯特·库恩说。美国资深外交官傅立民指出，“美国不是香港和北京之间对话的一部分”“暴力示威者不应该受到外国同情”。\r\n</p>\r\n<p class=\"one-p\">\r\n	连日来，无论是香港各界，还是国际社会，无论是政界商界人士，还是专家学者、媒体从业者，越来越多真正关心香港稳定和发展的人纷纷表态谴责美国涉港法案：“罔顾香港市民根本利益”“为香港暴力分子撑腰打气”“支持激进抗议者就是支持他们破坏香港这座城市”“又是一次政治作秀，不会得逞”……这些正义声音，从不同侧面说明这一涉港法案根本不得人心。\r\n</p>\r\n<p class=\"one-p\">\r\n	煽动暴力只会让暴力蔓延，必然损害包括美国在内的世界各国在港利益。美国一些政客妄图借涉港法案破坏香港发展、遏制中国，无异于一场危险的“玩火游戏”，终将自食其果。\r\n</p>\r\n<div id=\"Status\">\r\n	<div class=\"article-status\">\r\n		<div class=\"statement\">\r\n			免责声明：本文来自腾讯新闻客户端自媒体，不代表腾讯网的观点和立场。\r\n		</div>\r\n	</div>\r\n</div>', '', '', '', '', '', '2019-11-26 11:14:35', '2019-11-26 11:43:14');
INSERT INTO `sf_entity` VALUES ('7716220', 'list', '19', '...', '', '', '', '', '', '2019-11-28 08:21:33', '2019-11-28 10:06:39');
INSERT INTO `sf_entity` VALUES ('7716221', 'list', '20', '...', '', '', '', '', '', '2019-11-28 08:24:41', '2019-11-28 08:24:41');
INSERT INTO `sf_entity` VALUES ('7716222', 'list', '21', '...', '', '', '', '', '', '2019-11-28 08:25:23', '2019-11-28 08:25:23');
INSERT INTO `sf_entity` VALUES ('7716223', 'list', '22', '...', '', '', '', '', '', '2019-11-28 08:57:18', '2019-11-28 08:57:18');

-- ----------------------------
-- Table structure for `sf_list`
-- ----------------------------
DROP TABLE IF EXISTS `sf_list`;
CREATE TABLE `sf_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '列表编码',
  `mold_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属分类',
  `title` varchar(200) NOT NULL COMMENT '列表标题',
  `outline` varchar(500) NOT NULL DEFAULT '' COMMENT '列表概要',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序，降序',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COMMENT='列表表，存放有序列表数据，例如文章、链接、广告等';

-- ----------------------------
-- Records of sf_list
-- ----------------------------
INSERT INTO `sf_list` VALUES ('1', '1', '首页', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_list` VALUES ('2', '2', 'OPP', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_list` VALUES ('3', '1', '关于我们', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_list` VALUES ('4', '1', '解决方案', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_list` VALUES ('5', '1', 'OPPCMS', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_list` VALUES ('6', '1', '新数据', '', '0', '0000-00-00 00:00:00', '2019-11-14 15:00:02');
INSERT INTO `sf_list` VALUES ('10', '14', 'fsdffdsfdsfsdfsf', '百度首页设置登录网页资讯视频图片知道文库贴吧采购地图更多»换一换 搜索热点1全球最大造船集团新511万2俄舰队地中海演习436万3具荷拉家中身亡430万4网易向员工致歉430万5人民日报评张云雷426万6王思聪公司新电影422万7紫光阁怒批张云雷412万8邱淑贞女儿封面395万9大理洱源发生地震327万10卷走10亿拥23套房282万11崔始源道歉281万12零下40度不结冰264万13男性保护令新258万1411岁男孩被父杀害235万15越南鞋厂百人中毒230万查看更多>> 收起工具时间不限所有网页和文件站点内检索搜索工具百度为您找到相关结果约72个layui-soul-tabl ...', '0', '2019-11-26 08:11:46', '2019-11-26 14:53:03');
INSERT INTO `sf_list` VALUES ('11', '12', '防守打法胜多负少所发生的发送到', '近日，美国国会通过所谓“香港人权与民主法案”，不但不谴责令人发指的暴力犯罪，反而借“人权”“民主”之名为香港激进暴力犯罪分子撑腰打气，再度暴露其妄图乱港制华的险恶用心。曾经美丽的东方之珠，如今满目疮痍，令人痛心。5个多月以来，暴力的阴霾一步步地蚕食着香港的蓝天', '0', '2019-11-26 11:14:35', '2019-11-26 11:43:14');
INSERT INTO `sf_list` VALUES ('12', '1', '首页', '', '4', '2019-11-27 09:16:36', '2019-11-27 19:03:36');
INSERT INTO `sf_list` VALUES ('13', '15', '测试的广告', '', '0', '2019-11-27 10:36:55', '2019-11-27 10:36:55');
INSERT INTO `sf_list` VALUES ('14', '15', '测试', '', '0', '2019-11-27 11:08:37', '2019-11-27 11:08:37');
INSERT INTO `sf_list` VALUES ('15', '1', '解决方案', '', '3', '2019-11-27 18:53:14', '2019-11-27 19:03:37');
INSERT INTO `sf_list` VALUES ('16', '1', '社区', '', '1', '2019-11-27 18:53:25', '2019-11-27 19:03:36');
INSERT INTO `sf_list` VALUES ('17', '1', 'OPPCMS', '', '2', '2019-11-27 18:53:33', '2019-11-27 19:03:36');
INSERT INTO `sf_list` VALUES ('18', '18', '开始使用', '入门指南', '0', '2019-11-28 08:20:43', '2019-11-28 09:35:27');
INSERT INTO `sf_list` VALUES ('19', '18', 'Hello World', '模块 / 页面 / 控制器', '0', '2019-11-28 08:21:33', '2019-11-28 10:06:39');
INSERT INTO `sf_list` VALUES ('20', '18', '有趣的调试工具', 'Debug', '0', '2019-11-28 08:24:41', '2019-11-28 08:24:41');
INSERT INTO `sf_list` VALUES ('21', '18', '方便的发布方式', 'Path', '0', '2019-11-28 08:25:23', '2019-11-28 08:25:23');
INSERT INTO `sf_list` VALUES ('22', '19', '报500错误如何排查', 'Report', '0', '2019-11-28 08:57:18', '2019-11-28 08:57:18');

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COMMENT='记忆表，用于临时存储数据，类似内存缓存';

-- ----------------------------
-- Records of sf_memory
-- ----------------------------
INSERT INTO `sf_memory` VALUES ('23', '15', 'http://localhost/oppcms/default?_log=1|6J8QPB44', '[[\"d-1573726324.2381\",[\"打开数据库！\"]],[\"d-1573726324.2501\",[\"关闭数据库！\"]]]', '1573729925', '2019-11-14 18:12:05', '2019-11-14 18:12:05');
INSERT INTO `sf_memory` VALUES ('24', '15', 'Console:http://localhost/oppcms/default?_log=1|T3L9JV39', '[[\"d-1573726589.3321\",[\"打开数据库！\"]],[\"d-1573726589.3441\",[\"关闭数据库！\"]]]', '1573730190', '2019-11-14 18:16:30', '2019-11-14 18:16:30');
INSERT INTO `sf_memory` VALUES ('25', '15', 'Console:http://localhost/oppcms/default|NE0K6K5B', '[[\"d-1573726644.0431\",[\"打开数据库！\"]],[\"d-1573726644.0541\",[\"关闭数据库！\"]]]', '1573730245', '2019-11-14 18:17:25', '2019-11-14 18:17:25');

-- ----------------------------
-- Table structure for `sf_mold`
-- ----------------------------
DROP TABLE IF EXISTS `sf_mold`;
CREATE TABLE `sf_mold` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类编码',
  `site_id` int(11) NOT NULL COMMENT '所属站点',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
  `path` varchar(100) NOT NULL DEFAULT ',0,' COMMENT '父路径',
  `type` varchar(10) NOT NULL DEFAULT 'default' COMMENT '模具类型',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `outline` varchar(500) NOT NULL DEFAULT '' COMMENT '分类概述',
  `sort` int(11) NOT NULL COMMENT '排序，降序',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='模具表，区分数据类型，如新闻、链接、广告等';

-- ----------------------------
-- Records of sf_mold
-- ----------------------------
INSERT INTO `sf_mold` VALUES ('1', '1', '0', ',0,1,', 'nav', '主导航', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_mold` VALUES ('17', '1', '0', ',0,17,', 'article', '教程列表', '', '0', '2019-11-28 00:00:00', '2019-11-28 00:00:00');
INSERT INTO `sf_mold` VALUES ('18', '1', '17', ',0,17,18,', 'article', '基础说明', '', '0', '2019-11-28 00:00:00', '2019-11-28 00:00:00');
INSERT INTO `sf_mold` VALUES ('15', '1', '0', ',0,15,', 'ad', '首页轮播', '', '0', '2019-11-27 00:00:00', '2019-11-27 00:00:00');
INSERT INTO `sf_mold` VALUES ('16', '1', '0', ',0,16,', 'ad', '右侧悬浮', '', '0', '2019-11-27 00:00:00', '2019-11-27 00:00:00');
INSERT INTO `sf_mold` VALUES ('19', '1', '17', ',0,17,19,', 'article', '常见问题', '', '0', '2019-11-28 00:00:00', '2019-11-28 08:47:47');

-- ----------------------------
-- Table structure for `sf_preview`
-- ----------------------------
DROP TABLE IF EXISTS `sf_preview`;
CREATE TABLE `sf_preview` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '预览编号',
  `code` varchar(50) NOT NULL COMMENT '预览代码',
  `data` text NOT NULL COMMENT '预览数据',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='预览表，存储预览数据以用于临时展示';

-- ----------------------------
-- Records of sf_preview
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COMMENT='消息队列表';

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
  `data` binary(255) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0' COMMENT '消息存储数据',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COMMENT='消息队列表';

-- ----------------------------
-- Records of sf_queue_log
-- ----------------------------

-- ----------------------------
-- Table structure for `sf_relate`
-- ----------------------------
DROP TABLE IF EXISTS `sf_relate`;
CREATE TABLE `sf_relate` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '关联编号',
  `type` varchar(20) NOT NULL COMMENT '关联类型',
  `primary_id` bigint(20) NOT NULL COMMENT '主关联编号',
  `foreign_id` bigint(20) NOT NULL COMMENT '外关联编号',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '关系排序，降序',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='关系表，主要把数据关联起来';

-- ----------------------------
-- Records of sf_relate
-- ----------------------------
INSERT INTO `sf_relate` VALUES ('1', 'list_nav', '4', '5', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_relate` VALUES ('2', 'list_nav', '4', '6', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sf_relate` VALUES ('9', 'list', '15', '17', '2', '2019-11-27 19:02:35', '2019-11-27 19:02:35');

-- ----------------------------
-- Table structure for `sf_setting`
-- ----------------------------
DROP TABLE IF EXISTS `sf_setting`;
CREATE TABLE `sf_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属站点，0为全局',
  `group` varchar(50) NOT NULL DEFAULT 'default' COMMENT '配置组',
  `code` varchar(50) NOT NULL COMMENT '配置代码',
  `name` varchar(50) NOT NULL COMMENT '配置名称',
  `value` varchar(500) NOT NULL COMMENT '配置值',
  `format` varchar(50) NOT NULL DEFAULT 'text' COMMENT '值格式',
  `format_data` varchar(500) NOT NULL DEFAULT '' COMMENT '格式数据，用于格式支撑',
  `note` varchar(200) NOT NULL DEFAULT '' COMMENT '配置备注',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='配置表';

-- ----------------------------
-- Records of sf_setting
-- ----------------------------
INSERT INTO `sf_setting` VALUES ('1', '0', 'default', 'site_name', '网站名称', 'OPPCMS', 'text', '', '这是一个网站名称的配置，用于全局显示站点的名称，是唯一的名字', '0000-00-00 00:00:00', '2019-11-27 18:46:15');
INSERT INTO `sf_setting` VALUES ('2', '0', 'default', 'site_description', '网站描述', '转运四方，拥有美国，英国，日本，韩国，德国，澳大利亚等多国仓库及专业物流配送体系，转运全球，物流四方', 'text', '', '', '0000-00-00 00:00:00', '2019-11-27 16:36:03');
INSERT INTO `sf_setting` VALUES ('3', '0', 'default', 'site_keywords', '网站关键字', '转运四方，转运公司，海淘 ，转运全球', 'text', '', '', '0000-00-00 00:00:00', '2019-11-27 16:36:03');
INSERT INTO `sf_setting` VALUES ('4', '0', 'contact', 'contact_qq', '联系QQ', '83398609', 'text', '', '', '0000-00-00 00:00:00', '2019-11-27 16:39:40');
INSERT INTO `sf_setting` VALUES ('5', '0', 'default', 'site_enable', '前台状态', '1', 'checkbox', '', '网站前台是否开启', '0000-00-00 00:00:00', '2019-11-27 17:50:25');
INSERT INTO `sf_setting` VALUES ('6', '0', 'default', 'cache_mode', '缓存模式', '1', 'select', '[{\"code\":\"1\",\"name\":\"默认\"},{\"code\":\"2\",\"name\":\"高速\"},{\"code\":\"3\",\"name\":\"极速\"}]', '', '0000-00-00 00:00:00', '2019-11-27 18:30:43');

-- ----------------------------
-- Table structure for `sf_site`
-- ----------------------------
DROP TABLE IF EXISTS `sf_site`;
CREATE TABLE `sf_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL COMMENT '站点代码',
  `name` varchar(20) NOT NULL COMMENT '站点名称',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='站点表，主要用于分国家，分地区，分站点；可根据不同需求展现不同内容';

-- ----------------------------
-- Records of sf_site
-- ----------------------------
INSERT INTO `sf_site` VALUES ('1', 'cn', '中国', '1949-10-01 15:00:00');

-- ----------------------------
-- Table structure for `sf_url`
-- ----------------------------
DROP TABLE IF EXISTS `sf_url`;
CREATE TABLE `sf_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL COMMENT '地址类型',
  `type_id` varchar(50) NOT NULL COMMENT '类型代码',
  `value` varchar(200) NOT NULL COMMENT '自定义url',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`value`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='url自定义表';

-- ----------------------------
-- Records of sf_url
-- ----------------------------
INSERT INTO `sf_url` VALUES ('1', 'list', '19', '/hello', '2019-11-28 10:05:13', '2019-11-28 10:06:39');
