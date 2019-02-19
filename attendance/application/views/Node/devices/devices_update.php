<link href="/application/views/assets/chosen/chosen.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

function showUser(str){
    var url="?/devices/show_owner/";
    str = encodeURIComponent(str);
    url=url+"?depid="+str;
    $.ajax({
        url:url,
        type:'GET',
        success:function(data){
            document.getElementById("own1").innerHTML=data;
            $(".chzn-select").chosen();
        }
      });
}

function showAss(){
    var url="?/devices/show_asset/";
//    str = encodeURIComponent(str);
    var ownid=document.getElementById("own").value;
    url=url+"?ownid="+ownid;
    $.ajax({
        url:url,
        type:'GET',
        success:function(data){
            document.getElementById("assname1").innerHTML=data;
            $(".chzn-select").chosen();
        }
      });
}
function showAssDep(){
    var url="?/devices/show_asset_dep/";
//    str = encodeURIComponent(str);
    var depid=document.getElementById("dep").value;
    url=url+"?depid="+depid;
    $.ajax({
        url:url,
        type:'GET',
        success:function(data){
            document.getElementById("assname1").innerHTML=data;
            $(".chzn-select").chosen();
        }
      });
}
function assadd(){
        var myDiv= document.getElementById("assadd");
        myDiv.style.display ="block";
        document.getElementById('addname').value="";
}

//添加select option
function addoption(){
        var obj=document.getElementById('assname');
        var addname=document.getElementById('addname').value;
        if(addname!=""){
                addname="+"+addname;
                obj.options.add(new Option(addname,addname));
                var myDiv= document.getElementById("assadd");
                myDiv.style.display ="none";
        }
        var len=obj.options.length - 1;

        obj.options[len].selected = true;


}
function sure(){
        var item=document.getElementById('assname')
	var assname = item.options[item.selectedIndex].text;
	var assid = document.getElementById('assname').value;
        var obj=document.getElementById('sure');
        obj.style.display ="none";
        var myobj=document.getElementById('assname_sure');
        myobj.options.add(new Option(assname,assid));
        var myDiv=document.getElementById('forsure');
        myDiv.style.display="block";
}

</script>
<p><font color="red">*号为必填项</font><p>
<form   name="f1" class="form-horizontal " action="?/devices/update_cfm/?id=<?php echo $devid; ?>" method="post">
        <div class="form-group">
                <label class="col-xs-2 control-label">部门名称(*)</label>
                <div class="col-xs-3">
                        <select name='depid' id="dep"   onchange="showUser(this.value);showAssDep()" class='form-control chzn-select' >
				<?php foreach($departments as $deps_arr): ?>
				        <?php if($depid == $deps_arr['depid']): ?>
				        <option value='<?php echo $deps_arr['depid']; ?>' selected><?php echo $deps_arr['depname']; ?></option>
				        <?php else: ?>
				        <option value='<?php echo $deps_arr['depid']; ?>'><?php echo $deps_arr['depname']; ?></option>
				        <?php endif; ?>
				<?php endforeach; ?>
                        </select>
                </div>
                <label class="col-xs-2 control-label">使用人(*)</label>
                <div class="col-xs-3" id="own1">
			<select name=ownid id="own" onchange="showAss()" class='form-control chzn-select'>
				<?php foreach($owners as $owns_arr): ?>
				        <?php if($ownid == $owns_arr['ownid']): ?>
				        <option value='<?php echo $owns_arr['ownid']; ?>' selected><?php echo $owns_arr['owner']; ?></option>
				        <?php else: ?>
				        <option value='<?php echo $owns_arr['ownid']; ?>'><?php echo $owns_arr['owner']; ?></option>
				        <?php endif; ?>
				<?php endforeach; ?>
			</select>
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">类别(*)</label>
                <div class="col-xs-3">
                        <select name='sortid' class='form-control chzn-select'>
				<?php foreach($sorts as $sors_arr): ?>
				        <?php if($sortid == $sors_arr['sortid']): ?>
				        <option value='<?php echo $sors_arr['sortid']; ?>' selected><?php echo $sors_arr['dsortname']; ?></option>
				        <?php else: ?>
				        <option value='<?php echo $sors_arr['sortid']; ?>'><?php echo $sors_arr['dsortname']; ?></option>
				        <?php endif; ?>
				<?php endforeach; ?>
                        </select>
                </div>
                <label class="col-xs-2 control-label">品牌(*)</label>
                <div class="col-xs-3">
                        <select name='braid' class='form-control chzn-select'>
				<?php foreach($brands as $bras_arr): ?>
				        <?php if($braid == $bras_arr['braid']): ?>
				        <option value='<?php echo $bras_arr['braid']; ?>' selected><?php echo $bras_arr['braname']; ?></option>
				        <?php else: ?>
				        <option value='<?php echo $bras_arr['braid']; ?>'><?php echo $bras_arr['braname']; ?></option>
				        <?php endif; ?>
				<?php endforeach; ?>
                        </select>
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">型号(*)</label>
                <div class="col-xs-3">
                        <input class="form-control" name="model" value="<?php echo $model; ?>" required/>
                </div>
                <label class="col-xs-2 control-label">序列号</label>
                <div class="col-xs-3">
                        <input class="form-control" name="serialnum" value="<?php echo $serialnum; ?>" />
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">内存</label>
                <div class="col-xs-3">
                        <input class="form-control" name="memory" value="<?php echo $memory; ?>" />
                </div>
                <label class="col-xs-2 control-label">硬盘</label>
                <div class="col-xs-3">
                        <input class="form-control" name="disk" value="<?php echo $disk; ?>" />
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">显卡</label>
                <div class="col-xs-3">
                        <input class="form-control" name="display" value="<?php echo $display; ?>" />
                </div>
                <label class="col-xs-2 control-label">mac</label>
                <div class="col-xs-3">
                        <input class="form-control" name="mac" value="<?php echo $mac; ?>" />
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">其他信息</label>
                <div class="col-xs-3">
                        <input class="form-control" name="devdescribe"  value="<?php echo $devdescribe; ?>" />
                </div>
                <label class="col-xs-2 control-label">购买日期(*)</label>
                <div class="col-xs-3">
                        <input class="form-control input-datepicker" name="purdate" value="<?php echo $purdate; ?>" disabled/>
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">购买价格(*)</label>
                <div class="col-xs-3">
                        <input class="form-control" name="purprice" value="<?php echo $purprice; ?>" required/>
                </div>
                <label class="col-xs-2 control-label">备注(购买商)</label>
                <div class="col-xs-3">
                        <input class="form-control" name="supplier" value="<?php echo $supplier; ?>" />
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">状态</label>
                <div class="col-xs-3">
                        <select name='status' class='form-control'>
				<?php foreach($array_status as $sta):?>
					<?php if($sta == $status):?>
						<option selected='selected' value="<?php echo $sta; ?>"><?php echo $sta; ?></option>
					<?php else: ?>
						<option value="<?php echo $sta; ?>"><?php echo $sta; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
                	</select>
                </div>
                <label class="col-xs-2 control-label">所属资产</label>
                <div class="col-xs-3">
                        <div id="assname1">
                                <select id="assname" name="assname" class="form-control">
					<?php foreach($assets as $asss_arr): ?>
					        <?php if($assid == $asss_arr['assid']): ?>
					        <option value='<?php echo $asss_arr['assid']; ?>' selected><?php echo $asss_arr['sortname']."_".$asss_arr['assname']; ?></option>
					        <?php else: ?>
					        <option value='<?php echo $asss_arr['assid']; ?>'><?php echo $asss_arr['sortname']."_".$asss_arr['assname']; ?></option>
					        <?php endif; ?>
					<?php endforeach; ?>
                                </select>
                        </div>
                </div>
        </div>
        <div class="form-group" id="sure">
                <label class="col-xs-7 control-label" for=""></label>
                <div class="col-xs-3" >
			<button type="button" class="btn btn-info" onclick="sure()">锁定</button>
                        <button type="button" class="btn btn-success" onclick="assadd()">添加新资产</button>
                        <div id="assadd" style="display: none">
                                资产类别:&nbsp;
                                <select name="addname" id="addname"  class="form-control">
                                        <?php foreach($sorts_ass as $soras_arr): ?>
                                                <option value='<?php echo $soras_arr['asortname']; ?>'><?php echo $soras_arr['asortname']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                                &nbsp;&nbsp;<button type="button" class="btn btn-primary " id="addcfm" onclick="addoption()">添加</button>
                        </div>
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label" >更改原因(*)</label>
                <div class="col-xs-3">
			<textarea class="form-control" name="reason" id="changereason" required></textarea>
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-1 control-label" for=""></label>
                <div class="col-xs-2">
                        <input  type="submit" name="add" value="添加" class="btn btn-primary" />
                        <input type="button" name="back" id="back" value="返回" class="btn btn-success" onclick="history.go(-1)" />
                </div>
        </div>


</div>
<div id="forsure" style="display:none">
	<select id="assname_sure" name="assname_sure">
	</select>
</div>
</form>
<script type="text/javascript" src="/application/views/assets/chosen/chosen.jquery.js"></script>
    <script type="text/javascript">
      $(".chzn-select").chosen();
      $(document).ready(function() {
      $(".input-datepicker").datepicker();

    </script>

