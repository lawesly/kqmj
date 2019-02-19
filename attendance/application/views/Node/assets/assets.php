<style>
#myModal  .modal-dialog{
    width: auto;
}
</style>
<form name = "search" id="asssearch"   type="get" class="form-inline definewidth m10">
	<p><button type="button" class="btn btn-info" onclick="javascript:window.location='?/assets/export/'">导出</button></p>
</form>
<table class='table table-bordered table-hover definewidth m10' border=0 id="tab">
	<thead>
		<tr>
			<th>资产标签</th>
			<th>所属部门</th>
			<th>使用人</th>
			<th>资产类别</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($assets as $asss_arr): ?>
		<tr>
			<td><a href="?/assets/show_dev/?assname=<?php echo $asss_arr['assname']; ?>"  data-toggle="modal" data-target="#myModal"><?php echo $asss_arr['assname']; ?></td>
			<td><?php echo $asss_arr['depname']; ?></td>
			<td><?php echo $asss_arr['owner']; ?></td>
			<td><?php echo $asss_arr['sorname']; ?><span class="badge"><?php echo $asss_arr['devnums']; ?></span></td>
			<td>
				<a class="btn btn-xs btn-primary" href='?/assets/update/?id=<?php echo $asss_arr['assid']; ?>' style="margin-left:10px">修改</a>
				<a class="btn btn-xs btn-danger" href='?/assets/del/?id=<?php echo $asss_arr['assid']; ?>' onclick='return del_sure()' style="margin-left:10px">删除</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>	
</table>
<div class='inline pull-right page'>
        <?php echo $pagefoot; ?>
</div>

<script type="text/javascript">
      $(".chzn-select").chosen();
</script>

<div  class="modal fade" id="myModal" tabindex="-1">
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
            url: "?/brands/del/?id=" + id,
            type: "GET"
        }).done(function(data) {
            if(data == "1"){
                    swal({
                        title: "操作成功!",
                        text: "已成功删除数据！",
                        type: "success",
                        },
                        function(){window.location="?/brands/";}
                    );
            }else{
                    swal("失败!","请先删除绑定的设备!","error");
            }
        }).error(function(data) {
            swal("失败", "删除操作失败了!", "error");
        });
    });
}

$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});

</script>

