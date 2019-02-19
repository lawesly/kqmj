<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">异常说明</h4>
</div>
<div class="modal-body">
<table class="table table-bordered table-hover m10">
<tr>
        <th>ID</th>
        <th>类型</th>
        <th>异常时间</th>
        <th>说明</th>
        <th>证明人</th>
        <th>证明人确认</th>
        <th>最终确认</th>
        <th>操作</th>
</tr>
<?php foreach ($anomaly as $arr): ?>
<tr>
        <td><?php echo $arr['id']; ?></td>
        <td><?php echo $arr['type']; ?></td>
        <td><?php echo $arr['durtime']; ?></td>
        <td><?php echo $arr['reason']; ?></td>
        <td><?php echo $arr['invite']; ?></td>
        <td><?php echo $arr['invite_isack1']; ?></td>
        <td><?php echo $arr['isack1']; ?></td>
        <td>
		<?php if($arr['isack'] == 0): ?>
        	     	<button onclick="cfm('<?php echo $arr['id']; ?>',1)" class="btn btn-xs btn-success update" >确认</button>
		<?php else: ?>
	             	<button onclick="cfm('<?php echo $arr['id']; ?>',0)" class="btn btn-xs btn-danger update" >取消确认</button>
		<?php endif; ?>
        </td>

</tr>
<?php endforeach; ?>
</table>

</div>

<script>
function cfm(id,ack){
        $.ajax({
            url: "?/attendance/anomaly_cfm/?id=" + id + "&ack=" + ack,
            type: "GET"
        }).done(function(data) {
            if(data == "1"){
                    swal({
                        title: "操作成功!",
                        text: "已成功更新数据！",
                        type: "success",
                        },
                        function(){$("#myModal").modal('hide');}
                   //     function(){window.location="?/users/";}
                    );
            }else{
                    swal("失败!","操作失败!","error");
            }
        }).error(function(data) {
            swal("失败", "操作失败了!", "error");
        });
}

$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});

</script>

