document.getElementById('users').className='active';

$(document).ready(function() {
    $('#f1').ajaxForm(function(data) {
        if(data === '1'){
            swal({
                    title: "成功!",
                    text: "已成功添加数据！",
                    type: "success"
                },
                function(){window.location="?/users/";}
            );
        }else if(data === '0'){
            swal({
                    title: "失败!",
                    text: "请勿输入重复的用户名！",
                    type: "error"
                }
            );
        }else{
            swal({
                    title: "失败!",
                    text: "请勿输入重复的手机号！",
                    type: "error"
                }
            );

        }

    });
});
