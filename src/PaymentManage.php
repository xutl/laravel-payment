<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Payment;

use Closure;
use InvalidArgumentException;

/**
 * 支付渠道管理
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PaymentManage
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The array of resolved services drivers.
     *
     * @var ChannelInterface[]
     */
    protected $channels = [];

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * Create a new filesystem manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 获取支付渠道配置
     *
     * @param  string $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["payment.channels.{$name}"];
    }

    /**
     * 获取支付渠道实例
     *
     * @param  string $name
     * @return ChannelInterface
     */
    public function get($name)
    {
        return $this->channels[$name] ?? $this->resolve($name);
    }

    /**
     * 设置支付渠道实例
     *
     * @param  string $name
     * @param  mixed $service
     * @return $this
     */
    public function set($name, $service)
    {
        $this->channels[$name] = $service;
        return $this;
    }

    /**
     * 解析给定的支付渠道
     *
     * @param  string $name
     * @return ChannelInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (isset($this->customCreators[$config['channel']])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create' . ucfirst($config['channel']) . 'Channel';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new InvalidArgumentException("The [{$config['channel']}] is not supported.");
        }
    }

    /**
     * Register a custom channel creator Closure.
     *
     * @param  string $driver
     * @param  \Closure $callback
     * @return $this
     */
    public function extend($driver, Closure $callback)
    {
        $this->customCreators[$driver] = $callback;
        return $this;
    }

    /**
     * Call a custom channel creator.
     *
     * @param  array $config
     * @return ChannelInterface
     */
    protected function callCustomCreator(array $config)
    {
        $driver = $this->customCreators[$config['driver']]($this->app, $config);
        return $driver;
    }
}