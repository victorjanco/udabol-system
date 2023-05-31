/**
 * Created by Alejandro on 8/8/2017.
 */
$(document).ready(function () {
    get_service_list();
});
$(function () {
    /* Abre una ventana modal para registrar un nuevo servicio */
    $('#btn_new_service').click(function () {
        $('#frm_new_service')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        get_type_service_combo();
        $('#modal_new_service').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nuevo servicio se activa esta accion*/
    $('#frm_new_service').submit(function (event) {
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
                    $('#close_modal_new_service').click();
                    update_data_table($('#list_service'));
                } else if(respuesta.login === true){
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
            },
            error: function (error) {
                ajaxStop();
                console.log(error.responseText);
                swal('Error', 'Error al registrar los datos.', 'error');
            }
        });
    });
    /*Cuando da clic guardar de modal editar servicio se activa esta accion*/
    $('#frm_edit_service').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        // alert(form.serialize());
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success === true) {
                    $('.modal-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_edit_service').click();

                    update_data_table($('#list_service'));
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


/* Abre una ventana modal para editar el servicio */
function edit_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var nombre = rowData['nombre'];
    var descripcion = rowData['descripcion'];
    var precio = rowData['precio'];
    var tipo_servicio_id = rowData['tipo_servicio_id'];
    var nombre_tipo = rowData['nombre_tipo'];
    $('#id_servicio_edit').val(id);
    $('#nombre_servicio_edit').val(nombre);
    $('#descripcion_servicio_edit').val(descripcion);
    $('#precio_servicio_edit').val(precio);
    $('#tipo_servicio_servicio_edit').append('<option value="' + tipo_servicio_id + '">' + nombre_tipo + '</option>');
    get_type_service_enable(tipo_servicio_id);
    get_service_categoria(id);
    $('#modal_edit_service').modal({
        show: true,
        backdrop: 'static'
    });
}

/*Obtines la lista de todos los tipos de servicio para cargar en un combo*/
function get_type_service_enable(type_service_id) {
    $.post(siteurl('service/get_type_service_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                if (type_service_id != item.id) {
                    $('#tipo_servicio_servicio_edit').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                }
            });
        });
}

function get_service_categoria(service_id) {
    var datos = null;
    $.ajax({
        url: siteurl('service/get_service_category_by_service_id'),
        async: false,
        type: 'post',
        data: {service_id: service_id},
        success: function (data) {
            datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                if (item.categoria_id==1) {
                    $('#precio_servicio_alta_edit').val(item.precio_servicio);
                }else if (item.categoria_id==2) {
                    $('#precio_servicio_media_edit').val(item.precio_servicio);
                } else if (item.categoria_id==3) {
                    $('#precio_servicio_baja_edit').val(item.precio_servicio);
                }
            });

        }
    });
}

/*funcion eliminar servicio */
function delete_register(element) {
    delete_register_commom(element, 'service/disable_service');
}

/*funcion eliminar servicio */
function activate_service(element) {
    reactivate_register_commom(element, 'service/enable_service');
}
/*Para cargar la lista de servicios en el dataTable*/
function get_service_list() {
    var tabla = $('#list_service').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('service/get_service_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'tipo_servicio_id', class: 'text-center'},
            {data: 'nombre_tipo', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'precio', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 1,
            visible: false,
            searchable: false
        }, {
            targets: 6,
            render: function (data) {
                return state_crud(data);
            }
        }, {
            targets: 7,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(2, 'modal');
                } else {
                    return '<button type="button" onclick="activate_service(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}
