        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">欢迎进入考勤管理系统</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                	<?php if($username == '15958397862' or $username == '13957384997' or $username == '13967853090'): ?>
                		<br>
                		<p><lable style="color:green">懒人开门系统上线啦!</lable></p>
                		<p><a class="btn btn-danger" href='?/main/open/?id=8' >三楼北移门</a></p>
                	<?php endif; ?>
                	<?php if($username == '13957371045' or $username == '13967853090'): ?>
                		<br>
                		<p><lable style="color:green">懒人开门系统上线啦!</lable></p>
                		<p><a class="btn btn-danger" href='?/main/open/?id=1' >二楼北移门</a></p>
                		<p><a class="btn btn-danger" href='?/main/open/?id=13' >一楼北移门</a></p>
                	<?php endif; ?>
                	<?php if($username == '15024368607' or $username == '13967853090' or $username == '15726940636'): ?>
                		<br>
                		<p><lable style="color:green">懒人开门系统上线啦!</lable></p>
                	    <p><a class="btn btn-danger" href='?/main/open/?id=6' >技术部北门</a></p>
                	<?php endif; ?>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->


