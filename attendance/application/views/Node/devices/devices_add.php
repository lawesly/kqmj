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

function removeAss(){ 
	var obj=document.getElementById('assname'); 
	obj.options.length=0; 
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
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加设备管理</h4>
</div>
<div class="modal-body">
<p><font color="red">*号为必填项</font><p>
<form name="f1" id="f1" class="form-horizontal " action="?/devices/add_cfm/" method="post">
        <div class="form-group">
                <label class="col-xs-2 control-label">部门名称(*)</label>
                <div class="col-xs-8">
			<select name='depid' id="dep"   onchange="showUser(this.value);removeAss();" class='form-control' >
				<?php foreach($departments as $deps_arr): ?>
				        <option value='<?php echo $deps_arr['depid']; ?>'><?php echo $deps_arr['depname']; ?></option>
				<?php endforeach; ?>
			</select>
                </div>
	</div>
	<div class="form-group">
		<label class="col-xs-2 control-label">使用人(*)</label>
		<div class="col-xs-8" id="own1">
				<select name=ownid id="own" onchange="removeAss();" class='form-control'>
					<?php foreach($owners as $owns_arr): ?>
				        <option value='<?php echo $owns_arr['ownid']; ?>'><?php echo $owns_arr['owner']; ?></option>
					<?php endforeach; ?>
				</select>
		</div>
        </div>
	<div class="form-group">
		<label class="col-xs-2 control-label">类别(*)</label>
                <div class="col-xs-8 col-md-8">
			<select name='sortid' class='form-control'>
				<?php foreach($sorts as $sors_arr): ?>
			        <option value='<?php echo $sors_arr['sortid']; ?>'><?php echo $sors_arr['dsortname']; ?></option>
				<?php endforeach; ?>
			</select>
                </div>
	</div>
	<div class="form-group">
		<label class="col-xs-2 control-label">品牌(*)</label>
		<div class="col-xs-8">
			<select name='braid' class='form-control'>
				<?php foreach($brands as $bras_arr): ?>
			        <option value='<?php echo $bras_arr['braid']; ?>'><?php echo $bras_arr['braname']; ?></option>
				<?php endforeach; ?>
			</select>
                </div>
	</div>
        <div class="form-group">
		<label class="col-xs-2 control-label">型号(*)</label>
		<div class="col-xs-8">
			<input class="form-control" name="model" required/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-xs-2 control-label">序列号</label>
                <div class="col-xs-8">
                        <input class="form-control" name="serialnum" />
                </div>
	</div>
        <div class="form-group">
                <label class="col-xs-2 control-label">内存</label>
                <div class="col-xs-8">
                        <input class="form-control" name="memory" />
                </div>
	</div>
	<div class="form-group">
                <label class="col-xs-2 control-label">硬盘</label>
                <div class="col-xs-8">
                        <input class="form-control" name="disk" />
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">显卡</label>
                <div class="col-xs-8">
                        <input class="form-control" name="display" />
                </div>
	</div>
	<div class="form-group">
                <label class="col-xs-2 control-label">mac</label>
                <div class="col-xs-8">
                        <input class="form-control" name="mac" />
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">其他信息</label>
                <div class="col-xs-8">
                        <input class="form-control" name="devdescribe" />
                </div>
	</div>
	<div class="form-group">
                <label class="col-xs-2 control-label">购买日期(*)</label>
                <div class="col-xs-8">
                        <input class="form-control input-datepicker" name="purdate" required/>
                </div>
        </div>
        <div class="form-group">
                <label class="col-xs-2 control-label">购买价格(*)</label>
                <div class="col-xs-8">
                        <input class="form-control" name="purprice" required/>
                </div>
	</div>
	<div class="form-group">
                <label class="col-xs-2 control-label">备注(购买商)</label>
                <div class="col-xs-8">
                        <input class="form-control" name="supplier" />
                </div>
        </div>

	<div class="form-group">
                <label class="col-xs-2 control-label">状态</label>
                <div class="col-xs-8">
                        <select name='status' class='form-control'>
                                <option value='在用'>在用</option>
                                <option value='闲置'>闲置</option>
                                <option value='报废'>报废</option>
                </select>
		</div>
	</div>
	<div class="form-group">
                <div class="col-xs-2" >
                        <button type="button" class="btn btn-info btn-sm" onclick="showAss()">选择资产</button>
                </div>
		<div class="col-xs-8">
			<div id="assname1">
				<select id="assname" name="assname" class="form-control" required>
				
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
                <div class="col-xs-2" >
                        <button type="button" class="btn btn-success btn-sm"  onclick="assadd()" >添加资产</button>
                </div>
                <div id="assadd" style="display: none">
                <div class="col-xs-8" >
                                <select name="addname" id="addname"  class="form-control">
                                        <?php foreach($sorts_ass as $soras_arr): ?>
                                                <option value='<?php echo $soras_arr['asortname']; ?>'><?php echo $soras_arr['asortname']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                </div>
                <div class="col-xs-2" >
                        <button type="button" class="btn btn-primary " id="addcfm" onclick="addoption()">添加</button>
                </div>
                </div>


	</div>

        <div class="form-group">
                <label class="col-xs-2 control-label" for=""></label>
                <div class="col-xs-8">
                        <input  type="submit" name="add" value="保存" class="btn btn-primary" />
			<button type="button" class="btn btn-danger" data-dismiss="modal" >取消</button>
                </div>
        </div>
	
</form>
</div>
    <script type="text/javascript">
      $(".chzn-select").chosen();
      $(document).ready(function() {
      $(".input-datepicker").datepicker();
  });
    </script>

<script>
$(document).ready(function() {
        $('#f1').ajaxForm(function(data) {
                if(data == '1'){
                        swal({
                        title: "成功!",
                        text: "已成功添加数据！",
                        type: "success",
                        },
                        function(){window.location="?/devices/";}
                        );
                }else{
                        swal({
                        title: "失败!",
                        text: "请勿输入重复的数据！",
                        type: "error",
                        }
                        );
                }

        });
});
</script>

