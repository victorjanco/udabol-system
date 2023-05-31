/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 13/05/2019
 * Time: 11:43 AM
 */
// $(document).ready(function () {
//     get_product_reason_list();
// });

$(document).ready(function () {
    $('#frm_new_reason').submit(function (event) {
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
                    $('.modal-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_new_reason').click();

                    update_data_table($('#product_reason_list'));
                } else if (respuesta.login === true) {
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

    $('#frm_new_preci').submit(function (event) {
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
                    $('.modal-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_new_preci').click();

                    update_data_table($('#product_reason_list'));
                } else if (respuesta.login === true) {
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
    $('#branch_office_report').change(function (event) {
        get_warehouse_by_branch_office();
        get_product_reason_list();
    });

    $('#warehouse_report').change(function (event) {
        get_product_reason_list();
    });
});

function get_product_reason_list() {
    var tabla = $('#product_reason_list').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'ajax': {
            "url": siteurl('product/get_product_reason_list'),
            "type": "post",
            "data": {
                branch_office_report: $("#branch_office_report").val(),
                warehouse_report: $("#warehouse_report").val()
            }
        },
        'columns': [
            {data: 'id'},
            {data: 'nombre_sucursal'},
            {data: 'almacen'},
            {data: 'codigo', class: 'text-center'},
            {data: 'nombre_comercial'},
			{data: 'nombre_modelo'},
			{data: 'dimension'},
            {data: 'subgrupo'},
            {data: 'nombre_marca'},
            {data: 'stock', class: 'text-center'},
            {data: 'precio_costo', class: 'text-center'},
            {data: 'precio_venta', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 12,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado == 0) {
                        return "<span class='label label-danger'><i class='fa fa-times'></i> INACTIVO</span>"
                    } else {
                        return "<span class='label label-success'><i class='fa fa-check'></i> ACTIVO</span>"
                    }
                }
            },
            {
                targets: 13,
                orderable: false,
                render: function (data, type, row) {
                    // if(row.estado == 0){
                    //     return load_buttons_crud(23, 'formulario');
                    // }else{
                        return load_buttons_crud(22, 'formulario');
                    // }
                    //  '<a onclick="new_register_form(this)" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Motivo</a>'
                    // return '<li><a onclick="imprimir_codigo(this)" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> Imprimir </a></li>'
                }
            }
        ],
        "order": [[0, "asc"]],
    });
    tabla.ajax.reload();
}

function get_inactive_product_reason_list() {
    var tabla = $('#inactive_product_reason_list').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'ajax': {
            "url": siteurl('product/get_inactive_product_reason_list'),
            "type": "post",
        },
        'columns': [
            {data: 'id'},
            {data: 'nombre_sucursal'},
            {data: 'almacen'},
            {data: 'codigo', class: 'text-center'},
            {data: 'nombre_comercial'},
			{data: 'nombre_modelo'},
			{data: 'dimension'},
            {data: 'subgrupo'},
            {data: 'nombre_marca'},
            {data: 'stock', class: 'text-center'},
            {data: 'precio_costo', class: 'text-center'},
            {data: 'precio_venta', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 12,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado == 0) {
                        return "<span class='label label-danger'><i class='fa fa-times'></i> INACTIVO</span>"
                    } else {
                        return "<span class='label label-success'><i class='fa fa-check'></i> ACTIVO</span>"
                    }
                }
            },
            {
                targets: 13,
                orderable: false,
                render: function (data, type, row) {
                    // if(row.estado == 0){
                        return load_buttons_crud(23, 'formulario');
                    // }else{
                        // return load_buttons_crud(22, 'formulario');
                    // }
                    //  '<a onclick="new_register_form(this)" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Motivo</a>'
                    // return '<li><a onclick="imprimir_codigo(this)" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> Imprimir </a></li>'
                }
            }
        ],
        "order": [[0, "asc"]],
    });

}

function imprimir_codigo(seleccionado) {
    var table = $(seleccionado).closest('table').DataTable();
    var current_row = $(seleccionado).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();

    mostrar_ventana(site_url + 'product/imprimir_codigo/' + data['inventario_sucursal_id'], 'Impresion', '1500', '720');
}
function delete_product(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['inventario_sucursal_id'];
    var product_id = rowData['id'];
    var warehouse_id = rowData['almacen_id'];
    // var asignacion_dosificacion_id= rowData['asignacion_dosificacion_id'];

    swal({
            title: "Esta seguro que desea Eliminar el Producto?",
            text: "El producto se eliminara del almacen",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminar producto!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.post(site_url + 'inventory/delete_product', {id:id, product_id:product_id, warehouse_id:warehouse_id}).done(function (response) {
                    if (response) {
                        swal("Activado!", "El Producto ha sido Elimindao.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "No se pudo Eliminar el Producto", "error");
                    }
                });
            }
            /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
        });
}
function activate_product(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['inventario_sucursal_id'];
    var product_id = rowData['id'];
    var warehouse_id = rowData['almacen_id'];
    // var asignacion_dosificacion_id= rowData['asignacion_dosificacion_id'];

    swal({
            title: "Esta seguro que desea activar el Producto?",
            text: "El estado del registro cambiara",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, activar producto!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.post(site_url + 'inventory/activate_product', {id:id, product_id:product_id, warehouse_id:warehouse_id}).done(function (response) {
                    if (response) {
                        swal("Desactivado!", "El Producto ha sido activado.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "No se pudo activar el Producto", "error");
                    }
                });
            }
            /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
        });
}
function disable_product(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['inventario_sucursal_id'];
    var product_id = rowData['id'];
    var warehouse_id = rowData['almacen_id'];
    // var asignacion_dosificacion_id= rowData['asignacion_dosificacion_id'];

    swal({
            title: "Esta seguro que desea Desactivar el Producto?",
            text: "El estado del registro cambiara",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, desactivar producto!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.post(site_url + 'inventory/disable_product', {id:id, product_id:product_id, warehouse_id:warehouse_id}).done(function (response) {
                    if (response) {
                        swal("Desactivado!", "El Producto ha sido desactivado.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "No se pudo desactivar el Producto", "error");
                    }
                });
            }
            /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
        });
}

function preci_product(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['inventario_sucursal_id'];
    var product_id = rowData['id'];
    var precio_costo = rowData['precio_costo'];
    var precio_venta = rowData['precio_venta'];
    var precio_venta1 = rowData['precio_venta_1'];
    var precio_venta2 = rowData['precio_venta_2'];
    var precio_venta3 = rowData['precio_venta_3'];


    $('#inventory_branch_id').val(id);
    $('#cost_price').val(precio_costo);
    $('#sale_price').val(precio_venta);
    $('#sale_price1').val(precio_venta1);

    $('#modal_new_preci').modal({
        show: true,
        backdrop: 'static'
    });
}

function updated_stock_product(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['inventario_sucursal_id'];
    var product_id = rowData['id'];
    var warehouse_id = rowData['almacen_id'];
    // var asignacion_dosificacion_id= rowData['asignacion_dosificacion_id'];

    swal({
            title: "Esta seguro que desea Actualizar el Producto?",
            text: "El stock del registro se actualizara",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, actualizar stock!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.post(site_url + 'inventory/updated_product_stock', {id:id, product_id:product_id, warehouse_id:warehouse_id}).done(function (response) {
                    if (response) {
                        swal("Actualizado!", "El Producto ha sido actualizado.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "No se pudo actualizar el Producto", "error");
                    }
                });
            }
            /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
        });
}




function mostrar_ventana(url, title, w, h) {
    var left = 200;
    var top = 50;
    window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

/* Abre una ventana modal para editar marca */
function new_register_form(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    $('#product_id').val(id);
    $('#modal_new_reason').modal({
        show: true,
        backdrop: 'static'
    });
}

function get_warehouse_by_branch_office(){
    if ($('#branch_office_report').val() != 0) {
        var warehouse_list = get_warehouse_by_branch_office_id($('#branch_office_report').val());
        $('#warehouse_report').empty();
        $('#warehouse_report').append('<option value="0"> Todos </option>')
        $.each(warehouse_list, function (i, item) {
            $('#warehouse_report').append('<option value="' + item.id + '">' + item.nombre + '</option>')
        });
    } else {
        $('#warehouse_report').empty();
        $('#warehouse_report').append('<option value="0"> Todos </option>')
    }
}
