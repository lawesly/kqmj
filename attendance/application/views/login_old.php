<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <!--    <link href="/application/views/static/img/favicon.ico" type="image/x-icon" rel="shortcut icon">-->
    <link href="/application/views/static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/application/views/static/vendor/alert/sweet-alert.css" rel="stylesheet">
    <link rel="stylesheet" href="/application/views/static/vendor/flatui/css/flat-ui.min.css">
</head>

<body>
    <div class="container" style='width:970px'>
        <form id="f1" class="form-signin" method="post" action="?/login/cfm/">
            <div class="login">
                <div class="login-screen">
                    <div class="login-icon">
                        <img src="/application/views/static/vendor/flatui/img/icons/png/Compas.png" alt="Welcome to Mail App">
                        <h4>&nbsp;&nbsp;欢迎使用<small></small></h4>
                    </div>
                    <div class="login-form">
                        <div class="form-group">
                            <input type="text" name="username" class="form-control login-field" value="" placeholder="输入用户名" id="login-name" autocomplete="off">
                            <label class="login-field-icon fui-user" for="login-name"></label>
                        </div>
                        <div class="form-group">
                            <input type="password" name="passwd" class="form-control login-field" value="" placeholder="输入密码" id="login-pass" autocomplete="off">
                            <label class="login-field-icon fui-lock" for="login-pass"></label>
                            <p id='login-msg' style="color:#E74C3C"></p>
                        </div>
	                    <div class="form-group">
      	    	            <input type="checkbox" name="remember" id="remember" />
                            <label for="remember" style="color:grey">Remember me</label>
                            <!--<font color="grey">Remember me</font>-->
	                    </div>
                        <button id='login-btn' class="btn btn-primary btn-lg btn-block" >登录</button>
                        <!-- <a class="login-link" href="#">Lost your password?</a> -->
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="/application/views/static/vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/application/views/assets/js/jquery.form.js"></script>
    <script type="text/javascript" src='/application/views/static/vendor/alert/sweet-alert.min.js'></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="/application/views/static/vendor/bootstrap/js/bootstrap.js"></script>

    <script>

        $(document).ready(function() {
            $('#f1').ajaxForm(function(data) {
                if(data === '1'){
                        swal({
                            title: "登录成功!",
                            type: "success"
                        },
                        function(){window.location="?/main/";}
                        );
                }else if(data === '0'){
                        swal({
                            title: "用户名不存在!",
                            type: "error"
                        },
			    function(){window.location="?/login/";}
                        );
                }else if(data === '2'){
                        swal({
                            title: "密码错误!",
                            type: "error"
                        },
			    function(){window.location="?/login/";});

                }else if(data === '3'){
                        swal({
                            title: "账户已禁用!",
                            type: "error"},
                        function(){window.location="?/login/";});
                }else if(data === '4'){
                        swal({
                            title: "登录成功,您的密码是初始密码,请修改密码!",
                            type: "warning"
                        },
                        function(){window.location="?/passwd/";});
		        }else{
                        swal({
                            title: "其他原因导致登录失败!",
                            type: "error"
                        },
                        function(){window.location="?/login/";});
		        }
            });
        });

    </script>
</body>
</html>
