document.getElementById('attendance_user').className='active';

$(document).ready(function() {
    $(".input-datepicker").datepicker();
});


$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});
