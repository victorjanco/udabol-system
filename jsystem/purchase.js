/**
 * Created by mendoza on 19/08/2017.
 */
$(document).ready(function () {

    get_purchase_list();


    search_provider();
    search_product();
    $('#frm_add_product').submit(function (event) {
        event.preventDefault();
        add_row();
    });
    $('#unit_price').on("keyup", update_product_total);
    $('#unit_price').on("change", update_product_total);
    $('#quantity').on("keyup", update_product_total);
    $('#quantity').on("change", update_product_total);
    $("#additional_cost").on("keyup", update_product_cost_price);
    $("#additional_cost").on("change", update_product_cost_price);
    $("#storage_cost").on("keyup", update_product_cost_price);
    $("#storage_cost").on("change", update_product_cost_price);

    $("#purchase_off1").on("keyup", update_purchase_total);
    $("#purchase_off2").on("keyup", update_purchase_total);
    $("#purchase_off3").on("keyup", update_purchase_total);
    $("#purchase_off1").on("change", update_purchase_total);
    $("#purchase_off2").on("change", update_purchase_total);
    $("#purchase_off3").on("change", update_purchase_total);

    $(".delete_row").click(delete_row);

    register_purchase();
});

function get_purchase_list() {
    var tabla = $('#purchase_list').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('purchase/get_purchase_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'nro_compra', class: 'text-center'},
            {data: 'monto_subtotal', class: 'text-center'},
            {data: 'monto_total', class: 'text-center'},
            {data: 'fecha_compra_registro', class: 'text-center'},
            {data: 'nombre_proveedor', class: 'text-center'},
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
            visible: true,
            searchable: false
        }, {
            targets: 5,
            visible: true,
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
                if (row.estado == 1) {
                    // return load_buttons_crud(5, 'modal');
                    return load_buttons_crud(13,'modal')
                } else {
                    if (row.estado == 2){
                        return load_buttons_crud(13,'modal')
                    }else{
                        return '<label>No tiene Opciones</label>';
                    }
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}


function search_product() {
    $('.search_product').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('product/search_product'),
                dataType: "json",
                data: {
                    search: request.term
                },
                success: function (data) {
                    response($.map(data, function (value, key) {
                        var elem = value.split('|');
                        return {
                            value: elem[1],
                            label: elem[0] + "|" + elem[1],
                            id: key + "|" + value
                        };
                    }));
                }
            });
        },
        select: function (event, ui) {
            var key = (ui.item.id);
            var elem = key.split('|');
            $('.search_product_id').val(elem[0]);
            $('.search_product_code').val(elem[1]);
            $('.search_product_name').val(elem[2]);
            $('.search_product_cost_price').val(elem[3]);
            $('.search_product_sale_price').val(elem[4]);
            $('.search_product_wholesale_price').val(elem[5]);
        }
    });
}

function search_provider() {
    $('.search_provider').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('provider/search_provider'),
                dataType: "json",
                data: {
                    search: request.term
                },
                success: function (data) {
                    response($.map(data, function (value, key) {
                        var elem = value.split('|');
                        return {
                            value: elem[1],
                            label: elem[0] + "|" + elem[1],
                            id: key + "|" + value
                        };
                    }));
                }
            });
        },
        select: function (event, ui) {
            event.target.dataset.item = ui.item.id;
        },
        close: function (event) {
            var item = event.target.dataset.item;
            var elem = item.split('|');
            $('.search_provider_id').val(elem[0]);
            $('.search_provider_nit').val(elem[1]);
            $('.search_provider_name').val(elem[2]);
            $('.search_provider_direction').val(elem[3]);
            $('.search_provider_telephone').val(elem[4]);
            $('.search_provider_contact').val(elem[5]);
        }
    });
}

function update_purchase_total() {
    var purchase_off1 = parseFloat($("#purchase_off1").val());
    var purchase_off2 = parseFloat($("#purchase_off2").val());
    var purchase_off3 = parseFloat($("#purchase_off3").val());
    var purchase_subtotal = calculate_subtotal();

    var purchase_total = purchase_subtotal - (isNaN(purchase_off1) ? 0 : purchase_off1);
    purchase_total = purchase_total - (isNaN(purchase_off2) ? 0 : purchase_off2);
    purchase_total = purchase_total - (isNaN(purchase_off3) ? 0 : purchase_off3);

    $("#purchase_total").val(purchase_total);
}

function update_product_total() {
    var unit_price = parseFloat($("#unit_price").val());
    var quantity = parseFloat($("#quantity").val());
    var total = unit_price * quantity;
    $("#total").val(isNaN(total) ? 0 : total);

    update_product_cost_price();
}

function update_product_cost_price() {
    var quantity = parseFloat($("#quantity").val());
    var total = parseFloat($("#total").val());
    var additional_cost = parseFloat($("#additional_cost").val());
    var storage_cost = parseFloat($("#storage_cost").val());
    var cost_price = (total + additional_cost + storage_cost) / quantity;
    $("#cost_price").val(isNaN(cost_price) ? 0 : cost_price);
}

function delete_row(row) {
    var row_selected = $('#table_detail_purchase').find('tbody').find('tr').get(row - 1);
    row_selected.remove();
    // purchase_subtotal = purchase_subtotal - product_total;
    // $('#purchase_subtotal').val(isNaN(purchase_subtotal) ? 0 : purchase_subtotal);
    calculate_subtotal();

    $('#table_detail_purchase').find('tbody').find('tr').each(function (index, item) {
        var td = $(item).find('td').get(0);
        $(td).html(index + 1);

        var length = $(item).find('td').length;
        var tdd = $(item).find('td').get(length - 1);
        var html = '<button type="button" class="btn btn-danger" onclick="delete_row(' + (index + 1) + ')">' +
            '<i class="fa fa-minus"></i>&nbsp;Eliminar</button></td>';
        $(tdd).html(html);
    });
    update_purchase_total();
}

// var purchase_subtotal = 0;
function add_row() {
    var product_id = $("#product_id").val();
    if (product_id == null || product_id.length == 0) {
        swal('Error', 'El producto no es valido.');
        return;
    }

    var quantity = parseFloat($("#quantity").val());
    if (quantity == null || isNaN(quantity) || quantity < 1) {
        swal('Error', 'Ingrese una cantidad valida.');
        return;
    }

    var product_code = $("#product_code").val();
    var product_name = $("#product_name").val();
    var unit_price = $("#unit_price").val();
    var total = $("#total").val();
    var additional_cost = $("#additional_cost").val();
    var storage_cost = $("#storage_cost").val();
    var cost_price = $("#cost_price").val();
    var markup = $("#markup").val();
    var sale_price = $("#sale_price").val();
    var wholesale_price = $("#wholesale_price").val();
    var number_row = ($('#table_detail_purchase').find('tbody').find('tr').length + 1);

    var html = '<tr ';
    html += 'data-product_id="' + product_id + '" ';
    html += 'data-unit_price="' + unit_price + '" ';
    html += 'data-quantity="' + quantity + '" ';
    html += 'data-total="' + total + '" ';
    html += 'data-additional_cost="' + additional_cost + '" ';
    html += 'data-storage_cost="' + storage_cost + '" ';
    html += 'data-cost_price="' + cost_price + '" ';
    html += 'data-markup="' + markup + '" ';
    html += 'data-sale_price="' + sale_price + '" ';
    html += 'data-wholesale_price="' + wholesale_price + '" ';
    html += '>';
    html += '<td>' + number_row + '</td>';
    html += '<td>' + product_code + '</td>'; // TODO product_id
    html += '<td>' + product_name + '</td>';
    html += '<td>' + unit_price + '</td>';
    html += '<td>' + cost_price + '</td>';
    html += '<td>' + sale_price + '</td>';
    html += '<td>' + quantity + '</td>';
    html += '<td>' + total + '</td>';
    // html += '<td>' + additional_cost + '</td>';
    // html += '<td>' + storage_cost + '</td>';
    // html += '<td>' + markup + '</td>';
    // html += '<td>' + wholesale_price + '</td>';
    html += '<td style="text-align:center">' +
        '<button type="button" class="btn btn-danger" onclick="delete_row(' + number_row + ')">' +
        '<i class="fa fa-minus"></i>&nbsp;Eliminar</button></td>';
    html += '</tr>';


    $('#table_detail_purchase').find('tbody').append(html);
    //$('.close_modal').click();
    $('#frm_add_product').trigger('reset');
    // purchase_subtotal += parseFloat(total);
    // $('#purchase_subtotal').val(isNaN(purchase_subtotal) ? 0 : purchase_subtotal);
    calculate_subtotal();
    update_purchase_total();
}

function calculate_subtotal() {
    var purchase_subtotal = 0;
    $('#table_detail_purchase tbody tr').each(function (index, value) {
        purchase_subtotal += parseFloat(value.dataset.total);
    });
    $('#purchase_subtotal').val(isNaN(purchase_subtotal) ? 0 : purchase_subtotal);
    return isNaN(purchase_subtotal) ? 0 : purchase_subtotal;
}

function get_detail() {
    var detalle = [];
    $('#table_detail_purchase tbody tr').each(function (index, value) {
        var data = {};
        data['product_id'] = value.dataset.product_id;
        data['unit_price'] = value.dataset.unit_price;
        data['quantity'] = value.dataset.quantity;
        data['total'] = value.dataset.total;
        data['additional_cost'] = value.dataset.additional_cost;
        data['storage_cost'] = value.dataset.storage_cost;
        data['cost_price'] = value.dataset.cost_price;
        data['markup'] = value.dataset.markup;
        data['sale_price'] = value.dataset.sale_price;
        data['wholesale_price'] = value.dataset.wholesale_price;
        detalle.push(data);
    });

    return detalle;
}


function register_purchase() {
    $('#frm_register_purchase').submit(function (event) {
        event.preventDefault();

        var provider = $('#purchase_provider_id').val();


        if (provider == "") {
            swal('No existe proveedor', 'No existe el registro de proveedor, registre un nuevo proveedor', 'error');
            return;
        }

        var cantidad_filas = $('#table_detail_purchase tbody tr').length;
        if(cantidad_filas > 0){
            var data = {};
            var form_data = $(this).serializeArray();
            $(form_data).each(function (i, field) {
                data[field.name] = field.value;
            });
            data['detail_table'] = get_detail();
            ajaxStart('Guardando registros, por favor espere');
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: data,
                dataType: 'json',
                success: function (response) {
                    ajaxStop();
                    if (response.success === true) {
                        // var id = response.messages;
                        // alert(id);
                        swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                        setTimeout(function () {
                            location.href = site_url + "purchase/index"
                        });
                        // location.href = site_url("purchase/index");
                    } else if(response.login === true){
                        login_session();
                    } else {
                        $('.abm-error').remove();
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
        }else{
            swal('Error', 'Datos incorrectos, debe de agregar un detalle.', 'error');
        }
    });
}

function edit_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    $.redirect(siteurl('purchase/edit_purchase'), {id: id}, 'POST', '_self');
}

function delete_register(element) {
    delete_register_commom(element, 'purchase/disable_purchase');
}

/*funcion para editar al ingreso*/
function print_form(element) {
    var table = $(element).closest('table').DataTable();
    var current_row = $(element).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('print_purchase/print_detail');
    $.redirect(url, {id: data['id']}, 'POST', '_blank');
}