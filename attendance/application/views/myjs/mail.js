$(document).ready(function() {
    $('#f1').ajaxForm(function(data) {
        if(data === '1'){
            swal({
                    title: "成功!",
                    text: "已成功绑定邮箱！",
                    type: "success"
                },
                function(){window.location="?/main/";}
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
