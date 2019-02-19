<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="/application/views/static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="/application/views/static/vendor/metismenu/metis-menu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="/application/views/static/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="/application/views/static/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/application/views/static/vendor/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/application/views/static/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="/application/views/static/vendor/alert/sweet-alert.css" rel="stylesheet">
    <link href="/application/views/static/timepicker/jquery-ui-1.11.0/jquery-ui.css" rel="stylesheet">
    <link href="/application/views/static/timepicker/jQuery-Timepicker-Addon/dist/jquery-ui-timepicker-addon.css" rel="stylesheet">
    <link href="/application/views/assets/chosen/chosen.css" rel="stylesheet" type="text/css" />
    <link href="/application/views/static/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

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
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">考勤管理系统</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span> 欢迎,<?php echo $username; ?></span>
                </li>

                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="?/passwd/"><i class="fa fa-user fa-fw"></i> 修改密码</a>
                        </li>
                        <li><a href="?/mail/"><i class="fa fa-envelope fa-fw"></i> 绑定邮箱</a>
                        </li>
                        <li><a href="?/carlicense/"><i class="fa fa-car fa-fw"></i> 添加车牌</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="?/logout/"><i class="fa fa-sign-out fa-fw"></i> 退出</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" style="margin-top:50px" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <li>
                            <a href="?/main/" id="main"><i class="fa fa-home fa-fw"></i> 首页</a>
                        <li>
                            <a href="?/attendance_user/" id="attendance_user"><i class="fa fa-table fa-fw"></i> 考勤管理(员工版)</a>
                        </li>
                        <li>
                            <a href="?/anomaly_user/" id="anomaly_user"><i class="fa fa-edit fa-fw"></i> 异常管理</a>
                        </li>
                        <li>
                            <a href="?/notice_user/" id="notice_user"><i class="fa fa-star fa-fw"></i> 异常通知 <span class="badge"><?php echo $count; ?></span></a>
                        </li>
                        <?php if($username == '13967853090' || $username == '15726940636'): ?>
                            <li>
                                <a href="?/qjstatistics/" id="qjstatistics" ><i class="fa fa-table fa-fw"></i> 请假统计</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

