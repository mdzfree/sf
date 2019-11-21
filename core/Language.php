<?php
/**
* 语言类
*/
class Core_Language extends Core_Base
{
    public $data = array();

    function __construct(){
        //加载核心语言包
        $this->data = $this->getExLanguageFile(ROOT_DIR . DS, 'common');
    }

    /**
     * 载入语言文件
     * @param  String $file 带路径的语言文件名
     * @return Array
     */
    public function getLanguageFile($file)
    {
        $returns = array();
        if (is_file($file)) {
            $datas = self::$cache->getCsvData($file);
            for ($i = 0, $len = count($datas); $i < $len; $i++) {
                if ( count($datas[$i]) == 2 ) {
                    $returns[strtolower(trim($datas[$i][0]))] = $datas[$i][1];
                }
            }
        }
        return $returns;
    }

    public function getExLanguageFile($base, $filename, $defBase = null, $curBase = null)
    {
        $lang = self::getCurLanguageCode();
        $data = array();
        // ./Language/xxx.csv
        $baseLangFile = $base . 'Language' . DS . $filename . '.csv';
        if (file_exists($baseLangFile)) {
            $data = $this->getLanguageFile($baseLangFile);
        }

        // ./Language/[zh_cn]/xxx.csv
        $baseLangFile2 = $base . 'Language' . DS . $lang . DS . $filename . '.csv';
        if (file_exists($baseLangFile2)) {
            foreach ($this->getLanguageFile($baseLangFile2) as $key => $value) {
                $data[$key] = $value;
            }
        }

        // Theme/def/Language/xxx.csv
        if (!empty($defBase)) {
            $defLangFile = $defBase . 'Language' . $filename . '.csv';
            if (file_exists($defLangFile)) {
                foreach ($this->getLanguageFile($defLangFile) as $key => $value) {
                    $data[$key] = $value;
                }
            }
            // Theme/def/Language/[zh_cn]/xxx.csv
            $defLangFile2 = $defBase. 'Language' . DS . $lang . DS . $filename . '.csv';
            if (file_exists($defLangFile2)) {
                foreach ($this->getLanguageFile($defLangFile2) as $key => $value) {
                    $data[$key] = $value;
                }
            }
        }

        // Theme/cur/Language/xxx.csv
        if (!empty($curBase)) {
            $curLangFile = $curBase . 'Language' . $filename . '.csv';
            if (file_exists($curLangFile)) {
                foreach ($this->getLanguageFile($curLangFile) as $key => $value) {
                    $data[$key] = $value;
                }
            }
            // Theme/cur/Language/[zh_cn]/xxx.csv
            $curLangFile2 = $curBase . 'Language' . DS . $lang . DS . $filename . '.csv';
            if (file_exists($curLangFile2)) {
                foreach ($this->getLanguageFile($curLangFile2) as $key => $value) {
                    $data[$key] = $value;
                }
            }
        }
        return $data;
    }

    /**
     * 获取翻译后的语言关联数组
     * @param  String $filename 文件名称
     * @param  String $path     相对路径
     * @return Array            翻译后的语言数组
     */
    public function getTranslationLanguage($filename, $path = null)
    {
        $defFileBase = null;
        $curFileBase = null;
        if ( empty($path) ) {
            $fileBase = ROOT_DIR .DS;
        } else{
            if ( $path == '/') {
                //模板语言包
                $fileBase = THEME_DEF_DIR . DS;
                $defFileBase = THEME_CUR_DIR . DS;
            } else {
                //插件语言包
                $fileBase = MODULE_DIR . DS . $path . DS;
                $defFileBase = EXMODULE_DEF_DIR . DS . $path . DS;
                $curFileBase = EXMODULE_CUR_DIR . DS . $path . DS;
            }
        }
        $datas = $this->getExLanguageFile($fileBase, $filename, $defFileBase, $curFileBase);
        foreach ($datas as $key => $value) {
            $this->data[$key] = $value;
        }
        return $this->data;
    }
}