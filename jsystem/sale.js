/**
 *  * Created by Ariel Alejandro Gomez Chavez ( @ArielGomez ) on 11/9/2017.
 */
$(document).ready(function () {

    get_warehouse_sale();

    get_sale_note_list_disable();

    /*cargamos el metodo los almacenes apartir de la sucursal selecciona*/
    if(typeof cash_id !== 'undefined' && typeof cash_aperture_id !== 'undefined'){
        if(cash_id==false || cash_aperture_id==0){
            console.log(cash_id);
            console.log(cash_aperture_id);
            // alert('CAJA NO APERTURADA');
            swal({
                title: "Caja No aperturada",
                text: "La caja  no fue aperturada",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ok!",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                $.redirect(siteurl("home"));
            });
        }
    }

    $("#close_modal_add_row_sale").click(function(){
        $("#frm_add_product_sale")[0].reset();
    });
    
    $('#monto_sus').hide();
    $('#monto_tarjeta').hide();
    $('#monto_cheque').hide();
    $('#monto_bs').hide();

    $('#check_contado').click(function(e) {
        var value_check=$(this).is(':checked');
        if(value_check){
            $("#monto_bs").val('');
            $('#monto_bs').show();

            // $("#check_dolares")[0].checked = false;   
            // $("#check_tarjeta")[0].checked = false;
            // $("#check_cheque")[0].checked = false;

            // $('#monto_sus').hide();
            // $('#monto_tarjeta').hide();
            // $('#monto_cheque').hide();

            // $("#monto_sus").val(0);
            // $("#monto_tarjeta").val(0);
            // $("#monto_cheque").val(0);
        }else{
            $('#monto_bs').hide();
            $("#monto_bs").val(0);
            // clear_efective_amount();
            calcular_total_venta_forma_pago();
        }
    });
    $('#check_dolares').click(function(e) {
        var value_check=$(this).is(':checked');
        if(value_check){
            $("#monto_sus").val('');
            $('#monto_sus').show();

            // $("#check_contado")[0].checked = false;   
            // $("#check_tarjeta")[0].checked = false;
            // $("#check_cheque")[0].checked = false;

            // $('#monto_bs').hide();
            // $('#monto_tarjeta').hide();
            // $('#monto_cheque').hide();
            
            // $("#monto_bs").val(0);
            // $("#monto_tarjeta").val(0);
            // $("#monto_cheque").val(0);
        }else{
            $('#monto_sus').hide();
            $("#monto_sus").val(0);
            // clear_efective_amount();
            calcular_total_venta_forma_pago();
        }
    });

    $('#check_tarjeta').click(function(e) {
        var value_check=$(this).is(':checked');
        if(value_check){
            $("#monto_tarjeta").val('');
            $('#monto_tarjeta').show();

            // $("#check_contado")[0].checked = false;   
            // $("#check_dolares")[0].checked = false;
            // $("#check_cheque")[0].checked = false;

            // $('#monto_sus').hide();
            // $('#monto_bs').hide();
            // $('#monto_cheque').hide();
            
            // $("#monto_sus").val(0);
            // $("#monto_bs").val(0);
            // $("#monto_cheque").val(0);
        }else{
            $('#monto_tarjeta').hide();
            $("#monto_tarjeta").val(0);
            // clear_efective_amount();
            calcular_total_venta_forma_pago();
        }
    });
    $('#check_cheque').click(function(e) {
        var value_check=$(this).is(':checked');
        if(value_check){
            $("#monto_cheque").val('');
            $('#monto_cheque').show();

            // $("#check_contado")[0].checked = false;   
            // $("#check_dolares")[0].checked = false;
            // $("#check_tarjeta")[0].checked = false;

            // $('#monto_sus').hide();
            // $('#monto_tarjeta').hide();
            // $('#monto_bs').hide();
            
            // $("#monto_sus").val(0);
            // $("#monto_tarjeta").val(0);
            // $("#monto_bs").val(0);
        }else{
            $('#monto_cheque').hide();
            $("#monto_cheque").val(0);
            // clear_efective_amount();
            calcular_total_venta_forma_pago();
        }
    });

    $("#monto_sus").keyup(function(e){
        // var amount = parseFloat($("#monto_sus").val());
        // var monto_venta = parseFloat($("#sale_total").val());
        // console.log(monto_venta);
        // var efectivo = amount*parseFloat(change_type.monto_cambio_venta);
        // $("#monto_efectivo").val(efectivo.toFixed(2));
        // var monto_cambio= efectivo-monto_venta;
        // $("#monto_cambio").val(monto_cambio.toFixed(2));
        
        // if(monto_venta > efectivo){
        //     $("#monto_cambio").val(0);
        // }
        calcular_total_venta_forma_pago();
    });

    $("#monto_bs").keyup(function(e){
        // var amount = parseFloat($("#monto_bs").val());
        // var monto_venta = parseFloat($("#sale_total").val());
        // console.log(monto_venta);
        // $("#monto_efectivo").val(amount.toFixed(2));
        // var monto_cambio= amount-monto_venta;
        // $("#monto_cambio").val(monto_cambio.toFixed(2));
        
        // if(monto_venta > amount){
        //     $("#monto_cambio").val(0);
        // }
        calcular_total_venta_forma_pago();
    });

    $("#monto_tarjeta").keyup(function(e){
        // var amount = parseFloat($("#monto_tarjeta").val());
        // var monto_venta = parseFloat($("#sale_total").val());
        // console.log(monto_venta);
        // $("#monto_efectivo").val(amount.toFixed(2));
        calcular_total_venta_forma_pago();
    });
    $("#monto_cheque").keyup(function(e){
        // var amount = parseFloat($("#monto_cheque").val());
        // var monto_venta = parseFloat($("#sale_total").val());
        // console.log(monto_venta);
        // $("#monto_efectivo").val(amount.toFixed(2));
        calcular_total_venta_forma_pago();
    });

    $('#price_discount').on('keyup', function(e) {
        var discount = e.target.value;
        console.log(discount);
        calculate_discount(Number(discount));
        // var price_product_sale=$("#product_price_sale").val();
        // $("#product_price_discount").val(price_product_sale-discount);
    });
});

$(document).ready(function () {

    // search_product();
    $('#frm_add_product_sale').submit(function (event) {
        event.preventDefault();
        add_row_product_sale();
    });

    $('#cancel_sale').click(function (event) {
        event.preventDefault();
        cancel_sale();
    });

    $('#btn_new_note_sale').click(function (event) {
        $('#sale_type').val('NOTA VENTA');
        event.preventDefault();
        register_sale();
    });

    $('#btn_new_invoice_sale').click(function (event) {
        $('#sale_type').val('FACTURA');
        event.preventDefault();
        register_sale();
    });
    $('#btn_generate_invoice').click(function (event) {
        generate_invoice_for_sale();
    });

    $('#btn_new_credit_sale').click(function (event) {
        $('#sale_type').val('VENTA CREDITO');
        event.preventDefault();
        register_sale();
    });

    /*Metodo actualizar la lista de almacen segun la sucursal seleccionada*/
    $('#branch_office_sale').change(function () {
        $('#warehouse_sale').empty();
        get_warehouse_sale();
    });

    $('#sale_discount').keyup(function () {
        calculate_total_amounts_product();
    });

    $('#sale_discount').click(function () {
        // open_verify();
    });

    /*  Evento change de filtro por fecha de rececpion  */
    $("#filter_date_start").on('change', function () {
        console.log($("#filter_date_start").val());
        get_sale_note_list();
        get_sale_note_list_disable();
    });

    $("#filter_date_end").on('change', function () {
        console.log($("#filter_date_end").val());
        get_sale_note_list();
        get_sale_note_list_disable();
    });

    /*  Evento change de filtro por codigo de recepcion  */
    $("#filter_sale_number").keyup(function () {
        get_sale_note_list();
        get_sale_note_list_disable();
    });

    /*Cuando da clic guardar en el formulario nuevo cliente*/
    $('#frm_add_customer').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        ajaxStart('Guardando Nuevo Cliente, por favor espere');
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                console.log(respuesta);
                ajaxStop();
                if (respuesta.success === true) {
                    $('.modal-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_new_customer').click();
                    $('#frm_add_customer')[0].reset();
                    var customer_id = respuesta.customer.id;
                    var customer_name = respuesta.customer.nombre;
                    var customer_ci = respuesta.customer.ci;
                    var customer_nit = respuesta.customer.nit;
                    var customer_name_bill = respuesta.customer.nombre_factura;
                    var customer_type = get_type_customer(parseInt(respuesta.customer.tipo_cliente));

                    $('#nombre_factura').val(customer_name_bill);
                    $('#nit').val(customer_nit);
                    $('#id_customer').val(customer_id);
                    $('#type_customer_sale').val(customer_type);

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

    /*autocompletado para la busqueda por el nombre del producto apartir de sucursal_id, almacen_id y tipo_producto_id*/
    $('.search_name_product_sale').autocomplete({
        source: function (request, response) {
            var branch_office_sale = $('#branch_office_sale').val();
            var subgroup_sale = $('#subgroup_sale').val();
            var warehouse_sale = $('#warehouse_sale').val();
            var type_customer_sale = $('#type_customer').val();
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                type: 'post',
                data: {
                    name_startsWith: request.term,
                    type: 'name_product_sale',
                    branch_office_sale: branch_office_sale,
                    subgroup_sale: subgroup_sale,
                    type_customer_sale: type_customer_sale,
                    warehouse_sale: warehouse_sale

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

            $('#product_id_sale').val(elem[0]);
            $('#product_code_sale').val(elem[1]);
            $('#product_price_sale').val(parseFloat(elem[2]).toFixed(2));
            $('#product_quantity_sale').attr('max', elem_data[3]);
            $('#product_stock_sale').val(elem_data[3]);
            $('#product_quantity_sale').focus();

            $('#price_discount').val(0);
            calculate_discount(0);
        }
    });

    /*autocompletado para la busqueda por el codigo y codigo de barra del producto apartir de sucursal_id, almacen_id y tipo_producto_id*/
    $('.search_code_product_sale').autocomplete({
        source: function (request, response) {
            var branch_office_sale = $('#branch_office_sale').val();
            var subgroup_sale = $('#subgroup_sale').val();
            var warehouse_sale = $('#warehouse_sale').val();
            var type_customer_sale = $('#type_customer').val();
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                type: 'post',
                data: {
                    name_startsWith: request.term,
                    type: 'code_product_sale',
                    branch_office_sale: branch_office_sale,
                    subgroup_sale: subgroup_sale,
                    type_customer_sale: type_customer_sale,
                    warehouse_sale: warehouse_sale

                },
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
            var data_product = (ui.item.id);
            var elem = data_product.split('/');
            var data_array = ui.item.label;
            var elem_data = data_array.split('/');
            console.log(elem);
            $('#product_id_sale').val(elem[0]);
            $('#product_name_sale').val(elem[1]);
            $('#product_price_sale').val(parseFloat(elem[2]).toFixed(2));
            $('#product_quantity_sale').attr('max', elem_data[3]);
            $('#product_stock_sale').val(elem_data[3]);

            //$('#product_stock_sale').attr(');
            $('#product_quantity_sale').focus();

            $('#price_discount').val(0);
            calculate_discount(0);
        }
    });

    $("#product_code_sale").keypress(function (e) {
        var tecla = (e.keyCode ? e.keyCode : e.which);
        var enter = 13;
        if (tecla == enter) {
            lector_codigo_barra();
            return false;
        }
    });


});

function calculate_discount(discount){
    // var userInput = e.target.value;
    var price_product_sale=$("#product_price_sale").val();
    $("#product_price_discount").val(price_product_sale-discount);
}

function calcular_total_venta_forma_pago(){
    var amount_cheque= parseFloat($("#monto_cheque").val());
    var amount_tarjeta = parseFloat($("#monto_tarjeta").val());
    var amount_bs= parseFloat($("#monto_bs").val());
    var amount_sus = parseFloat($("#monto_sus").val());
    var efectivo_sus = amount_sus*parseFloat(change_type.monto_cambio_venta);

    var monto_venta = parseFloat($("#sale_total").val());

    var total_efectivo=amount_cheque+amount_tarjeta+amount_bs+efectivo_sus;
    

    $("#monto_efectivo").val(total_efectivo.toFixed(2));
    var total_cambio=total_efectivo-monto_venta;

    $("#monto_cambio").val(total_cambio.toFixed(2));

    // if(monto_venta > total_efectivo){
    //     $("#monto_cambio").val(0);
    // }
}

function check_sus(){
    var value_check=$("#monto_sus").is(':checked');
    alert('sdsd');
    if(value_check){
        alert('sdsd');
        $("#monto_sus").click();
    }
}

function clear_efective_amount(){
    $("#monto_efectivo").val(0);
    $("#monto_cambio").val(0);
}
/*Funcion para guardar la nueva venta  */
function register_sale() {

    event.preventDefault();
    var cantidad_filas = $('#table_detail_sale tbody tr').length;
    // console.log(cantidad_filas);
    var data = $('#frm_register_sale').serialize();

    var monto_efectivo = parseFloat($("#monto_efectivo").val());
    // console.log(monto_efectivo);
    var sale_total = parseFloat($("#sale_total").val());
    // console.log(sale_total);
    var sale_type = $('#sale_type').val();

    $('.modal-error').remove();
    if ((cantidad_filas > 0 && monto_efectivo > 0 && monto_efectivo >= sale_total) || (cantidad_filas > 0 &&  sale_type=="VENTA CREDITO")) {
       
        ajaxStart('Guardando la venta, por favor espere');
        $.ajax({
            url: site_url + 'sale/register_sale',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (response) {
                ajaxStop();
                console.log(response.datos_verify);

                if (response.success === true) {
                    var id = response.sale;
                    var url_sale = response.url_impression;
                    $('#frm_register_sale')[0].reset();
                    print(id, url_sale);
                    setTimeout(function () {
                        location.href = site_url + "sale/";
                    }, 1500);

                } else if(response.cash === true){
                    swal({
                        title: "Caja Cerrada",
                        text: "Caja cerrada, aperture nuevamente la caja",
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        $.redirect(siteurl("sale"));
                    });
                }else if (response.login === true) {
                    login_session();
                } else if (response.dosage_message !== null) {
					swal('Problemas con Dosificacion',response.dosage_message , 'error');
                } else if (response.success === false) {
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
        swal(
            'Error',
            'Datos incorrectos, debe de agregar un detalle, debe elegir una forma de pago, monto pago debe ser mayor o igual al total venta', 
            'error'
        )
    }
}

function generate_invoice_for_sale() {
    id_sale = $('#id_sale').val();
    id_customer = $('#id_customer').val();
    nombre_factura = $('#nombre_factura').val();
    nit =  $('#nit').val();
    dosage_id =$('#dosage_id').val();
    ajaxStart('Guardando la venta, por favor espere');
    $.ajax({
        url: site_url + 'sale/generate_invoice',
        type: 'post',
        data: {
            id_sale: id_sale,
            id_customer:id_customer,
            nombre_factura:nombre_factura,
            nit:nit,
            dosage_id:dosage_id
            },
        dataType: 'json',
        success: function (response) {
            ajaxStop();
            if (response.success === true) {
                var id = response.sale;
                var url_sale = response.url_impression;
                
                 print(id, url_sale);
                 setTimeout(function () {
                 location.href = site_url + "sale/sale_invoice";
                 }, 1500);

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
}

/* Abre una ventana modal para editar marca */
function generate_invoice(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    $('#delete_data').remove();
    $.post(siteurl('sale/get_sale_for_invoice'), {id: id},
        function (response) {
            var data = JSON.parse(response);
            $('#id_sale').val(id);
            $('#nit').val(data.customer.nit);
            $('#nombre_factura').val(data.customer.nombre_factura);
            
            $.each(data.dosage,function(key, registro) {
                $("#dosage_id").append('<option value='+registro.id+'>'+registro.nombre_actividad+' / '+registro.marca+' / '+registro.serial+'</option>');
            }); 
            $('#id_sale').val(id);
            var sale_header = '<div class="form-group" style="text-align: left" id="delete_data"> <div class="form-line">' +
                '<label>Tipo Venta: ' + '&nbsp;</label>' + data.sale.tipo_venta + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                '<label>Nro. Venta: ' + '&nbsp;</label>' + data.sale_branch_office.nro_venta + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                '<br>' +
                '<label>Fecha Venta: ' + '&nbsp;</label>' + data.sale.fecha_registro + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                '<label>Sucursal: ' + '&nbsp;</label>' + data.branch_office.nombre_comercial + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                '<br>' +
                '<div style="text-align: center"><label>DETALLE DE LA VENTA</label></div>' +
                '<br>';
            var sale_detail = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><label>Producto</label></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label>Cantidad</label></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label>Precio</label></div>';
            var data_detail = data.sale_detail;
            $.each(data_detail, function () {
                sale_detail = sale_detail + '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="left">' + this.descripcion + '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" align="right" style="padding-right: 8%">' + this.cantidad + '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" align="right" style="padding-right: 8%">' + this.precio_venta + '</div>';
            });
            var sale_footer = '<br><br><div align="right" style="padding-right: 4%">' +
                '<label>Monto Subtotal: ' + '&nbsp;</label>' + data.sale.subtotal + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                '<br>' +
                '<label>Descuento: ' + '&nbsp;</label>' + data.sale.descuento + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                '<br>' +
                '<label>Total: ' + '&nbsp;</label>' + data.sale.total + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                '</div> </div></div>';
            var sale_data = sale_header + sale_detail + sale_footer;
            $('#sale_data').append(sale_data);

            if(data.dosage.length>0){
                $('#modal_view_for_invoice').modal({
                    show: true,
                    backdrop: 'static'
                });
            }else{
                swal('Advertencia', 'No existen dosificaciones Activas..', 'warning');
            }
        });
}

function print_form(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('print_sale/print_note_sale');
    $.redirect(url, {id: data['id']}, 'POST', '_blank');

}

function print_form_invoice(elemento) {
	var table = $(elemento).closest('table').DataTable();
	var current_row = $(elemento).parents('tr');
	if (current_row.hasClass('child')) {
		current_row = current_row.prev();
	}
	var data = table.row(current_row).data();

	var url = siteurl('print_sale/print_invoice_sale');
	$.redirect(url, {id: data['id']}, 'POST', '_blank');
}



/*Funcion para buscar almacen segun la sucursal seleccionada*/
function get_warehouse_sale() {
    var id_branch_office = $('#branch_office_sale').val();

    var data = get_warehouse_by_branch_office_id(id_branch_office);
    $.each(data, function (i, item) {
        $('#warehouse_sale').append('<option value="' + item.id + '">' + item.nombre + '</option>');
    });

}

function add_row_product_sale() {
    var frm = $('#frm_add_product_sale').serialize();
    ajaxStart('Agregando producto al detalle, por favor espere');
    $.ajax({
        url: site_url + 'sale/add_row_product_sale',
        data: frm,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            ajaxStop();
            $('.modal-error').remove();
            if (response.success === true) {
                $('#table_detail_sale tbody').append(response.data);
                delete_row_product_sale();
                // $("#frm_add_product_sale")[0].reset();
                $(".close-modal").click();
                calculate_total_amounts_product();
                $('#product_name_sale').focus();
            } else if (response.login === true) {
                login_session();
            } else if (response.verify_data === false) {
                swal('Error', response.data, 'error');
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


}

function verify_discount() {


}

function sale_view(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    $('#delete_data_sale').remove();
    $.post(siteurl('sale/get_sale_for_invoice'), {id: id},
        function (response) {
            var data = JSON.parse(response);
            var customer_phone = data.customer.telefono1 + ' - ' + data.customer.telefono2;
            switch (parseInt(data.customer.tipo_cliente)) {
                case 0:
                    customer_type = 'Cliente Diario'
                    break;
                case 1:
                    customer_type = 'Cliente por Mayor'
                    break;
                case 2:
                    customer_type = 'Cliente Express'
                    break;
                case 3:
                    customer_type = 'Cliente laboratorio'
                    break;
            }

            $('#nit').val(data.customer.nit);
            $('#nombre_factura').val(data.customer.nombre_factura);
            var sale_header = '<div class="form-group" style="text-align: left" id="delete_data_sale"> <div class="form-line">' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Sucursal: ' + '&nbsp;' + data.branch_office.nombre_comercial + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Nro. Venta: ' + '&nbsp;' + data.sale_branch_office.nro_venta + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Cod. Trabajo: ' + '&nbsp;' + data.sale.codigo_recepcion + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Vendedor: ' + '&nbsp;' + data.user.nombre + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Fecha Venta: ' + '&nbsp;' + data.sale.fecha_registro + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Tipo Cliente: ' + '&nbsp;' + customer_type + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Cliente: ' + '&nbsp;' + data.customer.nombre + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>C.I.: ' + '&nbsp;' + data.customer.ci + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Telefono: ' + '&nbsp;' + customer_phone + '</label></div>' +
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE VENTA</label></div>';

            var sale_detail = '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><label>Codigo</label></div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><label>Producto</label></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label>Cantidad</label></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label>Precio</label></div>';
            var data_detail = data.sale_detail;
            $.each(data_detail, function () {
                sale_detail = sale_detail + '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="left">' 
                + this.codigo_producto + '</div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right" style="padding-right: 8%">' 
                + this.nombre_producto + '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" align="right" style="padding-right: 8%">' 
                + this.cantidad + '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" align="right" style="padding-right: 8%">' 
                + this.precio_venta_descuento + '</div>';
            });
            var discount = '';
            if (parseInt(data.sale.descuento) != 0) {
                discount = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: right; padding-right: 8%"><label>Monto Subtotal: ' + '&nbsp;' + data.sale.subtotal + '</label></div>' +
                    '<div class="col-lg-12 col-md-12 col-sm-12  col-xs-12" style="text-align: right; padding-right: 8%"><label>Descuento: ' + '&nbsp;' + data.sale.descuento + '</label></div>';
            }
            var sale_footer = '<br><br><br>' +
                discount +
                '<div class="col-lg-12 col-md-12 col-sm-12  col-xs-12" style="text-align: right; padding-right: 8%"><label>Total: ' + '&nbsp;' + data.sale.total + '</label></div>' +
                '</div></div>';
            var sale_data = sale_header + sale_detail + sale_footer;
            $('#sale_view_data').append(sale_data);
        });
    $('#modal_view_sale').modal({
        show: true,
        backdrop: 'static'
    });
}
function invoice_sale_view(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    $('#delete_data_sale').remove();
    $.post(siteurl('sale/get_sale_invoice'), {id: id},
        function (response) {
            var data = JSON.parse(response);
            var customer_phone = data.customer.telefono1 + ' - ' + data.customer.telefono2;
            switch (parseInt(data.customer.tipo_cliente)) {
                case 0:
                    customer_type = 'Cliente Diario'
                    break;
                case 1:
                    customer_type = 'Cliente por Mayor'
                    break;
                case 2:
                    customer_type = 'Cliente Express'
                    break;
                case 3:
                    customer_type = 'Cliente laboratorio'
                    break;
            }

            $('#nit').val(data.customer.nit);
            $('#nombre_factura').val(data.customer.nombre_factura);
            var sale_header = '<div class="form-group" style="text-align: left" id="delete_data_sale"> <div class="form-line">' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Sucursal: ' + '&nbsp;' + data.branch_office.nombre_comercial + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Nro. Venta: ' + '&nbsp;' + data.sale_branch_office.nro_venta + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Cod. Trabajo: ' + '&nbsp;' + data.sale.codigo_recepcion + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Vendedor: ' + '&nbsp;' + data.user.nombre + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Fecha Venta: ' + '&nbsp;' + data.sale.fecha_registro + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Tipo Cliente: ' + '&nbsp;' + customer_type + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Cliente: ' + '&nbsp;' + data.customer.nombre + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>C.I.: ' + '&nbsp;' + data.customer.ci + '</label></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label>Telefono: ' + '&nbsp;' + customer_phone + '</label></div>' +
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE VENTA</label></div>';

            var sale_detail = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><label>Producto</label></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label>Cantidad</label></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label>Precio</label></div>';
            var data_detail = data.sale_detail;
            $.each(data_detail, function () {
                sale_detail = sale_detail + '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="left">' + this.descripcion + '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" align="right" style="padding-right: 8%">' + this.cantidad + '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" align="right" style="padding-right: 8%">' + this.precio_venta + '</div>';
            });
            var discount = '';
            if (parseInt(data.sale.descuento) != 0) {
                discount = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: right; padding-right: 8%"><label>Monto Subtotal: ' + '&nbsp;' + data.sale.subtotal + '</label></div>' +
                    '<div class="col-lg-12 col-md-12 col-sm-12  col-xs-12" style="text-align: right; padding-right: 8%"><label>Descuento: ' + '&nbsp;' + data.sale.descuento + '</label></div>';
            }
            var sale_footer = '<br><br><br>' +
                discount +
                '<div class="col-lg-12 col-md-12 col-sm-12  col-xs-12" style="text-align: right; padding-right: 8%"><label>Total: ' + '&nbsp;' + data.sale.total + '</label></div>' +
                '</div></div>';
            var sale_data = sale_header + sale_detail + sale_footer;
            $('#sale_view_data').append(sale_data);
        });
    $('#modal_view_sale').modal({
        show: true,
        backdrop: 'static'
    });
}

function calculate_total_amounts_product() {

    var subtotal_amount = 0;
    $('#table_detail_sale tbody tr').each(function (index, value) {
        console.log('fila');
        console.log(value.dataset);
        subtotal_amount = subtotal_amount + parseFloat(value.dataset.price);
    });
    $('#sale_subtotal').val(subtotal_amount.toFixed(2));
    var discount = '';
    $('#sale_discount').val();
    if ($('#sale_discount').val() == '' || $('#sale_discount').val() == ' ') {
        discount = 0;
    } else {
        discount = $('#sale_discount').val();
    }
    var total_amount = subtotal_amount - discount;
    $("#sale_total").val(total_amount.toFixed(2));

    $("#frm_add_product_sale")[0].reset();
}

function cancel_sale() {
    ajaxStart('Cancelando la Venta, por favor espere');
    $.ajax({
        url: siteurl('sale/cancel_sale'),
        dataType: "json",
        type: 'post',
        success: function (response) {
            ajaxStop();
            if (response == true) {
                swal('Detalle Cancelado', 'Se limpio correctamente su detalle', 'success');
                location.href = site_url + "sale/";
            } else {
                swal('Error', 'Ocurrio un error ala eliminar el detalle.', 'error');
            }
        }
    });
}

function delete_row_product_sale() {

    $("a.elimina").click(function () {
        var valores = [];
        var i = 0;
        $(this).parents("tr").find("td").each(function () {
            valores.push($(this).children("input").val());
            console.log(valores);
            i++;
        })
        update_detail_virtual(valores[0], valores[1], valores[2], valores[3], valores[4], valores[9]);

        $(this).parents("tr").fadeOut("normal", function () {
            $(this).remove();

            calculate_total_amounts_product();
        });

    });
}

function update_detail_virtual(product_id, branch_office_id, warehouse_id, user_id, session_id, quantity) {
    ajaxStart('Eliminando producto del detalle, por favor espere');
    $.ajax({
        url: siteurl('sale/delete_row_product_detail_sale'),
        dataType: "json",
        type: 'post',
        data: {
            type: 'delete_row_detail_sale',
            branch_office_id: branch_office_id,
            user_id: user_id,
            session_id: session_id,
            warehouse_id: warehouse_id,
            product_id: product_id,
            quantity: quantity
        },
        success: function (response) {
            ajaxStop();
            if (response == true) {
                swal('Producto eliminado', 'El producto seleccionado fue eliminado del detalle.', 'success');
            } else {
                swal('Error', 'Ocurrio un error ala eliminar el producto del detalle.', 'error');
            }
        }
    });
}


/*carga la lista de los clientes al datatable*/
function get_sale_note_list() {
    var tabla = $('#list_sale_note').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: siteurl('sale/get_sale_note_list'),
            type: 'post',
            "data": {
                filter_date_start: $("#filter_date_start").val(),
                filter_date_end: $("#filter_date_end").val(),
                filter_sale_number: $("#filter_sale_number").val()
            }
        },
        columns: [
            {data: 'id'},
            {data: 'usuario', class: 'text-center'},
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'tipo_venta', class: 'text-center'},
            {data: 'nro_venta', class: 'text-center'},
            // {data: 'codigo_recepcion', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'total', class: 'text-right'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, 
        // {
            // targets: 4,
            // orderable: false,
            // render: function (data, type, row) {
            //     if (row.codigo_recepcion != '') {
            //         return row.codigo_recepcion;
            //     } else {
            //         return '<label>---</label>';
            //     }
            // }
        // },
        {
            targets: 7,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(6, 'formulario');
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }],
        // pagingType: "full_numbers",
        /* responsive: true,

         select: false*/
    });
}

function get_sale_invoice_list() {
	var tabla = $('#list_sale_invoice').DataTable({
		paging: true,
		info: true,
		filter: true,
		stateSave: true,
		processing: true,
		serverSide: true,
		destroy: true,
		ajax: {
			url: siteurl('sale/get_sale_invoice_list'),
			type: 'post',
			"data": {
				filter_date_start: $("#filter_date_start").val(),
				filter_date_end: $("#filter_date_end").val(),
				filter_sale_number: $("#filter_sale_number").val()
			}
		},
		columns: [
            {data: 'id'},
			{data: 'usuario', class: 'text-center'},
			{data: 'fecha', class: 'text-center'},
			{data: 'nro_factura', class: 'text-center'},
			{data: 'nit_cliente', class: 'text-center'},
			{data: 'nombre_cliente', class: 'text-center'},
			{data: 'importe_total_venta', class: 'text-right'},
			{data: 'descuento', class: 'text-right'},
			{data: 'importe_base_iva', class: 'text-right'},
			{data: 'opciones', class: 'text-center'}
		],
		columnDefs: [{
                targets: 0,
                visible: false,
                searchable: false
            },
            {
			targets: 9,
			orderable: false,
			render: function (data, type, row) {
				if (row.estado != 0) {
					return load_buttons_crud(19, 'formulario');
				} else {
					return '<label>No tiene Opciones</label>';
				}
            }
            
        }],
		// pagingType: "full_numbers",
		/* responsive: true,

         select: false*/
	});

}


function delete_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var tipo_venta = rowData['tipo_venta'];
    console.log(tipo_venta);
    
    if(tipo_venta=="VENTA CREDITO"){
        delete_register_commom(element, 'sale/disable_sale_credit');    
    }else{
        delete_register_commom(element, 'sale/disable_sale');
    }
    
}

function delete_register_invoice(element) {
    delete_register_commom(element, 'sale/disable_invoice');
}

/*carga la lista de los clientes anuladas*/
function get_sale_note_list_disable() {
    var tabla = $('#list_sale_note_disable').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: siteurl('sale/get_sale_note_list_disable'),
            type: 'post',
            "data": {
                filter_date_start: $("#filter_date_start").val(),
                filter_date_end: $("#filter_date_end").val(),
                filter_sale_number: $("#filter_sale_number").val()
            }
        },
        columns: [
            {data: 'id'},
            {data: 'usuario', class: 'text-center'},
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'tipo_venta', class: 'text-center'},
            {data: 'nro_venta', class: 'text-center'},
            {data: 'codigo_recepcion', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'total', class: 'text-right'},
            {data: 'estado', class: 'text-right'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 5,
            orderable: false,
            render: function (data, type, row) {
                if (row.codigo_recepcion != '') {
                    return row.codigo_recepcion;
                } else {
                    return '<label>---</label>';
                }
            }
        }, {
            targets: 8,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado == 0) {
                    return '<label> ANULADO </label>';
                }
            }
        }, {
            targets: 9,
            orderable: false,
            render: function (data, type, row) {
                return load_buttons_crud(12, 'formulario');
            }
        }],
        // pagingType: "full_numbers",
        /* responsive: true,

         select: false*/
    });
    tabla.ajax.reload();
}

function get_sale_note_credit_list() {
    var tabla = $('#list_sale_note').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: siteurl('sale/get_sale_note_credit_list'),
            type: 'post',
            "data": {
                filter_date_start: $("#filter_date_start").val(),
                filter_date_end: $("#filter_date_end").val(),
                filter_sale_number: $("#filter_sale_number").val()
            }
        },
        columns: [
            {data: 'id'},
            {data: 'usuario', class: 'text-center'},
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'tipo_venta', class: 'text-center'},
            {data: 'nro_venta', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'monto_credito', class: 'text-right'},
            {data: 'monto_saldo', class: 'text-right'},
            {data: 'deuda', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 8,
            orderable: false,
            render: function (data, type, row) {
                if (row.deuda == 1) {
                    return '<b><span class="label label-danger" style="font-size: 12px"> PENDIENTE </span></b>';
                } else {
                    return '<b><span class="label label-success" style="font-size: 12px"> PAGADO </span></b>';
                }
            }
        }, {
            targets: 9,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado == 0) {
                    return '<b><span class="label label-danger" style="font-size: 12px"> ANULADO </span></b>';
                } else {
                    return '<b><span class="label label-success" style="font-size: 12px"> ACTIVO </span></b>';
                }
            }
        }, {
            targets: 10,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(15, 'formulario');
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }],
        // pagingType: "full_numbers",
        /* responsive: true,

         select: false*/
    });
    tabla.ajax.reload();
}

function lector_codigo_barra() {
    var lector = $('#product_code_sale').val();
    var branch_office_sale = $('#branch_office_sale').val();
    var subgroup_sale = $('#subgroup_sale').val();
    var warehouse_sale = $('#warehouse_sale').val();
    var type_customer_sale = $('#type_customer').val();
    $.ajax({
        url: site_url + 'inventory/get_product_autocomplete',
        dataType: "json",
        type: 'post',
        data: {
            name_startsWith: lector,
            type: 'bar_code',
            branch_office_sale: branch_office_sale,
            subgroup_sale: subgroup_sale,
            type_customer_sale: type_customer_sale,
            warehouse_sale: warehouse_sale
        },
        success: function (data) {

            if (data.validacion === 1) {
                $('#product_id_sale').val(data.producto_id);
                $('#product_name_sale').val(data.nombre_comercial+' - '+ data.nombre_generico);
                $('#product_price_sale').val(data.precio_real.toFixed(2));
                $('#product_stock_sale').val(data.stock_real);
                $('#product_quantity_sale').focus();

            } else {
                swal("No existe", "No existe el producto", "error");
                $('#product_code_sale').val('');
                $('#product_code_sale').focus();
            }

        }
    });
}
