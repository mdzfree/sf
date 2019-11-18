<?php
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    $mx = Mx::instance();
    $mx->system('diffcoder');
    $mx->header(array('custom-key' => '123456'))->param(array('sign' => time()));
    $list = $mx->service('/user')->list(array('id' => 1));
    sfdump($list);
    if ($mx->hasError()) {
        sfquit($mx->getError());
    }
    $list = $mx->service('/user')->list(array('id' => 2));
    sfdump($list);

    if (Core_Model::isOpen()) {
        Core_Model::instance()->close();
    }