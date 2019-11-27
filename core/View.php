<?php
/**
 * 视图类，负责解析 xml 配置文件，
 * 生成 html 代码片段 等
 */
class Core_View extends Core_Base
{
    private $blocks;
    private $layouts;
    private $operates;
    public $template = array('attributes' => array(), 'path' => '');
    public $bind = array();
    public $config;
    public $isRender = true;

    function __construct()
    {
        parent::__construct();
        $names = explode('_', get_class($this));
        if ($names[0] === 'Ex') {
            $names[0] = $names[1];
        }
        $this->setPath(strtolower($names[0]));
    }
    /**
     * 解析页面，在插件目录，重写插件目录，模板目录解析配置文件
     * @param String $page  页面名称
     * @param String $wsDir 相对目录路径,例如 Admin/
     * @param Boolean $require  是否为唯一解析,为 True 会附带解析 Common.xml
     */
    function parsePage($page, $wsDir = '', $require = FALSE)
    {
        $GLOBALS['_PAGE'] = $this->bind;

        $pageFilePath = THEME_CUR_DIR . DS . 'layout' . DS . $page . '.xml';

        if (!is_file($pageFilePath)) {
            $pageFilePath = THEME_DEF_DIR . DS . 'layout' . DS . $page . '.xml';
            if (!is_file($pageFilePath)) {
                $page = '404';
            }
        }
        $GLOBALS['PAGE'] = $page;
        /*
        //解析模板默认语言包
        if ( empty($GLOBALS['THEME_LANGUAGE']) ) {
            $this->loadLanguage('common', '/');
            $GLOBALS['THEME_LANGUAGE'] = true;
        }
        *///custom
        //解析模板的公共配置
        if (!$require) {
            $commonFile = THEME_CUR_DIR . DS .  'layout' . DS . 'common.xml';
            if (file_exists($commonFile)) {
                $this->parseLayout($commonFile);
            } else {
                $commonFile = THEME_DEF_DIR . DS .  'layout' . DS . 'common.xml';
                if (file_exists($commonFile)) {
                    $this->parseLayout($commonFile);
                }
            }
        }
        $dir = MODULE_DIR;
        $handleDir = dir($dir);
        //解析插件配置 xml 文件 ::BOF
        while ($d = $handleDir->read()) {
            if (in_array($d, array('.', '..', '.svn', '.gitignore'))) {
                continue;
            }
            //扫描插件的公共配置
            if (!$require) {
                $exDefCommon = EXMODULE_DEF_DIR . DS . $d . DS . 'layout' . DS . 'common.xml';
                $exCurCommon = EXMODULE_CUR_DIR . DS . $d . DS . 'layout' . DS . 'common.xml';
                $common = MODULE_DIR . DS . $d . 'layout' . DS . 'common.xml';
                if (file_exists($exCurCommon)) {
                    $this->parseLayout($exCurCommon);
                } else if (file_exists($exDefCommon)) {
                    $this->parseLayout($exDefCommon);
                } else if (file_exists($common)){
                    $this->parseLayout($common);
                }
            }
            //插件私有配置
            $exDefPageFile = EXMODULE_DEF_DIR . DS . $d . DS . 'layout' . DS . $page . '.xml';
            $exCurPageFile = EXMODULE_CUR_DIR . DS . $d . DS . 'layout' . DS . $page . '.xml';
            $pageFile = MODULE_DIR . DS . $d . DS . 'layout' . DS . $page . '.xml';
            if (file_exists($exCurPageFile)) {
                $this->parseLayout($exCurPageFile);
            } else if (file_exists($exDefPageFile)) {
                $this->parseLayout($exDefPageFile);
            } else if (file_exists($pageFile)) {
                $this->parseLayout($pageFile);
            }
        }
        //解析插件配置 xml 文件 ::EOF

        //解析前台私有页
        $pageDefFilePath = THEME_DEF_DIR . DS . 'layout' . DS . $page . '.xml';
        $pageCurFilePath = THEME_CUR_DIR . DS . 'layout' . DS . $page . '.xml';
        if ( is_file($pageCurFilePath) ) {
            $this->parseLayout($pageCurFilePath);
        } else if (is_file($pageDefFilePath)) {
            $this->parseLayout($pageDefFilePath);
        }
        $this->executeOperate();
    }




    /**
     * 解析布局文件
     * @param  String $file 文件路径
     * @return Void
     */
    public function parseLayout($file)
    {
        $datas = sfdecode_xml_file_to_array($file);
        // 一般位于插件 Tpl 目录下
        if (isset($datas['place']) && is_array($datas['place'])) {
            foreach ($datas['place'] as $pk => $pv) {
                if (is_numeric($pk)) { // 同一页面不同位置有同一个 block
                    $this->parsePlace($pv);
                } else {
                    $this->parsePlace($datas['place']);
                    break;   // 若进入此分支，则表明只有一个 block
                }
            }
        }
        // 一般位于 Theme/default/Layout 下
        if (isset($datas['template']) && is_array($datas['template'])) {
            foreach ($datas['template'] as $key => $value) {
                if (is_string($key)) {
                    $templateName = $value['name'];
                    if (!empty($this->template['attributes'][$templateName])) {
                        foreach ($value as $k => $v) {
                            $this->template['attributes'][$templateName][$k] = $value[$k];
                        }
                    } else {
                        $this->template['attributes'][$templateName] = $value;
                    }
                } else {
                    $templateName = $value['@attributes']['name'];
                    if (!empty($this->template['attributes'][$templateName])) {
                        foreach ($value['@attributes'] as $k => $v) {
                            $this->template['attributes'][$templateName][$k] = $value['@attributes'][$k];
                        }
                    } else {
                        $this->template['attributes'][$templateName] = $value['@attributes'];
                    }
                }
                if (basename($file) != 'common.xml') {
                    $this->setPath('/');
                    $attributes = $this->template['attributes'][$templateName];
                    foreach ($this->template['attributes'] as $key => $value) {
                        if ($key != $templateName) {
                            foreach ($value as $k => $v) {
                                $attributes[$k] = $v;
                            }
                        }
                    }
                    $attributes['name'] = $templateName;
                    $this->template['attributes'] = $attributes;
                    $this->setTemplate($templateName);
                }
            }
        }
    }

    /**
     * 解析位置配置
     * @param  Array $data Xml的节点配置
     * @return Void
     */
    public function parsePlace($data)
    {
        $name = $data['@attributes']['name'];
        if (isset($data['block']) && is_array($data['block'])) {
            $block = $data['block'];
            foreach ($block as $key => $value) {
                if (is_numeric($key)) { // 该位置同一个 block 出现多次
                    $this->registerBlock($name, $value['@attributes']);
                } else {
                    $this->registerBlock($name, $value);
                    break;
                }
            }
        }
        if (isset($data['operate'])) {
            $operate = $data['operate'];
            if (is_array($operate)) {
                foreach ($operate as $key => $value) {
                    if (is_numeric($key)) {
                        $this->registerOperate($name, $value['@attributes']);
                    } else {
                        $this->registerOperate($name, $value);
                        break;
                    }
                }
            }
        }
    }

    /**
     * 注册块到指定位置， 没有对是否指定 tpl 做判断
     * @param  String $place 位置名称
     * @param  Array $block 块配置
     * @return Void
     */
    public function registerBlock($place, $block)
    {
        $render = isset($block['render']) ? $block['render'] : '*';
        $block['sort'] = isset($block['sort']) ? $block['sort'] : '100';
        if (empty($block['id'])) {
            $this->layouts[$place][$render][] = $block;
        } else {
            $this->layouts[$place][$render][$block['id']] = $block;
        }
    }

    /**
     * 移除指定位置的块
     * @param  String $place  位置名称
     * @param  String $render 位置区域
     * @param  String $id     块标示
     * @return Void
     */
    public function removeBlock($place, $render, $id)
    {
        unset($this->layouts[$place][$render][$id]);
    }

    /**
     * 注册操作块
     * @param  String $place   受范围的位置名称
     * @param  Array $operate 操作配置
     * @return Void
     */
    public function registerOperate($place, $operate)
    {
        $render = isset($operate['render']) ? $operate['render'] : '*';
        switch ($operate['action']) {
            case 'remove':
                if (!empty($operate['id'])) {
                    $this->operates[] = new method('removeBlock', $this, $place, $render, $operate['id']);
                }
                break;
        }
    }

    /**
     * 执行注册好的动作
     */
    function executeOperate()
    {
        if (!empty($this->operates)) {

            foreach ($this->operates as $operate) {
                $operate->execute();
            }
            $this->operates = array();
        }
    }

    public function setPath($path)
    {
        $this->template['path'] = $path;
        return $this;
    }
    public function getPath()
    {
        return $this->template['path'];
    }

    public function setTemplate($name)
    {
        $this->template['name'] = $name;
        return $this;
    }

    /**
     * 渲染 html 页面
     * @param  String $tpl  要渲染的 html 模板
     * @param  Array  $bind 传递到模板中的数组
     * @return String       返回 html 字符串
     */
    public function render($tpl = null)
    {
        if (isset($GLOBALS['_PAGE'])) {
            $this->bind['_PAGE'] = $GLOBALS['_PAGE'];
        }


        if (empty($this->transData)) {
            $this->loadLanguage('common', '/');
            $this->loadLanguage(null, $this->template['path']);
        }
        //custom
        if (!empty($this->bind)) {
            extract($this->bind);
        }
        if (null === $tpl) {
            $file = $this->getTemplateFile();
        } else {
            $file = $tpl;  //路径？？？
        }
        Core_Autoload::listenAsync();
        if (is_file($file)) {
            $tmpContent = ob_get_contents();
            ob_clean();
            ob_start();
            include $file;
            $content = $tmpContent . ob_get_contents();
            ob_clean();
            $html = $this->getFormatBind($content);
            echo $html;
            if (isset($_SERVER['REQUEST_URI']) && (!defined('FLAG_ADMIN') || FLAG_ADMIN !== true) && REWRITE_FILE_STATUS === true) {
                $dfile = str_replace(BASEDIR, '', $_SERVER['REQUEST_URI']);
                if ($dfile == '/') {
                    $dfile = '/index.html';
                }
                $dirs = ROOT_DIR . dirname($dfile);
                if (!is_dir($dirs)) {
                    @mkdir($dirs, 0777, TRUE);
                    @chmod($dirs, 0777);
                }
                if (is_dir($dirs) && is_numeric(strpos($dfile, REWRITE_SUFFIX))) {
                    Core_IoUtils::instance()->writeFile(ROOT_DIR . $dfile, $html);
                }
            }
            return true;
        } else {
            //TODO
            //write to log file not found
        }
        return false;
    }

    /**
     * 载入 .phtml 后缀的代码片段
     * @param  String $name 模板名称
     * @return String       代码片段
     */
    public function getTemplateFile($name = null)
    {
        $file = '';
        if (null !== $name) {
            $this->setTemplate($name);
        }

        if (empty($this->template['name'])) {
            return null;
        }
        $this->template['name'] = str_replace('/', DS, $this->template['name']);
        $this->template['name'] = str_replace('\\', DS, $this->template['name']);
        // 模板片段的相对路径前缀
        $relativePath = EXMODULE_DEF_DIR;
        if ($this->template['path'] == '/' || $this->template['name'][0] == DS) {
            if (is_numeric(strpos($this->template['name'], 'asset'))) {
                $relativePath = ROOT_DIR;
            } else {
                $relativePath = THEME_DEF_DIR;
            }
        } elseif (strtolower($this->template['path']) == 'admin') {
            $relativePath = MODULE_DIR . DS . $this->template['path'];
        }
        if (isset($this->template['name'])) {
            if ( $relativePath == THEME_DEF_DIR ) {
                $filePath = THEME_CUR_DIR . DS . 'tpl' . DS . $this->template['name'] . '.phtml';
                if ( !file_exists($filePath) ) {
                    $filePath = THEME_DEF_DIR . DS . 'tpl' . DS . $this->template['name'] . '.phtml';
                    if (is_file($filePath)) {
                        $file = $filePath;
                    } else {
                        //TODO
                        //write to log not found template
                    }
                } else {
                    $file = $filePath;
                }
            } else {
                if ( $relativePath == EXMODULE_DEF_DIR ) {

                     $filePath = EXMODULE_CUR_DIR . DS . $this->template['path'] . DS . 'tpl' . DS . $this->template['name'] . '.phtml';
                     if ( !file_exists($filePath) ) {
                        $filePath = EXMODULE_DEF_DIR . DS . $this->template['path'] . DS . 'tpl' . DS . $this->template['name'] . '.phtml';
                        if (file_exists($filePath)) {
                            $file = $filePath;
                        } else {
                            $filePath = MODULE_DIR . DS . $this->template['path'] . DS . 'tpl' . DS . $this->template['name'] . '.phtml';
                            if (is_file($filePath)) {
                                $file = $filePath;
                            } else {
                                //TODO
                                //write to log not found template
                            }
                        }
                     } else {
                        $file = $filePath;
                     }
                } elseif ($relativePath == ROOT_DIR) {
                    $filePath = $relativePath . DS . $this->template['name'] . '.phtml';

                    if (is_file($filePath)) {
                        $file = $filePath;
                    }

                } else {
                    $filePath = $relativePath . DS . 'tpl' . DS . $this->template['name'] . '.phtml';

                    if (is_file($filePath)) {
                        $file = $filePath;
                    } else {
                        $filePath = MODULE_DIR . DS . $this->template['path'] . DS . 'tpl' . DS . $this->template['name'] . '.phtml';
                        if (is_file($filePath)) {
                            $file = $filePath;
                        } else {
                            //TODO
                            //write to log not found template
                        }
                    }
                }

            }
        }
        // 渲染之前载入相应的语言包
        //$this->loadLanguage(null, $this->template['path']);//custom
        return $file;
    }

    public function isRender()
    {
        return true;
    }


    /**
     * 按顺序执行模板中不同位置指定的动作
     * @param  String $name   模板中的位置
     * @param  String $render 模板中的位置
     * @return Void
     */
    public function executePlace($name, $render = '*')
    {
        $tmpBlocks = array();
        $sortBlocks = array();
        if (isset($this->layouts[$name][$render]) && is_array($this->layouts[$name][$render])) {
            foreach ($this->layouts[$name][$render] as $block) {
                $tmpBlocks[$block['sort']][] = $block;
                $sortBlocks[$block['sort']] = $block['sort'];
            }
            if (!empty($sortBlocks)) {
                if (!empty($sortBlocks)) {
                    sort($sortBlocks);
                }
                for ($len   =   count($sortBlocks),$i = $len - 1; $i >= 0; $i--) {
                    foreach ($tmpBlocks[$sortBlocks[$i]] as $block) {
                        $instance = sfget_instance($block['class']);
                        // if (!$instance->view->isRender()) {
                        //     continue;
                        // }
                        if (!empty($block['method'])) {
                            $method = $block['method'];
                            if (method_exists($instance, $method)) {
                                $instance->$method($block);
                            } else {
                                //TODO
                                //write into log method not exist
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * 获取模板文件中指定位置的 html
     * @param  String $name   模板中指定位置
     * @param  String $render 模板中指定位置
     * @return String         返回的 html 代码片段
     */
    public function getPlaceHtml($name, $render = '*', $params = null)
    {
        $html = '';
        $tmpBlocks = array();
        $sortBlocks = array();
        if (!empty($this->layouts[$name][$render])) {
            foreach ($this->layouts[$name][$render] as $block) {
                $tmpBlocks[$block['sort']][] = $block;
                $sortBlocks[$block['sort']] = $block['sort'];
            }
            if (!empty($sortBlocks)) {
                if (!empty($sortBlocks)) {
                    sort($sortBlocks);
                }
                for ($len	=	count($sortBlocks),$i = $len - 1; $i >= 0; $i--) {
                    foreach ($tmpBlocks[$sortBlocks[$i]] as $block) {
                        $instance = sfget_instance($block['class']);
                        if (!empty($block['tpl'])) {
                            $instance->view->setTemplate($block['tpl']);
                        } else {
                            //TODO
                            // write to log not tpl file
                        }
                        if (!empty($block['method'])) {
                            $method = $block['method'];
                            if (method_exists($instance, $method)) {
                                $instance->view->bind = $block;
                                $instance->$method($block);
                            } else {
                                //TODO
                                //write into log method not exist
                            }
                        }
                        if(empty($GLOBALS['lesses'][get_class($instance)])){
                            $GLOBALS['lesses'][get_class($instance)] = true;
                            $file = $instance->view->getAssetPath('css/style.less');
                            if (file_exists($file)) {
                                set_bind('resource', self::$tag->linkLess($instance->view->getAssetUrl('css/style.less'))->toString());
                            }
                        }
                        if(empty($GLOBALS['csses'][get_class($instance)])){
                            $GLOBALS['csses'][get_class($instance)] = true;
                            $file = $instance->view->getAssetPath('css/style.css');
                            if (file_exists($file)) {
                                set_bind('resource', self::$tag->link($instance->view->getAssetUrl('css/style.css'))->toString());
                            }
                        }
                    	if (!$instance->view->isRender) {
                             continue;
                        }
                        $html .= $instance->view->getHtml();
                    }
                }
            }
        }
        return $html;
    }

    /**
     * 获取单个插件中的 html 模板内容
     * @return String  html 代码片段
     */
    public function getHtml()
    {
        if (isset($GLOBALS['_PAGE'])) {
            $this->bind['_PAGE'] = $GLOBALS['_PAGE'];
        }
        $content = ob_get_contents();
        ob_clean();
        ob_start();
        if (!empty($this->bind)) {
            extract($this->bind);
        }
        $file = $this->getTemplateFile();
        if (is_file($file)) {
            include $this->getTemplateFile();
        }

        $html = ob_get_contents();
        ob_clean();
        ob_start();
        echo $content;
        return $html;
    }

    /**
     * 获取资源路径
     * @param  String $file     e.g. js/boot.js
     * @param  String $module   e.g. logo
     * @return String           e.g. http://localhost/xxx/xxx/js/boot.js
     */
    public function getAssetUrl($file = '', $module = null, $mode = 2)
    {
        $rootUrl = BASEURL . self::$request->baseDir;
        if (isset($_COOKIE['asset_lang']) && isset($GLOBALS['assets'][$_COOKIE['asset_lang']])) {
            $rootUrl = $GLOBALS['assets'][$_COOKIE['asset_lang']];
        }
        $path = !empty($module) ? $module : $this->template['path'];
        if ($path === '/' || $file[0] == '/') {
            if ($file[0] == '/') {
                $path = '/';
                $file = substr($file, 1);
            }
            switch ($mode) {
                case 0:
                    $url = $rootUrl . $path . 'theme/' . THEME_NAME . '/asset/' .  $file;
                    break;
                case 1:
                    $url = $rootUrl . $path . 'theme/' . THEME_DEF_NAME . '/asset/' .  $file;
                    break;
                default:
                    $rPath =  ROOT_DIR . $path . 'theme/' . THEME_NAME . '/asset/' .  $file;
                    if ( file_exists($rPath) ) {
                        $url = $rootUrl . $path . 'theme/' . THEME_NAME . '/asset/' .  $file;
                    } else {
                        $url = $rootUrl . $path . 'theme/' . THEME_DEF_NAME . '/asset/' .  $file;
                    }
                    break;
            }
        } elseif ($path === 'admin') {
            $url = $rootUrl . '/module/admin/asset/' . $file;
        } else {
            switch ($mode) {
                case 0:
                    $rPath =  ROOT_DIR . '/theme/' . THEME_NAME . '/module/' . $path . '/asset/' . $file;
                    if ( file_exists($rPath) ) {
                        $url = $rootUrl . '/theme/' . THEME_NAME . '/exmodule/' . $path . '/asset/' . $file;
                    } else {
                        $rPath = ROOT_DIR . '/theme/' . THEME_NAME . '/module/' . $path . '/asset/' . $file;
                        if ( file_exists($rPath) ) {
                            $url = $rootUrl . '/theme/' . THEME_NAME . '/module/' . $path . '/asset/' . $file;
                        } else {
                            $url = $rootUrl . '/module/' . $path . '/asset/' . $file;
                        }
                    }
                    break;
                case 1:
                    $rPath =  ROOT_DIR . '/theme/' . THEME_DEF_NAME . '/module/' . $path . '/asset/' . $file;
                    if ( file_exists($rPath) ) {
                        $url = $rootUrl . '/theme/' . THEME_DEF_NAME . '/exmodule/' . $path . '/asset/' . $file;
                    } else {
                        $rPath = ROOT_DIR . '/theme/' . THEME_DEF_NAME . '/module/' . $path . '/asset/' . $file;
                        if ( file_exists($rPath) ) {
                            $url = $rootUrl . '/theme/' . THEME_DEF_NAME . '/module/' . $path . '/asset/' . $file;
                        } else {
                            $url = $rootUrl . '/module/' . $path . '/asset/' . $file;
                        }
                    }
                    break;
                default:
                    $rPath =  ROOT_DIR . '/theme/' . THEME_NAME . '/exmodule/' . $path . '/asset/' . $file;
                    if ( file_exists($rPath) ) {
                        $url = $rootUrl . '/theme/' . THEME_NAME . '/exmodule/' . $path . '/asset/' . $file;
                    } else {
                        $rPath =  ROOT_DIR . '/theme/' . THEME_DEF_NAME . '/exmodule/' . $path . '/asset/' . $file;
                        if ( file_exists($rPath) ) {
                            $url = $rootUrl . '/theme/' . THEME_DEF_NAME . '/exmodule/' . $path . '/asset/' . $file;
                        } else {
                             $rPath = ROOT_DIR . '/theme/' . THEME_NAME . '/module/' . $path . '/asset/' . $file;
                             if ( file_exists($rPath) ) {
                                 $url = $rootUrl . '/theme/' . THEME_NAME . '/module/' . $path . '/asset/' . $file;
                            } else {
                                $url = $rootUrl . '/module/' . $path . '/asset/' . $file;
                            }
                        }
                    }
                    break;
            }

        }
        return $url;
    }

    /**
     * 获取资源地址
     * @param string $file
     * @param string $module
     * @return string
     */
    public function getAssetPath($file = '', $module = null, $mode = 2)
    {
        $path = !empty($module) ? $module : $this->template['path'];
        if ($path === '/' || $file[0] == '/') {
            if ($file[0] == '/') {
                $path = '/';
                $file = substr($file, 1);
            }
            switch ($mode) {
                case 0:
                    $rPath =  ROOT_DIR . $path . 'theme/' . THEME_NAME . '/asset/' .  $file;
                    break;
                case 1:
                    $rPath =  ROOT_DIR . $path . 'theme/' . THEME_DEF_NAME . '/asset/' .  $file;
                    break;
                default:
                    $rPath =  ROOT_DIR . $path . 'theme/' . THEME_NAME . '/asset/' .  $file;
                    if ( !file_exists($rPath) ) {
                        $rPath =  ROOT_DIR . $path . 'theme/' . THEME_DEF_NAME . '/asset/' .  $file;
                    }
                    break;
            }

        } elseif ($path === 'admin') {
            $rPath =  ROOT_DIR . '/module/admin/asset/' . $file;
        } else {
            switch ($mode) {
                case 0:
                    $rPath =  ROOT_DIR . '/theme/' . THEME_NAME . '/exmodule/' . $path . '/asset/' . $file;
                    if (!is_file($rPath)) {
                        $rPath =  ROOT_DIR . '/theme/' . THEME_NAME . '/module/' . $path . '/asset/' . $file;
                        if ( !is_file($rPath) ) {
                            $rPath =  ROOT_DIR . '/module/' . $path . '/asset/' . $file;
                        }
                    }
                    break;
                case 1:
                    $rPath =  ROOT_DIR . '/theme/' . THEME_DEF_NAME . '/exmodule/' . $path . '/asset/' . $file;
                    if (!is_file($rPath)) {
                        $rPath =  ROOT_DIR . '/theme/' . THEME_DEF_NAME . '/module/' . $path . '/asset/' . $file;
                        if ( !is_file($rPath) ) {
                            $rPath =  ROOT_DIR . '/module/' . $path . '/asset/' . $file;
                        }
                    }
                    break;
                default:
                    $rPath =  ROOT_DIR . '/theme/' . THEME_NAME . '/exmodule/' . $path . '/asset/' . $file;
                    if ( !file_exists($rPath) ) {
                        $rPath =  ROOT_DIR . '/theme/' . THEME_DEF_NAME . '/exmodule/' . $path . '/asset/' . $file;
                    }
                    if (!is_file($rPath)) {
                        $rPath =  ROOT_DIR . '/theme/' . THEME_NAME . '/module/' . $path . '/asset/' . $file;
                        if ( !is_file($rPath) ) {
                            $rPath =  ROOT_DIR . '/theme/' . THEME_DEF_NAME . '/module/' . $path . '/asset/' . $file;
                        }
                        if ( !is_file($rPath) ) {
                            $rPath =  ROOT_DIR . '/module/' . $path . '/asset/' . $file;
                        }
                    }
                    break;
            }

        }
        return $rPath;
    }

    public function getAdminUrl($action, $param = array())
    {
        $addGet = array(
            'm' => strtolower($this->template['path'])
        );
        if (!empty($this->role)) {
            $addGet['r'] = $this->role;
        }
        $addGet['c'] = 'admin';
        $addGet['a'] = $action;
        if (is_array($param)) {
            $param = array_merge($addGet, $param);
        } else {
            $param = $addGet;
        }
        return sfurl('/admin', array('GET' => $param));
    }

    /**
     * 分页切割()
     * @param String $total      总数
     * @param Object $current    当前页码
     * @param Array  $pre        显示页码个数
     */
    function getPageNumber($total, $current, $pre)
    {
        $newArray = array();
        if (empty($current)) {
            $current = 1;
        }
        if (!intval($total)) {
            return $newArray;
        }
        if (intval($current) > intval($total)) {
            return $newArray;
        }
        if ( ($pre % 2) == 0 ) {
            return $newArray;
        }
        $array = array();
        for ($i=1; $i<=$total; $i++) {
            $array[] = $i;
        }
        $num = intval($pre / 2);
        $qm = array_slice($array, ($current - $num - 1), $num);
        $hm = array_slice($array, $current, $num);
        $center = array($current);
        if ($total - $current < $num) {
            if ($total > $pre) {
                $newArray = array_slice($array, ($total - $pre), $pre);
            } else {
                $newArray = $array;
            }
        } elseif ($current <= $num) {
            $newArray = array_slice($array, 0 ,$pre);
        } else {
            $newArray = array_merge($qm, $center, $hm);
        }
        return $newArray;
    }

    /**
     * 根据页面情况动态获取实际ID值
     * @param Array $data   页面对应数组, array('article_category' => 'id', 'article_detail' => 'cid') , array('article_category' => array('category_id','id'), 'article_detail' => 'cid')
     */
    function getPageRequestId($data)
    {
        if (isset($GLOBALS['PAGE'])) {
            $page = $GLOBALS['PAGE'];
            if (!empty($data[$page])) {
                $data = $data[$page];
                if (is_array($data)) {
                    //如果是数组则按顺序优先
                    $index = count($data);
                    for ($i = 0; $i < $index; $i++) {
                        if (isset($_REQUEST[$data[$i]])) {
                            return $_REQUEST[$data[$i]] ? $_REQUEST[$data] : 0;
                        }
                    }
                } else {
                    if (isset($_REQUEST[$data])) {
                        return $_REQUEST[$data] ? $_REQUEST[$data] : 0;
                    }
                }
                return 0;
            }
        }
        return null;
    }

//不区分大小写判断参数是否在请求里
    function hasRequest($key, $value = null)
    {
        if ($value === null) {
            $result = array_key_exists(strtolower($key), $_REQUEST);
            if (!$result) {
                $result = array_key_exists(strtoupper($key), $_REQUEST);
            }
            return $result;
        } else {
            if ($this->hasRequest($key)) {
                $data = array_key_exists(strtolower($key), $_REQUEST);
                if (!$data) {
                    $data =  $_REQUEST[strtoupper($key)];
                } else {
                    $data = $_REQUEST[strtolower($key)];
                }
                if (is_array($data)) {
                    $result = in_array(strtolower($value), $data);
                    if (!$result) {
                        $result = in_array(strtoupper($value), $data);
                    }
                    return $result;
                } else {
                    if (is_array($value)) {
                        $result = in_array(strtolower($data), $value);
                        if (!$result) {
                            $result = in_array(strtoupper($data), $value);
                        }
                        return $result;
                    } else {
                        return strtolower($value) == strtolower($data);
                    }
                }
            } else {
                if (is_array($value) &&empty($value[0]) && empty($data)) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * 在全局变量中绑定变量，同名变量不覆盖
     * @param String $name  要绑定的变量
     * @param String $value 变量的值
     * @param Int $key   强制覆盖该下标中的变量
     */
    function setBind($name, $value, $key = null)
    {
        if ($key === null) {
            $count = isset($GLOBALS['binds'][$name]) ? count($GLOBALS['binds'][$name]) + 1 : 1;
            $GLOBALS['binds'][$name][$count] = $value;
        } else {
            $GLOBALS['binds'][$name][$key] = $value;
        }
    }


    function getBind($name, $params = null)
    {
        if (!empty($params)) {
            $GLOBALS['binds_params'][$name] = $params;
        }
        $GLOBALS['binds_flag'][$name] = 1;
        return '<!--@' . $name . '-->';
    }

    function getFormatBind($content)
    {
        if (isset($GLOBALS['binds']) && is_array($GLOBALS['binds'])) {
            foreach ($GLOBALS['binds'] as $name => $value) {
                $strs = array();
                $firsts = array();
                $lasts = array();

                foreach ($value as $k => $v) {
                    if (!is_numeric($k)) {
                        switch ($k) {
                            case BIND_FIRST:
                                $firsts[] = $v;
                                break;

                            case BIND_LAST:
                                $lasts[] = $v;
                                break;

                            default:
                                array_splice($strs, 0, 0, $v);
                        }
                    } else {
                        $strs[] = $v;
                    }
                }
                if (!empty($firsts)) {
                    for ($i = 0, $index = count($firsts); $i < $index; $i++) {
                        array_splice($strs, 0, 0, $firsts[$i]);
                    }
                }
                if (!empty($lasts)) {
                    for ($i = 0, $index = count($lasts); $i < $index; $i++) {
                        array_splice($strs, count($strs) , 0, $lasts[$i]);
                    }
                }
                if ( empty($strs) ) {
                    $strs = array('   ');
                }
                $separated = empty($GLOBALS['binds_params'][$name]['split']) ? '' : $GLOBALS['binds_params'][$name]['split'];
                $content = str_replace('<!--@' . $name . '-->', implode($separated, $strs), $content);
            }
        }
        if ( !empty($GLOBALS['binds_flag']) ){
            foreach ($GLOBALS['binds_flag'] as $key => $value) {
                $content = str_replace('<!--@' . $key . '-->', '', $content);
            }
        }
        if (!defined('FLAG_ADMIN') || FLAG_ADMIN !== true) {
            $vars = array('{http}' => SITEURL_ROOT);
            foreach ($vars as $key => $value) {
                $content = str_replace($key, $value, $content);
            }
        }
        return $content;
    }

    /**
     * 获取GET参数数据到隐藏域，一般做表单预留操作
     * Enter description here ...
     * @param String/Array $data    排除的Key
     */
    function getCurQueryDatasToHidden($param = null)
    {
        $nos = array();
        $set = array();
        if (is_string($param)) {
            $nos[] = $param;
        } else if (is_array($param)) {
            foreach ($param as $key => $value) {
                if (!is_numeric($key)) {
                    $set[$key] = $value;
                    unset($param[$key]);
                }
            }
            $nos = $param;
        }
        if (!in_array('page', $nos)) {
            $nos[] = 'page';
        }
        $html = '';
        foreach ($_GET as $key => $value) {
            if (in_array($key, $nos)) {
                continue;
            }
            if (array_key_exists($key, $set)) {
                $value = $set[$key];
            }
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $html .= '<input type="hidden" name="' . $key . '[' . $k . ']" value="' . $v . '" />';
                }
            } else {
                $html .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
            }
        }
        return $html;
    }

    /**
     * 获取一个超出忽略字符串
     * @param $str  完整字符串
     * @param $end  截取长度
     * @param int $start    开始截取下标
     * @param string $add   超出后附加的字符串
     * @return string   截取后的字符串
     */
    function substrEllipsis($str, $end, $start = 0, $add = ' ...')
    {
        if ($start >= 0) {
            $str = strip_tags($str);
            $len = strlen($str);
            return mb_substr($str, $start, $end, 'utf-8') . ($len > $end ? $add : '');
        } else {
            $str = strip_tags($str);
            $len = strlen($str);
            return ($len > $end ? $add : '') . mb_substr($str, $len - $end, $end, 'utf-8');
        }
    }

}