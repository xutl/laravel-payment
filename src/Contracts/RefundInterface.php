<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Payment\Contracts;

/**
 * 退款接口
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
interface RefundInterface
{
    /**
     * 获取商户订单号
     * @return string
     */
    public function getOutTradeNo();

    /**
     * 商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母_-|*@ ，同一退款单号多次请求只退一笔。
     * @return string
     */
    public function getOutRefundNo();

    /**
     * 订单总金额，单位为分，只能为整数
     * @return int
     */
    public function getTotalFee();

    /**
     * 退款总金额，订单总金额，单位为分，只能为整数
     * @return int
     */
    public function getRefundFee();
}