        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">请假统计</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" method="post" action="?/qjstatistics/index/" id="form1">
                        <div class="form-group">
                            <div class="col-xs-2" style="width:130px">
                                <select name='display' class='form-control' id="display">
                                    <?php foreach($months as $month): ?>
                                        <?php if($month == $display): ?>
                                            <option value="<?php echo $month; ?>" selected><?php echo $month; ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-xs-2" style="width:150px">
                                <select name='depname' class='form-control' id="depname">
                                    <?php foreach($depnames as $depname): ?>
                                        <?php if($depname['depname'] == $depdisplay): ?>
                                            <option value="<?php echo $depname['depname']; ?>" selected><?php echo $depname['depname']; ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $depname['depname']; ?>"><?php echo $depname['depname']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-2">
                                    <input  type="submit"  value="查询" class="btn btn-primary" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table width="100%" class="table table-bordered table-hover definewidth m10" id="tab" >
                        <thead>
                        <tr>
                            <th>姓名</th>
                            <th>部门</th>
                            <th>工作天数</th>
                            <th>晚归次数</th>
                            <th>加班小时</th>
                            <th>加班次数</th>
                            <th>调休小时</th>
                            <th>调休次数</th>
                            <th>请假小时</th>
                            <th>请假次数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tongji as $ar1): ?>
                            <tr>
                                <td><?php echo $ar1['Name']; ?></td>
                                <td><?php echo $ar1['depname']; ?></td>
                                <td><?php echo $ar1['workdays']; ?></td>
                                <td><?php echo $ar1['wgdays']; ?></td>
                                <td><?php echo $ar1['jbhours']; ?></td>
                                <td><?php echo $ar1['jbdays']; ?></td>
                                <td><?php echo $ar1['txhours']; ?></td>
                                <td><?php echo $ar1['txdays']; ?></td>
                                <td><?php echo $ar1['qjhours']; ?></td>
                                <td><?php echo $ar1['qjdays']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



<div class="modal fade" id="myModal">
    <div class="modal-dialog" style="width:800px;">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>



