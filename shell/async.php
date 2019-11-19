<?php
    sleep(5);
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    $io = Core_IoUtils::instance();
    $dir = ROOT_DIR . DS . 'asset'  . DS . 'internal' . DS . 'data' . DS . 'async' . DS;
    $no = sfret('__no', 1);
    $asyncType = sfread_etc_global('config.php', 'async_type');
    $asyncMax = sfread_etc_global('config.php', 'async_max');
    if ($asyncType == 'io') {
        $asyncExp = sfread_etc_global('config.php', 'async_exp');
        $fileNew = sprintf('%snew.%s.log', $dir, $no);
        $fileOk = sprintf('%sok.%s.log', $dir, $no);
        $fileOkNew = sprintf('%sok.new.%s.log', $dir, $no);
        $fileOkExp = sprintf('%sok.exp.%s.log', $dir, $no);
        $beginTime = time();
        if (is_file($fileNew) || is_file($fileOkNew) || is_file($fileOkExp)) {
            $len = ceil(1 / $asyncMax * 10);
            $begin = $no * $len - $len;
            $scale = $begin . '-' . $no * $len;
            $result = sftrigger_event('async', 1, $scale);//执行任务
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
                $errors = sfget_event_exception_list('async');
                if (!empty($errors)) {
                    $errorContent = '';
                    foreach ($errors as $value) {
                        $errorContent .= $value->getMessage() . "\r\n";
                    }
                    $io->appendFile($dir . 'error.log', $errorContent);
                }
            }
        }
    }