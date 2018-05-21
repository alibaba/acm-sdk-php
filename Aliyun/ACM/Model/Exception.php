<?php
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */


class Aliyun_ACM_Exception extends Exception
{
    /**
     * @var string
     */
    private $requestId;

    /**
     * Aliyun_ACM_Exception constructor
     *
     * @param string $code
     *            log service error code.
     * @param string $message
     *            detailed information for the exception.
     * @param string $requestId
     *            the request id of the response, '' is set if client error.
     */
    public function __construct($code, $message, $requestId = '')
    {
        parent::__construct($message);
        $this->code = $code;
        $this->message = $message;
        $this->requestId = $requestId;
    }

    /**
     * The __toString() method allows a class to decide how it will react when
     * it is treated like a string.
     *
     * @return string
     */
    public function __toString() {
        return "Aliyun_ACM_Exception: \n{\n    ErrorCode: $this->code,\n    ErrorMessage: $this->message\n    RequestId: $this->requestId\n}\n";
    }

    /**
     * Get Aliyun_ACM_Exception error code.
     *
     * @return string
     */
    public function getErrorCode() {
        return $this->code;
    }

    /**
     * Get Aliyun_ACM_Exception error message.
     *
     * @return string
     */
    public function getErrorMessage() {
        return $this->message;
    }

    /**
     * Get log service sever requestid, '' is set if client or Http error.
     *
     * @return string
     */
    public function getRequestId() {
        return $this->requestId;
    }
}