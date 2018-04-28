<?php
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */

require_once realpath ( dirname ( __FILE__ ) . '/RequestCore.php' );

define('DEFAULT_PORT', 8080);

/**
 * Class Aliyun_ACM_Client
 * The basic client to manage ACM
 */
class Aliyun_ACM_Client {

    protected $accessKey;

    protected $secretKey;

    protected $endPoint;

    protected $nameSpace;

    protected $port;

    protected $appName;

    public $serverList = array();

    public function __construct($endpoint, $port){
        $this->endPoint = $endpoint;
        $this->port = $port;
    }

    /**
     * @param mixed $accessKey
     */
    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
    }

    /**
     * @param mixed $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param mixed $nameSpace
     */
    public function setNameSpace($nameSpace)
    {
        $this->nameSpace = $nameSpace;
    }

    /**
     * @param mixed $appName
     */
    public function setAppName($appName)
    {
        $this->appName = $appName;
    }

    private function getServerListStr(){
        $server_host = str_replace(array('host','port'), array($this->endPoint, $this->port),
            'http://host:port/diamond-server/diamond');
        $request = new RequestCore();
        $request->set_request_url($server_host);
        $request->send_request(true);
        if($request->get_response_code() != '200'){
            print '[getServerList] got invalid http response: ('.$server_host.'.';
        }
        $serverRawList = $request->get_response_body();
        return $serverRawList;
    }

    public function refreshServerList(){
        $this->serverList = array();
        $serverRawList = $this->getServerListStr();
        if(is_string($serverRawList)){
            $serverArray = explode('\n', $serverRawList);
            foreach ($serverArray as $value){
                $value = trim($value);
                $singleServerList = explode(':', $value);
                $singleServer = null;
                if(count($singleServerList) == 1){
                    $singleServer = new Aliyun_ACM_Model_Server($value,
                        constant('DEFAULT_PORT'),
                        Aliyun_ACM_Util::isIpv4($value));
                }else{
                    $singleServer = new Aliyun_ACM_Model_Server($singleServerList[0],
                        $singleServerList[1],
                        Aliyun_ACM_Util::isIpv4($value));
                }
                $this->serverList[$singleServer->url] = $singleServer;
            }
        }
    }

    public function getServerList(){
        return $this->serverList;
    }

    public function getConfig($dataId, $group){
        if(!is_string($this->secretKey) ||
            !is_string($this->accessKey)){
            throw new Aliyun_ACM_Exception ( 'Invalid auth string', "invalid auth info for dataId: $dataId" );
        }

        Aliyun_ACM_Util::checkDataId($dataId);
        $group = Aliyun_ACM_Util::checkGroup($group);

        $servers = $this->serverList;
        $singleServer = $servers[array_rand($servers)];

        $acm_host = str_replace(array('host','port'), array($singleServer->url, $singleServer->port),
            'http://host:port/diamond-server/config.co');

        $acm_host .= "?dataId=".urlencode($dataId)."&group=".urlencode($group)
            ."&tenant=".urlencode($this->nameSpace);

        $request = new RequestCore();
        $request->set_request_url($acm_host);

        $headers = $this->getCommonHeaders($group);

        foreach ($headers as $header_key => $header_val) {
            $request->add_header($header_key, $header_val);
        }

        $request->send_request(true);
        if($request->get_response_code() != '200'){
            print '[GETCONFIG] got invalid http response: ('.$acm_host.'.';
        }
        $rawData = $request->get_response_body();
        return $rawData;
    }

    public function publishConfig($dataId, $group, $content){
        if(!is_string($this->secretKey) ||
            !is_string($this->accessKey)){
            throw new Aliyun_ACM_Exception ( 'Invalid auth string', "invalid auth info for dataId: $dataId" );
        }

        Aliyun_ACM_Util::checkDataId($dataId);
        $group = Aliyun_ACM_Util::checkGroup($group);

        $servers = $this->serverList;
        $singleServer = $servers[array_rand($servers)];

        $acm_host = str_replace(array('host','port'), array($singleServer->url, $singleServer->port),
            'http://host:port/diamond-server/basestone.do?method=syncUpdateAll');

        $acm_body = "dataId=".urlencode($dataId)."&group=".urlencode($group)
                ."&tenant=".urlencode($this->nameSpace)
                ."&content=".urlencode($content);
        if(is_string($this->appName)){
            $acm_body .= "&appName=".$this->appName;
        }

        $request = new RequestCore();
        $request->set_body($acm_body);
        $request->set_request_url($acm_host);

        $headers = $this->getCommonHeaders($group);

        foreach ($headers as $header_key => $header_val) {
            $request->add_header($header_key, $header_val);
        }
        $request->set_method("post");
        $request->send_request(true);
        if($request->get_response_code() != '200'){
            print '[PUBLISHCONFIG] got invalid http response: ('.$acm_host.'#'.$request->get_response_code();
        }
        $rawData = $request->get_response_body();
        return $rawData;
    }

    public function removeConfig($dataId, $group){
        if(!is_string($this->secretKey) ||
            !is_string($this->accessKey)){
            throw new Aliyun_ACM_Exception ( 'Invalid auth string', "invalid auth info for dataId: $dataId" );
        }

        Aliyun_ACM_Util::checkDataId($dataId);
        $group = Aliyun_ACM_Util::checkGroup($group);

        $servers = $this->serverList;
        $singleServer = $servers[array_rand($servers)];

        $acm_host = str_replace(array('host','port'), array($singleServer->url, $singleServer->port),
            'http://host:port/diamond-server//datum.do?method=deleteAllDatums');

        $acm_body = "dataId=".urlencode($dataId)."&group=".urlencode($group)
            ."&tenant=".urlencode($this->nameSpace);

        $request = new RequestCore();
        $request->set_body($acm_body);
        $request->set_request_url($acm_host);

        $headers = $this->getCommonHeaders($group);

        foreach ($headers as $header_key => $header_val) {
            $request->add_header($header_key, $header_val);
        }
        $request->set_method("post");
        $request->send_request(true);
        if($request->get_response_code() != '200'){
            print '[REMOVECONFIG] got invalid http response: ('.$acm_host.'#'.$request->get_response_code();
        }
        $rawData = $request->get_response_body();
        return $rawData;
    }

    private function getCommonHeaders($group){
        $headers = array();
        $headers['Diamond-Client-AppName'] = 'ACM-SDK-PHP';
        $headers['Client-Version'] = '0.0.1';
        $headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
        $headers['exConfigInfo'] =  'true';
        $headers['Spas-AccessKey'] = $this->accessKey;

        $ts = round(microtime(true) * 1000);
        $headers['timeStamp'] = $ts;

        $signStr = $this->nameSpace.'+';
        if(is_string($group)){
            $signStr .= $group . "+";
        }
        $signStr = $signStr.$ts;

        var_dump($signStr);
        $headers['Spas-Signature'] = base64_encode(hash_hmac('sha1', $signStr, $this->secretKey,true));
        var_dump($headers);
        return $headers;
    }

}