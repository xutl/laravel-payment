<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Payment;

use Closure;
use InvalidArgumentException;
use XuTL\Payment\Channels\Alipay;
use XuTL\Payment\Channels\Wechat;

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
     * 创建 支付宝支付 渠道
     * @param array $config
     * @return Alipay
     */
    public function createAlipayChannel(array $config)
    {
        return new Alipay([
            'endpoint' => $config['endpoint'],
            'accessId' => $config['access_id'],
            'accessKey' => $config['access_key'],
            'securityToken' => $config['securityToken'] ?? null,
        ]);
    }

    /**
     * 创建 微信支付 渠道
     * @param array $config
     * @return Wechat
     */
    public function createWechatChannel(array $config)
    {
        return new Wechat([
            'endpoint' => $config['endpoint'] ?? 'https://api.mch.weixin.qq.com',
            'appId' => $config['app_id'],
            'apiKey' => $config['api_key'],
            'mchId' => $config['mch_id'],
            'privateKey' => $config['private_key'],
            'publicKey' => $config['public_key'],
            'timeout' => $config['timeout'],
            'sslCa' => $config['ssl_ca'],
            'signType' => $config['signType'] ?? Wechat::SIGNATURE_METHOD_SHA256
        ]);
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