<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">更新人员管理</h4>
</div>
<div class="modal-body">
	<form name="f1" id="f1" class="form-horizontal" action="?/owners/update_cfm/?id=<?php echo $ownid; ?>" method="post">
		<div class="form-group">
			<label class="col-xs-3 control-label">新人员</label>
			<div class="col-xs-8">
				<input class="form-control" type="text" placeholder="请输入人员名" name="newname" value=<?php echo $owner; ?> required />
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-3 control-label">所属部门</label>
			<div class="col-xs-8">
				<select name='newdepname' class="form-control">
					<?php foreach ($departments as $deps_arr): ?>
						<?php if($depid == $deps_arr['depid']): ?>
							<option value='<?php echo $deps_arr['depid']?>' selected><?php echo $deps_arr['depname']?></option>
						<?php else: ?>
							<option value='<?php echo $deps_arr['depid']?>'><?php echo $deps_arr['depname']?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-3 control-label" for=""></label>
			<div class="col-xs-5">
				<input  type="submit" name="update" value="保存" class="btn btn-primary" />
				<button type="button" class="btn btn-danger" data-dismiss="modal" >取消</button>
			</div>
		</div>

</form>
</div>

<script>
$(document).ready(function() {
        $('#f1').ajaxForm(function(data) {
                if(data == '1'){
                        swal({
                        title: "成功!",
                        text: "已成功修改数据！",
                        type: "success",
                        },
                        function(){window.location="?/owners/";}
                        );
                }else{
                        swal({
                        title: "失败!",
                        text: "请勿输入重复的数据！",
                        type: "error",
                        }
                        );
                }

        });
});
</script>

