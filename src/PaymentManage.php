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
 * Class PaymentManage
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
     * Get the aliyun service configuration.
     *
     * @param  string $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["payment.channels.{$name}"];
    }

    /**
     * Attempt to get the disk from the local cache.
     *
     * @param  string $name
     * @return AliyunInterface
     */
    public function get($name)
    {
        return $this->channels[$name] ?? $this->resolve($name);
    }

    /**
     * Set the given service instance.
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
     * Resolve the given disk.
     *
     * @param  string $name
     * @return AliyunInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create' . ucfirst($config['channel']) . 'Service';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new InvalidArgumentException("The [{$config['channel']}] is not supported.");
        }
    }

    /**
     * Register a custom driver creator Closure.
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
     * Call a custom driver creator.
     *
     * @param  array $config
     * @return AliyunInterface
     */
    protected function callCustomCreator(array $config)
    {
        $driver = $this->customCreators[$config['driver']]($this->app, $config);
        return $driver;
    }
}