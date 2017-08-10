<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-08-10
 * Time: 14:12
 */

namespace Admin\Controller;


class ManageController extends AdminController
{
    public function index(){
        $lists = M('Manage')->select();
        $this->assign('index', $lists);
        //$this->meta_title = '报修管理';
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $Manage = D('Manage');
            $data = $Manage->create();
//            var_dump($data);exit;
            if($data){
                $id = $Manage->add();
                if($id){
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_manage', 'manage', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Manage->getError());
            }
        } else {
            $pid = i('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
                $parent = M('Channel')->where(array('id'=>$pid))->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info',null);
           // $this->meta_title = '新增导航';
            $this->display('edit');
        }
    }


    public function edit($id = 0){
        if(IS_POST){
            $Manage = D('Manage');
            $data = $Manage->create();
            if($data){
                if($Manage->save()){
                    //记录行为
                    action_log('update_manage', 'manage', $data['id'], UID);
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error($Manage->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Manage')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $pid = i('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
                $parent = M('Manage')->where(array('id'=>$pid))->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            $this->display('');
        }
    }


    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Manage')->where($map)->delete()){
            //记录行为
            action_log('update_manage', 'manage', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}