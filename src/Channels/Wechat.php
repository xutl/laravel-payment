<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Payment\Channels;

use DOMDocument;
use DOMElement;
use Exception;
use Illuminate\Support\Str;
use XuTL\Payment\ChannelInterface;
use XuTL\Payment\Traits\HasHttpRequest;

/**
 * 微信支付渠道
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Wechat implements ChannelInterface
{
    use HasHttpRequest;

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

    /**
     * Make a post request.
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     * @return array
     * @throws Exception
     */
    protected function request($endpoint, $params = [], $headers = [])
    {
        $params['appid'] = $this->appId;
        $params['mch_id'] = $this->mchId;
        $params['nonce_str'] = $this->generateRandomString(32);
        $params['sign_type'] = $this->signType;
        $params['sign'] = $this->generateSignature($params);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $root = new DOMElement('request');
        $dom->appendChild($root);
        $this->buildXml($root, $params);
        $content = $dom->saveXML();


        $content = $response->getBody()->getContents();

        return $this->request('post', $endpoint, [
            'headers' => $headers,
            'form_params' => $params,
        ]);
    }

    /**
     * 生成签名
     * @param array $params
     * @return string
     * @throws Exception
     */
    protected function generateSignature(array $params)
    {
        $bizParameters = [];
        foreach ($params as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $bizParameters[$k] = $v;
            }
        }
        ksort($bizParameters);
        $bizString = urldecode(http_build_query($bizParameters) . '&key=' . $this->apiKey);
        if ($this->signType == self::SIGNATURE_METHOD_MD5) {
            $sign = md5($bizString);
        } elseif ($this->signType == self::SIGNATURE_METHOD_SHA256) {
            $sign = hash_hmac('sha256', $bizString, $this->apiKey);
        } else {
            throw new Exception ('This encryption is not supported');
        }
        return strtoupper($sign);
    }

    /**
     * 解密退款通知
     * @param string $string
     * @return string
     */
    protected function refundDecode($string)
    {
        return openssl_decrypt(base64_decode($string), 'aes-256-ecb', md5($this->apiKey), OPENSSL_RAW_DATA);
    }

    /**
     * 转换XML到数组
     * @param \SimpleXMLElement|string $xml
     * @return array
     */
    protected function convertXmlToArray($xml)
    {
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 生成一个指定长度的随机字符串
     * @param int $length
     * @return string
     */
    protected function generateRandomString($length = 32): string
    {
        try {
            return Str::random($length);
        } catch (Exception $e) {
            $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
            $randStr = str_shuffle($str);
            $rands = substr($randStr, 0, $length);
            return $rands;
        }
    }
}