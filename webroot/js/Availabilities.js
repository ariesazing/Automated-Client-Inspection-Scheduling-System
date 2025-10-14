let calendar;
$(function () {
    initAvailabilityCalendar();

    calendar.on('eventClick', function (info) {
        let id = info.event.id;
        let inspector_id = info.event.extendedProps.inspector_id;

        getInspectorAvailabilities(inspector_id);
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
            }
            $('#modal-title').html('Update ' + data.inspector.name);
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                msgBox('error', errorThrown);
            });

        console.log(info.event);
        console.log('Inspector ID:', info.event.extendedProps.inspector_id);
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
    });
});

function getInspectorAvailabilities(inspector_id) {
    $.ajax({
        url: BASE_URL + '/api/Availabilities/getInspectorAvailabilities/' + inspector_id,
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        const availabilities = res.data;
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error fetching users:', errorThrown);
    });
}
function initAvailabilityCalendar() {
    const calendarEl = $('#calendar')[0];
    if (!calendarEl) {
        console.error('Calendar element not found.');
        return;
    }

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
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