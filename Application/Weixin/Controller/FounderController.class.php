<?php
/**
 * Created by PhpStorm.
 * User: tongdragon
 * Date: 2015/9/16
 * Time: 14:28
 */
namespace Weixin\Controller;

use Think\Controller;

class FounderController extends Controller
{

    public function index()
    {
        $this->display();
    }


    public function getData()
    {
        $data =
            M('')->table('user u,user_founder f')
                 ->field('u.id,u.username,u.headpic,u.province,u.sex,u.city,f.type,f.intro,u.birthday')
                 ->where("u.id=f.uid")->limit(10)->select();
        $this->ajaxReturn($data);
    }

    public function detail()
    {
        $get =I('get.');
        //用户数据
        $where['user.id'] = $get['id'];
        $data = M() ->table('user user,user_founder founder')->where("user.id = founder.uid")->where($where)->find();
        $re['user']=$data;
        //用户动态
        $dataTopic  = M('topic')->field('id,content')->where(array('uid'=>$get['id']))->limit("0,5")->select();
        $re['usertopic']=$dataTopic;

        //用户博客
        $dataBlog  = M('blog')->field('id,title')->where(array('uid'=>$get['id']))->limit("0,5")->select();
        $re['userblog'] = $dataBlog;

        //用户项目
        unset($where,$data);
        $where['uid'] = $get['id'];
        $data = M('project')->where($where)->select();
        $re['userproject']=$data;
        $re = json_encode($re);
        $this->assign('data', $re);
        $this->display();
    }
}
