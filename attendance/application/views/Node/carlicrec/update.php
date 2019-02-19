        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="page-header">车牌管理/更新车牌</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form   id="f1" name="f1" class="form-horizontal " action="?/carlicrec/update_cfm/" method="post">
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">车牌ID</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入车牌ID" name="uVehicleID" value="<?php echo $vid; ?>" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">姓名</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入姓名" name="strName" value="<?php echo $strName; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">车牌号</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入车牌号,如浙F88888" name="strPlateID" value="<?php echo $strPlateID; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label" for=""></label>
                            <div class="col-lg-10 col-xs-8">
                                <input  type="submit" name="add" value="保存" class="btn btn-primary" />
                                <a type="button"  class="btn btn-success" href="?/carlicrec/">取消</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


