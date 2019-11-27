<?php
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.debug.php';
    $dir = ROOT_DIR . DS . 'asset/internal/data/debug';
    $io = Core_IoUtils::instance();
    $log = '';
    if (!empty($_POST)) {
        if ($_POST['action'] == 'delete') {
            $file = $dir . DS . $_POST['item'] . '.log';
            if (is_file($file)) {
                unlink($file);
            }
        } elseif (is_numeric(strpos($_POST['path'], '://'))) {
            $url = $_POST['path'];
            $method = $_POST['method'];
            $type = $_POST['type'];
            $headers = $_POST['header'];
            $param = $_POST['param'];
            if (!empty($headers)) {
                $headers = explode("\r\n", $headers);
                $fmtHeaders = array();
                foreach ($headers as $value) {
                    list($k, $v) = explode(':', $value);
                    $fmtHeaders[$k] = $v;
                }
                $headers = $fmtHeaders;
            }
            if (!empty($param)) {
                $json = json_decode($param, true);
                if (!empty($json) && is_array($json)) {
                    $isNumber = true;
                    foreach ($json as $k => $v) {
                        if (!is_numeric($k)) {
                            $isNumber = false;
                            break;
                        }
                    }
                    if ($isNumber) {
                        $param = $json;
                    }
                } elseif (is_numeric(strpos($param, "\r\n")) || is_numeric(strpos($param, "="))) {
                    $param = parse_ini_string($param);
                }
            }
            $file = $dir . DS . sfmd5_short(md5(json_encode($_POST))) . '.log';
            if (!is_file($file)) {
                $io->writeFile($file, json_encode($_POST));
            } else {
                touch($file);
            }
            $log = sfopen_url($url, $param, strtolower($method), array('return' => true, 'headers' => $headers, 'type' => $type));
        } elseif (is_numeric(strpos($_POST['path'], '/')) || is_numeric(strpos($_POST['path'], "\\"))) {
            $log .= date('Y-m-d H:i:s') . "----------------------";
            $path = $_POST['path'];
            $path = str_replace('/', DS, $path);
            $path = str_replace("\\", DS, $path);
            $dirs = array('asset', 'class', 'core', 'etc', 'module', 'shell', 'theme', 'vendor');
            $dir = null;
            $index = null;
            foreach ($dirs as $value) {
                $index = strpos($path, $value . DS);
                if (is_numeric($index)) {
                    $dir = $value . DS;
                    break;
                }
            }
            if (!empty($dir)) {
                $downloadFile = ROOT_DIR . DS . $dir . substr($path, $index + strlen($dir));
            } else {
                if (is_numeric(strpos($path, 'config.php')) || is_numeric(strpos($_POST['path'], 'var.php')) || is_numeric(strpos($_POST['path'], 'index.php'))) {

                    $downloadFile = ROOT_DIR . DS . basename($path);
                } else {
                    $log .= "\r\n无法匹配：" . $path;
                }
            }
            if (!empty($downloadFile)) {
                if (is_file($downloadFile)) {
                    $file = fopen($downloadFile, "rb");
                    //告诉浏览器这是一个文件流格式的文件
                    header("Content-type: application/octet-stream");
                    //请求范围的度量单位
                    header("Accept-Ranges: bytes" );
                    //Content-Length是指定包含于请求或响应中数据的字节长度
                    header("Accept-Length: " . filesize($downloadFile));
                    //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
                    header("Content-Disposition: attachment; filename=" . basename($downloadFile));
                    //读取文件内容并直接输出到浏览器
                    echo fread($file, filesize($downloadFile));
                    fclose($file);
                    sfquit();
                } else {
                    $log .= "\r\n找不到文件：" . $downloadFile;
                }
            }
        } elseif (!empty($_POST['path'])) {
            $log .= date('Y-m-d H:i:s') . "----------------------";
            $file = $dir . DS . sfmd5_short(md5(json_encode($_POST))) . '.log';
            if (!is_file($file)) {
                $io->writeFile($file, json_encode($_POST));
            } else {
                touch($file);
            }
            list($path, $note) = explode('#', $_POST['path']);
            if (is_numeric(strpos($path, '-'))) {
                list($class, $fun) = explode('-', $path);
                $object = new $class();
                if (!empty($object)) {
                    if (method_exists($object, $fun)) {
                        $log .= $_POST['path'];
                        $result = 0;
                        if (!empty($_POST['param'])) {
                            $json = json_decode($_POST['param'], true);
                            if (!empty($json) && is_array($json)) {
                                $isNumber = true;
                                foreach ($json as $k => $v) {
                                    if (!is_numeric($k)) {
                                        $isNumber = false;
                                        break;
                                    }
                                }
                                if ($isNumber) {
                                    $result = call_user_func_array(array($object, $fun), $json);
                                } else {
                                    $result = call_user_func_array(array($object, $fun), array($_POST['param']));
                                }
                            } elseif (is_numeric(strpos($_POST['param'], "\r\n"))) {
                                $result = call_user_func_array(array($object, $fun), array(parse_ini_string($_POST['param'])));
                            } else {
                                $result = call_user_func_array(array($object, $fun), array($_POST['param']));
                            }
                        } else {
                            $result = $object->$fun();
                        }
                        if (!$result && method_exists($object, 'getError') && $object->getError()) {
                            $log .= "\r\n" . print_r($object->getErrors(), true);
                        } else {
                            $log .= "\r\n" . print_r($result, true);
                        }
                    } else {
                        $log .= "\r\n" . "找不到方法：" . $fun;
                    }
                } else {
                    $log .= "\r\n" . "不存在：" . $class . "类";
                }
            } else {
                $log .= $_POST['path'];
                $fun = $path;
                if (function_exists($fun)) {
                    if (!empty($_POST['param'])) {
                        $json = json_decode($_POST['param'], true);
                        if (!empty($json) && is_array($json)) {
                            $isNumber = true;
                            foreach ($json as $k => $v) {
                                if (!is_numeric($k)) {
                                    $isNumber = false;
                                    break;
                                }
                            }
                            if ($isNumber) {
                                foreach ($json as $jk => $jv) {
                                    $jData = json_decode($jv, true);
                                    if (!empty($jData)) {
                                        $json[$jk] = $jData;
                                    }
                                }
                                $result = call_user_func_array($fun, $json);
                            } else {
                                $result = call_user_func_array($fun, array($_POST['param']));
                            }
                        } elseif (is_numeric(strpos($_POST['param'], "\r\n"))) {
                            $result = call_user_func_array($fun, array(parse_ini_string($_POST['param'])));
                        } else {
                            $result = call_user_func_array($fun, array($_POST['param']));
                        }
                    } else {
                        $result = call_user_func($fun);
                    }
                    $log .= "\r\n" . print_r($result, true);
                } else {
                    $log .= "\r\n" . "不存在：" . $fun . "方法";
                }
            }
        }
    }
    $list = $io->scanDir($dir);
    $fmtList = array();
    foreach ($list as $key => $value) {
        $time = filemtime($dir . DS . $value);
        if (empty($fmtList[$time])) {
            $fmtList[$time] = $value;
        } else {
            for ($i = 1; $i <= 100; $i++) {
                if (empty($fmtList[$time + $i])) {
                    $fmtList[$time + $i] = $value;
                    break;
                }
            }
        }
    }
    ksort($fmtList);
    $list = array_merge($fmtList);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Online Debug</title>
    <style>
        body{
            background-color:#444;
            font-size:14px;
        }
        *{
            padding: 0;
            margin: 0;
        }
        table {
            width: 100%;
        }
        td {
            width: 50%;
            padding: 15px;
            vertical-align: top;
        }

        .title-msg {
            width: 100%;
            height: 64px;
            line-height: 64px;
        }

        .title-msg:hover{
            cursor: default	;
        }

        .title-msg span {
            font-size: 12px;
            color: #707472;
            color: #fff;
        }

        .input-content {
            width: 100%;
        }

        .input-content input
        , .input-content textarea {
            width: 330px;
            height: 40px;
            border: 1px solid #dad9d6;
            background: #ffffff;
            padding-left: 10px;
            padding-right: 10px;
        }

        .input-content textarea {
            height: 200px;
        }

        .enter-btn {
            width: 352px;
            height: 40px;
            color: #fff;
            background: #0bc5de;
            line-height: 40px;
            text-align: center;
            border: 0px;
        }

        .enter-btn:hover {
            cursor:pointer;
            background: #1db5c9;
        }

        textarea {
            padding: 10px 5px;
        }
    </style>
    <script>
        function use_item(el) {
            document.getElementById('path').value = el.text;
            document.getElementById('param').value = el.attributes.param.value;
            document.getElementById('header').value = el.attributes.header.value;
            change_path(document.getElementById('path'));
        }
        function change_path(el) {
            if (el.value.indexOf('://') >= 0) {
                document.getElementById('http').style.display = 'inherit';
            } else {
                document.getElementById('http').style.display = 'none';
            }
        }
        window.onload = function () {
            if (document.getElementById('items').options.selectedIndex >=0) {
                use_item(document.getElementById('items').options[document.getElementById('items').options.selectedIndex]);
            }
        }
    </script>
</head>

<body>
    <table>
        <td>
            <form method="post">
                <div class="input-content">
                    <div class="title-msg">
                        <span>请输入调试路径：类名-方法名（执行）、URL（模拟请求）、文件路径（下载）；加#号可备注</span>
                    </div>
                    <div>
                        <input type="text" id="path" name="path" value="<?php echo sfret('path'); ?>" onkeypress="change_path(this)" onchange="change_path(this)" required/>
                    </div>

                    <div id="http"<?php echo Core_View::instance()->hasRequest('method') && is_numeric(strpos(sfret('path'), '://')) ? '' : ' style="display: none"'; ?>>
                        <div class="title-msg">
                            <span>请求方式：</span><select name="method"><option<?php echo Core_View::instance()->hasRequest('method', 'POST') ? ' selected' : ''; ?>>POST</option><option<?php echo Core_View::instance()->hasRequest('method', 'GET') ? ' selected' : ''; ?>>GET</option></select>
                            <span>ContentType：</span><select name="type"><option<?php echo Core_View::instance()->hasRequest('type', '') ? ' selected' : ''; ?> value="">Auto</option><option<?php echo Core_View::instance()->hasRequest('type', 'application/json') ? ' selected' : ''; ?>>application/json</option><option<?php echo Core_View::instance()->hasRequest('type', 'text/html') ? ' selected' : ''; ?>>text/html</option><option<?php echo Core_View::instance()->hasRequest('type', 'application/x-www-form-urlencoded') ? ' selected' : ''; ?>>application/x-www-form-urlencoded</option><option<?php echo Core_View::instance()->hasRequest('type', 'text/json') ? ' selected' : ''; ?>>text/json</option></select>
                        </div>
                        <div>
                            <textarea id="header" name="header" placeholder="headers                              Key:Value格式，可写多个，多个请换行" style="height: 60px; margin: 0"><?php echo sfret('header'); ?></textarea>
                        </div>
                    </div>

                    <div class="title-msg">
                        <span>请输入参数：字符串，<a href="javascript:;" style="color: #fff" title='单一字符串参数：["a", "b"] 或者参数为数组：[{"id": "1"}]'>[参数1, 参数2]</a>，JSON，<a href="javascript:;" style="color: #fff" title="参数为数组：&#13id=1&#13name=张三">ini</a></span>
                    </div>
                    <div>
                        <textarea id="param" name="param"><?php echo sfret('param'); ?></textarea>
                    </div>
                </div>
                <div style="margin-top: 16px">
                    <button type="submit" class="enter-btn" >提交</button>
                </div>
            </form>

            <form method="post">
                <input type="hidden" name="action" value="delete" />
                <div class="input-content">
                    <div class="title-msg">
                        <span>历史：</span><button type="submit" class="enter-btn" style="width: 120px;margin-left: 196px;">删除所选</button>
                    </div>
                    <div>
                        <select id="items" name="item" style="width: 352px; height: 354px" multiple>
                            <?php
                                $firstSelected = ' selected';
                                for ($len = count($list), $i = $len - 1; $i >= 0; $i--) :
                                    $value = $list[$i];
                                    list($name, $ext) = explode('.', $value);
                                    $content = $io->readFile($dir . DS . $value);
                                    $data = json_decode($content, true);
                            ?>
                            <option<?php echo $firstSelected; ?> onclick="use_item(this)" param='<?php echo $data['param']; ?>' header="<?php echo $data['header']; ?>" value="<?php echo $name; ?>" title="<?php echo $data['path']; ?>"><?php echo $data['path']; ?></option>
                            <?php
                                $firstSelected = '';
                                endfor;
                            ?>
                        </select>
                    </div>
                </div>
            </form>
        </td>
        <td>
            <div class="title-msg">
                <span>日志：</span>
            </div>
            <textarea style="width: 99%; height: 780px" readonly><?php echo $log; ?></textarea>
        </td>
    </table>
</body>
</html>
<?php
    if (Core_Model::isOpen()) {
        Core_Model::instance()->close();
    }
    if (!empty($_SESSION['_log_flag']) && !empty($GLOBALS['console_logs'])) {
        $dataJson = sfjson_encode_ex($GLOBALS['console_logs']);
        $kMd5 = sfmd5_short(md5($dataJson));
        Core_Cache::instance()->setArea(15)->set('Console:' . Core_Request::instance()->getCurUrl() . '|' . $kMd5, $dataJson);
    }