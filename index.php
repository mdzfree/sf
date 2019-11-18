<?php
    list($usec, $sec)   =   explode(' ', microtime());
    define('BEGIN_TIME', ((float)$usec + (float)$sec));
    error_reporting(E_ALL);
    $GLOBALS['__REQUEST'] = $_REQUEST;
    $localXML = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'local.xml';
    if (is_file($localXML)) {
        try {
            $xml = new SimpleXMLElement(file_get_contents($localXML));
            $GLOBALS['__LOCAL'] = json_decode(json_encode($xml), true);
        } catch (Exception $e) {
        }
    }
    require 'config.php';
    require 'vendor/autoload.php';
    require 'core/function.php';
    require 'var.php';
    ob_start();
    $request = sfget_instance('Core_Request');
    $actionName = $request->getAction();
    $c = sfget_instance($request->getControllerName());
    // 访问不存在的插件或不存在的方法，返回404
    if (is_object($c)) {
        if (method_exists($c, $actionName)) {
            if ($c->isCall($actionName)) {
                $GLOBALS['VIEW'] = $c->view;
                $c->$actionName();
            } else {
                $c->notAuthorizedAction();
            }
        } else {
            if ($c->hasCall()) {
                if ($c->isCall($actionName)) {
                    $GLOBALS['VIEW'] = $c->view;
                    $c->$actionName();
                } else {
                    $c->notAuthorizedAction();
                }
            } else {
                $c->notAuthorizedAction();
            }
        }
    } else {
        sf404();
    }
    sfquit();