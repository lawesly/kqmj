<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">异常申请记录</h4>
</div>
<div class="modal-body">
    <table class="table table-bordered table-hover m10">
        <tr>
            <th>ID</th>
            <th>类型</th>
            <th>日期</th>
            <th>异常时间</th>
            <th>说明</th>
            <th>描述</th>
            <th>证明人/领导</th>
            <th>证明人/领导确认</th>
            <th>操作员确认</th>
        </tr>
        <?php foreach ($anomaly as $arr): ?>
            <tr>
                <td><?php echo $arr['id']; ?></td>
                <td><?php echo $arr['type']; ?></td>
                <td><?php echo $arr['dwDate']; ?></td>
                <td><?php echo $arr['durtime']; ?></td>
                <td><?php echo $arr['type_sub']; ?></td>
                <td><?php echo $arr['reason']; ?></td>
                <td><?php echo $arr['invite']; ?></td>
                <td><?php echo $arr['sure']; ?></td>
                <?php if($arr['isack'] == '否'):?>
                    <td><font color="red"><?php echo $arr['isack']; ?></font></td>
                <?php else: ?>
                    <td><?php echo $arr['isack']; ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
