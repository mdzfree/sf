<?php
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.debug.php';
    if (empty($_REQUEST['code'])) {
        sfquit('not found code param');
    }
    $file = ROOT_DIR . '/asset/internal/report/' . $_REQUEST['code'] . '.log';
    if (is_file($file)) {
        sfquit('<pre>' . Core_IoUtils::instance()->readFile($file) . '</pre>');
    } else {
        sfquit('not found：' . $_REQUEST['code']);
    }