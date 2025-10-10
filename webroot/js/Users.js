
$(function () {
    getUsers();


    $('#add').on('click', function (e) {
        e.preventDefault();
        $('.modal-title').html('Add User');
        $('#users-modal').modal('show');
    });
    $('#users-table').on('click', '.edit', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update User');
        $.ajax({
            url: BASE_URL + '/api/Users/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function (data) {
                if (data != '') {
                    $('#username').val(data.username);
                    $('#role').val(data.role);
                    $('#status').val(data.status);
                    $('#id').val(data.id);
                    $('#users-modal').modal('show');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                msgBox('error', errorThrown);
            });
    });

    $('#users-form').submit(function (e) {
        e.preventDefault();
        let fd = new FormData(this);
        let id = $('#id').val();
        let url = '';
        if (id == '') {
            url = BASE_URL + '/api/Users/add';
        } else {
            url = BASE_URL + '/api/Users/edit/' + id;
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
                getUsers();
                msgBox(data.status, data.message);
                $('#users-modal').modal('hide');
            } else {
                msgBox(data.status, data.message);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            msgBox('error', errorThrown);
        });
    });

    $('#users-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Users/delete/' + id,
                    type: "DELETE",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function (data, textStatus, jqXHR) {
                        if (data.status == 'success') {
                            getUsers();
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

    $('#users-modal').on('shown.bs.modal', function () {
        setTimeout(function () {
            $('#username').focus();
        }, 500);
    });

    $('#users-modal').on('hidden.bs.modal', function () {
        $("#users-form").trigger("reset");
        $("#id").val('');
    });
});

function getUsers() {
    $.ajax({
        url: BASE_URL + '/api/Users/getUsers',
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        //console.log('Fetched users:', res);

        const users = res.data;

        $('#users-table').DataTable({
            responsive: true,
            destroy: true,
            order: [[0, 'asc']],
            data: users,
            columns: [
                { data: 'username' },
                { data: 'role' },
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

