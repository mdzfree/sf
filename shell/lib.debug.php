<?php
    if (DEV_MODE == 1 && (is_numeric(strpos(sfget_ip(), '::1')) || is_numeric(strpos(sfget_ip(), '127.0.0.1')))) {//调试模式且是本地机时
        return true;
    }
    if ($_REQUEST['token'] == '_sf20131111' || $_REQUEST['token'] != ENDE_KEY) {//且token配置为框架默认时（防止源码直接上线后被执行）
        sfquit('access denied');
    }