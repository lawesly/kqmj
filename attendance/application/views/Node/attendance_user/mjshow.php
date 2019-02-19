<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">门禁刷卡记录</h4>
</div>
<div class="modal-body">
    <table class="table table-bordered table-hover m10">
        <tr>
            <th>刷卡时间</th>
            <th>刷卡日期</th>
            <th>姓名</th>
            <th>部门</th>
            <th>门</th>
            <th>门禁卡号</th>
            <th>动作</th>
            <th>原因</th>
        </tr>
        <?php foreach ($menjin as $arr): ?>
            <tr>
                <td><?php echo $arr['swipeTime']; ?></td>
                <td><?php echo $arr['swipeDate']; ?></td>
                <td><?php echo $arr['Name']; ?></td>
                <td><?php echo $arr['depname']; ?></td>
                <td><?php echo $arr['doorSN']; ?></td>
                <td><?php echo $arr['cardNum']; ?></td>
                <td><?php echo $arr['action']; ?></td>
                <td><?php echo $arr['reasonNo']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
