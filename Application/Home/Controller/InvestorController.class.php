<?php
namespace Home\Controller;

use Think\Controller;

class InvestorController extends Controller
{
    public function index()
    {
        $uid = session('user.id');
        getMsgStatu($uid);


        $post = I('post.');
        // dump($post);
        $order = "user.last_login_time desc"; //排序方式

        if (!empty($post['search'])) {  //搜索

            $search                                = '%' . $post['search'] . "%";  //搜索条件1
            $where['user.username|investor.intro'] = array ('like', $search);        //搜索条件2
            $count                                 =
                M()->table('user user,user_investor investor')->where("user.id = investor.uid")->where($where)
                   ->order($order)->count();// 查询满足要求的总记录数
            $page                                  = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
            $show                                  = $page->show();// 分页显示输出
            $data                                  =
                M()->table('user user,user_investor investor')->where("user.id = investor.uid")->where($where)
                   ->order($order)->limit($page->firstRow . ',' . $page->listRows)->select();

            for ($i = 0; $i != count($data); $i++) {  //改变搜索结果颜色
                $data[ $i ]['intro'] =
                    str_replace($post['search'], "<span style='color:red'>{$post['search']}</span>",
                                $data[ $i ]['intro']);
            }
            //dump($data);


        } elseif (!empty($post['scale'])) {  //筛选
            $where = array ();
            if ($post['scale'] !== "不限") {
                $where['investor.type'] = $post['scale'];
            }
            if ($post['province'] !== "不限") {
                $where['user.province'] = $post['province'];
            }
            if ($post['city'] !== "不限") {
                $where['user.city'] = $post['city'];
            }

            $count =
                M()->table('user user,user_investor investor')->where("user.id = investor.uid")->where($where)
                   ->order($order)->count();// 查询满足要求的总记录数
            $page  = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
            $show  = $page->show();// 分页显示输出

            $data =
                M()->table('user user,user_investor investor')->where("user.id = investor.uid")->where($where)
                   ->order($order)->limit($page->firstRow . ',' . $page->listRows)->select();
            //dump($data);

        } else {         //全部
            $count =
                M()->table('user user,user_investor investor')->where("user.id = investor.uid")->order($order)
                   ->count();// 查询满足要求的总记录数
            $page  = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
            $show  = $page->show();// 分页显示输出
            $data  =
                M()->table('user user,user_investor investor')->where("user.id = investor.uid")
                   ->limit($page->firstRow . ',' . $page->listRows)->order($order)->select();
            //dump($data);
        }


        for ($i = 0; $i != count($data); $i++) {
            $data[ $i ]['agegroup']        =
                ageGroup($data[ $i ]['birthday_time']); //年龄转换 调用公共方法
            $data[ $i ]['reg_time']        =
                regTime($data[ $i ]['reg_time']);    //注册时间转换 调用公共方法
            $data[ $i ]['last_login_time'] =
                loginTime($data[ $i ]['last_login_time']);   //登录时间转换 调用公共方法
            if (strlen($data[ $i ]['username']) >= 12) {   // 用户名判断截取
                $data[ $i ]['username'] = substr($data[ $i ]['username'], 0, 10) . '..';
            }
        }

        $this->assign('investor', $data);
        $this->assign('page', $show);
        $this->display();
    }

    public function detail()
    {
        $get              = I('get.');
        $where['user.id'] = $get['id'];

        $data        =
            M()->table('user user,user_investor investor')->where("user.id = investor.uid")->where($where)->find();
        $followStatu = D('follow_man')->where(array ('uid' => session('user.id'), 'followid' => $get['id']))->find();
        if ($followStatu) {
            $data['follow'] = "取消关注";
        } else {
            $data['follow'] = "关注";
        }
        $this->assign('user', $data);

        //用户动态
        $dataTopic = M('topic')->field('id,content')->where(array ('uid' => $get['id']))->limit("0,5")->select();
        $this->assign("topic", $dataTopic);
        //用户博客
        $dataBlog = M('blog')->field('id,title')->where(array ('uid' => $get['id']))->limit("0,5")->select();
        $this->assign("blog", $dataBlog);

        //投资人关注的项目
        unset($where);
        $where['follow.uid'] = $get['id'];
        $dataFollowProject   =
            M()->table("follow_project follow,project project")->field("project.id,project.logo,project.pname")
               ->where("follow.projectid=project.id")->where($where)->select();
        $this->assign("projects", $dataFollowProject);

        $this->display();


    }


}