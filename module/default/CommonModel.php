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
    }