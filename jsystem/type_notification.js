/**
 * Created by Alejandro on 27/7/2017.
 */
$(document).ready(function () {
    get_type_notification_reception_list();
    get_type_notification_combo();
})
$(function () {
    /* Abre una ventana modal para registrar un nuevo tipo de notificacion */
    $('#btn_new_type_notification').click(function () {
        $('#frm_new_type_notification')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_type_notification').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nuevo tipo notificacion para se activa esta accion*/
    $('#frm_new_type_notification').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        ajaxStart('Guardando registros, por favor espere');
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta.success === true) {
                    $('.modal-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_new_type_notification').click();
                    get_type_notification_combo();
                    update_data_table($('#list_type_notification_reception'));
                } else if (respuesta.login === true){
                    login_session();
                } else {
                    $('.modal-error').remove();
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

    /*Cuando da clic guardar de modal editar el tipo de notificacion se activa esta accion*/
    $('#frm_edit_type_notification').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        ajaxStart('Guardando registros, por favor espere');
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta.success === true) {
                    $('.modal-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_edit_type_notification').click();
                    get_type_notification_combo();
                    update_data_table($('#list_type_notification_reception'));
                } else if (respuesta.login === true){
                    login_session();
                } else {
                    $('.modal-error').remove();
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

})

/*Obtines la lista de todos los tipos de notificacion para cargar en un combo*/
function get_type_notification_combo() {
    $('#tipo_notificacion').empty();
    $('#tipo_notificacion').append('<option value="0">Seleccione Tipo de Notificacion</option>');
    $.post(siteurl('type_notification/get_type_notification_reception_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                $('#tipo_notificacion').append('<option value="' + item.id + '">' + item.nombre + '</option>');
            });
        });

}
/* Abre una ventana modal para editar tipo notificacion */
function edit_register_type(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var nombre = rowData['nombre'];
    var descripcion = rowData['descripcion'];
    $('#id_tipo_notificacion_edit').val(id);
    $('#nombre_tipo_notificacion_edit').val(nombre);
    $('#descripcion_tipo_notificacion_edit').val(descripcion);
    $('#modal_edit_type_notification').modal({
        show: true,
        backdrop: 'static'
    });
}

/*funcion eliminar tipo de notificaciones */
function delete_register_type(element) {
    delete_register_commom(element, 'type_notification/disable_type_notification');
}

/*carga la lista de los tipo de notificaciones al datatable*/
function get_type_notification_reception_list() {
    var tabla = $('#list_type_notification_reception').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('type_notification/get_type_notification_reception_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'tipo', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 1,
            render: function (data) {
                return type_notification(data);
            }
        }, {
            targets: 4,
            render: function (data) {
                return state_crud(data);
            }
        }, {
            targets: 5,
            orderable: false,
            render: function (data, type, row) {

                if (row.estado != 0) {
                    var botones = '<div class="btn-group">' +
                        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
                        'OPCIONES <span class="caret"></span> ' +
                        '</button><ul class="dropdown-menu">';
                    botones = botones + '<li><a data-toggle="modal" onclick="edit_register_type(this)"><i class="fa fa-edit"></i> Editar</a></li>' +
                        '<li><a data-toggle="modal" onclick="delete_register_type(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                        '</ul> ' +
                        '</div>';
                    return botones;
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}