<?php

	namespace Weixin\Controller;

	use Think\Controller;
	use Com\Wechat;

	class IndexController extends Controller
	{
		/**
		 * 微信消息接口入口
		 * 所有发送到微信的消息都会推送到该操作
		 * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
		 */
		static $WX;
		static $token = 'chuangtouzz';
		protected $appid = "wx52f47532e0353f7b";
		protected $appkey = "3HhgGpCm9yEoy7ObE7gPe6OxpLchHMs8jSM45qVHYOD";

		public function index()
		{
			$this->display();
			self::$WX = new Wechat(self::$token);
			/* 获取请求信息 */
			$data = self::$WX->request();
			if ($data && is_array($data)) {
				$this->reply($data);
			}
		}

		/**
		 * @param  array $data 接受到微信推送的消息
		 */
		private function reply($data)
		{
			$openid = $data['FromUserName'];

			switch ($data['MsgType']) {
				case 'event':
					switch ($data['Event']) {
						//subscribe
						case 'subscribe':
							self::$WX->replyText('欢迎您关注枣庄创投！');
							break;

						//view
						case 'view':
							//TODO 只有不常点击的链接缓存openid，其他使用构造回复内容的方式。
							S("openid", $openid, 1);
							break;

						default:
							self::$WX->replyText("欢迎访问枣庄创投公众平台！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
							break;
					}
					break;

				case 'text':
					switch ($data['Content']) {
						case '0':
							self::$WX->replyText('欢迎访问枣庄创投公众平台，这是文本回复的内容！');
							break;
						default:
							break;
					}
					break;

				default:
					break;
			}
		}
	}
