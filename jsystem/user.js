/**
 * Created by Ariel on 13/07/2017.
 */
$(document).ready(function () {
    get_users_list();

    $('#ciudad').hide();
    // Para Verificacion de las contraseñas
    $('#clave').focus(function () {
        $('#msj_pass').hide();
        $('#confirmar_clave').val('');
    });

    $('#confirmar_clave').focusout(function () {
        var pass = $('#clave').val();
        if ($(this).val() != '') {
            if (pass != $(this).val()) {
                $('#msj_pass').show();
            } else {
                $('#msj_pass').hide();
            }
        }
    });
    //--------------------------------------------------

    //Evento despues de seleccionar departamento en el modal
    // de registro de sucursal
    $('#departamento').change(function () {
        if ($(this).val() === '0') {
            $('#ciudad').hide();
        } else {
            $('#ciudad').show();
            $('#ciudad').focus();
        }
    });


    $('#frm_new_user').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        var type = 'guardar';
        if ($('#imprimir').click()) {
            type = 'imprimir'
        }
        ajaxStart('Guardando registros, por favor espere');
        $.ajax({
            url: siteurl('user/registrer_user'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                console.log(respuesta);
                if (respuesta.success === true) {
                    $('.abm-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    // Si es un vista de formulario
                    // if (!$(this).hasClass('frm-noreset')) {
                    //     $(form)[0].reset();
                    // }

                    // // if ($(this).hasClass('frm-datatable')) {
                    // //     // reload_table();
                    // // }
                    location.href = site_url + "user";
                } else if(respuesta.login === true){
                    login_session();
                } else {
                    $('.abm-error').remove();
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

    $('#frm_edit_user').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        var type = 'guardar';
        if ($('#imprimir').click()) {
            type = 'imprimir'
        }
        ajaxStart("Guardando...");
        $.ajax({
            url: siteurl('user/edit_user'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta.success === true) {
                    $('.abm-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    location.href = site_url + "user";
                } else if(respuesta.login === true){
                    login_session();
                } else {
                    $('.abm-error').remove();
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

function get_users_list() {
    var tabla =$('#users_list').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'ajax': {
            "url": siteurl('user/get_users'),
            "type": "post",
            // dataSrc: ''
        },
        'columns': [
            {data: 'id'},
            {data: 'ci', class: 'text-center'},
            {data: 'nombre'},
            {data: 'telefono', class: 'text-center'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'usuario', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            }, {
                targets: 6,
                render: function (data) {
                    return state_crud(data);
                }
            },
            {
                targets: 7,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado != 0) {
                        return load_buttons_crud(2, 'formulario');
                    } else {
                        return '<button type="button" onclick="activate_user(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                    }
                }
            }
        ],
        "order": [[0, "asc"]]
    });
    tabla.ajax.reload();
}

function activate_user(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    // var asignacion_dosificacion_id= rowData['asignacion_dosificacion_id'];

    swal({
            title: "Esta seguro que desea activar el Usuario?",
            text: "El estado del registro cambiara",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, activar usuario!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.post(site_url + 'user/activate_user', {id:id}).done(function (response) {
                    if (response) {
                        swal("Activado!", "El Usuario ha sido activado.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "No se pudo reactivar el Usuario", "error");
                    }
                });
            }
            /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
        });
}

function edit_register_form(element) {
    edit_register_commom(element, 'user/edit');
}

function delete_register(element) {
    delete_register_commom(element,'user/delete_user')
}
