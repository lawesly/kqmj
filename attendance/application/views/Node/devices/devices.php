<p>
	<a  href="?/devices/add/" class="btn btn-success"  data-toggle="modal" data-target="#myModal" >添加设备</a>
	<button class='btn btn-info' type='button' onclick="window.location='<?php echo $export; ?>'" >导出</button>&nbsp;&nbsp;
</p>

<form   name="f1" class="form-horizontal " action="?/devices/search/" method="post">
        <div class="form-group">
                <label class="col-xs-1 control-label">设备标签</label>
                <div class="col-xs-2">
                        <input class="form-control" type="text"  name="devname" />
                </div>
                <label class="col-xs-1 control-label">部门名称</label>
                <div class="col-xs-2">
			<select name='depid' id='dep' class='form-control chzn-select' >
                        	<option value='0'>全部</option>
                        	<?php foreach($departments as $deps_arr): ?>
                        		<option value='<?php echo $deps_arr['depid']; ?>'><?php echo $deps_arr['depname']; ?></option>
                        	<?php endforeach; ?>
                	</select>
                </div>
                <label class="col-xs-1 control-label">品牌名称</label>
                <div class="col-xs-2">
			<select name = 'braid' class='form-control chzn-select'>
                        	<option value='0'>全部</option>
                        	<?php foreach($brands as $bras_arr): ?>
                        		<option value='<?php echo $bras_arr['braid']; ?>'><?php echo $bras_arr['braname']; ?></option>
                        	<?php endforeach; ?>
                	</select>
              
                </div>
                <label class="col-xs-1 control-label">类别名称</label>
                <div class="col-xs-2">
			<select name = 'sortid' class='form-control chzn-select'>
                        	<option value='0'>全部</option>
                        	<?php foreach($sorts as $sors_arr): ?>
                        		<option value='<?php echo $sors_arr['sortid']; ?>'><?php echo $sors_arr['dsortname']; ?></option>
                        	<?php endforeach; ?>
                	</select>

		</div>
        </div>
        <div class="form-group">
		<label class="col-xs-1 control-label">使用人</label>
                <div class="col-xs-2">
                        <input class="form-control" type="text"  name="owner" />
                </div>
                <label class="col-xs-1 control-label">型号</label>
                <div class="col-xs-2">
                        <input class="form-control" type="text"  name="model" />
                </div>
		<label class="col-xs-1 control-label">状态</label>
		<div class="col-xs-2">
			<select name='status' class='form-control'>
                        	<option value='staall'>全部</option>
                        	<option value='在用'>在用</option>
                        	<option value='闲置'>闲置</option>
                        	<option value='报废'>报废</option>
                </select>
		</div>
                <label class="col-xs-1 control-label">备注</label>
                <div class="col-xs-2">
                        <input class="form-control" type="text"  name="supplier" />
                </div>
	</div>
        <div class="form-group">
		<label class="col-xs-1 control-label">购买日期(起)</label>
                <div class="col-xs-2">
                        <input class="form-control input-datepicker" type="text" id="datepicker"  name="frompurdate"  />
                </div>
		<label class="col-xs-1 control-label">(止)</label>
                <div class="col-xs-2">
                        <input class="form-control input-datepicker" type="text"  name="topurdate"/>
                        <!--<input class="form-control" type="text"  name="topurdate" onfocus='c.showMoreDay = false;c.show(this);' />-->
                </div>
                <div class="col-xs-2">
                        <input  type="submit"  value="查询" class="btn btn-primary" />
                </div>
        </div>
</form>
<div id="devices_tab">
<table class='table table-bordered table-hover definewidth m10' id="tabs">
	<thead>
		<tr>
			<th>设备标签</th>
			<th>所属部门</th>
			<th>使用人</th>
			<th>类别名</th>
			<th>品牌名</th>
			<th>购买日期</th>
			<th>购买价格</th>
			<th>最后修改时间</th>
			<th>状态</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($devices as $devs_arr): ?>
		<tr>
			<td><a href="?/devices/details/?id=<?php echo $devs_arr['devid'];?>" data-toggle="modal" data-target="#myModal1"><?php echo $devs_arr['devname'] ?></a></td>
			<td><?php echo $devs_arr['depname'] ?></td>
			<td><?php echo $devs_arr['owner'] ?></td>
			<td><?php echo $devs_arr['sortname'] ?></td>
			<td><?php echo $devs_arr['braname'] ?></td>
			<td><?php echo $devs_arr['purdate'] ?></td>
			<td><?php echo $devs_arr['purprice'] ?></td>
			<td><?php echo $devs_arr['lastchange'] ?></td>
			<td><?php echo $devs_arr['status'] ?></td>
			<td><a href="?/devices/update/?id=<?php echo $devs_arr['devid'];?>" class="btn btn-xs btn-primary update" style="margin-left:10">更新</a>
			    <a href="?/devices/copy/?id=<?php echo $devs_arr['devid'];?>" class="btn btn-xs btn-success update" style="margin-left:10">复制</a>
			    <a onclick="delcfm('<?php echo $devs_arr['devid'];?>')" class="btn btn-xs btn-danger update" style="margin-left:10">删除</a></td>
		</tr>
	<?php endforeach; ?>
	</tbody>

</table>
</div>

<div class='inline pull-right page'>
        <?php echo $pagefoot; ?>
</div>

<script type="text/javascript">
      $(".chzn-select").chosen();
      $(document).ready(function() {
      $(".input-datepicker").datepicker();
  });
</script>
<div class="modal fade" id="myModal" data-backdrop='static'>
        <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">
                        </div>
                </div>
        </div>
</div>

<div class="modal fade" id="myModal1">
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
//$(".delButton").click(function(){
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
            url: "?/devices/del/?id=" + id,
            type: "GET"
        }).done(function(data) {
            if(data == "1"){
                    swal({
                        title: "操作成功!",
                        text: "已成功删除数据！",
                        type: "success",
                        },
                        function(){window.location="?/devices/";}
                    );
            }else{
                    swal("失败!","删除操作失败了!","error");
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

