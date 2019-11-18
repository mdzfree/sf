<?php
set_include_path(SYSTEM_LIBRARY_DIR);
require SYSTEM_LIBRARY_DIR . DS . 'Zend' . DS . 'Db.php';
/**
* 系统核心类 model
*/
class Core_Model extends Core_Base
{
    public $name;
    public $tableName;
    static private $globalDb;
    protected $db;
    protected $data;
    private $_language;
    function __construct($tableName = null, $config = null)
    {
        //parent::__construct();
        if ($tableName == null) {
            list($tableName, $action) = explode('_', get_class($this));
            $action = str_replace('Model', '', $action);
            if ($action == 'Common') {
                $tableName = strtolower($tableName);
            } else {
                $tableName = strtolower($tableName) . '_' . strtolower($action);
            }
        }
        $this->name = $tableName;
        $this->tableName = DB_TABLE_PREFIX . $tableName;
        if (!empty($config)) {
            if (!$this->loadConfig($config)) {
                $this->open();
            }
        } else {
            $this->open();
        }
        $this->data = array();
    }

    public static function isOpen()
    {
        return !empty(self::$globalDb);
    }

    protected function getCols($param, $cols = '*')
    {
        if ($cols == '*') {
            return '*';
        }
        if (is_string($cols)) {
            return explode(',', $cols);
        }
        return $cols;
    }

    /**
     * 系统的默认排序，指定表必须具备 post_time 和 sort 字段
     * @param Object $handler   SQL句柄
     * @param String $prefix    列前缀
     * @param Array $data   参数
     */
    protected function sort(&$handler, $prefix = null , $data = null)
    {
        if (empty($prefix)) {
            $prefix = $this->name;
        }
        if (empty($data['sort'])) {
            $handler->order($prefix . '__sort desc');
        }
        if (isset($data['post_time'])) {
            $handler->order($prefix . '__post_time ' . $data['post_time']);
        } else {
            $handler->order($prefix . '__id desc');
        }
    }

    public function setData($key, $value, $append = false)
    {
        if ($append !== false) {
            if (!empty($this->data[$key])) {
                if (is_array($this->data[$key])) {
                    $this->data[$key][] = $value;
                } else {
                    $this->data[$key] .= $value;
                }
            } else {
                $this->data[$key] = $value;
            }
        } else {
            $this->data[$key] = $value;
        }
    }

    public function getData($key)
    {
        return $this->data[$key];
    }

    public function existsData($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * 将同ID的多语言记录字段互补，生成新记录
     * @param   array $data  多语言记录
     * @return  array        处理后的记录
     */
    public function preDeal($data, $tableName)
    {
        $temp = array();
        $curLang = self::getCurLanguage();
        if (!empty($data)) {
            for ($i = 0, $len = count($data); $i < $len ; $i++) {
                if (array_key_exists($data[$i][$tableName . '_i18n__' . $tableName . '_id'], $temp)) {
                    foreach ($temp[$data[$i][$tableName . '_i18n__' . $tableName . '_id']] as $key => $value) {
                        if (empty($temp[$data[$i][$tableName . '_i18n__' . $tableName . '_id']][$key])) {
                            $temp[$data[$i][$tableName . '_i18n__' . $tableName . '_id']][$key] = $data[$i][$key];
                        }
                    }
                } else {
                    $temp[$data[$i][$tableName . '_i18n__' . $tableName . '_id']] = array();
                    if (!empty($data[$i]))
                    foreach ($data[$i] as $key => $value) {
                        $temp[$data[$i][$tableName . '_i18n__' . $tableName . '_id']][$key] = $value;
                    }
                }
            }
        }
        $temp = array_merge($temp);
        return $temp;
    }


    /**
     * 获取语言代码(没有设值就返回当前语言代码)，该方法会被继承，有默认调用的用处，
     * 也可在带调用model的时候 $model->setLanguageCode(?) 预处理获取指定语言数据
     */
    public function getLanguageCode()
    {

        if (empty($this->_language)) {

            $this->_language = self::getCurLanguageCode();
        }
        return $this->_language;
    }

    /**
     * 设置模块语言
     * @param String $code
     */
    public function setLanguageCode($code)
    {
        $this->_language = $code;
    }

    public function getI18n($tmp, $tableName, $data = null)
    {
        $tmp = $this->db->select()->from(array('foo' => $tmp))
                                    ->joinLeft(array('bar' => DB_TABLE_PREFIX . $tableName . '_i18n'), 'foo.' . $tableName . '__id = bar.' . $tableName . '_i18n__' . $tableName . '_id');


        $this->sort($tmp, $tableName, $data);
		$tmp->order(new Zend_Db_Expr('bar.' . $tableName . '_i18n__language = "' . $this->getLanguageCode() . '" DESC'));
		return $tmp;
    }

    function open()
    {
        if (!self::$globalDb && DB_NAME != 'test') {
            try {
                $config = array (
                    'host'     => DB_HOST,
                    'username' => DB_USER,
                    'password' => DB_PASSWORD,
                    'dbname'   => DB_PREFIX . DB_NAME
                );
                self::$globalDb = Zend_Db::factory('PDO_MYSQL', $config);
                if (self::$globalDb) {
                    self::$globalDb->query("SET NAMES UTF8");
                    self::$globalDb->query("SET time_zone = '" . SYS_TIME_ZONE . "'");
                    self::$globalDb->getProfiler()->setEnabled(true);
                    $this->db = self::$globalDb;
                    sfconsole('打开数据库！');
                } else {
                    sferror('Data connection not');
                }
            } catch (Exception $e) {
                sferror($e);
            }
        } else {
            if (self::$globalDb && !self::$globalDb->isConnected()) {
                sfconsole('连接被关闭，重新初始化！');
                self::$globalDb->query("SET NAMES UTF8");
                self::$globalDb->query("SET time_zone = '" . SYS_TIME_ZONE . "'");
                self::$globalDb->getProfiler()->setEnabled(true);
            }
            $this->db = self::$globalDb;
        }
    }

    function close()
    {
        if (self::$globalDb) {
            self::$globalDb->closeConnection();
            sfconsole('关闭数据库！');
        }
    }

    //重置服务器连接，慎用
    public function loadConfig($config)
    {
        $this->close();
        if (($key = sfis_valid($config, array('host', 'username', 'password', 'dbname'))) === true) {
            $error = '';
            try {
                $con = Zend_Db::factory('PDO_MYSQL', $config);
                if ($con) {
                    $con->query("SET NAMES UTF8");
                    $con->query("SET time_zone = '" . SYS_TIME_ZONE . "'");
                    $con->getProfiler()->setEnabled(true);
                    $this->close();
                    $this->db = $con;
                    self::$globalDb = $con;
                    return true;
                }
            } catch (Exception $e) {
                $error = '，' . $e->getMessage();
            }
            sfdebug('打开备用服务器失败：'. json_encode($config) . $error, 'system');
            return false;
        } else {
            return $this->setError('缺少必要参数：' . $key);
        }
    }

    public function reset()
    {
        $this->close();
        try {
            $config = array (
                'host'     => DB_HOST,
                'username' => DB_USER,
                'password' => DB_PASSWORD,
                'dbname'   => DB_PREFIX . DB_NAME
            );
            $con = Zend_Db::factory('PDO_MYSQL', $config);
            if ($con) {
                $con->query("SET NAMES UTF8");
                $con->query("SET time_zone = '" . SYS_TIME_ZONE . "'");
                $con->getProfiler()->setEnabled(true);
                $this->close();
                $this->db = $con;
                self::$globalDb = $con;
                return true;
            }
        } catch (Exception $e) {
            $error = '，' . $e->getMessage();
        }
        sfdebug('重置服务器连接失败：'. json_encode($config) . $error, 'system');
        return false;
    }

    public function switchDbByXML($xmlKey)
    {
        if (!empty($GLOBALS['__LOCAL'][$xmlKey]['host'])) {
            if (!empty($GLOBALS['__LOCAL'][$xmlKey]['password'])) {
                $GLOBALS['__LOCAL'][$xmlKey]['password'] = sfdestr($GLOBALS['__LOCAL'][$xmlKey]['password']);
            }
            $result = $this->loadConfig($GLOBALS['__LOCAL'][$xmlKey]);
            if (!$result) {
                $result = $this->reset();
                if (!$result) {
                    sferror();
                }
            }
        }
        return false;
    }

    /**
    * batchInsertOrUpdate
    * 此函数还在测试阶段慎用
    * 此函数用于批量添加或者更新，存在则更新，不存在则添加，需注意，此函数有局限性，认为重复的字段（一个或多个）需要创建唯一索 * 引或者联合索引
    * @version  1.0
    * @author  ff
    * @date  05-18
    * @access public
    * @param $name  表名
    * @param $datas  以数据库字段为键的数组键值对，并放入另一个索引数组中，这是一个二维数组
    * @since 1.0
    * @return array
    */
    public function batchInsertOrUpdate($name, $datas)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $keys = array();
        $vals = array();
        if (empty($datas) || !is_array($datas)) {
            return null;
        }
        reset($datas);
        $data = current($datas);
        $updateSql = '';
        foreach ($data as $key => $value) {
            $keys[] = $key;
            $updateSql .= $key . ' = VALUES (' . $key . '),';
        }
        foreach ($datas as $key => $value) {
            $row = array();
            for ($i = 0, $index = count($keys); $i < $index; $i++) {
                if (is_numeric(strpos($keys[$i], 'Date')) && !is_numeric(strpos($value[$keys[$i]], 'NOW'))) {
                    $row[] = date('Y-m-d H:i:s', strtotime($value[$keys[$i]]));
                } else {
                    $row[] = $this->db->quote($value[$keys[$i]]);
                }
            }
            $vals[] = $row;
        }
        $insertSql = 'INSERT INTO ' . $name . "(`" . implode("`, `", $keys) . "`) VALUES ";
        foreach ($vals as $key => $value) {
            $insertSql .= '(' . implode(', ', $value) . '), ';
        }
        $insertSql = str_replace('"NOW()"', 'NOW()', $insertSql);
        $insertSql = str_replace('"NULL"', 'NULL', $insertSql);
        $sql = rtrim($insertSql, ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($updateSql, ', ') . ';';
        return $this->db->exec($sql);
    }

    public function batch($name, $datas)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $keys = array();
        $vals = array();
        $data = current($datas);
        foreach ($data as $key => $value) {
            $keys[] = $key;
        }
        foreach ($datas as $key => $value) {
            $row = array();
            for ($i = 0, $index = count($keys); $i < $index; $i++) {
                if (is_numeric(strpos($keys[$i], 'Date')) && !is_numeric(strpos($value[$keys[$i]], 'NOW'))) {
                    $row[] = date('Y-m-d H:i:s', strtotime($value[$keys[$i]]));
                } else {
                    $row[] = addslashes($value[$keys[$i]]);
                }
            }
            $vals[] = $row;
        }
        $sql = 'INSERT INTO ' . $name . "(`" . implode("`, `", $keys) . "`) VALUES ";
        foreach ($vals as $key => $value) {
            $sql .= '("' . implode('", "', $value) . '"), ';
        }
        $sql = str_replace('"NOW()"', 'NOW()', $sql);
        $sql = str_replace('"NULL"', 'NULL', $sql);
        $sql = rtrim($sql, ', ') . ';';
        return $this->db->exec($sql);
    }

    public function into($tableName, $keys, $byBase)
    {
        $sql = 'INSERT INTO ' . $tableName . "(`" . implode("`, `", $keys) . "`) (" . $byBase->assemble() . ")";
        try {
            return $this->db->exec($sql);
        } catch (Exception $e) {
            return $this->setError($e->getMessage());
        }
    }

    public function query($param = array())
    {
        $base = $this->db->select()->from($this->tableName);
        foreach ($param as $key => $value) {
            $base->where($key . ' = ?', $value);
        }
        return $this->db->fetchRow($base);
    }


    public function insert($bind = array())
    {
        $result = $this->db->insert($this->tableName, $bind);
        if ($result) {
            return $this->db->lastInsertId($this->tableName);
        }
        return false;
    }

    public function update($newBind, $whereKV)
    {
        $where = '';
        if (is_array($whereKV)) {
            foreach ($whereKV as  $key => $value) {
                $where .= 'AND ' .$this->db->quoteInto('`' . $key . '` = ?', $value);
            }
            if (!empty($where)) {
                $where = substr($where, 4);
            }
            return $this->db->update($this->tableName, $newBind, $where);
        }
        return 0;//无条件不更新
    }

    public function delete($whereKV)
    {
        $where = '';
        if (is_array($whereKV)) {
            foreach ($whereKV as  $key => $value) {
                $where .= ' AND ' .$this->db->quoteInto('`' . $key . '` = ?', $value);
            }
            if (!empty($where)) {
                $where = substr($where, 5);
            }
            if (!empty($where)) {
                return $this->db->delete($this->tableName, $where);
            } else {
                return 0;//不允许无条件删除
            }
        }
        return 0;//无条件不更新
    }

    public function begin()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }
}