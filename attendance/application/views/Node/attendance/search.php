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
                            <form role="form" name="f1" class="form-horizontal" action="?/attendance/search/" method="post">
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
                                        <input type='button'  id="export"  class="btn btn-success" value="导出"/>
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
                            <th>签到</th>
                            <th>签退</th>
                            <th>是否异常</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($attendance as $att): ?>
                            <?php foreach ($att as $arr): ?>
                                <tr>
                                    <td><a href='?/attendance/mjshow/?phoneNum=<?php echo $arr['phoneNum']; ?>&swipedate=<?php echo $arr['dwDate']; ?>'  data-toggle="modal" data-target="#myModal"><?php echo $arr['phoneNum']; ?></a></td>
                                    <td><a href='?/attendance/zkshow/?phoneNum=<?php echo $arr['phoneNum']; ?>&dwDate=<?php echo $arr['dwDate']; ?>'  data-toggle="modal" data-target="#myModal"><?php echo $arr['Name']; ?></a></td>
                                    <td><?php echo $arr['depname']; ?></td>
                                    <td><?php echo $arr['dwDate']; ?></td>
                                    <td><?php echo $arr['week']; ?></td>
                                    <td><?php echo $arr['type']; ?></td>
                                    <td><?php echo $arr['stime']; ?></td>
                                    <td><?php echo $arr['etime']; ?></td>
                                    <td><?php echo $arr['onwork']; ?></td>
                                    <td><?php echo $arr['offwork']; ?></td>
                                    <?php if($arr['check'] == 0): ?>
                                        <td><font color='red'>异常</font></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
            	</div>
            	
            </div>
        </div>
        <!-- /#page-wrapper -->

        <div class="modal fade" id="myModal">
        	<div class="modal-dialog">
        		<div class="modal-content">
        			<div class="modal-header"></div>
        			<div class="modal-body"></div>
        		</div>
	        </div>
        </div>

