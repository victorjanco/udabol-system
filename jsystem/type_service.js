/**
 * Created by Alejandro on 8/8/2017.
 */
$(document).ready(function () {
    get_type_service_list();
    get_type_service_combo();
})
$(function () {
    /* Abre una ventana modal para registrar un nuevo tipo servicio */
    $('#btn_new_type_service').click(function () {
        $('#frm_new_type_service')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_type_service').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nuevo tipo servicio se activa esta accion*/
    $('#frm_new_type_service').submit(function (event) {
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
                    $('#close_modal_new_type_service').click();
                    get_type_service_combo();
                    update_data_table($('#list_type_service'));
                } else if(respuesta.login) {
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

    /*Cuando da clic guardar de modal editar servicio se activa esta accion*/
    $('#frm_edit_type_service').submit(function (event) {
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
                    $('#close_modal_edit_type_service').click();
                    get_type_service_combo();
                    update_data_table($('#list_type_service'));

                } else if(respuesta.login === true) {
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

/*Obtines la lista de todos los tipos de servicio para cargar en un combo*/
function get_type_service_combo() {
    $('#tipo_servicio_servicio').empty();
    $('#tipo_servicio_servicio').append('<option value="0">Seleccione Tipo de servicio</option>');
    $.post(siteurl('service/get_type_service_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                    $('#tipo_servicio_servicio').append('<option value="' + item.id + '">' + item.nombre + '</option>');
            });
        });

}
/* Abre una ventana modal para editar tipo servicio */
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
    $('#id_tipo_servicio_edit').val(id);
    $('#nombre_tipo_servicio_edit').val(nombre);
    $('#descripcion_tipo_servicio_edit').val(descripcion);
    $('#modal_edit_type_service').modal({
        show: true,
        backdrop: 'static'
    });
}

/*funcion eliminar tipo de servicio */
function delete_register_type(element) {
    delete_register_commom(element, 'service/disable_type_service');
}

/*carga la lista de los tipo de servicios al datatable*/
function get_type_service_list() {
    var tabla = $('#list_type_service').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('service/get_type_service_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
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
            targets: 3,
            render: function (data) {
                return state_crud(data);
            }
        }, {
            targets: 4,
            orderable:false,
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
