<?php
/**
 * Created by PhpStorm.
 * User: geeth
 * Date: 2015/11/27 0027
 * Time: 17:01
 */
//用户合法登录之后的页面及方法权限
namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public $user;

    public function _initialize()
    {
        $this->user = session('user');
        if (empty($this->user)) {
            $this->error("请先登录", U("Index/login"), 3);
        }
        if (empty($this->user['realname']) || empty($this->user['identity'])) {
            session('reg.step', 1);
            $this->success("你需要先补全信息", U('Reg/reg'));
            exit;
        } else {
            if (empty($this->user['headpic'])) {
                session('reg.step', 2);
                $this->success("你需要先选择偏好信息", U('Reg/reg'));
                exit;
            }
        }
        getMsgStatu($this->user['id']);
    }

    //产生系统消息

    /**
     * @param $type        string follow,liketopic,likeblog,replytopic,replyblog...
     * @param $receiverid  string 目标用户id
     * @param $objid       string 如，主题id,博客id,项目id等
     */
    //TODO 可为对象添加链接
    public function doAddSysMsg($type, $receiverid, $objid = NULL)
    {
        if (empty($type) || empty($receiverid)) {
            return;
        }
        $theme   = "";
        $content = "";
        $uid     = $this->user['id'];
        $user    = M('user')->where("id=$uid")->find();

        switch ($type) {
            case 'follow':
                $theme   = "新的关注";
                $content = "用户" . $user['username'] . "关注了你！";
                break;
            case 'followproject':
                $theme       = "新的关注";
                $projectname = M('project')->where("id=$objid")->getField('pname');
                $content     = "用户" . $user['username'] . "关注了你的项目:" . $projectname;
                break;
            case 'liketopic':
                $theme     = "主题获得了赞";
                $topicname = M('topic')->where("id=$objid")->getField('content');
                $topicname = subtext($topicname, 15);
                $content   = "用户" . $user['username'] . "赞了你的主题:" . $topicname;
                break;
            case 'likeblog':
                $theme    = "博客获得了赞";
                $blogname = M('blog')->where("id=$objid")->getField('title');
                $blogname = subtext($blogname, 20);
                $content  = "用户" . $user['username'] . "赞了你的博客:" . $blogname;
                break;

            case 'replytopic':
                $theme     = "主题新回复";
                $topic     = M('topic')->where("id=$objid")->find();
                $tid       = $topic['id'];
                $topicname = subtext($topic['content'], 15);
                $url = U('Home/Share/detailtopic', array ('id' => $tid, 'uid' => $receiverid));
                $content   =
                    "用户" . $user['username'] . "回复了你的主题:<a href='".$url."' target='_blank'>" . $topicname . "</a>";
                $content   = htmlspecialchars($content);
                break;
            case 'replyblog':
                $theme    = "博客新回复";
                $blog     = M('blog')->where("id=$objid")->find();
                $bid      = $blog['id'];
                $blogname = subtext($blog['title'], 20);
                $url = U('Home/Share/detailblog', array ('id' => $bid, 'uid' => $receiverid));
                $content  =
                    "用户" . $user['username'] . "回复了你的博客:<a href='".$url."' target='_blank'>" . $blogname . "</a>";;
                $content = htmlspecialchars($content);
                break;
            /*            case 'liketopiccomment':
                            $theme    = "博客新回复";
                            $blogname = M('blog')->where("id=$objid")->getField('title');
                            $blogname = subtext($blogname, 12);
                            $content  = "用户" . $user['username'] . "回复了你的:" . $blogname;
                            break;
                        case 'likeblogcomment':
                            $theme    = "博客新回复";
                            $blogname = M('blog')->where("id=$objid")->getField('title');
                            $blogname = subtext($blogname, 12);
                            $content  = "用户" . $user['username'] . "回复了你的博客:" . $blogname;
                            break;*/
        }
        $add['senderid']   = 0;
        $add['content']    = $content;
        $add['statu']      = 1;
        $add['theme']      = $theme;
        $add['fromname']   = "系统消息";
        $add['receiverid'] = $receiverid;
        $add['sendtime']   = date("Y-m-d H:i:s");
        M('message')->add($add);
    }

}