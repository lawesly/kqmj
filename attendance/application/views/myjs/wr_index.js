document.getElementById('wr').className='active';

$(document).ready(function() {
    $('#tab').DataTable({
        "responsive": true,
        "order": [[ 0, "desc" ]],
        "language": {
            "lengthMenu": "每页 _MENU_ 条记录",
            "zeroRecords": "没有找到记录",
            "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
            "infoEmpty": "无记录",
            "infoFiltered": "(从 _MAX_ 条记录过滤)",
            "search": "搜索",
            "paginate": {
                "first":      "首页",
                "last":       "末页",
                "next":       "下页",
                "previous":   "上页"
            }
        }
    });
} );

$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});

function delcfm(id){
    swal({
        title: "您确定要删除吗？",
        text: "您确定要删除这条数据？",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: false,
        confirmButtonText: "是的，我要删除",
        confirmButtonColor: "#ec6c62"
    }, function() {
        $.ajax({
            url: "?/wr/del/?id=" + id,
            type: "GET"
        }).done(function(data) {
            if(data === "1"){
                swal({title: "操作成功!",
                        text: "已成功删除数据！",
                        type: "success"
                    }, function(){window.location="?/wr/";}
                );
            }else{
                swal("失败!","删除操作失败!","error");
            }
        }).error(function() {
            swal("失败", "删除操作失败了!", "error");
        });
    });
}

