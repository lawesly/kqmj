<div class="panel panel-default">
<div class="panel-body">
<form   name="f1" class="form-horizontal " action="?/anomaly_sta/search/" method="post">
        <div class="form-group">
                <label class="col-xs-1 control-label">*日期(起)</label>
                <div class="col-xs-5">
                        <input class="form-control input-datepicker" type="text" id="fromdate"  name="fromdate"  required/>
                </div>
                <label class="col-xs-1 control-label">*(止)</label>
                <div class="col-xs-5">
                        <input class="form-control input-datepicker" id="todate" type="text"  name="todate" required/>
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-1 control-label">确认情况</label>
                <div class="col-xs-5">
			<select class="form-control" name="isack" id='isack'>
				<?php foreach($isacks as $key=>$val): ?>
				<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
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
查询数据显示处

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

