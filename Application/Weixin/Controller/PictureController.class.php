<?php
	/**
	 * Created by PhpStorm.
	 * User: tongdragon
	 * Date: 2015/9/23
	 * Time: 11:10
	 */
	namespace Weixin\Controller;

	use Think\Controller;
	use Think\Upload;
	use Think\Image;

	class PictureController extends Controller
	{
		public function index()
		{
			$this->time = time();
			$this->display();
		}

		//上传操作
		public function upload()
		{
			$token = I('post.token');
			$timestamp = I('post.timestamp');
			$verifyToken = md5('unique_salt' . $timestamp);
			if (!empty($_FILES) && $token == $verifyToken) {
				//上传参数配置
				$config = array(
					'maxSize' => 3145728,                   // 设置附件上传大小
					'rootPath' => './Uploads/',         // 设置附件上传根目录
					'savePath' => '',                       // 设置附件上传（子）目录
					'saveName' => array('uniqid', ''),          //保存名称
					'exts' => array('jpg', 'gif', '', 'jpeg'),          // 设置附件上传类型
					'autoSub' => true,                          //自动使用子目录保存上传文件 默认为true
					'subName' => array('date', 'Ymd'),          //子目录创建方式，采用数组或者字符串方式定义
				);
				$upload = new Upload($config);// 实例化上传类

				// 上传文件
				$info = $upload->upload();
				if (!$info) {// 上传错误提示错误信息
					$data = array(
						'status' => 0,//状态
						'info' => $upload->getError(),
					);
				} else {// 上传成功
					//添加水印和缩略图
					$img_url = $config['rootPath'] . $info['Filedata']['savepath'] . $info['Filedata']['savename'];//图片地址
					$img = new Image(1, $img_url);
					$mark = "./Public/images/uploadify/hbh.png";                                                     //水印图片地址
					$img->water($mark);                                                                             //添加水印默认是右下角
					$img->save($img_url);                                                                            //保存水印后的图片
					$img->thumb(250, 150);                                                                               //缩略图宽250px 高150px 等比例缩放
					$thumb_url = $config['rootPath'] . $info['Filedata']['savepath'] . 'tb_' . $info['Filedata']['savename'];//缩略图地址
					$img->save($thumb_url);//保存缩略图

					$data = array(
						'savename' => $info['Filedata']['savename'],//保存名称
						'savepath' => $config['rootPath'] . $info['Filedata']['savepath'],//保存路径
						'status' => 1,//状态
					);
				}

				$this->ajaxReturn($data);
			}
		}

		//删除图片
		public function del()
		{
			if (!IS_AJAX) {
				exit('需要AJAX提交信息');
			}
			$img_url = I('post.url');
			$len = strlen(__ROOT__);
			$img_url = substr($img_url, $len);

			//获取缩略图地址
			$tb_img_url = get_tb_img_url($img_url);

			if (@unlink($img_url) && @unlink($tb_img_url)) {
				$data = array(
					'status' => 1,
					'info' => ':)成功删除图片',
				);
			} else {
				$data = array(
					'status' => 0,
					'info' => ':(删除图片失败',
				);
			}
			$this->ajaxReturn($data);
		}

		//修改名称
		public function changeName()
		{
			if (!IS_AJAX) {
				exit('需要AJAX提交信息');
			}

			$old_url = I('post.url');                 //原来的地址
			$len = strlen(__ROOT__);
			$old_url = substr($old_url, $len);    //除去前缀,改成 ./Uploads/...
			$name = I('post.name');                   //新的名称不包含后缀
			$info = pathinfo($old_url);               //获取图片的信息
			$path = $info['dirname'] . '/';             //目录信息
			$ext = $info['extension'];                //后缀信息
			$new_url = $path . $name . '.' . $ext;            //生成新的图片地址信息

			$old_tb = get_tb_img_url($old_url);
			$new_tb = get_tb_img_url($new_url);
			//检查是否有同名文件;目标文件是否存在
			if (!file_exists($old_url)) {
				$data = array(
					'status' => 0,
					'info' => '原文件不存在!',
				);
			} elseif (file_exists($new_url)) {
				$data = array(
					'status' => 0,
					'info' => '新的命名已存在,起冲突,请改换别名!',
				);
			} else {
				$res = @rename($old_url, $new_url);//修改名称
				$res_tb = @rename($old_tb, $new_tb);//修改缩略图名称
				if ($res) {
					$data = array(
						'status' => 1,
						'info' => ':)成功修改图片标题',
						'savepath' => $new_url,
						'savename' => $name,
					);
				} else {
					$data = array(
						'status' => 0,
						'info' => ':(修改失败',
					);
				}
			}

			$this->ajaxReturn($data);
		}
	}