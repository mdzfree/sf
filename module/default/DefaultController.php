<?php
/**
* demo
*/
class Default_DefaultController extends Core_Controller
{
    public function indexAction()
    {
        $this->view->parsePage('index');
        $this->view->render();
    }

    public function listAction()
    {
        $datas = sfgetforge('user/getList');
        $this->view->bind['list'] = $datas;
        $this->view->setTemplate('simple');
        $this->view->render();
    }
}