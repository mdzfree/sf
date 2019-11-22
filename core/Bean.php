<?php
//获取指定表的指定列唯一下标指定下标指定列值，
//例如：table->field->keyValue[->fieldValue,->order('id desc'),->group('id'),->andWhere('id' => 1),->orWhere('status' => 1),->toArray([field1, filed2]),->getAll([field1, filed2])]
/*
    $this->users->UserID->$value['UserID']->toArray('UserID', 'UserName')//获取最新的users表数据且以UserID作为KEY下标且下标值为1的第一条记录的数组，并且只要UserID和UserName列
    $this->users->UserCode->admin->UserName//获取users表以UserCode作为KEY下标且下标值为admin的第一条记录的UserName值
    $this->users->UserCode->admin->getAll()//获取users表以UserCode作为KEY下标且下标值都为admin的数组集合
    $this->users->UserCode->admin->order('id desc')->getAll()//获取users表以UserCode作为KEY下标且下标值都为admin按id降序的数组集合
    $this->users->UserCode->admin->group('id desc')->getAll()//获取users表以UserCode作为KEY下标且下标值都为admin按id分组的数组集合
    this->users->UserCode->admin->getTotal()//获取users表以UserCode作为KEY下标且下标值都为admin的记录数
    $value = array('UserID' => 1);
    $this->users->UserID->$value['UserID']->UserName//获取users表以UserID作为KEY下标且下标值为1的第一条记录的UserName值
    $this->users->UserID->$value['UserID']->andWhere('status', 1)->UserName//获取users表以UserID作为KEY下标且下标值为1且状态为1的第一条记录的UserName值
 */
    /**
    *   内存数据表列索引器
    */
    class _Bean_Value
    {
        private $_field;
        private $_value;
        public function __construct($field, $value)
        {
            $this->_field = $field;
            $this->_value = $value;
        }
        public function __get($field)
        {
            $this->_field->_table->_bean->setCols(array($field));
            $data = $this->_field->_table->_bean->where($this->_field->_name, $this->_value)->getRow();
            if (array_key_exists($field, $data)) {
                return $data[$field];
            }
            return null;
        }

        public function toArray()
        {
            $cols = func_get_args();
            if (!empty($cols)) {
                $this->_field->_table->_bean->setCols($cols);
            }
            return $this->_field->_table->_bean->where($this->_field->_name, $this->_value)->getRow();
        }

        public function toArrayCache()
        {
            $cols = func_get_args();
            if (!empty($cols)) {
                $this->_field->_table->_bean->setCols($cols);
            }
            return $this->_field->_table->_bean->where($this->_field->_name, $this->_value)->getRowCache();
        }

        public function getAll()
        {
            $cols = func_get_args();
            if (!empty($cols)) {
                $this->_field->_table->_bean->setCols($cols);
            }
            return $this->_field->_table->_bean->where($this->_field->_name, $this->_value)->getAll();
        }

        public function andWhere($field)
        {
            $args = func_get_args();
            if (count($args) == 2) {
                $value = $args[1];
                $this->_field->_table->_bean->where($field, $value);
            } else {
                $this->_field->_table->_bean->where($field);
            }
            return $this;
        }

        public function orWhere($field)
        {
            $args = func_get_args();
            if (count($args) == 2) {
                $value = $args[1];
                $this->_field->_table->_bean->orWhere($field, $value);
            } else {
                $this->_field->_table->_bean->orWhere($field);
            }
            return $this;
        }

        public function order($value)
        {
            $this->_field->_table->_bean->order($value);
            return $this;
        }

        public function group($value)
        {
            $this->_field->_table->_bean->group($value);
            return $this;
        }

        public function getAllCache()
        {
            $cols = func_get_args();
            if (!empty($cols)) {
                $this->_field->_table->_bean->setCols($cols);
            }
            return $this->_field->_table->_bean->where($this->_field->_name, $this->_value)->getAllCache();
        }

        public function getTotal()
        {
            return $this->_field->_table->_bean->where($this->_field->_name, $this->_value)->getTotal();
        }

        public function getTotalCache()
        {
            return $this->_field->_table->_bean->where($this->_field->_name, $this->_value)->getTotalCache();
        }
    }

    /**
    *   内存数据表列索引器
    */
    class _Bean_Field
    {
        public $_name;
        public $_table;
        public function __construct($name, $table)
        {
            $this->_name = $name;
            $this->_table = $table;
        }
        public function __get($id)
        {
            return new _Bean_Value($this, $id);
        }
    }
    /**
    *   内存数据表索引器
    */
    class _Bean_Table
    {
        public $_name;
        /**
         * @var Core_Bean
         */
        public $_bean;
        public function __construct($name, $bean)
        {
            $this->_name = $name;
            $this->_bean = $bean;
            $this->_bean->from($name);
        }

        public function getAll()
        {
            return $this->_bean->getAll();
        }

        public function __get($name)
        {
            return new _Bean_Field($name, $this);
        }
    }
    /**
    *
    */
    class Core_Bean extends Core_Model
    {
        public $_from;
        public $_cols;
        public $_tableName;
        public $_andWhere;
        public $_orWhere;
        public function __construct($name = null)
        {
            parent::__construct($name);
            if (empty($GLOBALS['__BeanCache'])) {
                $GLOBALS['__BeanCache'] = array();
            }
        }
        public function setCols($cols)
        {
            $this->_cols = $cols;
            return $this;
        }

        public function from($name)
        {
            $this->_from = $this->db->select()->from($name);
            $this->_tableName = $name;
        }

        public function where($field)
        {
            $args = func_get_args();
            if (count($args) == 2) {
                $value = $args[1];
                if (!empty($this->_orWhere)) {
                    $this->_orWhere->orWhere('`' . $field . '` = ?', $value);
                } else {
                    $this->_andWhere = $this->_from->where('`' . $field . '` = ?', $value);
                }
            } else {
                if (!empty($this->_orWhere)) {
                    $this->_orWhere->orWhere($field);
                } else {
                    $this->_andWhere = $this->_from->where($field);
                }
            }
            return $this;
        }

        public function orWhere($field, $value)
        {
            $args = func_get_args();
            if (count($args) == 2) {
                $value = $args[1];
                if (!empty($this->_andWhere)) {
                    $this->_andWhere->where('`' . $field . '` = ?', $value);
                } else {
                    $this->_orWhere = $this->_from->orWhere('`' . $field . '` = ?', $value);
                }
            } else {
                if (!empty($this->_andWhere)) {
                    $this->_andWhere->where($field);
                } else {
                    $this->_orWhere = $this->_from->orWhere($field);
                }
            }
            return $this;
        }


        public function order($value)
        {
            $this->_from->order($value);
            return $this;
        }

        public function group($value)
        {
            $this->_from->group($value);
            return $this;
        }

        public function getRow()
        {
            $sql = $this->_from->assemble();
            if (!empty($this->_cols)) {
                $colSql = '`' . implode('`, `', $this->_cols) . '`';
                $sql = str_replace('`' . $this->_tableName . '`.*', $colSql, $sql);
            }
            $sql .= ' limit 1';
            $this->_cols = null;
            return $this->db->fetchRow($sql);
        }

        public function getRowCache()
        {
            $sql = $this->_from->assemble();
            if (!empty($this->_cols)) {
                $colSql = '`' . implode('`, `', $this->_cols) . '`';
                $sql = str_replace('`' . $this->_tableName . '`.*', $colSql, $sql);
            }
            $this->_cols = null;
            $key = 'row_' . md5($sql);
            if (empty($GLOBALS['__BeanCache'][$key])) {
                $GLOBALS['__BeanCache'][$key] = $this->db->fetchRow($sql);
            }
            return $GLOBALS['__BeanCache'][$key];
        }

        public function getAll()
        {
            $sql = $this->_from->assemble();
            if (!empty($this->_cols)) {
                $colSql = '`' . implode('`, `', $this->_cols) . '`';
                $sql = str_replace('`' . $this->_tableName . '`.*', $colSql, $sql);
            }
            $this->_cols = null;
            return $this->db->fetchAll($sql);
        }

        public function getAllCache()
        {
            $sql = $this->_from->assemble();
            if (!empty($this->_cols)) {
                $colSql = '`' . implode('`, `', $this->_cols) . '`';
                $sql = str_replace('`' . $this->_tableName . '`.*', $colSql, $sql);
            }
            $this->_cols = null;
            $key = 'all_' . md5($sql);
            if (empty($GLOBALS['__BeanCache'][$key])) {
                $GLOBALS['__BeanCache'][$key] = $this->db->fetchAll($sql);
            }
            return $GLOBALS['__BeanCache'][$key];
        }

        public function getTotal()
        {
            $sql = $this->_from->assemble();
            $sql = str_replace('`' . $this->_tableName . '`.*', 'count(1)', $sql);
            return $this->db->fetchOne($sql);
        }

        public function getTotalCache()
        {
            $sql = $this->_from->assemble();
            $sql = str_replace('`' . $this->_tableName . '`.*', 'count(1)', $sql);
            $key = 'total_' . md5($sql);
            if (empty($GLOBALS['__BeanCache'][$key])) {
                $GLOBALS['__BeanCache'][$key] = $this->db->fetchOne($sql);
            }
            return $GLOBALS['__BeanCache'][$key];
        }

        public function table($name)
        {
            $name = strtolower($name);
            return new _Bean_Table($name, $this);
        }

        /**
         * @param $name
         * @return _Bean_Table
         */
        public function __get($name)
        {
            $name = strtolower($name);
            return new _Bean_Table(DB_TABLE_PREFIX . $name, $this);
        }
    }