
<!DOCTYPE html>
<html data-theme="cupcake">

<head>
    <title>Calendar HMI Chemical Testing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    {{-- <link rel="stylesheet" href="css/style.css"> --}}
    <style>
        .container {
            /* margin-top: 50px; */
            padding-top: 50px;
        }

        .calendar-and-notes-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 20px; /* Space between calendar and notes card */
        }

        #calendar {
            flex-grow: 1; /* Calendar takes most of the space */
        }

        .notes-card {
            flex-basis: 300px; /* Fixed width for the notes card */
            flex-shrink: 0;
            background-color: #f8f8f8;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 650px;
            overflow: scroll;
        }

        .note-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            position: relative; /* For delete button positioning */
        }

        .note-item textarea {
            width: 100%;
            border: none;
            background: transparent;
            resize: vertical; /* Allow vertical resizing */
            font-family: sans-serif;
            font-size: 14px;
            min-height: 50px;
        }

        .note-item .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
            font-size: 16px;
        }

        .button-cards-container {
            display: flex;
            justify-content: flex-start;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .button-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            text-decoration: none;
            color: inherit;
            flex: 0 0 200px; /* Fixed width for cards */
            max-width: 200px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
        }

        .button-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .button-card h3 {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .button-card p {
            font-size: 0.9rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .calendar-and-notes-wrapper {
                flex-direction: column; /* Stack calendar and notes vertically */
            }

            .notes-card {
                flex-basis: auto; /* Allow notes card to take natural width */
                width: 100%; /* Full width on small screens */
            }

            #searchBar {
                width: 80%; /* Make search bar wider on small screens */
            }

            .button-card {
                flex: 1 1 100%; /* One card per row on small screens */
                max-width: 100%;
            }
        }

        .search-results-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
        }
        .search-result-item {
            padding: 8px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }
        .search-result-item:hover {
            background-color: #f5f5f5;
        }
        .search-result-item:last-child {
            border-bottom: none;
        }

.shake-left{-webkit-animation:shake-left .7s steps(2,end) infinite both;animation:shake-left .7s steps(2,end) infinite both}


@-webkit-keyframes shake-left{0%,100%{-webkit-transform:rotate(0deg);transform:rotate(0deg);-webkit-transform-origin:0 50%;transform-origin:0 50%}10%{-webkit-transform:rotate(2deg);transform:rotate(2deg)}20%,40%,60%{-webkit-transform:rotate(-4deg);transform:rotate(-4deg)}30%,50%,70%{-webkit-transform:rotate(4deg);transform:rotate(4deg)}80%{-webkit-transform:rotate(-2deg);transform:rotate(-2deg)}90%{-webkit-transform:rotate(2deg);transform:rotate(2deg)}}@keyframes shake-left{0%,100%{-webkit-transform:rotate(0deg);transform:rotate(0deg);-webkit-transform-origin:0 50%;transform-origin:0 50%}10%{-webkit-transform:rotate(2deg);transform:rotate(2deg)}20%,40%,60%{-webkit-transform:rotate(-4deg);transform:rotate(-4deg)}30%,50%,70%{-webkit-transform:rotate(4deg);transform:rotate(4deg)}80%{-webkit-transform:rotate(-2deg);transform:rotate(-2deg)}90%{-webkit-transform:rotate(2deg);transform:rotate(2deg)}}

        .color-change{-webkit-animation:color-change .8s linear infinite alternate both;animation:color-change .8s linear infinite alternate both}
        @-webkit-keyframes color-change{0%{background:#19dcea}25%{background:#b22cff}50%{background:#ea2222}75%{background:#f5be10}100%{background:#3bd80d}}@keyframes color-change{0%{background:#19dcea}25%{background:#b22cff}50%{background:#ea2222}75%{background:#f5be10}100%{background:#3bd80d}}

        .roll-out{-webkit-animation:roll-out .6s cubic-bezier(.755,.05,.855,.06) both;animation:roll-out .6s cubic-bezier(.755,.05,.855,.06) both}


        @-webkit-keyframes roll-out{0%{-webkit-transform:translateY(0) rotate(0deg);transform:translateY(0) rotate(0deg);opacity:1}100%{-webkit-transform:translateY(800px) rotate(720deg);transform:translateY(800px) rotate(720deg);-webkit-filter:blur(50px);filter:blur(50px);opacity:0}}@keyframes roll-out{0%{-webkit-transform:translateY(0) rotate(0deg);transform:translateY(0) rotate(0deg);opacity:1}100%{-webkit-transform:translateY(800px) rotate(720deg);transform:translateY(800px) rotate(720deg);-webkit-filter:blur(50px);filter:blur(50px);opacity:0}}


        </style>
</head>

<body>


    <div class="text-center absolute" style="top: -5%; left: 2%;">
        <img style="width: 150px; height: 150px;" src="{{ asset('css/posco.png') }}" alt="Logo">
    </div>
    <div class="absolute top-0 right-0 p-4">
    </div>


    <div class="container">
        <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 20px;">
            <input type="text" id="searchBar" class="mb-1 border-0 shadow w-1/6 p-2 rounded-xl hover:transition-all hover:duration-2000 hover:w-1/5 text-center" placeholder="Search event...">
            <a href="{{ route('logout') }}" class="bi bi-door-closed-fill text-blue-500 text-2xl ml-3" title="Logout"></a>
        </div>
        <h2 class="text-left font-mono font-bold text-gray-700 xl:text-3xl">Calendar HMI Chemical Testing</h2>

    <div class="calendar-and-notes-wrapper">
        <div id='calendar' class="font-mono font-bold text-gray-700 text-sm"></div>

        <div class="notes-card">
            <h3 class="text-lg font-bold mb-2 text-center">My Notes</h3>
            <button id="add-note-btn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Add New Note
            </button>
            <div id="sticky-notes-container"></div>
        </div>
    </div>

    <div class="button-cards-container mt-4">
        <a href="https://one.posco.net/idms/webapps/jsp/one/login/login.jsp" target="_blank" class="button-card">
            <h3>Posco.net</h3>
            <p>Login Email & OJM</p>
        </a>
        <a href="http://picmesa.poscoway.net:8041/S60/S60010/0113" target="_blank" class="button-card">
            <h3>PICMESA</h3>
            <p>Go to the PICMESA application.</p>
        </a>
        <a href="https://mpdev.my.id/" target="_blank" class="button-card">
            <h3>MPDEV</h3>
            <p>Go to the MPDEV application.</p>
        </a>

    </div>

</div>



{{-- <div class="bg-white w-2/12 h-full absolute top-0" id="recent"> --}}
    {{-- </div> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
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

    var predefinedTipes = [
        "Create WO",
        "Check Server",
        "Restart Server",
        "Relocate Server",
        "Restart Watdog",
        "Maintenance",
        "Backup and Scan Office",
        "Delete Sample ID",
        "Backup Oracle",
        "Request",
        "Flushing",
        "inspect",
        "Create Weekly Report",
        "Complete WO",
        "Issues",
        "Assign WO",
        "Scan And Update ALYac",
        "Libur",
        "Cuti",
        "etc",
    ];

    var calendar = $('#calendar').fullCalendar({
        editable: true,
        events: {
            url: SITEURL + "/",
            data: function() { // a function that returns an object
                return {
                    title: $('#searchBar').val()
                };
            },
            success: function(data) {
                var events = [];
                data.forEach(function(event) {
                    events.push({
                        id: event.id,
                        tipe: event.tipe,
                        title: event.title,
                        start: event.start,
                        end: event.end,
                        description: event.description,
                        allDay: true
                    });
                });
                return events;
            }
        },
        displayEventTime: false,
        selectable: true,
        selectHelper: true,
        eventRender: function(event, element, view) {
            element.find('.fc-title').text(event.tipe + ': ' + event.title);
        },

        loading: function(isLoading) {
            if (!isLoading && $('#searchBar').val()) {
                var events = $('#calendar').fullCalendar('clientEvents');
                if (events.length > 0) {
                    events.sort(function(a, b) {
                        return new Date(a.start) - new Date(b.start);
                    });
                    $('#calendar').fullCalendar('gotoDate', events[0].start);
                }
            }
        },

        select: function(start, end, allDay) {
            Swal.fire({
                allowOutsideClick: false,
                title: "Add Event",
                html: `
                    <select id="swal-input-tipe" class="swal2-input" placeholder="Select event type">
                        <option value="" disabled selected>Select event type</option>
                        ${predefinedTipes.map(tipe => `<option value="${tipe}">${tipe}</option>`).join('')}
                    </select>
                    <input id="swal-input-title" class="swal2-input" placeholder="Event Title">
                    <textarea id="swal-input-description" class="swal2-input col-12" placeholder="Description"></textarea>`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                preConfirm: () => {
                    const tipe = document.getElementById('swal-input-tipe').value;
                    const title = document.getElementById('swal-input-title').value;
                    if (!tipe || !title) {
                        Swal.showValidationMessage(`Please select a type and enter a title`);
                    }
                    return { tipe: tipe, title: title, description: document.getElementById('swal-input-description').value };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var startFormatted = moment(start).format("Y-MM-DD");
                    var endFormatted = moment(end).subtract(1, 'days').format("Y-MM-DD");

                    $.ajax({
                        url: SITEURL + "/fullcalenderAjax",
                        data: {
                            tipe: result.value.tipe,
                            title: result.value.title,
                            description: result.value.description,
                            start: startFormatted,
                            end: endFormatted,
                            type: 'add'
                        },
                        type: "POST",
                        success: function(data) {
                            Swal.fire("Event Created Successfully");
                            calendar.fullCalendar('refetchEvents');
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
                            tipe: event.tipe,
                            title: event.title,
                            start: start,
                            description: event.description,
                            end: end,
                            type: 'update'
                        },
                        type: "POST",
                        success: function(response) {
                            Swal.fire("Event Updated Successfully");
                            calendar.fullCalendar('refetchEvents');
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
            var start = moment(event.start).format("Y-MM-DD");
            var end = event.end ? moment(event.end).format("Y-MM-DD") : start;
            Swal.fire({
                title: `${event.tipe}: ${event.title}`,
                icon: "info",
                html: `${event.description}`,
                showCancelButton: true,
                showDenyButton: true,
                showConfirmButton: true,
                confirmButtonText: "Edit",
                denyButtonText: "Delete",
                cancelButtonText: "Close",
                footer: '<button type="button" class="btn btn-ghost shadow-xl" id="send-wa">Kirim WA</button>'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: `Edit Event`,
                        html: `
                            <select id="swal-input-tipe" class="swal2-input">
                                ${predefinedTipes.map(tipe => `<option value="${tipe}" ${tipe === event.tipe ? 'selected' : ''}>${tipe}</option>`).join('')}
                            </select>
                            <input id="swal-input-title" class="swal2-input" placeholder="Event Title" value="${event.title}">
                            <textarea id="swal-input-description" class="swal2-input col-12" placeholder="Description">${event.description}</textarea>`,
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: 'Update',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            const tipe = document.getElementById('swal-input-tipe').value;
                            const title = document.getElementById('swal-input-title').value;
                            if (!tipe || !title) {
                                Swal.showValidationMessage(`Please select a type and enter a title`);
                            }
                            return { tipe: tipe, title: title, description: document.getElementById('swal-input-description').value };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            return $.ajax({
                                type: "POST",
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                    id: event.id,
                                    tipe: result.value.tipe,
                                    title: result.value.title,
                                    description: result.value.description,
                                    start: start,
                                    end: end,
                                    type: 'update'
                                }
                            }).then(response => {
                                calendar.fullCalendar('refetchEvents');
                                Swal.fire("Updated!", "Event has been updated.", "success");
                            }).catch(() => {
                                Swal.fire("Oops...", "Something went wrong!", "error");
                            });
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire({
                        title: `Delete Event`,
                        text: `Are you sure you want to delete "${event.tipe}: ${event.title}"?`,
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
                                    calendar.fullCalendar('refetchEvents');
                                    Swal.fire("Deleted!", `"${event.tipe}: ${event.title}" has been deleted.`, "success");
                                },
                                error: function() {
                                    Swal.fire("Oops...", "Something went wrong!", "error");
                                }
                            });
                        }
                    });
                }
            });

            $(document).on('click', '#send-wa', function() {
                let text = `
${moment(event.start).format('DD MMM YYYY')}
${event.tipe.toUpperCase()}: ${event.title.toUpperCase()}
description: ${event.description ? event.description.toUpperCase().replace(/\b\w+\b/g, function(word) { return word[0].toUpperCase() + word.slice(1).toLowerCase(); }) : '-'}
                    `;
                Swal.fire({
                    title: `${event.tipe.toUpperCase()}: ${event.title.toUpperCase()}`,
                    text: 'Are you sure you want to send a WA message?',
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, send it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url: `https://wa.mpdev.my.id/api/create-message?appkey=89beefce-4a61-4529-831f-d35de597141e&authkey=bKibYKFlHIS0WrDINddsI9VeZ0SLXM0NYQQEDyZQ7je1I5hoEy&to=628887770815&message=${encodeURIComponent(text)}`,
                            success: function(response) {
                                Swal.fire("Success!", "WA has been sent!", "success");
                            },
                            error: function() {
                                Swal.fire("Oops...", "Something went wrong!", "error");
                            }
                        });
                    }
                });
            });
        },
    });

    $('#searchBar').on('input', function() {
        var query = $(this).val();
        $('#calendar').fullCalendar('refetchEvents');
    });

    // Sticky Notes functionality
    const stickyNotesContainer = $('#sticky-notes-container');
    const addNoteBtn = $('#add-note-btn');

    function loadNotes() {
        $.get(SITEURL + "/notes", function(response) {
            stickyNotesContainer.empty();
            response.forEach(note => {
                createNoteCard(note);
            });
        });
    }

    function createNoteCard(noteData) {
        const noteCard = $('<div class="note-item"></div>');
        noteCard.attr('data-id', noteData.id);

        const textarea = $('<textarea></textarea>');
        textarea.val(noteData.content);
        textarea.on('input', function() {
            saveNoteContent(noteData.id, $(this).val());
        });

        const deleteBtn = $('<button class="delete-btn">&times;</button>');
        deleteBtn.on('click', function() {
            deleteNote(noteData.id, noteCard);
        });

        noteCard.append(textarea);
        noteCard.append(deleteBtn);
        stickyNotesContainer.append(noteCard);
    }

    addNoteBtn.on('click', function() {
        $.post(SITEURL + "/notes", {
            content: '',
        }, function(response) {
            if (response.success) {
                createNoteCard(response.note);
                toastr.success('New note added!');
            }
        });
    });

    function saveNoteContent(id, content) {
        $.post(SITEURL + "/notes", {
            id: id,
            content: content
        }, function(response) {
            if (response.success) {
                // toastr.success('Note content saved!');
            }
        });
    }

    function deleteNote(id, noteElement) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: SITEURL + "/notes",
                    type: 'DELETE',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            noteElement.remove();
                            toastr.success('Note deleted!');
                        }
                    }
                });
            }
        });
    }

    loadNotes();
});
    </script>

</body>

</html>
