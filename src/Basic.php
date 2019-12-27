<?php
namespace startina\cubyn;
use Curl\Curl;

abstract class Basic {
    protected $connectTimeout = 0;  // 网络超时时间
    protected $errorTimesLimit = 1; // 网络故障重试次数
    protected $errorTimes = 0;  // 错误次数
    protected $key = '';    // 请求密匙
    protected $host = 'https://api.cubyn.com/v2/';
    protected $errorMsg = '';
    protected $response = null;
    protected $totalCount = 0;  //  列表总数量

    /**
     * Client constructor.
     * @param $key string 应用密匙
     * @param null $connectTimeout curl超时时间，设为0则不设超时时间
     */
    public function __construct(string $key, int $connectTimeout = null, int $errorTimesLimit = 1)
    {
        $this->key = $key;
        $this->errorTimesLimit = $errorTimesLimit;
        if (!is_null($connectTimeout)) {
            $this->connectTimeout = $connectTimeout;
        }
    }
    protected function setError($msg)
    {
        $this->errorMsg = $msg;
    }
    protected function reinitErrorTimes()
    {
        $this->errorTimes = 0;
    }

    /**
     * @param string $uri 请求相对路径
     * @param array $params 请求参数
     * @param string $method 请求方式，支持[get,post,put,delete等]
     * @return bool|object|null
     * @throws \ErrorException
     */
    protected function request(string $uri, array $params = [], string $method = 'get')
    {
        $url = $this->host.$uri;
        $curl = new Curl();
        $curl->setTimeout($this->connectTimeout);
        $curl->setHeader('X-Application', $this->key);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $curl->setOpt(CURLOPT_ENCODING,'gzip');
        $curl->{$method}($url, $params);
        $curl->close();
        $this->response = $curl->response;
        $this->totalCount = $curl->responseHeaders['x-total-count'] ?? 0;

        switch ($curl->httpStatusCode) {
            case 0:
                // 请求超时
                if ($this->errorTimes++ < $this->errorTimes) {
                    return $this->request($uri, $params, $method);
                } else {
                    $this->setError($curl->message);
                    return false;
                }
            case 200:
                $this->reinitErrorTimes();
                return $curl->response;
            default:
                $this->reinitErrorTimes();
                $this->setError('未知错误');
                return false;
        }
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->errorMsg;
    }

    /**
     * 获取列表总数量
     * @return int
     */
    public function getTotalCount() {
        return $this->totalCount;
    }







    /**
     * @desc 创建包裹
     * @url https://developers-storage.cubyn.com/#step-3-create-parcels
     * @param $firstName
     * @param $lastName
     * @param $address
     * @param $orderRef
     * @param $objectCount
     * @param $items
     * @return bool|object|null
     * @throws \ErrorException
     */
    public function createParcels($firstName, $lastName, $address, $orderRef, $objectCount, $items)
    {
        $params = [];
        $params['firstName'] = $firstName;
        $params['lastName'] = $lastName;
        $params['address'] = $address;
        $params['orderRef'] = $orderRef;
        $params['objectCount'] = $objectCount;
        $params['items'] = $items;
        return $this->request('parcels', [], 'post');
    }

}