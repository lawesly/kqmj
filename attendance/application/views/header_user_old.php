<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $title; ?></title>
<!--    <link href="{{data.favicon}}" type="image/x-icon" rel="shortcut icon">-->
    <link href="/application/views/static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="/application/views/static/vendor/metismenu/metis-menu.min.css" rel="stylesheet">
    <link href="/application/views/static/vendor/datatable/datatable.css" rel="stylesheet">
    <link href="/application/views/static/vendor/alert/sweet-alert.css" rel="stylesheet">

    <link href="/application/views/static/vendor/sb-admin-2.css" rel="stylesheet">
    <link href="/application/views/static/vendor/animate.css" rel="stylesheet">
    <link href="/application/views/static/vendor/select/select2.min.css" rel="stylesheet">
    <link href="/application/views/static/timepicker/jquery-ui-1.11.0/jquery-ui.css" rel="stylesheet">
    <link href="/application/views/static/timepicker/jQuery-Timepicker-Addon/dist/jquery-ui-timepicker-addon.css" rel="stylesheet">

    <link href="/application/views/static/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/application/views/assets/chosen/chosen.css" rel="stylesheet" type="text/css" />
    <link href="/application/views/static/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css" rel="stylesheet">
<!--    <link href="/application/views/static/multiple-select-master/multiple-select.css" rel="stylesheet">-->
    <style type="text/css">
        .navbar-header{
            width: 200px;
            text-align: center;
            /*padding-left: 20px;*/
        }
        .navbar-brand{
            font-size: 20px;
        }
        .table{
            margin-top: 10px;
        }
        .hover{
            cursor: pointer;
        }
        .progress-reboot{
            position: absolute;
            height: 2px;
            top: 0px;
            background: #337ab7;
            width: 0%;
            z-index: 2000;
	    }
        #main-content{
    	    overflow-x:auto;
	    }
        #main-content .add-btn{
    	    position: absolute;
    	    z-index:100;
    	    width: 200px;
	    }
        #wrapper{
    	    background: #2e3c4e;
	    }
        .navbar-default .navbar-nav>li>a{
    	    color:white;
	    }
        .navbar-default .navbar-nav>li>a:hover{
            background: #299d71;
	    }
        .navbar-default .navbar-brand{
    	    color:white;
	    }
        .navbar-default .navbar-brand:hover{
    	    color:white;
	    }
        .select2-container{
	        width: 100% !important;
	    }
        button.detail{
	        margin-left: 10px;
	    }
        .sweet-alert h2{
	        margin-top: 30px;
	    }
    </style>

    <script type="text/javascript" src="/application/views/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="/application/views/assets/js/jquery.form.js"></script>
    <script type="text/javascript" src="/application/views/assets/chosen/chosen.jquery.js"></script>
    <!--<script type="text/javascript" src="/application/views/assets/js/jquery-1.8.1.min.js"></script>-->
</head>

<body>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">考勤管理系统</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="/" >首页</a></li>
        </ul>
        <ul class="nav navbar-nav pull-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $username; ?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="?/passwd/">修改密码</a>
                        <a href="?/mail/">绑定邮箱</a>
                        <a href="?/carlicense/">添加车牌</a>
                        <a href="?/logout/">退出</a>
                    </li>
                </ul>
            </li>
        </ul>
        <form class="navbar-form">
            <!--<input type="text" class="form-control" placeholder="Search...">-->
        </form>
        <div class="navbar-default sidebar" style="margin-top:42px" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="?/attendance_user/">考勤管理(员工版)</a>
                    </li>
                    <li>
                        <a href="?/anomaly_user/">异常管理</a>
                    </li>
                    <li>
                        <a href="?/notice_user/">异常通知  <span class="badge"><?php echo $count; ?></span></a>
                    </li>
                    <?php if($username == '13967853090'): ?>
                    <li>
                        <a href="?/qjstatistics/">请假统计</a>
                    </li>
                    <?php endif; ?>
                    <?php if($username == '15726940636'): ?>
                    <li>
                        <a href="?/qjstatistics/">请假统计</a>                                      </li>
                    <?php endif; ?>
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>
    <div id="page-wrapper" class='animated '>
        <div class="container-fluid">
            <div class="row">
                <div id='main-content' class="col-md-12" style='padding-top:20px;'>
