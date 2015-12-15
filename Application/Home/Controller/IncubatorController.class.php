<?php
//园区孵化器模块
namespace Home\Controller;
use Think\Controller;
class IncubatorController extends Controller {
    //首页
    public function index()
    {
        $uid = session('user.id');
        getMsgStatu($uid);

        $post = I('post.');
        $order = "posttime desc" ; //排序方式
        $field="id,name,logo,rise,province,city,type,favourpolicy,condition";
        $incubator  = D("incubator");
        if (!empty($post['search'])) {  //搜索

            $search                               = '%' . $post['search'] . "%";  //搜索条件1
            $where['name|address|city|province'] = array ('like', $search);        //搜索条件2
            $count      =  $incubator->where($where)->count();// 查询满足要求的总记录数
            $page       = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
            $show       = $page->show();// 分页显示输出
            $data = $incubator->field($field)->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();

            $cou =    count($data);
            for ($i = 0; $i != $cou; $i++) {  //改变搜索结果颜色
                $data[$i]['name'] = str_replace($post['search'], "<span style='color:red'>{$post['search']}</span>", $data[$i]['name']);
            }

        }elseif(!empty($post['type'])){ //筛选
            $where = array ();
            if ($post['type'] !== "不限") {
                $where['type'] = $post['type'];
            }
            if ($post['province'] !== "不限") {
                $where['province'] = $post['province'];
            }
            if ($post['city'] !== "不限") {
                $where['city'] = $post['city'];
            }


            $count      =  $incubator->where($where)->count();// 查询满足要求的总记录数
            $page       = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
            $show       = $page->show();// 分页显示输出
            $data = $incubator->field($field)->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();


        }else{
            $count      =  $incubator->field($field)->count();// 查询满足要求的总记录数
            $page       = PAGE($count);// 实例化分页类 传入总记录数和每页显示的记录数
            $show       = $page->show();// 分页显示输出
            $data =  $incubator->field($field)->order($order)->limit($page->firstRow.','.$page->listRows)->select();
        }

        $this->assign("page",$show);
        $this->assign("incubator",$data);
        $this->display();
    }

    //详情页
    public function detail(){
        $id = I('get.id');
        $incubator  = D("incubator");
        $data =  $incubator->getDataId($id);
        $dataRecom = $incubator->getDataListRecommend();  //推荐园区
        //dump($data);
        $this->assign("recommend",$dataRecom);
        $this->assign("incubator",$data);
        M("incubator")->where(array("id"=>$data['id']))->setInc('view');  //阅读量自加
        $this->display();
    }


}
