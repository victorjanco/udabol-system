/**
 * Created by Ariel on 20/07/2017.
 */
$(document).ready(function () {
    get_product_list();
});

$(document).ready(function () {

    $("#grupo").on('change', function () {
        $.ajax({
            url: siteurl('group/get_subgroups'),
            type: 'post',
            data: {group_id: $("#grupo").val()},
            dataType: 'json',
            success: function (response) {
                $('#subgrupo').empty();
                $.each(response, function (i, item) {
                    $('#subgrupo').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                });
            }
        })
    });

    $("#brand").on('change', function () {
        $.ajax({
            url: siteurl('model/get_model_by_brand'),
            type: 'post',
            data: {marca_id: $("#brand").val()},
            dataType: 'json',
            success: function (response) {
                $('#modelo').empty();
                $.each(response, function (i, item) {
                    $('#modelo').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                });
            }
        })
    });

    $('#frm_new_product').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        var type = 'guardar';
        if ($('#imprimir').click()) {
            type = 'imprimir'
        }
        ajaxStart('Guardando registros, por favor espere');
        $.ajax({
            url: $(this).attr('action'),
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
                    location.href = site_url + 'product/new_product';
                    console.log(site_url + 'product/new_product');
                    if (!$(this).hasClass('frm-noreset')) {
                        //  $(form)[0].reset();//
                        location.href = site_url('product/new_product');
                    }

                    if ($(this).hasClass('frm-datatable')) {
                        // reload_table();
                    }
                } else if (respuesta.login === true) {
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
            },
            error: function (error) {
                ajaxStop();
                console.log(error.responseText);
                // **alert('error; ' + eval(error));**
                swal('Error', 'Error al registrar los datos.', 'error');
            }
        });
    });

    $("#btn_create_product").click(function () {
        $.ajax({
            url: siteurl('product/create_product'),
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.success == 1) {
                    window.location.replace(siteurl('product/new_product'));
                } else {
                    swal('No tienes permiso', 'El administrador solo puede crear producto', 'warning');
                }
            }
        });
    });

    $("#btn_excel_export_product").on('click', function () {
        $.ajax({
            url: siteurl('product/create_product'),
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.success == 1) {
                    $.redirect(siteurl('product/export_to_excel_product'), null, 'POST', '_blank');
                } else {
                    swal('No tienes permiso', 'El administrador solo puede crear producto', 'warning');
                }
            }
        });
    });

    $("#btn_excel_import_price_product").on('click', function () {
        $.ajax({
            url: siteurl('product/create_product'),
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.success == 1) {
                    window.location.replace(siteurl('product/import_price_products'));
                } else {
                    swal('No tienes permiso', 'El administrador solo puede crear producto', 'warning');
                }
            }
        });
    });

    $("#btn_excel_import_product").on('click', function () {
        $.ajax({
            url: siteurl('product/create_product'),
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.success == 1) {
                    window.location.replace(siteurl('product/import_products'));
                } else {
                    swal('No tienes permiso', 'El administrador solo puede crear producto', 'warning');
                }
            }
        });
    });
    
    $("#btn_excel_import_stock_product").on('click', function () {
        $.ajax({
            url: siteurl('product/create_product'),
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.success == 1) {
                    window.location.replace(siteurl('product/import_stock_products'));
                } else {
                    swal('No tienes permiso', 'El administrador solo puede crear producto', 'warning');
                }
            }
        });
    });
    $("#btn_excel_delete_product").on('click', function () {
        $.ajax({
            url: siteurl('product/create_product'),
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.success == 1) {
                    window.location.replace(siteurl('product/delete_stock_products'));
                } else {
                    swal('No tienes permiso', 'El administrador solo puede crear producto', 'warning');
                }
            }
        });
    });
    $("#btn_generate_barcode").click(function () {
        $.ajax({
            url: siteurl('product/generar_codigo'),
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $('#codigo').val(response);
                $('#codigo1').val(response);
                // console.log(response);
            }
        });
    });
});

function get_product_list() {
    var tabla = $('#product_list').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'ajax': {
            "url": siteurl('product/get_products_list'),
            "type": "post",
            // dataSrc: ''
        },
        'columns': [
            {data: 'id'},
            {data: 'codigo', class: 'text-center'},
            {data: 'nombre_comercial'},
            {data: 'nombre_generico'},
            {data: 'dimension', class: 'text-center'},
            {data: 'precio_venta', class: 'text-center'},
            {data: 'grupo', class: 'text-center'},
            {data: 'subgrupo', class: 'text-center'},
            {data: 'modelo', class: 'text-center'},
            {data: 'marca', class: 'text-center'},
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
                targets: 10,
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
                targets: 11,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado != 0) {
                        return load_buttons_crud(17, 'formulario');
                    } else {
                        return '<button type="button" onclick="activate_product(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                    }
                }
            }
        ]
    });

}
function get_product_inactive_list() {
    var tabla = $('#product_inactive_list').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'ajax': {
            "url": siteurl('product/get_product_inactive_list'),
            "type": "post",
            // dataSrc: ''
        },
        'columns': [
            {data: 'id'},
            {data: 'codigo', class: 'text-center'},
            {data: 'nombre_comercial'},
            {data: 'nombre_generico'},
            {data: 'dimension', class: 'text-center'},
            {data: 'precio_venta', class: 'text-center'},
            {data: 'grupo', class: 'text-center'},
            {data: 'subgrupo', class: 'text-center'},
            {data: 'modelo', class: 'text-center'},
            {data: 'marca', class: 'text-center'},
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
                targets: 10,
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
                targets: 11,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado != 0) {
                        // return load_buttons_crud(17, 'formulario');
                    } else {
                        // return '<button type="button" onclick="activate_product(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                        // return '<button type="button" onclick="activate_product(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                        return load_buttons_crud(21, 'formulario');
                    }
                }
            }
        ]
    });

}
function activate_product(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
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
                $.post(site_url + 'product/activate_product', {id:id}).done(function (response) {
                    if (response) {
                        swal("Activado!", "El Producto ha sido activado.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "No se pudo reactivar el Producto", "error");
                    }
                });
            }
            /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
        });
}
function delete_product(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    // var asignacion_dosificacion_id= rowData['asignacion_dosificacion_id'];

    swal({
            title: "Esta seguro que desea eliminar definitivamente el producto?",
            text: "El Producto se eliminara definitivamente",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar producto!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.post(site_url + 'product/delete_product', {id:id}).done(function (response) {
                    if (response) {
                        swal("Activado!", "El Producto ha sido Eliminado.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "No se pudo eliminar el Producto", "error");
                    }
                });
            }
            /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
        });
}
function view_register(element) {

    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    $.ajax({
        url: siteurl('product/get_product_by_id'),
        type: 'post',
        data: 'id=' + rowData['id'],
        dataType: 'json',
        success: function (respuesta) {
            $('#codigo').val(respuesta.codigo);
            $('#comercial').val(respuesta.nombre_comercial);
            $('#generico').val(respuesta.nombre_generico);
            $('#dimension').val(respuesta.dimension);
            $('#medida').val(respuesta.medida);
            $('#precio_venta').val(respuesta.precio_venta);
            $('#precio_costo').val(respuesta.precio_compra);
            $('#categoria').val(respuesta.categoria);
            $('#grupo').val(respuesta.grupo);
            $('#modelo').val(respuesta.modelo);
            $('#proveedor').val(respuesta.proveedor);
            // $('#').val(respuesta.);
        }
    });

    $('#modal_view_product').modal({
        show: true,
        backdrop: 'static'
    });
}

function edit_register_form(element) {
    edit_register_commom(element, 'product/edit');
}

function view_register_form(element) {
    edit_register_commom(element, 'product/view');
}

function delete_register(element) {
    delete_register_commom(element, 'product/delete')
}

function add_gallery_product(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    $.redirect(siteurl('product/register_gallery_product'), {id: rowData['id']}, 'POST', '_self');
}

function imprimir_codigo(seleccionado) {
    var table = $(seleccionado).closest('table').DataTable();
    var current_row = $(seleccionado).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();

    mostrar_ventana(site_url + 'product/imprimir_codigo/' + data['id'], 'Impresion', '1500', '720');
}

function mostrar_ventana(url, title, w, h) {
    var left = 200;
    var top = 50;
    window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}
