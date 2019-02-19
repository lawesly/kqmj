        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="page-header">添加作息</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form   id="f2" name="f2" class="form-horizontal " action="?/wr/add_cfm/" method="post">
                        <div class="form-group">
                            <label for="dwDate" class="col-lg-2 col-xs-4 control-label">日期</label>
                            <div class="col-lg-10 col-xs-8">
                                <input  class="form-control input-datepicker" type="text" name="dwDate" id="dwDate" readonly/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label" for="type">类型</label>
                            <div class="col-lg-10 col-xs-8">
                                <select name='type' class='form-control' id="type">
                                    <option value="0">上班</option>
                                    <option value="1">休息</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="stime" class="col-lg-2 col-xs-4 control-label">上班时间</label>
                            <div class="col-lg-4 col-xs-8">
                                <input  class="form-control timepicker" type="text" name="stime" id="stime" value="08:30" readonly/>
                            </div>
                            <label for="stime_tx" class="col-lg-2 col-xs-4 control-label">是否弹性</label>
                            <div class="col-lg-4 col-xs-8">
                                <select name='stime_tx' id="stime_tx" class='form-control'>
                                    <option value="0">是</option>
                                    <option value="1">否</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="etime" class="col-lg-2 col-xs-4 control-label">下班时间</label>
                            <div class="col-lg-4 col-xs-8">
                                <input  class="form-control timepicker" type="text" name="etime" id="etime" value="17:00" readonly/>
                            </div>
                            <label for="etime_tx" class="col-lg-2 col-xs-4 control-label">是否弹性</label>
                            <div class="col-lg-4 col-xs-8 ">
                                <select name='etime_tx' id="etime_tx" class='form-control'>
                                    <option value="0">是</option>
                                    <option value="1">否</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="des" class="col-lg-2 col-xs-4 control-label">描述</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" name="des" id="des" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label" for=""></label>
                            <div class="col-lg-10 col-xs-8">
                                <input  type="submit" name="add" value="保存" class="btn btn-primary" />
                                <a type="button" href="?/wr/" class="btn btn-warning">取消</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
