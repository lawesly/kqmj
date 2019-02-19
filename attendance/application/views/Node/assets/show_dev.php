<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">查看设备</h4>
</div>
<div class="modal-body">
<table class="table table-bordered table-hover m10">
<tr>
<th>编号</th>
<th>类别</th>
<th>品牌</th>
<th>型号</th>
<th>状态</th>
<th>日期</th>
<th>价格</th>
<th>编辑</th>
<th>删除</th>
</tr>
<?php foreach ($devices as $devs_arr): ?>
<tr>
<td><?php echo $devs_arr['devname']; ?></td>
<td><?php echo $devs_arr['sorname']; ?></td>
<td><?php echo $devs_arr['braname']; ?></td>
<td><?php echo $devs_arr['model']; ?></td>
<td><?php echo $devs_arr['status']; ?></td>
<td><?php echo $devs_arr['purdate']; ?></td>
<td><?php echo $devs_arr['purprice']; ?></td>
<td><a href='?/devices/update/?id=<?php echo $devs_arr['devid']; ?>'>编辑</a></td>
<td><a href='?/devices/del/?id=<?php echo $devss_arr['devid']; ?>' onclick='return del_sure()'>删除</a></td>
</tr>
<?php endforeach; ?>

</table>
<?php
echo "<div style='float: left;color: green;'>合计:<b>".$totalprice."</b>元</div>";
?>
</div>
