$(document).ready(function() {
    $('#f1').ajaxForm(function(data) {
        if(data === '1'){
            swal({
                    title: "成功!",
                    text: "已成功添加车牌！",
                    type: "success"
                },
                function(){window.location="?/carlicense/";}
            );
        }else{
            swal({
                    title: "失败!",
                    text: "其他原因导致失败!",
                    type: "error"
                }
            );
        }

    });
});
