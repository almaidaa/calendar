<!DOCTYPE html>
<html>
<head>
    <title>Calendar HMI Chemical Testing</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
  
<div class="container">
    <h1>Calendar HMI Chemical Testing</h1>
    <div id='calendar'></div>
</div>
   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    var SITEURL = "{{ url('/') }}";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var calendar = $('#calendar').fullCalendar({
        editable: true,
        events: SITEURL + "/fullcalender",
        displayEventTime: false,
        selectable: true,
        selectHelper: true,
        eventRender: function(event, element, view) {
            if (event.allDay === 'true' || event.allDay === true) {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        select: function(start, end, allDay) {
            Swal.fire({
                title: "Masukan Judul",
                input: "text",
                inputPlaceholder: "Isi dulu donk kaka",
                showCancelButton: true,
                confirmButtonText: "OK",
                cancelButtonText: "Batal",
                inputValidator: (value) => {
                    if (!value) {
                        return 'Judul tidak boleh kosong';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var title = result.value;
                    var start = moment(start).format("Y-MM-DD");
                    var end = moment(end).format("Y-MM-DD");
                    $.ajax({
                        url: SITEURL + "/fullcalenderAjax",
                        data: {
                            title: title,
                            start: start,
                            end: end,
                            type: 'add'
                        },
                        type: "POST",
                        success: function(data) {
                            Swal.fire("Data Berhasil Buat");

                            calendar.fullCalendar('renderEvent', {
                                id: data.id,
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            }, true);

                            calendar.fullCalendar('unselect');
                        },
                        error: function() {
                            Swal.fire("Oops...", "Something went wrong!", "error");
                        }
                    });
                }
            });
        },
        eventDrop: function(event, delta, revertFunc) {
            var start = moment(event.start).format("Y-MM-DD");
            var end = moment(event.end).format("Y-MM-DD");

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: SITEURL + '/fullcalenderAjax',
                        data: {
                            title: event.title,
                            start: start,
                            end: end,
                            id: event.id,
                            type: 'update'
                        },
                        type: "POST",
                        success: function(response) {
                            Swal.fire("Event Updated Successfully");
                        },
                        error: function() {
                            revertFunc(); // Revert the event if update fails
                            Swal.fire("Oops...", "Something went wrong!", "error");
                        }
                    });
                } else {
                    revertFunc(); // Revert the event if user cancels
                }
            });
        },
        eventClick: function(event) {
            Swal.fire({
                title: "Are you sure?",
                text: "Yakin Mau dihapus?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/fullcalenderAjax',
                        data: {
                            id: event.id,
                            type: 'delete'
                        },
                        success: function(response) {
                            calendar.fullCalendar('removeEvents', event.id);
                            Swal.fire("Deleted!", "Yahh Sudah Hilang", "success");
                        },
                        error: function() {
                            Swal.fire("Oops...", "Something went wrong!", "error");
                        }
                    });
                }
            });
        }

    });

});
</script>

</body>
</html>
