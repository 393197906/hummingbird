<?php

namespace Home\Model;

use Think\Model;

class UserFounderModel extends Model
{
    protected $_validate = array (array ('realname', 'require', '需要填写用户名！'), // 用户名不能为空
                                  array ('phone', 'require', '需要填写联系方式！'), // 联系方式不能为空
                                  array ('profession', 'require', '需要填写专业能力与水准！'), // 专业能力与水准不能为空
                                  array ('skill', 'require', '需要填写特长！'), // 特长不能为空
                                  array ('school', 'require', '需要填写毕业院校！'), // 毕业院校不能为空
                                  array ('advantage', 'require', '需要填写定位优势！'), //定位优势不能为空！
                                  array ('intro', 'require', '需要填写创业经历！'), // 创业经历不能为空！
                                  array ('hobby', 'require', '需要填写兴趣爱好！'), // 兴趣爱好不能为空！ step3

                                  array ('phone', '/^1\d{10}$|^(0\d{2,3}-?|\(0\d{2,3}\))?[1-9]\d{4,7}(-\d{1,8})?$/', '联系方式格式不正确！'), // 联系方式格式
                                  array ('profession', '0,30', '专业能力与水准超过指定长度！',0,'length'), // 专业能力与水准长度限制
                                  array ('skill', '1,50', '特长超过指定长度！',0,'length'), // 特长长度限制
                                  array ('school', '1,30', '毕业院校超过指定长度！',0,'length'), // 毕业院校长度限制
                                  array ('advantage', '1,30', '定位优势超过指定长度！',0,'length'), //定位优势长度限制！
                                  array ('companyname', '0,30', '公司名称超过指定长度！',0,'length'), // 公司情况优势长度限制！
                                  array ('hobby', '0,50', '关注的项目类型超过指定长！',0,'length'), // 关注的项目类型长度限制！step3
                                  array('intro', 'checklength', '创业经历不能少于50个字且不能大于300个字！', 0, 'function'),  //创业经历长度限制
    );

    public function getData($uid){
        $where['uid'] = $uid;
        $date = $this->where($where)->find();
        return $date;
    }



}


?>