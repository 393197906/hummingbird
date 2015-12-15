<?php
namespace Home\Controller;

use Think\Controller;

class MyShareController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
    }


    public function index()
    {
        $frag = I('get.frag', 'newstatu');
        $frag = 'frag' . $frag;
        $this->assign('frag', $frag);
        switch ($frag) {
            case 'fragmystatu':
                $this->getMyTopics();
                break;
            case 'fragmyblog':
                $this->getMyBlogs();
                break;

            case 'frageditblog':
                $this->editBlog();
                break;
            default:
                break;
        }
        $this->display();
    }


    //TODO 分页处理
    public function getMyTopics()
    {
        $where['uid'] = $this->user['id'];
        $data         = M('topic')->where($where)->order('posttime desc')->select();
        $headpic      = session('user.headpic');
        $this->assign('status', $data);
        $this->assign('headpic', $headpic);
    }

    public function getMyBlogs()
    {
        $where['uid'] = $this->user['id'];
        $data         = M('blog')->where($where)->order('posttime desc,edittime desc')->select();
        $this->assign('blogs', $data);
    }


    public function editBlog()
    {
        $id = I('get.id');
        $data = M('blog')->where("id=$id")->find();
        $data['content'] = html_entity_decode($data['content']);
        $this->assign('blog', $data);
    }

    public function doAddTopic()
    {
        $add = I('post.');
        if (empty($add['content'])) {
            $this->error('你并未发布任何内容');
        }
        $add['uid'] = $this->user['id'];

        $add['posttime'] = date("Y-m-d H:i:s");
        $add['state']    = 1;

        if (M('topic')->add($add)) {
            $this->ajaxReturn("success");
        } else {
            $this->ajaxReturn("failed");
        }
    }


    public function doAddBlog()
    {
        $add = I('post.');
        if (empty($add['title']) || empty($add['content'])) {
            $this->ajaxReturn('请填写完整');
        }
        $add['uid']           = $this->user['id'];
        $add['posttime']      = date('Y-m-d H:i:s');
        $add['statu']         = 1;
        $add['view']          = 0;
        $add['approvenumber'] = 0;
        //判断重复发布
        $where['uid']   = $this->user['id'];
        $where['title'] = $add['title'];
        $re             = M('blog')->where($where)->find();
        if ($re) {
            $this->ajaxReturn('exist');
        }

        if (M('blog')->add($add)) {
            $this->success("发布博客成功");
        }else{
            $this->error("发布博客失败");
        }
    }




    public function doEditBlog()
    {
        $save        = I('post.');
        if (empty($save['title']) || empty($save['content'])) {
            $this->error('请填写完整');
        }
        $re = M('blog')->save($save);
        if ($re) {
            $this->success("编辑博客成功");
        }else{
            $this->error("编辑博客失败");
        }


    }

    public function doDelBlog()
    {
        $where['id'] = I('post.id');
        $re          = M('blog')->where($where)->delete();
        if ($re) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }
    }

    public function doDelTopic()
    {
        $where['id'] = I('post.id');
        $re          = M('topic')->where($where)->delete();
        if ($re) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }

    }


}