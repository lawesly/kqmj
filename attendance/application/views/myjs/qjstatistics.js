document.getElementById('qjstatistics').className='active';

$(document).ready(function() {
    $('#tab').DataTable({
        "order": [[ 3, "asc" ]],
        "iDisplayLength": 50,
        "responsive": true,
//        "columnDefs": [
//            {"orderable": false, "targets": 2},
//            {"orderable": false, "targets": 3}
//        ],
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
    $(".input-datepicker").datepicker();
});

$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});
