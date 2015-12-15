<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends BaseController
{


    public static $pageNum = 12;//每页显示条数
    public function _initialize()
    {
        if (ACTION_NAME=='userinfo') {

        }else{
            parent::_initialize();
        }

    }

    public function index()
    {
        $this->redirect("userinfo");
        //$this->display();
    }

    private function identity()
    {
        $str = '';
        if (session('user.identity') == "0") {
            $str .= 'Blocks/usermenuinvestor';
        } else {
            $str .= "Blocks/usermenufounder";
        }
        $this->assign("menu", $str);
    }

    //个人信息
    public function userinfo()
    {
        $this->identity(); //加载左侧菜单
        $user_session = session('user');
        //TODO 对字段进行格式化处理,方便前端使用
        $user     = D('user');  //实例化user
        $dataUser = $user->getData($user_session['id']);  //取得user 数据
        if ($dataUser['identity'] == '1') {
            $userRole = D('user_founder');  //实例化user_founder;
        } else {
            $userRole = D('user_investor');
        }
        $dataUserRole  = $userRole->getData($user_session['id']); //取得user_founder 数据
        $data          = array_merge($dataUserRole, $dataUser);   // 合并数组
        $birthday      = explode('-', date("Y-m-d", $data['birthday_time']));  //分割出生日期
        $data['year']  = $birthday[0];
        $data['month'] = $birthday[1];
        $data['day']   = $birthday[2];
        $this->assign('user', $data);
        if (session('user.identity') == "0") {
            $frag = I('get.frag', 'userinfoinvestor');
        } else {
            $frag = I('get.frag', 'userinfofounder');
        }
        $frag = 'frag' . $frag;
        $this->assign('frag', $frag);
        $this->display();
    }

    //个人信息更改
    public function userInfoChange()
    {
        $data = I('post.');             //取得数据
        if (!empty($data['year']) && !empty($data['month']) && !empty($data['day'])) {
            $data['birthday']      = $data['year'] . "-" . $data['month'] . "-" . $data['day'];  //获取出生日期
            $data['birthday_time'] = strtotime($data['birthday']);      //获取出生日期时间戳
        }

        $id            = session('user.id');   // 获取user主键
        $uid           = session('user.id');   //获取userRole第二主键
        $data['check'] = "check";   //验证规则

        //头像上传
        if (!empty($_FILES['headpic']['name'])) {
            $upLoadStatu = D('User')->upload($_FILES['headpic'], 'headpic');
            if (!$upLoadStatu) {
                $this->error("请选择您喜爱的头像");
            } else {
                $data['headpic'] = '/' . $upLoadStatu['savepath'] . $upLoadStatu['savename'];
                //删除之前的头像
                $file = session('user.headpic');
                $file = UPL_PATH . 'headpic' . $file;
                if (file_exists($file)) {
                    if (unlink($file)) {
                        //更新session
                        session('user.headpic', $data['headpic']);
                    } else {
                        $this->error($file, '', 5);
                    }
                }
            }
        }

        $user = D('user');
        if (session('user.identity') == "0") {
            $userRole = D('user_investor');
        } else {
            $userRole = D('user_founder');
        }
        if ($user->create($data) || $userRole->create($data)) {

            $whereUser['id']      = $id;  //user 条件
            $whereUserRole['uid'] = $uid;  //userRole条件
            $statuUser            = $user->where($whereUser)->save($data);
            $statuUserRole        = $userRole->where($whereUserRole)->save($data);

            if ($statuUserRole || $statuUser) {
                $this->success("保存成功", "userinfo", 3);
            } else {
                $this->error("保存失败，请返回重新填写相关数据", '', 3);
            }
        } else {
            $this->error(" " . $user->getError() . " " . $userRole->getError(), "", 100);
        }
    }

    //我的项目
    public function myproject()
    {
        $pageNum = self::$pageNum;  //每页显示数目
        $project = D('project'); //实例化projectModel类
        $count   = $project->getCountByUserId(session('user.id'));
        $page    = PAGE($count, $pageNum);// 实例化分页类 传入总记录数和每页显示的记录数
        $show    = $page->show();// 分页显示输出
        $data    =
            $project->getDataByUserId(session('user.id'),
                                      array ("firstRow" => $page->firstRow, 'listRows' => $page->listRows));
        $frag    = I('get.frag', 'myproject');
        $frag    = 'frag' . $frag;
        $this->assign('frag', $frag);
        $this->assign('myProject', $data);
        $this->assign('page', $show);
        $this->display();
    }

    //添加项目
    public function addProject()
    {
        $data               = I('post.');
        $data['uid']        = session('user.id');  //获取用户主键
        $data['location']   = $data['province'] . " " . $data['city']; //获取城市
        $data['updatetime'] = date('Y-m-d H:i:s');  //获取更新时间
        $project            = D('project'); //实例化projectModel类
        $upLoadStatu        = $project->upload($_FILES['logo']);   //文件上传；
        if (!$upLoadStatu) {
            $this->error("请选择项目图片");

            return;
        } else {
            $data['logo'] = '/' . $upLoadStatu['savepath'] . $upLoadStatu['savename'];
        }
        // dump($data);

        if ($project->create($data)) {
            $statu = $project->add($data);
            if ($statu) {
                $this->success("项目添加成功！", 'myproject', 3);
            } else {
                $this->error("添加项目失败，请返回重新添加");
            }
        } else {
            $this->error($project->getError());
        }
    }


    //修改项目页面
    public function editProject()
    {
        $this->identity(); //加载左侧菜单
        $data             = D('Project')->getData(I('get.id'));
        $arr              = explode(' ', $data['location']);  // 分割location
        $data['province'] = $arr[0];
        $data['city']     = $arr[1];
        session('project.logo', $data['logo']);  //存储logo路径
        session('project.id', $data['id']);  //存储id
        //dump($data);
        $this->assign('project', $data);
        $this->display();
    }

    //项目修改
    public function doEditProject()
    {
        $data             = I('post.');
        $data['location'] = $data['province'] . ' ' . $data['city']; //合并localtion
        $id               = session('project.id');
        //头像上传
        if (!empty($_FILES['logo']['name'])) {
            $upLoadStatu = D('User')->upload($_FILES['logo'], 'projectpic');
            if (!$upLoadStatu) {
                dump($upLoadStatu);
                $this->error("请选择您喜爱的项目头像", '', 100);
            } else {
                $data['logo'] = '/' . $upLoadStatu['savepath'] . $upLoadStatu['savename'];
                //删除之前的头像
                $file = session('project.logo');
                $file = UPL_PATH . 'projectpic' . $file;
                if (file_exists($file)) {
                    if (unlink($file)) {
                        //更新session
                        session('project.logo', $data['logo']);
                    } else {
                        $this->error($file, '', 5);
                    }
                }
            }
        }
        session('project', NULL);
        $project = D('project');   //实例化
        if ($project->create($data)) {
            $where['id'] = $id;
            $statu       = $project->where($where)->save($data);
            if ($statu) {
                $this->success('修改成功', 'myproject', '3');
            } else {

                $this->error('修改失败，请返回重新修改');
            }
        } else {
            $this->error($project->getError());
        }

    }


    //删除项目
    public function doDelProject(){
        $id = I('get.id');
        $project = M('project');
        $status = $project->where(array("id"=>$id))->delete();
        if($status){
            $this->success("项目删除成功");
        }else{
            $this->error("项目删除失败，请再次尝试");
        }
    }

    //账号设置页面
    public function setting()
    {
        $this->identity(); //加载左侧菜单
        $frag = I('get.frag', 'setting');
        $frag = 'frag' . $frag;
        $this->assign('frag', $frag);
        $this->display();
    }

    //我的关注
    public function follow()
    {

        $this->identity(); //加载左侧菜单
        $pageNum = self::$pageNum; //每页显示条目
        $frag    = I('get.frag', 'follow');
        if ($frag == "follow") {  //首页面跳转
            $this->redirect("follow?frag=followfounder");
        }

        if ($frag == 'followfounder') { //关注的合伙人
            $rela                = "follow.followid=user.id and user.id=founder.uid";
            $where['follow.uid'] = session('user.id');
            $count               =
                M()->table('user user,follow_man follow,user_founder founder')->where($rela)->where($where)
                   ->count();// 查询满足要求的总记录数
            $page                = PAGE($count, $pageNum);// 实例化分页类 传入总记录数和每页显示的记录数
            $show                = $page->show();// 分页显示输出

            $data = M()->table('user user,follow_man follow,user_founder founder')//取得数据
                       ->where($rela)->where($where)->limit($page->firstRow . ',' . $page->listRows)->select();

            for ($i = 0; $i != count($data); $i++) {
                $data[$i]['agegroup'] = ageGroup($data[$i]['birthday_time']); //年龄转换 调用founder内静态方法
            }

            //dump($data);
            $this->assign('founder', $data);

        } elseif ($frag == 'followinvestor') { //关注的投资人
            $rela                = "follow.followid=user.id and user.id=investor.uid";
            $where['follow.uid'] = session('user.id');

            $count =
                M()->table('user user,follow_man follow,user_investor investor')->where($rela)->where($where)
                   ->count();// 查询满足要求的总记录数
            $page  = PAGE($count, $pageNum);// 实例化分页类 传入总记录数和每页显示的记录数
            $show  = $page->show();// 分页显示输出
            $data  =
                M()->table('user user,follow_man follow,user_investor investor')->where($rela)->where($where)
                   ->limit($page->firstRow . ',' . $page->listRows)->select();
            for ($i = 0; $i != count($data); $i++) {
                $data[$i]['agegroup'] = ageGroup($data[$i]['birthday_time']); //年龄转换 调用founder内静态方法
            }
            $this->assign('investor', $data);
        } elseif ($frag == 'followproject') {    //关注的项目
            $rela                = "follow.projectid=project.id";
            $where['follow.uid'] = session('user.id');

            $count =
                M()->table('follow_project follow,project project')->where($rela)->where($where)->count();// 查询满足要求的总记录数
            $page  = PAGE($count, $pageNum);// 实例化分页类 传入总记录数和每页显示的记录数
            $show  = $page->show();// 分页显示输出
            $data  =
                M()->table('follow_project follow,project project')->where($rela)->where($where)
                   ->limit($page->firstRow . ',' . $page->listRows)->select();
            for ($i = 0; $i != count($data); $i++) {
                $arr                  = explode(" ", $data[$i]['location']);
                $data[$i]['province'] = $arr[0];
                $data[$i]['city']     = $arr[1];
            }

            $this->assign('project', $data);
        }


        $frag = 'frag' . $frag;   //加前缀
        $this->assign('frag', $frag);
        $this->assign('page', $show);
        $this->display();
    }

    //修改密码
    public function doChangePwd()
    {
        $data = I('post.');

        $id     = session('user.id');
        $oldpwd = M('user')->where("id=$id")->getField("password");
        if (md5($data['oldpwd']) == $oldpwd) {
            if ($data['password'] == $data['repassword']) {
                $save['id']       = $id;
                $save['password'] = md5($data['password']);
                $re               = M('user')->save($save);
                if ($re) {
                    session('user', NULL);
                    $this->success('修改成功，请重新登录', U('Index/login'), 3);
                } else {
                    $this->error('修改密码失败');
                }
            } else {
                $this->error('新密码不一致');
            }

        } else {
            $this->error('原密码不正确');
        }


    }

    //修改手机号码
    public function doChangePhone()
    {
        $data    = I('post.');
        $captcha = $data['captcha'];
        if ($captcha != session('reg.captcha')) {
            $this->error('手机验证码错误');
            return;
        } else {
            unset($data['captcha']);
            session('reg.captcha', NULL);
        }
        $where['id']    = session('user.id');
        $where['phone'] = $data['oldphone'];
        $data['check']  = 'check';  //勾选
        $user           = D('user');
        $status         = $user->where($where)->find();
        if ($status) {
            unset($where['phone']);
            if ($user->create($data)) {
                $saveStatus = $user->where($where)->save($data);
                if ($saveStatus) {
                    $this->success("手机更改成功", "setting");
                } else {
                    $this->error("系统繁忙或新号码与原号码一致");
                }
            } else {
                $this->error($user->getError());
            }
        } else {
            $this->error("您输入的原始手机号码不准确，请核对后再次更改");
        }

    }

    //项目关注方法
    public function doFollowProject()
    {
        $follow = M('follow_project');
        $where['uid']       = session('user.id');
        $projectid          = I('get.id');
        $where['projectid'] = $projectid;
        $receiverid = M('project')->where("id=$projectid")->getField('uid');
        $this->doAddSysMsg('followproject', $receiverid, $projectid);

        $data = $follow->where($where)->find();

        if ($data) {
            $del = $follow->where($where)->delete();
            if ($del) {
                $this->ajaxReturn("关注") ;
            }
        } else {
            $add = $follow->add($where);
            if ($add) {
                $this->ajaxReturn("取消关注");
            }
        }

    }

    //项目关注状态
    public function get_doFollowProject()
    {
        $follow = M('follow_project');

        $where['uid']       = session('user.id');
        $where['projectid'] = I('post.id');
        $uid                = I('post.uid');
        $pname = I('post.pname');

        if ($where['uid'] == $uid) {
            echo "您不能关注自己的项目";
            return;
        }
        $data = $follow->where($where)->find();

        if ($data) {
            $this->error("您已关注过{$pname}");
        } else {
            $add = $follow->add($where);
            if ($add) {
                $this->success("关注{$pname}成功");
            } else {
               $this->error("系统繁忙,请稍候尝试");
            }
        }

    }

    //用户关注方法
    public function doFollowUser()
    {
        $follow = D('follow_man');

        $where['uid']      = session('user.id');
        $where['followid'] = I('get.id');
        $data = $follow->where($where)->find();

        if ($data) {
            $del = $follow->where($where)->delete();
            if ($del) {
                echo "关注";
            }
        } else {
            $add = $follow->add($where);
            if ($add) {
                $this->doAddSysMsg('follow', $where['followid'], $where['followid']);
                echo "取消关注";
            }
        }
    }

    //用户关注状态
    public function get_doFollowUser()
    {

        $follow            = D('follow_man');
        $username          = I('post.username');
        $where['uid']      = session('user.id');
        $where['followid'] = I('post.id');

        if ($where['uid'] == $where['followid']) {
            $this->error("您不能关注自己");

        }

        $data = $follow->where($where)->find();
        if ($data) {
            $this->error("您已关注过{$username}");
        } else {
            $add = $follow->add($where);
            if ($add) {
                $this->success("关注{$username}成功");
            } else {
                $this->error("系统繁忙，请再此尝试");
            }
        }
    }


    public function findpwd()
    {
        $this->display();
    }

    //发送短信验证码
    public function getCaptcha()
    {
        $phone   = I('post.phone');
        $captcha = randNum(4);
        session('captcha', $captcha);
        $re = sendMsg($phone, $captcha, 'resetpwd');
        if ($re) {
            $this->ajaxReturn($re);
        } else {
            $this->ajaxReturn("failed");
        }
    }

    //验证手机号
    public function doCheckPhone()
    {
        $params  = I('post.');
        $captcha = session("captcha");
        $phone   = $params['phone'];
        if ($params['captcha'] == $captcha) {
            $id = M('user')->where("phone=$phone")->getField('id');
            session('uid', $id);
            $this->redirect('User/resetpwd');
        } else {
            $this->error("验证码错误");
        }
    }


    public function resetpwd()
    {
        $this->display();
    }

    public function doResetPwd()
    {
        $params = I('post.');
        if ($params['password'] == $params['repassword']) {
            $save['id']       = session('uid');
            $save['password'] = md5($params['password']);
            $re               = M('user')->save($save);
            if ($re) {
                session('captcha', NULL);
                session('uid', NULL);
                $this->success("密码重置成功", U('Index/login'), 3);
            } else {
                $this->error("密码修改失败");
            }
        } else {
            $this->error("密码输入不一致，请重新输入");
        }
    }

    //点赞整合
    public function doRise()
    {

        $id   = I('post.id');
        $type = I('post.type');
        $receiverid="";
        $msgtype = "";

        switch ($type) {
            case "topic":
                $msgtype = "liketopic";
                $role = M("topic");
                $receiverid = $role->where("id=$id")->getField('uid');
                break;
            case "topic_comment":
                //$msgtype = "liketopiccomment";
                $role = M("topic_comment");
                //$receiverid = $role->where("id=$id")->getField('targetid');
                break;
            case "blog":
                $msgtype = "likeblog";
                $role = M("blog");
                $receiverid = $role->where("id=$id")->getField('uid');

                break;
            case "blog_comment":
                //$msgtype = "likeblogcomment";
                $role = M("blog_comment");
                //$receiverid = $role->where("id=$id")->getField('targetid');
                break;
            case "incubator":
                $role = M("incubator");
                break;
        }
            $where['type'] = $type;
            $where['uid'] =  session('user.id');
            $where['objectid'] = $id;
            $result = M("user_action")->where($where)->find();

            if($result){
                $this->error("您已经赞过了");
                return;
            }

            $status = $role->where(array ("id" => $id))->setInc("rise");
            if ($status) {
                $this->doAddSysMsg($msgtype, $receiverid, $id);
                M("user_action")->add($where);
                $this->success("点赞成功");
            } else {
                $this->error("系统繁忙");
            }
    }
    //评论整合
    public function doAddComment()
    {
        $msgtype = "";
        $objid = "";
        $table=I('post.table');  //表
        $add = I('post.');

        if ($table == 'blog_comment') {
            $msgtype = 'replyblog';
            $objid = $add['bid'];

        }else if ($table == 'topic_comment') {
            $msgtype = 'replytopic';
            $objid = $add['tid'];

        }
        if (empty($add['content'])) {
            $this->error("评论内容不可为空");
        }
        $add['uid']         = session('user.id');
        $add['commenttime'] = date("Y-m-d H:i:s");
        $add['statu']       = 1;
        $re                 = M($table)->add($add);
        if ($re) {
            $this->doAddSysMsg($msgtype, $add['targetid'], $objid);
            $this->success("评论成功");
        } else {
            $this->error("评论失败");
        }
    }

}
