<?php


namespace app\lib\exception;


class ErrorException extends BaseException
{
    public $code = 401;
    public $msg = 'fail';
    public $status = 'error';
    public $data=[];
    public $success = false;



    public function __construct($msg="",$code=401){
        $this->msg=$msg;
        $this->code=$code;
    }

}