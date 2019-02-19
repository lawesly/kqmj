<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">指纹打卡记录</h4>
</div>
<div class="modal-body">
    <table class="table table-bordered table-hover m10">
        <tr>
            <th>打卡时间</th>
            <th>打卡日期</th>
            <th>姓名</th>
            <th>部门</th>
        </tr>
        <?php foreach ($zk as $arr): ?>
            <tr>
                <td><?php echo $arr['dwTime']; ?></td>
                <td><?php echo $arr['dwDate']; ?></td>
                <td><?php echo $arr['Name']; ?></td>
                <td><?php echo $arr['depname']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
