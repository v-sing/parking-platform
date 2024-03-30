<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace VSing\ParkingPlatform\Kernel\Cache;


use VSing\ParkingPlatform\Factory;
use VSing\ParkingPlatform\Kernel\ServiceContainer;
use VSing\ParkingPlatform\Parking\Application;
use VSing\ParkingPlatform\ParkingPlatform;

class Cache
{
    /**
     * @var array 缓存的实例
     */
    public $instance = [];

    /**
     * @var int 缓存读取次数
     */
    public $readTimes = 0;

    /**
     * @var int 缓存写入次数
     */
    public $writeTimes = 0;

    /**
     * @var object 操作句柄
     */
    public $handler;

    protected $app;


    /**
     * Cache constructor.
     *
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;

    }

    /**
     * 连接缓存驱动
     * @access public
     * @param array $options 配置数组
     * @param bool|string $name 缓存连接标识 true 强制重新连接
     * @return Driver
     */
    public function connect(array $options = [], $name = false)
    {
        $type = !empty($options['type']) ? $options['type'] : 'File';

        if (false === $name) {
            $name = md5(serialize($options));
        }
        if (true === $name || !isset($this->instance[$name])) {
            $class = false === strpos($type, '\\') ?
                '\\VSing\\ParkingPlatform\\Kernel\\Cache\\Driver\\' . ucwords($type) :
                $type;
            if (true === $name) {
                return new $class($options);
            }
            $this->instance[$name] = new $class($options);
        }
        return $this->instance[$name];
    }

    /**
     * 自动初始化缓存
     * @access public
     * @param array $options 配置数组
     * @return Driver
     */
    public function init(array $options = [])
    {
        if (is_null($this->handler)) {
            $config = $this->app['config'];
            if (empty($options) && 'complex' == $config['cache']['type']) {
                $default = $config['cache']['default'];
                // 获取默认缓存配置，并连接
                $options = $config['cache'][$default['type']] ?: $default;
            } elseif (empty($options)) {
                $options = $config['cache'];
            }
            $this->handler = $this->connect($options);
        }

        return $this->handler;
    }

    /**
     * 切换缓存类型 需要配置 cache.type 为 complex
     * @access public
     * @param string $name 缓存标识
     * @return Driver
     */
    public function store($name = '')
    {
        $config = $this->app['config'];
        if ('' !== $name && 'complex' == $config['cache']['type']) {
            return $this->connect($config['cache'][$name], strtolower($name));
        }
        return $this->init();
    }

    /**
     * 判断缓存是否存在
     * @access public
     * @param string $name 缓存变量名
     * @return bool
     */
    public function has($name)
    {
        $this->readTimes++;

        return $this->init()->has($name);
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存标识
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get($name, $default = false)
    {
        $this->readTimes++;
        return $this->init()->get($name, $default);
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存标识
     * @param mixed $value 存储数据
     * @param int|null $expire 有效时间 0为永久
     * @return boolean
     */
    public function set($name, $value, $expire = null)
    {
        $this->writeTimes++;

        return $this->init()->set($name, $value, $expire);
    }

    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param string $name 缓存变量名
     * @param int $step 步长
     * @return false|int
     */
    public function inc($name, $step = 1)
    {
        $this->writeTimes++;

        return $this->init()->inc($name, $step);
    }

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param string $name 缓存变量名
     * @param int $step 步长
     * @return false|int
     */
    public function dec($name, $step = 1)
    {
        $this->writeTimes++;

        return $this->init()->dec($name, $step);
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存标识
     * @return boolean
     */
    public function rm($name)
    {
        $this->writeTimes++;

        return $this->init()->rm($name);
    }

    /**
     * 清除缓存
     * @access public
     * @param string $tag 标签名
     * @return boolean
     */
    public function clear($tag = null)
    {
        $this->writeTimes++;

        return $this->init()->clear($tag);
    }

    /**
     * 读取缓存并删除
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function pull($name)
    {
        $this->readTimes++;
        $this->writeTimes++;

        return $this->init()->pull($name);
    }

    /**
     * 如果不存在则写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value 存储数据
     * @param int $expire 有效时间 0为永久
     * @return mixed
     */
    public function remember($name, $value, $expire = null)
    {
        $this->readTimes++;

        return $this->init()->remember($name, $value, $expire);
    }

    /**
     * 缓存标签
     * @access public
     * @param string $name 标签名
     * @param string|array $keys 缓存标识
     * @param bool $overlay 是否覆盖
     * @return Driver
     */
    public function tag($name, $keys = null, $overlay = false)
    {
        return $this->init()->tag($name, $keys, $overlay);
    }

}
