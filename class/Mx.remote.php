<?php
        if (!empty($GLOBALS['etc']['service_sys_url_' . $this->_system])) {
            $baseUrl = $GLOBALS['etc']['service_sys_url_' . $this->_system];
            $serviceUrl = $baseUrl . $this->_path . '/' . $name;
            $args = array('return' => true);
            if ($this->_headers) {
                $args['headers'] = $this->_headers;
            }
            if (!empty($this->_params)) {
                $arguments = array_merge($this->_params, $arguments[0]);
            } else {
                $arguments = $arguments[0];
            }
            $result = sfopen_url($serviceUrl, $arguments, 'post', $args);
            $result = json_decode($result, true);
            if (empty($result)) {
                $this->setErrorCode(100503)->setError('返回数据异常！');
                return null;
            }
            if ($result['result'] != 1) {
                $this->setErrorCode(100500)->setError('请求失败：' . $result['message']);
                return null;
            }
            return $result['data'];
        } else {
            $this->setErrorCode(100404)->setError('没有找到系统配置：' . $this->_system);
            return null;
        }