<?php
    $flagOk = true;
    if (DEV_MODE == 1) {//调试模式时
        if (!is_numeric(strpos(sfget_ip(), '::1'))) {//当非本地
            if (ENDE_KEY == '_sf20131111') {//且token配置为框架默认时（防止源码直接上线后被执行）
                $flagOk = false;
            } else if ($_REQUEST['token'] != ENDE_KEY) {//当token不对时
                $flagOk = false;
            }
        }
    } else {//非调试模式时
        if ($_REQUEST['token'] == '_sf20131111') {//且token配置为框架默认时（防止源码直接上线后被执行）
            $flagOk = false;
        } else if ($_REQUEST['token'] != ENDE_KEY) {//token不对时
            $flagOk = false;
        }
    }
    if (!$flagOk) {
        //如果token不正确或者是默认token，则不允许执行
        sfquit('access denied');
    }