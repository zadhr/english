-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2019-04-25 06:37:25
-- 服务器版本： 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `english`
--

-- --------------------------------------------------------

--
-- 表的结构 `book_question`
--

CREATE TABLE `book_question` (
  `id` int(11) NOT NULL,
  `tid` int(11) DEFAULT NULL COMMENT '书本id',
  `sid` int(11) DEFAULT NULL COMMENT '单元',
  `word` varchar(90) DEFAULT NULL COMMENT '单词',
  `choice1` varchar(30) DEFAULT NULL,
  `choice2` varchar(30) DEFAULT NULL,
  `choice3` varchar(30) DEFAULT NULL,
  `choice4` varchar(30) DEFAULT NULL,
  `place` varchar(51) DEFAULT NULL COMMENT '位置',
  `example` varchar(50) DEFAULT NULL,
  `trans` varchar(51) DEFAULT NULL,
  `w_trans` varchar(60) DEFAULT NULL COMMENT '翻译',
  `type` varchar(20) DEFAULT NULL COMMENT '单词类型',
  `answer` int(11) DEFAULT NULL COMMENT '选择题正确选项'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `book_question`
--

INSERT INTO `book_question` (`id`, `tid`, `sid`, `word`, `choice1`, `choice2`, `choice3`, `choice4`, `place`, `example`, `trans`, `w_trans`, `type`, `answer`) VALUES
(1, 1, 1, 'success', '成功', '失败', '123', '123', '123', '123', '123', '成功', 'v', NULL),
(2, 1, 1, 'fail', '成功', '失败', '123', '3123', '123', '123', '123', '失败', 'n', NULL),
(3, 5, 5, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', 0),
(4, 9, 9, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', 0);

-- --------------------------------------------------------

--
-- 表的结构 `book_system`
--

CREATE TABLE `book_system` (
  `id` int(11) NOT NULL,
  `unit` varchar(90) DEFAULT NULL COMMENT '单元',
  `tid` int(11) DEFAULT NULL,
  `choice_limit` int(11) DEFAULT '0' COMMENT '选择题限时',
  `choice_num` smallint(6) DEFAULT '0' COMMENT '选择题出题数目',
  `blank_limit` int(11) DEFAULT '0' COMMENT '填空题限时',
  `blank_num` int(11) DEFAULT '0' COMMENT '填空题出题数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `book_system`
--

INSERT INTO `book_system` (`id`, `unit`, `tid`, `choice_limit`, `choice_num`, `blank_limit`, `blank_num`) VALUES
(1, '第一单元', 1, 5, 2, 5, 2),
(2, 'unit1', 2, 1, 1, 1, 1),
(3, 'unit1', 3, 1, 1, 1, 1),
(4, 'unit1', 4, 1, 1, 1, 1),
(5, 'unit1', 5, 1, 1, 1, 1),
(6, 'unit1', 6, 1, 1, 1, 1),
(7, 'unit1', 7, 1, 1, 1, 1),
(8, 'unit1', 8, 1, 1, 1, 1),
(9, 'unit1', 9, 5, 1, 5, 1);

-- --------------------------------------------------------

--
-- 表的结构 `book_title`
--

CREATE TABLE `book_title` (
  `id` int(10) UNSIGNED NOT NULL,
  `gid` smallint(6) DEFAULT NULL COMMENT '年级id',
  `name` varchar(42) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `book_title`
--

INSERT INTO `book_title` (`id`, `gid`, `name`) VALUES
(1, 10, '必修一'),
(2, 1, '一年级上册'),
(3, 1, '一年级下册'),
(4, 7, '初一上册'),
(5, 8, '初二上册'),
(6, 2, '二年级上册（人教版）'),
(7, 1, '一年级上册（人教版）'),
(8, 1, '一年级上册（xx版）'),
(9, 10, '必修二');

-- --------------------------------------------------------

--
-- 表的结构 `document`
--

CREATE TABLE `document` (
  `id` tinyint(4) NOT NULL,
  `content` mediumtext,
  `title` varchar(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `document`
--

INSERT INTO `document` (`id`, `content`, `title`) VALUES
(7, '<p>爱的发的是发送到发送到发送到</p><p><img src=\"http://192.168.1.109/english/public/uploads/pic/5c0f26f90921f.jpg\"></p><p>213212321</p>', '公司简介'),
(8, '<p>\"一、使用的方法</p><p>这组词的共同意思是“运动，竞技，比赛”。其区别在于：</p><p>⒈sport一般指体力运动，如爬山、滑水、钓鱼等； game指常有一定的规则，而且决定胜负的脑力或体力劳动的“竞技”； match多指网球、足球、高尔夫球等运动项目的比赛，常用在英国； play泛指无目的或结果的消遣或娱乐活动； tournament指通过不同级别的比赛而夺魁的体育项目“比赛”“锦标赛”“联赛”。\"</p>', '帮助文档');

-- --------------------------------------------------------

--
-- 表的结构 `figure`
--

CREATE TABLE `figure` (
  `id` smallint(6) NOT NULL,
  `pic` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `figure`
--

INSERT INTO `figure` (`id`, `pic`) VALUES
(5, 'http://192.168.1.109/english/public/uploads/pic/5c175e8a22236.jpg'),
(4, 'http://192.168.1.109/english/public/uploads/pic/5c175e8650866.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `gift`
--

CREATE TABLE `gift` (
  `id` int(11) NOT NULL,
  `pic` varchar(250) DEFAULT NULL,
  `num` varchar(30) DEFAULT NULL COMMENT '数量',
  `point` int(11) DEFAULT NULL,
  `introduce` varchar(300) DEFAULT NULL COMMENT '简介',
  `name` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `gift`
--

INSERT INTO `gift` (`id`, `pic`, `num`, `point`, `introduce`, `name`) VALUES
(1, 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2960781233,1253702570&fm=27&gp=0.jpg', '96', 500, 'tstst', '熊本熊sss'),
(4, 'http://192.168.1.109/english/public/uploads/pic/5c12076f85987.jpg', '121', 123, '3123123', '123'),
(5, 'http://192.168.1.109/english/public/uploads/pic/5c12082b173e7.jpg', '3122', 12312, '123123', '12312321'),
(6, 'http://192.168.1.109/english/public/uploads/pic/5c120a33b91bf.jpg', '123123', 12312, '123123', '123123'),
(7, 'http://192.168.1.109/english/public/uploads/pic/5c120a3c7b95f.jpg', '123', 123, '123', '12312'),
(8, 'http://192.168.1.109/english/public/uploads/pic/5c120a468653f.jpg', '123123', 123123, '12321', '123'),
(9, 'http://192.168.1.109/english/public/uploads/pic/5c120a4e43307.jpg', '123123', 123123, '123123', '123123'),
(10, 'http://192.168.1.109/english/public/uploads/pic/5c120a5975f87.jpg', '12312', 123123, '123123', '123123'),
(11, 'http://192.168.1.109/english/public/uploads/pic/5c120a5f0377f.jpg', '312312', 12312, '12321', '123123');

-- --------------------------------------------------------

--
-- 表的结构 `gift_order`
--

CREATE TABLE `gift_order` (
  `id` int(10) UNSIGNED NOT NULL,
  `nickname` varchar(90) DEFAULT NULL,
  `gift` varchar(120) DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '2' COMMENT '状态  2未发放   1已发放',
  `name` varchar(90) DEFAULT NULL COMMENT '姓名',
  `state` tinyint(4) DEFAULT NULL COMMENT '1快递   2现场取',
  `no` varchar(30) DEFAULT '无订单号' COMMENT '快递号',
  `num` int(15) DEFAULT NULL COMMENT '数量',
  `phone` varchar(15) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `DATE` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `gift_order`
--

INSERT INTO `gift_order` (`id`, `nickname`, `gift`, `point`, `address`, `status`, `name`, `state`, `no`, `num`, `phone`, `uid`, `pic`, `DATE`) VALUES
(1, '胡歌', '玩偶', 1000, 'sadsda', 2, 'Bean', 1, '123', 1, '18888888888', 1, NULL, '2018-12-14 14:38:18'),
(2, 'sfsf', 'dsds', 4500, 'sdasdasd', 1, 'sdsd', 2, '无订单号', 0, '1555555555', 1, NULL, '2018-12-14 14:38:18'),
(3, 'ZIMING_XIE', '熊本熊sss', 500, '123', 2, '刘德华', 1, '无订单号', 1, '13169640604', 2, NULL, '2018-12-14 14:38:18'),
(4, 'ZIMING_XIE', '熊本熊sss', 500, '123', 2, '刘德华', 1, '无订单号', 1, '13169640604', 2, NULL, '2018-12-14 14:38:18'),
(5, 'ZIMING_XIE', '熊本熊sss', 500, '123', 2, '刘德华', 1, '无订单号', 1, '13169640604', 2, NULL, '2018-12-14 14:38:18'),
(6, 'ZIMING_XIE', '12312321', 12312, '123', 2, '刘德华', 1, '无订单号', 1, '13169640604', 2, 'http://192.168.1.109/english/public/uploads/pic/5c12082b173e7.jpg', '2018-12-14 14:38:18'),
(7, 'ZIMING_XIE', '熊本熊sss', 500, '123', 2, '刘德华', 1, '无订单号', 1, '13169640604', 2, 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2960781233,1253702570&fm=27&gp=0.jpg', '2018-12-14 14:38:18'),
(8, 'ZIMING_XIE', '熊本熊sss', 500, '123', 2, '刘德华', 1, '无订单号', 1, '13169640604', 2, 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2960781233,1253702570&fm=27&gp=0.jpg', '2018-12-14 14:38:18'),
(9, 'ZIMING_XIE', '熊本熊sss', 500, '123', 2, '刘德华', 1, '无订单号', 1, '13169640604', 2, 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2960781233,1253702570&fm=27&gp=0.jpg', '2018-12-14 14:38:18'),
(10, 'ZIMING_XIE', '123', 123, '123', 2, '刘德华', 1, '无订单号', 1, '13169640604', 2, 'http://192.168.1.109/english/public/uploads/pic/5c12076f85987.jpg', '2018-12-14 14:38:18'),
(11, 'ZIMING_XIE', '123', 123, '123', 2, '刘德华', 2, '无订单号', 1, '13169640604', 2, 'http://192.168.1.109/english/public/uploads/pic/5c12076f85987.jpg', '2018-12-14 14:38:18'),
(12, 'ZIMING_XIE', '熊本熊sss', 500, '123', 1, '刘德华', 1, '无订单号', 1, '13169640604', 2, 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2960781233,1253702570&fm=27&gp=0.jpg', '2018-12-14 14:38:18');

-- --------------------------------------------------------

--
-- 表的结构 `grade`
--

CREATE TABLE `grade` (
  `id` smallint(6) NOT NULL,
  `grade` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `grade`
--

INSERT INTO `grade` (`id`, `grade`) VALUES
(1, '一年级'),
(2, '二年级'),
(3, '三年级'),
(4, '四年级'),
(5, '五年级'),
(6, '六年级'),
(7, '初一'),
(8, '初二'),
(9, '初三'),
(10, '高中');

-- --------------------------------------------------------

--
-- 表的结构 `midware`
--

CREATE TABLE `midware` (
  `id` int(11) NOT NULL,
  `openid` varchar(90) DEFAULT NULL,
  `token` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `midware`
--

INSERT INTO `midware` (`id`, `openid`, `token`) VALUES
(1, 'test', 'testtest'),
(5, 'o6Rw75BNvWBucdt38Yoy_TmaSqA4', 'e82d0a5921b86a16e109ad2409d9590d7d033c40'),
(3, 'o6Rw75L6WZsn3Vs8qjGUgVwz451w', '73655e608ae069692cb3408b50b0e0a6aa3eeebc'),
(4, 'o6Rw75La8kFw18m3GFchE03i-DHo', 'f368496f302aa31f3078125ddacfc31c57c7bfd8');

-- --------------------------------------------------------

--
-- 表的结构 `mistake`
--

CREATE TABLE `mistake` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `words` varchar(60) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sid` int(11) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '1选择题   2填空题',
  `state` tinyint(4) DEFAULT '1' COMMENT '1自动  2手动'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mistake`
--

INSERT INTO `mistake` (`id`, `uid`, `words`, `date`, `sid`, `type`, `state`) VALUES
(1, 5, 'fail', '2019-01-23 03:58:05', 1, 1, 1),
(2, 5, 'success', '2019-01-23 03:59:22', 1, 2, 1),
(3, 5, 'fail', '2019-01-24 02:37:51', 0, 1, 1),
(4, 5, '1', '2019-02-25 07:50:05', 5, 1, 1),
(5, 5, '1', '2019-02-25 08:26:07', 9, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_list`
--

CREATE TABLE `m_list` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `qid` mediumtext,
  `sid` varchar(90) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_list`
--

INSERT INTO `m_list` (`id`, `uid`, `qid`, `sid`, `type`) VALUES
(1, 5, '[1,2]', '1', 1),
(2, 5, '[1,2]', '1', 2),
(3, 5, '[]', '5', 1),
(4, 5, '[]', '[1,9]', 1),
(5, 5, '[2]', '[1]', 1),
(6, 5, '[1,2,3]', '[5,1]', 1),
(7, 5, '[]', '9', 2),
(8, 5, '[4]', '9', 1),
(9, 5, '[2,4]', '[1,9]', 2);

-- --------------------------------------------------------

--
-- 表的结构 `m_tlist`
--

CREATE TABLE `m_tlist` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `qid` varchar(210) DEFAULT NULL,
  `kid` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_tlist`
--

INSERT INTO `m_tlist` (`id`, `uid`, `qid`, `kid`, `sid`) VALUES
(1, 5, '[2,2,2,2,2]', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `point_get`
--

CREATE TABLE `point_get` (
  `id` int(11) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `point` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `point_get`
--

INSERT INTO `point_get` (`id`, `name`, `point`) VALUES
(1, '分享积分', 1233),
(2, '满分积分', 10);

-- --------------------------------------------------------

--
-- 表的结构 `posters`
--

CREATE TABLE `posters` (
  `id` tinyint(4) NOT NULL,
  `pic` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `posters`
--

INSERT INTO `posters` (`id`, `pic`) VALUES
(1, 'http://192.168.1.109/english/public/uploads/pic/5c1763836352e.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `q_list`
--

CREATE TABLE `q_list` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `qid` varchar(60) DEFAULT NULL,
  `sid` varchar(90) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `q_list`
--

INSERT INTO `q_list` (`id`, `uid`, `qid`, `sid`, `type`) VALUES
(1, 5, '[]', '1', 1),
(2, 5, '[]', '1', 2),
(3, 5, '[]', '[1]', 1),
(4, 5, '[]', '[5,1]', 1),
(5, 5, '[]', '5', 1),
(6, 5, '[]', '[5]', 1),
(7, 5, '[4,1]', '[1,9]', 1),
(8, 5, '[]', '9', 1),
(9, 5, '[]', '9', 2),
(10, 5, '[]', '[1,9]', 2);

-- --------------------------------------------------------

--
-- 表的结构 `q_tlist`
--

CREATE TABLE `q_tlist` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `qid` varchar(300) DEFAULT NULL,
  `kid` int(11) DEFAULT NULL COMMENT '任务id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `sign_in`
--

CREATE TABLE `sign_in` (
  `id` tinyint(4) NOT NULL,
  `start` tinyint(4) DEFAULT NULL,
  `end` tinyint(4) DEFAULT NULL,
  `rate` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sign_in`
--

INSERT INTO `sign_in` (`id`, `start`, `end`, `rate`) VALUES
(1, 1, 10, 1),
(2, 11, 20, 2),
(3, 21, 30, 3);

-- --------------------------------------------------------

--
-- 表的结构 `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `num` smallint(6) DEFAULT NULL COMMENT '做题总数',
  `name` varchar(90) DEFAULT NULL,
  `q_state` tinyint(4) DEFAULT NULL COMMENT '1选择  2填空',
  `end` varchar(30) DEFAULT NULL COMMENT '结束时间',
  `sid` varchar(600) DEFAULT NULL,
  `start` varchar(30) DEFAULT NULL COMMENT '开始时间',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `limit_time` int(11) DEFAULT NULL COMMENT '单题限时',
  `state` tinyint(4) DEFAULT '0' COMMENT '0未通知  1已通知'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `task`
--

INSERT INTO `task` (`id`, `num`, `name`, `q_state`, `end`, `sid`, `start`, `create_time`, `limit_time`, `state`) VALUES
(1, 5, 'test', 1, '2019-12-30', '[1]', '2018-12-21', '2019-01-23 03:51:36', 10, 1);

-- --------------------------------------------------------

--
-- 表的结构 `task_score`
--

CREATE TABLE `task_score` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `re_time` smallint(6) DEFAULT '0',
  `shortest_time` int(11) DEFAULT '0',
  `start` varchar(15) DEFAULT NULL,
  `num` smallint(6) DEFAULT '0',
  `kid` int(11) DEFAULT NULL COMMENT '任务id',
  `score` decimal(5,1) DEFAULT '0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `total_score`
--

CREATE TABLE `total_score` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `total_score` decimal(5,1) DEFAULT NULL COMMENT '总分',
  `ave_time` decimal(10,1) DEFAULT NULL COMMENT '平均重复次数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `total_score`
--

INSERT INTO `total_score` (`id`, `uid`, `tid`, `total_score`, `ave_time`) VALUES
(1, 5, 1, '0.0', '17.5'),
(2, 5, 9, '300.0', '3.5');

-- --------------------------------------------------------

--
-- 表的结构 `units_score`
--

CREATE TABLE `units_score` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `score` decimal(5,1) DEFAULT '0.0',
  `sid` varchar(90) DEFAULT NULL COMMENT '单元id',
  `re_time` smallint(6) DEFAULT '0' COMMENT '重复次数',
  `shortest_time` int(11) DEFAULT '0' COMMENT '最短用时',
  `start` varchar(15) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '1选择  2填空',
  `num` smallint(6) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `units_score`
--

INSERT INTO `units_score` (`id`, `uid`, `score`, `sid`, `re_time`, `shortest_time`, `start`, `type`, `num`) VALUES
(1, 5, '50.0', '[1]', 2, 9, '1551086160', 1, 1),
(2, 5, '0.0', '[5,1]', 2, 5, '1551086293', 1, 0),
(3, 5, '200.0', '[5]', 0, 2, '1551082556', 1, 2),
(4, 5, '0.0', '[1,9]', 1, 7, '1551084043', 1, 0),
(5, 5, '33.3', '[1,9]', 0, 8, '1551152794', 2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `unit_score`
--

CREATE TABLE `unit_score` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `score` decimal(5,1) DEFAULT '0.0',
  `sid` varchar(90) DEFAULT NULL COMMENT '单元',
  `re_time` smallint(6) DEFAULT '0' COMMENT '重复次数',
  `shortest_time` int(11) DEFAULT '0' COMMENT '最短用时',
  `start` varchar(15) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '1 选择   2填空',
  `num` smallint(6) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `unit_score`
--

INSERT INTO `unit_score` (`id`, `uid`, `tid`, `score`, `sid`, `re_time`, `shortest_time`, `start`, `type`, `num`) VALUES
(1, 5, 1, '0.0', '1', 26, 3, '1551152690', 1, 0),
(2, 5, 1, '0.0', '1', 9, 2, '1551152701', 2, 0),
(3, 5, 5, '200.0', '5', 3, 2, '1551081217', 1, 0),
(4, 5, 9, '200.0', '9', 3, 6, '1551152610', 1, 0),
(5, 5, 9, '100.0', '9', 4, 3, '1551152784', 2, 0);

-- --------------------------------------------------------

--
-- 表的结构 `userinfo`
--

CREATE TABLE `userinfo` (
  `id` int(11) NOT NULL,
  `pic` varchar(250) DEFAULT NULL,
  `nickname` varchar(36) DEFAULT NULL,
  `grade` varchar(15) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `sex` tinyint(4) DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL COMMENT '收货地址',
  `sign` tinyint(4) DEFAULT '0' COMMENT '签到',
  `point` int(11) DEFAULT '0',
  `id_card` varchar(20) DEFAULT NULL,
  `openid` varchar(60) DEFAULT NULL,
  `sign_token` varchar(15) DEFAULT '0',
  `name` varchar(60) DEFAULT NULL,
  `share_token` varchar(15) DEFAULT '0',
  `identity` varchar(60) DEFAULT NULL COMMENT '身份',
  `date` bigint(20) DEFAULT '0' COMMENT '登录日'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `userinfo`
--

INSERT INTO `userinfo` (`id`, `pic`, `nickname`, `grade`, `phone`, `sex`, `address`, `sign`, `point`, `id_card`, `openid`, `sign_token`, `name`, `share_token`, `identity`, `date`) VALUES
(1, 'https://gss3.bdstatic.com/7Po3dSag_xI4khGkpoWK1HF6hhy/baike/c0%3Dbaike180%2C5%2C5%2C180%2C60/sign=16778565ae014c080d3620f76b12696d/0df3d7ca7bcb0a460d4c83f56c63f6246a60af94.jpg', '胡歌', '高一', '18888888888', 1, 'testtest', 0, 1, 'sssssss', 'test', '0', '胡歌', NULL, NULL, 0),
(5, 'https://wx.qlogo.cn/mmopen/vi_32/NCwlCWCMDX7s94CM1icwptM1HQIQ34WBAHeUuFIibI8UFsGviaic4rMU6lAdYwpg5e9Bj3icRo5Tn0PIibkQ3oA3OuOA/132', 'ZIMING_XIE', '四年级', '13169650909', 1, '好希望你看这招拆招女婿喜欢吃', 0, 121, '440181199606060808', 'o6Rw75BNvWBucdt38Yoy_TmaSqA4', '1545616377', '哈哈哈', '0', NULL, 1551152782),
(3, 'https://wx.qlogo.cn/mmopen/vi_32/3SfsicNn4qztAibpjfLwUIxcicJk2f1vdtNOTFt4I0MKm8rSYVhDYMS2BOqaYESPfZMxZWfxNG9GZFrDic0Yib699rw/132', '文娟?', '一年级', '13567890987', 2, '公关部b', 0, 0, '123466798547899', 'o6Rw75L6WZsn3Vs8qjGUgVwz451w', '0', '的时候', '0', NULL, 0),
(4, 'https://wx.qlogo.cn/mmopen/vi_32/uw5hNE4RMLyicXRy2NSbWRMgZBjQ1ibmBz1aMIL5NaPV0aOWV6ZuQ548vqu5vbfBWQA7aaV5UaNUvAv21OiavQwfA/132', '宇瞳', '高二', '13169640600', 1, 'qeed', 0, 0, '440181199505050505', 'o6Rw75La8kFw18m3GFchE03i-DHo', '0', '哈哈哈', '0', NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` smallint(6) NOT NULL,
  `username` varchar(15) DEFAULT NULL,
  `password` varchar(90) DEFAULT NULL,
  `remember_token` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `remember_token`) VALUES
(1, 'admin', '$2y$10$qq42mTQTyV3XXGEd2DNXX.9DT9O.NmwLAdtUTl7Wy6OOeNAsUVRQG', 'V5Q4aAQRMQrhnWwV2xlM9SWZc'),
(3, 'test', '$2y$10$6VwEsAMRCmVUJBqo.eq5DOmUXA5riTaKn6GHuvumAhwyWqfFuVKDS', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book_question`
--
ALTER TABLE `book_question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_system`
--
ALTER TABLE `book_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_title`
--
ALTER TABLE `book_title`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `figure`
--
ALTER TABLE `figure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift`
--
ALTER TABLE `gift`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_order`
--
ALTER TABLE `gift_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `midware`
--
ALTER TABLE `midware`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mistake`
--
ALTER TABLE `mistake`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_list`
--
ALTER TABLE `m_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_tlist`
--
ALTER TABLE `m_tlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_get`
--
ALTER TABLE `point_get`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posters`
--
ALTER TABLE `posters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `q_list`
--
ALTER TABLE `q_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `q_tlist`
--
ALTER TABLE `q_tlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sign_in`
--
ALTER TABLE `sign_in`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_score`
--
ALTER TABLE `task_score`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `total_score`
--
ALTER TABLE `total_score`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units_score`
--
ALTER TABLE `units_score`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit_score`
--
ALTER TABLE `unit_score`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `book_question`
--
ALTER TABLE `book_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `book_system`
--
ALTER TABLE `book_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `book_title`
--
ALTER TABLE `book_title`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `document`
--
ALTER TABLE `document`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `figure`
--
ALTER TABLE `figure`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `gift`
--
ALTER TABLE `gift`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `gift_order`
--
ALTER TABLE `gift_order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `grade`
--
ALTER TABLE `grade`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `midware`
--
ALTER TABLE `midware`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `mistake`
--
ALTER TABLE `mistake`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `m_list`
--
ALTER TABLE `m_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `m_tlist`
--
ALTER TABLE `m_tlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `point_get`
--
ALTER TABLE `point_get`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `posters`
--
ALTER TABLE `posters`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `q_list`
--
ALTER TABLE `q_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `q_tlist`
--
ALTER TABLE `q_tlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `sign_in`
--
ALTER TABLE `sign_in`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `task_score`
--
ALTER TABLE `task_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `total_score`
--
ALTER TABLE `total_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `units_score`
--
ALTER TABLE `units_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `unit_score`
--
ALTER TABLE `unit_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
