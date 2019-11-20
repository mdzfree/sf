<?php
    define('ROOT_PATH', dirname(dirname(__FILE__)));
    define('VDS', DIRECTORY_SEPARATOR);
    $basedir = ltrim(dirname(dirname($_SERVER['SCRIPT_NAME'])));
    $basedir = ($basedir === VDS ? '' : $basedir);
    define('BASEDIR', $basedir);
    require ROOT_PATH . VDS . 'include.php';