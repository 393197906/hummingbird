<?php
namespace Admin\Controller;
use Think\Controller;
class FounderController extends Controller {
    public function _initialize(){

        $session = session("admin");
        if(empty($session)){
            $this->redirect("Login/index");
        }
    }

    public function index()
    {
        $User = D('user');
        $where['nickname'] =array('like','%'.$_GET['rolename']."%");
        $where['identity']='1';
        $count      = $User->where($where)->count();// ��ѯ����Ҫ����ܼ�¼��
        $Page       = new \Think\Page($count,10);// ʵ������ҳ�� �����ܼ�¼����ÿҳ��ʾ�ļ�¼��
        if ($where['username']!=''&&$where!=null) {
            $data = $User->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        } else {
            $data = $User->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        }
// ���з�ҳ���ݲ�ѯ ע��page�����Ĳ�����ǰ�沿���ǵ�ǰ��ҳ��ʹ�� $_GET[p]��ȡ
        $this->assign('Founder',$data);// ��ֵ���ݼ�
        $show       = $Page->show();// ��ҳ��ʾ���
        $this->assign('page',$show);// ��ֵ��ҳ���
        $this->display(); // ���ģ��

    }



    //�޸ı���
    public  function  save(){
        $User=D('user');//����ʵ����
    $User->create();//֧�ִ�������ʽ�������ݶ���
    $User->save();

        redirect(U('index'));
    }
    //�༭��ť��ѯ
    public  function  edit(){
        $where['id']= $_GET['id'];
        $User=D('user');
        $data = $User->where($where)->find();
        $this->assign('edit',$data);
        $this->display();
    //dump($data);
    }
//��ӱ���
    public  function  insert(){
        $User=D('user');//����ʵ����
        $User->create();//֧�ִ�������ʽ�������ݶ���
        $User->add();//���ύ

        redirect(U('index'));
    }
    ///ɾ������
    public  function delete(){
        $where['id']= $_GET['id'];
        $User=D('user');
        $User->where($where)->delete();
        redirect(U('index'));
    }


    public function update(){
       $where['id']= $_GET['id'];

       $status['status']=$_GET['status'];
        $User = D('user');
        $User->where($where)->save($status);
        redirect(U('index?p=2'));
      //  dump(I('post.'));

    }
}