<?php
    $GLOBALS['_console_mode'] = 'default';
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.php';
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib.debug.php';
    $dir = ROOT_DIR . DS . 'asset/internal/data/patch';
    $io = Core_IoUtils::instance();
    $password = ENDE_KEY;//提交更新验证的最好自定义密码
    if (!empty($_POST)) {
        ini_set('output_buffering', 4096);
        ob_start();
        if ($_POST['password'] != $password) {
            sfquit('<script>parent.msg("密码错误！")</script>');
        }
        if (!empty($_POST['item'])) {
            //恢复补丁
            $dirBackup = $dir . '/' . $_POST['item'] . '/file_backup';
            echo str_pad('', 4096);
            echo('<script>parent.log("校验目录：' . $_POST['item'] . '")</script>');
            ob_flush();
            flush();
            if (is_dir($dirBackup)) {
                $list = $io->scanDir($dirBackup, 0);
                foreach ($list as $key => $value) {
                    $cur = ROOT_DIR . '/' . $value;
                    if (!is_dir($cur)) {
                        echo str_pad('', 4096);
                        echo('<script>parent.log("开始恢复：' . $value . '")</script>');
                        ob_flush();
                        flush();
                        if (!copy($dirBackup . '/' . $value, $cur)) {
                            sfquit('<script>parent.msg("恢复文件失败，请检查服务器权限！")</script>');
                        }
                    }
                }
                sfquit('<script>parent.msg("恢复成功！")</script>');
            } else {
                sfquit('<script>parent.msg("找不到恢复的备份文件夹！")</script>');
            }
        }
        $tmpName = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        if (empty($tmpName)) {
            sfquit('<script>parent.msg("找不到上传的文件或操作！")</script>');
        }
        if (!is_numeric(strpos($_FILES['file']['name'], '.php')) && !is_numeric(strpos($_FILES['file']['name'], '.zip'))) {
            sfquit('<script>parent.msg("只支持php和zip文件！")</script>');
        }
        $step = 0;
        $zip = null;
        $path = null;
        set_time_limit(0);
        $backupFiles = array();
        while (true) {
            echo str_pad('', 4096);
            switch ($step++) {
                case 0:
                    echo('<script>parent.log("校验文件...")</script>');
                    ob_flush();
                    flush();
                    if (!is_file($tmpName)) {
                        sfquit('<script>parent.msg("服务器丢失文件！")</script>');
                    }
                    if (is_numeric(strpos($_FILES['file']['name'], '.zip'))) {
                        $zip = new ZipArchive();
                        if (!$zip->open($tmpName)) {
                            sfquit('<script>parent.msg("压缩包损坏！")</script>');
                        } else {
                            $path = $dir . '/' . md5_file($tmpName) . '/file';
                            $io->createDir($path);
                            $zip->extractTo($path);
                        }
                    } else {
                        //php文件
                        if (is_numeric(strpos($fileName, 'function.php'))) {
                            $path = $dir . '/' . md5_file($tmpName) . '/file';
                            $io->createDir($path . '/core');
                            if (!copy($tmpName, $path . '/core/function.php')) {
                                sfquit('<script>parent.msg("拷贝文件失败，请检查服务器权限！")</script>');
                            }
                        } else {
                            $handler = fopen($tmpName, 'rb');
                            $content = fread($handler, 100);
                            fclose($handler);
                            if (is_numeric(strpos($content, 'class '))) {
                                if (is_numeric(strpos($content, 'extends'))) {
                                    $sIndex = strpos($content, 'class ') + 6;
                                    $eIndex = strpos($content, ' extends') - $sIndex;
                                } else {
                                    //其他无继承文件
                                    $sIndex = strpos($content, 'class ') + 6;
                                    $eIndex = strpos($content, "\r\n", $sIndex) - $sIndex;
                                }
                                $className = substr($content, $sIndex, $eIndex);
                                list($lName, $rName) = explode('_', $className);
                                $lName = strtolower($lName);
                                if (strtolower($lName) == 'core') {
                                    $path = $dir . '/' . md5_file($tmpName) . '/file';
                                    $io->createDir($path . '/core');
                                    if (!copy($tmpName, $path . '/core/' . $fileName)) {
                                        sfquit('<script>parent.msg("拷贝文件失败，请检查服务器权限！")</script>');
                                    }
                                } else {
                                    $path = $dir . '/' . md5_file($tmpName) . '/file';
                                    $io->createDir($path . '/module/' . $lName);
                                    if (!copy($tmpName, $path . '/module/' . $lName . '/' . $fileName)) {
                                        sfquit('<script>parent.msg("拷贝文件失败，请检查服务器权限！")</script>');
                                    }
                                }
                            } else {
                                if (is_numeric(strpos($fileName, 'var.php')) || is_numeric(strpos($fileName, 'image.php')) || is_numeric(strpos($fileName, 'index.php'))) {
                                    $path = $dir . '/' . md5_file($tmpName) . '/file';
                                    $io->createDir($path);
                                    if (!copy($tmpName, $path . '/' . $fileName)) {
                                        sfquit('<script>parent.msg("拷贝文件失败，请检查服务器权限！")</script>');
                                    }
                                } else {
                                    sfquit('<script>parent.msg("暂无法识别该文件路径！")</script>');
                                }
                            }
                        }
                    }
                    echo('<script>parent.log("校验完成！")</script>');
                    ob_flush();
                    flush();
                    break;
                case 1:
                    echo str_pad('', 4096);
                    echo('<script>parent.log("开始备份...")</script>');
                    ob_flush();
                    flush();
                    $io->createDir($path . '_backup');
                    if (!empty($_POST['note'])) {
                        $io->writeFile(dirname($path) . '/note.log', $_POST['note']);
                    }
                    $list = $io->scanDir($path, 0);
                    foreach ($list as $key => $value) {
                        $cur = ROOT_DIR . '/' . $value;
                        if (is_dir($cur)) {
                            $io->createDir($path . '_backup/' . $value);
                            unset($list[$key]);
                        }
                    }
                    foreach ($list as $value) {
                        $cur = ROOT_DIR . '/' . $value;
                        if (is_file($cur)) {
                            if (!copy($cur, $path . '_backup/' . $value)) {
                                sfquit('<script>parent.msg("备份文件错误，请检查服务器权限！")</script>');
                            }
                        }
                        $backupFiles[] = $value;
                    }
                    if (empty($backupFiles)) {
                        sfquit('<script>parent.msg("备份文件失败，请检查服务器权限！")</script>');
                    }
                    echo str_pad('', 4096);
                    echo('<script>parent.log("备份完成！")</script>');
                    ob_flush();
                    flush();
                    break;
                case 2:
                    if (!empty($zip)) {
                        echo('<script>parent.log("开始解压...")</script>');
                        ob_flush();
                        flush();
                        $zip->extractTo(ROOT_DIR);
                    } else {
                        //替换php文件
                        $list = $io->scanDir($path, 0);
                        foreach ($list as $key => $value) {
                            $cur = ROOT_DIR . '/' . $value;
                            if (!is_dir($cur)) {
                                echo('<script>parent.log("开始复制：' . $value . '")</script>');
                                if (!copy($path . '/' . $value, $cur)) {
                                    sfquit('<script>parent.msg("复制文件失败，请检查服务器权限！")</script>');
                                }
                            }
                        }
                    }
                    break;
                default:
                    sfquit('<script>parent.msg("更新成功！")</script>');
            }
        }
        sfquit('<script>parent.msg("无效请求！")</script>');
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
    $fmtList = array();
    for ($i = count($list) - 1; $i >= 0; $i--) {
        $value = $list[$i];
        $fileNote = $dir . DS . $value . DS . 'note.log';
        $note = date('Y-m-d H:i:s', filemtime($dir . DS . $value)) . '#' . $value;
        if (file_exists($fileNote)) {
            $note = $io->readFile($fileNote) . ' - ' . $note;
        }
        $fmtList[] = $note;
    }
    $list = $fmtList;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Online Patch</title>
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
    <script type="text/javascript">
        function go() {
            var pwd = document.getElementById('password');
            var submit = document.getElementById('submit');
            if (pwd.value.length == 0) {
                log("请输入授权密码！");
                pwd.focus();
                return false;
            }
            submit.innerText = '提交中……';
            submit.disabled = true;
            submit.style.backgroundColor = '#ccc';
            return true;
        }
        function log(content) {
            var log = document.getElementById('log');
            var date = new Date();
            log.value = content + " --- " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds()  + "\r\n" +log.value;
        }
        function msg(content) {
            log(content);
            var submit = document.getElementById('submit');
            submit.innerText = '上传';
            submit.disabled = false;
            submit.style.backgroundColor = '';
        }
    </script>
</head>

<body>
<table>
    <iframe id="ifrmain" name="ifrmain" style="display: none"></iframe>
    <td>
        <form method="post" enctype="multipart/form-data" target="ifrmain" onsubmit="return go()">
            <div class="input-content">
                <div class="title-msg">
                    <span>请选择需要上传的文件（php文件或zip压缩包）</span>
                </div>
                <div>
                    <input type="file" name="file" style="font-size: 25px" />
                </div>

                <div class="title-msg">
                    <span>补丁说明（可选）：</span>
                </div>
                <div>
                    <input type="text" name="note" />
                </div>

                <div class="title-msg">
                    <span>授权密码：</span>
                </div>
                <div>
                    <input type="password" name="password" id="password" onkeyup="document.getElementById('password2').value = this.value" onchange="document.getElementById('password2').value = this.value" autocomplete="false" />
                </div>
            </div>
            <div style="margin-top: 16px">
                <button type="submit" class="enter-btn" id="submit" >上传</button>
            </div>
        </form>

        <form method="post" target="ifrmain">
            <input type="hidden" name="password" id="password2" />
            <div class="input-content">
                <div class="title-msg">
                    <span>历史：</span><button type="submit" class="enter-btn" style="width: 120px;margin-left: 196px;">恢复所选</button>
                </div>
                <div>
                    <select id="items" name="item" style="width: 352px; height: 427px" multiple>
                        <?php
                            foreach ($list as $value) :
                                list($name, $id) = explode('#', $value);
                        ?>
                            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                        <?php
                            endforeach;
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
        <textarea id="log" style="width: 99%; height: 780px" readonly></textarea>
    </td>
</table>
</body>
</html>