<?php
interface IAdmin {
    public function getId();//管理员关键ID
    public function getName();//管理员名称
    public function getEmail();//管理员联系邮箱
    public function getTelephone();//管理员联系电话
    public function getType();//管理员类型
    public function getGrade();//管理员等级
    public function getConfig();//管理员相关数据
}
/**
 * 系统基类，负责整个系统基本的业务和函数
 * @author JackMan  version 1.0 2013-10-15
 *
 */
class Core_Base
{
    /**
     * URL 对象
     * @var Core_Request
     */
    static $request;
    static $cache;
    static $language;
    static $templateConfig;

    /**
     * 类全局 对象
     * @var array
     */
    static $classGlobal;
    private $transData;
    private $flags = array('languages' => array());
    public $_language;
    public $lastMessage;
    public $lastData;
    public $lastError;
    public $lastErrorCode = -1;
    public $errors = array();

    /**
     * 所有子类首次实例化预留为全局对象
     */
    function __construct($init = null)
    {
        $className = get_class($this);
        if (!isset($GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$className])) {
            $GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$className] = $this;
        }
        $this->_language = sfget_instance('Core_Language');
        $this->init();
    }

    public function loadLanguage($name = null, $path = null)
    {
        if ($name == null) {
            $name = 'common';
        }
        if (!empty($this->flags[$path])) {
            return;
        }
        $language = sfget_instance('Core_Language');
        $this->transData = $language->getTranslationLanguage($name, $path);
        $this->flags[$path] = true;
    }
    /**
     * 将字符转为当前语言，可接受多个参数
     * @param  Mixed $key 接受多个参数
     * @return String      翻译后的字符串
     */
    public function __()
    {
        $ret = '';
        $args = func_get_args();
        foreach ($args as $key) {
            $k = strtolower(trim($key));
            if (isset($this->transData[$k])) {
                $ret .= $this->transData[$k];
            } else {
                if (isset($this->_language->data[$k])) {
                    $ret .= $this->_language->data[$k];
                } else {
                    $ret .= $key;   //无法翻译，则返回原文
                }
            }
        }
        return $ret;
    }


    /**
     * 类实例化（单例模式）
     */
    public static function instance()
    {
        $classFullName = get_called_class();
        if (!isset($GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$classFullName])) {
            if (class_exists($classFullName)) {
                $instance = $GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$classFullName] = new static();
                return $instance;
            }
        }
        return $GLOBALS['instances'][GLOBAL_INSTANCE_KEY][$classFullName];
    }

    function classGlobal($key, $value = NULL){
        $prefix = get_class($this) . '__';
        if ($value === NULL) {
            if (isset(self::$classGlobal[$prefix . $key])) {
                return self::$classGlobal[$prefix . $key];
            }
            return array();
        }
        self::$classGlobal[$prefix . $key] = $value;
    }

    function getPluginConfig()
    {
        $className = get_class($this);
        $d = current(explode('_', $className));
        $confFile = MODULE_DIR . DS . $d . DS . 'config.xml';
        if (is_file($confFile)) {
            $file = $confFile;
        } else {
            //TODO
            // $d module have no config
            return;
        }
        $tmpData = sfdecode_xml_file_to_array($file);
        $exConfFile = EXMODULE_CUR_DIR . DS . $d . DS . 'config.xml';
        if (is_file($exConfFile)) {
            $addTmpData = sfdecode_xml_file_to_array($exConfFile);
            $tmpData = $tmpData + $addTmpData;
        }
        return $tmpData;
    }

    /**
     * 获取当前语言
     */
    static function getCurLanguage()
    {
        /*
        $module = get_instance('Core_Model_Language');
        $language = array();
        if (isset($_REQUEST['lang'])) {
			if (isset($_GET['lang'])) {
				$lang = $_GET['lang'];
			} else if (isset($_POST['lang'])) {
				$lang = $_POST['lang'];
			} else {
				$lang = $_REQUEST['lang'];
			}
            $language = $module->getByCode($lang);

        }
        if (empty($language)) {
            $language = $module->getDefault();
        }
        */
        return LANGUAGE;
    }

    /**
     * 获取当前语言代码（最高优先参数中的语言代码，不存在则返回默认语言代码）
     */
    static function getCurLanguageCode()
    {
        if (empty(self::$language)) {
            $language = self::getCurLanguage();
            self::$language = $language['language__code'];

        }
        return self::$language;
    }

    static function getDefaultLanguageCode()
    {
        return LANGUAGE;
    }

    public function init()
    {
        if (empty(self::$templateConfig)) {
            $fileConfig = THEME_DEF_DIR . DS . 'config.xml';

            $config = sfdecode_xml_file_to_array($fileConfig);
            if (isset($config['request']) && isset($config['request']['map']) && isset($config['request']['map']['from'])) {
                $config['request']['map'] = array($config['request']['map']);
            }

            self::$templateConfig = $config;
        }
        if (empty(self::$request)) {
            self::$request = sfget_instance('Core_Request');
        }
    }

    /**
     * @return Core_Cache
     */
    public static function cache()
    {
        if (empty(self::$cache)) {
            self::$cache = sfget_instance('Core_Cache');
        }
        return self::$cache;
    }


    /**
     * 传参（配置）重走构建逻辑
     */
    function apply($argList)
    {
        if (!empty($argList)) {
            call_user_func_array(array($this, '__construct'), $argList);
        }
    }

    /**
     * 根据类名获取对象不同域下的单例()
     * @param String $name      例如 Core_Base
     * @param Object $object    单例的域
     * @param Array  $scope     单例的域组
     * @param Mixed  $arg       可选，多个参数用于初始化构造函数
     */
    static function getInstance($className, $object = null, $scope = null)
    {
        return sfget_instance($className, $object, $scope);
    }

    /**
     * 获取session对象
     */
    static function session()
    {
        if (!isset($_SESSION)) {
            $cookieID = session_id();
            if (empty($cookieID)) {
                $cookieID = $_COOKIE['PHPSESSID'];
            }
            if (empty($cookieID)) {
                session_start();
            } else {
                session_id($cookieID);
                session_start();
            }
        } else {
            $cookieID = session_id();
            if (empty($cookieID)) {
                $cookieID = $_COOKIE['PHPSESSID'];
                if (empty($cookieID)) {
                    session_start();
                } else {
                    session_id($cookieID);
                    session_start();
                }
            }
        }
        return $_SESSION;
    }
    
    public function hasError()
    {
        return !empty($this->lastError);
    }

    public function getError()
    {
        return $this->lastError;
    }

    public function getErrorCode()
    {
        return $this->lastErrorCode;
    }

    public function setError($content)
    {
        $args = func_get_args();
        $this->lastError = $content;
        if (count($args) > 1) {
            $this->lastError = call_user_func_array('sprintf', $args);
        }
        return false;
    }

    public function setErrorCode($code)
    {
        $this->lastErrorCode = $code;
        return $this;
    }

    /**
     * @param Exception $e
     * @return bool
     */
    public function setException($e)
    {
        return $this->setErrorCode($e->getCode())->setError($e->getMessage());
    }

    public function pushError($content, $code = -1, $data = null)
    {
        if ($code === null) {
            $code = -1;
        }
        $this->errors[] = array('content' => $content, 'code' => $code, $data);
    }

    public function getErrors()
    {
        if (empty($this->errors) && !empty($this->lastError)) {
            return array(array('content' => $this->lastError, 'code' => $this->lastErrorCode));
        }
        return $this->errors;
    }

    public function resetErrors()
    {
        $this->errors = array();
        return $this;
    }

    public function cloneError($object)
    {
        $this->lastError = $object->lastError;
        $this->lastErrorCode = $object->lastErrorCode;
        $this->errors = $object->errors;
        return false;
    }
    
    public function setErrorNull($content = '未知错误')
    {
        return $this->setErrorCode(100001)->setError($content);
    }

    public function setLocal($key, $data)
    {
        $io = Core_IoUtils::instance();
        $file = ROOT_DIR . DS . 'asset' . DS . 'internal' . DS . 'data' . DS . 'local' . DS . get_class($this) . DS . $key . '.log';
        $io->createDir(dirname($file));
        $io->writeFile($file, serialize($data));
        return $this;
    }

    public function getLocal($key)
    {
        $io = Core_IoUtils::instance();
        $file = ROOT_DIR . DS . 'asset' . DS . 'internal' . DS . 'data' . DS . 'local' . DS . get_class($this) . DS . $key . '.log';
        if (is_file($file)) {
            $data = $io->readFile($file);
            if (!empty($data)) {
                return unserialize($data);
            }
        }
        return null;
    }

    public function deleteLocal($key)
    {
        $file = ROOT_DIR . DS . 'asset' . DS . 'internal' . DS . 'data' . DS . 'local' . DS . get_class($this) . DS . $key . '.log';
        @unlink($file);
        return $this;
    }
}