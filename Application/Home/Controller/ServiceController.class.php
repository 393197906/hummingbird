<?php
namespace Home\Controller;
use Think\Controller;
class ServiceController extends Controller {
    public function index()
    {
        $uid = session('user.id');
        getMsgStatu($uid);

        $pid = I('get.id');
        $article = D('article');
        $data = $article->getDataPid($pid);                        //全部
        $RecommendData = $article->getDataPidRecommend($pid,4); //推荐文章
        $hotData =$article->getDataPidHot($pid,4);       //热点文章
        $theme = M('subject')->where(array('id'=>$pid))->find();  //模板
        if($pid="19"){
            $data1=$article->field("id,title")->where(array("pid"=>$pid,"classname"=>"创业政策"))->select();
            $data2=$article->field("id,title")->where(array("pid"=>$pid,"classname"=>"法务信息"))->select();

            $this->assign("cyzc",$data1);
            $this->assign("fwxx",$data2);
        }
        $this->assign("recommendarticle",$RecommendData);
        $this->assign("hotarticle",$hotData);
        $this->assign("theme",$theme['theme']);
        $this->assign("article",$data);
        $this->display();
    }

    public function article(){
        $id = I('get.id');
        $article = D('article');
        $data = $article->getDataId($id);  //全部数据
        //dump($data);
        $hotData =$article->getDataPidHot($data['pid'],4);       //热点文章
        $this->assign("article",$data);
        $this->assign("hotarticle",$hotData);
        D("article")->where(array("id"=>$data['id']))->setInc('readnum');  //阅读量自加
        $this->display();

    }



}