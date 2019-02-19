        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">用户管理</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success btn-sm" href='?/carlicrec/add/'>添加车牌</a>
                    <a class="btn btn-info btn-sm" href="?/carlicrec/export/">导出</a>
                    <a class="btn btn-danger btn-sm" href='?/carlicrec/wl/?action=import' >更新白名单</a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <table width="100%" class="table table-bordered table-hover definewidth m10" id="tab" >
                        <thead>
                        <tr>
                            <th>车辆ID</th>
                            <th>车牌号码</th>
                            <th>用户ID</th>
                            <th>姓名</th>
                            <th>用户编码</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($carlicense_exp as $arr): ?>
                            <tr>
                                <td><?php echo $arr['uVehicleID']; ?></td>
                                <td><?php echo $arr['strPlateID']; ?></td>
                                <td><?php echo $arr['uCustomerID']; ?></td>
                                <td><?php echo $arr['strName']; ?></td>
                                <td><?php echo $arr['strCode']; ?></td>
                                <td>
                                    <a href='?/carlicrec/update/?id=<?php echo $arr['uVehicleID']; ?>' class="btn btn-xs btn-primary update"  style="margin-left:10px" >更新</a>
                                    <button onclick="delcfm('<?php echo $arr['uVehicleID']; ?>')" class="btn btn-xs btn-danger update" style="margin-left:10px">删除</button>
                                </td>
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



