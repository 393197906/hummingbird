<?php

namespace Home\Model;

use Think\Model;

class IncubatorModel extends Model
{


    //园区列表数据
    public function getDataList()
    {
        $data = $this->field("id,name,address,phone,logo,rise")->order("posttime desc")->select();
        return $data;
    }

    //推荐园区数据
    public function getDataListRecommend()
    {
        $data = $this->field("id,name")->where(array("recommend"=>"1"))->order("posttime desc")->select();
        return $data;
    }

    //园区单个数据
    public function getDataId($id)
    {
        $data = $this->where(array("id"=>$id))->find();
        return $data;
    }
}


?>