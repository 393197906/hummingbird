<?php
namespace Admin\Controller;

use Think\Controller;

class UserController extends Controller
{
    public function _initialize(){

        $session = session("admin");
        if(empty($session)){
            $this->redirect("Login/index");
        }
    }

    public function index()
    {   
        $url = "founderList";
        $this->assign("url",$url);
        $this->assign("left","User/user-left");
        $this->display("Index/index");
    }

    public function founderList()
    {   
        $con = M()->table('user a,user_founder b')->field('a.id,a.username,a.nickname,b.type,a.email,a.province,a.reg_time')
               ->where('a.id=b.uid')->count();
        $page = PAGEADMIN($con);   
        $show = $page->show();// 分页显示输出

        $data =
            M()->table('user a,user_founder b')
               ->field('a.id,a.username,a.nickname,b.type,a.email,a.province,a.reg_time')
               ->limit($page->firstRow.','.$page->listRows)
               ->where('a.id=b.uid')->select();
        $this->assign("title","Founder");       
        $this->assign("tag","合伙人 <small>Founder</small>");//标题头
        $this->assign("icon","user"); //图标
        $this->assign('user', $data);   //数据
        $this->assign("page",$show); //分页
        $this->display("user");
    }

    public function investorList()
    {
        $con =  M()->table('user a,user_investor b')->field('a.id,a.username,a.nickname,b.type,a.email,a.province,a.reg_time')
               ->where('a.id=b.uid')->count();
        $page = PAGEADMIN($con);   
        $show = $page->show();// 分页显示输出

        $data =
            M()->table('user a,user_investor b')->field('a.id,a.username,a.nickname,b.type,a.email,a.province,a.reg_time')
               ->where('a.id=b.uid')->select();
        $this->assign("title","Investor");    
        $this->assign('user', $data); //数据源
        $this->assign("tag","投资人 <small>Investor</small>"); //标题头
        $this->assign("icon","dollar2"); //图标
        $this->assign("page",$show); //分页
        $this->display('user');
    }

    public function doDelete(){
    }

}