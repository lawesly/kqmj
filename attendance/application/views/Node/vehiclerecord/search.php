        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="page-header">车辆通行记录</h5>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form   name="f1" class="form-horizontal " action="?/vehiclerecord/search/" method="post">
                                <div class="form-group">
                                    <label class="col-lg-1 col-xs-4 control-label">*(起)</label>
                                    <div class="col-lg-5 col-xs-8">
                                        <input class="form-control input-datepicker" type="text" id="datepicker"  name="fromdate"  value="<?php echo $fromdate; ?>"  readonly/>
                                    </div>
                                    <label class="col-lg-1 col-xs-4 control-label">*(止)</label>
                                    <div class="col-lg-5 col-xs-8">
                                        <input class="form-control input-datepicker" type="text"  name="todate" value="<?php echo $todate; ?>" readonly/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-1 col-xs-4">
                                        <input  type="submit"  value="查询" class="btn btn-primary" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <table width="100%" class="table table-bordered table-hover definewidth m10" id="tab" >
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>手机号</th>
                            <th>姓名</th>
                            <th>车牌</th>
                            <th>时间</th>
                            <th>类型</th>
                            <th>状态</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $arr): ?>
                            <tr>
                                <td><?php echo $arr['id']; ?></td>
                                <td><?php echo $arr['phoneNum']; ?></td>
                                <td><?php echo $arr['name']; ?></td>
                                <td><?php echo $arr['carLicense']; ?></td>
                                <td><?php echo $arr['operTime']; ?></td>
                                <td><?php echo $arr['type']; ?></td>
                                <td><?php echo $arr['code']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



<script>



</script>
