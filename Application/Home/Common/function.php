<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/7
 * Time: 16:25
 */
function judge(){
    $var='1';
    if(!$_SESSION) {
        redirect(U('Login/login'));
        $var='0';
    }else{
        return $var;
    }
}

//���ݱ����û�������Ϣ
function user($id){
    $User=D('user');
    if($id!=null){
return $User->where('id='.$id)->find();
    }else{
        return $User->where('id='.$_SESSION['user']['id'])->find();
    }
}
function user_founder(){
    $User=D('user_founder');
    return $User->where('id='.$_SESSION['user']['uid'])->select();

}
function project($id){
    $User=D('project');
    if($id!=null){
        return  $User->where('id='.$id)->find();
    }else{
        return  $User->where('uid='.$_SESSION['user']['id'])->find();
    }
}
