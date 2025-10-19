$(function () {
    getInspections();
    getSchedulingLogs();

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
                    const ii = i.inspector;
                    const ic = i.client;

                    loadInspectors(i.inspector_id, function () {
                        $('#scheduled_date').val(i.scheduled_date);
                        $('#reason').val(i.scheduling_logs?.[0]?.reason || '');
                        $('#status').val(i.status);
                        $('#remarks').val(i.remarks);
                        $('#id').val(i.id);
                        $('#inspections-modal').modal('show');
                    });
                }
                $('#modal-title').html('Update Inspection');
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

// -------------------------------------------------------------------------------
// The following is from Scheduling logs
// ------------------------------------------------------------------------

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
                { data: 'reason' },
                { data: 'user.username' },
                { data: 'created_at' },
            ]
        });
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error fetching users:', errorThrown);
    });
}