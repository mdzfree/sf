<?php

    /**
     * 微服务核心类
     */
    class Mx extends Core_Base
    {
        private $_system = 'local';
        private $_path;
        private $_headers = array();
        private $_params = array();

        public function system($system)
        {
            $this->_system = $system;
            return $this;
        }

        public function header($headers)
        {
            $this->_headers = $headers;
            return $this;
        }

        public function param($params)
        {
            $this->_params = $params;
            return $this;
        }

        public function __call($name, $arguments)
        {
            $systemFile = dirname(__FILE__) . DS . 'Mx.' . $this->_system . '.php';
            if (is_file($systemFile)) {
                return include $systemFile;
            } else {
                $systemFile = dirname(__FILE__) . DS . 'Mx.remote.php';
                return include $systemFile;
            }
        }

        public function service($name)
        {
            $this->_path = $name;
            return $this;
        }

        public function config($path)
        {
            return $path;
        }
    }