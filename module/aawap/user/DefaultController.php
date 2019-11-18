<?php
    class Aawap_User_DefaultController  extends Core_Controller
    {
        public function listAction()
        {
            self::$request->setExtensionFunction('json');
            if ($GLOBALS['headers']['Custom-Key'] != '123456') {
                sfresponse(0, '验证不合法！');
            }
            sfresponse(1, '', $_POST);
        }
    }