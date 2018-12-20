<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Payment\Channels;

use XuTL\Payment\ChannelInterface;

/**
 * 支付宝支付渠道
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Alipay implements ChannelInterface
{

    /**
     * @var string 网关地址
     */
    public $endpoint = 'https://api.mch.weixin.qq.com';

    const SIGNATURE_METHOD_RSA = 'RSA';
    const SIGNATURE_METHOD_RSA2 = 'RSA2';

    /**
     * @var integer
     */
    public $appId;

    /** @var string */
    public $alipayAccount;

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
    public $signType = self::SIGNATURE_METHOD_RSA2;

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

    public function app(){

    }

    public function mp(){

    }

    public function miniapp(){

    }

    public function wap(){

    }

    public function scan(){

    }

    public function pos(){

    }
}