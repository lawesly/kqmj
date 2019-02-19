<link href="/application/views/assets/chosen/chosen.css" rel="stylesheet" type="text/css" />
<script src="/application/views/js/calendar.js"></script>
<script type="text/javascript">
var c = new Calendar("c");
document.write(c);

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
</script>
<!--<body onload="showAss()">-->
<form  action="?/devices/add_cfm/?page=<?php echo $page; ?>" method="post" class="definewidth m20" name="form1" id="form1">
<p><font color="red">*号为必填项</font><p>
<table class="table table-bordered  m10">
<tr>
<td width="15%" class="tableleft" >部门名称(*)</td>
<td width="35%">
<select name='depid' id="dep"   onchange="showUser(this.value);showAssDep()" class='chzn-select' >
<?php foreach($departments as $deps_arr): ?>
	<?php if($depid == $deps_arr['depid']): ?>
	<option value='<?php echo $deps_arr['depid']; ?>' selected><?php echo $deps_arr['depname']; ?></option>
	<?php else: ?>
	<option value='<?php echo $deps_arr['depid']; ?>'><?php echo $deps_arr['depname']; ?></option>
	<?php endif; ?>
<?php endforeach; ?>
</select></td>
<td width="15%" class="tableleft">使用人(*)</td>
<td width="35%">
<div id="own1">
<select name=ownid id="own" onchange="showAss()" class='chzn-select'>
<?php foreach($owners as $owns_arr): ?>
	<?php if($ownid == $owns_arr['ownid']): ?>
        <option value='<?php echo $owns_arr['ownid']; ?>' selected><?php echo $owns_arr['owner']; ?></option>
	<?php else: ?>
        <option value='<?php echo $owns_arr['ownid']; ?>'><?php echo $owns_arr['owner']; ?></option>
	<?php endif; ?>
<?php endforeach; ?>
</select>
</div>
</td>
</tr>
<td class="tableleft">类别(*)</td>
<td>
<select name='sortid' class='chzn-select'>
<?php foreach($sorts as $sors_arr): ?>
        <?php if($sortid == $sors_arr['sortid']): ?>
        <option value='<?php echo $sors_arr['sortid']; ?>' selected><?php echo $sors_arr['dsortname']; ?></option>
	<?php else: ?>
        <option value='<?php echo $sors_arr['sortid']; ?>'><?php echo $sors_arr['dsortname']; ?></option>
	<?php endif; ?>
<?php endforeach; ?>
</select>
</td>
<td class="tableleft">品牌(*)</td>
<td>
<select name='braid' class='chzn-select'>
<?php foreach($brands as $bras_arr): ?>
	<?php if($braid == $bras_arr['braid']): ?>
        <option value='<?php echo $bras_arr['braid']; ?>' selected><?php echo $bras_arr['braname']; ?></option>
	<?php else: ?>
        <option value='<?php echo $bras_arr['braid']; ?>'><?php echo $bras_arr['braname']; ?></option>
	<?php endif; ?>
<?php endforeach; ?>
</select>
</td>
</tr>
<tr>
<td class="tableleft">型号(*)</td>
<td>
<input type="text" name="model" id="model" value="<?php echo $model; ?>" required/>
</td>
<td class="tableleft">序列号</td>
<td>
<input type="text" name="serialnum"  value="<?php echo $serialnum; ?>"/>
</td>
</tr>
<tr>
<td class="tableleft">内存</td>
<td>
<input type="text" name="memory" value="<?php echo $memory; ?>"/>
</td>
<td class="tableleft">硬盘</td>
<td>
<input type="text" name="disk" value="<?php echo $disk; ?>"/>
</td>
</tr>
<tr>
<tr>
<td class="tableleft">显卡</td>
<td>
<input type="text" name="display" value="<?php echo $display; ?>"/>
</td>
<td class="tableleft">mac</td>
<td>
<input type="text" name="mac" value="<?php echo $mac; ?>"/>
</td>
</tr>
<td class="tableleft">其他信息</td>
<td>
<input type="text" name="devdescribe" value="<?php echo $devdescribe; ?>"/>
</td>
<td class="tableleft">购买日期(*)</td>
<td>
<input type="text" name="purdate" id="purdate" onfocus="c.showMoreDay = false;c.show(this);" value="<?php echo $purdate; ?>" required/>
</td>
<tr>
<td class="tableleft">购买价格(*)</td>
<td>
<input type="text" name="purprice" id="purprice" value="<?php echo $purprice; ?>" required/>
</td>
<td class="tableleft">备注(购买商)</td>
<td>
<input type="text" name="supplier" value="<?php echo $supplier; ?>"/>
</td>
</tr>
<tr>
<td class="tableleft">状态(*)</td>
<td>
<select name="status">
<?php
$array_status = array("在用","闲置","报废");
foreach($array_status as $sta){
        if($sta == $status){
                echo  "<option selected='selected'>$sta</option>";
        }else{
                echo  "<option>$sta</option>";
        }
}

?>
</select>
</td>
<td class="tableleft">所属资产(*)</td>
<td>
<div id="assname1">
<select id="assname" name="assname">
<?php foreach($assets as $asss_arr): ?>
        <?php if($assid == $asss_arr['assid']): ?>
        <option value='<?php echo $asss_arr['assid']; ?>' selected><?php echo $asss_arr['sortname']."_".$asss_arr['assname']; ?></option>
        <?php else: ?>
        <option value='<?php echo $asss_arr['assid']; ?>'><?php echo $asss_arr['sortname']."_".$asss_arr['assname']; ?></option>
        <?php endif; ?>
<?php endforeach; ?>

</select>
</div>
<span><button type="button" class="btn btn-success" onclick="assadd()">添加新资产</button></span>
<div id="assadd" style="display: none">
资产类别:&nbsp;
<select name="addname" id="addname"  style="width:200px">
<?php foreach($sorts_ass as $soras_arr): ?>
        <option value='<?php echo $soras_arr['asortname']; ?>'><?php echo $soras_arr['asortname']; ?></option>
<?php endforeach; ?>
</select>

&nbsp;&nbsp;<button type="button" class="btn btn-primary" id="addcfm" onclick="addoption()">添加</button>
</div>
</td>
</tr>

</table>
<input  type="submit" class="btn btn-primary" name="add" value="添加" /> &nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="window.location='?/devices/'">返回</button>

</form>
<script type="text/javascript" src="/application/views/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/application/views/assets/chosen/chosen.jquery.js"></script>
    <script type="text/javascript">
      $(".chzn-select").chosen();
    </script>

