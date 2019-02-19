        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">用户管理</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href='?/users/add/' >添加用户</a>
                    <a class="btn btn-info" href="?/users/export/">导出</a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <table width="100%" class="table table-bordered table-hover definewidth m10" id="tab" >
                        <thead>
                        <tr>
                            <th>用户ID</th>
                            <th>用户名</th>
                            <th>所属组</th>
                            <th>手机号码</th>
                            <th>真实姓名</th>
                            <th>邮箱</th>
                            <th>车牌</th>
                            <th>状态</th>
                            <th>最后登录时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $arr): ?>
                            <tr>
                                <td><?php echo $arr['userid']; ?></td>
                                <td><?php echo $arr['username']; ?></td>
                                <td><?php echo $arr['groupname']; ?></td>
                                <td><?php echo $arr['phoneNum']; ?></td>
                                <td><?php echo $arr['realname']; ?></td>
                                <td><?php echo $arr['mail']; ?></td>
                                <td><?php echo $arr['carlicense']; ?></td>
                                <td><?php echo $arr['status']; ?></td>
                                <td><?php echo $arr['lastlogin']; ?></td>
                                <?php if($arr['userid'] == 1): ?>
                                    <td></td>
                                <?php else: ?>
                                    <td>
                                        <a href='?/users/update/?id=<?php echo $arr['userid']; ?>' class="btn btn-xs btn-primary update" style="margin-left:10px" >更新</a>
                                        <button onclick="delcfm('<?php echo $arr['userid']; ?>')" class="btn btn-xs btn-danger update" style="margin-left:10px">删除</button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



<div class="modal fade" id="myModal" data-backdrop="static" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>



