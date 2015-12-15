<?php
namespace Weixin\Controller;
use Think\Controller;

class BlogController extends BaseController
{

    public function index()
    {
        # code...
        $this->display();
    }

    public function getData()
    {
        $data = M('')->table('blog b,user u')->field('u.headpic,u.username,b.id,b.uid,b.title,b.type,b.tag,b.view,b.rise,UNIX_TIMESTAMP(b.posttime) posttime,b.statu')->where("b.uid=u.id")->limit(50)->select();
        $this->ajaxReturn($data);
    }
}
