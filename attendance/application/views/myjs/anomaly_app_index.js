document.getElementById('anomaly_app').className='active';

$(document).ready(function() {
    $('#tab').DataTable({
        "responsive": true,
        "order": [[ 4, "desc" ]],
        "iDisplayLength": 10,
        "bStateSave":true,
        "autoWidth": false,
        "columnDefs": [
            {"orderable": false, "targets": 0},
            {"searchable": false, "targets": 9},
            { "width": "10%", "targets": 5 }
        ],
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


$(document).ready(function() {
    var location = "?/anomaly_app/?display=" + display;
    location = location + "&sure=" + sure;
    $('#form1').ajaxForm(function(data) {
        if(data === '1'){
            swal({
                    title: "成功!",
                    text: "已成功更新数据！",
                    type: "success"
                },
                function(){window.location=location;}
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


function add(id){
    var nums = document.getElementById("nums").value;
    nums = Number(nums);
    var ids = document.getElementById("ids").value;
    if(document.getElementById(id).checked === true){
        nums = nums + 1;
        ids = ids + "," + id + "e";
    }else{
        nums = nums - 1;
        ids_arr = ids.split(',');
        rep_str = "," + id + "e";
        ids = ids.replace(rep_str,"");
    }
    document.getElementById("nums").value = nums;
    document.getElementById("sure").value="确定(" + nums + ")";
    document.getElementById("ids").value=ids;
    if(nums > 0){
        document.getElementById("fdisabled").disabled=false;
    }else{
        document.getElementById("fdisabled").disabled=true;
    }
}

function changeMonth(val){
    var sure = document.getElementById("showsure").value;
    var url = "?/anomaly_app/?display=" + val + "&sure=" + sure;
    window.location = url;

}
function changeSure(val){
    var display = document.getElementById("display").value;
    var url = "?/anomaly_app/?display=" + display + "&sure=" + val;
    window.location = url;

}

$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});

//获取checkbox按钮组
var allpro = document.getElementsByName("c1");
//全选方法
function change() {
    //获取全选按钮
    var all = document.getElementById("all");
    //全选按钮被选中时，遍历所有按钮
    if (all.checked) {
        allpro.checked = false;
        document.getElementById("nums").value = 0;
        document.getElementById("sure").value="确定(" + 0 + ")";
        document.getElementById("ids").value=0;
        for (var i = 0; i < allpro.length; i++) {
            if (allpro[i].type == "checkbox") {
                allpro[i].checked = true;
                add(allpro[i].id);

            }
        }
        //全选按钮未被选中时
    } else {
        for (var i = 0; i < allpro.length; i++) {
            if (allpro[i].type == "checkbox") {
                allpro[i].checked = false;
                add(allpro[i].id);
            }
        }
    }
}