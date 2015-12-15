<?php
/**
 * Created by PhpStorm.
 * User: tongdragon
 * Date: 2015/9/16
 * Time: 14:42
 */
namespace Weixin\Controller;

use Think\Controller;
use Think\Model;

class ProjectController extends BaseController
{
    public function index()
    {
        $Pro  = D("Project");
        $list = $Pro->limit(10)->select();
        $this->assign('list', $list);
        $this->display();
    }

    public function getData()
    {
        $data = M('project')->limit(10)->select();
        $this->ajaxReturn($data);
    }

}
