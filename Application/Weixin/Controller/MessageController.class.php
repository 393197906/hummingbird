<?php
/**
 * Created by PhpStorm.
 * User: 文
 * Date: 2015/9/27
 * Time: 20:32
 */
namespace Weixin\Controller;
use Think\Controller;
class MessageController extends Controller{
    public function index(){
        $mess = M('message');

        $list = $mess->limit(10)->select();
        $this->assign('list',$list);
        $this->display();
    }
    public function detail($id){
        $mess = M('message');
        $where['id'] = $id;
        $list = $mess->where($where)->select();
        $this->assign('list',$list);
        $this->display();
    }
}