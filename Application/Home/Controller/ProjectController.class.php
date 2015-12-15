<?php
namespace Home\Controller;

use Think\Controller;

class ProjectController extends Controller
{
    public function index()
    {
        $uid = session('user.id');
        getMsgStatu($uid);
        $post = I('post.');
        //dump($post);
        $order = "updatetime desc" ; //排序方式
        $project = D('project');

        if (!empty($post['search'])) {  //搜索

            $search               = '%' . $post['search'] . "%";  //搜索条件1
            $where['pname|intro'] = array ('like', $search);        //搜索条件2
            $count      =$project->where($where)->order($order)->count();
            $page = PAGE($count);
            $show = $page->show();
            $data                 = $project->where($where)->order($order)->limit($page->firstRow.','.$page->listRows)->select();

            for ($i = 0; $i != count($data); $i++) {  //改变搜索结果颜色
                $data[$i]['intro'] = str_replace($post['search'], "<span style='color:red'>{$post['search']}</span>", $data[$i]['intro']);
            }
            //dump($data);

       // }  elseif (!empty($post['ptype'])) {  //筛选
          }  elseif (!empty($post['stage'])) {  //筛选
            $where = array ();
            if ($post['ptype'] !== "热门"&&(!empty($post['ptype']))) {
                $where['ptype'] = $post['ptype'];
            }
            if ($post['province'] !== "不限") {
                $where['location'] = $post['province'].' '.$post['city'];
            }
            if ($post['stage'] !== "不限") {
                $where['stage'] = $post['stage'];
            }

            $count      = $project->where($where)->order($order)->count();
            $page = PAGE($count);
            $show = $page->show();
            $data = $project->where($where)->order($order)->limit($page->firstRow.','.$page->listRows)->select();
            //dump($data);

        } else {         //全部

            $count      =  $project->order($order)->count();
            $page = PAGE($count);
            $show = $page->show();
            $data    = $project->order($order)->limit($page->firstRow.','.$page->listRows)->select();
            //dump($data);
        }

        for ($i = 0; $i != count($data); $i++) {  //添加年龄段
            $data[$i]['updatetime'] = $this->createTime(strtotime($data[$i]['updatetime']));   //创建日期转换
        }
        $this->assign('project', $data);
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }
    // 换算项目创建日期
    protected function createTime($date)
    {
        $timeday = 24 * 60 * 60;
        $str     = '';

        if ($date > strtotime(date("Y:m:d") . " 00:00:00")) {
            $str = "今天创建";
        } elseif ($date > (strtotime(date("Y:m:d") . " 00:00:00") - $timeday)) {
            $str = "昨天创建";
        } elseif ($date > (strtotime(date("Y:m:d") . " 00:00:00") - 2 * $timeday)) {
            $str = "三天内创建";
        } elseif ($date > (strtotime(date("Y:m:d") . " 00:00:00") - 6 * $timeday)) {
            $str = "一周内创建";
        } elseif ($date > (strtotime(date("Y:m:d") . " 00:00:00") - 29 * $timeday)) {
            $str = "一月内创建";
        } elseif ($date > (strtotime(date("Y:m:d") . " 00:00:00") - 89 * $timeday)) {
            $str = "三月内创建";
        } elseif ($date > (strtotime(date("Y:m:d") . " 00:00:00") - 364 * $timeday)) {
            $str = "一年内创建";
        } else {
            $str = "创建日期超过一年";
        }


        return $str;
    }
    //项目详情页
    public function detail()
    {

        $get =I('get.');
        $where['id'] = $get['id'];


        $project = M('project');
        $data=$project->where($where)->find();
        $followStatu = D('follow_project')->where(array('uid'=>session('user.id'),'projectid'=>$get['id']))->find();
        if($followStatu){
            $data['follow'] ="取消关注";
        }else{
            $data['follow'] ="关注";
        }
        // 项目成员
        $user= D('user');
        $userData =$user->where(array("id"=>$data['uid']))->select();

        //相似项目
        $likeData = $project ->field("pname,id,logo")->where(array("type"=>$data['type'],"id"=>array('neq',$data['id'])))->select();
        $this->assign('member',$userData); //项目成员数据
        $this->assign('project',$data);   //项目数据
        $this->assign('likeprojects',$likeData); //项目成员数据
        $this->display();
    }

}