<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-08-11
 * Time: 19:09
 */

namespace Home\Controller;


use Think\Page;

class NoticeController extends HomeController
{
    public function index(){
        $model=M('document')->alias('d')->join('onethink_picture da  ON d.id = da.id')->field('d.description,d.id,d.title,da.path,d.create_time,d.view')->select();
        //var_dump($model);exit;
        $this->assign('list', $model);
        $this->display();
    }
    public function detail($id){
        $model=M('document')->alias('d')->join('onethink_document_article da  ON d.id = da.id')->field('d.id,d.title,da.content,d.create_time')->where(['d.id'=>$id])->select();
        //var_dump($model);exit;

        $this->assign('list', $model);
        $this->display();
    }

}