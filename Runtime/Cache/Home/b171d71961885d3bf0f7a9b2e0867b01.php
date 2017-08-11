<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>盒老师</title>
    <meta name="keywords" content="盒老师">
    <meta name="content" content="盒老师">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <link type="text/css" rel="stylesheet" href="/Public/static/login/css/login.css">
    <script type="text/javascript" src="/Public/static/login/js/jquery.min.js"></script>
</head>
<body class="login_bj" >
<div class="zhuce_body">
    <div class="logo"><a href="#"><img src="/Public/static/login/images/logo.png" width="114" height="54" border="0"></a></div>
    <div class="zhuce_kong login_kuang">
        <div class="zc">
            <div class="bj_bai">
                <h3>登录</h3>
                <form class="login-form" action="/index.php?s=/Home/User/login.html" method="post">
                    <div class="control-group">
                        <!--<label class="control-label" for="inputEmail">用户名</label>-->
                        <div class="controls">
                            <input type="text" id="inputEmail" class="kuang_txt" placeholder="请输入用户名"  ajaxurl="/member/checkUserNameUnique.html" errormsg="请填写1-16位用户名" nullmsg="请填写用户名" datatype="*1-16" value="" name="username">
                        </div>
                    </div>
                    <div class="control-group">
                        <!--<label class="control-label" for="inputPassword">密码</label>-->
                        <div class="controls">
                            <input type="password" id="inputPassword"  class="kuang_txt" placeholder="请输入密码"  errormsg="密码为6-20位" nullmsg="请填写密码" datatype="*6-20" name="password">
                        </div>
                    </div>
                    <div class="control-group">
                        <!--<label class="control-label" for="inputPassword">验证码</label>-->
                        <div class="controls">
                            <input type="text" id="inputPassword" class="kuang_txt" placeholder="请输入验证码"  errormsg="请填写5位验证码" nullmsg="请填写验证码" datatype="*5-5" name="verify">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"></label>
                        <div class="controls">
                            <img width="226px" class="verifyimg reloadverify" alt="点击切换" src="<?php echo U('verify');?>" style="cursor:pointer;">
                        </div>
                        <div class="controls Validform_checktip text-warning"></div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <label class="checkbox">
                                <input type="checkbox"> 自动登陆
                            </label>
                            <button type="submit" class="btn">登 陆</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bj_right">
                <p>使用以下账号直接登录</p>
                <a href="#" class="zhuce_qq">QQ注册</a>
                <a href="#" class="zhuce_wb">微博注册</a>
                <a href="#" class="zhuce_wx">微信注册</a>
                <p><span><span class="pull-left"><span>还没有账号? <a href="<?php echo U('User/register');?>">立即注册</a></span> </span></p>

            </div>
        </div>
    </div>

</div>

</body>

    <script type="text/javascript">
        $(document)
            .ajaxStart(function(){
                $("button:submit").addClass("log-in").attr("disabled", true);
            })
            .ajaxStop(function(){
                $("button:submit").removeClass("log-in").attr("disabled", false);
            });


        $("form").submit(function(){
            var self = $(this);
            $.post(self.attr("action"), self.serialize(), success, "json");
            return false;

            function success(data){
                if(data.status){
                    window.location.href = data.url;
                } else {
                    self.find(".Validform_checktip").text(data.info);
                    //刷新验证码
                    $(".reloadverify").click();
                }
            }
        });

        $(function(){
            var verifyimg = $(".verifyimg").attr("src");
            $(".reloadverify").click(function(){
                if( verifyimg.indexOf('?')>0){
                    $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
                }else{
                    $(".verifyimg").attr("src", verifyimg.replace(/?.*$/,'')+'?'+Math.random());
                }
            });
        });
    </script>

</html>