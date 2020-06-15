<?php


namespace app\lib\exception;


use think\Exception;

class SuccessMessage extends BaseException
{
    public $code = 0;
    public $msg = 'success';
    public $data=[];


    public function __construct($msg="",$code=0){
        $this->msg=$msg;
        $this->code=$code;
    }
}