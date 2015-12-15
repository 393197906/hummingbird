<?php

namespace Home\Model;

use Think\Model;

class ProjectModel extends Model
{
    protected $_validate = array (array ('pname', 'require', '需要填写项目名称！'), // 项目名必须
                                  array ('num', 'require', '需要填写参与人数！'),  // 参与人数必须
                                  array ('needmoney', 'require', '需要填写融资数额！'),  // 融资数额必须
                                  array ('intro', 'require', '需要填写项目介绍！'),  // 项目介绍必须
                                  array ('partnertype', 'require', '需要选择合伙人类型！'),  // 合伙人类型必须
                                  array ('ptag', 'require', '需要填写项目标签！'),  // 项目标签必须

                                  array ('pname', '1,30', '机构名称超出指定长度！',0,'length'), // 项目名称长度限制
                                  array ('partnertype', '1,30', '合伙人类型超出指定长度！',0,'length'), // 合伙人类型长度限制
                                  array ('ptag', '1,30', '项目标签超出指定长度！',0,'length'), // 项目标签长度限制
                                  array('intro', 'checklength', '机构名称不能少于50个字且不能大于300个字！', 0, 'function'),

    );


    //取得project数据
    public function getData($id){
        $where['id'] = $id;
        $date = $this->where($where)->find();
        return $date;
    }

    //取得project数据 兼顾分页
    public function getDataByUserId($id,$limit=NULL){
        $where['uid'] = $id;
        if(empty($limit)){
            $date = $this->where($where)->select();
        }
        elseif(!is_array($limit)){
            return ;
        }else{
            $date = $this->where($where)->limit($limit['firstRow'].','.$limit['listRows'])->select();
        }

        return $date;
    }
    //取得数据总数（分页）
    public function getCountByUserId($id){
        $where['uid'] = $id;
        $count = $this->where($where)->count();
        return $count;
    }

    //logo图片上传
    public function upload($file){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Uploads/projectpic/'; // 设置附件上传根目录
        // 上传单个文件
        $info   =   $upload->uploadOne($file);
        return $info;
    }

}


?>