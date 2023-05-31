$(document).ready(function () {
    load_product_type();
    load_warehouse_list();
});

$(function () {
    $('#branch_office_transfer').change(function (event) {
        get_warehouse_by_branch_office();
    });

    $('#warehouse_transfer').change(function (event) {
        if ($('#warehouse_transfer').val() == $('#warehouse').val()) {
            get_warehouse_by_branch_office();
        }
    });

    $('#warehouse').change(function (event) {
        if ($('#warehouse_transfer').val() == $('#warehouse').val()) {
            get_warehouse_by_branch_office();
        }
    });

    $('#btn_aprobation').click(function () {
        var inventory_entry_id=$('#inventory_entry_id').val();
        // alert(inventory_entry_id);
        aprobation_register_commom(inventory_entry_id, 'transfer_inventory/aprobation_transfer_inventory_entry','transfer_inventory/transfer_inventory_entry');
        // $.redirect(siteurl("transfer_inventory/transfer_inventory_entry"));  
    });
    $('#btn_no_aprobation').click(function () {
        var inventory_entry_id=$('#inventory_entry_id').val();
        descart_register_commom(inventory_entry_id, 'transfer_inventory/descart_transfer_inventory_entry','transfer_inventory/transfer_inventory_entry');
    });

    /*Quitado  de form-line */
    $("#code_product_output").focus();

    $("#close_modal_add_row_output").click(function () {
        $(".row_inventory").remove();
    });

    /*Autocompletado para producto por tipo de producto, necesita un elemento con clase "type_product_id", puede ser un select*/
    $('#product_output').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'name_product_transfer_inventory_output',
                    type_product_id: $('#type_product').val(),
                    warehouse_id: $('#warehouse').val()
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
            /*$('#inventory_id').val(elem[0]);
            $('#nro_lote').val(elem[3]);
            $('#quantity_available').val(elem[4]);
            $('#price_cost').val(elem[5]);
            $('#price_sale').val(elem[6]);
            $('#date_expired').val(elem[7]);*/
            var product_name=ui.item.value;
            $('#product_output').val($.trim(product_name));
            $('#code_product_output').val(elem[4]);

        }
    });

    $('#code_product_output').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'code_product_transfer_inventory_output',
                    type_product_id: $('#type_product').val(),
                    warehouse_id: $('#warehouse').val()
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
            /*$('#inventory_id').val(elem[0]);
            $('#nro_lote').val(elem[3]);
            $('#quantity_available').val(elem[4]);
            $('#price_cost').val(elem[5]);
            $('#price_sale').val(elem[6]);
            $('#date_expired').val(elem[7]);*/
            var product_name=ui.item.value;
            $('#product_output').val(elem[3]);

        }
    });

    $("#code_product_output").keypress(function (e) {
        var tecla = (e.keyCode ? e.keyCode : e.which);
        var enter = 13;
        if (tecla == enter) {
            bar_code_reader_transfer();
            return false;
        }
    });

    /*Evento Submit para añadir fila al detalle*/
    $('#frm_add_row_inventory_output').submit(function (event) {
        event.preventDefault();
        if ($('#warehouse_transfer').val() > 0) {
            if (parseFloat($('#quantity_available').val()) >= parseFloat($('#quantity').val())) {
                var cantidad_filas = $('#lista_detalle tbody tr').length;
                var form = $(this);
                data = form.serializeArray();
                $('.modal-error').remove();
                ajaxStart('Guardando registros, por favor espere');
                $.ajax({
                    url: site_url + 'inventory/add_detail_output',
                    type: $(this).attr('method'),
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        ajaxStop();
                        if (response.success === true) {
                            $('#lista_detalle tbody').append(response.data); //dataTable > tbody:first
                            delete_row_table();
                            document.getElementById("frm_add_row_inventory_output").reset();
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
                    },
                    error: function (error) {
                        ajaxStop();
                        console.log(error.responseText);
                        // **alert('error; ' + eval(error));**
                        swal('Error', 'Error al registrar los datos.', 'error');
                    }
                });
            } else {
                swal('Error', 'La cantidad que desea transferir debe ser menor o igual ala que tiene en su stock.', 'error');
            }
        } else {
            swal('Error', 'Seleccione Sucursal y Almacen especifico que desea transferirle.', 'error');
        }
    });
    $('#frm_add_row_inventory_output_array').submit(function (event) {
        event.preventDefault();
        if ($('#warehouse_transfer').val() > 0) {
            var cantidad_filas = $('#list_inventory tbody tr').length;
            /*if (parseFloat($('#quantity_available').val()) >= parseFloat($('#quantity').val())) {*/

                var form = $(this);
                data = form.serializeArray();
                $('.modal-error').remove();
                ajaxStart('Guardando registros, por favor espere');
                $.ajax({
                    url: site_url + 'inventory/add_detail_output_array',
                    type: $(this).attr('method'),
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        ajaxStop();
                        if (response.success === true) {
                            $('#lista_detalle tbody').append(response.data); //dataTable > tbody:first
                            delete_row_table();
                            //document.getElementById("frm_add_row_inventory_output").reset();
                            $('#product_output').val('');
                            $('#close_modal_add_row_output').click();
                            $(".row_inventory").remove();
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
                    },
                    error: function (error) {
                        ajaxStop();
                        console.log(error.responseText);
                        // **alert('error; ' + eval(error));**
                        swal('Error', 'Error al registrar los datos.', 'error');
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
    $('#frm_register_inventory_output').submit(function (event) {
        event.preventDefault();
        var warehouse_transfer = $('#warehouse_transfer').val();
        var warehouse_origin = $('#warehouse').val();
        if (warehouse_transfer > 0) {
            $('#warehouse_origin_id').val(warehouse_origin);
            $('#warehouse_transfer_id').val(warehouse_transfer);
            $('#branch_office_transfer_id').val($('#branch_office_transfer').val());
            $('#description').val($('#observation').val());
            var cantidad_filas = $('#lista_detalle tbody tr').length;
            var form = $(this);
            data = form.serialize();
            $('.modal-error').remove();
            if (cantidad_filas > 0) {
                ajaxStart('Guardando registros, por favor espere');
                $.ajax({
                    url: site_url + 'transfer_inventory/register_transfer_inventory_output',
                    type: $(this).attr('method'),
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        ajaxStop();
                        if (response.success === true) {

                            var id = response.id;
                            var url_sale = response.url_impression;
                            print(id, url_sale);

                            $('#lista_detalle tbody').append(response.data); //dataTable > tbody:first
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

                                    $.redirect(site_url + 'transfer_inventory/transfer_inventory_output');
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
                    },error: function (error) {
                        ajaxStop();
                        console.log(error.responseText);
                        // **alert('error; ' + eval(error));**
                        swal('Error', 'Error al registrar los datos.', 'error');
                    }
                });
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



});

function calculate_total_quantity_product() {

    var subtotal_amount = 0;
    $('#list_inventory tbody tr').each(function (index, value) {
        subtotal_amount = subtotal_amount + parseFloat(value.dataset.price);
    });
    $('#sale_subtotal').val(subtotal_amount);
    var discount = '';
    $('#sale_discount').val();
    if ($('#sale_discount').val() == '' || $('#sale_discount').val() == ' ') {
        discount = 0;
    } else {
        discount = $('#sale_discount').val();
    }
    var total_amount = subtotal_amount - discount;
    $("#sale_total").val(total_amount);
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
                var product_name = item.nombre_comercial +' - '+ item.nombre_generico;
                var product_code = item.codigo_producto;
                var stock = item.stock;
                var price_cost = item.precio_costo;
                var price_sale = item.precio_venta;
                var lote = item.lote;
                var row = '<tr class="row_inventory">' +
                    '<td><input id="inventory_id'+i+'" name="inventory_id[]" value="' + inventory_id + '" hidden >' + product_code + '</td>' +
                    '<td>' + product_name + '</td>' +
                    '<td><input id="quantity_available'+i+'" name="quantity_available[]" value="' + stock + '" hidden >' + stock + '</td>' +
                    '<td><input type="number" min="0" max="' + stock + '" id="quantity'+i+'" name="quantity[]" value=""></td>' +
                    '<td><input id="price_cost'+i+'" name="price_cost[]" value="' + price_cost + '"></td>' +
                    '<td><input id="price_sale'+i+'" name="price_sale[]" value="' + parseFloat(price_sale).toFixed(2) + '"></td>' +
                    '<td>' + lote + '</td>' +
                    '</tr>';
                $('#list_inventory tbody').append(row);
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
    $('#modal_add_product_output').modal({
        show: true,
        backdrop: 'static'
    });
}

/*  de aca en adelante  */
function div_click(tipo_id, url) {
    $.redirect(site_url + url, {
        id: tipo_id
    });
}

/* carga el listado de proveedores de ese producto*/
function load_proveedor_select(product_id) {
    $.ajax({
        dataType: 'json',
        url: siteurl('provider/get_providers_product'),
        async: false,
        type: 'post',
        data: {product_id: product_id},
        success: function (response) {
            $('#proveedor').html('');
            $.each(response, function (i, item) {
                $('#proveedor').append('<option value="' + item.id + '">' + item.nombre + '</option>')
            })
        }
    });
}


function load_product_type() {
    $.ajax({
        dataType: 'json',
        url: siteurl('type_product/get_type_product'),
        async: false,
        success: function (response) {
            $.each(response, function (i, item) {
                $('#tipo_producto').append('<option value="' + item.id + '">' + item.nombre + '</option>')
            })
        }
    });

}

function load_warehouse_list() {
    $.ajax({
        dataType: 'json',
        url: siteurl('warehouse/get_warehouse_enable'),
        async: false,
        success: function (response) {
            $.each(response, function (i, item) {
                $('#almacen').append('<option value="' + item.id + '">' + item.nombre + '</option>')
            })
        }
    });
}


function delete_row_table() { //Elimina las filas de la tabla

    $("a.elimina").click(function () {
        $(this).parents("tr").fadeOut("normal", function () {
            $(this).remove();
        });
    });
}

/*funcion para editar al ingreso*/
function edit_register_form(element) {
    show_register_commom(element, 'inventory/edit');
}

function delete_row_table_edit(indice) {
    $("#" + indice).remove();
}

function show_register_commom(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('inventory/edit');
    $.redirect(url, {id: data['id']}, 'POST', '_self');
}


/*carga la lista de las marcas al datatable*/
function get_tranfer_inventory_output_list() {
    var tabla = $('#list_output').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        'ajax': {
            "url": siteurl('transfer_inventory/get_transfer_inventory_output_list'),
            "type": 'post'
        },
        'columns': [
            {data: 'nro', class: 'text-center'},
            {data: 'nombre_tipo_salida', class: 'text-center'},
            {data: 'fecha_modificacion', class: 'text-center'},
            {data: 'observacion', class: 'text-center'},
            {data: 'sucursal_origen', class: 'text-center'},
            {data: 'sucursal_destino', class: 'text-center'},
            {data: 'almacen_destino', class: 'text-center'},
            {data: 'estado_aprobacion', class: 'text-center'},
            {data: 'opciones', class: 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 7,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado_aprobacion == 0) {
                        return "<span class='label label-info'><i class='fa fa-times'></i> POR APROBAR</span>"
                    } else if(row.estado_aprobacion == 1){
                        return "<span class='label label-success'><i class='fa fa-thumbs-up'></i> APROBADO</span>"
                    }else{
                        return "<span class='label label-danger'><i class='fa fa-thumbs-down'></i> DESCARTADO</span>"
                    }
                }
            },
            {
                targets: 8,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado !== 0 ) {
                        if(row.estado_aprobacion == 0){// no aprobado
                            return load_buttons_crud(16, 'form');
                        }else{//aprobado
                            return load_buttons_crud(25, 'form');
                        }
                    } else {
                        return '<label>No tiene Opciones</label>';
                    }
                }
            }
        ],

    });
    tabla.ajax.reload();
}

/*carga la lista de las marcas al datatable*/
function get_tranfer_inventory_entry_list() {
    var tabla = $('#list_output').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        'ajax': {
            "url": siteurl('transfer_inventory/get_transfer_inventory_entry_list'),
            "type": 'post'
        },
        'columns': [
            {data: 'nro', class: 'text-center'},
            {data: 'nombre_tipo_ingreso', class: 'text-center'},
            {data: 'fecha_modificacion', class: 'text-center'},
            {data: 'observacion', class: 'text-center'},
            {data: 'sucursal_origen', class: 'text-center'},
            {data: 'sucursal_destino', class: 'text-center'},
            {data: 'almacen_destino', class: 'text-center'},
            {data: 'estado_aprobacion', class: 'text-center'},
            {data: 'opciones', class: 'text-center'},

        ],
        "columnDefs": [
            {
                targets: 7,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado_aprobacion == 0) {
                        return "<span class='label label-info'><i class='fa fa-times'></i> POR APROBAR</span>"
                    } else if(row.estado_aprobacion == 1){
                        return "<span class='label label-success'><i class='fa fa-thumbs-up'></i> APROBADO</span>"
                    }else{
                        return "<span class='label label-danger'><i class='fa fa-thumbs-down'></i> DESCARTADO</span>"
                    }
                }
            },
            {
                targets: 8,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado !== 0) {
                        if(row.estado_aprobacion == 0){// no aprobado
                            return load_buttons_crud(24, 'form');
                        }else{//aprobado
                            return load_buttons_crud(11, 'form');
                        }
                    } else {
                        return '<label>No tiene Opciones</label>';
                    }
                }
            }
        ],
        "order": [[0, "asc"]],
    });
    tabla.ajax.reload();
}

function print_form(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('transfer_inventory/print_transfer_inventory');
    $.redirect(url, {id: data['id']}, 'POST', '_blank');

}

function bar_code_reader_transfer() {
    var lector = $('#code_product_output').val();

    var type_product_id = $('#type_product').val();
    var warehouse_id = $('#warehouse').val();
0
    $.ajax({
        url: site_url + 'inventory/get_product_autocomplete',
        dataType: "json",
        type: 'post',
        data: {
            name_startsWith: lector,
            type: 'code_bar_product_transfer_inventory_output',
            type_product_id: type_product_id,
            warehouse_id: warehouse_id
        },
        success: function (data) {

            if (data.validacion === 1) {
                view_inventory_product(data.sucursal_id, data.almacen_id, data.producto_id);
                var product_name=data.nombre_comercial;
                $('#product_output').val($.trim(product_name));
                $('#product_output').focus();
            } else {
                swal("No existe", "No existe el producto", "error");
                $('#code_product_output').val('');
                $('#code_product_output').focus();
            }

        }
    });
}

function get_warehouse_by_branch_office(){
    if ($('#branch_office_transfer').val() != 0) {
        var warehouse_list = get_warehouse_by_branch_office_id($('#branch_office_transfer').val());
        $('#warehouse_transfer').empty();
        $('#warehouse_transfer').append('<option value="0"> Seleccione Almacen Destino</option>')
        $.each(warehouse_list, function (i, item) {
            $('#warehouse_transfer').append('<option value="' + item.id + '">' + item.nombre + '</option>')
        });
    } else {
        $('#warehouse_transfer').empty();
    }
}


