/**
 * Created by Alejandro on 27/7/2017.
 */
$(function () {
    /* Abre una ventana modal para registrar una nueva notificacion de recepcion*/
    $('#btn_new_reception_notification').click(function () {
        $('#frm_new_notification')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_notification').modal({
            show: true,
            backdrop: 'static'
        });
    });

    $('#btn_new_reception_pago').click(function () {
        $('#frm_new_pago')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_pago').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nueva notificacion para se activa esta accion*/
    $('#frm_new_notification').submit(function (event) {
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
                    $('#close_modal_new_notification').click();

                    update_data_table($('#list_specific_reception'));
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

    $('#frm_new_pago').submit(function (event) {
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
                    $('#close_modal_new_pago').click();

                    update_data_table($('#list_reception_pago'));
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
    $('#frm_edit_notification').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success === true) {
                    $('.modal-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_edit_notification').click();

                    update_data_table($('#list_specific_reception'));
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

function calcular_saldo() {
    var pago = parseFloat($('#pago').val());
    var monto_total = parseFloat($('#monto_total').val());
    var total_pagado = parseFloat($('#total_pagado').val());
    console.log('pago ' + pago);
    console.log('monto total ' + monto_total);
    console.log('total pagado ' + total_pagado);
    console.log('saldo total ' + saldo_total);
    var saldo_total = monto_total - (total_pagado + pago);
    console.log('pago ' + pago);
    console.log('monto total ' + monto_total);
    console.log('total pagado ' + total_pagado);
    console.log('saldo total ' + saldo_total);
    $('#saldo_total').val(saldo_total);
}
function view_register(element) {

    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    $.ajax({
        url: siteurl('customer/get_customer_id'),
        type: 'post',
        data: 'id=' + rowData['id'],
        dataType: 'json',
        success: function (response) {
            $('#ci_cliente_view').val(response.ci);
            $('#nit_cliente_view').val(response.nit);
            $('#nombre_cliente_view').val(response.nombre);
            $('#telefono1_cliente_view').val(response.telefono1);
            $('#telefono2_cliente_view').val(response.telefono2);
            $('#direccion_cliente_view').val(response.direccion);
            $('#email_cliente_view').val(response.email);
        }
    });

    $('#modal_view_customer').modal({
        show: true,
        backdrop: 'static'
    });
}

/* Abre una ventana modal para editar tipo notificacion */
function edit_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var glosa = rowData['glosa'];
    var fecha = rowData['fecha_recepcionada'];
    var tipo_notificacion_id = rowData['tipo_notificacion_id'];
    var tipo = rowData['tipo'];
    $('#id_notification_edit').val(id);
    $('#glosa_notificacion_edit').val(glosa);
    $('#fecha_notificacion_edit').val(fecha);
    $('#tipo_notificacion_edit').append('<option value="' + tipo_notificacion_id + '">' + tipo + '</option>');
    get_type_notification_enable(tipo_notificacion_id);
    $('#modal_edit_notification').modal({
        show: true,
        backdrop: 'static'
    });
}

/*Obtines la lista de todas la sucursal para cargar en un combo*/
function get_type_notification_enable(tipo_notificacion_id) {
    $.post(siteurl('type_notification/get_type_notification_reception_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                if (tipo_notificacion_id != item.id) {
                    $('#tipo_notificacion_edit').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                }
            });
        });
}
/*funcion eliminar tipo de notificaciones */
function delete_register(element) {
    delete_register_commom(element, 'reception_notification/disable_reception_notification');
}

/*carga la lista de los tipo de notificaciones al datatable*/
function get_reception_notification_list() {
    var tabla = $('#list_notification_reception').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('reception_notification/get_reception_notification_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'tipo', class: 'text-center'},
            {data: 'codigo_recepcion', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'glosa', class: 'text-center'},
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
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
                    return load_buttons_crud(1, 'modal');
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

/*carga la lista de los tipo de notificaciones al datatable*/
function get_specific_reception_list() {
    var tabla = $('#list_specific_reception').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('reception_notification/get_reception_notification_specific_list'),
            type: 'post',
            data: {id_reception: $('#id_reception').val()}
        },
        columns: [
            {data: 'id'},
            {data: 'tipo_notificacion_id'},
            {data: 'tipo', class: 'text-center'},
            {data: 'codigo_recepcion', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'glosa', class: 'text-center'},
            {data: 'fecha_registro', class: 'text-center'},
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
            targets: 7,
            render: function (data) {
                return state_crud(data);
            }
        }, {
            targets: 8,
            orderable: false,
            render: function (data, type, row) {

                if (row.estado != 0) {
                    return load_buttons_crud(3, 'modal');
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
/*cargar la lista de los pagos de una recepcion*/