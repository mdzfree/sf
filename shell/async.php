<?php
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    $io = Core_IoUtils::instance();
    $dir = ROOT_DIR . DS . 'asset'  . DS . 'internal' . DS . 'data' . DS . 'async' . DS;
    $no = sfret('__no', 1);
    $asyncType = sfread_etc_global('config.php', 'async_type');
    if ($asyncType == 'io') {
        $asyncExp = sfread_etc_global('config.php', 'async_exp');
        $fileNew = sprintf('%snew.%s.log', $dir, $no);
        $fileOk = sprintf('%sok.%s.log', $dir, $no);
        $fileOkNew = sprintf('%sok.new.%s.log', $dir, $no);
        $fileOkExp = sprintf('%sok.exp.%s.log', $dir, $no);
        $beginTime = time();
        if (is_file($fileNew) || is_file($fileOkNew) || is_file($fileOkExp)) {
            $result = sftrigger_event('async');//执行任务
            if ($beginTime + $asyncExp > time()) {
                if (is_file($fileOkExp)) {
                    if (is_file($fileNew)) {
                        rename($fileNew, $fileOk);
                    } else {
                        @unlink($fileOkExp);
                        @unlink($fileOkNew);
                    }
                } elseif (is_file($fileOkNew)) {
                    unlink($fileOkNew);
                } elseif (is_file($fileNew)) {
                    rename($fileNew, $fileOk);
                }
                $io->writeFile($fileOk, time());
                if (sfarray_true($result)) {
                    //TODO 记录错误原因
                }
            }
        }
    }