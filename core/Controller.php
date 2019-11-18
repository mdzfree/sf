<?php
/**
* 系统控制器基类
*/
/* @var $view Core_View */
class Core_Controller extends Core_Base
{
    /**
     * @var Core_View
     */
    public $view;

    function __construct()
    {
        $this->view = sfget_instance('Core_View', $this);

        $names = explode('_', get_class($this));
        if ($names[0] === 'Ex') {
            $names[0] = $names[1];
        }
        if (substr($names[0], 0, 2) == 'aa') {
            $this->view->setPath($names[0] . '/' . $names[1]);
        } else {
            $this->view->setPath($names[0]);
        }
        //_quit($names[0] . '/' . $names[1]);
        // $language = get_instance('Core_Language', $this->view);
        // $this->transData = $language->getTranslationLanguage('common', $this->view->template['path']);
    }

    public function isCall($methodName)
    {
        return true;
    }
    
    public function hasCall()
    {
        return false;
    }

    public function notAuthorizedAction()
    {
        sferror('not authorized!');
    }
}