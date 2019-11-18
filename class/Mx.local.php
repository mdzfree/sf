<?php
    $useLocal = true;
    $paths = explode('/', $this->_path);
    if (empty($paths[0])) {
        array_splice($paths, 0, 1);
    }
    $len = count($paths);
    $className = $paths[0] . '_';
    if (substr($this->_path, 0, 1) != '/') {
        $className = 'Aa' . $className . ucfirst($paths[1]) . '_';
    } else {
        $className = $paths[0] . '_';
    }
    if ($len > 2) {
        $className .= $paths[2];
    } else {
        $className .= 'Default';
    }
    $className .= 'Service';
    if (class_exists($className)) {
        $object = sfget_instance($className);
        if (method_exists($object, $name)) {
            return call_user_func_array(array($object, $name), $arguments);
        }
    } else {
        $useLocal = false;
    }
    if (!$useLocal) {
        if (!empty($GLOBALS['etc']['service_base_url_' . $this->_system])) {
            $baseUrl = $GLOBALS['etc']['service_base_url_' . $this->_system];
            $serviceUrl = $baseUrl . '/base/service?path=' . $this->_path . '/' . $name;
            $result = sfopen_url($serviceUrl, json_encode($arguments), 'post', array('return' => true));
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
        }
    }