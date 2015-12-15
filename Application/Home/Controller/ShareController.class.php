<?php
namespace Home\Controller;

use Think\Controller;

class ShareController extends Controller
{
    public function index()
    {
        $uid = session('user.id');
        getMsgStatu($uid);

        $rightnum = 5; //右侧显示条数
        $type = I('get.type', 'topic');

        $this->assign('active', $type);
        $frag = 'frag' . $type;
        if ($type == 'topic') {
            $this->getTopics();
            //热门话题
            $hotData=M("topic")->field("id,content,uid")->order("rise desc,view desc")->limit("0,".$rightnum)->select();
            $this->assign("hottopic",$hotData);
            //最新话题
            $newData=M("topic")->field("id,content,uid")->order("posttime desc")->limit("0,".$rightnum)->select();
            $this->assign("newtopic",$newData);
        } else {
            if ($type == 'blog') {
                $this->getBlogs();
                //热门博客
                $hotData=M("blog")->field("id,title,uid")->order("rise desc,view desc")->limit("0,".$rightnum)->select();
                $this->assign("hotblog",$hotData);
                //最新博客
                $newData=M("blog")->field("id,title,uid")->order("posttime desc")->limit("0,".$rightnum)->select();
                $this->assign("newblog",$newData);
            }
            }

        $this->assign('frag', $frag);
        $this->display();
    }


    public function getTopics()
    {

        $count      =  M("")->table('topic t,user u')
                            ->field('t.id,t.uid,t.content,t.type,t.view,t.rise,t.posttime,t.statu,u.username,u.headpic')
                            ->where('t.uid=u.id')->order('t.posttime desc')->count();// 查询满足要求的总记录数
        $page       = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $page->show();// 分页显示输出
        $data =
            M("")->table('topic t,user u')
                 ->field('t.id,t.uid,t.content,t.type,t.view,t.rise,t.posttime,t.statu,u.username,u.headpic')
                 ->where('t.uid=u.id')->order('t.posttime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('topics', $data);
        $this->assign("page",$show);

    }

    public function getBlogs()
    {
        $count      = M("")->table('blog b,user u')
                           ->field('b.id,b.uid,b.content,b.type,b.view,b.rise,b.posttime,b.statu,b.tag,b.title,u.username,u.headpic')
                           ->where('b.uid=u.id')->order('b.posttime desc')->count();// 查询满足要求的总记录数
        $page       = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $page->show();// 分页显示输出
        $data =
            M("")->table('blog b,user u')
                 ->field('b.id,b.uid,b.content,b.type,b.view,b.rise,b.posttime,b.statu,b.tag,b.title,u.username,u.headpic')
                 ->where('b.uid=u.id')->order('b.posttime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('blogs', $data);
        $this->assign("page",$show);
    }


    public function detailTopic()
    {
        $id  = I('get.id');
        $uid = I('get.uid');

        M('topic')->where("id=$id")->setInc('view');

        //查topic
        $where['id'] = $id;
        $data        = M('topic')->where($where)->find();
        $this->assign('topic', $data);
        unset($where);
        //查user
        $where['id'] = $uid;
        $data        = M('user')->where($where)->find();
        $this->assign('user', $data);
        unset($where);

        //查评论
        $where['tid'] = $id;
        $data         =
            M("")->table('topic_comment tc,user u')
                 ->field('u.username,u.headpic,tc.id,tc.tid,tc.uid,tc.targetid,tc.content,tc.rise,tc.commenttime,tc.statu')
                 ->where($where)->where('u.id=tc.uid')->select();
        $this->assign('comments', $data);
        $this->display();
    }

    public function detailBlog()
    {
        $id  = I('get.id');
        $uid = I('get.uid');
        M('blog')->where("id=$id")->setInc('view');
        //查blog
        $where['id'] = $id;
        $data        = M('blog')->where($where)->find();
        $data['content'] = html_entity_decode($data['content']);
        $this->assign('blog', $data);
        unset($where);
        //查user
        $where['id'] = $uid;
        $data        = M('user')->where($where)->find();
        $this->assign('user', $data);
        unset($where);

        //查评论
        $where['bid'] = $id;
        $data         =
            M("")->table('blog_comment b,user u')
                 ->field('u.username,u.headpic,b.id,b.bid,b.uid,b.targetid,b.content,b.rise,b.commenttime,b.statu')
                 ->where($where)->where('u.id=b.uid')->select();
        $this->assign('comments', $data);
        $this->display();
    }


    //TODO 控制器权限问题，方法移动


}