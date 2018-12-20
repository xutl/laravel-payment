<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Payment\Channels;

use XuTL\Payment\ChannelInterface;

/**
 * 微信支付渠道
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Wechat implements ChannelInterface
{
    const SIGNATURE_METHOD_MD5 = 'MD5';
    const SIGNATURE_METHOD_SHA256 = 'HMAC-SHA256';

    //退款资金来源
    const FUNDING_SOURCE_RECHARGE = 'REFUND_SOURCE_RECHARGE_FUNDS';//可用余额
    const FUNDING_SOURCE_UNSETTLED = 'REFUND_SOURCE_UNSETTLED_FUNDS';//未结算资金

    /**
     * @var string 网关地址
     */
    public $endpoint = 'https://api.mch.weixin.qq.com';

    /**
     * @var string 绑定支付的开放平台 APPID
     */
    public $appId;

    /**
     * @var string 商户支付密钥
     * @see https://pay.weixin.qq.com/index.php/core/cert/api_cert
     */
    public $apiKey;

    /**
     * @var string 商户号
     * @see https://pay.weixin.qq.com/index.php/core/account/info
     */
    public $mchId;

    /**
     * @var string 私钥
     */
    public $privateKey;

    /**
     * @var string 公钥
     */
    public $publicKey;

    /**
     * @var string 签名方法
     */
    public $signType = self::SIGNATURE_METHOD_SHA256;

    public $sslCa = '';

    /**
     * 链接超时
     * @var float
     */
    public $timeout = 5.0;

    /**
     * Wechat constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            foreach ($config as $name => $value) {
                $this->$name = $value;
            }
        }
        $this->init();
    }

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {

    }

    /**
     * 返回网关地址
     * @return string
     */
    public function getBaseUri()
    {
        return $this->endpoint;
    }

    public function app()
    {

    }

    public function mp()
    {

    }

    public function miniapp()
    {

    }

    public function wap()
    {

    }

    public function scan()
    {

    }

    public function pos()
    {

    }
}