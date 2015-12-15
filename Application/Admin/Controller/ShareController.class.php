<?php
namespace Admin\Controller;
use Think\Controller;
class ShareController extends Controller {

    public function _initialize(){

        $session = session("admin");
        if(empty($session)){
            $this->redirect("Login/index");
        }
    }

    public function index()
    {   
        $url = "topicList";
        $this->assign("url",$url);
        $this->assign("left","Share/share-left");
        $this->display("Index/index"); 
    }


    public function topicList(){
        $this->getTopics();
        $this->assign("title","Topic");       
        $this->assign("tag","话题 <small>Topic</small>");//标题头
        $this->assign("icon","bubbles"); //图标
        $this->display();
    }

    public function blogList(){
        $this->getBlogs();
        $this->assign("title","Blog");       
        $this->assign("tag","博客 <small>Blog</small>");//标题头
        $this->assign("icon","bold"); //图标
        $this->display();
    }


     public function getTopics()
    {

        $count      =  M("")->table('topic t,user u')
                            ->field('t.id,t.uid,t.content,t.type,t.view,t.rise,t.posttime,t.statu,u.username,u.headpic')
                            ->where('t.uid=u.id')->order('t.posttime desc')->count();// 查询满足要求的总记录数
        $page       = PAGEADMIN($count);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $page->show();// 分页显示输出
        $data =
            M("")->table('topic t,user u')
                 ->field('t.id,t.uid,t.content,t.type,t.view,t.rise,t.posttime,t.statu,u.username,u.headpic')
                 ->where('t.uid=u.id')->order('t.posttime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('topics', $data);
        $this->assign("page",$show);

    }

    public function getBlogs()
    {
        $count      = M("")->table('blog b,user u')
                           ->field('b.id,b.uid,b.content,b.type,b.view,b.rise,b.posttime,b.statu,b.tag,b.title,u.username,u.headpic')
                           ->where('b.uid=u.id')->order('b.posttime desc')->count();// 查询满足要求的总记录数
        $page       = PAGEADMIN($count);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $page->show();// 分页显示输出
        $data =
            M("")->table('blog b,user u')
                 ->field('b.id,b.uid,b.content,b.type,b.view,b.rise,b.posttime,b.statu,b.tag,b.title,u.username,u.headpic')
                 ->where('b.uid=u.id')->order('b.posttime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('blogs', $data);
        $this->assign("page",$show);
    }

}