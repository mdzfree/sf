<?php
    class Default_CommonBean extends Core_Bean
    {
        public function addLog($id = 1)
        {
            sflog('test', time(), '测试定时任务：' . $id);
            //sfexception('故意报错！');
        }
    }