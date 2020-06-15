<?php


namespace app\lib\exception;


use think\Exception;

class SqlException extends Exception
{
    public $code = 401;
    public $msg = '数据库错误';
    public function __construct($msg="",$code=401){
        $this->msg=$msg;
        $this->code=$code;
    }
}