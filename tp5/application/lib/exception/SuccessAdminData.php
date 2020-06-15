<?php


namespace app\lib\exception;


use think\Exception;

class SuccessAdminData extends BaseException
{
    public $code = 0;
    public $msg = "success";
    public $data=[];


    public function __construct($data,$msg ="success",$code= 0){
        $this->data=$data;
        $this->msg=$msg;
        $this->code=$code;
    }
}