        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">异常通知</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-6">
                    <select name='display' class='form-control' id="display" onchange="changeMonth(this.value)">
                        <?php if($display == 0): ?>
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
                    <form class="form-horizontal" method="post" action="?/notice_user/cfm/" id="form1">
                        <table width="100%" class="table table-bordered table-hover m10" id="tab">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="c0" id="all" value="全选" onclick="change()"></th>
                                <th>ID</th>
                                <th>申请人</th>
                                <th>类型</th>
                                <th>日期</th>
                                <th>上班时间</th>
                                <th>下班时间</th>
                                <th>异常时间</th>
                                <th>总时间</th>
                                <th>说明</th>
                                <th>描述</th>
                                <th>证明人/领导</th>
                                <th>证明人/领导确认</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($notice as $arr): ?>
                                <tr>
                                    <td><input type="checkbox" value='1' name="c1" id="<?php echo $arr['id']; ?>" onclick="add(<?php echo $arr['id']; ?>)"></td>
                                    <td><?php echo $arr['anoID']; ?></td>
                                    <td><a href='?/notice_user/zkshow/?phoneNum=<?php echo $arr['phoneNum']; ?>&dwDate=<?php echo $arr['dwDate']; ?>' data-toggle="modal" data-target="#myModal"><?php echo $arr['Name']; ?></a></td>
                                    <td><?php echo $arr['type']; ?></td>
                                    <td><a href='?/notice_user/mjshow/?phoneNum=<?php echo $arr['phoneNum']; ?>&swipedate=<?php echo $arr['dwDate']; ?>' data-toggle="modal" data-target="#myModal"><?php echo $arr['dwDate']; ?></a></td>
                                    <td><?php echo $arr['onwork']; ?></td>
                                    <td><?php echo $arr['offwork']; ?></td>
                                    <td><font color='red'><?php echo $arr['durtime']; ?></font></td>
                                    <td><?php echo $arr['sumtime']; ?></td>
                                    <td><?php echo $arr['type_sub']; ?></td>
                                    <td><?php echo $arr['reason']; ?></td>
                                    <td><?php echo $arr['invite']; ?></td>
                                    <td><?php echo $arr['sure']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <fieldset id="fdisabled" disabled>
                            <div class="form-group form-group-sm">
                                <div class="col-xs-6 col-md-2">
                                    <select class="form-control" name="action" id="action">
                                        <option value='sure'>确认</option>
                                        <option value='cancel'>取消确认</option>
                                    </select>
                                    <lable for="action"></lable>
                                </div>
                                <input class="btn btn-success btn-sm" id="sure" type="submit" value="确定(0)">
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
            <div class="modal-header">

            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
        

