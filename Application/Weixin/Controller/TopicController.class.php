<?php
namespace Weixin\Controller;
use Think\Controller;

class TopicController extends BaseController
{

    public function index()
    {
        # code...
        $this->display();
    }

    public function detail(){
        $id=I('get.id');

        $topic=M('')->table('topic t,user u,user_founder f')->field('u.headpic,u.username,f.type ftype,t.id,t.uid,t.content,t.type,t.view,t.rise,UNIX_TIMESTAMP(t.posttime) posttime,t.statu')->where("t.id=$id AND t.uid=u.id AND f.uid=u.id")->find();
        unset($where);
        $where['tid']=I('get.id');
        $comments=M('')->table('topic_comment t,user u')->field('t.id,t.uid,t.tid,t.content,t.commenttime,t.rise,u.username,u.headpic')->where($where)->where("t.uid=u.id")->select();
        $data['topic']=$topic;
        $data['comments']=$comments;
        $data=json_encode($data);
        $this->assign('data',$data);
        $this->display();
    }

    public function getData()
    {
        $data = M('')->table('topic t,user u')->field('u.headpic,u.username,t.id,t.uid,t.content,t.type,t.view,t.rise,UNIX_TIMESTAMP(t.posttime) posttime,t.statu')->where("t.uid=u.id")->limit(50)->select();
        $this->ajaxReturn($data);
    }


    public function getTopic()
    {
        # code...

    }

}
