<?php
    class Default_CommonBean extends Core_Bean
    {
        public function addLog($id = 1)
        {
            sflog('test', time(), '测试定时任务：' . $id);
            //sfexception('故意报错！');
        }
        
        public function consumeMessage()
        {
            $mq = Core_Mq::instance();
            $datas = $mq->getList('message');
            if (!empty($datas)) {
                $mq->update($datas[0]['id'], array('log' => '测试用！'));
                $mq->wait();
            }
        }
    }