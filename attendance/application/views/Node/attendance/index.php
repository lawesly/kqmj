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
                                        <input class="form-control input-datepicker" type="text" id="fromdate"  name="fromdate"  value="<?php echo $firstday; ?>"  readonly/>
                                    </div>
                                    <div class="col-lg-1 col-xs-4">
                                        <label>*(止)</label>
                                    </div>
                                    <div class="col-lg-5 col-xs-8">
                                        <input class="form-control input-datepicker" type="text" id="todate"  name="todate"  value="<?php echo $today; ?>"  readonly/>
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
                    查询数据显示处
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
