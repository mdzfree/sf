<?php
    class Default_CommonBean extends Core_Bean
    {
        public function addLog()
        {
            sflog('test', time(), '测试定时任务');
        }
    }