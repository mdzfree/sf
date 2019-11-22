<?php

    /**
     * Class Core_Union  链接表，用于数据不大的表与主表联合（不支持链表条件）
     */
    class Core_Union extends Core_Base
    {

        /**
         * @var array 多表（外键表）联合数据集合，可缓存
         */
        public $tableDatas = array();

        /**
         * @var array 最后处理的数据集合
         */
        private $lastDatas = array();

        private function initTable($name, $idKey = 'id')
        {
            $db = Core_Model::instance();
            if (!isset($this->tableDatas[$name][$idKey])) {
                $cacheKey = $name . '__' . $idKey;
                $cacheDatas = $this->getLocal($cacheKey);//获取本地缓存，更高级应用可改为redis等
                if (!empty($cacheDatas)) {
                    $this->tableDatas[$name][$idKey] = $cacheDatas;
                } else {
                    $this->tableDatas[$name][$idKey] = array();
                    $db->tableName = $name;
                    $datas = $db->queryList();
                    foreach ($datas as $value) {
                        if (empty($value[$idKey])) {
                            $this->tableDatas[$name][$idKey][] = $value;
                        } else {
                            $this->tableDatas[$name][$idKey][$value[$idKey]] = $value;
                        }
                    }
                    $this->setLocal($cacheKey, $this->tableDatas[$name][$idKey]);
                }
            }
            return !empty($this->tableDatas[$name][$idKey]);
        }

        /**
         * @param $datas
         * @return Core_Union
         */
        public function select($datas)
        {
            $this->lastDatas = $datas;
            return $this;
        }

        /**
         * 把外键表合并到数据里
         * @param string $rightName 外键表名
         * @param string $rightId  外键表与主数据的关联列名
         * @param string $leftId 主数据里与外联数据的外键ID列名
         * @param array $fields 需要的外键列集合
         * @return $this
         */
        public function join($rightName, $rightId, $leftId, $fields = array())
        {
            if (empty($this->lastDatas) || !$this->initTable($rightName, $rightId)) {
                return $this;
            }
            $rightDatas = $this->tableDatas[$rightName][$rightId];
            if ($fields == array()) {
                $data = current($rightDatas);
                $fields = array_keys($data);
            }
            foreach ($this->lastDatas as $key => $value) {
                foreach ($fields as $k => $v) {
                    $leftField = $v;
                    $rightField = $v;
                    if (!is_numeric($k)) {
                        $rightField = $k;
                        $leftField = $v;
                    }
                    //主数据外联ID为空  或者 外联数据没有id数据 则跳过
                    if (empty($value[$leftId]) || empty($rightDatas[$value[$leftId]])) {
                        continue;
                    }
                    $this->lastDatas[$key][$leftField] = $rightDatas[$value[$leftId]][$rightField];
                }
            }
            return $this;
        }

        /**
         * 获取当前处理后的结果
         * @return array
         */
        public function getAll()
        {
            return $this->lastDatas;
        }


        /**
         * 获取外联表条件的指定值
         * @param string $rightName 外联表名
         * @param string $rightId   外联表唯一值列名
         * @param string $rightValue 获取的唯一值
         * @return array|null
         */
        public function getValue($rightName, $rightId, $rightValue)
        {
            if ($this->initTable($rightName, $rightId)) {
                return $this->tableDatas[$rightName][$rightId][$rightValue];
            }
            return null;
        }
    }