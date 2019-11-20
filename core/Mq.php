<?php
    class Core_Mq extends Core_Bean
    {
        private $_session_id;//会话编号
        private $_lastList;

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
         * @param Core_Base $exchange  交换机名
         * @param string $routingKey    路由名
         * @param string $props     附加参数
         * @param string $body  消息体
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

        public function getList($exchange, $tag = null)
        {
            $this->_lastList = array();
            if (!empty($tag)) {
                $this->_session_id = $tag;
            }
            if (empty($this->_session_id)) {
                $this->_session_id = sfget_now_time_long_number();
            }
            //占用任务
            $sql = sprintf('UPDATE %s SET session_id = "%s", update_time = "' . date('Y-m-d H:i:s') .  '" WHERE status = 0 AND session_id = "" %s ORDER BY id ASC LIMIT 1', DB_TABLE_PREFIX . 'queue', $this->_session_id, ' AND ' . $this->db->quoteInto('exchange = ?', $exchange));
            $result = $this->db->exec($sql);
            if ($result) {
                $sessionId = $this->_session_id;
                $datas = $this->queue->session_id->$sessionId->getAll();
                $this->_lastList = $datas;
                return $datas;
            }
            return null;
        }

        public function update($id, $newData)
        {
            foreach ($this->_lastList as $key => $value) {
                if ($value['id'] == $id) {
                    $this->_lastList[$key] = array_merge($this->_lastList[$key], $newData);
                    return true;
                }
            }
            return null;
        }
        
        public function wait()
        {
            if (!empty($this->_lastList)) {
                $this->begin();
                $sessionId = $this->_lastList[0]['session_id'];
                $this->update(array('status' => 1, 'update_time' => date('Y-m-d H:i:s')), array('session_id' => $sessionId));
                foreach ($this->_lastList as $value) {
                    $logBind = $value;
                    $logBind['status'] = 1;
                    $logBind['update_time'] = date('Y-m-d H:i:s');
                    unset($logBind['id']);
                    $result = $this->db->insert(DB_TABLE_PREFIX . 'queue_log', $logBind);
                    if ($result) {
                        $this->delete(array('id' => $value['id']));
                    } else {
                        $this->rollBack();
                        break;
                    }
                }
                $this->commit();
                return true;
            }
            return null;
        }

        //消费
        public function execTask($exchange, $limit = 5)
        {
            $queryQty = 0;
            $execQty = 0;
            if (empty($this->_session_id)) {
                $this->_session_id = sfget_now_time_long_number();
            }
            //占用任务
            $sql = sprintf('UPDATE %s SET session_id = "%s", update_time = "' . date('Y-m-d H:i:s') .  '" WHERE status = 0 AND session_id = "" %s ORDER BY id ASC LIMIT ' . $limit, DB_TABLE_PREFIX . 'queue', $this->_session_id, ' AND ' . $this->db->quoteInto('exchange = ?', $exchange));
            $result = $this->db->exec($sql);
            if ($result) {
                $sessionId = $this->_session_id;
                $datas = $this->queue->session_id->$sessionId->getAll();
                if (!empty($datas)) {
                    $queryQty = count($datas);
                    foreach ($datas as $value) {
                        switch ($value['exchange']) {
                            case 'event':
                                $log = '';
                                $eventName = $value['routing_key'];
                                sftrigger_event($eventName, 0, $value);
                                $errors = sfget_event_exception_list($eventName);
                                if (empty($errors)) {
                                    $value['status'] = 1;
                                } else {
                                    $value['status'] = 0;
                                    foreach ($errors as $e) {
                                        $log .= $e->getMessage() . "\r\n";
                                    }
                                    $value['log'] = $log;
                                }
                                $logBind = $value;
                                unset($logBind['id']);
                                $result = $this->db->insert(DB_TABLE_PREFIX . 'queue_log', $logBind);
                                if ($result) {
                                    $this->delete(array('id' => $value['id']));
                                    $execQty++;
                                }
                                break;
                            case 'path':
                                $log = '';
                                if (is_numeric(strpos($value['routing_key'], '-'))) {
                                    list($class, $fun) = explode('-', $value['routing_key']);
                                    $object = new $class();
                                    if (!empty($object)) {
                                        if (method_exists($object, $fun)) {
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
                                            $log .= print_r($result, true) . "\r\n";
                                        } else {
                                            $log .= "不方法：" . $fun . "\r\n";
                                        }
                                    } else {
                                        $log .= "不存在：" . $class . "类" . "\r\n";
                                    }
                                }
                                if ($result) {
                                    $value['status'] = 1;
                                } else {
                                    $value['status'] = 0;
                                }
                                $logBind = $value;
                                $logBind['log'] = $log;
                                unset($logBind['id']);
                                $result = $this->db->insert(DB_TABLE_PREFIX . 'queue_log', $logBind);
                                if ($result) {
                                    $this->delete(array('id' => $value['id']));
                                    $execQty++;
                                }
                                break;
                            case 'export':
                                $log = '';
                                if (is_numeric(strpos($value['routing_key'], '-'))) {
                                    list($class, $fun) = explode('-', $value['routing_key']);
                                    $object = new $class();
                                    if (!empty($object)) {
                                        if (method_exists($object, $fun)) {
                                            $result = 0;
                                            list($start, $limit) = explode('-', $value['props']);
                                            if (!empty($value['body'])) {
                                                if (!empty($limit)) {
                                                    $sql = $value['body'] . ' limit ' . $start . ',' . ($limit - $start);
                                                    $datas = $this->db->fetchAll($sql);
                                                    try {
                                                        $result = $object->$fun($datas, $value);
                                                        if (!is_bool($result)) {
                                                            $value['data'] = json_encode($result);
                                                            $result = true;
                                                        }
                                                    } catch (Exception $e) {
                                                        $result = false;
                                                        $log .= $e->getMessage() . "\r\n";
                                                    }
                                                } else {
                                                    $log .= "找不到分页参数" . "\r\n";
                                                }
                                            } else {
                                                $log .= "找不到导出SQL" . "\r\n";
                                            }
                                            $log .= print_r($result, true) . "\r\n";
                                        } else {
                                            $log .= "不方法：" . $fun . "\r\n";
                                        }
                                    } else {
                                        $log .= "不存在：" . $class . "类" . "\r\n";
                                    }
                                }
                                $logBind = $value;
                                $logBind['log'] = $log;
                                $logBind['update_time'] = date('Y-m-d H:i:s');
                                unset($logBind['id']);
                                $result = $this->db->insert(DB_TABLE_PREFIX . 'queue_log', $logBind);
                                if ($result) {
                                    $this->delete(array('id' => $value['id']));
                                    $execQty++;
                                }
                                break;
                        }
                    }
                }
            }
            return $queryQty . ':' . $execQty;
        }

        public function publishEventTask($name, $param)
        {
            $this->publish('event', $name, '', is_array($param) ? json_encode($param) : $param);
        }

        public function publishPathTask($path, $param)
        {
            $this->publish('path', $path, '', is_array($param) ? json_encode($param) : $param);
        }

        public function publishExportTask($callbackPath, $sql, $limit, $total)
        {
            for ($i = 0; $i < $total; $i += $limit) {
                $this->publish('export', $callbackPath, $i . '-' . ($i + $limit), $sql);
            }
        }
    }