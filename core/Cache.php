<?php

/**
 * 缓存核心类
 * Class Core_Cache
 */
class Core_Cache extends Core_Bean
{
    public function __construct($name = null)
    {
        parent::__construct('memory');
    }

    private $_area = '0';//存储区域

    /**
     * 设置存储区域
     * @param $name
     * @return $this
     */
    public function setArea($name)
    {
        $this->_area = $name;
        return $this;
    }

    /**
     * 获取当前存储区域
     * @return string
     */
    public function getArea()
    {
        return $this->_area;
    }

    /**
     * 设置缓存数据
     * @param $key  键名
     * @param $value    键值
     * @param int $exp  过期秒数
     * @return bool|int 是否设置成功
     */
    public function set($key, $value, $exp = 3600)
    {
        $data = $this->memory->key->$key->andWhere('area', $this->_area)->toArray();
        if (empty($data)) {
            $bind = array(
                'area' => $this->_area
                , 'key' => $key
                , 'value' => $value
                , 'effective' => time() + $exp
                , 'create_time' => date('Y-m-d H:i:s')
                , 'update_time' => date('Y-m-d H:i:s')
            );
            $result = $this->insert($bind);
            if ($result) {
                return $result;
            }
        } else {
            $bind = array(
                'value' => $value
                , 'effective' => time() + $exp
                , 'update_time' => date('Y-m-d H:i:s')
            );
            $result = $this->update($bind, array('id' => $data['id'], 'update_time' => $data['update_time']));//防止冗余更新
            if ($result) {
                return $result;
            }
        }
        return false;
    }

    /**
     * 获取有效的缓存数据
     * @param $key  键名
     * @return bool|null
     */
    public function get($key)
    {
        $data = $this->memory->key->$key->andWhere('area', $this->_area)->order('id desc')->toArray();
        if (!empty($data)) {
            if (time() <= $data['effective']) {
                return $data['value'];
            } else {
                return false;//过期
            }
        }
        return null;
    }


    /**
     * 堆入数据
     * @param $key 键名
     * @param $value 数值
     * @param int $exp  过期秒数
     * @return bool 是否堆入成功
     */
    public function push($key, $value, $exp = 3600)
    {
        $bind = array(
            'area' => $this->_area
            , 'key' => $key
            , 'value' => $value
            , 'effective' => time() + $exp
            , 'create_time' => date('Y-m-d H:i:s')
            , 'update_time' => date('Y-m-d H:i:s')
        );
        $result = $this->insert($bind);
        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * 拉取数据
     * @param $key  键名
     * @return |null    数组
     */
    public function pull($key)
    {
        $datas = $this->memory->key->$key->andWhere('area', $this->_area)->andWhere(time() . ' < effective')->getAll();
        if (!empty($datas)) {
            return $datas;
        }
        return null;
    }

    /**
     * 移除拉取中的数据
     * @param $key  键名
     * @param $id   唯一编号
     * @return bool 是否移除成功
     */
    public function removePull($key, $id)
    {
        $result = $this->delete(array('key' => $key, 'id' => $id));
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * 设置缓存对象
     * @param $key
     * @param $object
     * @param int $exp
     * @return bool|int
     */
    public function setObject($key, $object, $exp = 3600)
    {
        return $this->set($key, serialize($object), $exp);
    }


    /**
     * 获取缓存对象
     * @param $key  键名
     * @return mixed|null   有效则返回存储对象，否则返回null
     */
    public function getObject($key)
    {
        $data = $this->get($key);
        if (!empty($data)) {
            return unserialize($data);
        }
        return null;
    }

    /**
     * 设置缓存数组
     * @param $key
     * @param $array
     * @param int $exp
     * @return bool|int
     */
    public function setArray($key, $array, $exp = 3600)
    {
        return $this->set($key, json_encode($array), $exp);
    }


    /**
     * 获取缓存数组
     * @param $key  键名
     * @return mixed|null   有效则返回存储数组，否侧返回null
     */
    public function getArray($key)
    {
        $data = $this->get($key);
        if (!empty($data)) {
            return json_decode($data);
        }
        return null;
    }
}