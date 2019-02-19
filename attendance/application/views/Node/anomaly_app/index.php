        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">异常申请</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-xs-6">
                    <select name='display' class='form-control' id="display" onchange="changeMonth(this.value)">
                        <?php foreach($months as $month): ?>
                            <?php if($month == $display): ?>
                                <option value="<?php echo $month; ?>" selected><?php echo $month; ?></option>
                            <?php else: ?>
                                <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-xs-6">
                    <select name='showsure' class='form-control' id="showsure" onchange="changeSure(this.value)">
                        <?php if($sure == 0): ?>
                            <option value="0" selected>未确认</option>
                            <option value="1">已确认</option>
                        <?php else: ?>
                            <option value="0">未确认</option>
                            <option value="1" selected>已确认</option>
                        <?php endif; ?>
                    </select>
                </div>
                
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" method="post" action="?/anomaly_app/cfm/" id="form1">
                        <table width="100%" class="table table-bordered table-hover m10" id="tab">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="c0" id="all" value="全选" onclick="change()"></th>
                                <th>ID</th>
                                <th>申请人</th>
                                <th>类型</th>
                                <th>日期</th>
                                <th>异常时间</th>
                                <th>统计时间</th>
                                <th>说明</th>
                                <th>描述</th>
                                <th>证明人/领导</th>
                                <th>证明人/领导确认</th>
                                <th>操作员确认</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($anomaly as $arr): ?>
                                <tr>
                                    <td><input type="checkbox" value='1' name="c1" id="<?php echo $arr['id']; ?>" onclick="add(<?php echo $arr['id']; ?>)"></td>
                                    <td><?php echo $arr['id']; ?></td>
                                    <td><a href='?/attendance/zkshow/?phoneNum=<?php echo $arr['phoneNum']; ?>&dwDate=<?php echo $arr['dwDate']; ?>' data-toggle="modal" data-target="#myModal"><?php echo $arr['Name']; ?></a></td>
                                    <td><?php echo $arr['type']; ?></td>
                                    <td><a href='?/attendance/mjshow/?phoneNum=<?php echo $arr['phoneNum']; ?>&swipedate=<?php echo $arr['dwDate']; ?>' data-toggle="modal" data-target="#myModal"><?php echo $arr['dwDate']; ?></a></td>
                                    <td><?php echo $arr['apptime']; ?></td>
                                    <td><?php echo $arr['durtime']; ?></td>
                                    <td><?php echo $arr['type_sub']; ?></td>
                                    <td><?php echo $arr['reason']; ?></td>
                                    <td><?php echo $arr['invite']; ?></td>
                                    <td><?php echo $arr['sure']; ?></td>
                                    <?php if($arr['isack'] == '否'):?>
                                        <td id="<?php echo 'sure'.$arr['id'].'e'; ?>"><font color="red"><?php echo $arr['isack']; ?></font></td>
                                    <?php else: ?>
                                        <td id="<?php echo 'sure'.$arr['id'].'e'; ?>"><?php echo $arr['isack']; ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <fieldset id="fdisabled" disabled>
                            <div class="form-group form-group-sm">
                                <div class="col-xs-6 col-md-2">
                                    <select class="form-control" name="action" id="action">
                                        <option value='sure'>确认</option>
                                        <option value='suremark'>确认+标记</option>
                                        <option value='cancel'>取消</option>
                                    </select>
                                </div>
                                <input class="btn btn-success btn-sm" id="sure" type="submit" value="确定(0)">
                            </div>
                        </fieldset>
                        <input type='hidden' name="ids" id="ids" value="0">
                        <input type='hidden' name="nums" id="nums" value="0">
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"></div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

        <script>
            var display="<?php echo $display; ?>";
            var sure="<?php echo $sure; ?>";
        </script>


