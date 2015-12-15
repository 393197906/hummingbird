<?php
/**
 * Created by PhpStorm.
 * User: geeth
 * Date: 2015/11/29 0029
 * Time: 13:05
 */

namespace Home\Controller;

use Think\Controller;

class RegController extends Controller
{


    private function isStep($step = 0)
    {
        if (session('reg.step') == $step) {
            return TRUE;
        } else {
            $this->redirect('reg');
            return FALSE;
        }

    }

    //注册页面1
    public function reg()
    {
        $reg = session('reg');
        if (!empty($reg)) {
            $url='regstep'.($reg['step']+1);
            $this->redirect($url);
        }
        $this->display();
    }

    //注册页面2
    public function regstep2()
    {
        if ($this->isStep(1)) {
            $this->display();
        }

    }

    //注册页面3
    public function regstep3()
    {
        $this->isStep(2);

        $type = session("reg.info");

        if ($type == 'founder') {
            $page = 'regfounder3';
        } else {
            $page = 'reginvestor3';
        }
        $this->assign("page", $page);
        $this->display();
    }

    //注册step1
    public function doReg()
    {
        $user = D('user');
        if ($data = $user->create()) {
            $data['password'] = md5($data['password']);   //密码md5加密
            $data['reg_time'] = time();
            $uid              = $user->add($data);
            if ($uid) {
                session('reg.uid', $uid);
                session('reg.step', 1);
                $this->redirect('regstep2');
            } else {
                $this->error("注册失败，请返回重新填写相关信息");
            }
        } else {
            $this->error($user->getError());
        }
    }

    //注册step2
    public function  doReg2()
    {
        //TODO
        $data    = I('post.');            //获取表单提交数据
        $captcha = $data['captcha'];
        if ($captcha != session('reg.captcha')) {
            $this->error('手机验证码错误');
        } else {
            unset($data['captcha']);
        }
        $data['uid']           = session('reg.uid');   //获取注册第一步uid
        if (empty($data['uid'])) {
            $data['uid'] = session('user.id');
        }
        $data['birthday']      = $data['year'] . "-" . $data['month'] . "-" . $data['day'];  //获取出生日期
        $data['birthday_time'] = strtotime($data['birthday']);                               //获取出生日期时间戳
        $user                  = D('user');                                                   //实例化user表


        if ($data['info'] == 'founder') {
            $userRole         = D('user_founder');
            $data['identity'] = '1';   //注册人身份


        } elseif ($data['info'] == 'investor') {
            $userRole         = D('user_investor');
            $data['identity'] = '0';    //注册人身份
        }



        if ($userRole->create($data)) {
            $id = $userRole->add($data);  //insert  'user_role'
            if (!$id) {
                $this->error("注册失败请返回重新填写相关信息");
            }
        } else {
            $this->error($userRole->getError());
        }

        $where['id']   = $data['uid']; //主键对应
        $data['check'] = "check";   //验证规则
        if ($user->create($data)) {
            $row = $user->where($where)->save($data);  //update 'user'
            if (!$row) {
                $this->error("注册失败请返回重新填写相关信息");
            }
        } else {
            $this->error($userRole->getError());
        }


        if ($id && $row) {
            session('reg.step', 2);   //step2；
            session('reg.id', $id);   //存入用户身份主键
            session('reg.info', $data['info']); //保存类型
            $this->redirect("regstep3");
        }
    }

    //注册step3
    public function doReg3()
    {
        $data = I('post.');
        // dump ($data);
        if ($data['info'] == 'founder') {
            $userRole = D('user_founder');

        } elseif ($data['info'] == 'investor') {
            $userRole = D('user_investor');
        }

        $upLoadStatu = D('User')->upload($_FILES['headpic'], 'headpic');   //文件上传；
        if (!$upLoadStatu) {
            $this->error("请选择您喜爱的头像");
        } else {
            $uid             = session('reg.uid');
            if (empty($uid)) {
                $uid = session('user.id');
            }
            $save['headpic'] = '/' . $upLoadStatu['savepath'] . $upLoadStatu['savename'];
            $re              = M('user')->where("id=$uid")->save($save);
            if ($re) {

            } else {
                $this->error("上传头像失败");
            }
        }
        $where['id'] = session('reg.id');   //取得主键
        if ($userRole->create($data)) {
            $row = $userRole->where($where)->save($data);

            if ($row) {
                session('reg', NULL);            //注册成功，清空session.reg
                $this->success("注册成功", U("Index/login"), 3);
            } else {
                $this->error("注册失败请返回重新填写相关信息");
            }
        } else {
            $this->error($userRole->getError());
        }
    }

    //获取手机验证码
    public function getCaptcha()
    {
        $phone   = I('post.phone');
        $captcha = randNum(4);
        session('reg.captcha', $captcha);
        sendMsg($phone, $captcha, 'reg');
    }

}