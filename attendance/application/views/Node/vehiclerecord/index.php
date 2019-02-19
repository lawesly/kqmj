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
                                        <input class="form-control input-datepicker" type="text" id="datepicker"  name="fromdate"  value="<?php echo $firstday; ?>"  readonly/>
                                    </div>
                                    <label class="col-lg-1 col-xs-4 control-label">*(止)</label>
                                    <div class="col-lg-5 col-xs-8">
                                        <input class="form-control input-datepicker" type="text"  name="todate" value="<?php echo $today; ?>" readonly/>
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
                查询数据显示处
            </div>
        </div>


