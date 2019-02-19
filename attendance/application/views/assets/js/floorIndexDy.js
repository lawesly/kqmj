
//��Ҫ����cityUrl, issueId
$(function () {
	
	//������֤�ر�ʱ����ͨͼƬ��֤
    var houseMeasureMsgFlag = $("#houseMeasureMsgFlag").val();
	if (houseMeasureMsgFlag == 0) {
		
		var time = new Date().getTime();
		$("#dyVerifyImg").attr("src", "http://newhouse." + cityUrl + "/newhouse/floor/getNewVerify.do?date=" + time + "&type=" + $("#dyDesValue").val());
	}
});

//������Ϣ��ȡ�ֻ�������֤��
function getdyValiCode () {
	
	var phone = $.trim($("#dyPhone").val());
	if (phone == "" || !isMobil(phone)) {
		
		alert("��ȡ��֤��ʧ�ܣ������������ֻ����룡");
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
        		
        		alert ("��֤�뷢�ͳɹ�����");
        	}
        },
        error:function() {
        
        	alert ("��֤�뷢��ʧ�ܣ����Ժ����»�ȡ����");
        	return ;
        }
    });
}

//���·�����֤�뵹��ʱ
var dyWait = 60;
function dyValidateCodeTime () {
	
	if (dyWait == 0) {
		
		$("#dyValiCodeBtn").removeAttr("disabled");
		$("#dyValiCodeBtn").val("������֤��");
		dyWait = 60;
	} else {
		
		$("#dyValiCodeBtn").attr("disabled", true);
		$("#dyValiCodeBtn").val( "���·��� " + dyWait + "");
		dyWait--;
		setTimeout(function() {dyValidateCodeTime();}, 1000);
	}
}

//������Ϣ�ύ
function dySubmit () {
	
	$("#dyBtn").attr("disabled", true);
	
	var phone = $.trim($("#dyPhone").val());
	var code = $.trim($("#dyValiCode").val());
	
	var jjtz = $("#jjtz").is(':checked');
	var kptz = $("#kptz").is(':checked');
	var hxjjtz = $("#hxjjtz").is(':checked');
	
	if (phone == "" || !isMobil(phone)) {
	
		alert("�����������ֻ����룡");
		$("#dyPhone").focus();
		$("#dyBtn").attr("disabled", false);
		return ;
	}
	
	if (code == "") {
		
		alert("��������ȷ����֤�룡");
		$("#dyValiCode").focus();
		$("#dyBtn").attr("disabled", false);
		return ;
	}
	
	if (jjtz != true && kptz != true && hxjjtz != true) {
		
		alert ("��ѡ�񽵼�֪ͨ���߿���֪ͨ");
		$("#dyBtn").attr("disabled", false);
		return ;
	}
	
	//��֤������֤��
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
        		
        		alert ("���ĳɹ�����");
        		$(".closeBtn").click();
        		$("#dyPhone").val("");
        		$("#dyValiCode").val("");
        		
        		//�л�ͼƬ��֤����֤ͼƬ
        		if ($("#houseMeasureMsgFlag").val() == 0) {
        			
        			var time = new Date().getTime();
        			$("#dyVerifyImg").attr("src", "http://newhouse." + cityUrl + "/newhouse/floor/getNewVerify.do?date=" + time + "&type=" + $("#dyDesValue").val());
        		}
        	}
        	else {
        		
        		alert ("��֤����֤ʧ�ܣ�����д��ȷ����֤�룡��");
        	}
        	
        	$("#dyBtn").attr("disabled", false);
        },
        error:function() {
        
        	alert ("��֤����֤ʧ�ܣ��������ύ����");
        	$("#dyBtn").attr("disabled", false);
        }
    });
}