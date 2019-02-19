<select id="assname" name="assname" class="form-control" required>
<?php foreach($assets as $asss_arr): ?>
        <option value='<?php echo $asss_arr['assid']; ?>'><?php echo $asss_arr['sortname']."_".$asss_arr['assname']; ?></option>
<?php endforeach; ?>
</select>

