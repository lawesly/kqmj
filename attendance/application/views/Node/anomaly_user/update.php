        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="page-header">修改异常说明</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form   id="f2" name="f2" class="form-horizontal " action="?/anomaly_user/update_cfm/" method="post">
                        <input  type="hidden" name="phoneNum" value="<?php echo $phoneNum; ?>" />
                        <input  type="hidden" name="anoid" value="<?php echo $anoid; ?>" />
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">异常日期<font color='red'>(*可多选)</font></label>
                            <div class="col-lg-10 col-xs-8">
                                <input  class="form-control input-datepicker" type="text" name="dwDate"  id="dwDate" value="<?php echo $dwDate; ?>" readonly/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">异常类型</label>
                            <div class="col-lg-10 col-xs-8">
                                <select name='type' class='form-control' id="type" onchange="show();SumTime()">
                                    <?php foreach($anomalyTypeArr as $key => $value): ?>
                                        <?php if($type == $key): ?>
                                            <option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">异常说明</label>
                            <div class="col-lg-10 col-xs-8">
                                <select name='type_sub' class='form-control' id="type_sub">
                                    <?php foreach($type_sub_arr as $sub): ?>
                                        <?php if($sub == $type_sub): ?>
                                            <option value="<?php echo $sub; ?>" selected><?php echo $sub; ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $sub; ?>"><?php echo $sub; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">异常时间</label>
                            <div class="col-lg-4 col-xs-8">
                                <input  class="form-control timepicker" type="text" id="stime" name="stime" value="<?php echo $stime; ?>" onchange="SumTime()" readonly/>
                            </div>
                            <label class="col-lg-2 col-xs-4 control-label">--</label>
                            <div class="col-lg-4 col-xs-8">
                                <input  class="form-control timepicker" type="text" id="etime" name="etime"  value="<?php echo $etime; ?>" onchange="SumTime()" readonly/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">总时间(可修改)</label>
                            <div class="col-lg-10 col-xs-8">
                                <select name='sumtime' class='form-control' id="sumtime">
                                    <?php foreach($sumTimeArr as $sumTime): ?>
                                        <?php if($sumTime==$sumtime): ?>
                                            <option value="<?php echo $sumTime; ?>" selected><?php echo $sumTime; ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $sumTime; ?>"><?php echo $sumTime; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">补充<font color='red'>(*)</font></label>
                            <div class="col-lg-10 col-xs-8">
                                <input  class="form-control" type="text" name="reason" value="<?php echo $reason; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">证明人/领导<font color='red'>(*)</font></label>
                            <div class="col-lg-10 col-xs-8">
                                <!--<select name='invite[]' id="invite" class="form-control"  multiple="multiple" required>-->
                                <select name='invite[]' id="invite" class="form-control"  multiple="multiple" required>
                                    <?php foreach($users as  $arr): ?>
                                        <?php if(in_array($arr['username'],$notice_arr)): ?>
                                            <option value="<?php echo $arr['username']; ?>" selected><?php echo $arr['realname']."_".$arr['username']; ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $arr['username']; ?>"><?php echo $arr['realname']."_".$arr['username']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label" for=""></label>
                            <div class="col-lg-10 col-xs-8">
                                <input  type="submit" name="add" value="保存" class="btn btn-primary" />
                                <a type="button"  class="btn btn-warning" href="/?/anomaly_user/">取消</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                var year = <?php echo $year; ?>;
                var month = <?php echo $month; ?>;
                var tdate = <?php echo $tdate; ?>;
                var lastmonth = <?php echo $lastmonth; ?>;
            </script>
        </div>
