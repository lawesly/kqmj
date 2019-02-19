<link href="/application/views/assets/chosen/chosen.css" rel="stylesheet" type="text/css" />
<script src="/application/views/js/delete.js"></script>
<script>
function search_page(str){
  var url="?/devices/search/?page=" + str;
  document.getElementById("f2").action=url;
  document.getElementById('f2').submit();
}
</script>

<p><button type="button" class="btn btn-success" onclick="window.location='?/devices/add/'">添加设备</button>
<button class='btn btn-info' type='button' onclick="window.location='<?php echo $export; ?>'" >导出</button>&nbsp;&nbsp;</p>

<form   name="f1" class="form-horizontal " action="?/devices/search/" method="post">
        <div class="form-group">
                <label class="col-xs-1 control-label">设备标签</label>
                <div class="col-xs-2">
                        <input class="form-control" type="text"  name="devname" value="<?php echo $searchdevname; ?>"/>
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
                        <input  type="submit" name="search" value="查询" class="btn btn-primary" />
                </div>
        </div>
</form>
<form   id="f2" name="f2"  action="?/devices/search/" method="post">
	<input type="hidden" name='devname' value="<?php echo $searchdevname; ?>" >
	<input type="hidden" name='depid' value="<?php echo $searchdepid; ?>" >
	<input type="hidden" name='braid' value="<?php echo $searchbraid; ?>" >
	<input type="hidden" name='sortid' value="<?php echo $searchsortid; ?>" >
	<input type="hidden" name='devname' value="<?php echo $searchdevname; ?>" >
	<input type="hidden" name='owner' value="<?php echo $searchowner; ?>" >
	<input type="hidden" name='model' value="<?php echo $searchmodel; ?>" >
	<input type="hidden" name='status' value="<?php echo $searchstatus; ?>" >
	<input type="hidden" name='supplier' value="<?php echo $searchsupplier; ?>" >
	<input type="hidden" name='frompurdate' value="<?php echo $searchfrompurdate; ?>" >
	<input type="hidden" name='topurdate' value="<?php echo $searchtopurdate; ?>" >
</form>
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
			<td><a href="?/devices/details/?id=<?php echo $devs_arr['devid'];?>"><?php echo $devs_arr['devname'] ?></a></td>
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
			    <a href="?/devices/del/?id=<?php echo $devs_arr['devid'];?>" onclick='return del_sure()' class="btn btn-xs btn-danger update" style="margin-left:10">删除</a></td>
		</tr>
	<?php endforeach; ?>
	</tbody>

</table>
<div class='inline pull-right page'>
        <?php echo $pagefoot; ?>
</div>

<script type="text/javascript" src="/application/views/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/application/views/assets/chosen/chosen.jquery.js"></script>
    <script type="text/javascript">
      $(".chzn-select").chosen();
      $(document).ready(function() {
      $(".input-datepicker").datepicker();
  });
    </script>
