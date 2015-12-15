<?php
	/**
	 * Created by PhpStorm.
	 * User: tongdragon
	 * Date: 2015/9/7
	 * Time: 18:41
	 */
	namespace WeiXin\Controller;

	use Think\Controller;

	class UserController extends Controller
	{
		public function register()
		{
			if (IS_POST) {
				$user = M('User');
				if ($data = $user->create()) {
					$uid = $user->add();
					if ($uid > 0) {
						$this->success('注册成功', U('login'));
					} else {
						$this->error('注册失败');
					}
				} else {
					$this->error('创建数据对象失败');
				}
			} else {
				$this->display();
			}
		}

		public function login()
		{
			if (IS_POST) {
				$where['username'] = I('post.username');
				$where['password'] = I('post.password');

				$User = M('User');
				$re = $User->where($where)->find();
				if ($re) {
					$this->autoLogin($re);
					$this->success('登陆成功', U('Project/index'));
				} else {
					$this->error('登陆失败');
				}
			} else {
				$this->display();
			}

		}

		/**
		 * @param $user
		 */
		public function autoLogin($user)
		{
			$auth = array(
				'uid' => $user['id'],
				'username' => $user['username'],
				'last_login_time' => $user['last_login_time'],
				'identity' => $user['identity'],
			);
			S('user_auth', $auth);
			S('user_auth_sign', data_auth_sign($auth));
		}

		/**
		 *
		 * @return void
		 */
		public function logout()
		{
			S('user_auth', null);
			S('user_auth_sign', null);
		}

		public function pageFindPwd(){
			$this->display();
		}

		public function userCenter(){

		}

	}