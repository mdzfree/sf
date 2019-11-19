<?php
/**
 * 兼容函数转换
 */
if (!function_exists('mysql_escape_string')) {
    function mysql_escape_string($value)
    {
        if (function_exists('mysql_real_escape_string')) {
            return mysql_real_escape_string($value);
        }
        return addslashes($value);
    }
}
if (!function_exists('lcfirst')) {
    function lcfirst($str)
    {
        return strtolower(substr($str, 0, 1)) . substr($str, 1);
    }
}
if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
/**
 * 方法类，用于触发事件传入的对象
 */
class Method
{
    public $_object;
    public $_function_name;
    public $_params;

    function __construct($function_name, $object = null)
    {
        $this->_function_name = $function_name;
        $this->_object = $object;
        $args = func_get_args();
        $len = count($args);
        if ($len > 2) {

            for ($i = 2; $i < $len; $i++) {
                $this->_params[] = $args[$i];
            }
        }
    }

    /**
     * 执行函数
     * @param null $params 传参集合
     * @return mixed
     */
    function execute($params = null)
    {
        if (is_array($params)) {
            if (is_array($this->_params)) {
                $params = array_merge($this->_params, $params);
            }
        } else {
            $params = $this->_params;
        }
        if (is_object($this->_object) && is_string($this->_function_name)) {
            if (count($params) > 0) {

                return call_user_func_array(array(
                    $this->_object,
                    $this->_function_name
                ) , $params);
            } else {

                return call_user_func(array(
                    $this->_object,
                    $this->_function_name
                ));
            }
        } else if (is_string($this->_function_name) && function_exists($this->_function_name)) {
            if (count($params) > 0) {

                return call_user_func_array($this->_function_name, $params);
            } else {

                return $this->_function_name();
            }
        }
    }
}

/**
 * 读取etc配置
 * @param $path
 * @return array|bool
 */
function sfread_etc($path)
{
    return parse_ini_file(ROOT_DIR . '/etc/' . $path);
}

/**
 * 读取etc
 * @param $path 配置路径
 * @param $key  配置key值
 * @return mixed
 */
function sfread_etc_global($path, $key)
{
    if (empty($GLOBALS['_etc_' . $path])) {
        $GLOBALS['_etc_' . $path] = sfread_etc($path);
    }
    return $GLOBALS['_etc_' . $path][$key];
}

/**
 * 返回指定数据的值
 * @param $key  值的键名
 * @param string $return    为空时返回的值
 * @param null $datas   数组数据，默认是 $_REQUEST
 * @return mixed|string 返回值
 */
function sfret($key, $return = '', $datas = null)
{
    if ($datas == null) {
        $datas = $_REQUEST;
    }
    if (isset($datas[$key]) && !empty($datas[$key])) {
        return $datas[$key];
    }
    return $return;
}
//读取全局配置    ::开始
$GLOBALS['etc'] = sfread_etc('config.php');
define('DEBUG', sfret('debug', 3, $GLOBALS['etc']));//3为上线模式，出错会报蓝屏界面
define('DEV_MODE', sfret('dev_mode', 0, $GLOBALS['etc']));//是否是开发模式
$GLOBALS['headers'] = getallheaders();
if (!empty($GLOBALS['etc']['console']) && empty($GLOBALS['_console_mode'])) {
    $browser = '';
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $br = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i', $br)) {
            $browser = 'MSIE';
        } elseif (preg_match('/Firefox/i', $br)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $br)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $br)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera/i', $br)) {
            $browser = 'Opera';
        } else {
            $browser = 'Other';
        }
    }
    $consoleFile = ROOT_DIR . DS . 'class' . DS . 'Console' . DS . strtolower($browser) . '.php';
    if ($GLOBALS['etc']['console'] == 1 && is_file($consoleFile)) {
        require $consoleFile;
    } else {
        $browser = '';
    }
    if (empty($browser) || !function_exists('sfconsole')) {
        function sfconsole()
        {
            list($usec, $sec)   =   explode(' ', microtime());
        $GLOBALS['console_logs'][] = array('d-' . ((float)$usec + (float)$sec), func_get_args());
            global $_log_i;
            $_log_i = isset($_log_i) ? $_log_i : 0;
            header('Log-A-' . $_log_i++ . ':' . json_encode(func_get_args()));
        }
        function sfconsolew()
        {
            list($usec, $sec)   =   explode(' ', microtime());
            $GLOBALS['console_logs'][] = array('w-' . ((float)$usec + (float)$sec), func_get_args());
            global $_log_i;
            $_log_i = isset($_log_i) ? $_log_i : 0;
            header('Log-W-' . $_log_i++ . ':' . json_encode(func_get_args()));
        }
        function sfconsolee()
        {
            list($usec, $sec)   =   explode(' ', microtime());
            $GLOBALS['console_logs'][] = array('e-' . ((float)$usec + (float)$sec), func_get_args());
            global $_log_i;
            $_log_i = isset($_log_i) ? $_log_i : 0;
            header('Log-E-' . $_log_i++ . ':' . json_encode(func_get_args()));
        }
        function sfconsolel()
        {
            list($usec, $sec)   =   explode(' ', microtime());
            $GLOBALS['console_logs'][] = array('l-' . ((float)$usec + (float)$sec), func_get_args());
            global $_log_i;
            $args = func_get_args();
            $_log_i = isset($_log_i) ? $_log_i : 0;
            header('Log-A-' . $_log_i++ . ':' . $args[0]);
            array_splice($args, 0, 1);
            header('Log-A-' . $_log_i . ':' . json_encode($args));
        }
    }
} else {
    function sfconsole()
    {
        $GLOBALS['console_logs'][] = func_get_args();
        list($usec, $sec)   =   explode(' ', microtime());
        $GLOBALS['console_logs'][] = array('d-' . ((float)$usec + (float)$sec), func_get_args());
    }
    function sfconsolew()
    {
        list($usec, $sec)   =   explode(' ', microtime());
        $GLOBALS['console_logs'][] = array('w-' . ((float)$usec + (float)$sec), func_get_args());
    }
    function sfconsolee()
    {
        list($usec, $sec)   =   explode(' ', microtime());
        $GLOBALS['console_logs'][] = array('e-' . ((float)$usec + (float)$sec), func_get_args());
    }
    function sfconsolel()
    {
        list($usec, $sec)   =   explode(' ', microtime());
        $GLOBALS['console_logs'][] = array('l-' . ((float)$usec + (float)$sec), func_get_args());
    }
}
//读取全局配置    ::结束

//自动注册  ::开始
/**
 * 自动加载类
 * @param $className
 */
function sfautoload($className)
{
    if (strpos($className, '_')) {
        $path = str_replace('_', DS, $className);
        $fullPath = ROOT_DIR . DS . lcfirst($path) . '.php';
        if (is_file($fullPath)) {
            require $fullPath;
        }
    } else {
        $fullPath = ROOT_DIR . DS . 'class' . DS . lcfirst($className) . '.php';
        if (is_file($fullPath)) {
            require $fullPath;
        }
    }
}

if(function_exists('spl_autoload_register')) {
    spl_autoload_register('sfautoload');
}

/**
 * 自动加载模块类
 * @param  String $className 类名
 * @return Void
 */
function sfload_module($className)
{
    $className = lcfirst($className);
    $path = explode('_', $className);
    $paths = explode(DS, $path);
    if (count($paths) == 3) {
        $paths[1] = lcfirst($paths[1]);
        $path = implode(DS, $paths);
    }
    if (array_shift($path) === 'Ex') {
        $filePath = EXMODULE_CUR_DIR . DS . $path[0] . DS . $path[1] . '.php';
        if ( !is_file($filePath) ) {
            $filePath = EXMODULE_DEF_DIR . DS . $path[0] . DS . $path[1] . '.php';
        }
    } else {
        $filePath = MODULE_DIR . DS . str_replace('_', DS, $className) . '.php';
    }
    if (file_exists($filePath)) {
        require $filePath;
    }
}

spl_autoload_extensions('.php');
spl_autoload_register('sfload_module');
//自动注册  ::结束

//默认异步请求（瞬间中断）
//open_url('/test')
//open_url('/test?a=b', array('c' => 'd'))
//open_url('http://www.baidu.com')
function sfopen_url($url, $param = array(), $method = 'get', $args = array('return' => false))
{
    if (!empty($param) && is_string($param)) {
        //针对json请求
        $ch = curl_init($url);
        $headers = array();
        if (!empty($args['headers'])) {
            foreach ($args['headers'] as $k => $v) {
                $headers[] = sprintf('%s:%s', $k, $v);
            }
        }
        if (!empty($args['type'])) {
            $headers[] = 'Content-Type:' . $args['type'];
        } else {
            $headers[] = 'Content-Type:application/json';
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($ch);
        return $output;
    }
    $contentType = "Content-type:application/x-www-form-urlencoded\r\n";
    $cfg = parse_url($url);
    if (!isset($cfg['host'])) {
        $host = $_SERVER['HTTP_HOST'];
    } else {
        $host = $cfg['host'];
    }
    if (isset($cfg['scheme'])) {
        $port = $cfg['scheme'] == 'https' ? 443 : 80;
    } else {
        $port = $_SERVER['SERVER_PORT'];
    }
    $path = empty($cfg['path']) ? '/' : $cfg['path'];
    $sessionId = session_id();
    if (!empty($sessionId)) {
        $cookie = "PHPSESSID=" . $sessionId;
    }
    $data = "";
    if (!empty($cfg['query'])) {
        $addParam = array();
        parse_str($cfg['query'], $addParam);
        $param = $param + $addParam;
    }
    if (!empty($param)) {
        if (is_array($param)) {
            $data = http_build_query($param);
        } else {
            $data = $param;
            $contentType = "Content-type:application/json\r\n";
        }
    }
    if (!empty($args['type'])) {
        $contentType = "Content-type:" . $args['type'];
    }
    $method = strtolower($method);
    if ($method == 'post') {
        $out = "POST ${path} HTTP/1.1\r\n";
        $out .= "Host: " . $host . "\r\n";
        $out .= $contentType;
        if (!empty($args['headers'])) {
            foreach ($args['headers'] as $key => $value) {
                $out .= sprintf("%s: %s\r\n", $key, $value);
            }
        }
        $out .= "Expect: 100-continue\r\n";
        $out .= "User-Agent: Mozilla/5.0(compatible;MSIE 9.0; Windows NT 6.1; Trident/5.0)\r\n";
        $out .= "Accept: text/html, application/xhtml+xml, */*\r\n";
        if (!empty($data)) {
            $out .= "Content-length:" . strlen($data) . "\r\n";
            $out .= "\r\n${data}";
        }
    } else {
        if (!empty($data)) {
            $path .= '?' . $data;
        }
        $out = "GET ${path} HTTP/1.1\r\n";
        $out .= "Host: " . $host . "\r\n";
        if (!empty($args['headers'])) {
            foreach ($args['headers'] as $key => $value) {
                $out .= sprintf("%s: %s\r\n", $key, $value);
            }
        }
    }
    $out .= "Connection: Close\r\n";
    if (!empty($cookie)) {
        $out .= "Cookie: " . $cookie . "\r\n\r\n";
    }
    $errno = 0;
    $errstr = "";
    $fp = fsockopen($host, $port, $errno, $errstr, !$args['return'] ? 1: ($args['timeout'] ? $args['timeout'] : 80));
    //@stream_set_blocking($fp, 0);
    @stream_set_timeout($fp, 10);
    fwrite($fp, $out);
    $response = '';
    if ($args['return']) {
        while($row = fread($fp, 4096)){
            $response .= $row;
        }
        $response = explode("\r\n\r\n", $response);
        foreach ($response as $value) {
            if (substr(strtolower($value), 0, 5) != 'http/') {
                $response = $value;
                break;
            }
        }
        if (is_numeric(strpos($response, "\r\n{"))) {
            $response = explode("\r\n{", $response);
            $response =  '{' . $response[1];
            $response = explode("}\r\n", $response);
            $response =  $response[0] . '}';
        }
    }
    fclose($fp);
    return $response;
}

//错误处理  ::开始
function func_shutdown_crawl()
{
    $args = error_get_last();
    if (empty($args)) {
        return;
    }
    if ( DEBUG != 3 ) {
        if (!in_array($args[0], array(2, 8, 8192))) {
            sfconsole('error:');
            if (DEBUG == 2) {
                sfconsole(array($args[0], $args[1], $args[2], $args[3]));
            } else {
                sfconsole($args);
            }
        }
    } else {
        if (!in_array($args[0], array(2, 8, 8192)))
            sferror($args);
    }
}

function func_error_crawl()
{
    $args = func_get_args();
    if (empty($args)) {
        return;
    }
    if ( DEBUG != 3 ) {
        if (!in_array($args[0], array(2, 8, 8192))) {
            sfconsole('error:');
            if (DEBUG == 2) {
                sfconsole(array($args[0], $args[1], $args[2], $args[3]));
            } else {
                sfconsole($args);
            }
        }
    } else {
        if (!in_array($args[0], array(2, 8, 8192)))
            sferror($args);
    }
}

function func_exception_crawl()
{
    $args = func_get_args();
    if (empty($args)) {
        return;
    }
    if ( DEBUG != 3 ) {
        if (!in_array($args[0], array(2, 8, 8192))) {
            sfconsole('error:');
            if (DEBUG == 2) {
                sfconsole(array($args[0], $args[1], $args[2], $args[3]));
            } else {
                sfconsole($args);
            }
        }
    } else {
        if (!in_array($args[0], array(2, 8, 8192)))
            sferror($args);
    }
}

register_shutdown_function('func_shutdown_crawl');
set_error_handler('func_error_crawl');
set_exception_handler('func_exception_crawl');
//错误处理  ::结束

/**
 * 注册事件
 * @param $event    事件名称
 * @param Method|string $object   方法对象
 * @param null $key 事件唯一键名，默认则叠加不替换
 * @return int|null 事件唯一键名
 */
function sfregister_event($event, $object, $key = null)
{
    if ($key === null) {
        $GLOBALS['events'][$event][] = $object;
        $key = count($GLOBALS['events'][$event]) - 1;
    } else {
        $GLOBALS['events'][$event][$key] = $object;
    }
    return $key;
}

/**
 * 触发事件
 * @param $event 事件名称
 * @param int $retMode 返回模式（-1直接返回, 0不返回, 1累积结果返回）
 * @param string $scale 比例范围
 * @return array|mixed|null 事件处理后的结果
 */
function sftrigger_event($event, $retMode = 1, $scale = null)
{
    $params = func_get_args();
    array_splice($params, 0, 3);

    if (!empty($GLOBALS['events'][$event])) {
        $returns = array();
        $events = $GLOBALS['events'][$event];
        //部分处理逻辑
        if (!empty($scale) && is_numeric(strpos($scale, '-'))) {
            $total = count($events);
            $len = $total / 10;
            list($begin, $end) = explode('-', $scale);
            if ($end > $begin) {
                $qtyBegin = floor($begin * $len);
                $qtyEnd = floor(($end - $begin)  * $len);
                if ($qtyBegin < $total) {
                    $events = array_slice($events, $qtyBegin, $qtyEnd < 1 ? 1 : $qtyEnd);
                } else {
                    $events = array();
                }
            }
        }
        foreach ($events as $key => $value) {
            try {
                switch ($retMode) {
                    case -1:
                        if (is_object($value)) {
                            $result = call_user_func_array(array($value, 'execute') , array($params));
                            if ($result !== null) {
                                return $result;
                            }
                        } elseif (is_string($value)) {
                            $result = include $value;
                            if ($result !== null) {
                                return $result;
                            }
                        }
                        break;
                    case 0:
                        if (is_object($value)) {
                            call_user_func_array(array($value, 'execute') , array($params));
                        } else {
                            include $value;
                        }
                        break;
                    case 1:
                        if (is_object($value)) {
                            $returns[] = call_user_func_array(array($value, 'execute') , array($params));
                        } else {
                            $returns[] = include $value;
                        }
                        break;
                }
            } catch (Exception $e) {
                if (!isset($GLOBALS['event_exceptions'])) {
                    $GLOBALS['event_exceptions'] = array($event => array());
                } else {
                    if (!isset($GLOBALS['event_exceptions'][$event])) {
                        $GLOBALS['event_exceptions'][$event] = array();
                    }
                }
                $GLOBALS['event_exceptions'][$event][] = $e;
            }
        }
        if ($retMode === 1) {
            return $returns;
        }
    }
    return null;
}

/**
 * 移除事件
 * @param $event    事件名称
 * @param $key  事件唯一键名
 */
function sfremove_event($event, $key)
{
    unset($GLOBALS['events'][$event][$key]);
}

/**
 * 清除所有事件
 * @param $event    事件名称
 */
function sfclear_event($event)
{
    unset($GLOBALS['events'][$event]);
}

/**
 * 获取事件异常
 * @param $event    事件名称
 * @param null $nullReturn  无异常内容时默认返回的异常对象
 * @return Exception    事件的第一个异常对象
 */
function sfget_event_exception($event, $nullReturn = null)
{
    if (empty($GLOBALS['event_exceptions'][$event])) {
        return $nullReturn;
    }
    return current($GLOBALS['event_exceptions'][$event]);
}

/**
 * 获取事件异常
 * @param $event    事件名称
 * @return array    事件的第一个异常对象
 */
function sfget_event_exception_list($event)
{
    return $GLOBALS['event_exceptions'][$event];
}


/**
 * 获取IP地址
 * @return array|false|mixed|string
 */
function sfget_ip()
{
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $realip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv( "HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return empty($realip) ? '-' : $realip;
}

/**
 * 将 xml 文件解析成数组并返回数组
 * @param  String $file 文件路径
 * @return array
 */
function sfdecode_xml_file_to_array($file)
{
    return sfparse_xml_to_array(file_get_contents($file));
}

/**
 * 将 xml 文件解析成数组并返回数组
 * @param  String $data   xml 格式字符串
 * @return array
 */
function sfparse_xml_to_array($data)
{
    if (empty($data)) {
        return array();
    }
    $xml = new SimpleXMLElement($data);
    return json_decode(json_encode($xml), true);
}

/**
 * 输出分割后的第几元素
 * @param $string string   分割字符串
 * @param string $char  分割字符
 * @param int $index    元素下表
 * @return string
 */
function sfstr_index($string, $char = ',', $index = 0)
{
    $strs = explode($char, $string);
    return $strs[$index];
}

/**
 * 有一个是否有效数据
 * @param $param array    数组参数
 * @param $config array   判断配置，array('id', 'no')，如果id存在有值，则返回id；array('no' => 'numeric|array|string')表示no为特定类型，则返回no；array('type' => array('YES', 'NO'))如果type符合YES或NO，则返回type
 * @return bool|mixed|string    返回有效的数组key，为false为无效
 */
function sfget_valid_one($param, $config)
{
    if (is_string($param)) {
        $param = array($param);
    }
    foreach ($config as $key => $value) {
        if (is_numeric($key)) {
            if (!empty($param[$value])) {
                return $value;
            }
        } else {
            if (is_array($value)) {
                if (in_array($param[$key], $value)) {
                    return $key;
                }
            } else {
                switch ($value) {
                    case 'numeric':
                        if (is_numeric($param[$key])) {
                            return $key;
                        }
                        break;
                    case 'array':
                        if (is_array($param[$key])) {
                            return $key;
                        }
                        break;
                    case 'string':
                        if (!is_numeric($param[$key])) {
                            return $key;
                        }
                        break;
                }
            }
        }
    }
    return false;
}

/**
 * 是否有效数组
 * @param $param array    数组参数
 * @param $config array   判断配置，array('id', 'code')表示id和code不可为空；array('no' => 'numeric|array|string')表示no必须为特定类型；array('type' => array('YES', 'NO'))表示type的值班必须为YES或则NO
 * @return bool|int|mixed|string    为true则有效，其它则为错误的key值
 */
function sfis_valid($param, $config)
{
    if (is_string($param)) {
        $param = array($param);
    }
    foreach ($config as $key => $value) {
        if (is_numeric($key)) {
            if (!array_key_exists($value, $param)) {
                return $value;
            }
        } else {
            if (is_array($value)) {
                if (!in_array($param[$key], $value)) {
                    return $key;
                }
            } else {
                switch ($value) {
                    case 'numeric':
                        if (!is_numeric($param[$key])) {
                            return $key;
                        }
                        break;
                    case 'array':
                        if (!is_array($param[$key])) {
                            return $key;
                        }
                        break;
                    case 'string':
                        if (is_numeric($param[$key])) {
                            return $key;
                        }
                        break;
                }
            }
        }
    }
    return true;
}

/**
 * 数组里的结果是否都有效
 * @param $array    数组数据
 * @return bool|null    都有效返回true，空数组返回null，其中一个错误则返回false
 */
function sfarray_true($array) {
    if (empty($array)) {
        return null;
    }
    foreach ($array as $key => $value) {
        if (!$value) {
            return false;
        }
    }
    return true;
}

/**
 * 转换md5短码
 * @param $a    md5值
 * @return string   md5短码
 */
function sfmd5_short($a){
    for($a = md5( $a, true ),
        $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
        $d = '',
        $f = 0;
        $f < 8;
        $g = ord( $a[ $f ] ),
        $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
        $f++
    );
    return $d;
}

/**
 * 加密密码
 * @param $code 原始密码
 * @return string   加密后的密码  md5:XX
 */
function sfencode($code)
{
    $rand=substr(md5(rand(1, time())),rand(1, 10),2);
    $str1=substr($rand, 0,1);
    $str2=substr($rand, 1,1);
    $number=(int)(is_numeric($str1)?$str1:is_numeric($str2)?$str2:strlen($code)/2);
    $leftStr=substr($code, 0,$number);
    $rightStr=substr($code, $number);
    return md5($leftStr.$str2.$rightStr.$str1).':'.$rand;
}

/**
 * 判断密码是否正确
 * @param $code 原始用户输入的密码
 * @param $eCode    数据库加密后的密码 md5:XX
 * @return bool 有效则返回 true
 */
function sfcheck($code, $eCode)
{
    $array=explode(':', $eCode);
    if (count($array) !== 2) {
        return false;
    }
    $str1=substr($array[1], 0,1);
    $str2=substr($array[1], 1,1);
    $number=(int)(is_numeric($str1)?$str1:is_numeric($str2)?$str2:strlen($code)/2);
    $leftStr=substr($code, 0,$number);
    $rightStr=substr($code, $number);
    return md5($leftStr.$str2.$rightStr.$str1)==$array[0];
}


/**
 * 加密字符串
 * @param $str  需要加密的字符串
 * @return mixed|string 加密后的字符串
 */
function sfenstr($str)
{
    $str = openssl_encrypt($str, "AES-256-CBC", ENDE_KEY);
    return sfurl_encode($str);
}

/**
 * 解密字符串
 * @param $str  加密后的字符串
 * @return string   解密后的字符串
 */
function sfdestr($str)
{
    $str = sfurl_decode($str);
    return trim(openssl_decrypt($str, "AES-256-CBC", ENDE_KEY));
}

/**
 * 堆送后台临时信息(显示需模板支持，结束即销毁)
 * @param String $content   内容
 * @param String $title     标题（可选）
 */
function sfpush_admin_tmp_message($content, $title = 'Message', $level = 'normal')
{
    Core_Base::session();
    if (!isset($GLOBALS['admin_timestamp'])) {
        $GLOBALS['admin_timestamp'] = time();
    }
    if (isset($_SESSION['admin_tmp_messages'])) {
        $messages = $_SESSION['admin_tmp_messages'];
    }
    if (empty($messages)) {
        $messages = array();
    }
    $messages[] = array(
        'level' => $level,
        'title' => $title,
        'content' => $content,
        'timestamp' => $GLOBALS['admin_timestamp']
    );
    $_SESSION['admin_tmp_messages'] = $messages;
}
/**
 * 获取后台临时信息
 */
function sfget_admin_tmp_messages()
{
    Core_Base::session();
    if ( isset($_SESSION['admin_tmp_messages']) ) {
        return $_SESSION['admin_tmp_messages'];
    } else {
        return null;
    }
}

/**
 * 堆送后台临时信息(显示需模板支持，结束即销毁)
 * @param String $content   内容
 * @param String $title     标题（可选）
 */
function sfpush_tmp_message($content, $title = 'Message', $level = 'error')
{
    Core_Base::session();
    if (!isset($GLOBALS['timestamp'])) {
        $GLOBALS['timestamp'] = time();
    }
    if (isset($_SESSION['tmp_messages'])) {
        $messages = $_SESSION['tmp_messages'];
    }
    if (empty($messages)) {
        $messages = array();
    }
    $messages[] = array(
        'level' => $level,
        'title' => $title,
        'content' => $content,
        'timestamp' => $GLOBALS['timestamp']
    );
    if (REWRITE_FILE_STATUS == true) {
        setcookie('tmp_messages', json_encode($messages), time() + 1800, '/');
    } else {
        $_SESSION['tmp_messages'] = $messages;
    }

}
/**
 * 获取后台临时信息
 */
function sfget_tmp_messages()
{
    Core_Base::session();
    if ( isset($_SESSION['tmp_messages']) ) {
        return $_SESSION['tmp_messages'];
    } else {
        return null;
    }
}

/**
 * 输出ini格式的配置键值
 * @param $setting  配置字符串，如 OK=active;NO=empty;
 * @param $k    配置键名，如NO
 * @return mixed    返回指定键值
 */
function sfini_kv($setting, $k)
{
    $datas = parse_ini_string($setting);
    return $datas[$k];
}

/**
 * 自动转换编码为 utf-8
 * @param $data 需要转换的数据
 * @return string   转换后的字符
 */
function sfcharacet($data)
{
    if ( !empty($data) ) {
        $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
        if ( $fileType != 'UTF-8') {
            $data = mb_convert_encoding($data ,'utf-8' , $fileType);
        }
    }
    return $data;
}

/**
 * 获取一个按照事件生成的长编号
 * @param string $added 附加字符串
 * @return string   编号
 */
function sfget_now_time_long_number($added = '')
{
    $time = time();
    $year = date('Y', $time);
    $cY = (int)substr($year, 2, 1);
    if ($cY == 0) {
        $cY = 1;
    }
    list($usec, $sec)   =   explode(' ', microtime());
    $ip = sfget_ip();
    $ip = ip2long($ip);
    $ip = sprintf('%05s', abs($ip));
    return $cY . substr(date('ymdHis',$time), 1).substr($usec, 2, 2) . $ip . rand(1, 9) . $added;
}

/**
 * 获取一个唯一字符串
 * @return string   唯一字符串
 */
function sfget_unique()
{
    list($usec, $sec)   =   explode(' ', microtime());
    $s = $sec + $usec;
    $s = explode('.', $s);
    $s[1] = sprintf('%04s', $s[1]);
    $s = implode('', $s);
    return $s . sprintf('%04s', rand(0, 1000));
}

/**
 * 判断插件是否别拓展
 * @param  String  $className
 * @return boolean
 */
function sfis_extended($className)
{
    $className = str_replace('_', DS, $className);
    $exCurFilePath = EXMODULE_CUR_DIR . DS . $className . '.php';
    if (file_exists($exCurFilePath)) {
        return TRUE;
    } else {
        $exDefFilePath = EXMODULE_DEF_DIR . DS . $className . '.php';
        if (file_exists($exDefFilePath)) {
            return TRUE;
        }
        return FALSE;
    }
}

/**
 * 判断是否存在实例
 * @param $className    例如 Core_Base
 * @param null $object  单例的域
 * @param null $scope   单例的域组
 * @return bool
 */
function sfis_instance($className, $object = null, $scope = null)
{
    $className = ucfirst($className);
    if (sfis_extended($className)) {
        $className = 'Ex_' . $className;
    }
    /**
     * 若 $object 不为 null，则返回 $object 范围下的单例，
     * 否则遍历 $scope 范围下单例，若不存在，则返回全局单例。
     */
    if (is_object($object)) {
        $objName    =   get_class($object);
        if (isset($GLOBALS['instances'][$objName][$className])) {
            return true;
        }
    } else {
        if (is_array($scope)) {
            foreach ($scope as $key => $objName) {
                if (isset($GLOBALS['instances'][$objName][$className])) {
                    return true;
                }
            }
        }
    }
    if (class_exists($className)) {
        if (isset($GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$className])) {
            return true;
        }
    }
    return false;
}

/**
 * @param $className    例如 Core_Base
 * @param null $object  单例的域
 * @param null $scope   单例的域组
 * @return Core_Base
 */
function sfget_instance($className, $object = null, $scope = null)
{
    $className = ucfirst($className);
    $argList = array_slice(func_get_args(), 3);
    if (sfis_extended($className)) {
        $className = 'Ex_' . $className;
    }
    /**
     * 若 $object 不为 null，则返回 $object 范围下的单例，
     * 否则遍历 $scope 范围下单例，若不存在，则返回全局单例。
     */
    if (is_object($object)) {
        $objName    =   get_class($object);
        if (!isset($GLOBALS['instances'][$objName][$className])) {
            $GLOBALS['instances'][$objName][$className] = new $className();
        }
        $GLOBALS['instances'][$objName][$className]->apply($argList);
        return $GLOBALS['instances'][$objName][$className];
    } else {
        if (is_array($scope)) {
            foreach ($scope as $key => $objName) {
                if (isset($GLOBALS['instances'][$objName][$className])) {
                    $GLOBALS['instances'][$objName][$className]->apply($argList);
                    return $GLOBALS['instances'][$objName][$className];
                }
            }
        }
    }
    if (class_exists($className)) {
        if (!isset($GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$className])) {
            $GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$className] = new $className();
        }
        $GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$className]->apply($argList);
    }
    return $GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$className];
}

/**
 * 输出图片URL地址
 * @param $path 图片路径
 * @param null $width   图片宽度
 * @param null $height  图片高度
 * @param int $opacity  背景透明度
 * @param null $baseDir 根目录
 * @return mixed    图片URL地址
 */
function sfimg($path, $width = null, $height = null, $opacity = 127, $baseDir = null)
{
    $image = sfget_instance('Core_Image');
    return $image->getConvertUrl($path, $width, $height, $opacity, $baseDir);
}


//加密
function sfbase64_encode($char) {
    $asciivalue = ord($char);

    //判断是否为数字
    if ($asciivalue >= 48 && $asciivalue <= 57) {
        return '99' . $char;
    }

    //判断是否为小写字母
    if ($asciivalue >= 97 && $asciivalue <= 122) {
        return '88' . $char;
    }

    $result = '';
    //判断ascii值是否为三位数，若是则直接返回，若不是则补全三位
    switch (strlen($asciivalue)) {
        case 1:
            $result = '77' . strval($asciivalue);
            break;
        case 2:
            $result = '6' . strval($asciivalue);
            break;
        case 3:
            $result = strval($asciivalue);
            break;
        default:
            break;
    }
    return $result;
}

//解密
function sfbase64_decode($strtemp) {
    $judge = substr($strtemp, 0, 2);

    $result = '';
    //判断字符串类型
    switch ($judge) {
        case '99':
        case '88':
            $result = substr($strtemp, 2, 1);
            break;
        case '77':
            $result = chr(intval(substr($strtemp, 2, 1)));
            break;
        default:
            if (substr($judge, 0, 1) == '6') {
                $result = chr(intval(substr($strtemp, 1, 2)));
            } else {
                $result = chr(intval(substr($strtemp, 2, 1)));
            }
            break;
    }
    return $result;
}

/**
 * 获取URL地址
 * @param string $path  路径
 * @param null $param   附加参数
 * @return bool|string  URL地址
 */
function sfurl($path = '/', $param = null)
{
    $request = sfget_instance('Core_Request');
    $url = $request->getUrlByPath($path, $param);
    $index = strlen($url);
    if ($url[$index - 1] == '?') {
        $url = substr($url, 0, $index - 1);
    }
    return $url;
}

/**
 * URL转义
 * @param $url  url地址
 * @return mixed|string 转义后的URL地址
 */
function sfurl_encode($url)
{
    $url = base64_encode($url);
    $turl = '';
    for ($i = 0; $i < strlen($url); $i++) {
        $turl .= sfbase64_encode(substr($url, $i, 1));
    }
    $url = $turl;
    $url = str_replace('+', '_a', $url);
    $url = str_replace('/', '_b', $url);
    $url = str_replace('=', '_c', $url);
    return $url;
}

/**
 * URL反转义
 * @param $url  已转义的URL地址
 * @return bool|string  反转义后的URL地址
 */
function sfurl_decode($url)
{
    $url = str_replace('_a', '+', $url);
    $url = str_replace('_b', '/', $url);
    $url = str_replace('_c', '=', $url);
    $turl = '';
    for ($i = 0; $i < strlen($url); $i+=3) {
        $turl .= sfbase64_decode(substr($url, $i, 3));
    }
    $url = $turl;
    return base64_decode($url);
}

/**
 * 重定向URL
 * @param null $url 重定向后的URL地址
 * @param bool $redirect    是否接受参数重定向
 */
function sfredirect($url = null, $redirect = true)
{
    $request = sfget_instance('Core_Request');
    if ($url === null && $redirect && isset($_REQUEST['redirect'])) {
        $url = $_REQUEST['redirect'];
    }
    if (is_numeric($url)) {
        $request->go($url, false);
    } else {
        $request->redirect($url);
    }
}

/**
 * 回退重定向
 * @param $setp 回退第几步，负数为回退，正数为前进
 * @param bool $return  是否只返回
 * @return mixed    需要回退的URL地址
 */
function sfgo($setp, $return = false)
{
    $request = sfget_instance('Core_Request');
    return $request->go($setp, $return);
}

/**
 * 按环境输出响应客户端
 * @param int $result   响应结果
 * @param string $message   响应消息
 * @param string $data  响应数据
 */
function sfresponse($result = 1, $message = '', $data = '')
{
    $request = sfget_instance('Core_Request');
    if ($result === true) {
        $result = 1;
    }
    if ($result === false) {
        $result = 0;
    }
    $ret = array(
        'result' => $result
        , 'message' => $message
        , 'data' => $data
    );
    $key = $request->getExtensionFunctionKey();
    $fun = $request->getExtensionFunctionName();
    $header = true;
    if (!empty($fun)) {
        switch (strtolower($key)) {
            case 'xml':
                header("Content-type:text/xml");
                $header = false;
                break;
        }
        sfquit($fun($ret), $header);
    } else {
        if (FLAG_AJAX) {
            sfquit(json_encode($ret), $header);
        }
    }
    if (!empty($message)) {
        if (defined('FLAG_ADMIN')) {
            sfpush_admin_tmp_message($message);
        } else {
            sfpush_tmp_message($message);
        }
    }
    sfredirect();
}

/**
 * 伪造方法，主要用于伪代码编写
 * @param $path 路径，/asset/forge下的相对路径
 * @param array $param 附加参数
 * @return array|null   结果数据
 */
function sfgetforge($path, $param = array())
{
    $whereRow = null;
    if (!empty($param)) {
        $whereRow = false;
    }
    $file = ROOT_DIR . '/asset/forge/' . $path . '.csv';
    if (is_file($file)) {
        $returns = array();
        $datas = Core_IoUtils::instance()->getCsv($file);
        if (!empty($datas)) {
            $cols = $datas[0];
            for ($i = 1, $len = count($datas); $i < $len; $i++) {
                $value = $datas[$i];
                $data = array();
                foreach ($value as $k => $v) {
                    $data[$cols[$k]] = $v;
                    foreach ($param as $pk => $pv) {
                        if ($data[$pk] == $pv) {
                            $whereRow = true;
                        }
                    }
                }
                if ($whereRow) {
                    return array($data);
                }
                $returns[] = $data;
            }
        }
        if ($whereRow === null) {
            return $returns;
        }
    }
    return null;
}

/**
 * 序列输出数据
 * @param $value    需要输出的数据
 * @param bool $exit    是否直接结束
 */
function sfdump($value, $exit = false)
{
    //header("Content-type:text/html;charset=utf-8");
    echo '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="content-type" content="text/html;charset=utf-8"></head><body><pre>';
    print_r($value);
    echo '</pre></body></html>';
    if ($exit && !isset($_REQUEST['dump'])) {
        sfquit();
    }
}

/**
 * 清空左右空格
 * @param $value    需要清空的数据
 * @return array|string 清空后的数组
 */
function sftrim(&$value)
{
    if (is_array($value)) {
        foreach ($value as $k => $v) {
            if ($v !== null) {
                if ($v === '') {
                    unset($value[$k]);
                    continue;
                }
                if (is_string($v)) {
                    $v = trim($v);
                    $value[$k] = $v;
                } else {
                    foreach ($v as $k1 => $v1) {
                        if ($v1 === '') {
                            unset($v[$k1]);
                            continue;
                        }
                        if (is_string($v1)) {
                            $v[$k1] = trim($v1);
                        }
                    }
                    $value[$k] = $v;
                }
            }
        }
    } else {
        $value = trim($value);
    }
    reset($value);
    return $value;
}

/**
 * 加密JSON为UTF8结果
 * @param $matchs   数组数据
 * @return false|string 加密后的字符串
 */
function sfjson_encode_ex_to_utf8($matchs)
{
    return iconv('UCS-2BE', 'UTF-8',  pack('H4',  $matchs[1]));
}

/**
 * 加密JSON为中文可见数据
 * @param $value    数组数据
 * @return false|string|string[]|null   加密后的字符串
 */
function sfjson_encode_ex($value)
{
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
         $str = json_encode($value);
         $str =  preg_replace_callback("#\\\u([0-9a-f]{4})#i", 'sfjson_encode_ex_to_utf8', $str);
         return  $str;
    } else {
         return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}

/**
 * 404页面
 */
function sf404()
{
    header('HTTP/1.1 404 not found!');
    include ROOT_DIR . DS . 'theme/404.html';
}

/**
 * 输出随机的字符串
 * @param $len  随机长度
 * @param null $chars   随机可选的字符集合字符串
 * @return string   随机的字符串
 */
function sfrand_string($len, $chars=null)
{
    if (is_null($chars)){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }
    mt_srand(10000000*(double)microtime());
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
        $str .= $chars[mt_rand(0, $lc)];
    }
    return $str;
}

/**
 * 是否是开发者IP地址
 * @return bool
 */
function sfis_dev_ip()
{
    $ips = explode(',', $GLOBALS['etc']['dev_ip']);
    if (in_array(sfget_ip(), $ips)) {
        return true;
    }
    return false;
}

/**
 * 获取需要开发引入的调试文件
 * @param $name 调试工具下的名称
 * @return string 需要引入的调试文件
 */
function sfdebug_file($name)
{
    $rootDir = ROOT_DIR . DS . 'shell' . DS . 'tool' . DS . 'debug' . DS;
    $isDebug = false;
    if (sfis_dev_ip()) {
        $isDebug = true;
    }
    if (!empty($_COOKIE['dev_date'])) {
        $date = sfdestr($_COOKIE['dev_date']);
        if (date('Y-m-d') == date('Y-m-d', $date)) {
            $isDebug = true;
        }
    }
    if (!$isDebug && $_SESSION['_ende_key'] == 1) {
        $isDebug = true;
    }
    if ($isDebug) {
        $file = $rootDir . $name . '.php';
        if (is_file($file)) {
            return $file;
        }
    }
    return $rootDir . 'empty.php';
}

/**
 * 调试数据写入到日志文件
 * @param $content  调试数据
 * @param string $type  调试类型
 * @param null $params  附加参数
 * @return string   调试的文件路径
 */
function sfdebug($content, $type = 'default', $params = null)
{
    $time = time();
    $dir_debug = ROOT_DIR . '/asset/internal/debug';
    if(!is_dir($dir_debug)){
        mkdir($dir_debug, 0777, true);
    }
    $dir_debug = $dir_debug . DS . $type . DS . date('Y-m-d', $time) . DS . date('H', $time) . DS . (int)(date('i', $time) / 10);
    if(!is_dir($dir_debug)){
        mkdir($dir_debug, 0777, true);
    }
    if(isset($params['file_name'])){
        $file_name = $params['file_name'] . '.log';
    }else{
        $file_name = date('Y-m-d_H_i_s', $time) . '.log';
    }
    $file_debug = $dir_debug . DS . ip2long(FUNC_IP) . '_' . $file_name;
    $start_content = '';
    $end_content = '';
    if (isset($params['title'])) {
        $start_content = ("\r\n------------------------------------" . $params['title'] . "[Start]\r\n");
        $end_content = ("\r\n------------------------------------" . $params['title'] . "[End]\r\n");
    }
    if (isset($params['save_mode']) && strcmp($params['save_mode'], 'ser') == 0){
        file_put_contents($file_debug, $start_content . serialize($content) . $end_content, FILE_APPEND);
    } else {
        file_put_contents($file_debug, $start_content . print_r($content, true) . $end_content, FILE_APPEND);
    }
    if (!empty($params['backup'])) {
        @copy($params['backup'], $file_debug . '.bak');
    }
    return $file_name;
}

/**
 * 保存日志数据
 * @param $type 日志类型
 * @param $id   类型关键编号
 * @param $content  日志内容
 * @param null $params  附加参数
 * @return mixed    是否保存成功
 */
function sflog($type, $id, $content, $params = null)
{
    if (!empty($params['adminID'])) {
        $adminID = $params['adminID'];
    } else {
        $adminID = $_REQUEST['adminID'];
    }
    $data = array_key_exists('data', $params) ? $params['data'] : null;
    $level = isset($params['level']) ? $params['level'] : null;
    $log = sfget_instance('Core_Model_Operationlog');
    return $log->insertOperationlog($type, $id, $content, $data, $adminID, $level);
}
/**
 * 达到预期效果时调用的完成日志
 */
function sflog_complete($type, $id, $content, $data = null, $adminID = null)
{
    $params = array(
        'adminID' => $adminID
        , 'data' => $data
        , 'level' => 'C'
    );
    return sflog($type, $id, $content, $params);
}

/**
 * 未达到预期但不必要情况下调用的警告日志
 */
function sflog_warning($type, $id, $content, $data = null, $adminID = null)
{
    $params = array(
        'adminID' => $adminID
        , 'data' => $data
        , 'level' => 'W'
    );
    return sflog($type, $id, $content, $params);

}

/**
 * 可能会造成严重后果时调用的致命日志
 */
function sflog_fatal($type, $id, $content, $data = null, $adminID = null)
{
    $params = array(
        'adminID' => $adminID
        , 'data' => $data
        , 'level' => 'F'
    );
    return sflog($type, $id, $content, $params);
}
/**
 * 达到预期效果时调用的完成日志
 */
function sflog_complete_in($type, $id, $content, $data = null, $adminID = null)
{
    $params = array(
        'adminID' => $adminID
        , 'data' => $data
        , 'level' => 'C'
        , 'object' => 'I'
    );
    return sflog($type, $id, $content, $params);
}

/**
 * 未达到预期但不必要情况下调用的警告日志
 */
function sflog_warning_in($type, $id, $content, $data = null, $adminID = null)
{
    $params = array(
        'adminID' => $adminID
        , 'data' => $data
        , 'level' => 'W'
        , 'object' => 'I'
    );
    return sflog($type, $id, $content, $params);

}

/**
 * 可能会造成严重后果时调用的致命日志
 */
function sflog_fatal_in($type, $id, $content, $data = null, $adminID = null)
{
    $params = array(
        'adminID' => $adminID
        , 'data' => $data
        , 'level' => 'F'
        , 'object' => 'I'
    );
    return sflog($type, $id, $content, $params);
}

/**
 * 达到预期效果时调用的完成日志
 */
function sflog_complete_dev($type, $id, $content, $data = null, $adminID = null)
{
    $params = array(
        'adminID' => $adminID
        , 'data' => $data
        , 'level' => 'C'
        , 'object' => 'P'
    );
    return sflog($type, $id, $content, $params);
}

/**
 * 未达到预期但不必要情况下调用的警告日志
 */
function sflog_warning_dev($type, $id, $content, $data = null, $adminID = null)
{
    $params = array(
        'adminID' => $adminID
        , 'data' => $data
        , 'level' => 'W'
        , 'object' => 'P'
    );
    return sflog($type, $id, $content, $params);

}

/**
 * 可能会造成严重后果时调用的致命日志
 */
function sflog_fatal_dev($type, $id, $content, $data = null, $adminID = null)
{
    $params = array(
        'adminID' => $adminID
        , 'data' => $data
        , 'level' => 'F'
        , 'object' => 'P'
    );
    return sflog($type, $id, $content, $params);
}

/**
 * 重定向到错误页面
 * @param null $errors  指定的错误数组
 */
function sferror($errors = null)
{
    if ($errors) {
        $GLOBALS['errors'] = $errors;
    }
    ob_clean();
    $errors = print_r(func_get_args(), true);
    if (!empty($errors)) {
        if (is_numeric(strpos(DOMAIN, 'localhost')) && DEV_MODE == 1 || is_numeric(strpos(DOMAIN, '127.0.0.1')) && DEV_MODE == 1) {
            sfdump($errors);
            sfquit();
        } else {
            $number = sfmd5_short(md5($errors));
            $dir = ROOT_DIR . DS . 'asset' . DS . 'internal' . DS . 'report';
            Core_IoUtils::instance()->writeFile($dir . DS . $number . '.log', $errors);
            $GLOBALS['report'] = $number;
        }
    }
    if (FLAG_AJAX) {
        try {
            sfget_instance('Core_Request')->setExtensionFunction('json');
        } catch (Exception $e){}
        sfresponse(0, "请求失败，联系技术客服码：" . $number, 500);
    } else {
        include ROOT_DIR . DS . 'theme' . DS . 'error.phtml';
    }
    sfquit();
}

/**
 * 系统专用调试异常
 * @param $msg string  错误信息
 * @param int $code 错误代码
 * @throws Exception    异常
 */
function sfexception($msg, $code = 0) {
    $debugInfo = debug_backtrace();
    $msg = date('Y-m-d H:i:s') . ' ' . $debugInfo[0]['file']. ' ('.$debugInfo[0]['line'].')： '. $msg;
    throw new Exception($msg, $code);
}

/**
 * 根据当前文件和环境获取块文件路径
 * @param $__FILE__ 当前文件__FILE__
 * @param $name 块名称
 * @return string   块文件路径
 */
function sfget_block_file($__FILE__, $name)
{
    $ext = 'on';
    if (DEV_MODE == 1) {
        $ext = 'dev';
    }
    $basePath = dirname($__FILE__) . DS . 'Block' . DS . $name;
    $file =  sprintf('%s.%s.php', $basePath, $ext);
    if (is_file($file)) {
        return $file;
    }
    $file =  sprintf('%s.on.php', $basePath);
    if (is_file($file)) {
        return $file;
    }
    $file =  sprintf('%s.dev.php', $basePath);
    if (is_file($file)) {
        return $file;
    }
    sferror(sprintf('查找不到：' . $file . '文件块！'));
}

/**
 * 执行控制台日志逻辑
 */
function sfconsole_logs()
{
    if (!empty($_SESSION['_log_flag']) && !empty($GLOBALS['console_logs'])) {
        $dataJson = sfjson_encode_ex($GLOBALS['console_logs']);
        $kMd5 = sfmd5_short(md5($dataJson));
        Core_Cache::instance()->setArea(15)->set('Console:' . Core_Request::instance()->getCurUrl() . '|' . $kMd5, $dataJson);
    }
}

/**
 * 结束系统
 * @param null $message 结束前输出的信息
 * @param bool $header  是否输出头编码
 */
function sfquit($message = null, $header = true)
{
    Core_Base::session();
    if (isset($_SESSION['admin_tmp_messages'])) {
        $timestamp = $_SESSION['admin_tmp_messages'];
        if (isset($GLOBALS['admin_timestamp'])) {
            $GLOBALS['admin_timestamp'] = 0;
        }
        if (!isset($GLOBALS['admin_timestamp']) || $timestamp != $GLOBALS['admin_timestamp']) {
            unset($_SESSION['admin_tmp_messages']);
        }
    }
    if (isset($_SESSION['tmp_messages'])) {
        $timestamp = $_SESSION['tmp_messages'];
        if (isset($GLOBALS['timestamp'])) {
            $GLOBALS['timestamp'] = 0;
        }
        if (!isset($GLOBALS['timestamp']) || $timestamp != $GLOBALS['timestamp']) {
            unset($_SESSION['tmp_messages']);
        }
    }
    if (isset($_SESSION['?'])) {
        unset($_SESSION['?']);
    }
    session_write_close();
    list($usec, $sec) = explode(' ', microtime());
    define('END_TIME', ((float)$usec + (float)$sec));
    $seconds = number_format((END_TIME - BEGIN_TIME) , 3);
    $memory = number_format((memory_get_usage() / 1024 / 1024) , 2);
    sfconsole('---------------- Operation statistics ----------------');
    sfconsole('Running time: ' . $seconds . ' seconds');
    sfconsole('Memory size: ' . $memory . ' MB');
    if ($message !== null) {
        if ($header) {
            header("Content-type:text/html;charset=utf-8");
        }
        echo $message;
    }
    if (defined('MAX_SECOND') && $seconds > MAX_SECOND || $seconds > 5) {
        $log = array(
            'location' => sfget_instance('Core_Request')->getCurUrl()
            , 'seconds' => $seconds
            , 'memory' => $memory
            , 'ip' => sfget_ip()
            , 'request' => $GLOBALS['__REQUEST']
            , 'trace' => debug_backtrace()
        );
        if (isset($_SESSION) && isset($_SESSION['admin']['adminID'])) {
            $log['user_id'] = $_SESSION['admin']['adminID'];
        }
        sfdebug($log, 'backtrace');
    }
    if (Core_Model::isOpen()) {
        sfget_instance('Core_Model')->close();
    }
    sfconsole_logs();
    exit();
}

/* 执行模块自动文件 */
$listCacheFile = MODULE_DIR . DS . 'list.cache.php';
$cacheExp = 3600;
$chacheExpIni = sfread_etc_global('config.php', 'module_cache');
if (!empty($chacheExpIni)) {
    $cacheExp = $chacheExpIni;
}
if (is_file($listCacheFile) && @filemtime($listCacheFile) + $cacheExp > time()) {
    include $listCacheFile;
    $modules = json_decode($mList, true);
} else {
    $modules = Core_IoUtils::instance()->scanDir(MODULE_DIR, 1, null, true);
    Core_IoUtils::instance()->writeFile($listCacheFile, sprintf("<?php \$mList = '%s';", json_encode($modules)));
}
foreach ($modules as $module) {
    $autoFile = MODULE_DIR . DS . $module . DS . 'autoload.php';
    if (file_exists($autoFile)) {
        include $autoFile;
    }
}
define('FUNC_IP', sfget_ip());
/**
 * 如参数附带日志要求则记录日志标识
 */
if (array_key_exists('_log', $_REQUEST)) {
    @session_start();
    if ($_REQUEST['_log'] === 0) {
        unset($_SESSION['_log_flag']);
    } else {
        $_SESSION['_log_flag'] = $_REQUEST['_log'];
    }
}
/**
 * 如参数附带加密编码则附带调试标识
 */
if (array_key_exists('enkey', $_REQUEST)) {
    @session_start();
    if ($_REQUEST['enkey'] == ENDE_KEY) {
        $_SESSION['_ende_key'] = 1;
    } else if(intval($_REQUEST['enkey']) == 0) {
        unset($_SESSION['_ende_key']);
    }
}