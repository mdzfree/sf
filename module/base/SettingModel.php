<?php
    class Base_SettingModel extends Core_Model
    {
        public function findGroupList()
        {
            $base = $this->db->select()->from(DB_TABLE_PREFIX . 'setting', array('code' => 'group'));
            $base->group('group');
            $datas = $this->db->fetchAll($base);
            return $datas;
        }
    }