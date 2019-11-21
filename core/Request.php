<?php
/**
 * 请求类
 */
class Core_Request extends Core_Base
{
    private $url;
    private $protocol;
    private $host;
    private $root;

    private $module = 'default';
    private $controller = 'default';
    private $action = 'index';
    private $dsPath = '';
    private $useSystem = false;
    private $path = array();
    private $query;
    private $exKey;
    private $exFun;
    public $get;
    public $post;

    public $domain = DOMAIN;
    public $baseDir = BASEDIR;
    public $page;

    private $curRoute = array(
                            'module' => 'default',
                            'controller' => 'default',
                            'action' => 'index',
                            'path' => array(),
                            'query' => array()
                        );

    /**
     * 初始化 url
     */
    public function __construct($url = null)
    {
        parent::__construct();
        if ($url != null) {
            $url = strtolower($url);
        } else {
            $url = strtolower($this->curPageUrl());
        }
        $url = str_replace(REWRITE_SUFFIX, '', $url);
        if (!empty($GLOBALS['__LOCAL']['extension'])) {
            foreach ($GLOBALS['__LOCAL']['extension'] as $key => $value) {
                $key = strtolower($key);
                if (is_numeric(strpos($url, '.' . $key))) {
                    $url = str_replace('.' . $key, '', $url);
                    $this->exKey = $key;
                    $this->exFun = $value;
                }
            }
        }
        //解析目录参数映射
        if (isset(self::$templateConfig['dir']) && isset(self::$templateConfig['dir']['map'])) {
            if (empty(self::$templateConfig['dir']['map'][0])) {
                self::$templateConfig['dir']['map'] = array(self::$templateConfig['dir']['map']);
            }
            foreach (self::$templateConfig['dir']['map'] as $key => $value) {
                $lUrl = strtolower($url);
                $bFrom = strtolower($value['from']);
                //http://localhost/dir/[from] => http://localhost/dir?[to]
                if (is_numeric(strpos($lUrl, SITEURL . $bFrom))) {
                    $char = substr($lUrl, strlen(SITEURL . $bFrom), 1);
                    if (empty($char) || $char == '/') {
                        $url = str_replace(SITEURL . $bFrom, SITEURL, $url);//TODO: 大小写
                        $url = str_replace('//', '/', $url);
                        $url = str_replace(':/', '://', $url);
                        list($pk, $pv) = explode('=', $value['to']);
                        $_REQUEST[$pk] = $pv;
                        $_GET[$pk] = $pv;
                    }
                }
            }
        }
        //解析地址映射
        if (isset(self::$templateConfig['request']) && isset(self::$templateConfig['request']['map'])) {
            foreach (self::$templateConfig['request']['map'] as $key => $value) {
                $lUrl = strtolower($url);
                $bUrl = strtolower(basename($url));
                $bFrom = strtolower($value['from']);
                $bTo = strtolower($value['to']);
                $splitI = strpos($bUrl, '-');
                $paramI = strpos($bUrl, '?');
                $okI = is_numeric($splitI) && (is_numeric($paramI) ? $splitI < $paramI : true);
                if (is_numeric(strpos($lUrl, $bFrom)) && $okI) {
                    $url = str_replace($bFrom, $value['to'], $lUrl);
                    $this->redirect($url);
                }
                if (is_numeric(strpos($lUrl, $bTo)) && ($okI || is_numeric($bUrl))) {
                    $url = str_replace($bTo, $value['from'], $lUrl);
                    break;
                } else {
                    $lastName = parse_url(str_replace(SITEURL, '', $url));


                    if (isset($lastName['path'])) {
                        if (isset($lastName['query'])) {
                            $len = strlen($lastName['path']) - 1;
                            if ($lastName['path'][$len] == '/') {
                                $lastName['path'] = substr($lastName['path'], 0, $len);
                            }
                        }
                        $lastName = $lastName['path'];
                        $baseName = basename($value['to']);

                        if (strcasecmp($lastName, $baseName) === 0 || strcasecmp($lastName, $baseName . '/') === 0) {
                            //wrong only 1
                            $url = str_replace('/' . $lastName, $value['from'], $url);
                            break;
                        }
                    }
                }
            }
        }
        $this->url = $url;
        $this->main();
        self::session();
        $useUrl = false;
        $curUrl = strtolower($url);
        if (!isset($_SESSION['cur_url'])) {
            $_SESSION['cur_url'] = $curUrl;
        }
        if ($_SESSION['cur_url'] != $curUrl) {
            $useUrl = true;
        }
        $extension = array('php', substr(REWRITE_SUFFIX, 1));
        $tmp = pathinfo(str_replace(REWRITE_SUFFIX, '', $url));
        $tmp['extension'] = sfret('extension', '', $tmp);
        //异步视图加载方式
        if (!isset($_REQUEST['ajax']) && !FLAG_AJAX && (in_array($tmp['extension'], $extension) || empty($tmp['extension'])) && $useUrl) {
            if (isset($_SESSION['track'])) {
                $track = $_SESSION['track'];
            } else {
                $track = array('history' => array('front' => array(), 'admin' => array()));
            }
            if (!is_array($track['history'])) {
                $track['history'] = array('front' => array(), 'admin' => array());
            } elseif (!isset($track['history']['front']) || !is_array($track['history']['front'])) {
                $track['history']['front'] = array();
            } elseif (!isset($track['history']['admin']) || !is_array($track['history']['admin'])) {
                $track['history']['admin'] = array();
            }

            $curKey = $this->getControllerName() != 'Admin_DefaultController' ? 'front' : 'admin';
            $len = count($track['history'][$curKey]);
            if ($len > 0 && $url == $track['history'][$curKey][$len - 1]) {
                //防止刷新重复
            } else {
                if ($len >= 3) {
                    if ($url == $track['history'][$curKey][1]) {
                        $track['history'][$curKey][1] = $track['history'][$curKey][0];
                    }
                    $track['history'][$curKey] = array(
                        0 => $track['history'][$curKey][1],
                        1 => $track['history'][$curKey][2],
                        2 => $_SESSION['cur_url']
                    );
                } else {
                    array_push($track['history'][$curKey], $_SESSION['cur_url']);
                }
                $_SESSION['cur_url'] = $curUrl;
            }
            $_SESSION['track'] = $track;
        }
    }

    public function setExtensionFunction($key, $rep = true)
    {
        if (!$rep && !empty($this->exKey)) {
            return true;
        }
        if (!empty($GLOBALS['__LOCAL']['extension'])) {
            foreach ($GLOBALS['__LOCAL']['extension'] as $k => $v) {
                $k = strtolower($k);
                if ($key == $k) {
                    $this->exKey = $k;
                    $this->exFun = $v;
                    return true;
                }
            }
        }
        return false;
    }

    public function getExtensionFunctionKey()
    {
        return $this->exKey;
    }

    public function getExtensionFunctionName()
    {
        return $this->exFun;
    }


    /**
     * 若是自定义 URL, 则返回系统原始 URL
     */
    public function getOriginUrl($url)
    {
        return $url;//custom
        $result = self::$cache->getUrl($url);
        if (!empty($result)) {
            return $result;
        }
    }


    public function main()
    {
        $this->get = $_GET;
        $this->post = $_POST;

        $this->parseUrl();
    }

    /**
     * 生成当前页面的 URL
     * @return string 当前页面 url
     */
    private function curPageUrl()
    {
        $pageUrl = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
            $pageUrl .= "s";
        }
        $pageUrl .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageUrl .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . urldecode($_SERVER["REQUEST_URI"]);
        } else {
            $pageUrl .= $_SERVER["HTTP_HOST"] . urldecode($_SERVER["REQUEST_URI"]);
        }
        return $pageUrl;
    }

    /**
     * 解析 URL
     *
     * @access private
     */
    private function parseUrl()
    {
        $data = parse_url($this->url);
        $this->protocol = $data['scheme'];
        $this->host = $data['host'];
        $this->root = BASEDIR;
        $path = (isset($data['path'])) ? $data['path'] : '';

        // TODO
        // 应该只替换一次 BUG
        $path = str_replace(BASEDIR, '', $path);


        if (strpos($path, '.php') != 0) {
            $path = substr($path, strpos($path, '.php') + 4);
        }

        // 获取原生地址
        $oPath = trim($path, '/');
        $path = $this->getOriginUrl($oPath);
        if ($oPath != $path) {
            $GLOBALS['UrlMap'] = array($path => $oPath);
        }
        $this->dsPath = $path;
        $this->parseRoute($path);
        $addGet = array();
        foreach ($_GET as $key => $value) {
            if (!empty($value)) {
                $addGet[$key] = $value;
            }
        }
        $query = (isset($data['query'])) ? $data['query'] : http_build_query($addGet);
        $this->curRoute['query'] = $this->parseQuery($query);

    }

    /**
     * 解析 module 和 controller
     *
     * @access private
     * @param  string $path
     */
    private function parseRoute($path, $delLen = 0)
    {
        $_module = $this->module;
        $_controller = $this->controller;
        $_action = $this->action;

        $path = trim($path, '/');
        if (!empty($path)) {
            $_path = explode('/', $path);
            if ($delLen > 0) {
                array_splice($_path, 0, $delLen);
            }
            if (count($_path) >= 2) {
                $tmpModule = array_shift($_path);
                if (!empty($tmpModule)) {
                    $_module = $tmpModule;
                }
                $_action = array_shift($_path);
            } else {
                $tmpModule = array_shift($_path);
                if (!empty($tmpModule)) {
                    $_module = $tmpModule;
                }
            }
            if (empty($delLen)) {
                if (!empty($GLOBALS['__LOCAL']['system']['appid'])) {
                    if (strtolower($GLOBALS['__LOCAL']['system']['appid']) == strtolower($_module)) {
                        $this->useSystem = true;
                        return $this->parseRoute($this->dsPath, 1);
                    }
                }
            }
            $this->curRoute['path'] = $_path;
            // add $_REQUEST PARAMS VALUE  // wrong priority
            if (!empty($_path)) {
                for ($i = 0, $index = count($_path); $i + 1 < $index; $i+= 2) {
                    if ($i + 3 == $index) {
                        $_path[$i+1] .= ('/' . $_path[$i + 2]);
                    }
                    $_REQUEST[$_path[$i]] = $_path[$i+1];
                }
            }
        }
        // 若 module 中有 '_' ,则 module 中包含控制器
        if (FALSE !== strpos($_module, '_')) {
            list($this->curRoute['module'], $this->curRoute['controller']) = explode('_', $_module);
            $_module = $this->curRoute['module'];
            $_controller = $this->curRoute['controller'];
        } else {
            $this->curRoute['module'] = $_module;
        }
        $this->curRoute['module'] = current(explode('-', $_module));
        $this->curRoute['action'] = current(explode('.', $_action));
    }

    /**
     * 设置 url path
     *
     * @access private
     * @param string $key   e.g. module\controller\action
     * @param stirng $value
     */
    private function setPath($key, $value)
    {
        switch ($key) {
            case 'module':
                $this->module = $value;
                break;

            case 'controller':
                $this->controller = $value;
                break;

            case 'action':
                $this->action = current(explode('.', $value));
                break;

            default:
                $this->path[] = $key;
                $this->path[] = $value;
                break;
        }
    }

    public function setPathArray(array $arr)
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $this->setPath($key, $value);
            }
        }
        return $this;
    }

    /**
     * 设置 url query 字符串
     *
     * @access private
     * @param string $key
     * @param string $value
     */
    private function setQuery($key, $value)
    {
        $this->query[$key] = $value;
    }

    /**
     * 设置 url query 字符串
     *
     * @access public
     * @param array $arr
     * @return object
     */
    public function setQueryArray(array $arr)
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $this->setQuery($key, $value);
            }
        }
        return $this;
    }

    /**
     * 生成新的 url 与当前 url 参数无关
     * @return string
     */
    public function getUrl($reset = true)
    {
        if (IS_REWRITE) {
            $url = 'http://' . $this->host . $this->root;
        } else {
            $url = 'http://' . $this->host . $this->root . '/index.php';
        }
        if ($this->controller === 'default') {
            if ($this->action === 'index') {
                $url .= '/'. $this->module;
            } else {
                $url .= '/' . $this->module . '/' . $this->action;
            }
        } else {
            $url .= '/' . $this->module . '_' . $this->controller . '/' . $this->action;
        }
        if (!empty($this->path)) {
            $url .= '/' . join('/', $this->path);
        }
        if (is_array($this->query)) {
            $url .= '?' . http_build_query($this->query, '', '&');
        }
        if ($reset) {
            $this->resetUrl();
        }
        return $url;
    }

    /**
     * 根据路径获取URL
     */
    public function getUrlByPath($path, $param = null)
    {
        $base = SITEURL_ROOT;
        $level = substr($path, 0, 2);
        if ($level == '..') {
            $base = dirname($base);
            $path = substr($path, 2);
        }
        $first = substr($path, 0, 4);
        if ($first == 'http') {
            //
        } else if ($first[0] == '/') {
            //解析目录参数映射，追加根目录
            if (isset(self::$templateConfig['dir']) && isset(self::$templateConfig['dir']['map'])) {
                $hasWord = false;//是否存在映射值
                foreach (self::$templateConfig['dir']['map'] as $key => $value) {
                    $sub2str = substr($path, 1);
                    if ($sub2str == $value['from'] || substr($sub2str, 0, strpos($sub2str, '/')) == $value['from']) {
                        $hasWord = true;
                    }
                }
                foreach (self::$templateConfig['dir']['map'] as $key => $value) {
                    list($pk, $pv) = explode('=', $value['to']);
                    if ($_REQUEST[$pk] == $pv) {
                        //排除资源文件和已存在配置关键字
                        if (is_numeric(strpos($path, 'asset/')) || is_numeric(strpos($path, '.')) || $hasWord) {
                        } else {
                            $path = '/' . $value['from'] . $path;
                        }
                    }
                }
            }
            //root add path
            if (IS_REWRITE) {
                $path = str_replace(REWRITE_SUFFIX, '', $path);
                $path = $base . (empty($path) || $path[strlen($path) -1] == '/' ? $path: $path . (REWRITE_FILE_STATUS ? REWRITE_SUFFIX : ''));
            } else {
                $path = $base . '/index.php' . $path;
            }
        }

        // URL 中包含语言
        $lang = $this->getQuery('lang');
        if (isset($param['GET']['lang']) && (!empty($param['GET']['lang']) || (!empty($param['GET']) && $param['GET']['lang'] == null))) {
            $lang = $param['GET']['lang'];
            unset($param['GET']['lang']);
        }

        if (!empty($lang)) {
            // $path 中是否已包含 "?""
            if (false === stripos($path, '?')) {
                $path = $path . '?lang=' . $lang;
            } else {
                $path = $path . '&lang=' . $lang;
            }
        }
        // URL 中包含货币
        $cur = $this->getQuery('cur');
        if (!empty($cur)) {
            // $path 中是否已包含 "?""
            if (false === stripos($path, '?')) {
                $path = $path . '?cur=' . $cur;
            } else {
                $path = $path . '&cur=' . $cur;
            }
        }
        // URL 中包含货币
        $openid = $this->getQuery('openid');
        if (!empty($openid)) {
            // $path 中是否已包含 "?""
            if (false === stripos($path, '?')) {
                $path = $path . '?openid=' . $openid;
            } else {
                $path = $path . '&openid=' . $openid;
            }
        }

        if (!empty($param['GET'])) {
            if (false === stripos($path, '?')) {
                $path = $path . '?' . http_build_query($param['GET'], '', '&');
            } else {
                $path = $path . '&' . http_build_query($param['GET'], '', '&');
            }
        }
        return $path;
    }

    /**
     * 将 url 参数置为默认
     *
     * @access private
     */
    private function resetUrl()
    {
        $this->module = 'default';
        $this->controller = 'default';
        $this->action = 'index';
        $this->path = '';
        $this->query = array();
        return $this;
    }

    public function resetQuery()
    {
        $this->module = $this->curRoute['module'];
        $this->controller = $this->curRoute['controller'];
        $this->path = '';
        $this->query = array();
        return $this;
    }

    /**
     * 保留当前 url 参数更改其中部分参数
     *
     * @access private
     * @param string $key
     * @param string $value
     * @return object 调用者本身对象
     */
    public function setCurPath($key, $value)
    {
        switch ($key) {
            case 'module':
                $this->curRoute['module'] = $value;
                break;

            case 'controller':
                $this->curRoute['controller'] = $value;
                break;

            case 'action':
                $this->curRoute['action'] = current(explode('.', $value));
                break;
            default:
                $i = array_search($key, $this->curRoute['path']);
                if (empty($value)) {
                    if (is_numeric($i)) {
                        unset($this->curRoute['path'][$i + 1]);
                        unset($this->curRoute['path'][$i]);
                    }
                } else {
                    if (is_numeric($i)) {
                        $this->curRoute['path'][$i + 1] = $value;
                    } elseif (is_numeric($key)) {
                        $this->curRoute['path'][$key] = $value;
                    } else {
                        $this->curRoute['path'][] = $key;
                        $this->curRoute['path'][] = $value;
                    }
                }
                break;
        }
        return $this;
    }

    /**
     * 依据当前 url 设置新的 url
     *
     * @access private
     * @param array $arr url 参数
     */
    public function setCurPathArray(array $arr)
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $this->setCurPath($key, $value);
            }
        }
        return $this;
    }

    /**
     * 设置当前页面 url query 字符串
     *
     * @access private
     * @param string $key
     * @param string $value
     */
    public function setCurQuery($key, $value)
    {
        if (empty($value) && $value !== 0) {
            unset($this->curRoute['query'][$key]);
        } else {
            $this->curRoute['query'][$key] = $value;
        }
        return $this;
    }

    public function setCurQueryArray($arr)
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $this->setCurQuery($key, $value);
            }
        }
        return $this;
    }
    /**
     * 获取当前 url
     * @return string
     */
    public function getCurUrl()
    {

        if (IS_REWRITE) {
            $url = 'http://' . $this->host . $this->root;
        } else {
            $url = 'http://' . $this->host . $this->root . '/index.php';
        }
        if ($this->curRoute['controller'] === 'default') {
            if ($this->curRoute['action'] === 'index') {
                $url .= '/'. $this->curRoute['module'];
            } else {
                $url .= '/' . $this->curRoute['module'] . '/' . $this->curRoute['action'];
            }
        } else {
            $url .= '/' . $this->curRoute['module'] . '_' . $this->curRoute['controller'] . '/' . $this->curRoute['action'];
        }
        if (!empty($this->curRoute['path'])) {
            $url .= '/' . join('/', $this->curRoute['path']);
            if (count($this->curRoute['path']) == 1) {
                $url .= '/';
            }
        }
        if (IS_REWRITE && $url[strlen($url) -1] != '/') {
            $url .= REWRITE_SUFFIX;
        }
        if (!empty($this->curRoute['query'])) {
            $tmpQuery = $this->curRoute['query'];
            if (count($this->curRoute['query']) == 1 && isset($this->curRoute['query']['page'])) {
                $url = $this->curPageUrl();
                $url = substr($url, 0, strpos('?'));
            }
            $url .= '?' . http_build_query($tmpQuery, '', '&');
            $url = urldecode($url);
        }
        $this->resetCurUrl();
        if (isset(self::$templateConfig['request']) && isset(self::$templateConfig['request']['map'])) {
            foreach (self::$templateConfig['request']['map'] as $key => $value) {
                if (is_numeric(strpos($url, $value['from']))) {
                    $url = str_replace($value['from'], $value['to'], $url);
                    break;
                }
            }
        }
        if (!empty($GLOBALS['UrlMap'])) {
            $url = str_replace(key($GLOBALS['UrlMap']), current($GLOBALS['UrlMap']), $url);
        }
        return $url;
    }

    /**
     * 重置当前地址
     */
    public function resetCurUrl()
    {
        $this->curRoute['query'] = array();
        $this->parseUrl();
        return $this;
    }

    /**
     * parseQuery function.
     *
     * @access private
     * @param mixed $query
     * @return int
     */
    private function parseQuery($query)
    {
        $_query = array();

        if (!empty($query)) {
            $_q = explode('&', $query);
            foreach ($_q as $item) {
                $_item = explode('=', $item);
                if (is_numeric(strpos($_item[0], '[]'))) {
                    if (isset($_item[1])) {
                        if (isset($_query[$_item[0]])) {
                            $_query[$_item[0]] .= ('&' . $_item[0] . '=' . $_item[1]);
                        } else {
                            $_query[$_item[0]] .= $_item[1];
                        }

                    }
                } else {
                    $_query[$_item[0]] = (isset($_item[1])) ? $_item[1] : true;
                }
            }
        }
        return $_query;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getQuery($key)
    {
        if (isset($this->curRoute['query'][$key])) {
            $result = $this->curRoute['query'][$key];
        } else {
            $result = '';
        }
        return $result;
    }

    public function get($key)
    {
        if (isset($this->curRoute[$key])) {
            $result = $this->curRoute[$key];
        } else {
            $result = '';
        }
        return $result;
    }

    public function getRootUrl()
    {
        return $this->root;
    }

    /**
     * 获取控制器类名
     * 优先获取 Override/Custom 目录下的
     * @return String 控制器类名
     */
    public function getControllerName()
    {
        $prefix = '';
        if ($this->useSystem) {
            $prefix = 'Aa' . $GLOBALS['__LOCAL']['system']['appid'] . '_';//Aareport_
        }
        $controlName = $prefix . ucfirst($this->get('module')) . '_' . ucfirst($this->get('controller')) . 'Controller';
        return $controlName;
    }

    public function getAction()
    {
        return $this->get('action') . 'Action';
    }

    public function isModule($modelName)
    {
        return strcasecmp($this->get('module'), $modelName) === 0;
    }

    public function isPath($modelName, $actionName = 'index', $controllerName = 'default', $existsParam = array())
    {
        return strcasecmp($this->get('module'), $modelName) === 0 && strcasecmp($this->get('action'), $actionName) === 0 && strcasecmp($this->get('controller'), $controllerName) === 0;
    }

    public function getResource($path)
    {
        $this->url = BASEURL . $this->baseDir . '/' . $path;
        return $this->url;
    }
    public function setPage($name)
    {
        if (isset($this->page)) {
            $this->page = $name;
            return true;
        }
        return false;
    }
    public function getPage()
    {
        return $this->page;
    }

    function redirect($url = null)
    {
        if ($url === null) {
            $url = $_SERVER['HTTP_REFERER'];
            if (empty($url)) {
                $url = SITEURL;
            }
        } else if( $url == '/'){
            $url = SITEURL;
        } else {
            $first = substr($url, 0, 4);
            if ($first != 'http') {
                $url = $this->getUrlByPath($url);
            }
        }
        self::session();
        $cookieID = session_id();
        if (empty($cookieID)) {
            $cookieID = $_COOKIE['PHPSESSID'];
        }
        if (!session_write_close($cookieID)) {
            session_id($cookieID);
            session_start();
            session_write_close();
        }
        sfget_instance('Core_Model')->close();
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header('location:' . $url);
        sfquit();
    }
    /**
     * 设置日志路由地址
     * @param String $url   设置的地址
     * @param String $keyUrl    指定的路由（默认为当前地址），该路由的意思是 例如1和2参数分别为 /, /login 表示为你要设置“登录”路由为“首页”
     */
    function setGo($url, $keyUrl = null)
    {
        $curKey = (!defined('FLAG_ADMIN') || FLAG_ADMIN !== true) ? 'front' : 'admin';
        $track = $_SESSION['track'];
        if (!isset($track['map'])) {
            $track['map'] =  array('front' => array(), 'admin' => array());
        }
        $map = $track['map'][$curKey];
        if (empty($keyUrl)) {
            $keyUrl = $this->getCurUrl();
        }
        $map[$keyUrl] = $this->getUrlByPath($url);
        $track['map'][$curKey] = $map;
        $_SESSION['track'] = $track;
    }

    /**
     * 跳到指定步长路由地址
     * Enter description here ...
     * @param unknown_type $setp
     * @param unknown_type $return
     */
    function go($setp, $return = false)
    {
        $curKey = (!defined('FLAG_ADMIN') || FLAG_ADMIN !== true) ? 'front' : 'admin';
        self::session();
        $track = $_SESSION['track'];
        if (!empty($track['map'][$curKey][$setp])) {
            $this->redirect($track['map'][$curKey][$setp]);
        }
        $setp += 2;
        if (in_array($setp, array(0, 1, 2))) {
            $goUrl = $track['history'][$curKey][$setp];
            if (empty($goUrl)) {
                $goUrl = current($track['history'][$curKey]);
            }
            if (!empty($track['map'][$curKey][$goUrl])) {
                $goUrl = $track['map'][$curKey][$goUrl];
            }
            $url = $this->getCurUrl();
            if ($url != $goUrl) {
                if ($return)
                    return $goUrl;
                $this->redirect($goUrl);
            }
        }
        if ($return) {
            return false;
        }
        $this->redirect();
    }

    //根据后缀输出制定格式
    public function response($data)
    {
        $type = null;
        $arr = parse_url($this->url);
        $file = basename($arr['path']);
        $ext = explode(".", $file);
        if (isset($ext[1])) {
            $type = $ext[1];
        }
        switch ($type) {
           case 'json':
               echo json_encode($data);
               break;
           default:
               echo $data;
               break;
        }
    }

    public function getDsPath()
    {
        return $this->dsPath;
    }

    public function getModule()
    {
        return $this->module;
    }
}
