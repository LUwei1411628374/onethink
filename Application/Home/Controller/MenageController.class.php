<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-08-11
 * Time: 16:40
 */

namespace Home\Controller;


class MenageController extends HomeController
{
    public function index()
    {
        $model =M('Menage');//实例化对象
        $count  = $model->count();// 查询满足要求的总记录数
        $Page   = new Page($count,10);//传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $list = $model->limit($Page->firstRow.','.$Page->listRows)->select();
//        $list = M('Repair')->select();
        $this->assign('list', $list);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();

//        </table>
//</div>
//<div class="page">{$page}</div>

    }



    public function add(){
        if(IS_POST){
            $Menage = D('Menage');
            $data = $Menage->create();
//            var_dump($data);exit;
            if($data){
                $Menage->numbers = uniqid(date("Ymd"));
                $id = $Menage->add();
                if($id){
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_menage', 'menage', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Menage->getError());
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
            $this->display();
        }
    }
}