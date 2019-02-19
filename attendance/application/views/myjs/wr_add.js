document.getElementById('wr').className='active';

$('.timepicker').timepicker();
$(document).ready(function() {
    $(".input-datepicker").datepicker();
});

//$('#dwDate').multiDatesPicker();

$(document).ready(function() {
    $('#f2').ajaxForm(function(data) {
        if(data === '1'){
            swal({
                    title: "成功!",
                    text: "已成功更新数据！",
                    type: "success"
                },
                function(){window.location="?/wr/";}
            );
        }else{
            swal({
                    title: "失败!",
                    text: "更新数据失败！",
                    type: "error"
                }
            );

        }

    });
});