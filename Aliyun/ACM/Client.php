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

    public function getServerList(){
        $server_host = str_replace(array('host','port'), array($this->endPoint, $this->port),
            'http://host:port/diamond-server/diamond');
        $request = new RequestCore();
        $request->set_request_url($server_host);
        $request->send_request(true);
        if($request->get_response_code() != '200'){
            print '[getServerList] got invalid http response: ('.$server_host.'.';
        }
        $serverRawList = $request->get_response_body();
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
        return $request->get_response_body();
    }


}