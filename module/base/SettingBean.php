<?php
    class Base_SettingBean extends Core_Bean
    {
        public function getList()
        {
            $datas = $this->setting->getAll();
            return $datas;
        }
        public function getListByGroup($name)
        {
            $datas = $this->setting->group->$name->getAll();
            return $datas;
        }

        public function getGroupNameByCode($code)
        {
            switch ($code) {
                case 'default':
                    return '默认设置';
                    break;
                default:
                    return $this->__($code);
            }
        }

        public function getGroupList()
        {
            return Base_SettingModel::instance()->findGroupList();
        }

        public function modifySetting($param)
        {
            if (sfis_valid($param, array('id' => 'array'))) {
                try {
                    $this->begin();
                    foreach ($param['id'] as $id => $value) {
                        $result = $this->db->update(DB_TABLE_PREFIX . 'setting', array('value' => $value, 'update_time' => date('Y-m-d H:i:s')), $this->db->quoteInto('id = ?', $id));
                        if (!$result) {
                            throw new Zend_Db_Adapter_Exception('修改配置项（' . $id . '）时失败！');
                        }
                    }
                    $this->commit();
                    return true;
                } catch (Exception $e) {
                    $this->rollBack();
                    return $this->setException($e);
                }
            } else {
                return $this->setErrorCode(100500)->setError('找不到修改的配置数据或格式异常！');
            }
        }
    }