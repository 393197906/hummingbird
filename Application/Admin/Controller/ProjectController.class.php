<?php
namespace Admin\Controller;
use Think\Controller;
class ProjectController extends Controller {

    public function _initialize(){

        $session = session("admin");
        if(empty($session)){
            $this->redirect("Login/index");
        }
    }

    public function index()
    {   
        $url = "projectList";
        $this->assign("url",$url);
        $this->assign("left","Project/project-left");
        $this->display("Index/index"); 
    }


    public function projectList(){
        $project = D("project");
        $con = $project->count();
        $page = PAGEADMIN($con);   
        $show = $page->show();// 分页显示输出

        $data =$project->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign("title","project");       
        $this->assign("tag","项目 <small>Project</small>");//标题头
        $this->assign("icon","lamp"); //图标
        $this->assign('project', $data);   //数据
        $this->assign("page",$show); //分页
        $this->display();
    }

}