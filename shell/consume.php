<?php
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    $mq = Core_Mq::instance();
    echo 'event:' . $mq->consume('event') . '<br />';
    echo 'path:' . $mq->consume('path') . '<br />';