/**
 * Created by Gustavo Cisneros on 15/08/2017.
 */
$(document).ready(function () {
    load_product_type();
    load_warehouse_list();
    get_inventory_list();
    get_purchase_list();
    $('#btn_register_device').hide();
    $('#btn_volver_atras').hide();
    //get_inventory_output_list();
    f = 0;
    $('#codigo_barra').focus();
    $('#barcode_output').focus();
});


$(function () {
    /*Cuando da clic guardar de modal nueva marca se activa esta accion*/
    $('#frm_new').submit(function (event) {
        event.preventDefault();
        var cantidad_filas = $('#lista_detalle tbody tr').length;
        var form = $(this);
        data = form.serializeArray();
        if (cantidad_filas > 0) {
            data.push({
                    name: "nombre",
                    value: $("#nombre").val()
                },
                {
                    name: "fecha_ingreso",
                    value: $("#fecha_ingreso").val()
                },
                {
                    name: "nro_comprobante",
                    value: $("#nro_comprobante").val()
                }
            );
            ajaxStart('Guardando registros, por favor espere');
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: data,
                dataType: 'json',
                success: function (response) {
                    ajaxStop();
                    if (response.success === true) {
                        swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                        var id = response.inventory;
                        var url_print = response.url_print;
                        $('#frm_new')[0].reset();
                        print(id, url_print);
                        setTimeout(function () {
                            location.href = site_url + "inventory/"
                        });
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
            swal('Error', 'Datos incorrectos, debe de agregar un detalle.', 'error');
        }
    });

    /*Metodo para guardar la edicion del ingreso comun*/
    $('#frm_edit').submit(function (event) {
        event.preventDefault();
        var cantidad_filas = $('#lista_detalle tbody tr').length;
        var form = $(this);
        data = form.serializeArray();
        if (cantidad_filas > 0) {
            data.push({name: "nombre", value: $("#nombre").val()}, {
                name: "fecha_ingreso",
                value: $("#fecha_ingreso").val()
            });
            ajaxStart('Guardando registros, por favor espere');
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: data,
                dataType: 'json',
                success: function (response) {
                    ajaxStop();
                    if (response.success === true) {
                        swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                        update_data_table($('#list'));
                    } else {
                        $('.modal-error').remove();
                        if (response.messages !== null) {
                            $.each(response.messages, function (key, value) {
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
        } else {

        }
    });

    /*Evento Submit en el ingreso por compra    */
    $('#frm_new_entry_purchase').submit(function (event) {
        event.preventDefault();
        var cantidad_filas = $('#lista_detalle tbody tr').length;
        var form = $(this);
        data = form.serializeArray();
        if (cantidad_filas > 0) {
            data.push({name: "nombre", value: $("#nombre").val()}, {
                name: "fecha_ingreso",
                value: $("#fecha_ingreso").val()
            });
            ajaxStart('Guardando registros, por favor espere');
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: data,
                dataType: 'json',
                success: function (response) {
                    ajaxStop();
                    if (response.success === true) {
                        swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                        $.redirect(siteurl('inventory'), null, 'GET', '_self');
                    } else {
                        $('.modal-error').remove();
                        if (response.messages !== null) {
                            $.each(response.messages, function (key, value) {
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
        } else {

        }
    });

    /*Autocompletado para producto por tipo de producto, necesita un elemento con clase "type_product_id", puede ser un select*/
    $('#barcode_output').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'code_product_inventory_output',
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
            $('#inventory_id').val(elem[0]);
            $('#nro_lote').val(elem[3]);
            $('#quantity_available').val(elem[4]);
            $('#price_cost').val(elem[5]);
            $('#price_sale').val(parseFloat(elem[6]).toFixed(2));
            $('#date_expired').val(elem[7]);
            $('#product_output').val(ui.item.value);
            $('#quantity').focus();
        }
    });

    /*Autocompletado para producto por tipo de producto, necesita un elemento con clase "type_product_id", puede ser un select*/
    $('#product_output').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'name_product_inventory_output',
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
            $('#inventory_id').val(elem[0]);
            $('#barcode_output').val(elem[2]);
            $('#nro_lote').val(elem[3]);
            $('#quantity_available').val(elem[4]);
            $('#price_cost').val(elem[5]);
            $('#price_sale').val(parseFloat(elem[6]).toFixed(2));
            $('#date_expired').val(elem[7]);
            $('#product_output').val(ui.item.value);
            $('#quantity').focus();
        }
    });


    $('#barcode_output').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'code_product_inventory_output',
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
            $('#inventory_id').val(elem[0]);
            $('#barcode_output').val(elem[2]);
            $('#nro_lote').val(elem[3]);
            $('#quantity_available').val(elem[4]);
            $('#price_cost').val(elem[5]);
            $('#price_sale').val(parseFloat(elem[6]).toFixed(2));
            $('#date_expired').val(elem[7]);
            $('#product_output').val(elem[1]);
            $('#quantity').focus();
        }
    });

    $("#barcode_output").keypress(function (e) {
        var tecla = (e.keyCode ? e.keyCode : e.which);
        var enter = 13;
        if (tecla == enter) {
            bar_code_reader_output();
            return false;
        }
    });


    /*Evento Submit para añadir fila al detalle*/
    $('#frm_add_row_inventory_output').submit(function (event) {
        event.preventDefault();
        var quantity_available = $('#quantity_available').val();
        var quantity = $('#quantity').val();
        if (parseFloat(quantity_available) >= parseFloat(quantity)) {
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
                        $('#barcode_output').focus();
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
    });

    /*Submit en guardar de nueva salida de inventario  */         
    $('#frm_register_inventory_output').submit(function (event) {
        event.preventDefault();
        var cantidad_filas = $('#lista_detalle tbody tr').length;
        var form = $(this);
        data = form.serialize();
        $('.modal-error').remove();
        if (cantidad_filas > 0) {
            ajaxStart('Guardando registros, por favor espere');
            $.ajax({
                url: site_url + 'inventory/register_inventory_output',
                type: $(this).attr('method'),
                data: data,
                dataType: 'json',
                success: function (response) {
                    ajaxStop();
                    if (response.success === true) {
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
                                // $.redirect(site_url + 'inventory/inventory_output');
                                var id = response.inventory_output;
                                var url_print = response.url_print;
                                // $('#frm_new')[0].reset();
                                print(id, url_print);
                                setTimeout(function () {
                                    location.href = site_url + "inventory/inventory_output"
                                });
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
                            });
                        } else {
                            swal('Error', 'Error al registrar los datos.', 'error');
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
                'Datos incorrectos, debe de agregar un detalle.',
                'error'
            )
        }
    });

    $('#btn_previsualizar').click(function (event) {


        // if ($('#nombre').val() != '') {

            if ($('#fecha_ingreso').val() != '') {

                if ($('#nro_comprobante').val() != '') {

                    var cantidad_filas = $('#lista_detalle tbody tr').length;
                    if (cantidad_filas > 0){
                        $('#btn_register_device').show();
                        $('#btn_previsualizar').hide();
                        $('#btn_cancelar').hide();
                        $('#btn_volver_atras').show();

                        $('#reg_nombre').hide();
                        $('#pre_glosa').show();
                        $('#pre_glosa').html($('#nombre').val());

                        $('#reg_fecha_ingreso').hide();
                        $('#pre_fecha_ingreso').show();
                        $('#pre_fecha_ingreso').html($('#fecha_ingreso').val());

                        $('#reg_nro_comprobante').hide();
                        $('#pre_nro_comprobante').show();
                        $('#pre_nro_comprobante').html($('#nro_comprobante').val());

                        $('#div_detalle').hide();

                    } else{
                        swal('Detalle de ingreso inventario', 'Datos incorrectos, debe de agregar un detalle.', 'info');
                    }

                } else {
                    swal('Campo número comprobante', 'El campo número de comprobante no debe estar vacío.', 'info');
                }

            } else {
                swal('Campo fecha de ingreso', 'El campo fecha de ingreso no debe estar vacío.', 'info');
            }

        // } else {
        //     swal('Campo glosa', 'El campo glosa no debe estar vacío.', 'info');
        // }


    });


    $('#btn_volver_atras').click(function (event) {
        $('#reg_nombre').show();
        $('#pre_glosa').hide();
        $('#reg_fecha_ingreso').show();
        $('#pre_fecha_ingreso').hide();
        $('#reg_nro_comprobante').show();
        $('#pre_nro_comprobante').hide();

        $('#div_detalle').show();

        $('#btn_cancelar').show();
        $('#btn_previsualizar').show();
        $('#btn_register_device').hide();
        $('#btn_volver_atras').hide();
    });

    // Autocompletado para buscar producto por codigo de barra
    $('#codigo_barra').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('product/get_code_name_product_autocomplete'),
                dataType: "json",
                type: 'post',
                data: {
                    name_startsWith: request.term,
                    type: 'code'
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
        autoFocus: true,
        selectFirst: true,
        select: function (event, ui) {
            var data_product = (ui.item.id);
            var elem = data_product.split('/');
            var data_array = ui.item.label;
            var elem_data = data_array.split('/');

            $('#product_id').val(elem[0]);
            $('#precio_venta').val(elem[2]);
            $('#precio_compra').val(elem[3]);
            $('#producto').val(elem[1]);
            $('#codigo_barra').val(elem_data[0]);

            load_proveedor_select(elem[0]);

        },
        focus: function (event, ui) {
            event.preventDefault();
        }
    });

    // Autocompletado para buscar producto por nombre de producto
    $('#producto').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('product/get_code_name_product_autocomplete'),
                dataType: "json",
                type: 'post',
                data: {
                    name_startsWith: request.term,
                    type: 'name'
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        var data = nombre.split('/');
                        if (data.length > 1) {
                            return {
                                label: nombre,
                                value: data[1],
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

            $('#product_id').val(elem[0]);
            $('#precio_venta').val(elem[2]);
            $('#precio_compra').val(elem[3]);
            $('#producto').val(elem[1]);
            $('#codigo_barra').val(elem_data[0]);

            load_proveedor_select(elem[0]);
        }
    });

    // Evento para el Lector de Codigo de Barra
    $("#codigo_barra").keypress(function (e) {

        var tecla = (e.keyCode ? e.keyCode : e.which);
        var enter = 13;
        if (tecla == enter) {
            bar_code_reader();
            return false;
        }
    });

});


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
            console.log(response)
            $('#proveedor').html('');
            $.each(response, function (i, item) {
                $('#proveedor').append('<option value="' + item.id + '">' + item.nombre + '</option>')
            })
        }
    });
}

function addRow1() {
    //agrega filas a la tabla de nota de venta y suma los precios de cada producto

    f = f + 1;
    $('#contador').val(f);
    var frm = $("#frm_registro_venta_otros").serialize();
    $.ajax({
        url: site_url + 'inventory/add_detail_common',
        data: frm,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $('.modal-error').remove();
            if (response.success === true) {
                $('#lista_detalle tbody').append(response.data); //dataTable > tbody:first
                delete_row_table();
                var name_inventory_entry, date_inventory_entry;
                name_inventory_entry = $("#nombre").val();
                date_inventory_entry = $("#fecha_ingreso").val();
                document.getElementById("frm_registro_venta_otros").reset();
                $('#proveedor').html('');
                return false;
            } else {
                $('.modal-error').remove();
                if (response.messages !== null) {
                    f = f - 1;
                    $('#contador').val(f);
                    console.log(response.messages);
                    $.each(response.messages, function (key, value) {
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
    return false;
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

/*carga la lista de las marcas al datatable*/
function get_inventory_list() {
    var tabla = $('#list').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,
        'ajax': {
            "url": siteurl('inventory/get_inventory_list'),
            "type": 'post'
        },
        'columns': [
            {data: 'id'},
            {data: 'nro_ingreso_inventario', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'fecha_ingreso', class: 'text-center'},
            {data: 'nombre_sucursal', class: 'text-center'},
            {data: 'nombre_tipo_ingreso', class: 'text-center'},
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
                if (row.estado !== 0) {
                    if(row.tipo_ingreso_inventario_id == 1){// no aprobado
                        return load_buttons_crud(16, 'form');
                    }else{//aprobado
                        return load_buttons_crud(25, 'form');
                    }
                    // return load_buttons_crud(16, 'formulario');
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }]
    });
    // tabla.ajax.reload();
}

/*funcion para editar al ingreso*/
function edit_register_form(element) {
    show_register_commom(element, 'inventory/edit');
}

function delete_row_table_edit(indice) {
    $("#" + indice).remove();
}
function print_form(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    // $.redirect(siteurl('cash_output/print_cash_output'), {id: id}, 'POST', '_blank');
    print(id, 'inventory/print_inventory_entry');
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

function show_inventory_output(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('inventory/view_inventory_output');
    $.redirect(url, {id: data['id']}, 'POST', '_self');
}


/*  Ingreso por compra  */
function get_purchase_list() {
    var tabla = $('#purchase_list').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('inventory/get_purchase_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'nro_compra', class: 'text-center'},
            {data: 'monto_subtotal', class: 'text-center'},
            {data: 'monto_total', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 2,
            visible: true,
            searchable: false
        }, {
            targets: 3,
            visible: true,
            searchable: false
        }, {
            targets: 4,
            render: function (data) {
                return state_crud(data);
            }
        }, {
            targets: 5,
            orderable: false,
            render: function (data, type, row) {
                botones = '<a type="button" onclick="register_entry_purchase(this)" class="btn-primary btn">Ingresar compra a inventario</a>';
                return botones;
            }
        }]
    });
    tabla.ajax.reload();
}

function register_entry_purchase(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('inventory/new_purchase_entry');
    $.redirect(url, {id: data['id'], type_inventory_entry_id: $("#type_inventory_entry_id").val()}, 'POST', '_self');
}

function delete_register(element) {
    delete_register_commom(element, 'inventory/disable_inventory_entry');
}

function view_register_form(element) {
    show_register_commom(element);
}

function bar_code_reader() {

    var lector = $('#codigo_barra').val();
    $.ajax({
        url: site_url + 'product/get_code_name_product_autocomplete',
        dataType: "json",
        type:"post",
        data: {
            name_startsWith: lector,
            type: 'bar_code'
        },

        success: function (data) {

            if (data.validacion === 1) {

                $('#product_id').val(data.id);
                $('#precio_venta').val(data.precio_venta);
                $('#precio_compra').val(data.precio_compra);
                $('#producto').val(data.nombre_comercial+' - '+data.nombre_generico);
                $('#codigo_barra').val(data.codigo);

                load_proveedor_select(data.id);

            } else {
                swal("No existe", "No existe el producto", "error");
            }

            $('#nro_lote').focus();
        }
    });
}

function bar_code_reader_output() {
    var lector = $('#barcode_output').val();

    var type_product_id = $('#type_product').val();
    var warehouse_id = $('#warehouse').val();

    $.ajax({
        url: site_url + 'inventory/get_product_autocomplete',
        dataType: "json",
        type: 'post',
        data: {
            name_startsWith: lector,
            type: 'bar_code_reader_output',
            type_product_id: type_product_id,
            warehouse_id: warehouse_id
        },
        success: function (data) {

            if (data.validacion === 1) {
                $('#inventory_id').val(data.inventario_id);
                $('#barcode_output').val(data.codigo_producto);
                $('#nro_lote').val(data.lote);
                $('#quantity_available').val(data.stock);
                $('#price_cost').val(data.precio_costo);
                $('#price_sale').val(data.precio_venta);
                $('#date_expired').val(data.fecha_vencimiento);
                $('#product_output').val(data.nombre_comercial);
                $('#quantity').focus();

            } else {
                swal("No existe", "No existe el producto", "error");
                $('#barcode_output').val('');
                $('#barcode_output').focus();
            }

        }
    });
}

// evento de teclado
function event_nro_comprobante() {
    var nro = $('#nro_comprobante').val();
    // $('#nro_lote').val(''+ nro);
}




