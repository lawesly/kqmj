<style>
 th {font-size:12px;}
 td {font-size:12px;}
</style>

<div class="panel panel-default">
<div class="panel-body">
<form   name="f1" class="form-horizontal " action="?/anomaly_sta/search/" method="post">
        <div class="form-group">
                <label class="col-xs-1 control-label">*日期(起)</label>
                <div class="col-xs-5">
                        <input class="form-control input-datepicker" type="text" id="fromdate"  name="fromdate"  value="<?php echo $fromdate; ?>" required/>
                </div>
                <label class="col-xs-1 control-label">*(止)</label>
                <div class="col-xs-5">
                        <input class="form-control input-datepicker" type="text"  id="todate" name="todate" value="<?php echo $todate; ?>" required/>
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-1 control-label">确认情况</label>
                <div class="col-xs-5">
                        <select class="form-control" name="isack" id='isack'>
                                <?php foreach($isacks as $key=>$val): ?>
				<?php if($isack == $key): ?>
                                <option value="<?php echo $key; ?>" selected><?php echo $val; ?></option>
				<?php else: ?>
                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
				<?php endif; ?>
                                <?php endforeach; ?>
                        </select>
                </div>
        </div>
	<div class="form-group">
                <div class="col-xs-1">
                        <input  type="submit"  value="查询" class="btn btn-primary" />
                </div>
                <div class="col-xs-1">
                        <input type='button'  id="export"  class="btn btn-success" value="导出"/>
                </div>
                <div class="col-xs-1">
                        <input type='button'  id="export"  class="btn btn-danger" value="清空" onclick="truncate()"/>
                </div>
	
        </div>
</form>
</div>
</div>

<table class="table table-bordered table-hover definewidth m10" id="tab" >
	<thead>
		<tr>
			<th>手机号码</th>
			<th>人员名称</th>
			<th>部门</th>
			<th>考勤日期</th>
			<th>星期</th>
			<th>上班时间</th>
			<th>下班时间</th>
			<th>状态</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($attendance as $arr): ?>
			<tr>
				<td><a href='?/attendance/mjshow/?phoneNum=<?php echo $arr['phoneNum']; ?>&swipedate=<?php echo $arr['dwDate']; ?>'  data-toggle="modal" data-target="#myModal"><?php echo $arr['phoneNum']; ?></a></td>
				<td><a href='?/attendance/zkshow/?phoneNum=<?php echo $arr['phoneNum']; ?>&dwDate=<?php echo $arr['dwDate']; ?>'  data-toggle="modal" data-target="#myModal"><?php echo $arr['Name']; ?></a></td>
				<td><?php echo $arr['depname']; ?></td>
				<td><?php echo $arr['dwDate']; ?></td>
				<td><?php echo $arr['dwWeek']; ?></td>
				<td><?php echo $arr['stime']; ?></td>
				<td><?php echo $arr['etime']; ?></td>
				<?php if($arr['isack'] == 0): ?>
					<td><font color='red'>未确认</font></td>
				<?php else: ?>
					<td><font color='blue'>已确认</font></td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
		
	</tbody>
</table>

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
$("#export").click(function(){
        var fromdate=document.getElementById("fromdate").value;
        var todate=document.getElementById("todate").value;
        var isack=document.getElementById("isack").value;
        var url = "?/anomaly_sta/export/";
        url = url + "?from=" + fromdate + "&to=" + todate + "&isack=" + isack;
        window.location=url;
});
</script>
<script>
$(document).ready(function() {
    $('#tab').DataTable({
        "order": [[ 0, "asc" ]],
	"iDisplayLength": 50,
//        "columnDefs": [
//            {"orderable": false, "targets": 2},
//            {"orderable": false, "targets": 3}
//        ],
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

<script type="text/javascript">
      $(document).ready(function() {
      $(".input-datepicker").datepicker();
  });
</script>

<script>

$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});

</script>

<script>
function truncate(){
    swal({
        title: "您确定要清除吗？",
        text: "您确定要清除这些数据？",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: false,
        confirmButtonText: "是的，我要清除",
        confirmButtonColor: "#ec6c62"
    }, function() {
        $.ajax({
            url: "?/anomaly_sta/truncate/",
            type: "GET"
        }).done(function(data) {
            if(data == "1"){
                    swal({
                        title: "操作成功!",
                        text: "已成功清除数据！",
                        type: "success",
                        }
                    );
            }else{
                    swal("失败!","操作失败!","error");
            }
        }).error(function(data) {
            swal("失败", "操作失败了!", "error");
        });
    });
}

</script>
