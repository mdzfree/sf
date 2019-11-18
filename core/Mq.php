<?php
    class Core_Mq extends Core_Bean
    {
        private $_session_id;//会话编号

        public function __construct($name = null)
        {
            parent::__construct('queue');
        }
        /**
         * 设置会话编号
         * @param $sessionId
         */
        public function setSessionId($sessionId)
        {
            $this->_session_id;
        }

        /**
         * 发布消息
         * @param $exchange 交换机名
         * @param $routingKey   路由名
         * @param $props    附加参数
         * @param $body 消息体
         * @return bool 是否发布成功
         */
        public function publish($exchange, $routingKey, $props, $body)
        {
            $bind = array(
                'exchange' => $exchange
                , 'routing_key' => $routingKey
                , 'props' => $props
                , 'body' => $body
                , 'status' => 0
                , 'create_time' => date('Y-m-d H:i:s')
                , 'update_time' => date('Y-m-d H:i:s')
            );
            $result = $this->insert($bind);
            if ($result) {
                return $result;
            }
            return false;
        }

        //消费
        public function consume($exchange)
        {
            $queryQty = 0;
            $execQty = 0;
            if (empty($this->_session_id)) {
                $this->_session_id = sfget_now_time_long_number();
            }
            //占用任务
            $sql = sprintf('UPDATE %s SET session_id = "%s", update_time = "' . date('Y-m-d H:i:s') .  '" WHERE status = 0 AND session_id = "" %s ORDER BY id ASC LIMIT 5', DB_TABLE_PREFIX . 'queue', $this->_session_id, ' AND ' . $this->db->quoteInto('exchange = ?', $exchange));
            $result = $this->db->exec($sql);
            if ($result) {
                $sessionId = $this->_session_id;
                $datas = $this->queue->session_id->$sessionId->getAll();
                if (!empty($datas)) {
                    $queryQty = count($datas);
                    foreach ($datas as $value) {
                        switch ($value['exchange']) {
                            case 'event':
                                $result = sftrigger_event('event_' . $value['routing_key'], 1, $value);
                                if (sfarray_true($result)) {
                                    $this->update(array('status' => 1, 'update_time' => date('Y-m-d H:i:s')), array('id' => $value['id']));
                                    $logBind = $value;
                                    $logBind['status'] = 1;
                                    $logBind['update_time'] = date('Y-m-d H:i:s');
                                    unset($logBind['id']);
                                    $result = $this->db->insert(DB_TABLE_PREFIX . 'queue_log', $logBind);
                                    if ($result) {
                                        $this->delete(array('id' => $value['id']));
                                        $execQty++;
                                    }
                                } else {
                                    $this->update(array('session_id' => '', 'update_time' => date('Y-m-d H:i:s')), array('id' => $value['id'], 'session_id' => $sessionId));
                                    $logBind = $value;
                                    $exception = sfget_event_exception('event_' . $value['routing_key'], new Exception('无事件触发'));
                                    $logBind['log'] = $exception['message'];
                                    $logBind['update_time'] = date('Y-m-d H:i:s');
                                    unset($logBind['id']);
                                    $this->db->insert(DB_TABLE_PREFIX . 'queue_log', $logBind);
                                }
                                break;
                            case 'path':
                                $log = '';
                                if (is_numeric(strpos($value['routing_key'], '-'))) {
                                    list($class, $fun) = explode('-', $value['routing_key']);
                                    $object = new $class();
                                    if (!empty($object)) {
                                        if (method_exists($object, $fun)) {
                                            $result = 0;
                                            if (!empty($value['body'])) {
                                                $json = json_decode($value['body'], true);
                                                if (!empty($json) && is_array($json)) {
                                                    $isNumber = true;
                                                    foreach ($json as $k => $v) {
                                                        if (!is_numeric($k)) {
                                                            $isNumber = false;
                                                            break;
                                                        }
                                                    }
                                                    if ($isNumber) {
                                                        $result = call_user_func_array(array($object, $fun), $json);
                                                    } else {
                                                        $result = call_user_func_array(array($object, $fun), array($value['body']));
                                                    }
                                                } elseif (is_numeric(strpos($_POST['param'], "\r\n"))) {
                                                    $result = call_user_func_array(array($object, $fun), array(parse_ini_string($value['body'])));
                                                } else {
                                                    $result = call_user_func_array(array($object, $fun), array($value['body']));
                                                }
                                            } else {
                                                $result = $object->$fun();
                                            }
                                            $log .= "\r\n" . print_r($result, true);
                                        } else {
                                            $log .= "\r\n" . "不方法：" . $fun;
                                        }
                                    } else {
                                        $log .= "\r\n" . "不存在：" . $class . "类";
                                    }
                                }
                                if ($result) {
                                    $this->update(array('status' => 1, 'update_time' => date('Y-m-d H:i:s')), array('id' => $value['id']));
                                    $logBind = $value;
                                    $logBind['status'] = 1;
                                    $logBind['update_time'] = date('Y-m-d H:i:s');
                                    unset($logBind['id']);
                                    $result = $this->db->insert(DB_TABLE_PREFIX . 'queue_log', $logBind);
                                    if ($result) {
                                        $this->delete(array('id' => $value['id']));
                                        $execQty++;
                                    }
                                } else {
                                    $this->update(array('session_id' => '', 'update_time' => date('Y-m-d H:i:s')), array('id' => $value['id'], 'session_id' => $sessionId));
                                    $logBind = $value;
                                    $logBind['log'] = $log;
                                    $logBind['update_time'] = date('Y-m-d H:i:s');
                                    unset($logBind['id']);
                                    $this->db->insert(DB_TABLE_PREFIX . 'queue_log', $logBind);
                                }
                                break;
                        }
                    }
                }
            }
            return $queryQty . ':' . $execQty;
        }
    }