<?php
    list($usec, $sec)   =   explode(' ', microtime());
    define('BEGIN_TIME', ((float)$usec + (float)$sec));
    require 'include.php';
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