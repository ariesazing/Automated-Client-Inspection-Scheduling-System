$(function () {
    initAvailabilityCalendar()
    getInspections();
    getSchedulingLogs();

    /*
    calendar.on('eventClick', function (info) {
        let id = info.event.id;

        $.ajax({
            url: BASE_URL + '/api/Inspections/getInspectorInspections/' + id,
            type: "GET",
            dataType: 'json'
        }).done(function (data) {
            if (data != '') {
                $('#id').val(data.id);
                $('#inspectionsCalendar-modal').modal('show');
                $('#modal-title').html(data.inspector.name + ' at ' + data.scheduled_date);
            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                msgBox('error', errorThrown);
            });
    });
*/
    $('#inspections-table').on('click', '.edit', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: BASE_URL + '/api/Inspections/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function (data) {
                if (data != '') {
                    const i = data.inspection;

                    loadInspectors(i.inspector_id, function () {
                        let scheduledDate = formatDateForInput(data.inspection.scheduled_date);
                        $('#scheduled_date').val(scheduledDate);
                        $('#status').val(i.status);
                        $('#remarks').val(i.remarks);
                        $('#id').val(i.id);
                        $('#inspections-modal').modal('show');
                    });
                }
                $('#modal-title').html('Update Inspection: ' + data.inspection.client.establishment_name);
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                msgBox('error', errorThrown);
            });
    });

    $('#inspections-form').submit(function (e) {
        e.preventDefault();

        let fd = new FormData(this);
        let id = $('#id').val();
        let url = BASE_URL + '/api/Inspections/edit/' + id;

        $.ajax({
            processData: false,
            contentType: false,
            data: fd,
            url: url,
            type: 'POST',
            dataType: 'json'
        }).done(function (data) {
            if (data.status == 'success') {
                getInspections();
                msgBox(data.status, data.message);
                $('#inspections-modal').modal('hide');
            } else {
                msgBox(data.status, data.message);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            msgBox('error', errorThrown);
        });
    });

    $('#inspections-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Inspections/delete/' + id,
                    type: "DELETE",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function (data, textStatus, jqXHR) {
                        if (data.status == 'success') {
                            getInspections();
                            msgBox(data.status, data.message);
                        } else {
                            msgBox(data.status, data.message);
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        msgBox('error', errorThrown);
                    });
            }
        });
    });

    $('#inspectionsCalendar-modal').on('shown.bs.modal', function () {

    });

    $('#inspectionsCalendar-modal').on('hidden.bs.modal', function () {

    });

    $('#inspections-modal').on('shown.bs.modal', function () {
        setTimeout(function () {
            $('#remarks').focus();
        }, 500);
    });

    $('#inspections-modal').on('hidden.bs.modal', function () {
        $("#inspections-form").trigger("reset");
        $("#id").val('');
    });
});

function loadInspectors(selectedInspectorsId = null, callback = null) {
    $.ajax({
        url: BASE_URL + '/api/Inspectors',
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        let options = '<option value="">-- Select Inspector --</option>';
        res.forEach(function (inspector) {
            options += `<option value="${inspector.id}" ${inspector.id == selectedInspectorsId ? 'selected' : ''}>${inspector.name}</option>`;
        });
        $('#inspector-id').html(options);
        if (callback) callback();
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error fetching users:', errorThrown);
    });
}

function getInspections() {
    $.ajax({
        url: BASE_URL + '/api/Inspections/getInspections',
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        //console.log('Fetched inspections:', res);

        const inspections = res.data;

        $('#inspections-table').DataTable({
            responsive: true,
            destroy: true,
            order: [[0, 'asc']],
            data: inspections,
            columns: [
                { data: 'id' },
                { data: 'client.establishment_name' },
                { data: 'inspector.name' },
                { data: 'scheduled_date' },
                { data: 'actual_date' },
                { data: 'status' },
                { data: 'remarks' },
                { data: 'client.risk_level' },
                {
                    data: null,
                    render: function (data) {
                        return `
                            <div style="text-align:center;">
                                <a href="#" class="edit" data-id="${data.id}"><i class="fas fa-pen"></i></a> |
                                <a href="#" class="delete text-danger" data-id="${data.id}"><i class="fa fa-trash"></i></a>
                            </div>`;
                    }
                }
            ]
        });
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error fetching users:', errorThrown);
    });
}

function getSchedulingLogs() {
    $.ajax({
        url: BASE_URL + '/api/SchedulingLogs/getSchedulingLogs',
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        //console.log('Fetched inspections:', res);

        const scheduling_logs = res.data;

        $('#schedulingLogs-table').DataTable({
            responsive: true,
            destroy: true,
            order: [[0, 'asc']],
            data: scheduling_logs,
            columns: [
                { data: 'inspection_id' },
                { data: 'old_date' },
                { data: 'new_date' },
                { data: 'user.username' },
                { data: 'created_at' },
            ]
        });
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
                url: BASE_URL + '/api/Inspections',
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

// Helper function to format date for HTML input
function formatDateForInput(dateString) {
    if (!dateString) return '';

    // If it's already in YYYY-MM-DD format, return as is
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
        return dateString;
    }

    // If it's a different format, parse and reformat
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return ''; // Invalid date

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}
