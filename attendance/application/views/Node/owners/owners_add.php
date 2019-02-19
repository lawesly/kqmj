<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">添加人员管理</h4>
</div>
<div class="modal-body">
	<form   name="f1" id="f1" class="form-horizontal " action="?/owners/add_cfm/" method="post">
		<div class="form-group">
			<label class="col-xs-3 control-label">人员名</label>
			<div class="col-xs-8">
			<input class="form-control" type="text" placeholder="请输入人员名" name="owner" required />
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-3 control-label">所属部门</label>
			<div class="col-xs-8">
				<select name='depname' class="form-control">
				<?php foreach ($departments as $dep_arr): ?>
					<option value='<?php echo $dep_arr['depid'];?>'><?php echo $dep_arr['depname'] ?></option>
				<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-3 control-label" for=""></label>
			<div class="col-xs-8">
				<input  type="submit" name="add" value="添加" class="btn btn-primary"  />
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
                        text: "已成功添加数据！",
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
