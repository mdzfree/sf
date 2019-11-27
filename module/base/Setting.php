<?php
    class Base_Setting extends Core_Bean
    {
        private $_configList = array();
        public function __construct($name = null)
        {
            parent::__construct($name);
            $configList = Base_SettingBean::instance()->getList();
            foreach ($configList as $config) {
                $this->_configList[$config['code']] = $config;
            }
        }

        public function __get($name)
        {
            return $this->_configList[$name];
        }

    }