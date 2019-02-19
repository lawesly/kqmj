        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">添加车牌</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form   id="f1" name="f1" class="form-horizontal " action="?/carlicense/cfm/" method="post">
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">登录名</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入登录名" name="username" disabled  value="<?php echo $username; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">新车牌</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入车牌(多个用,[英文逗号]隔开)" name="carlicense"  id="carlicense" value="<?php echo $carlicense; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label" for=""></label>
                            <div class="col-lg-10 col-xs-8">
                                <input  type="submit" name="add" value="保存" class="btn btn-primary" />
                                <button type="button" class="btn btn-danger" onclick="window.location='/?/main/'">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
