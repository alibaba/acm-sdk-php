<?php
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */

require_once realpath(dirname(__FILE__) . '/../Aliyun/ACM/Autoload.php');;

$client = new Aliyun_ACM_Client('acm.aliyun.com','8080');
$resp = $client->getServerList();
var_dump($resp);