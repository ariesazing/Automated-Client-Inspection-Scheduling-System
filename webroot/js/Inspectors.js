$(function () {
    getInspectors();

    $('#add').on('click', function (e) {
        e.preventDefault();
        $('#modal-title').html('Add Inspector');
        loadUsers(null, function () {
            // show modal only after users are loaded
            $('#inspectors-modal').modal('show');
        });
    });
    $('#inspectors-table').on('click', '.edit', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: BASE_URL + '/api/Inspectors/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function (data) {
                if (data != '') {
                    loadUsers(data.user_id, function () {
                        // populate other inputs after users are loaded
                        $('#name').val(data.name);
                        $('#specialization').val(data.specialization);
                        $('#id').val(data.id);
                        $('#inspectors-modal').modal('show');
                    });
                }
                $('#modal-title').html('Update Inspector: ' + data.name);
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                msgBox('error', errorThrown);
            });
    });

    $('#inspectors-form').submit(function (e) {
        e.preventDefault();
        let fd = new FormData(this);
        let id = $('#id').val();
        let url = '';
        if (id == '') {
            url = BASE_URL + '/api/Inspectors/add';
        } else {
            url = BASE_URL + '/api/Inspectors/edit/' + id;
        }

        $.ajax({
            processData: false,
            contentType: false,
            data: fd,
            url: url,
            type: 'POST',
            dataType: 'json'
        }).done(function (data) {
            if (data.status == 'success') {
                getInspectors();
                msgBox(data.status, data.message);
                $('#inspectors-modal').modal('hide');
            } else {
                msgBox(data.status, data.message);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            msgBox('error', errorThrown);
        });
    });

    $('#inspectors-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Inspectors/delete/' + id,
                    type: "DELETE",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function (data, textStatus, jqXHR) {
                        if (data.status == 'success') {
                            getInspectors();
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

    $('#inspectors-modal').on('shown.bs.modal', function () {
        setTimeout(function () {
            $('#name').focus();
        }, 500);
    });

    $('#inspectors-modal').on('hidden.bs.modal', function () {
        $("#inspectors-form").trigger("reset");
        $("#id").val('');
    });
});

function loadUsers(selectedUserId = null, callback = null) {
    $.ajax({
        url: BASE_URL + '/api/Users',
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        let options = '<option value="">-- Select User --</option>';

        res.forEach(function (user) {
            if (user.role === 'inspector' && user.status === 'active') {
                options += `<option value="${user.id}" ${user.id == selectedUserId ? 'selected' : ''}>${user.username}</option>`;
            }
        });

        $('#user-id').html(options);
        if (callback) callback();
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error fetching users:', errorThrown);
    });
}

function getInspectors() {
    $.ajax({
        url: BASE_URL + '/api/Inspectors/getInspectors',
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        //console.log('Fetched inspectors:', res);

        const inspectors = res.data;

        $('#inspectors-table').DataTable({
            responsive: true,
            destroy: true,
            order: [[0, 'asc']],
            data: inspectors,
            columns: [
                { data: 'user.username' },
                { data: 'name' },
                { data: 'specialization' },
                { data: 'status' },
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