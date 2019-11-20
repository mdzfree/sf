<?php
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    $mq = Core_Mq::instance();
?>
<html>
<head><?php echo !empty($_REQUEST['auto']) ? '<meta http-equiv="refresh" content="3">' : '' ?></head>
<body>
<?php
    echo 'event:' . $mq->execTask('event') . '<br />';
    echo 'path:' . $mq->execTask('path') . '<br />';
    echo 'export:' . $mq->execTask('export', 1) . '<br />';
?>
</body>
</html>
