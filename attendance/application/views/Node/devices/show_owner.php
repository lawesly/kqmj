<select name=ownid id="own" onchange="removeAss();" class="form-control chzn-select" >
<?php foreach($owners as $owns_arr): ?>
        <option value='<?php echo $owns_arr['ownid']; ?>'><?php echo $owns_arr['owner']; ?></option>
<?php endforeach; ?>
</select>
