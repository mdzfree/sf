<?php
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    $io = Core_IoUtils::instance();
    $dir = ROOT_DIR . DS . 'asset'  . DS . 'internal' . DS . 'data' . DS . 'async' . DS;
    $no = sfret('__no', 1);
    $asyncType = sfread_etc_global('config.php', 'async_type');
    if ($asyncType == 'io') {
        $asyncMax = sfread_etc_global('config.php', 'async_max');
        $asyncSleep = sfread_etc_global('config.php', 'async_sleep');
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
            try {
                $result = sftrigger_event('async', 1, $scale);//执行任务
            } catch (Exception $e) {
            } finally {
                if ($beginTime + $asyncExp >= time()) {
                    if (is_file($fileOkExp)) {
                        if (is_file($fileNew)) {
                            @unlink($fileOkExp);
                            if (!empty($asyncSleep)) {
                                if (filemtime($fileNew) + $asyncSleep < time()) {
                                    //如果修改时间间隔超过当前时间，则直接完成
                                    rename($fileNew, $fileOk);
                                } else {
                                    //否则余下间隔过期
                                    touch($fileNew, filemtime($fileNew) + $asyncSleep - $asyncExp);
                                }
                            } else {
                                rename($fileNew, $fileOk);
                            }
                        } else {
                            @unlink($fileOkExp);
                            if (!empty($asyncSleep)) {
                                if (filemtime($fileOkNew) + $asyncSleep < time()) {
                                    //如果修改时间间隔超过当前时间，则直接完成
                                    @unlink($fileOkNew);
                                } else {
                                    //否则余下间隔过期
                                    @touch($fileOkNew, filemtime($fileOkNew) + $asyncSleep - $asyncExp);
                                }
                            } else {
                                @unlink($fileOkNew);
                            }
                        }
                    } elseif (is_file($fileOkNew)) {
                        if (!empty($asyncSleep)) {
                            if (filemtime($fileOkNew) + $asyncSleep < time()) {
                                //如果修改时间间隔超过当前时间，则直接完成
                                @unlink($fileOkNew);
                            } else {
                                //否则余下间隔过期
                                @touch($fileOkNew, filemtime($fileOkNew) + $asyncSleep - $asyncExp);
                            }
                        } else {
                            @unlink($fileOkNew);
                        }
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
    }