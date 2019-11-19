<?php
    class Core_Autoload extends Core_Base
    {
        /**
         * 监听执行异步逻辑
         */
        public static function listenAsync()
        {
            $asyncMax = sfread_etc_global('config.php', 'async_max');
            if (!empty($asyncMax)) {
                $io = Core_IoUtils::instance();
                $asyncType = sfread_etc_global('config.php', 'async_type');
                $asyncExp = sfread_etc_global('config.php', 'async_exp');
                $dir = ROOT_DIR . DS . 'asset'  . DS . 'internal' . DS . 'data' . DS . 'async' . DS;
                if ($asyncType == 'io') {
                    for ($i = 1; $i <= $asyncMax; $i++) {
                        $fileNew = sprintf('%snew.%s.log', $dir, $i);
                        $fileOk = sprintf('%sok.%s.log', $dir, $i);
                        $fileOkNew = sprintf('%sok.new.%s.log', $dir, $i);
                        $fileOkExp = sprintf('%sok.exp.%s.log', $dir, $i);
                        if (is_file($fileOkNew)) {//如果存在已执行超期则重新执行
                            if (filemtime($fileOkNew) + $asyncExp < time()) {
                                if (!is_file($fileOkExp)) {//不存在超时执行中时
                                    $fp = fopen($fileOkNew, "w");//占有重启任务
                                    if (flock($fp, LOCK_EX | LOCK_NB)) {
                                        $io->writeFile($fileOkNew, time());
                                        if (copy($fileOkNew, $fileOkExp)) {
                                            sfopen_url(sfurl('/shell/async.php', array('GET' => array('__no' => $i, '__mode' => 'exp'))));
                                            flock($fp, LOCK_UN);
                                            fclose($fp);
                                            break;
                                        }
                                    }
                                }
                            }
                        } elseif (is_file($fileOk)) {
                            $fp = fopen($fileOk, "w");//占有重启任务
                            if (flock($fp, LOCK_EX | LOCK_NB)) {
                                $io->writeFile($fileOk, time());
                                if (copy($fileOk, $fileOkNew)) {
                                    sfopen_url(sfurl('/shell/async.php', array('GET' => array('__no' => $i, '__mode' => 'reset'))));
                                    flock($fp, LOCK_UN);
                                    fclose($fp);
                                    break;
                                }
                            }
                        } elseif (is_file($fileNew)) {
                            if (filemtime($fileNew) + $asyncExp < time()) {
                                if (!is_file($fileOkExp)) {//不存在超时执行中时
                                    $fp = fopen($fileOk, "w");//占有重启任务
                                    if (flock($fp, LOCK_EX | LOCK_NB)) {
                                        $io->writeFile($fileOk, time());
                                        if (copy($fileOk, $fileOkExp)) {
                                            sfopen_url(sfurl('/shell/async.php', array('GET' => array('__no' => $i, '__mode' => 'exp'))));
                                            flock($fp, LOCK_UN);
                                            fclose($fp);
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            $io->writeFile($fileNew, time());
                            sfopen_url(sfurl('/shell/async.php', array('GET' => array('__no' => $i, '__mode' => 'new'))));
                            break;
                        }
                    }
                }
            }
        }
    }