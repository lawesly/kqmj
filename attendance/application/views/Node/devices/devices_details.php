<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">设备详情</h4>
</div>
<div class="modal-body">
<!--<form  class="definewidth m20" name="form1" id="form1">-->
<table class="table table-bordered table-hover m10">
	<tr><td width='20%' class='tableleft' >设备标签</td>
		<td><?php echo $devname; ?></td>
	</tr>
	<tr>
		<td width='20%' class='tableleft' >资产标签</td>
		<td><?php echo $assname; ?></td>
	</tr>
	<tr>
		<td width='20%' class='tableleft' >部门名称</td>
		<td><?php echo $depname; ?></td>
	</tr>
	<tr>
		<td width='20%' class='tableleft' >使用人</td>
		<td><?php echo $owner; ?></td>
	</tr>
	<tr>	
		<td width='20%' class='tableleft' >设备类别</td>
		<td><?php echo $sortname; ?></td>
	</tr>
	<tr>
		<td width='20%' class='tableleft' >品牌名称</td>
		<td><?php echo $braname; ?></td>
	</tr>
	<tr>
		<td width='20%' class='tableleft' >型号</td>
		<td><?php echo $model; ?></td>
	</tr>
	<?php if($serialnum != ""): ?>
	<tr>
		<td width='20%' class='tableleft' >序列号</td>
		<td><?php echo $serialnum; ?></td>
	</tr>
	<?php endif; ?>
        <?php if($memory != ""): ?>
        <tr>
                <td width='20%' class='tableleft' >内存</td>
                <td><?php echo $memory; ?></td>
        </tr>
        <?php endif; ?>
        <?php if($disk != ""): ?>
        <tr>
                <td width='20%' class='tableleft' >硬盘</td>
                <td><?php echo $disk; ?></td>
        </tr>
        <?php endif; ?>
        <?php if($display != ""): ?>
        <tr>
                <td width='20%' class='tableleft' >显卡</td>
                <td><?php echo $display; ?></td>
        </tr>
        <?php endif; ?>
        <?php if($mac != ""): ?>
        <tr>
                <td width='20%' class='tableleft' >mac</td>
                <td><?php echo $mac; ?></td>
        </tr>
        <?php endif; ?>
        <?php if($devdescribe != ""): ?>
        <tr>
                <td width='20%' class='tableleft' >其他信息</td>
                <td><?php echo $devdescribe; ?></td>
        </tr>
        <?php endif; ?>
	<tr>
		<td width='20%' class='tableleft' >购买日期</td>
		<td><?php echo $purdate; ?></td>
	</tr>
	<tr>
		<td width='20%' class='tableleft' >购买价格</td>
		<td><?php echo $purprice; ?></td>
	</tr>
        <?php if($supplier != ""): ?>
        <tr>
                <td width='20%' class='tableleft' >备注</td>
                <td><?php echo $supplier; ?></td>
        </tr>
        <?php endif; ?>

</table>
</div>

