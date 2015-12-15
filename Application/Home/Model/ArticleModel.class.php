<?php

namespace Home\Model;

use Think\Model;

class ArticleModel extends Model
{
    //专题文章
    public function getDataPid($pid){
        $data = $this->field("id,title")->where(array("pid"=>$pid))->select();

        return $data;
    }
     //文章详情
    public function getDataId($id){
        $data = $this->where(array("id"=>$id))->find();

        return $data;
    }

    //专题热点文章
    public function getDataPidHot($pid,$num){
        $data = $this->field("id,title")->where(array("pid"=>$pid))->order('readnum desc')->limit(0,$num)->select();

        return $data;
    }

    //专题推荐文章
    public function getDataPidRecommend($pid,$num){
        $data = $this->field("id,title")->where(array("pid"=>$pid,"recommend"=>'1'))->order('posttime desc')->limit(0,$num)->select();

        return $data;
    }

}


?>