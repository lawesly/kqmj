
//需要变量cityUrl, issueId
$(function () {
	
	//短信验证关闭时，开通图片验证
    var houseMeasureMsgFlag = $("#houseMeasureMsgFlag").val();
	if (houseMeasureMsgFlag == 0) {
		
		var time = new Date().getTime();
		$("#dyVerifyImg").attr("src", "http://newhouse." + cityUrl + "/newhouse/floor/getNewVerify.do?date=" + time + "&type=" + $("#dyDesValue").val());
	}
});

//订阅信息获取手机短信验证码
function getdyValiCode () {
	
	var phone = $.trim($("#dyPhone").val());
	if (phone == "" || !isMobil(phone)) {
		
		alert("获取验证码失败，请输入您的手机号码！");
		$("#dyPhone").focus();
		return ;
	}
	
	dyValidateCodeTime();
	
	$.ajax({
	    
    	url:"http://newhouse." + cityUrl + "/newhouse/floor/sendSmsCode.do?" + "key=" + $.md5(new Date().getDate() + "-" + phone) + "&nd=" + new Date().getTime(),
    	type: "get",
        data:{
        	
        	"phone" : phone
        },
    	dataType:'jsonp',  
        jsonp:'callback',
        success:function(result) {
        	
        	if (result) {
        		
        		alert ("验证码发送成功！！");
        	}
        },
        error:function() {
        
        	alert ("验证码发送失败，请稍后重新获取！！");
        	return ;
        }
    });
}

//重新发送验证码倒计时
var dyWait = 60;
function dyValidateCodeTime () {
	
	if (dyWait == 0) {
		
		$("#dyValiCodeBtn").removeAttr("disabled");
		$("#dyValiCodeBtn").val("发送验证码");
		dyWait = 60;
	} else {
		
		$("#dyValiCodeBtn").attr("disabled", true);
		$("#dyValiCodeBtn").val( "重新发送 " + dyWait + "");
		dyWait--;
		setTimeout(function() {dyValidateCodeTime();}, 1000);
	}
}

//订阅信息提交
function dySubmit () {
	
	$("#dyBtn").attr("disabled", true);
	
	var phone = $.trim($("#dyPhone").val());
	var code = $.trim($("#dyValiCode").val());
	
	var jjtz = $("#jjtz").is(':checked');
	var kptz = $("#kptz").is(':checked');
	var hxjjtz = $("#hxjjtz").is(':checked');
	
	if (phone == "" || !isMobil(phone)) {
	
		alert("请输入您的手机号码！");
		$("#dyPhone").focus();
		$("#dyBtn").attr("disabled", false);
		return ;
	}
	
	if (code == "") {
		
		alert("请输入正确的验证码！");
		$("#dyValiCode").focus();
		$("#dyBtn").attr("disabled", false);
		return ;
	}
	
	if (jjtz != true && kptz != true && hxjjtz != true) {
		
		alert ("请选择降价通知或者开盘通知");
		$("#dyBtn").attr("disabled", false);
		return ;
	}
	
	//验证短信验证码
	$.ajax({
	    
    	url:"http://newhouse." + cityUrl + "/newhouse/floor/subscribe.do?nd=" + new Date().getTime(),
    	type: "get",
        data:{
        	
        	"code" : code,
			"phone" : phone,
			"jjtz" : jjtz == true ? 1 : 0,
			"kptz" : kptz == true ? 1 : 0,
			"hxjjtz" : hxjjtz == true ? 1 : 0,
			"recordId" : issueId
        },
    	dataType:'jsonp',  
        jsonp:'callback',
        success:function(result) {
        	
        	if (result && result.flag == 1) {
        		
        		alert ("订阅成功！！");
        		$(".closeBtn").click();
        		$("#dyPhone").val("");
        		$("#dyValiCode").val("");
        		
        		//切换图片验证的验证图片
        		if ($("#houseMeasureMsgFlag").val() == 0) {
        			
        			var time = new Date().getTime();
        			$("#dyVerifyImg").attr("src", "http://newhouse." + cityUrl + "/newhouse/floor/getNewVerify.do?date=" + time + "&type=" + $("#dyDesValue").val());
        		}
        	}
        	else {
        		
        		alert ("验证码验证失败，请填写正确的验证码！！");
        	}
        	
        	$("#dyBtn").attr("disabled", false);
        },
        error:function() {
        
        	alert ("验证码验证失败，请重新提交！！");
        	$("#dyBtn").attr("disabled", false);
        }
    });
}