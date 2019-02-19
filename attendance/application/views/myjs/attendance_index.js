document.getElementById('attendance').className='active';

$("#export").click(function(){
    var fromdate=document.getElementById("fromdate").value;
    var todate=document.getElementById("todate").value;
    var url = "?/attendance/export/";
    url = url + "?from=" + fromdate + "&to=" + todate;
    window.location=url;
});

    $(document).ready(function() {
        $(".input-datepicker").datepicker();
    });

$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});
