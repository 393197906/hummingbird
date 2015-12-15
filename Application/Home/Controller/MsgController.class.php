<?php
namespace Home\Controller;

use Think\Controller;

class MsgController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
        //select msg number
        $where['senderid']=0;
        $where['receiverid'] = $this->user['id'];
        $where['statu']=1;
        $where['receiverdel']=0;
        $sysnumber = M('message')->where($where)->count();

        unset($where['senderid']);
        $where['senderid'] = array ('neq', 0);
        $usernumber = M('message')->where($where)->count();

        session('msg.sysnumber', $sysnumber);
        session('msg.usernumber', $usernumber);

    }

    public function index()
    {
        $frag = I('get.frag', 'index');
        $frag = 'frag' . $frag;
        $this->assign('frag', $frag);

        switch ($frag) {
            case 'fragsystemmsg':
                $data = D('Message')->getSystemMsg();
                $this->assign('systemmsg', $data);
                break;
        }
        $this->display();
    }


    public function mymsg()
    {
        $frag = I('get.frag', 'mymsg');
        $frag = 'frag' . $frag;
        $this->assign('frag', $frag);

        if ($frag == 'fragnewmsg') {
            $where['receiverid']  = $this->user['id'];
            $where['senderid']    = array ('neq', 0);
            $where['receiverdel'] = 0;
            $data                 = M('Message')->where($where)->order('statu desc,sendtime desc')->select();
            if (!empty($data)) {
                $data['content'] = html_entity_decode($data['content']);
            }
            $this->assign('newmsg', $data);
        }else if ($frag == 'fragsendedmsg') {
            $where['senderid']    = session('user.id');
            $where['senderdel'] = 0;
            $data                 = M('Message')->where($where)->select();
            if (!empty($data)) {
                $data['content'] = html_entity_decode($data['content']);
            }
            $this->assign('sendedmsg', $data);
        }

        $this->display();
    }

    //发送消息
    public function doSendMsg()
    {
        $add             = I('post.');
        $add['senderid'] = session('user.id');
        if ($add['senderid'] == $add['receiverid']) {
            $this->error("不能发送消息给自己");
        }
        $add['fromname'] = session('user.username');
        $add['headpic']  = session('user.headpic');
        $add['sendtime'] = date("Y-m-d H:i:s");
        $add['statu']    = 1;
        $msg             = D('message');
        if ($msg->create($add)) {
            if ($msg->add($add)) {
                $this->success("发送消息成功");
            } else {
                $this->error("发送消息失败");
            }
        } else {
            $this->error($msg->getError());
        }
    }

    //设置已读
    public function setMsgReaded()
    {
        $id            = I('post.id');
        $save['id']    = $id;
        $save['statu'] = 0;
        if (M('message')->save($save)) {
            $this->ajaxReturn("success");
        } else {
            $this->ajaxReturn("failed");
        }
    }


    //删除消息
    public function delMsg()
    {
        $id = I('post.id');
        $re=M('message')->where("id=$id")->delete();
        if ($re) {
            $this->ajaxReturn("success");
        }else{
            $this->ajaxReturn("failed");
        }
    }

    //收信人删除消息
    public function delMsgByReceiver()
    {
        $id                  = I('post.id');
        $save['id']          = $id;
        $save['receiverdel'] = 1;
        if (M('message')->save($save)) {
            $this->ajaxReturn("success");
        } else {
            $this->ajaxReturn("failed");
        }
    }

    //发信人删除消息
    public function delMsgBySender()
    {
        $id                  = I('post.id');
        $save['id']          = $id;
        $save['senderdel'] = 1;
        if (M('message')->save($save)) {
            $this->ajaxReturn("success");
        } else {
            $this->ajaxReturn("failed");
        }
    }

}
