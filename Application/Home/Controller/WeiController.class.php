<?php
/**
 * Created by PhpStorm.
 * User: 熊
 * Date: 2017/8/14
 * Time: 11:08
 */

namespace Home\Controller;
require './vendor/autoload.php'; // 引入 composer 入口文件
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use Think\Controller;

class WeiController extends Controller
{
 public function index()
 {


$app = new Application(C('wechat_config'));
     $server = $app->server;
     /*$server->setMessageHandler(function ($message) {
         // $message->FromUserName // 用户的 openid
         // $message->MsgType // 消息类型：event, text....
         return "您好！欢迎关注我!";
     });*/
     $server->setMessageHandler(function ($message) {
         switch ($message->MsgType) {
             case 'event':
                 return '欢迎关注宏远物业智能管理系统,输入s加定位可以搜索周边你想了解的
                 商家比如‘s饭店’，输入t‘城市’可以查询该城市天气比如t成都,还可以输入w可以得到我们最新活动';
                 break;
             case 'text':
                 $content=$message->Content;
                 if($content){
                     preg_match("/^(\w)(.*)$/",$content,$matches);
                     switch ($matches[1]){
                         case 'w':
                             $Document= M('Document')->where(['category_id'=>42])->select();
                             $news=[];
                             $news_count=0;
                             foreach ($Document as $name=>$value){
                                 $new = new News([
                                     'title'       => $value['title'],
                                     'description' => $value['description'],
                                     'url'         => 'http://xgx.tianjiao.site/index.php?s=/Home/Guarantee/seller/',
                                     'image'       => 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1502766357697&di=9990fb73926a66eb642acadbc5a77702&imgtype=jpg&src=http%3A%2F%2Fimg4.imgtn.bdimg.com%2Fit%2Fu%3D1280503970%2C1032368772%26fm%3D214%26gp%3D0.jpg',
                                 ]);
                                 $news[]=$new;
                                 $news_count++;//百度接口做多可以做8个
                                 if($news_count>=8){
                                     break;
                                 }
                             }


                             //返回图文消息
                             return $news;
                             break;
                         case 's':
                             //得到用户输入的文本内容
                             $query=$matches[2];
                             //查询出数据表中的坐标信息
                             $user_location = M('location')->where(['open_id'=>$message->FromUserName])->find();
                             //拼接出$location
                             $location = $user_location['x'].','.$user_location['y'];
                             //拼接出接口路劲
                             $search_url = "http://api.map.baidu.com/place/search?query={$query}&location=$location&radius=1000&output=xml";
                             //的到xml的子节点
                             $sempleXm=simplexml_load_file($search_url);
                             //定义一个大数组
                             $news=[];
                             $news_count=0;//数组长度赋值为空
                             //遍历出xml中的数据
                             foreach ($sempleXm->results->result as $key=>$value){
                                 $url = html_entity_decode($value->detail_url);//转译路径
                                 $lat=(string)$value->location->lat;//得到维度
                                 $lng=(string)$value->location->lng;//的到精度
                                 $name=(string)$value->name;//得到名字
                                 $address=(string)$value->address;//得到描述
                                 //拼接图片路径
                                 $image_url="http://api.map.baidu.com/panorama/v2?ak=mzyIoPg42h4yy9Twcvcy9t0oWlvlTbhx&width=512&height=256&location={$lng},{$lat}&fov=180";
                                 //图文列表
                                 $new = new News([
                                     'title'       => $name,
                                     'description' => $address,
                                     'url'         => $url,
                                     'image'       => $image_url,
                                 ]);
                                 $news[]=$new;
                                 $news_count++;//百度接口做多可以做8个
                                 if($news_count>=8){
                                     break;
                                 }

                             }
                             return $news;
                             break;
                         case 't':
                             //返回天气
                             $weatherXml=simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                             $weather=[];
                             foreach ($weatherXml as $name=>$value){
                                 $weather[(string)$value['cityname']] = (string)$value['stateDetailed'];
                             }
                             return $weather[$matches[2]];
                             break;

                     }

                 }else{
                     $text = new Text(['content'=>'这是我自己发送的文本消息']);
                     return $text;
                 }
                 break;
             case 'image':
                 return '收到图片消息';
                 break;
             case 'voice':
                 return '收到语音消息';
                 break;
             case 'video':
                 return '收到视频消息';
                 break;
             case 'location':
                  /* $message->Location_X  地理位置纬度
                    $message->Location_Y  地理位置经度
                       $message->Scale       地图缩放大小
                   $message->Label       地理位置信息*/
                 $sql = "insert into onethink_location(open_id,x,y,scale,label) VALUES ('{$message->FromUserName}','$message->Location_X','$message->Location_Y','{$message->Scale}','{$message->Label}') ON  DUPLICATE KEY UPDATE x='{$message->Location_X}',y='{$message->Location_Y}',scale='{$message->Scale}',label='{$message->Label}'";
                 M()->execute($sql);
                 return $message->Label;
                 break;
             case 'link':
                 return '收到链接消息';
                 break;
             // ... 其它消息
             default:
                 return '收到其它消息';
                 break;
         }
         // ...
     });
// 将响应输出
  $server->serve()->send(); // Laravel 里请使用：return $response;

 }
 public function addMenu(){
     $app = new Application(C('wechat_config'));
     $menu=$app->menu;
     $buttons = [
         [
             "name"       => "导航",
             "sub_button" => [

                 [
                     "type" => "view",
                     "name" => "首页",
                     "url"  => "http://xgx.tianjiao.site/index.php?s=/Home/Index/index"
                 ],
                 [
                     "type" => "view",
                     "name" => "业主认证",
                     "url"  => "http://xgx.tianjiao.site/index.php?s=/Home/Guarantee/edit.html"
                 ],
             ],
         ],
         [
             "name"       => "菜单",
             "sub_button" => [

                 [
                     "type" => "view",
                     "name" => "小区通知",
                     "url"  => "http://xgx.tianjiao.site/index.php?s=/Home/Guarantee/notice.html"
                 ],
                 [
                     "type" => "view",
                     "name" => "商家活动",
                     "url"  => "http://xgx.tianjiao.site/index.php?s=/Home/Guarantee/seller"
                 ],
                 [
                     "type" => "view",
                     "name" => "小区租售",
                     "url"  => "http://xgx.tianjiao.site/index.php?s=/Home/Guarantee/edit.html"
                 ],
                 [
                     "type" => "view",
                     "name" => "小区活动",
                     "url"  => "http://xgx.tianjiao.site/index.php?s=/Home/Guarantee/activity.html"
                 ],
             ],
         ],
         [
             "name"       => "个人中心",
             "sub_button" => [

                 [
                     "type" => "view",
                     "name" => "个人中心",
                     "url"  => "http://xgx.tianjiao.site/index.php?s=/Home/Guarantee/my.html"
                 ],
                 [
                     "type" => "view",
                     "name" => "在线保修",
                     "url"  => "http://xgx.tianjiao.site/index.php?s=/Home/Guarantee/add"
                 ],
                 [
                     "type" => "view",
                     "name" => "便民服务",
                     "url"  => "http://xgx.tianjiao.site/index.php?s=/Home/Guarantee/service "
                 ],
             ],
         ],
     ];
     $menu->add($buttons);
     $menus = $menu->all();
     dump($menus);
 }
    public static function getAccess(){
        if(!session('opend_id')){
            //没有，发起授权
            $app = new \EasyWeChat\Foundation\Application(C('wechat_config'));
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            //将请求的路由保存到session中
            session('request_uri',$_SERVER['PATH_INFO']);
            $response->send(); // Laravel 里请使用：return $response;
        }
    }
    public function callback(){
        $app = new \EasyWeChat\Foundation\Application(C('wechat_config'));
        $user = $app->oauth->user();
        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用

        //将用户的opend_id保存到session中
        session('opend_id',$user->getId());
        $this->redirect(session('request_uri'));
    }

}