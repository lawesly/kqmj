        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <br>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form role="form" name="f1" class="form-horizontal" action="?/attendance_user/search/" method="post">
                                <div class="form-group">
                                    <div class="col-lg-1 col-xs-4">
                                        <label>*(起)</label>
                                    </div>
                                    <div class="col-lg-5 col-xs-8">
                                        <input class="form-control input-datepicker" type="text" id="fromdate"  name="fromdate"  value="<?php echo $fromdate; ?>"  readonly/>
                                    </div>
                                    <div class="col-lg-1 col-xs-4">
                                        <label>*(止)</label>
                                    </div>
                                    <div class="col-lg-5 col-xs-8">
                                        <input class="form-control input-datepicker" type="text" id="todate"  name="todate"  value="<?php echo $todate; ?>"  readonly/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <input  type="submit"  value="查询" class="btn btn-primary" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <table width="100%" class="table table-bordered table-hover definewidth m10" id="tab" >
                        <thead>
                        <tr>
                            <th>手机号码</th>
                            <th>人员名称</th>
                            <th>部门</th>
                            <th>考勤日期</th>
                            <th>星期</th>
                            <th>类型</th>
                            <th>上班时间</th>
                            <th>下班时间</th>
                            <th>异常</th>
                            <th>说明</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($attendance as $key => $arr): ?>
                            <tr>
                                <td><a href='?/attendance_user/mjshow/?swipedate=<?php echo $key; ?>'  data-toggle="modal" data-target="#myModal"><?php echo $arr['phoneNum']; ?><a></td>
                                <td><a href='?/attendance_user/zkshow/?dwDate=<?php echo $key; ?>' data-toggle="modal" data-target="#myModal"><?php echo $arr['Name']; ?></a></td>
                                <td><?php echo $arr['depname']; ?></td>
                                <td><?php echo $key; ?></td>
                                <td><?php echo $arr['week']; ?></td>
                                <td><?php echo $arr['type']; ?></td>
                                <td><?php echo $arr['stime']; ?></td>
                                <td><?php echo $arr['etime']; ?></td>
                                <?php if($arr['check'] == 0): ?>
                                    <td><lable style="color:red">异常</lable></td>
                                <?php else: ?>
                                    <td></td>
                                <?php endif; ?>
                                <?php if($arr['is_anomaly'] == 1): ?>
                                    <?php if(substr($key,0,6) < substr($today,0,6) AND substr($today,-2) > "05"): ?>
                                    <td><a class="btn btn-success btn-xs" href='?/attendance_user/anoshow/?dwDate=<?php echo $key; ?>'  data-toggle="modal" data-target="#myModal">显示</a></td>
                                    <?php else: ?>
                                    <td><a class="btn btn-info btn-xs" href="?/anomaly_user/add/?date=<?php echo $key; ?>" target="_blank">添加</a>&nbsp;<a class="btn btn-success btn-xs" href='?/attendance_user/anoshow/?dwDate=<?php echo $key; ?>'  data-toggle="modal" data-target="#myModal">显示</a></td>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if(substr($key,0,6) < substr($today,0,6) AND substr($today,-2) > "05"): ?>
                                    <td>无</td>
                                    <?php else: ?>
                                    <td><a class="btn btn-info btn-xs" href="?/anomaly_user/add/?date=<?php echo $key; ?>" target="_blank">添加</a></td>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->

    <div class="modal fade" id="myModal">
	    <div class="modal-dialog" style="width:800px;">
		    <div class="modal-content">
			    <div class="modal-header">
			    </div>
			    <div class="modal-body">
			    </div>
		    </div>
	    </div>
    </div>
