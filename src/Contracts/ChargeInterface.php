<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Payment\Contracts;

/**
 * 付款接口
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
interface ChargeInterface
{
    /**
     * 获取发起支付的客户端IP
     * @return string
     */
    public function getClientIp();

    /**
     * 商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一。
     * @return string
     */
    public function getOutTradeNo();

    /**
     * 订单总金额，单位为分，只能为整数
     * @return int
     */
    public function getTotalFee();

    /**
     * 设置符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     * @return string
     */
    public function getCurrency();

    /**
     * 获取附加参数
     * @return mixed
     */
    public function getAttach();

    /**
     * 获取订单内容
     * @return mixed
     */
    public function getBody();
}