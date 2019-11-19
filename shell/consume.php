<?php
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    $mq = Core_Mq::instance();
    echo 'event:' . $mq->exeConsume('event') . '<br />';
    echo 'path:' . $mq->exeConsume('path') . '<br />';