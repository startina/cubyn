<?php
namespace startina\cubyn;
use Curl\Curl;
use ErrorException;

abstract class Basic {
    protected $connectTimeout = 0;  // 网络超时时间
    protected $errorTimesLimit = 1; // 网络故障重试次数
    protected $errorTimes = 0;  // 错误次数
    protected $key = '';    // 请求密匙
    protected $host = 'https://api.cubyn.com/v2/';
    protected $errorMsg = '';
    protected $response = null;
    protected $totalCount = 0;  //  列表总数量
    protected $info = null;
    protected $token = null;
    protected $tokenExpired = 0;

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

    /**
     * @param $msg
     * @throws \Exception
     */
    protected function setError($msg)
    {
        $this->errorMsg = $msg;
        throw new \Exception($msg);
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
     * @throws \Exception|\ErrorException
     */
    protected function request(string $uri, array $params = [], string $method = 'get')
    {
        try {
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
                    if (is_object($curl->response) && !empty($curl->response->message)) {
                        $this->setError($curl->response->message);
                    } else {
                        $this->setError('API接口-未知错误');
                    }
                    return false;
            }
        } catch (\Exception $exception) {
            $this->setError($exception->getMessage());
            return  false;
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
     * @desc 获取接口基本信息
     * @url https://developers-storage.cubyn.com/#authentication
     * @return bool|object|null
     * @throws \Exception|\ErrorException
     */
    public function info()
    {
        if (!$this->info) {
            $this->info = $this->request('');
        }
        return  $this->info;
    }

    /**
     * 获取token令牌。用于部分接口所需，如下载
     * @param string $type
     * @return |null
     * @throws \Exception|\ErrorException
     */
    public function getToken($type = 'short') {
        if (!$this->token || (time() > $this->tokenExpired)) {
            $uri = 'tokens';
            $params = [];
            $params['type'] = $type;
            $params['userId'] = $this->info()->auth->id;
            $obj = $this->request($uri, $params, 'post');
            $this->token = $obj->token;
            $this->tokenExpired = time() + $obj->ttl/1000 - 3;  //  提前3s过期
        }
        return $this->token;
    }


}