<?php
namespace Api\Controller;
/**
 * Created by PhpStorm.
 * User: NilTor
 * Date: 2015/9/18
 * Time: 16:02
 */
use Think\Controller;
class TestController extends Controller
{

    public function sendMsg()
    {

        $phone = I('post.phone');
        $action = I('post.action');
        $code = rand(1000, 9999);
        $re=sendMsg($phone, $code, $action);
        if ($re['result']=='SUCCESS') {
            $this->ajaxReturn($code);
        }else{
            $this->ajaxReturn($re);
        }

    }
}