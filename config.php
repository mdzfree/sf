<?php
$GLOBALS['__GET'] = $_GET;
$GLOBALS['__POST'] = $_POST;
$GLOBALS['__REQUEST'] = $_REQUEST;

define('DB_PREFIX', '');
define('DB_TABLE_PREFIX', 'sf_');
define('DB_HOST', 'localhost');
define('DB_NAME', 'test');//test名称不会连接数据库
define('DB_USER', 'root');
define('DB_PASSWORD', '');

define('ENDE_KEY', '_sf20131111');

/* config ::bof */
if (!isset($GLOBALS['__LOCAL'])) {
    $localXML = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'local.xml';
    if (is_file($localXML)) {
        try {
            $xml = new SimpleXMLElement(file_get_contents($localXML));
            $GLOBALS['__LOCAL'] = json_decode(json_encode($xml), true);
        } catch (Exception $e) {
        }
    }
}
if (!defined('THEME_NAME')) {
    define('THEME_NAME', 'framework');
}


define('CACHE_LESS', true);
define('THEME_DEF_NAME', 'default');
define('SYS_TIME_CODE','PRC');
define('SYS_TIME_ZONE','+8:00');
/* config ::eof */
date_default_timezone_set(SYS_TIME_CODE);

define('DS', DIRECTORY_SEPARATOR);
define('HTTP', 'http://');
define('HTTPS', 'https://');
define('DOMAIN', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
define('BASEURL', HTTP . DOMAIN);  //  e.g. http://localhost
if (!defined('BASEDIR')) {
    $basedir = ltrim(dirname($_SERVER['SCRIPT_NAME']));
    $basedir = ($basedir === DS ? '' : $basedir);
    define('BASEDIR', $basedir);
}
define('SITEURL_ROOT', BASEURL . BASEDIR);
define('SITEURL', SITEURL_ROOT . '/'); //  e.g. http://localhost/[dirname]/
define('ROOT_DIR', dirname(__FILE__));

define('SYSTEM_DIR', ROOT_DIR . DS . 'core');
define('SYSTEM_LIBRARY_DIR', SYSTEM_DIR . DS . 'library');
define('CACHE_DIR', ROOT_DIR . DS . 'asset/cache/page');
define('MODULE_DIR', ROOT_DIR . DS . 'module');
define('THEME_DIR', ROOT_DIR . DS . 'theme');
define('THEME_DEF_DIR', THEME_DIR . DS . THEME_DEF_NAME);
define('THEME_CUR_DIR', THEME_DIR . DS . THEME_NAME);
define('EXMODULE_DEF_DIR', THEME_DEF_DIR . DS . 'module');
define('EXMODULE_CUR_DIR', THEME_CUR_DIR . DS . 'module');
define('GLOBAL_INSTANCE_KEY', '--');

define('FLAG_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? ($_SERVER['HTTP_X_REQUESTED_WITH']== 'XMLHttpRequest') : false);
define('BIND_FIRST', 'first__');
define('BIND_LAST', '__last');
define('IS_REWRITE', true);
define('REWRITE_SUFFIX', '');
define('REWRITE_FILE_STATUS', false);

$GLOBALS['config'] = array(
                    'module' => 'default',
                    'controller' => 'default',
                    'action' => 'index'
                );
$GLOBALS['instances'] = array();

define('WX', isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false);