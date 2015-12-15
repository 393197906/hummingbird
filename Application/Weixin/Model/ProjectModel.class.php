<?php
	/**
	 * Created by PhpStorm.
	 * User: tongdragon
	 * Date: 2015/9/23
	 * Time: 10:29
	 */
	namespace Weixin\Model;

	use Think\Model;

	/**
	 * 文档基础模型
	 */
	class ProjectModel extends Model
	{

		protected $_auto = array(
			array('update_time', NOW_TIME, self::MODEL_BOTH),
			array('uid', 'getUid', self::MODEL_BOTH, 'callback'),
		);

		public function getUid()
		{
			$user = S("user_auth");

			return $user['uid'];
		}
	}