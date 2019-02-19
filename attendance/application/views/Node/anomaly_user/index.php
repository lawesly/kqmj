        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">异常管理</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success update" href='?/anomaly_user/add/' >添加</a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-info btn-sm " href="?/anomaly_user/?display=50">最近50条</a>
                    <a class="btn btn-primary btn-sm" href="?/anomaly_user/?display=300">最近300条</a>
                    <a class="btn btn-default btn-sm" href="?/anomaly_user/?display=0">显示全部</a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <table width="100%" class="table table-bordered table-hover m10" id="tab">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>类型</th>
                            <th>日期</th>
                            <th>异常时间</th>
                            <th>总时间</th>
                            <th>说明</th>
                            <th>描述</th>
                            <th>证明人/领导</th>
                            <th>证明人/领导确认</th>
                            <th>操作员确认</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($anomaly as $arr): ?>
                            <tr>
                                <td><?php echo $arr['id']; ?></td>
                                <td><?php echo $arr['type']; ?></td>
                                <td><?php echo $arr['dwDate']; ?></td>
                                <td><?php echo $arr['durtime']; ?></td>
                                <td><?php echo $arr['sumtime']; ?></td>
                                <td><?php echo $arr['type_sub']; ?></td>
                                <td><?php echo $arr['reason']; ?></td>
                                <td><?php echo $arr['invite']; ?></td>
                                <td><?php echo $arr['sure']; ?></td>
                                <?php if($arr['isack'] == '否'):?>
                                    <td><font color="red"><?php echo $arr['isack']; ?></font></td>
                                <?php else: ?>
                                    <td><?php echo $arr['isack']; ?></td>
                                <?php endif; ?>
                                <td>
                                    <?php if($arr['isupdate'] == 1): ?>
                                        <button class='btn btn-info btn-xs' disabled> 修改</button>
                                    <?php else: ?>
                                        <a class='btn btn-info btn-xs' href="?/anomaly_user/update/?id=<?php echo $arr['id']; ?>"  class="btn btn-xs btn-info" > 修改</a>
                                    <?php endif; ?>
                                    <button onclick="delcfm('<?php echo $arr['id']; ?>')" class="btn btn-xs btn-danger">删除</button>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->


    <div class="modal fade" id="myModal" data-backdrop="static" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
