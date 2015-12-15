<?php
/**
 * Created by PhpStorm.
 * User: tongdragon
 * Date: 2015/9/16
 * Time: 14:28
 */
namespace Weixin\Controller;

use Think\Controller;

class InvestorController extends BaseController
{
    public function index()
    {
        $Pro  = M("User");
        $list = $Pro->limit(10)->select();
        $this->assign('list', $list);
        $this->display();
    }

    public function detail()
    {
        $this->display();
    }

    public function search()
    {
        $this->display();

    }
}