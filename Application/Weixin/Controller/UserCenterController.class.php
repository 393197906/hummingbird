<?php
	/**
	 * Created by PhpStorm.
	 * User: tongdragon
	 * Date: 2015/9/30
	 * Time: 12:44
	 */
	namespace Weixin\Controller;

	use Think\Controller;

	class UserCenterController extends Controller
	{
		private $openid;

		public function index()
		{
			$this->display();
		}

		//注册绑定页面
		public function pageBind()
		{
			$sign = null;
			$openid = I('post.openid');
			if ($openid == null || $openid == "") {
				$openid = S("openid");
				S("openid", null);
			}
			$where['openid'] = $openid;
			$user = M("User");
			if ($openid != null && $openid != "") {
				$re = $user->where($where)->find();
				if ($re) {
					$sign = "该微信已与" . $re['realname'] . "绑定!";
				}
			}
			$this->assign("openid", $openid);
			$this->assign("sign", $sign);
			$this->display();
		}

		//绑定
		public function doBind()
		{
			$where["phone"] = I('post.username');
			$where["password"] = md5(I('post.password'));
			$user = M('User');
			$re = $user->where($where)->find();
			if ($re) {
				if ($re['openid'] != null && $re['openid'] != "") {
					$this->error("该账号已经绑定！");
				}
				$save['openid'] = I('post.openid');
				$re1 = $user->where($where)->save($save);
				if ($re1) {
					$this->success("绑定成功", U("pageUserCenter", "openid=" . $this->openid));
				} else {
					$this->success("绑定失败");
				}
			} else {
				$this->error("用户验证失败");
			}
		}
	}