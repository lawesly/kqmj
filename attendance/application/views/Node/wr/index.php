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
                            <p><a class="btn btn-success" href='?/wr/add/' >添加作息</a></p>
                            <p>常规作息时间:</p>
                            <p>10.01～04.30 08:30～12:00 13:00～17:00</p>
                            <p>05.01～09.30 08:30～12:00 13:30～17:30</p>
                            <p>周四20点下班,周末双休</p>
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
                            <th>日期</th>
                            <th>工作/休息</th>
                            <th>工作时间</th>
                            <th>上班弹性</th>
                            <th>下班弹性</th>
                            <th>描述</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($wrs as $arr): ?>
                            <tr>
                                <td><?php echo $arr['dwDate']; ?></td>
                                <td><?php echo $arr['type']; ?></td>
                                <td><?php echo $arr['worktime']; ?></td>
                                <td><?php echo $arr['stime_tx']; ?></td>
                                <td><?php echo $arr['etime_tx']; ?></td>
                                <td><?php echo $arr['des']; ?></td>
                                <td>
                                    <button onclick="delcfm('<?php echo $arr['id']; ?>')" class="btn btn-xs btn-danger" >删除</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
