$(document).ready(function() {
    $('#f1').ajaxForm(function(data) {
        if(data === '1'){
            swal({
                    title: "成功!",
                    text: "已成功更新密码！",
                    type: "success"
                },
                function(){window.location="?/main/";}
            );
        }else if(data === '0'){
            swal({
                    title: "失败!",
                    text: "密码不一致！",
                    type: "error"
                }
            );
        }else if(data === '2'){
            swal({
                    title: "失败!",
                    text: "密码过短,至少4位!",
                    type: "error"
                }
            );
        }else if(data === '3'){
            swal({
                    title: "失败!",
                    text: "不能为初始密码!",
                    type: "error"
                }
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