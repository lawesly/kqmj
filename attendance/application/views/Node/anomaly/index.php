<form class="form-horizontal" method="post" action="#" id="form1">
    <input type='hidden' name="ids" id="ids" value="">
    <input type='hidden' name="nums" id="nums" value="0">
    <table class="table table-bordered table-hover m10" id="tab">
        <thead>
            <tr>
                <th>选择</th>
                <th>ID</th>
                <th>类型</th>
                <th>日期</th>
                <th>异常时间</th>
                <th>说明</th>
                <th>证明人</th>
                <th>确认情况</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($anomaly as $arr): ?>
                <tr>
                    <td><input type="checkbox" value='1' name="<?php echo $arr['id']; ?>" id="<?php echo $arr['id']; ?>" onclick="add(<?php echo $arr['id']; ?>)"></td>
                    <td><?php echo $arr['id']; ?></td>
                    <td><?php echo $arr['type']; ?></td>
                    <td><?php echo $arr['dwDate']; ?></td>
                    <td><?php echo $arr['durtime']; ?></td>
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
                <option value='restart'>确认</option>
                <option value='frestart'>取消确认</option>
            </select>
        </div>
        <input class="btn btn-success btn-sm" id="sure" type="submit" value="确定(0)">
    </fieldset>
</form>
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

<script>
$(document).ready(function() {
		$('#tab').DataTable({
			"order": [[ 2, "desc" ]],
			"iDisplayLength": 10,
			"columnDefs": [
			{"orderable": false, "targets": 0},
			//            {"orderable": false, "targets": 3}
			],
			"language": {
			"lengthMenu": "每页 _MENU_ 条记录",
			"zeroRecords": "没有找到记录",
			"info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
			"infoEmpty": "无记录",
			"infoFiltered": "(从 _MAX_ 条记录过滤)",
			"search": "搜索",
			"paginate": {
			"first":      "首页",
			"last":       "末页",
			"next":       "下页",
			"previous":   "上页"
			},
			}

		});
} );
</script>

<script>
function delcfm(id){
	swal({
		title: "您确定要删除吗？",
		text: "您确定要删除这条数据？",
		type: "warning",
		showCancelButton: true,
		closeOnConfirm: false,
		confirmButtonText: "是的，我要删除",
		confirmButtonColor: "#ec6c62"
	}, function() {
		$.ajax({
			url: "?/attendance_user/anomaly_del/?id=" + id,
			type: "GET"
		}).done(function(data) {
			if(data == "1"){
				swal({
					title: "操作成功!",
					text: "已成功删除数据！",
					type: "success",
				},
				function(){$("#myModal").modal('hide');});
			}else{
				swal("失败!","删除操作失败!","error");
			}
		}).error(function(data) {
			swal("失败", "删除操作失败了!", "error");
		});
	});
}

function add(id){
	var nums = document.getElementById("nums").value;
	nums = Number(nums);
	var ids = "0";
	if(document.getElementById(id).checked == true){
		nums = nums + 1;
		ids = ids + "," + id;
	}else{
		nums = nums - 1;
		ids = ids + "," + id;	
	}
	document.getElementById("nums").value = nums;
	document.getElementById("sure").value="确定(" + nums + ")";
	document.getElementById("ids").value=ids;
	if(nums > 0){
		document.getElementById("fdisabled").disabled=false;
	}else{
		document.getElementById("fdisabled").disabled=true;
	}
}

$("#myModal").on("hidden.bs.modal", function() {
		$(this).removeData("bs.modal");
		});

</script>

