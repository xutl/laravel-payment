<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Payment;

use Illuminate\Support\ServiceProvider;

/**
 * Class PaymentServiceProvider
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->setupConfig();

        $this->app->singleton('payment', function () {
            return new PaymentManage($this->app);
        });
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__ . '/../config/payment.php') ?: $raw;

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $source => config_path('payment.php'),
            ], 'payment-config');
        }

        $this->mergeConfigFrom($source, 'payment');
    }
}