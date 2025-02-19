<!DOCTYPE html>
<html data-theme="cupcake">
<head>
    <title>Calendar HMI Chemical Testing</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
      window.OneSignalDeferred = window.OneSignalDeferred || [];
      OneSignalDeferred.push(async function(OneSignal) {
        await OneSignal.init({
          appId: "{{ env('ONESIGNAL_APP_ID') }}",
        });

        console.log(OneSignal.User.PushSubscription.id??null);
        // console.log(OneSignal);
        console.log(OneSignal.EVENTS.NOTIFICATION_PERMISSION_CHANGED_AS_BOOLEAN=='permissionChange');


          if (OneSignal.User.PushSubscription.id) {
            fetch("/save-player-id", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
              },
              body: JSON.stringify({ player_id: OneSignal.User.PushSubscription.id }),
            })
              .then((response) => {
                if (response.ok) {
                  console.log("Berhasil menyimpan player ID");
                } else {
                  console.log("Gagal menyimpan player ID");
                }
              })
              .catch((error) => console.error("Error:", error));
          }

        });

    </script>


</head>
<body>
    <div class="text-center absolute" style="top: -5%; left: 2%;">
        <img style="width: 150px; height: 150px;" src="{{ asset('css/posco.png') }}" alt="Logo">
    </div>
    <div class="absolute top-0 right-0 p-4">
    </div>


    <div class="container">
        <div class="relative w-full z-1 text-center right-0 top-1/2 translate-y-[90px]">
            <input type="text" id="searchBar" class="mb-3 border-0 shadow w-1/6 p-2 rounded-xl hover:transition-all hover:duration-2000 hover:w-1/5 text-center" placeholder="Search event...">
            <a href="{{ route('logout') }}" class="bi bi-door-closed-fill text-blue-500 text-2xl" title="Logout"></a>
    </div>
    <h2 class="text-center font-mono font-bold text-gray-700 xl:text-3xl" style="position: relative; top: -20px;">Calendar HMI Chemical Testing</h2>
    <div id='calendar' class="font-mono font-bold text-gray-700 text-sm"></div>

</div>
{{-- <div class="bg-white w-2/12 h-full absolute top-0" id="recent"> --}}
    {{-- </div> --}}
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
            var predefinedTitles = [
                "Create WO",
                "Check Server",
                "Restart Server",
                "Relocate Server",
                "Restart Watdog",
                "Flushing",
                "inspect",
                "Create Weekly Report",
                "Complete WO",
                "Issues",
                "etc",
            ];
            var calendar = $('#calendar').fullCalendar({
                timezone:'local',
                editable: true,
                events: SITEURL + "/",
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
                        allowOutsideClick: false,
                        title: "Select or Enter Event Title",
                        html: `
                    <select id="swal-input1" class="swal2-input form-control p-1 my-1" placeholder="Select event title">
                        <option value="" disabled selected>Select event title</option>
                        ${predefinedTitles.map(title => `<option value="${title}">${title}</option>`).join('')}
                    </select>
                    <textarea id="swal-input2" class="swal2-input form-control p-1 my-1 col-12" placeholder="Description" style="height: 235px;"></textarea>`,
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel",
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to select or enter a title';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var title = result.value;
                            var startFormatted = moment(start).format("Y-MM-DD");
                            var endFormatted = moment(end).subtract(1, 'days').format(
                                "Y-MM-DD");
                            const selectValue = document.getElementById('swal-input1').value;
                            const inputValue = document.getElementById('swal-input2').value;
                            $.ajax({
                                url: SITEURL + "/fullcalenderAjax",
                                data: {
                                    title: selectValue,
                                    description:inputValue,
                                    start: startFormatted,
                                    end: endFormatted,
                                    type: 'add'
                                },
                                type: "POST",
                                success: function(data) {
                                    Swal.fire("Event Sukses");
                                    calendar.fullCalendar('renderEvent', {
                                        id: data.id,
                                        title: selectValue,
                                        description:inputValue,
                                        start: startFormatted,
                                        end: endFormatted,
                                        allDay: allDay
                                    }, true);
                                    calendar.fullCalendar('unselect');
                                },
                                error: function() {
                                    Swal.fire("Oops...", "Description cannot null!",
                                        "error");
                                }
                            });
                        }
                    });
                },
                eventDrop: function(event, delta, revertFunc) {
                    var start = moment(event.start).format("Y-MM-DD");
                    var end = event.end ? moment(event.end).format("Y-MM-DD") : start;
                    Swal.fire({
                        title: "Are you sure?",
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
                                    id: event.id,
                                    title: event.title,
                                    start: start,
                                    description: event.description,
                                    end: end,
                                    type: 'update'
                                },
                                type: "POST",
                                success: function(response) {
                                    Swal.fire("Event Updated Successfully");
                                },
                                error: function() {
                                    revertFunc
                                        (); // Revert the event if update fails
                                    Swal.fire("Oops...", "Something went wrong!",
                                        "error");
                                }
                            });
                        } else {
                            revertFunc(); // Revert the event if user cancels
                        }
                    });
                },
                eventClick: function(event) {
                    var start = moment(event.start).format("Y-MM-DD");
                    var end = event.end ? moment(event.end).format("Y-MM-DD") : start;
                    Swal.fire({
                        title: `${event.title}`,
                        icon: "info",
                        html: `${event.description}`,
                        showCancelButton: true,
                        showDenyButton: true,
                        showConfirmButton: true,
                        confirmButtonText: "Edit",
                        denyButtonText: "Delete",
                        cancelButtonText: "Close"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Edit event
                            Swal.fire({
                                title: `${event.title}`,
                                html: `
                            <select id="swal-input1" class="swal2-input form-control p-1 my-1">
                                ${predefinedTitles.map(title => `<option value="${title}" ${title === event.title ? 'selected' : ''}>${title}</option>`).join('')}
                            </select>
                            <textarea id="swal-input2" style="height: 235px;" class="swal2-input form-control p-1 my-1 col-12" placeholder="Or enter a custom title">${event.description}</textarea>`,
                                focusConfirm: false,
                                showCancelButton: true,
                                confirmButtonText: 'Update',
                                showLoaderOnConfirm: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const selectValue = document.getElementById('swal-input1').value;
                                    const inputValue = document.getElementById('swal-input2').value;
                                    return $.ajax({
                                        type: "POST",
                                        url: SITEURL + '/fullcalenderAjax',
                                        data: {
                                            id: event.id,
                                            title: selectValue,
                                            description: inputValue,
                                            start: start,
                                            end: end,
                                            type: 'update'
                                        }
                                    }).then(response => {
                                        event.title = selectValue;
                                        event.description = inputValue;
                                        event.start = start;
                                        event.end = end;
                                        calendar.fullCalendar('updateEvent',
                                            event);
                                        Swal.fire("Updated!",
                                            "Event title has been updated.",
                                            "success");
                                    }).catch(() => {
                                        Swal.fire("Oops...",
                                            "Something went wrong!", "error"
                                        );
                                    });
                                }
                            });
                        } else if (result.isDenied) {
                            // Delete event
                            Swal.fire({
                                title: `${event.title}`,
                                text: `Yakin Mau dihapus?`,
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
                                            calendar.fullCalendar(
                                                'removeEvents', event.id
                                            );
                                            Swal.fire("Deleted!",
                                                `"${event.title}" has been deleted.`,
                                                "success");
                                        },
                                        error: function() {
                                            Swal.fire("Oops...",
                                                "Something went wrong!",
                                                "error");
                                        }
                                    });
                                }
                            });
                        }
                        // If canceled, do nothing (close the modal)
                    });
                },
            });
            $('#searchBar').on('input', function() {
                const query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: "{{ url('search') }}",
                        method: "POST",
                        data: {
                            type: 'search',
                            title: query,
                            description: query
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response);
                            calendar.fullCalendar('removeEvents');
                            calendar.fullCalendar('addEventSource', response);
                        },
                        error: function() {
                            Swal.fire("Oops...", "Something went wrong!", "error");
                        }
                    });
                } else {
                    calendar.fullCalendar('refetchEvents');
                }
            });
        });
    </script>
</body>
</html>
