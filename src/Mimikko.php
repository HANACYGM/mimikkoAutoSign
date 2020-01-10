<?php
namespace src;

use Curl\Curl;

class Mimikko {
    private $host = "https://api1.mimikko.cn";
    private $userAgent = "okhttp/3.8.0";
    private $appId = "wjB7LOP2sYkaMGLC";
    private $curlInstance;
    private $user;
    private $password;
    public $response = array();
    
    //step1
    private $token;
    private $userName;

    //step2
    private $servantId;

    public function __construct($user, $password) {
        $this->user = $user;
        $this->password = $password;
        
        $this->curlInstance = new Curl();
        $this->curlInstance->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $this->curlInstance->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $this->curlInstance->setHeader("User-Agent", $this->userAgent);
        $this->curlInstance->setHeader("Content-Type", "application/json");
        $this->curlInstance->setHeader("Accept-Language", "zh-cn");
        $this->curlInstance->setHeader("AppID", $this->appId);
        
    }
    /**
     * 获取 token 并在 header 里设置 Authorization
     * @return type
     */
    public function getToken(){
        $url = $this->host . "/client/user/LoginWithPayload";
        $this->curlInstance->post($url, json_encode(array(
                    'id' => $this->user,
                    'password' => hash('sha256', $this->password)
                )));
        
        $this->getError();
        
        $this->response[__FUNCTION__] = $response = json_decode($this->curlInstance->response, true);
        
        $this->curlInstance->setHeader("Authorization", $this->token = $response['body']['Token'] ?? "");
        $this->userName = $response['body']['UserName'] ?? "";
        
        return $this;
    }
    
    /**
     * 获取用户信息
     * @return $this
     */
    protected function getUserOwnInformation(){
        $url = $this->host . "/client/user/GetUserOwnInformation";
        $this->curlInstance->get($url);
        
        $this->response[__FUNCTION__] = $response = json_decode($this->curlInstance->response);
        return $this;
    }
    
    /**
     * 获取助手ID
     * @return $this
     */
    public function getServantId(){
        $this->getUserOwnInformation();
        $url = $this->host . "/client/Servant/GetServantList?startIndex=0&count=9999";
        $this->curlInstance->get($url);
        
        $this->getError();
        
        $this->response[__FUNCTION__] = $response = json_decode($this->curlInstance->response, true);
        $this->servantId = "";
        foreach($response['body']['Items'] as $servant){
            if($servant['IsDefault']){
                $this->servantId = $servant['ServantId'];
                break;
            }
        }
        
        return $this;
    }
    
    /**
     * 兑换能量值
     * @return $this
     */
    public function getExchangeReward(){
        $url = $this->host . "/client/love/ExchangeReward";
        
        $this->curlInstance->get($url, array(
            'servantId' => $this->servantId
        ));
        
        $this->getError();
        
        $this->response[__FUNCTION__] = $response = json_decode($this->curlInstance->response);
        return $this;
    }
    /**
     * 签到
     * @return $this
     */
    public function SignAndSignInformationV2(){
        $url = $this->host . "/client/RewardRuleInfo/SignAndSignInformationV2/" . urlencode($this->userName);
        
        $this->curlInstance->get($url);
        
        $this->getError();
        
        $this->response[__FUNCTION__] = $response = json_decode($this->curlInstance->response);
        return $this;
    }


    private function getError(){
        if($this->curlInstance->error_code !== 0 || !empty($this->curlInstance->error_message)){
            throw new Exception(__FUNCTION__ . "异常" . json_encode([
                "response"              => $this->response,
                "request_headers"       => $this->curlInstance->request_headers,
                "error_code" => $this->curlInstance->error_code,
                "error_message" => $this->curlInstance->error_message
            ]));
        }
    }
    
    function __destruct() {
        $this->curlInstance->close();
    }
}
