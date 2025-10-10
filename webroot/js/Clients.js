$(function () {
    getClients();

    $('#add').on('click', function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Client');
        $('#clients-modal').modal('show');
    });
    $('#clients-table').on('click', '.edit', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Client');
        $.ajax({
            url: BASE_URL + '/api/Clients/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function (data) {
                if (data != '') {
                    $('#owner-name').val(data.owner_name);
                    $('#establishment-name').val(data.establishment_name);
                    $('#address').val(data.address);
                    $('#type').val(data.type);
                    $('#risk-level').val(data.risk_level);
                    $('#status').val(data.status);
                    $('#id').val(data.id);
                    $('#clients-modal').modal('show');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                msgBox('error', errorThrown);
            });
    });

    $('#clients-form').submit(function (e) {
        e.preventDefault();
        let fd = new FormData(this);
        let id = $('#id').val();
        let url = '';
        if (id == '') {
            url = BASE_URL + '/api/Clients/add';
        } else {
            url = BASE_URL + '/api/Clients/edit/' + id;
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
                getClients();
                msgBox(data.status, data.message);
                $('#clients-modal').modal('hide');
            } else {
                msgBox(data.status, data.message);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            msgBox('error', errorThrown);
        });
    });

    $('#clients-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Clients/delete/' + id,
                    type: "DELETE",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function (data, textStatus, jqXHR) {
                        if (data.status == 'success') {
                            getClients();
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

    $('#clients-modal').on('shown.bs.modal', function () {
        setTimeout(function () {
            $('#owner-name').focus();
        }, 500);
    });

    $('#clients-modal').on('hidden.bs.modal', function () {
        $("#clients-form").trigger("reset");
        $("#id").val('');
    });
});


function getClients() {
    $.ajax({
        url: BASE_URL + '/api/Clients/getClients',
        type: 'GET',
        dataType: 'json'
    }).done(function (res) {
        //console.log('Fetched clients:', res);

        const clients = res.data;

        $('#clients-table').DataTable({
            responsive: true,
            destroy: true,
            order: [[0, 'asc']],
            data: clients,
            columns: [
                { data: 'owner_name' },
                { data: 'establishment_name' },
                { data: 'address' },
                { data: 'type' },
                { data: 'risk_level' },
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