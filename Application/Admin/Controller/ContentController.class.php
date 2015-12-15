<?php
namespace Admin\Controller;

use Think\Controller;
class ContentController extends Controller
{
    //专题列表

    public function _initialize(){

        $session = session("admin");
        if(empty($session)){
            $this->redirect("Login/index");
        }
    }
    //获取专题列表（辅助）
    protected function getList()
    {
        $subject = D('subject')->getData();
        $this->assign("subject", $subject);
    }
    //首页
    public function index()
    {
        $this->getList();
        $url = "subjectList";
        $this->assign("url",$url);
        $this->assign("left","content-left");
        $this->display("Index/index");
    }

    //专题列表（显示）
    public function subjectList()
    {
        $pid       = I('get.id');
        $article = D('article');
        if(empty($pid)){  //首页加载

            $count = D('article')->getDataNum();
            $page = PAGEADMIN($count);
            $data = $article->getDataList($page->firstRow,$page->listRows);
            $this->assign("title","subjectList");
            $this->assign("subject","专题列表 <small>SubjectList</small>");
            $this->assign("articleList",$data);
            $this->assign("page",$page->show());
            $this->display();
            return;
        }

        $subjectData = M('subject')->where(array('id'=>$pid))->find();
        $data = $article ->getDataList($page->firstRow,$page->listRows,$pid);
        $count = D('article')->getDataNum($pid);
        $page = PAGEADMIN($count);
        $this->assign("title","subjectList");
        $this->assign("subject",$subjectData['name']);
        $this->assign("articleList",$data);
        $this->assign("page",$page->show());
        $this->display();
        
    }


    //添加文章（显示）
    public function addArticle()
    {   
        $this->getList();
        $this->display();
    }

    //添加修改文章用的到（辅助）
    public function getClass()
    {
        $name = I('post.name');
        $data = M('subject')->field("class")->where(array ("name" => $name))->find();
        $str ="";
        $arr  = explode("|", $data['class']);
        foreach ($arr as $v) {
            $str.= "<option value='" . $v . "'>" . $v . "</option>";
        }

        echo $str;
    }


    //添加文章（处理）
    public function doAddArticle()
    {
        $data             = I('post.');
        $pid              = M('subject')->field('id')->where(array ('name' => $data['subject']))->find();
        $data['pid']      = $pid['id'];  //获取父专题Id
        $data['posttime'] = time();  //获取发布时间
        $data['author']   = session('user.username');  //获取作者名
        $data['uid']      = session('user.id');  //获取作者id
        //dump($data);
        $article = D('article');

        if ($article->create($data)) {
            $status = $article->add($data);

            if ($status) {
                $this->success("文章发布成功", "subjectlist?frag=list&id=" . $data['pid'], 3);
            } else {
                $this->error("文章发布失败");
            }
        } else {
            $this->error($article->getError());
        }


    }

    //推荐文章（处理）
    public function doRecommendArticle()
    {
        $id          = I('post.id');
        $article     = M('article');
        $where['id'] = $id;
        $data        = $article->where($where)->find();
        if ($data['recommend'] == "0") {
            $status = $article->where(array ("id" => $id))->save(array ("recommend" => "1"));
            if ($status) {
                $info = array("推荐成功",1);
                $this->success($info);
            } else {
                $info = array("系统繁忙",1);
                $this->error($info);
            }
        } else {
            $status = $article->where(array ("id" => $id))->save(array ("recommend" => "0"));
            if ($status) {
                $info = array("取消推荐成功",0);
                $this->success($info);
            } else {
                $info = array("系统繁忙",0);
                $this->error($info);
            }
        }
    }
    
    //编辑文章（显示）
    public function editArticle(){
        $id = I('get.id');
        $article = D('article');
        $data = $article->where(array('id'=>$id))->find();
        $this->getList(); //专题列表
        $classData = M('subject')->field("class")->where(array ("id" => $data['pid']))->find();
        $class  = explode("|", $classData['class']);
        $this->assign('class',$class);
        $this->assign('article',$data);
        $this->display();
    }
    // 编辑文章（处理）
    public function doEditArticle()
    {
        $data             = I('post.');
        $data['edittime'] = time();  //获取编辑时间
        $pid              = M('subject')->field('id')->where(array ('name' => $data['subject']))->find();
        $data['pid']      = $pid['id'];  //获取父专题Id
        $article          = D('article');
        if ($article->create($data)) {
            $status = $article->where(array ('id' => I('get.id')))->save($data);

            if ($status) {
                $this->success("文章修改成功");
            } else {
                $this->error("文章修改失败");
            }
        } else {
            $this->error($article->getError());
        }
    }

    //删除文章（处理）
    public function doDelArticle()
    {
        $article = D('article');
        $status  = $article->where(array ('id' => I('post.id')))->delete();
        if ($status) {
            $this->success("文章删除成功");
        } else {
            $this->error("文章删除失败，请再次尝试");
        }
    }

    //专题管理（显示）
    public function subjectControl()
    {
        $this->getList();
        $subject = M('subject');
        $data = $subject->order('id')->select();
        $this->assign("title","subjectControl");
        $this->assign("subject", $data);
        $this->display();
    }

    //添加专题（处理）
    public function doAddSubject()
    {
        $data = I('post.');
        $subject = D('subject');
        if ($subject->create($data)) {
            $status = $subject->add($data);
            if ($status) {
                $this->success("新增专题成功");
            } else {
                $this->error("系统繁忙");
            }
        } else {
             $this->error($subject->getError());
        }
    }

    //专题编辑（处理）
    public function doEditSubject()
    {
        $data    = I('post.');
        $subject = D('subject');
        if ($subject->create($data)) {
            $where['id'] = $data['id'];
            unset($data['id']);
            $status = $subject->where($where)->save($data);
            if ($status) {
                $this->success("修改成功");
            } else {
                $this->error("系统繁忙");
            }
        } else {
                $this->error( $subject->getError() );
        }

    }

    //删除专题（处理）
    public function doDeleteSubject()
    {
        $id      = I('post.id');
        $subject = D('subject');

        $where['id'] = $id;
        $status      = $subject->where($where)->delete();
        if ($status) {
            $this->success("删除成功");
        } else {
             $this->error("系统繁忙");
        }
    }


    //园区列表（显示）
    public function incubatorList()
    {
        $incubator = D('incubator');
        $count = $incubator->getDataNum();
        $page = PAGEADMIN($count);
        $data = $incubator->getDataList($page->firstRow,$page->listRows);
        $this->assign("incubatorList", $data);
        $this->assign("page",$page->show());
        $this->display();

    }
     //添加园区（显示）
    public function addIncubator(){
        $this->display();
    }

    //添加园区（处理）
    public function  doAddIncubator()
    {
        $data = I('post.');
        $incubator = D('incubator');
        $upLoadStatu = $incubator->upload($_FILES['logo']);   //文件上传；

        if (!$upLoadStatu) {
            $this->error("请选择园区LOGO图片");
            return;
        } else {
            $data['logo'] = '/' . $upLoadStatu['savepath'] . $upLoadStatu['savename'];
        }
        $data['posttime'] = time();
        $data['author']   = session('user.name');
        $data['uid']      = session('user.id');

        if ($incubator->create($data)) {
            $status = $incubator->add($data);
            if ($status) {
                $this->success("园区添加成功",'incubatorList');
            } else {
                $this->error("园区添加失败");
            }
        } else {
            $this->error($incubator->getError());
        }
    }

    //编辑园区（显示）
    public function editIncubator(){
        $id = I('get.id');
        $data = D('incubator')->getDataId($id);
        $this->assign("incubator",$data);
        $this->display();
    }
    //编辑园区（处理）
    public function  doEditIncubator()
    {
        $data = I('post.');
        $id = I('get.id');
        //dump($data);dump($id);dump($_FILES);
        //return;
        $incubator = D('incubator');

        if(!empty($_FILES['logo']['name'])) {  //判断是否更改logo
            $upLoadStatu = $incubator->upload($_FILES['logo']);   //文件上传；
            if (!$upLoadStatu) {
                $this->error("请选择园区LOGO图片");
                return;
            } else {
                if(file_exists($data['ylogo'])){
                    if(!unlink($data['ylogo'])){
                        $this->error("logo更新失败");
                    }
                }
                $data['logo'] = '/' . $upLoadStatu['savepath'] . $upLoadStatu['savename'];
            }

        }

        $data['edittime'] = time(); //修改时间

        if ($incubator->create($data)) {
            $status = $incubator->where(array("id"=>$id))->save($data);
            if ($status) {
                $this->success("园区修改成功","incubatorList");
            } else {
                $this->error("园区修改失败");
            }
        } else {
            $this->error($incubator->getError());
        }
    }

    //删除园区（处理）

    public function doDelIncubator()
    {
        $incubator = M('incubator');
        $status    = $incubator->where(array ('id' => I('post.id')))->delete();
        if ($status) {
            
            $this->success("园区删除成功");
        } else {
            $this->error("园区删除失败");
        }
    }

    //园区推荐（处理）
    public function doRecommendIncubator(){
        $id          = I('post.id');
        $incubator = M('incubator');
        $where['id'] = $id;
        $data        = $incubator->where($where)->find();
        if ($data['recommend'] == "0") {
            $status = $incubator->where(array ("id" => $id))->save(array ("recommend" => "1"));
            if ($status) {
                $info = array("推荐成功",'1');
                $this->success($info);
            } else {
                $info = array("系统繁忙");
                $this->error($info);
            }
        } else {
            $status = $incubator->where(array ("id" => $id))->save(array ("recommend" => "0"));
            if ($status) {
                $info = array("取消推荐成功",'0');
                $this->success($info);
            } else {
                $info = array("系统繁忙");
                $this->error($info);
            }

        }
    }




}