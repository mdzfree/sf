<?php
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.debug.php';
    $dirs = Core_IoUtils::instance()->scanDir(ROOT_DIR . '/asset/internal/debug', 1, null, true);
    $echoList = array();
    foreach ($dirs as $value) {
        $sDir = ROOT_DIR . '/asset/internal/debug/' . $value . '/' . date('Y-m-d') . '/' . date('H'). '/' . intval(date('i') / 10);
        if (is_dir($sDir)) {
            $list = Core_IoUtils::instance()->scanDir($sDir);
            foreach ($list as $v) {
                if (!isset($echoList[$value])) {
                    $echoList[$value] = array();
                }
                $echoList[$value][] = array($v, Core_IoUtils::instance()->readFile($sDir . '/' . $v));
            }
        }
    }
    
    foreach ($echoList as $key => $list) {
        ?>
        <h5><?php echo $key; ?></h5>
        <?php
        for ($len = count($list), $i = $len; $i > 0; $i--) {
            list($n, $c) = $list[$i - 1];
        ?>

            <?php echo $n; ?>:<textarea><?php echo $c; ?></textarea><br /><br />
        <?php
        }
    }
    if (Core_Model::isOpen()) {
        Core_Model::instance()->close();
    }