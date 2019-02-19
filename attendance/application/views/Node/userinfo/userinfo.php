        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">员工信息</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href='?/userinfo/update/' >同步用户</a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <table width="100%" class="table table-bordered table-hover definewidth m10" id="tab" >
                        <thead>
                        <tr>
                            <th>编号</th>
                            <th>人员名称</th>
                            <th>卡号</th>
                            <th>手机号码</th>
                            <th>部门</th>
                            <th>员工类别</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($userinfo as $arr): ?>
                            <tr>
                                <td><?php echo $arr['dwEnrollNumber']; ?></td>
                                <td><?php echo $arr['Name']; ?></td>
                                <td><?php echo $arr['cardNum']; ?></td>
                                <td><?php echo $arr['phoneNum']; ?></td>
                                <td><?php echo $arr['depname']; ?></td>
                                <td><?php echo $arr['Privilege']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
