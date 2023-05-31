/**
 * Created by Ariel on 17/07/2017.
 */
$(document).ready(function () {

    get_activity_list();
    // get_office_list();


    $('#frm_registrar_actividad').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        ajaxStart('Registrando datos...');
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta.success === true) {
                    $('.error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_registro_actividad').click();

                    update_data_table($('#lista_actividad'));
                } else if(respuesta.login === true){
                    login_session();
                } else {
                    $('.error').remove();
                    if (respuesta.messages !== null) {
                        $.each(respuesta.messages, function (key, value) {
                            var element = $('#' + key);
                            var parent = element.parent();
                            parent.removeClass('form-line');
                            parent.addClass('form-line error');
                            parent.after(value);
                        });
                    } else {
                        swal('Error', 'Eror al registrar los datos.', 'error');
                    }
                }
            }
        });
    });

    $('#frm_editar_actividad').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        ajaxStart('Registrando datos...');
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta.success === true) {
                    $('.error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_editar_actividad').click();

                    update_data_table($('#lista_actividad'));
                } else if(respuesta.login === true){
                    login_session();
                } else {
                    $('.error').remove();
                    if (respuesta.messages !== null) {
                        $.each(respuesta.messages, function (key, value) {
                            var element = $('#' + key);
                            var parent = element.parent();
                            parent.removeClass('form-line');
                            parent.addClass('form-line error');
                            parent.after(value);
                        });
                    } else {
                        swal('Error', 'Eror al registrar los datos.', 'error');
                    }
                }
            }
        });
    });
});

function get_activity_list() {
    var tabla = $('#lista_actividad').DataTable({
		paging: true,
		info: true,
		filter: true,
		stateSave: true,
		processing: true,
		serverSide: true,
        'ajax': {
            "url": siteurl('company/get_activity_list'),
            "type": "post"
        },
        'columns': [
            {data: 'id'},
            {data: 'nombre'},
            {data: 'opciones', class: 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 2,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado != 0) {
                        return '<div class="btn-group">' +
                            '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
                            'OPCIONES <span class="caret"></span> ' +
                            '</button><ul class="dropdown-menu">' +
                            '<li><a data-toggle="modal" onclick="edit_register_activity(this)"><i class="fa fa-edit"></i> Editar</a></li>' +
                            '<li><a onclick="delete_register_activity(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                            '</ul> ' +
                            '</div>';
                    }
                }
            }
        ]
    });

}

function edit_register_activity(elemento) {
    $('.modal-error').remove();
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    $('#id_actividade').val(rowData['id']);
    $('#nombre_actividade').val(rowData['nombre']);

    $('#modal_editar_actividad').modal({
        show: true,
        backdrop: 'static'
    });
}

function delete_register_activity(elemento) {
    delete_register_commom(elemento,'company/delete_activity')
}

