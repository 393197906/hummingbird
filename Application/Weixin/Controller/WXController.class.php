<?php
	namespace Weixin\Controller;

	use Think\Controller;
	use Com\WechatAuth;
	use Com\errCode;

	class WXController extends Controller
	{
		static $WXAuth;
		protected $appid = 'wx52f47532e0353f7b';
		protected $appsecret = '6f2ef02325be5e89d835d62b9352a5a7';

		public function _initialize()
		{
			$token = S("token");
			self::$WXAuth = new WechatAuth($this->appid, $this->appsecret, $token);
			if (!$token) {
				$token = self::$WXAuth->getAccessToken();
				S(array('expire' => $token['expires_in']));
				S("token", $token['access_token']);
			}
		}
		public function index()
		{
			$this->display();
		}

		/**
		 * 自定义菜单
		 */
		public function createMenu()
		{
			$menuData = array(
				"button" =>
					array(
						array(
							'name' => '项目库',
							'sub_button' => array(
								array('type' => 'view', 'name' => '项目精选', 'url' => 'http://115.28.49.146/tong/index.php/project/profilter'),
								array('type' => 'view', 'name' => '全部项目', 'url' => 'http://115.28.49.146/tong/index.php/project/index')
							)
						),
						array(
							'name' => '创业服务',
							'sub_button' => array(
								array('type' => 'view', 'name' => '创业合伙人', 'url' => 'http://115.28.49.146/tong/Server/partners'),
								array('type' => 'view', 'name' => '融资服务', 'url' => 'http://115.28.49.146/tong/finance'),
								array('type' => 'view', 'name' => '技术支持', 'url' => 'http://115.28.49.146/tong/techSupport'),
								array('type' => 'view', 'name' => '政策服务', 'url' => 'http://115.28.49.146/tong/policy'),
							)
						),
						array(
							'name' => '我的',
							'sub_button' => array(
								array('type' => 'view', 'name' => '个人中心', 'url' => 'http://115.28.49.146/tong/index.php/UserCenter/pageUserCenter'),
								array('type' => 'view', 'name' => '绑定账号', 'url' => 'http://115.28.49.146/tong/index.php/UserCenter/pageBind'),
							)
						)
					)
			);
			$re = self::$WXAuth->menuCreate($menuData);
			if ($re['errcode'] != 0) {
				$ret = errCode::getErrText($re['errcode']);
				$this->error($ret);
			} else {
				$this->success('创建菜单成功！');
			}
		}

		public function deleteMenu() {
			$re = self::$WXAuth->menuDelete();
			if ($re['errcode'] != 0) {
				$ret = errCode::getErrText($re['errcode']);
				$this->error($ret);
			} else {
				$this->success('删除成功');
			}

		}
		public function getMenu()
		{
			$arr = self::$WXAuth->menuGet();

			if ($arr['errcode'] != 0) {
				$ret = errCode::getErrText($arr['errcode']);
				$this->error($ret);
			}

			if (!empty($arr)) {
				//保护中文，微信api不支持中文转义的json结构
				array_walk_recursive($arr, function (&$value) {
					$value = urlencode($value);
				});
				$arr = urldecode(json_encode($arr));
			}
			return $arr;
		}
	}

