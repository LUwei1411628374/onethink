<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-08-14
 * Time: 19:14
 */

namespace Home\Controller;


use EasyWeChat\Foundation\Application;
use Think\Controller;
require './vendor/autoload.php';
class WechatController extends Controller
{
    public function index(){
        $app = new Application(C('wechat_config'));
    }
}