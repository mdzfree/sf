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

        /**
         * 建立导出消息队列
         */
        public function export()
        {
            $base = $this->db->select()->from(DB_TABLE_PREFIX . 'entity');
            Core_Mq::instance()->publishExportTask('Default_CommonBean-write', $base->assemble(), 1, 1);
        }

        //回调消费函数
        public function write($datas, $queue)
        {
            $headers = array('id', 'type', 'type_id', 'data', 'create_time');
            $io = Core_IoUtils::instance();
            $dsPath = '/asset/cache/' . session_id() . '/export/' . $queue['props'] . '.csv';
            $file = ROOT_DIR . $dsPath;
            $io->createDir(dirname($file));
            $io->writeCsv($file, $headers, $datas);
            sfexception('故意异常！');
            return $dsPath;
        }
    }