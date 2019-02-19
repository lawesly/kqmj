<p><a  href="?/owners/add/" class="btn btn-success"  data-toggle="modal" data-target="#myModal" >添加人员</a></p>
<table class="table table-bordered table-hover definewidth m10" id="tab" >
    <thead>
    <tr>
        <th>编号</th>
        <th>人员名称</th>
        <th>员工类别</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($userinfo as $owns_arr): ?>
        <tr>
            <td><?php echo $owns_arr['dwEnrollNumber']; ?></td>
            <td><?php echo $owns_arr['Name']; ?></td>
            <td><?php echo $owns_arr['Privilege']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class='inline pull-right page'>
    <?php echo $pagefoot; ?>
</div>

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


$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});

</script>


