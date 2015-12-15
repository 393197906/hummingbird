<?php

	/**
	 * Created by PhpStorm.
	 * User: tongdragon
	 * Date: 2015/9/22
	 * Time: 18:47
	 */
	namespace Weixin\Model;

	use Think\Model;

	class UserModel extends Model
	{
		protected $_auto = array(
			array('reg_time', NOW_TIME, self::MODEL_INSERT),
			array('last_login_time', 0, self::MODEL_BOTH),
		);
	}