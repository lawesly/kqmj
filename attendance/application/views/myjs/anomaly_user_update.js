document.getElementById('anomaly_user').className='active';
;
function SumTime(){
    var type = document.getElementById('type').value;
    var stime = document.getElementById('stime').value;
    if(stime < "08:30"){
        stime = "08:30";
    }
    var etime = document.getElementById('etime').value;
    var userAgent = navigator.userAgent;
    if (userAgent.indexOf("Safari") > -1){
        var randdate = "2017/01/01";
    }else{
        var randdate = "2017-01-01";
    }
    var f_stime = randdate + " " + stime + ":00";
    var f_etime = randdate + " " + etime + ":00";
    var unix_stime = Date.parse(new Date(f_stime))/1000;
    var unix_etime = Date.parse(new Date(f_etime))/1000;
    var sumtime = (unix_etime - unix_stime)/3600;
    var month = new Date().getMonth() + 1;
    if(month >= 10 || month < 5){
        if(stime <= "12:00" && etime >= "13:00"){
            sumtime = sumtime - 1;
        }
    }else{
        if(stime <= "12:00" && etime >= "13:30"){
            sumtime = sumtime - 1.5;
        }
    }
    if(type == 2){
        var int1 = parseInt(sumtime);
        var float1 = sumtime - int1;
        if(float1 > 0){
            if(float1 <= 0.5){
                sumtime = int1 + 0.5;
            }else{
                sumtime = int1 + 1;
            }
        }
        if(sumtime < 1){
            sumtime = 1;
        }
    }else if(type == 3){
        var int1 = parseInt(sumtime);
        var float1 = sumtime - int1;
        if(float1 < 0.5){
            sumtime = int1;
        }else{
            sumtime = int1 + 0.5;
        }
        if(sumtime < 1){
            sumtime = 0;
        }

    }else{
        sumtime = 0;
    }
    document.getElementById('sumtime').value = sumtime;
}

function show(){
    var main=document.getElementById('type').value;
    var obj = document.getElementById('type_sub');
    if(main == 1){
        obj.options.length = 0;
        obj.options.add(new Option("上班未打卡","上班未打卡"));
        obj.options.add(new Option("下班未打卡","下班未打卡"));
    }else if(main == 2){
        obj.options.length = 0;
        obj.options.add(new Option("调休","调休"));
        obj.options.add(new Option("年休假","年休假"));
        obj.options.add(new Option("病假","病假"));
        obj.options.add(new Option("事假","事假"));
        obj.options.add(new Option("婚假","婚假"));
        obj.options.add(new Option("产假","产假"));
        obj.options.add(new Option("护理假","护理假"));
        obj.options.add(new Option("丧假","丧假"));
    }else if(main == 3){
        obj.options.length = 0;
        obj.options.add(new Option("调休","调休"));
        obj.options.add(new Option("折现","折现"));

    }else{
        obj.options.length = 0;
        obj.options.add(new Option("公出","公出"));
    }
}


$('.timepicker').timepicker({hourGrid: 4,minuteGrid: 10});


if(tdate > 5){
    mindate = new Date(year, month, 1);
}else{
    mindate = new Date(year, lastmonth, 1);
}
$('#dwDate').multiDatesPicker({
    minDate: mindate
});

    //        $("#invite").multipleSelect({
    //	    filter: true,
    //	    position: 'top'
    //        });

    $(document).ready(function() {
        $('#invite').multiselect({
            enableFiltering: true,
            maxHeight: 300,
            buttonWidth: '100%',
            dropUp: true
        });
    });

$(document).ready(function() {
    $('#f2').ajaxForm(function(data) {
        if(data == '1'){
            swal({
                    title: "成功!",
                    text: "已成功更新数据！",
                    type: "success",
                },
                function(){window.location="?/anomaly_user/";}
            );
        }else{
            swal({
                    title: "失败!",
                    text: "更新数据失败！",
                    type: "error",
                }
            );

        }

    });
});

