<?php
/**
 * Class Aliyun_ACM_Client
 * The basic client to manage ACM
 */

/**
 * Class Aliyun_ACM_Util
 * the utility class
 */
class Aliyun_ACM_Util{

    const VALID_STR = array('_','-', '.', ':');

    public static function isIpv4($ipAddress){
        return is_numeric(ip2long($ipAddress));
    }

    public static function readFromFile(){

    }

    public static function saveToFile(){

    }

    public static function isValid($input){
        if(is_string($input)){
            for($i=0;$i<strlen($input);++$i){
                $s = $input[$i];
                if(is_numeric($s) ||
                    (!ctype_alpha($s) && !in_array($input[$i], self::VALID_STR))){
                    return '0';
                }
            }
        }
        return '1';
    }

    public static function checkDataId($dataId){
        if(!is_string($dataId)){
            throw new Aliyun_ACM_Exception ( 'Invalid dataId input', "invalid dataId: $dataId" );
        }
    }

    public static function checkGroup($group){
        if(!is_string($group)){
            return 'DEFAULT_GROUP';
        }else{
            return $group;
        }
    }
}