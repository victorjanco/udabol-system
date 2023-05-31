/**
 * User: Ing. Ariel Alejandro Gomez Chavez
 * Github: https://github.com/ariel-ssj
 * Date: 1/10/2019 10:33
 */
$(function () {
	$('#btn_new_reception_payment').click(function () {
		get_sum_reception_payments();
		$('#frm_new_reception_payment')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
		$(".modal-error").empty();
		$('#modal_new_reception_payment').modal({
			show: true,
			backdrop: 'static'
		});

		setTimeout(function () {
			calculate_total_reception();
		}, 500);
	});

	$('#reception_discount').click(function () {
		open_verify();
	});


	$('#frm_new_reception_payment').submit(function (event) {
		event.preventDefault();
		var form = $(this);

		if ($('#reception_discount').val() == '') {
			$('#reception_discount').val(0);
		}

		if ($('#reception_balance').val() < 0){
			swal('Saldo en 0', 'No puede registrar nuevos pagos, el saldo se encuentra en 0.', 'error');
		} else {
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
						$('#close_modal_new_reception_payment').click();

						update_data_table($('#list_reception_payment'));
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
		}
	});
});

function print_form(element) {
	var recepcion_id = parseInt($('#id_reception').val());
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var id = rowData['id'];
	$.redirect(siteurl('reception_payment/print_payment'), {payment_id: id, reception_id: recepcion_id}, 'POST', '_blank');
}

function print_payments(element) {
	var recepcion_id = parseInt($('#id_reception').val());
	$.redirect(siteurl('reception_payment/print_payment_history'), {reception_id: recepcion_id}, 'POST', '_blank');
}

function get_sum_reception_payments() {
	var recepcion_id = $('#id_reception').val();
	$.ajax({
		url: siteurl('reception_payment/get_sum_reception_payments'),
		type: 'post',
		data: {id: recepcion_id},
		success: function (response) {
			var data = JSON.parse(response);
			var total_discount=data.total_descuentos;
			var total_payments=data.total_pagados;
			$('#reception_discount_old').val(total_discount);
			$('#reception_payment_old').val(total_payments);
		}
	});

}

function calculate_total_reception() {

	var reception_total = $('#reception_total').val();

	var reception_discount = $('#reception_discount').val();
	if (reception_discount == '') {
		reception_discount = 0;
	}
	var reception_discount_old = $('#reception_discount_old').val();
	if (reception_discount_old == '') {
		reception_discount_old = 0;
	}
	reception_discount = parseFloat(reception_discount) + parseFloat(reception_discount_old);
	var reception_total_payment = reception_total - reception_discount;
	$('#reception_total_payment').val(reception_total_payment.toFixed(2));

	var total_payment_old = $('#reception_payment_old').val();
	if (total_payment_old == '') {
		total_payment_old = 0;
	}

	var total_payment = $('#reception_payment').val();
	if (total_payment == '') {
		total_payment = 0;
	}

	var total_balance = parseFloat(reception_total_payment) - parseFloat(total_payment) - parseFloat(total_payment_old);
	$('#reception_balance').val(total_balance.toFixed(2));
}

function delete_register(element) {
	delete_register_commom(element, 'reception_payment/disable');
}
function get_reception_payment_list() {
	var tabla = $('#list_reception_payment').DataTable({
		paging: true,
		info: true,
		filter: true,
		stateSave: true,
		processing: true,
		serverSide: true,
		ajax: {
			url: siteurl('reception_payment/get_reception_payment_list'),
			type: 'post',
			data: {id_reception: $('#id_reception').val()}
		},
		columns: [
			{data: 'codigo_recepcion', class: 'text-center'},
			{data: 'nombre_usuario', class: 'text-center'},
			{data: 'fecha_modificacion', class: 'text-center'},
			{data: 'observacion', class: 'text-center'},
			{data: 'pago'},
			{data: 'estado', class: 'text-center'},
			{data: 'opciones', class: 'text-center'}
		],
		columnDefs: [{
			targets: 5,
			render: function (data) {
				return state_crud(data);
			}
		}, {
			targets: 6,
			orderable: false,
			render: function (data, type, row) {
				if (row.estado != 0) {
					return load_buttons_crud(20, 'modal');
				} else {
					return '<label>No tiene Opciones</label>';
				}
			}
		}],
		/*responsive: true,
         pagingType: "full_numbers",
         select: false*/
	});
}
