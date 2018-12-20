<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Payment;

use Illuminate\Support\Facades\Facade;

/**
 * Class Payment
 *
 * @mixin PaymentManage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}