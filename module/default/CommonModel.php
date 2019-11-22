<?php
    /**
    *
    */
    class Default_CommonModel extends Core_Model
    {
        public function isPassword($value)
        {
            /*
            $select = $this->db->select()->from(DB_TABLE_PREFIX . 'test');
            $sql = $select->where('pass = ?', $value)->limit(1)->assemble();
            $data = $this->db->fetchRow($sql);
            if (!empty($data)) {
                return true;
            }
            */
            return false;
        }

        public function getList($param = array())
        {
            $base = $this->db->select()->from(DB_TABLE_PREFIX . 'list');
            if (!empty($param['mold_name'])) {
                $mold = Core_Union::instance()->getValue(DB_TABLE_PREFIX . 'mold', 'name', $param['mold_name']);
                if (!empty($mold)) {
                    $base->where('mold_id = ?', $mold['id']);
                } else {
                    $base->where('1=0');
                }
            }
            return $this->db->fetchAll($base);
        }
    }