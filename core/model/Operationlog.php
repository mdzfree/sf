<?php

    /**
     * 日志类  by mengdz  20190223
     * VERSION: v1.0
     * QQ:83398609
     */
    class SFLog
    {
        private $curMonth;//当前分月（按季）
        private $dir;//日志目录
        private $type;//日志类型
        private $index;//日志索引
        private $file;//日志文件
        private $user = null;//操作员标识
        private $_dbs;

        /**
         * 确保目录存在
         */
        public function __construct($name, $host, $user, $pswd)
        {
            $this->curMonth = intval(date('m') / 3) + 1;
            $this->_dbs = array(mysql_connect($host, $user, $pswd));
            if ($this->_dbs[0]) {
                mysql_select_db($name, $this->_dbs[0]);
                mysql_query('SET NAMES UTF8MB4', $this->db());
            }
        }

        /**
         * 设置添加者/操作者
         * @param string $name 添加操作者标识
         */
        public function setUser($value)
        {
            $this->user = $value;
            return $this;
        }

        /**
         * 获取来访者IP
         * @return string ip地址
         */
        public function getIP()
        {
            if (isset($_SERVER)) {
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                } else {
                    $realip = $_SERVER['REMOTE_ADDR'];
                }
            } else {
                if (getenv("HTTP_X_FORWARDED_FOR")) {
                    $realip = getenv("HTTP_X_FORWARDED_FOR");
                } elseif (getenv("HTTP_CLIENT_IP")) {
                    $realip = getenv("HTTP_CLIENT_IP");
                } else {
                    $realip = getenv("REMOTE_ADDR");
                }
            }
            return empty($realip) ? '-' : $realip;
        }

        /**
         * DB对象
         * @return object 数据库DB数据
         */
        public function db()
        {
            return $this->_dbs[0];
        }

        /**
         * 判断数据库文件是否存在，不存在则创建
         * @return boolean 文件是否成功创建或存在
         */
        public function isExists()
        {
            return true;
        }

        /**
         * KEY转换为三位数字，用于索引编码
         * @param string $key key
         * @return string     三位编码数字
         */
        public function key2number($key)
        {
            $h = sprintf("%u", crc32($key));
            $h1 = intval(fmod($h, 100));
            switch (strlen($h1)) {
                case 1:
                    return '0' . $h1;
                    break;
            }
            return $h1;
        }

        /**
         * 根据日志数据获取DATA KEY
         * @param string $data 日志数据
         * @return string       DATA KEY
         */
        public function getDataKey($data)
        {
            $tableName = DB_TABLE_PREFIX . 'data_' . $index;
            $index = md5($data);
            $index = $this->key2number($index);
            $key = 'k__' . $this->type . '_' . $this->index . '__' . $index . '__' . uniqid();
            $sql = 'INSERT INTO ' . $tableName . '(id, `key`, content) VALUES(NULL, "%s", \'%s\')';
            $sql = sprintf($sql, $key, $data);
            try {
                $result = mysql_query($sql, $this->db());
                if (!$result) {
                    throw new Exception();
                }
            } catch (Exception $e) {
                $cSql = 'CREATE TABLE IF NOT EXISTS `' . $tableName . '` (`id` int(11) NOT NULL AUTO_INCREMENT,  `key` varchar(200) NOT NULL,  `content` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
                mysql_query($cSql, $this->db());
                $result = mysql_query($sql, $this->db());
            }
            return $result ? $key : null;
        }

        /**
         * DB插入数据
         * @param string type 日志类型，例如 A 管理员，U 用户，API 接口 。。。
         * @param string key 关键值，例如 管理员ID 1，API 20190214单号 。。。
         * @param string content 日志内容，描述该日志的文本
         * @param string data 日志数据，会转换为json格式
         * @param array param  扩展参数
         * @return int         影响行数
         */
        public function insert($type, $key, $content, $data, $param)
        {
            $tableName = DB_TABLE_PREFIX . 'log_' . $this->type . '_' . $this->index;
            if (!empty($data)) {
                $json = json_decode($data, true);
                if (is_array($json)) {
                    $data = $this->getDataKey($data);
                }
            }
            $this->type = strtolower($type);
            $this->index = $this->key2number($key);
            $sql = 'INSERT INTO ' . $tableName . '(id, type, `key`, content, data, ip, `add`, `create`) VALUES(NULL, "%s", "%s", "%s", "%s", "%s", "%s", NOW())';
            $sql = sprintf($sql, mysql_escape_string($type), mysql_escape_string($key), mysql_escape_string($content), mysql_escape_string($data), $this->getIP(), mysql_escape_string($this->user));
            try {
                $result = mysql_query($sql, $this->db());
                if (!$result) {
                    throw new Exception('插入失败：' . $sql);
                }
            } catch (Exception $e) {
                $cSql = 'CREATE TABLE IF NOT EXISTS `' . $tableName . '` (`id` int(11) NOT NULL AUTO_INCREMENT,  `type` varchar(200) NOT NULL,  `key` varchar(200) NOT NULL,  `content` text NOT NULL,  `data` text DEFAULT NULL,  `ip` varchar(30) NOT NULL,  `add` varchar(200) DEFAULT NULL,  `create` datetime NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
                mysql_query($cSql, $this->db());
                $result = mysql_query($sql, $this->db());
            }
            return $result;
        }

        /**
         * DB查询数据
         * @param string $type 日志类型
         * @param string $key 日志key
         * @return array       返回的日志集合
         */
        public function select($type, $key)
        {
            $this->type = strtolower($type);
            $this->index = $this->key2number($key);
            $tableName = DB_TABLE_PREFIX . 'log_' . $this->type . '_' . $this->index;
            $sql = sprintf('SELECT * FROM ' . $tableName . ' WHERE type = "%s" AND `key` = "%s" ORDER BY id DESC', mysql_escape_string($type), mysql_escape_string($key));
            $result = mysql_query($sql, $this->db());
            $datas = array();
            if ($result) {
                while ($ret = mysql_fetch_assoc($result)) {
                    $datas[] = $ret;
                }
            }
            return $datas;
        }

        /**
         * 添加日志
         * @param string type 日志类型，例如 A 管理员，U 用户，API 接口 。。。
         * @param string key 关键值，例如 管理员ID 1，API 20190214单号 。。。
         * @param string content 日志内容，描述该日志的文本
         * @param string data 日志数据，会转换为json格式
         * @param array param  扩展参数
         * @return int         影响行数
         */
        public function add($type, $key, $content, $data = null, $param = array())
        {
            $this->type = strtolower($type);
            $this->index = $this->key2number($key);
            if ($this->isExists()) {
                return $this->insert(strtoupper($type), $key, $content, $data, $param);
            } else {
                return '日志文件不存在且无法创建！';
            }
        }

        /**
         * 查询日志
         * @param string $type 日志类型
         * @param string $key 日志key
         * @return array       返回的日志集合
         */
        public function query($type, $key)
        {
            $this->type = strtolower($type);
            $this->index = $this->key2number($key);
            if ($this->isExists()) {
                return $this->select(strtoupper($type), $key);
            } else {
                return '没有数据';
            }
        }

        /**
         * 根据DATA KEY 获取DATA数据
         * @param string $key log日志中的data key
         * @return string      返回的data数据
         */
        public function getDataByKey($key)
        {
            if (!$key || substr($key, 0, 3) != 'k__') {
                return $key;
            }
            list($k, $l, $d, $i) = explode('__', $key);
            list($type, $index) = explode('_', $l);
            $this->type = $type;
            $this->index = $index;
            $sql = 'SELECT * FROM data_' . $d . ' WHERE `key` = "' . $key . '" LIMIT 1';
            $result = mysql_query($sql, $this->db());
            $row = mysql_fetch_array($result);
            return $row['content'];
        }

        /**
         * 关闭链接
         */
        public function __destruct()
        {
            foreach ($this->_dbs as $key => $value) {
                mysql_close($value);
            }
        }
    }
    /**
    *
    */
    class Core_Model_Operationlog extends Core_Model
    {
        private $log;
        public function __construct()
        {
            parent::__construct();
            $this->log = new SFLog(DB_NAME, DB_HOST, DB_USER, DB_PASSWORD);
        }
        public function queryOperationlog($param = array())
        {
            if (!sfget_valid_one($param, array('type'))) {
                return array();
            } else {
                if (is_array($param['type']) && count($param['type']) == 1) {
                    $param['type'] = current($param['type']);
                }
            }

            return $this->log->query($param['type'], $param['code']);
        }

        public function insertOperationlog($type, $id, $content, $data = '', $adminID = null, $level = 'W')
        {
            return $this->log->setUser($adminID)->add($type, $id, $content, $data);
        }
    }