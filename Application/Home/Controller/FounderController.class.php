<?php
namespace Home\Controller;

use Think\Controller;

class FounderController extends Controller
{

    //首页
    public function index()
    {
        $uid = session('user.id');
        getMsgStatu($uid);

        $post = I('post.');
        $order = "user.last_login_time desc" ; //排序方式

        if (!empty($post['search'])) {  //搜索

            $search                               = '%' . $post['search'] . "%";  //搜索条件1
            $where['user.username|founder.intro'] = array ('like', $search);        //搜索条件2

            $count      =  M()->table('user user,user_founder founder')->where("user.id = founder.uid")->where($where)->count();// 查询满足要求的总记录数
            $page       = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
            $show       = $page->show();// 分页显示输出
            $data                                 = M()->table('user user,user_founder founder')
                                                       ->where("user.id = founder.uid")
                                                       ->where($where)
                                                       ->limit($page->firstRow.','.$page->listRows)
                                                       ->order($order)
                                                       ->select();


            for ($i = 0; $i != count($data); $i++) {  //改变搜索结果颜色
                $data[$i]['intro'] = str_replace($post['search'], "<span style='color:red'>{$post['search']}</span>", $data[$i]['intro']);
            }
            //dump($data);


        } elseif (!empty($post['type'])) {  //筛选
            $where = array ();
            if ($post['type'] !== "不限") {
                $where['founder.type'] = $post['type'];
            }
            if ($post['province'] !== "不限") {
                $where['user.province'] = $post['province'];
            }
            if ($post['city'] !== "不限") {
                $where['user.city'] = $post['city'];
            }
            if ($post['agegroup'] !== "不限") {
                $date = ageGroupReverse($post['agegroup']);
                $where['birthday_time'] = array("between","{$date['start']},{$date['end']}");
            }

            $count =M()->table('user user,user_founder founder')->where("user.id = founder.uid")->where($where)->count();
            $page       = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
            $show       = $page->show();// 分页显示输出
            $data = M()->table('user user,user_founder founder')
                        ->where("user.id = founder.uid")
                        ->where($where)
                        ->order($order)
                        ->limit($page->firstRow.','.$page->listRows)
                        ->select();
            //dump($data);

        } else {         //全部

            $count =M()->table('user user,user_founder founder')->where("user.id = founder.uid")->order($order)->count();
            $page       = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
            $show       = $page->show();// 分页显示输出
            $data = M()->table('user user,user_founder founder')
                    ->where("user.id = founder.uid")
                    ->order($order)
                    ->limit($page->firstRow.','.$page->listRows)
                    ->select();
            //dump($data);
        }


        for ($i = 0; $i != count($data); $i++) {
            $data[$i]['agegroup']        = ageGroup($data[$i]['birthday_time']); //年龄转换
            $data[$i]['reg_time']        = regTime($data[$i]['reg_time']);    //注册时间转换
            $data[$i]['last_login_time'] = loginTime($data[$i]['last_login_time']);   //登录时间转换
            if (strlen($data[$i]['username']) >= 12) {   // 用户名判断截取
                $data[$i]['username'] = substr($data[$i]['username'], 0, 10) . '..';
            }
        }

        $this->assign('founder', $data);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }



    public function detail()
    {
        $get =I('get.');
        //用户数据
        $where['user.id'] = $get['id'];
        $data = M() ->table('user user,user_founder founder')->where("user.id = founder.uid")->where($where)->find();
        //关注状态
        $followStatu = D('follow_man')->where(array('uid'=>session('user.id'),'followid'=>$get['id']))->find();
        if($followStatu){
            $data['follow'] ="取消关注";
        }else{
            $data['follow'] ="关注";
        }
        $this->assign('user',$data);

        //用户动态
        $dataTopic  = M('topic')->field('id,content')->where(array('uid'=>$get['id']))->limit("0,5")->select();
        $this->assign("topic",$dataTopic);
        //用户博客
        $dataBlog  = M('blog')->field('id,title')->where(array('uid'=>$get['id']))->limit("0,5")->select();
        $this->assign("blog",$dataBlog);

        //用户项目
        unset($where,$data);
        $where['uid'] = $get['id'];
        $data = M('project')->where($where)->select();
        $this->assign('projects', $data);
        $this->display();
    }

}