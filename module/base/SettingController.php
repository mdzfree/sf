<?php
    class Base_SettingController extends Admin_AdminController
    {
        public function indexAction()
        {
            $bean = Base_SettingBean::instance();
            if (!empty($_POST)) {
                if ($bean->modifySetting($_POST)) {
                    sfresponse(1, '更新成功！');
                } else {
                    sfpush_admin_tmp_message($bean->getError());
                }
            }
            $group = sfret('group', 'default');
            $this->view->bind['groupList'] = $bean->getGroupList();
            $this->view->bind['name'] = $bean->getGroupNameByCode($group);
            $this->view->bind['list'] = $bean->getListByGroup($group);
            $this->view->setTemplate('admin/setting/index');
            $this->view->render();
        }
    }