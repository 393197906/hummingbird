<?php

namespace Home\Model;

use Think\Model;

class UserInvestorModel extends Model
{

    protected $_validate = array (array ('realname', 'require', '需要填写用户名！'), // 用户名不能为空
                                  array ('phone', 'require', '需要填写联系方式！'), // 联系方式不能为空
                                  //array ('institutionname', 'require', '需要填写机构名称！'), // 机构信息不能为空
                                  array ('intro', 'require', '需要填写投资经历！'), // 投资方式不能为空
                                  array ('wantproject', 'require', ' 需要填写关注的项目类型！'), // 关注的项目类型不能为空 step3

                                  array ('phone', '/^1\d{10}$|^(0\d{2,3}-?|\(0\d{2,3}\))?[1-9]\d{4,7}(-\d{1,8})?$/', '联系方式格式不正确！'), // 联系方式格式
                                  array ('institutionname', '0,30', '机构名称超出指定长度！',0,'length'), // 机构信息长度限制
                                  array ('wantproject', '0,50', ' 关注的项目超出指定长度！',0,'length'), // 关注的项目类型长度限制 step3
                                  array('intro', 'checklength', '投资经历不能少于50个字且不能大于300个字！', 0, 'function'),

    );

    public function getData($uid){
        $where['uid'] = $uid;
        $date = $this->where($where)->find();
        return $date;
    }


}


?>