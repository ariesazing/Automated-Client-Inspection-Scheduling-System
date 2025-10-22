let calendar;
$(function () {
    initAvailabilityCalendar();
    getAvailabilitiesForTable();


    calendar.on('eventClick', function (info) {
        let id = info.event.id;

        $.ajax({
            url: BASE_URL + '/api/Availabilities/editAvailabilities/' + id,
            type: "GET",
            dataType: 'json'
        }).done(function (data) {
            if (data != '') {
                $('#is_available').val(String(data.is_available));
                $('#reason').val(data.reason);
                $('#id').val(data.id);
                $('#availabilities-modal').modal('show');
                $('#modal-title').html('Update ' + data.inspector.name);
            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                msgBox('error', errorThrown);
            });
    });
  
    $('#availabilities-form').on('submit', function (e) {
        e.preventDefault();

        let fd = new FormData(this);
        let id = $('#id').val();
        let url = BASE_URL + '/api/Availabilities/editAvailabilities/' + id;

        $.ajax({
            processData: false,
            contentType: false,
            data: fd,
            url: url,
            type: 'POST',
            dataType: 'json'
        }).done(function (data) {
            if (data.status == 'success') {
                getAvailabilities();
                msgBox(data.status, data.message);
                $('#availabilities-modal').modal('hide');
            } else {
                msgBox(data.status, data.message);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            msgBox('error', errorThrown);
        });
    });

    $('#availabilities-modal').on('shown.bs.modal', function () {
        setTimeout(function () {
            $('#reason').focus();
        }, 500);
    });

    $('#availabilities-modal').on('hidden.bs.modal', function () {
        $("#availabilities-form").trigger("reset");
        $("#id").val('');

        if (calendar) {
            calendar.refetchEvents();
        }
    });
});

function getAvailabilities() {
    $.ajax({
        url: BASE_URL + '/api/Availabilities/getAvailabilities/',
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        console.log('Availabilities loaded:', res.data);
        if (calendar) {
            calendar.refetchEvents();
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error fetching availabilities:', errorThrown);
    });
}
function getAvailabilitiesForTable() {
    $.ajax({
        url: BASE_URL + '/api/Availabilities/getAvailabilitiesForTable',
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {

        const availabilities = res.data;

        $('#availabilities-table').DataTable({
            responsive: true,
            destroy: true,
            order: [[0, 'asc']],
            data: availabilities,
            columns: [
                { data: 'inspector.name' },
                { data: 'available_date' },
                { data: 'is_available' },
                { data: 'reason' }
            ]
        });
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error fetching users:', errorThrown);
    });
}
/*
function getInspectorAvailabilities(inspector_id) {
    $.ajax({
        url: BASE_URL + '/api/Availabilities/getInspectorAvailabilities/' + inspector_id,
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        const isAvailable = res.data.is_available;
        $('#is_available').val(isAvailable ? '1' : '0'); 
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error fetching inspector availabilities:', errorThrown);
    });
}
*/

function initAvailabilityCalendar() {
    const calendarEl = $('#calendar')[0];
    if (!calendarEl) {
        console.error('Calendar element not found.');
        return;
    }

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        contentHeight: 600,
        businessHours: true,
        editable: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '08:00',
            endTime: '18:00',
        },
        events: function (fetchInfo, successCallback, failureCallback) {
            console.log('Fetching calendar events...');

            $.ajax({
                url: BASE_URL + '/api/Availabilities',
                method: 'GET',
                dataType: 'json'
            })
                .done(function (data) {
                    successCallback(data.data || data);
                })
                .fail(function (xhr, status, error) {
                    console.error('Error loading events:', error);
                    failureCallback(error);
                });
        }
    });
    calendar.render();
}