/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 27/05/2019
 * Time: 17:37 PM
 */

$(document).ready(function () {

    // Autocompletado para orden de trabajo
    $('#code_work').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('transit/get_order_work_autocomplete'),
                dataType: "json",
                type: 'post',
                data: {
                    name_startsWith: request.term,
                    type: 'code_work'
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        var data = nombre.split('/');
                        if (data.length > 1) {
                            return {
                                label: nombre,
                                value: data[0],
                                id: item
                            };
                        } else {
                            return {
                                label: nombre,
                                value: "",
                                id: item
                            };
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            var data_product = (ui.item.id);
            var elem = data_product.split('/');
            var data_array = ui.item.label;
            var elem_data = data_array.split('/');

            $('#order_work_id').val(elem[1]);
            $('#code_work').val(elem[0]);
            $('#date_reception').html(elem[2]);
            $('#customer_ci').html(elem[3]);
            $('#customer_name').html(elem[4]);
            $('#brand').html(elem[5]);
            $('#model').html(elem[6]);
            $('#imei').html(elem[7]);
        }
    });


    $('#product_name_transit').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'name_product_transfer_inventory_output',
                    type_product_id: 1,
                    warehouse_id: $('#origin_warehouse_id').val()
                },
                type: 'post',
                success: function (data) {
                    response($.map(data, function (item, codigo) {
                        var data = codigo.split('/');
                        if (data.length > 1) {
                            return {
                                label: codigo,
                                value: data[1],
                                id: item
                            };
                        } else {
                            return {
                                label: codigo,
                                value: "",
                                id: item
                            };
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            var Date = (ui.item.id);
            var elem = Date.split('/');
            view_inventory_product(elem[0], elem[1], elem[2]);
        }
    });

    $('#code_product_transit').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'code_product_transfer_inventory_output',
                    type_product_id: 1,
                    warehouse_id: $('#origin_warehouse_id').val()
                },
                type: 'post',
                success: function (data) {
                    response($.map(data, function (item, codigo) {
                        var data = codigo.split('/');
                        if (data.length > 1) {
                            return {
                                label: codigo,
                                value: data[0],
                                id: item
                            };
                        } else {
                            return {
                                label: codigo,
                                value: "",
                                id: item
                            };
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            var Date = (ui.item.id);
            var elem = Date.split('/');
            view_inventory_product(elem[0], elem[1], elem[2]);
        }
    });

    $("#reason").on('change', function () {
        if ($("#reason").val() == 5) {
            $("#div_order_work").show();
        } else {
            $("#div_order_work").hide();
        }
    });

    $('#frm_add_row_inventory_transit_array').submit(function (event) {
        event.preventDefault();
        if ($('#destination_warehouse_id').val() > 0) {
            var cantidad_filas = $('#list_inventory_transit tbody tr').length;
            /*if (parseFloat($('#quantity_available').val()) >= parseFloat($('#quantity').val())) {*/

            var form = $(this);
            data = form.serializeArray();
            $('.modal-error').remove();
            ajaxStart('Guardando registros, por favor espere');
            $.ajax({
                url: site_url + 'inventory/add_transit_detail_output_array',
                type: $(this).attr('method'),
                data: data,
                dataType: 'json',
                success: function (response) {
                    ajaxStop();
                    if (response.success === true) {
                        $('#lista_detalle_transit_output tbody').append(response.data); //dataTable > tbody:first
                        delete_row_table();
                        //document.getElementById("frm_add_row_inventory_output").reset();
                        $('#code_product_transit').val('');
                        $('#product_name_transit').val('');
                        $('#close_modal_add_product_transit').click();
                        $(".row_inventory_transit").remove();
                        return false;
                    } else {
                        $('.modal-error').remove();
                        if (response.messages !== null) {
                            $.each(response.messages, function (key, value) {
                                var element = $('#' + key);
                                var parent = element.parent();
                                parent.removeClass('form-line');
                                parent.addClass('form-line error');
                                parent.after(value);
                                if (key == 'description') {
                                    var element = $('#observation');
                                    var parent = element.parent();
                                    parent.removeClass('form-line');
                                    parent.addClass('form-line error');
                                    parent.after(value);
                                }
                            });
                        } else {
                            swal('Error', 'Eror al registrar los datos.', 'error');
                        }
                    }
                }
            });
            /*} else {
                swal('Error', 'La cantidad que desea transferir debe ser menor o igual ala que tiene en su stock.', 'error');
            }*/
        } else {
            swal('Error', 'Seleccione Sucursal y Almacen especifico que desea transferirle.', 'error');
        }
    });

    /*Submit en guardar de nueva salida de inventario  */
    $('#frm_register_transit_output').submit(function (event) {
        event.preventDefault();
        var warehouse_transfer = $('#destination_warehouse_id').val();
        if (warehouse_transfer > 0) {
            var cantidad_filas = $('#lista_detalle_transit_output tbody tr').length;
            var form = $(this);
            data = form.serialize();
            $('.modal-error').remove();
            if (cantidad_filas > 0) {
                if ($('#reason').val() == 6 || ($('#reason').val() == 5 && $('#order_work_id').val() != '')) {
                    ajaxStart('Guardando registros, por favor espere');
                    $.ajax({
                        url: site_url + 'transit/register_transfer_inventory_output_transit',
                        type: $(this).attr('method'),
                        data: data,
                        dataType: 'json',
                        success: function (response) {
                            ajaxStop();
                            if (response.success === true) {

                                var id = response.id;
                                var url_sale = response.url_impression;
                                print(id, url_sale);

                                $('#lista_detalle_transit_output tbody').append(response.data); //dataTable > tbody:first
                                delete_row_table();
                                swal({
                                        title: "Datos correctos",
                                        text: "Registro realizado correctamente",
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonClass: "btn-danger",
                                        confirmButtonText: "Ok",
                                        cancelButtonText: "Cancelar",
                                        closeOnConfirm: false
                                    },
                                    function () {

                                        $.redirect(site_url + 'transit/output');
                                    });
                                return false;
                            } else if (response.login === true) {
                                login_session();
                            } else {
                                $('.modal-error').remove();
                                if (response.messages !== null) {
                                    $.each(response.messages, function (key, value) {
                                        var element = $('#' + key);
                                        var parent = element.parent();
                                        parent.removeClass('form-line');
                                        parent.addClass('form-line error');
                                        parent.after(value);
                                        if (key == 'description') {
                                            var element = $('#observation');
                                            var parent = element.parent();
                                            parent.removeClass('form-line');
                                            parent.addClass('form-line error');
                                            parent.after(value);
                                        }
                                    });
                                } else {
                                    swal('Error', 'Eror al registrar los datos.', 'error');
                                }
                            }
                        }
                    });
                } else {

                    swal(
                        'Error',
                        'Debe agregar un orden de trabajo',
                        'error'
                    )
                }
            } else {
                swal(
                    'Error',
                    'Datos incorrectos, debe de agregar un detalle',
                    'error'
                )
            }
        } else {
            swal('Error', 'Seleccione Sucursal y Almacen especifico que desea transferirle.', 'error');
        }
    });
    $('#frm_register_transit_entry').submit(function (event) {
        event.preventDefault();
        var warehouse_transfer = $('#destination_warehouse_id').val();
        if (warehouse_transfer > 0) {
            var form = $(this);
            data = form.serialize();
            $('.modal-error').remove();

            ajaxStart('Guardando la devolucion , por favor espere');
            $.ajax({
                url: site_url + 'transit/register_transfer_inventory_entry_transit',
                type: $(this).attr('method'),
                data: data,
                dataType: 'json',
                success: function (response) {
                    ajaxStop();
                    if (response.success === true) {

                        var id = response.id;
                        var url_sale = response.url_impression;
                        print(id, url_sale);

                        $('#lista_detalle_transit_output tbody').append(response.data); //dataTable > tbody:first
                        delete_row_table();
                        swal({
                                title: "Datos correctos",
                                text: "Registro realizado correctamente",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Ok",
                                cancelButtonText: "Cancelar",
                                closeOnConfirm: false
                            },
                            function () {

                                $.redirect(site_url + 'transit/output');
                            });
                        return false;
                    } else if (response.login === true) {
                        login_session();
                    } else {
                        $('.modal-error').remove();
                        if (response.messages !== null) {
                            $.each(response.messages, function (key, value) {
                                var element = $('#' + key);
                                var parent = element.parent();
                                parent.removeClass('form-line');
                                parent.addClass('form-line error');
                                parent.after(value);
                                if (key == 'description') {
                                    var element = $('#observation');
                                    var parent = element.parent();
                                    parent.removeClass('form-line');
                                    parent.addClass('form-line error');
                                    parent.after(value);
                                }
                            });
                        } else {
                            swal('Error', 'Eror al registrar los datos.', 'error');
                        }
                    }
                }
            });

        } else {
            swal('Error', 'Seleccione Sucursal y Almacen especifico que desea transferirle.', 'error');
        }
    });

    $("#code_product_transit").keypress(function (e) {
        var tecla = (e.keyCode ? e.keyCode : e.which);
        var enter = 13;
        if (tecla == enter) {
            bar_code_reader_transit();
            return false;
        }
    });

    $('#frm_transit_approved').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        data = form.serialize();
        $('.modal-error').remove();

        ajaxStart('Guardando la devolucion , por favor espere');
        $.ajax({
            url: site_url + 'transit/register_transit_approved',
            type: $(this).attr('method'),
            data: data,
            dataType: 'json',
            success: function (response) {
                ajaxStop();
                if (response.success === true) {

                    swal({
                            title: "Datos correctos",
                            text: "Registro realizado correctamente",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Ok",
                            cancelButtonText: "Cancelar",
                            closeOnConfirm: false
                        },
                        function () {
                            $.redirect(site_url + 'transit/transit_borrowed_piece');
                        });
                    return false;
                } else if (response.login === true) {
                    login_session();
                } else {
                    swal('Error', 'Eror al registrar los datos.', 'error');
                }
            }
        });


    });


});

function delete_row_table() { //Elimina las filas de la tabla

    $("a.elimina").click(function () {
        $(this).parents("tr").fadeOut("normal", function () {
            $(this).remove();
        });
    });
}

/*function get_transit_list() {
    $('#list_output').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,
        'ajax': {
            "url": siteurl('transit/get_transit_list'),
            "type": 'post',
        },
        'columns': [
            {data: 'id'},
            {data: 'nro_prestamo', class: 'text-center'},
            {data: 'fecha_transito_prestamo', class: 'text-center'},
            {data: 'usuario_entregador_id_prestamo', class: 'text-center'},
            {data: 'usuario_solicitante_id_prestamo', class: 'text-center'},
            {data: 'detalle_prestamo', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        "columnDefs": [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 6,
            orderable: false,
            render: function (data, type, row) {
                return '';
            }
        }]
    });

}*/

function get_transit_list() {
    var tabla = $('#list_output').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'ajax': {
            "url": siteurl('transit/get_transit_list'),
            "type": "post",
        },
        'columns': [
            {data: 'id'},
            {data: 'nro_prestamo', class: 'text-center'},
            {data: 'fecha_transito_prestamo', class: 'text-center'},
            {data: 'usuario_entregador_id_prestamo', class: 'text-center'},
            {data: 'usuario_solicitante_id_prestamo', class: 'text-center'},
            {data: 'tipo', class: 'text-center'},
            {data: 'detalle_prestamo', class: 'text-center'},
            {data: 'codigo_recepcion', class: 'text-center'},
            {data: 'estado_transito', class: 'text-center'},
            {data: 'observacion_prestamo', class: 'text-center'},
            {data: 'opciones', class: 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 8,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado_transito == 1){
                        return '<div style="background-color: red"> PRESTADO</div>';
                    } else if (row.estado_transito == 3) {
                        return '<div style="background-color: yellow"> POR APROBAR </div>';
                    }
                }
            },
            {
                targets: 10,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado_transito == 3){
                        return '<button type="button" class="btn btn-success waves-effect" onclick="new_transit_entry(this)">' +
                            '<i class="material-icons">assignment_turned_in</i>' +
                            '<span>Aprobar</span>' +
                            '</button>';
                    }else
                        return ' Sin opciones';
                }
            }
        ],
        "order": [[0, "asc"]],
    });
    tabla.ajax.reload();
}

function get_transit_borrowed_piece() {
    var tabla = $('#list_borrowed_piece').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'ajax': {
            "url": siteurl('transit/get_transit_borrowed_piece'),
            "type": "post",
        },
        'columns': [
            {data: 'id'},
            {data: 'nro_prestamo', class: 'text-center'},
            {data: 'fecha_transito_prestamo', class: 'text-center'},
            {data: 'usuario_solicitante_id_prestamo', class: 'text-center'},
            {data: 'usuario_entregador_id_prestamo', class: 'text-center'},
            {data: 'tipo', class: 'text-center'},
            {data: 'detalle_prestamo', class: 'text-center'},
            {data: 'codigo_recepcion', class: 'text-center'},
            {data: 'estado_transito', class: 'text-center'},
            {data: 'observacion_prestamo', class: 'text-center'},
            {data: 'opciones', class: 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 8,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado_transito == 1){
                        return '<div style="background-color: red"> PRESTADO</div>';
                    } else if (row.estado_transito == 3) {
                        return '<div style="background-color: yellow"> POR APROBAR </div>';
                    }
                }
            },
            {
                targets: 10,
                orderable: false,
                render: function (data, type, row) {
                    return '<button type="button" class="btn btn-info waves-effect" onclick="transit_approved(this)">' +
                        '<i class="fa fa-mail-forward"></i>' +
                        '<span>Devolver</span>' +
                        '</button>';
                }
            }
        ],
        "order": [[0, "asc"]],
    });
    tabla.ajax.reload();
}


function view_inventory_product(branch_office_id, warehouse_id, product_id) {
    $.ajax({
        dataType: 'json',
        url: siteurl('inventory/get_inventory_by_product_id'),
        async: false,
        type: 'post',
        data: {
            branch_office_id: branch_office_id,
            warehouse_id: warehouse_id,
            product_id: product_id
        },
        success: function (response) {

            $.each(response, function (i, item) {
                var inventory_id = item.inventario_id;
                var product_name = item.nombre_comercial;
                var product_code = item.codigo_producto;
                var stock = item.stock;
                var price_cost = item.precio_costo;
                var price_sale = item.precio_venta;
                var lote = item.lote;
                var row = '<tr class="row_inventory_transit">' +
                    '<td><input id="inventory_id' + i + '" name="inventory_id[]" value="' + inventory_id + '" hidden >' + product_code + '</td>' +
                    '<td>' + product_name + '</td>' +
                    '<td><input id="quantity_available' + i + '" name="quantity_available[]" value="' + stock + '" hidden >' + stock + '</td>' +
                    '<td><input type="number" min="0" max="' + stock + '" id="quantity' + i + '" name="quantity[]" value=""></td>' +
                    '<td hidden><input id="price_cost' + i + '" name="price_cost[]" value="' + price_cost + '"></td>' +
                    '<td hidden><input id="price_sale' + i + '" name="price_sale[]" value="' + price_sale + '"></td>' +
                    '<td>' + lote + '</td>' +
                    '</tr>';
                $('#list_inventory_transit tbody').append(row);
            })

        },
        error: function (error) {
            ajaxStop();
            console.log(error.responseText);
            // **alert('error; ' + eval(error));**
            swal('Error', 'Error al registrar los datos.', 'error');
        }
    });
    $('#quantity0').focus();
    $('#modal_add_product_transit').modal({
        show: true,
        backdrop: 'static'
    });
};

function new_transit_entry(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('transit/transit_entry');
    $.redirect(url, {id: data['id']}, 'POST', '_self');
}

function transit_approved(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('transit/transit_approved');
    $.redirect(url, {id: data['id']}, 'POST', '_self');
}

function bar_code_reader_transit() {
    var lector = $('#code_product_transit').val();

    var warehouse_id = $('#origin_warehouse_id').val();

    $.ajax({
        url: site_url + 'inventory/get_product_autocomplete',
        dataType: "json",
        type: 'post',
        data: {
            name_startsWith: lector,
            type: 'code_bar_product_transfer_inventory_output',
            type_product_id: 1,
            warehouse_id: warehouse_id
        },
        success: function (data) {
            if (data.validacion === 1) {
                view_inventory_product(data.sucursal_id, data.almacen_id, data.producto_id);
            } else {
                swal("No existe", "No existe el producto", "error");
                $('#code_product_transit').val('');
                $('#code_product_transit').focus();
            }

        }
    });
}