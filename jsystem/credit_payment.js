/**
 * Created by Alejandro on 18/7/2017.
 */
$(document).ready(function () {
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
});
$(function () {

    /*Cuando da clic guardar de modal nueva marca se activa esta accion*/
    $('#frm_register_payment').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var monto_efectivo = $("#monto_efectivo").val();
        var total_payment = $("#total_payment").val();

        if (monto_efectivo > 0 && total_payment > 0 && monto_efectivo >= total_payment) {
            ajaxStart("Se esta registrando el pago esper por favor...");
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
                        // update_data_table($('#list_credit_payment'));
                        var id = respuesta.payment_credit_sale_id;
                        var url_sale = respuesta.url_impression;
                        print(id, url_sale);
                        
                        setTimeout(function () {
                            location.href = site_url + "credit_payment/";
                        }, 1500);
                    } else if(respuesta.cash === true){
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
                            $.redirect(siteurl("credit_payment"));
                        });
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
                    // **alert('error; ' + eval(error));**
                    swal('Error', 'Error al registrar los datos.', 'error');
                }
            });
       }else {
        swal(
            'Error',
            'Datos incorrectos, El pago debe ser mayor 0, tambien debe elegir una forma de pago',
            'error'
        )
    }
    });

});

function calcular_total_venta_forma_pago(){
    var amount_cheque= parseFloat($("#monto_cheque").val());
    var amount_tarjeta = parseFloat($("#monto_tarjeta").val());
    var amount_bs= parseFloat($("#monto_bs").val());
    var amount_sus = parseFloat($("#monto_sus").val());
    var efectivo_sus = amount_sus*parseFloat(change_type.monto_cambio_venta);

    var monto_venta = parseFloat($("#total_payment").val());

    var total_efectivo=amount_cheque+amount_tarjeta+amount_bs+efectivo_sus;
    

    $("#monto_efectivo").val(total_efectivo.toFixed(2));
    var total_cambio=total_efectivo-monto_venta;

    $("#monto_cambio").val(total_cambio.toFixed(2));

    // if(monto_venta > total_efectivo){
    //     $("#monto_cambio").val(0);
    // }
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
    $.redirect(siteurl('credit_payment/new_payment'), {id: id}, 'POST', '_self');
}
function payment_specific(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    $.redirect(siteurl('credit_payment/view_payment'), {id: id}, 'POST', '_self');
}
/*funcion eliminar marca
function delete_register(element) {
	delete_register_commom(element, 'group/disable');
}
*/
function delete_register_type(element) {
    delete_register_commom(element, 'Credit_payment/disable_credit_payment');
}

/////////////ELIMINAR/////////////////
/*function delete_register(element) {
	var table = $(element).closest('table').DataTable();
	var current_row = $(element).parents('tr');
	if (current_row.hasClass('child')) {
		current_row = current_row.prev();
	}
	var data = table.row(current_row).data();

	var credit_payment_id = data['id'];
	swal({
			title: "Esta seguro que desea eliminar este registro?",
			text: "El estado del registro cambiara",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Si, eliminar registro!",
			cancelButtonText: "No, cancelar",
			closeOnConfirm: false,
			closeOnCancel: true
		},
		function (isConfirm) {
			if (isConfirm) {
				$.post(site_url + 'credit_payment/disable_credit_payment', {credit_payment_id: credit_payment_id})
					.done(function (response) {
						ajaxStop();
						if (response) {
							swal("Eliminado!", "El registro ha sido eliminado.", "success");
							table.ajax.reload();
						} else {
							swal("Error", "Problemas al eliminar", "error");
						}
					})
					.fail(function(xhr, exception, errorThrown) {
						ajaxStop();
						// swal("Error", "Error al eliminar", "error");
						let message = xhr.responseText.split('workcorp')[0];
						console.log("jqXHR: ",  message);
						console.log("textStatus: ", exception);
						console.log("errorThrown: ", errorThrown);
						swal('Ocurrió un problema', message, 'error');
					});
			}
		});
}*/
/////////////////////////////////////
function modify_total_payment() {
    var filas = $("#list_credit_sale > tbody > tr").length;
    var sumandoMontos = 0;

    $('input[name ="payment[]"]').each(function (indice, elemento) {
        var monto =$(elemento).val();
        if (monto==''){
            monto=0;
        }
        sumandoMontos=sumandoMontos+ parseFloat(monto);
    });

    $('#total_payment').val(sumandoMontos.toFixed(2));

}

function modify_payment(contador) {
    var payment=$('#payment'+contador).val();
    var residue=parseFloat($('#residue'+contador).val());
    if (payment==''){
        payment=0;
    }
    payment=parseFloat(payment);
    if (residue>=payment){
        modify_total_payment();
    } else{
        swal('Error', 'El monto de pago no debe ser mayor al saldo del credito.', 'error');
        var payment=$('#payment'+contador).val('');
        modify_total_payment();
    }
}

function put_empty(contador) {
    $('#payment'+contador).val('');
    modify_total_payment();
}

/*carga la lista de las marcas al datatable*/
function get_payment_customer_list() {
    var tabla = $('#list_payment_customer').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            "url": siteurl('credit_payment/get_payment_customer_list'),
            "type": 'post',
            "data":{customer_id:$('#customer_id').val()}
        },
        columns: [
            {data: 'id'},
            {data: 'nro_transaccion_pago', class: 'text-center'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'fecha_modificacion', class: 'text-center'},
            {data: 'monto_total', class: 'text-right'},
            {data: 'observacion', class: 'text-left'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false,
            orderable: false
        }, {
            target: 1,
            searchable: false,
            orderable: false
        }, {
            targets: 6,
            orderable: false,
            render: function (data, type, row) {
                var botones = '<div class="btn-group">' +
                    '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
                    'OPCIONES <span class="caret"></span> ' +
                    '</button><ul class="dropdown-menu">';
                botones = botones + '<li><a onclick="payment_specific(this)"><i class="fa fa-eye"></i> Ver Pago</a></li> ' +
                                    '<li><a onclick="print_form(this)"><i class="fa fa-eye"></i> imprimir</a></li> ' +
                    '</ul> ' +
                    '</div>';
                return botones;
            }
        }],
        "order": [[ 1, "DESC" ]]
    });
    tabla.ajax.reload();
}

function get_credit_payment_list() {
    var tabla = $('#list_credit_payment').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'cache': false,
        'destroy': true,
        'ajax': {
            "url": siteurl('credit_payment/get_credit_payment_list'),
            "type": "post",
        },
        responsive: false,
        'columns': [
            {data: 'id'},
            {data: 'nombre_sucursal', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'fecha_vencimiento', class: 'text-center'},
            {data: 'monto_total', class: 'text-right'},
            {data: 'monto_credito', class: 'text-right'},
            {data: 'monto_saldo', class: 'text-right'},
            {data: 'opciones', class: 'text-center'}
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false
            },
            {
                targets: 1,
                searchable: false,
                orderable: false
            },
            {
                targets: 7,       
                orderable: false,
                render: function (data, type, row) {

                    var botones = '<div class="btn-group">' +
                        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
                        'OPCIONES <span class="caret"></span> ' +
                        '</button><ul class="dropdown-menu">';
                    botones = botones + '<li><a onclick="new_register_form(this)"><i class="fa fa-plus"></i> Nuevo Pago</a></li>' +
                        '<li><a onclick="payment_customer(this)"><i class="fa fa-eye"></i> Ver Pagos</a></li> ' +
                        '</ul> ' +
                        '</div>';
                    return botones;
                }

            }
        ],
        "order": [[ 2, "ASC" ]]
    });
    tabla.ajax.reload();
}


function payment_customer(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('credit_payment/payment_customer');
    $.redirect(url, {id: data['id']}, 'POST', '_self');
}

/* Carga la lista de pagos*/
function get_payments_list() {
    var tabla = $('#list_payments').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            "url": siteurl('credit_payment/get_payment_list'),
            "type": 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'nro_transaccion_pago', class: 'text-center'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'fecha_modificacion', class: 'text-center'},
            {data: 'monto_total', class: 'text-right'},
            {data: 'observacion', class: 'text-left'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false,
            orderable: false
        }, {
            target: 1,
            searchable: false,
            orderable: false
        }, {
            targets: 6,
            orderable: false,
            render: function (data, type, row) {
				if(row.estado !=0){
                var botones = '<div class="btn-group">' +
                    '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
                    'OPCIONES <span class="caret"></span> ' +
                    '</button><ul class="dropdown-menu">';
					botones = botones + '<li><a onclick="payment_specific(this)"><i class="fa fa-eye"></i> Ver Pago</a></li>' +
					'<li><a data-toggle="modal" onclick="delete_register_type(this)"><i class="fa fa-times"></i> Eliminar</a></li>'
                    '</ul> ' +
                    '</div>';
                return botones;
				}else {
					return '<label>No tiene Opciones</label>';
				}
            }
        }],
        "order": [[ 1, "DESC" ]]
    });
    tabla.ajax.reload();
}


function print_form(element) {
    var table = $(element).closest('table').DataTable();
    var current_row = $(element).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('print_sale/print_payment_credit_sale');
    $.redirect(url, {id: data['id']}, 'POST', '_blank');
}
