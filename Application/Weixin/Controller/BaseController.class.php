<?php
/**
 * Created by PhpStorm.
 * User: geeth
 * Date: 2015/11/29 0029
 * Time: 14:25
 */

namespace Weixin\Controller;
use Think\Controller;

class BaseController extends Controller
{

    public function _initialize()
    {

    }

    public function index()
    {
        $this->display("Blocks/comingsoon");
    }

    public function detail(){
        $this->display("Blocks/comingsoon");
    }
}
