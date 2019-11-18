<?php
    class Base_DefaultController extends Core_Controller
    {
        public function serviceAction()
        {
            self::$request->setExtensionFunction('json');
            if (!empty($_REQUEST['path'])) {
                $paths = explode('/', $_REQUEST['path']);
                if (empty($paths[0])) {
                    array_splice($paths, 0, 1);
                }
                $len = count($paths);
                $className = ucfirst($paths[0]) . '_';
                $name = $paths[1];
                if ($len > 2) {
                    $className .= $paths[1];
                    $name = $paths[2];
                } else {
                    $className .= 'Default';
                }
                $className .= 'Service';
                if (class_exists($className)) {
                    $object = sfget_instance($className);
                    if (method_exists($object, $name)) {
                        $arguments = file_get_contents('php://input');
                        $data = call_user_func_array(array($object, $name), json_decode($arguments, true));
                        sfresponse(1, '', $data);
                    }
                }
                sfresponse(0, '服务不存在！');
            } else {
                sfresponse(0, '缺少模块路径');
            }
        }
    }