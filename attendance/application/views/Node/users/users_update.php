        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="page-header">用户管理/更新用户</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form   id="f1" name="f1" class="form-horizontal " action="?/users/update_cfm/?id=<?php echo $uid; ?>" method="post">
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">登录名</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入登录名" name="username" value="<?php echo $username; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">密码</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="password" placeholder="请输入密码" name="passwd" value="111111" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">手机号</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入手机号" name="phoneNum" value="<?php echo $phoneNum; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">真实姓名</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入真实姓名" name="realname" value="<?php echo $realname; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">邮箱</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入邮箱" name="mail" value="<?php echo $mail; ?>"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">车牌</label>
                            <div class="col-lg-10 col-xs-8">
                                <input class="form-control" type="text" placeholder="请输入车牌" name="carlicense" value="<?php echo $carlicense; ?>"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label" for="groupid">所属组</label>
                            <div class="col-lg-10 col-xs-8">
                                <select name='groupid' class='form-control' id="groupid">
                                    <?php foreach($groupArr as $key => $value): ?>
                                        <?php if($key == $groupid): ?>
                                            <option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label">状态</label>
                            <div class="col-lg-10 col-xs-8">
                                <?php if($status == 2): ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="inlineRadio1" value="2" checked/> 启用+道闸
                                    </label>
                                <?php else: ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="inlineRadio1" value="2"/> 启用+道闸
                                    </label>
                                <?php endif; ?>
                                <?php if($status == 1): ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="inlineRadio1" value="1" checked/> 启用
                                    </label>
                                <?php else: ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="inlineRadio1" value="1" /> 启用
                                    </label>
                                <?php endif; ?>
                                <?php if($status == 3): ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="inlineRadio1" value="3" checked/> 道闸
                                    </label>
                                <?php else: ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="inlineRadio1" value="3" /> 道闸
                                    </label>
                                <?php endif; ?>
                                <?php if($status == 0): ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="inlineRadio1" value="0" checked/> 禁用
                                    </label>
                                <?php else: ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="inlineRadio1" value="0" /> 禁用
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-xs-4 control-label" for=""></label>
                            <div class="col-lg-10 col-xs-8">
                                <input  type="submit" name="add" value="保存" class="btn btn-primary" />
                                <a type="button" class="btn btn-success" href="?/users/">取消</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


