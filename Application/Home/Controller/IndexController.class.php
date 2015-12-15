<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{


    //首页面
    public function index()
    {
        $this->display();
    }

    //登录界面
    public function login()
    {
        $this->display();
    }

    //登录验证
    public function doLogin()
    {
        $data['username|email'] = I('post.username');
        $data['password']       = md5(I('post.password'));
        $data['_logic']         = 'and';

        $user  = M('user');
        $statu = $user->where($data)->find();
        if ($statu) {
            $loginTime['last_login_time'] = time();
            if (!$user->where($data)->save($loginTime)) {  //更新登录时间
                $this->error("系统错误！");
            }
            session('user', $statu);
            session('user.islogin', 1);
            session('reg', NULL);
            if (empty($statu['realname']) || $statu['identity'] == "") {
                session('reg.step', 1);
                $this->success("登录成功，请先补全信息", U('Reg/reg'));
            } else {
                if (empty($statu['headpic'])) {
                    session('reg.step', 2);
                    $this->success("登录成功，请先选择偏好信息", U('Reg/reg'));

                } else {
                    $this->success('登录成功', U('Founder/index'), 2);
                }
            }
        } else {
            $this->error("登录失败，请检查您的账号和密码", 'login', 2);
        }
    }

    //退出登录
    public function loginOut()
    {
        session('user', NULL);
        $this->redirect('index');
    }

}