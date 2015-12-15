<?php

namespace Home\Model;

use Think\Model;
class MessageModel extends Model
{
    protected $_validate = array (

        array ('receiverid', 'require', '你未选择收信人！'),
        array ('content', 'require', '请填写消息内容！'),
        array ('theme', 'require', '请填写消息主题！'),
    );

    public function getData($id)
    {
        $where['id'] = $id;
        $data        = $this->where($where)->find();
        return $data;
    }

    //获取通知消息
    public function getSystemMsg()
    {
        $where['senderid']=0;
        $where['receiverid'] = session('user.id');
        $data = $this->where($where)->order('statu desc,sendtime desc')->select();
        return $data;
    }


    //添加系统消息
    public function addSystemMsg()
    {
        $add             = I('post.');
        $add['senderid'] =0;
        $add['fromname'] = "系统消息";
        $add['sendtime'] = date("Y-m-d H:i:s");
        $add['statu']    = 1;
        return $this->add($add);
    }

}


?>