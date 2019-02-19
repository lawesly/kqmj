<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">异常说明</h4>
</div>
<div class="modal-body">
    <table class="table table-bordered table-hover m10">
        <tr>
            <th>ID</th>
            <th>邀请人</th>
            <th>类型</th>
            <th>异常日期</th>
            <th>异常时间</th>
            <th>说明</th>
            <th>补充</th>
            <th>确认情况</th>
            <th>操作</th>
        </tr>
        <tr>
            <td><?php echo $anoID; ?></td>
            <td><?php echo $Name; ?></td>
            <td><?php echo $type; ?></td>
            <td><?php echo $dwDate; ?></td>
            <td><?php echo $durtime; ?></td>
            <td><?php echo $type_sub; ?></td>
            <td><?php echo $reason; ?></td>
            <td><?php echo $isack1; ?></td>
            <td>
                <?php if($isack == 0): ?>
                    <button onclick="cfm('<?php echo $id; ?>',1)" class="btn btn-xs btn-success update" >确认</button>
                <?php else: ?>
                    <button onclick="cfm('<?php echo $id; ?>',0)" class="btn btn-xs btn-danger update" >取消确认</button>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>

<script>
function cfm(id,ack){
        $.ajax({
            url: "?/notice_user/cfm/?id=" + id + "&ack=" + ack,
            type: "GET"
        }).done(function(data) {
            if(data === "1"){
                    swal({
                        title: "操作成功!",
                        text: "已成功更新数据！",
                        type: "success"
                        },
                        function(){$(window.location="?/notice_user/")}
                    );
            }else{
                    swal("失败!","操作失败!","error");
            }
        }).error(function() {
            swal("失败", "操作失败了!", "error");
        });
}


</script>

