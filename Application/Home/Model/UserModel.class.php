<?php

namespace Home\Model;

use Think\Model;

class UserModel extends Model
{
    protected $_validate = array (array ('username', 'require', '需要填写用户名！'), // 用户名必须
                                  array ('username', '', '帐号名称已经存在！', 1, 'unique', 1), // 验证用户名是否已经存在
                                  array ('username', '6,30', ' 用户名长度不正确', 0, 'length'), // 验证密码是否在指定长度范围
                                  array ('email', '', '该邮箱已经注册！', 1, 'unique', 1), // 验证邮箱是否已经存在
                                  //array ('phone', '', '该手机号码已经注册！', 1, 'unique', 1), // 验证手机号码是否已经存在
                                  array ('email', 'email', 'Email格式错误！', 2), // 如果输入则验证Email格式是否正确
                                  array ('password', '6,30', '密码长度不正确', 0, 'length'), // 验证密码是否在指定长度范围
                                  array ('province', '不限制', '请正确选择省市', 0, 'notequal'), // 验证密码是否在指定长度范围
                                  array ('city', '不限制', '请正确选择省市', 0, 'notequal'), // 验证密码是否在指定长度范围
                                  array ('check', 'require', '请确认你已做好准备', 1), // 勾选必须
                                  //array ('repassword', 'password', '确认密码不一致', 0, 'confirm'), // 验证确认密码是否和密码一致

    );

    public function getData($id)
    {
        $where['id'] = $id;
        $date        = $this->where($where)->find();
        return $date;
    }

    //头像图片上传
    public function upload($file,$filepath){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Uploads/'.$filepath.'/'; // 设置附件上传根目录
        // 上传单个文件
        $info   =   $upload->uploadOne($file);
        //dump($upload->getError());  //错误信息
        return $info;
    }
}


?>