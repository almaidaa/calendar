/*************  ✨ Windsurf Command 🌟  *************/
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
            max-width: 650px;
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
        <a href="http://wa.mpdev.my.id/api/create-message?appkey=89beefce-4a61-4529-831f-d35de597141e&authkey=bKibYKFlHIS0WrDINddsI9VeZ0SLXM0NYQQEDyZQ7je1I5hoEy&to=628887770815&message=Example message" target="_blank" class="button-card">
            <h3>Send WA</h3>
            <p>Send a WhatsApp message.</p>
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
                "Libur",
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
                    }
                },
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
                    <select id="swal-input1" class="swal2-input" placeholder="Select event title">
                        <option value="" disabled selected>Select event title</option>
                        ${predefinedTitles.map(title => `<option value="${title}">${title}</option>`).join('')}
                    </select>
                    <textarea id="swal-input2" class="swal2-input col-12" placeholder="Description"></textarea>`,
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
                                    Swal.fire("Oops...", "Something went wrong!",
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
                        cancelButtonText: "Close",
                        footer: '<button type="button" class="swal2-confirm swal2-styled" id="send-wa">Send WA</button>'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: `${event.title}`,
                                html: `
                            <select id="swal-input1" class="swal2-input">
                                ${predefinedTitles.map(title => `<option value="${title}" ${title === event.title ? 'selected' : ''}>${title}</option>`).join('')}
                            </select>
                            <textarea id="swal-input2" class="swal2-input col-12" placeholder="Or enter a custom title">${event.description}</textarea>`,
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
                                        event.start = moment(start);
                                        event.end = moment(end);

                                        calendar.fullCalendar('updateEvent', event);

                                        Swal.fire("Updated!", "Event title has been updated.", "success");
                                    }).catch(() => {
                                        Swal.fire("Oops...", "Something went wrong!", "error");
                                    });
                                }
                            });
                        } else if (result.isDenied) {
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
                                            calendar.fullCalendar('removeEvents', event.id);
                                            Swal.fire("Deleted!", `"${event.title}" has been deleted.`, "success");
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
${event.title.toUpperCase()}
description: ${event.description ? event.description.toUpperCase().replace(/\b\w+\b/g, function(word) { return word[0].toUpperCase() + word.slice(1).toLowerCase(); }) : '-'}
                            `;
                        Swal.fire({
                            title: `${event.title.toUpperCase()}`,
                            text: 'Yakin Mau mengirimkan WA?',
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

/*******  7f2e6940-f1b0-4c52-ad9a-b6eaf150733b  *******/
