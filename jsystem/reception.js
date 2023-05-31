/**
 * Created by Ariel on 27/07/2017.
 */
$(document).ready(function () {
	get_reception_list();
	get_reception_list_delivered();
	/*listado de entregados*/
	delete_row_table();
	load_devices_customer();
	load_devices();
	load_colors();
	load_brands();
	// load_models();
	load_failures();
	load_solutions();
	load_services_type();
	load_references();
	// enable_webcam();
	get_warehouse_for_type_warranty();

	// $('#btn_generate_note_sale').click(function (event) {
	// 	event.preventDefault();
	// 	var order_work_id = $('#order_work_id').val();

	// 	var reception_discount = $('#sale_discount').val();
	// 	if (reception_discount == '') {
	// 		reception_discount = 0;
	// 	}

	// 	var reception_discount_old = $('#reception_discount_old').val();
	// 	if (reception_discount_old == '') {
	// 		reception_discount_old = 0;
	// 	}
	// 	reception_discount = parseFloat(reception_discount) + parseFloat(reception_discount_old);

	// 	var reception_id = $('#reception_id').val();
	// 	ajaxStart('Registrando datos...');
	// 	$.ajax({
	// 		url: siteurl('sale/generate_sale_by_order_work_id'),
	// 		type: 'post',
	// 		data: {
	// 			order_work_id: order_work_id,
	// 			reception_id: reception_id,
	// 			code_reception: $('#code_reception').val(),
	// 			reception_total: $('#reception_total').val(),
	// 			reception_discount: $('#reception_discount').val(),
	// 			reception_total_payment: $('#reception_total_payment').val(),
	// 			reception_payment: $('#reception_payment').val(),
	// 			reception_balance: $('#reception_balance').val(),
	// 			reception_payment_observation: $('#reception_payment_observation').val(),
	// 			sale_discount: reception_discount,
	// 			id_reception: reception_id
	// 		},
	// 		dataType: 'json',
	// 		success: function (response) {
	// 			ajaxStop();
	// 			alert(response.cash);
	// 			if (response.success === true) {
	// 				if (response.sale === true) {
	// 					var id = response.sale_id;
	// 					var url_sale = response.url_impression;
	// 					print(id, url_sale);
	// 					setTimeout(function () {
	// 						location.href = site_url + "reception";
	// 					}, 1500);
	// 				} else {
	// 					swal('DATOS CORRECTOS', response.messages, 'success');
	// 					update_data_table($("#reception_list"));
	// 					$('#close_modal_state').click();
	// 				}
	// 			}else if(response.cash === true){
    //                 swal({
    //                     title: "Caja Cerrada",
    //                     text: "Caja cerrada, aperture nuevamente la caja",
    //                     type: "warning",
    //                     showCancelButton: false,
    //                     confirmButtonColor: "#DD6B55",
    //                     confirmButtonText: "Ok!",
    //                     closeOnConfirm: false,
    //                     closeOnCancel: true
    //                 },
    //                 function (isConfirm) {
    //                     $.redirect(siteurl("reception"));
    //                 });
    //             }else if (response.login === true) {
	// 				login_session();
	// 			} else {
	// 				swal('DATOS INCORRECTOS', response.messages, 'error');
	// 			}
	// 		},
	// 		fail: function () {
	// 			alert('errro');
	// 		}
	// 	});

	// });

	/*$('#frm_generate_sale_for_reception_old').submit(function (event) {
     event.preventDefault();
     console.log('esta funcionando');
     var order_work_id = $('#order_work_id').val();
     var discount = $('#sale_discount').val();
     var pago = $('#pago').val();
     if (!discount) {
     discount = 0;
     }
     var reception_id = $('#reception_id').val();
     ajaxStart('Registrando datos...');
     $.ajax({
     url: siteurl('sale/generate_sale_by_order_work_id'),
     type: 'post',
     data: {
     order_work_id: order_work_id,
     reception_id: reception_id,
     discount: discount,
     pago: pago,
     id_reception: reception_id
     },
     dataType: 'json',
     success: function (response) {
     ajaxStop();
     if (response.success === true) {
     if (response.sale === true) {
     var id = response.sale_id;
     var url_sale = response.url_impression;
     print(id, url_sale);
     setTimeout(function () {
     location.href = site_url + "reception";
     }, 1500);
     } else {
     swal('DATOS CORRECTOS', response.messages, 'success');
     update_data_table($("#reception_list"));
     $('#close_modal_state').click();
     }
     } else if (response.login === true) {
     login_session();
     } else {
     swal('DATOS INCORRECTOS', response.messages, 'error');
     }
     },
     fail: function () {
     alert('errro');
     }
     });
     });*/

	 $("#brand").on('change', function () {
        $.ajax({
            url: siteurl('model_reception/get_model_reception_by_brand'),
            type: 'post',
            data: {marca_id: $("#brand").val()},
            dataType: 'json',
            success: function (response) {
                $('#model').empty();
                $.each(response, function (i, item) {
                    $('#model').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                });
            }
        })
    });
	$('#failure_select').change(function () {
		var falla = String($('#failure_select').val());
		if (falla.length > 0) {
			var array_fallas = falla.split(",");
			var verificar = 0;
			$.each(array_fallas, function (i, item) {
				if (array_fallas[i] != null) {
					if (array_fallas[i] == 72) {
						verificar = 1;
					} else {
						verificar = 0;
					}
				} else {
					verificar = 0;
				}
			});

			if (verificar == 1) {
				$('#div_color').show();
			} else {
				$('#div_color').hide();
			}
		}
	});

	// Pasar los datos buscados al formulario
	$('#btn_confirm_customer').click(function () {
		var id = $('.search_id_customer').val();
		var ci = $('.search_ci').val();
		var name = $('.search_name').val();
		var telf1 = $('.search_telf1').val();
		var telf2 = $('.search_telf2').val();
		$('#id_customer').val(id);
		$('#ci_customer').val(ci);
		$('#name_customer').val(name);
		$('#telefono1_customer').val(telf1);
		$('#telefono2_customer').val(telf2);

		load_devices_customer(id);

		$('#modal_search_customer').modal('hide');
	});

	$('#btn_new_item').click(function () {
		$("#brand_product").val('').trigger('change');
		$("#model_product").val('').trigger('change');
	});

	// Metodo que optiene los dispositivos y sus modelos concatenados
	// $('#brand').change(function () {
	// 	$.ajax({
	// 		url: siteurl('product/get_producto_and_model_by_brand'),
	// 		data: 'id=' + $('#brand').val(),
	// 		type: 'post',
	// 		success: function (data) {
	// 			$('#model').empty();
	// 			var datos = JSON.parse(data);
	// 			$.each(datos, function (i, item) {
	// 				$('#model').append('<option value="' + item.id + '">' + item.modelo + '</option>');
	// 			});

	// 			get_model_code();
	// 		}
	// 	});

	// });

	// Metodo que optiene los dispositivos y sus modelos concatenados
	// $('#model').change(function () {
	// 	get_model_code();
	// });
	
	$('#open_discount').click(function () {
		open_verify();
	});

	$('#reception_discount').click(function () {
		// open_verify();
	});

	// Evento que obtiene los servicios por un tipo de servicio seleccionado
	var object_service = null;
	$('#service_type').change(function () {
		$.ajax({
			url: siteurl('service/get_service_by_type'),
			data: 'id=' + $('#service_type').val(),
			type: 'post',
			success: function (data) {
				$('#service').empty();
				$('#service').append('<option value="0">Seleccione una opcion</option>');
				var data = JSON.parse(data);
				object_service = data;
				$.each(data, function (i, item) {
					$('#service').append('<option value="' + item.id + '">' + item.nombre + '</option>');
				});
			}
		});
	});

	// Evento para obtener el precio elegido de un servicio
	$('#service').change(function () {
		var price = 0;
		var data = object_service;
		console.log(object_service);
		var id = $('#service').val();
		if (id != 0) {
			$.each(data, function (i, item) {
				if (item.id === id) {
					$('#price').val(item.precio);
					$('#price_cost').val(item.precio);
					return true;
				}
			})
		} else {
			$('#price').val('0.00');
		}
	});

	$('#close_modal_generate_note_sale').click(function (event) {
		update_data_table($("#reception_list"));
		$('#close_modal_state').click();
		setTimeout(function () {
			location.href = site_url + "reception";
		}, 100);
	});


	//Registro de un dispositivo
	$('#frm_register_devices').submit(function (event) {
		event.preventDefault();

		if ($('#id_customer').val() === '0' || $('#id_customer').val() === '') {
			swal('Debe seleccionar al menos un cliente', '', 'error');
			return true;
		}
		var form = $(this);
		var data = $(form).serialize();
		data = data + '&id_customer=' + $('#id_customer').val();
		var id_form = $(this).attr('id');
		var data_modal = $(this).parents('div');
		var id_modal = data_modal[2].id;
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: data,
			dataType: 'json',
			success: function (respuesta) {
				if (respuesta.success === true) {
					$('.abm-error').remove();
					swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
					$('#' + id_form)[0].reset();
					$('#' + id_modal).modal('hide');
					load_devices_customer($('#id_customer').val());
				} else {
					$('.error').remove();
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

	//Registro de un dispositivo
	$('#frm_register_reference').submit(function (event) {
		event.preventDefault();
		var form = $(this);
		$('.modal-error').remove();
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: $(form).serialize(),
			dataType: 'json',
			success: function (respuesta) {
				console.log(respuesta);
				if (respuesta.success === true) {
					$('.modal-error').remove();
					swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
					$('.close_modal').click();

					load_references();
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

	$('#add_reference').click(function () {
		$('#frm_register_reference')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
		$(".modal-error").empty();
		$('#register_reference').modal({
			show: true,
			backdrop: 'static'
		});
	});

	$('#frm_register_recepcion').submit(function (event) {
		event.preventDefault();

		if ($('#reception_discount').val() == '') {
			$('#reception_discount').val(0);
		}

		if ($('#reception_payment').val() == '') {
			$('#reception_payment').val(0);
		}

		var data = {};
		var form_data = $(this).serializeArray();
		$(form_data).each(function (i, field) {
			data[field.name] = field.value;
		});

		data['failures'] = set_failures();// Obtenemos las fallas seleccionadas
		data['solutions'] = set_solutions();// Obtenemos las soluciones seleccionadas
		data['detail_table'] = get_detail();// Obtenemos los servicios seleccionados
		data['detail_table_product'] = get_detail_product();// Obtenemos los servicios seleccionados
		ajaxStart('Registrando datos...');
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: data,
			dataType: 'json',
			success: function (response) {
				ajaxStop();
				if (response.success === true) {
					// if (response.receipt_payment_id != '') {
					// 	$.redirect(siteurl('reception_payment/print_payment'), {
					// 		payment_id: response.receipt_payment_id,
					// 		reception_id: response.messages
					// 	}, 'POST', '_blank');
					// }

					var id = response.messages;
					
					// setTimeout(function () {
					// 	// $.redirect(siteurl('reception/register_gallery'), {id: id}, 'POST', '_self');
					// 	print_order_wwork(id);
					// 	location.href = site_url + "reception";
					// }, 1500)

					swal({
                        title: "Registrado!",
                        text: "Recepcion Registrada",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        print_order_wwork(id);
						location.href = site_url + "reception";
                    });

				} else if (response.login === true) {
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
			},
			error: function (error) {
                ajaxStop();
                console.log(error.responseText);
                // **alert('error; ' + eval(error));**
                swal('Error', 'Error al registrar los datos.', 'error');
            }
		});
	});

	//////////////////////////////////////
function print_order_wwork(reception_id){
	var page = site_url + "reports/imprimir_orden_trabajo";
	var params = {
		id: reception_id
	};
	var body = document.body;
	form = document.createElement('form');
	form.method = 'POST';
	form.action = page;
	form.name = 'jsform';
	form.target = '_blank';
	for (index in params) {
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = index;
		input.id = index;
		input.value = params[index];
		form.appendChild(input);
	}
	body.appendChild(form);
	form.submit();
	return false;
}
/////////////////////////////////////
	/*Cuando da clic guardar en el formulario nuevo cliente*/
	$('#frm_add_customer').submit(function (event) {
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
					$('#close_modal_new_customer').click();
					$('#frm_add_customer')[0].reset();
					var customer_id = respuesta.customer.id;
					var customer_name = respuesta.customer.nombre;
					var customer_ci = respuesta.customer.ci;
					var customer_nit = respuesta.customer.nit;
					var customer_name_bill = respuesta.customer.nombre_factura;
					$('#nombre_factura').val(customer_name_bill);
					$('#nit').val(customer_nit);
					$('#id_customer').val(customer_id);

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

	/*Metodo autocompletado de busqueda por CI del cliente*/
	$('#ci_customer').autocomplete({
		source: function (request, response) {
			$.ajax({
				url: siteurl('customer/get_data_customer'),
				dataType: "json",
				data: {
					name_startsWith: request.term,
					type: 'ci'
				},
				success: function (data) {
					response($.map(data, function (item, nombre) {
						var data = nombre.split(' - ');
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
			var Date = (ui.item.id);
			var elem = Date.split('/');
			if (elem.length > 1) {
				$('.search_telf1').val(elem[3]);
				// $('.search_telf2').val(elem[4]);
				$('.search_nit').val(elem[2]);
				$('.search_name').val(elem[0]);
				$('.search_id_customer').val(elem[1]);
				$('#name_customer').val(elem[0]);
				$('#ci_customer').val(ui.item.value);

				load_devices_customer(elem[1]);
			} else {
				$('.search_telf1').val('');
				$('.search_telf2').val('');
				$('#ci_customer').val('');
				$('.search_name').val('');
				$('.search_id_customer').val('');
			}
		}
	});

	/*Metodo autocompletado de busqueda por NOMBRE del cliente*/
	$('#name_customer').autocomplete({
		source: function (request, response) {
			$.ajax({
				url: siteurl('customer/get_data_customer'),
				dataType: "json",
				data: {
					name_startsWith: request.term,
					type: 'name'
				},
				success: function (data) {
					response($.map(data, function (item, nombre) {
						var data = nombre.split(' - ');
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
			var Date = (ui.item.id);
			var elem = Date.split('/');
			if (elem.length > 1) {
				$('.search_telf1').val(elem[3]);
				// $('.search_telf2').val(elem[4]);
				$('#ci_customer').val(elem[2]);
				$('.search_name').val(elem[0]);
				$('.search_id_customer').val(elem[1]);
				// $('.name_customer').val('elem[0]');
				// console.log(elem);
				// $('#ci_customer').val(ui.item.value);

				load_devices_customer(elem[1]);
			} else {
				$('.search_telf1').val('');
				$('.search_telf2').val('');
				$('#ci_customer').val('');
				$('.search_name').val('');
				$('.search_id_customer').val('');
			}
		}
	});

	/* REDIRECCIONANDO PARA LA IMPRESION    */
	$('#print_order_work').click(function (e) {
		e.preventDefault();
		var page = site_url + "reports/imprimir_orden_trabajo";
		var params = {
			id: $("#id_reception").val()
		};
		var body = document.body;
		form = document.createElement('form');
		form.method = 'POST';
		form.action = page;
		form.name = 'jsform';
		form.target = '_blank';
		for (index in params) {
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = index;
			input.id = index;
			input.value = params[index];
			form.appendChild(input);
		}
		body.appendChild(form);
		form.submit();
		return false;
	});

	/* ACTUALIZAR FALLAS - SERVICIOS */
	$("#btn_refresh_select").on('click', function () {
		ajaxStart('Obteniendo datos...');
		load_failures();
		load_solutions();
		load_services_type();
		ajaxStop()
	});

	$("#btn_refresh_select_brand").on('click', function () {
		ajaxStart('Obteniendo datos...');
		load_brands();

		ajaxStop()
	});
	/* Evento de cambio de marca de producto*/
	$("#brand_product").change(function () {
		//$("#price_work").val("");
		if ($("#brand_product").val() !== "") {
			$.ajax({
				url: siteurl('model/get_model_by_brand'),
				data: {marca_id: $("#brand_product").val()},
				type: 'post',
				success: function (data) {
					$('#model_product').empty();
					$('#model_product').append('<option value="">Seleccione una opcion</option>');
					var data = JSON.parse(data);
					object_service = data;
					$.each(data, function (i, item) {
						$('#model_product').append('<option value="' + item.id + '">' + item.nombre + '</option>');
					});
				}
			});
		} else {
			$('#model_product').empty();
		}
	});


	/*autocompletado para la busqueda por el nombre del producto apartir de sucursal_id, almacen_id y tipo_producto_id*/
	$('#producto_order_work').autocomplete({
		source: function (request, response) {
			var model_product = $('#model_product').val();
			$.ajax({
				url: siteurl('inventory/get_product_autocomplete'),
				dataType: "json",
				type: 'post',
				data: {
					name_startsWith: request.term,
					type: 'name_product_expertise',
					model_product: model_product,
					warehouse_id: $('#warehouse_id').val()
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
			if ($('#producto_order_work').val() !== "") {
				var data_product = (ui.item.id);
				var elem = data_product.split('/');
				$('#price_product').val(elem[2]);
				$('#price_sale').val(parseFloat(elem[7]).toFixed(2));
				$('#quantity_product').focus();
				$("#product_selected").val(elem[0])
			} else {
				$('#producto_order_work').focus();
			}

		}
	});

	$('#reason_producto_order_work').autocomplete({
		source: function (request, response) {
			$.ajax({
				url: siteurl('inventory/get_product_autocomplete'),
				dataType: "json",
				type: 'post',
				data: {
					name_startsWith: request.term,
					type: 'name_product_reception',
					warehouse_id: $('#warehouse_id').val()
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
			if ($('#reason_producto_order_work').val() !== "") {
				var data_product = (ui.item.id);
				var elem = data_product.split('/');
				$("#reason_product_selected").val(elem[0])
			} else {
				$('#reason_producto_order_work').focus();
			}
		}
	});

	/*Evento Submit de agregar servicio */
	$('#frm_add_row_product').submit(function (event) {
		event.preventDefault();
		add_row_product();
	});


	$('#frm_receptiom_edit_states').submit(function (event) {
		event.preventDefault();
	});

	// FILTROS DE BUSQUEDA RECEPCION
	/*  Evento change de filtro por MARCA  */
	$("#filter_reception_brand").on('change', function () {
		get_reception_list();
	});
	/*  Evento change de filtro por ESTADO */
	$("#filter_reception_state").on('change', function () {
		get_reception_list();
	});
	/*  Evento change de filtro por DATOS (DIAS)  */
	$("#filter_date").on('change', function () {
		get_reception_list();
	});

	/*  Evento change de filtro por FECHA INICIO */
	$("#filter_date_start_reception").on('change', function () {
		console.log($("#filter_date_start_reception").val());
		get_reception_list();
	});

	/*  Evento change de filtro por FECHA FIN*/
	$("#filter_date_end_reception").on('change', function () {
		console.log($("#filter_date_end_reception").val());
		get_reception_list();
	});

	/*  Evento change de filtro por CODIGO RECEPCION  */
	$("#filter_reception_code").keyup(function () {
		get_reception_list();
	});

	// FILTROS DE BUSQUEDA RECEPCION ENTREGADOS
	/*  Evento change de filtro por FECHA INICIO */
	$("#filter_date_start_delivered").on('change', function () {
		console.log($("#filter_date_start_delivered").val());
		get_reception_list_delivered();
	});

	/*  Evento change de filtro por FECHA FIN*/
	$("#filter_date_end_delivered").on('change', function () {
		console.log($("#filter_date_end_delivered").val());
		get_reception_list_delivered();
	});

	$('#frm_new_reception_reason').submit(function (event) {
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
					var NO_APROBADO = 9;
					var reception_id = $('#reception_reason_id').val();

					$('.modal-error').remove();
					swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
					$('#close_modal_new_reception_reason').click();

					update_reception_states(reception_id, NO_APROBADO);
					update_data_table($('#reception_list'));
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

	// Evento para seleccionar GARANTIA
	$("#warranty_select").on('change', function () {
		get_warehouse_for_type_warranty();
	});

});

// function get_model_code() {
// 	$.ajax({
// 		url: siteurl('model/get_model_by_id'),
// 		data: 'id=' + $('#model').val(),
// 		type: 'post',
// 		success: function (data) {
// 			var datos = JSON.parse(data);
// 			$('#model_code').val(datos.codigo);
// 		}
// 	});
// }

function add_row_product() {
	var frm = $('#frm_add_row_product').serialize();
	ajaxStart('Guardando registros, por favor espere');
	$.ajax({
		url: site_url + 'order_work/add_row_product',
		data: frm,
		type: 'post',
		dataType: 'json',
		success: function (response) {
			ajaxStop();
			$('.modal-error').remove();
			if (response.success === true) {
				$('#table_services_product tbody').append(response.data);
				delete_row_product();
				$("#frm_add_row_product")[0].reset();
				$(".close-modal").click();
				calculate_total_amounts_product();
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

function delete_row_product() {
	$("a.elimina").click(function () {
		$(this).parents("tr").fadeOut("normal", function () {
			$(this).remove();
			calculate_total_amounts_product();
		});
	});
}

function calculate_total_amounts_product() {

	var total_amount = 0;
	var html_foot_string = "";
	$('#table_services_product tbody tr').each(function (index, value) {
		total_amount = total_amount + parseFloat(value.dataset.price);
	});

	$("#total_amount_product").val(total_amount);

	html_foot_string = '<tr> ' +
		'<td style="text-align: right" colspan="4"><b>Total Bs.:</b></td> ' +
		'<td style="padding: 10px; text-align: right"><b>' + total_amount.toFixed(2) + '</b></td> ' +
		'</tr>';

	$('#table_services_product tfoot').empty();
	$('#table_services_product tfoot').append(html_foot_string);
	calculate_total_reception();
}

function get_reception_list() {
	var tabla = $('#reception_list').DataTable({
		'paging': true,
		'info': true,
		'filter': true,
		'stateSave': true,
		'processing': true,
		'serverSide': true,
		'cache': false,
		'destroy': true,
		'ajax': {
			"url": siteurl('reception/get_receptions_list'),
			"type": "post",
			"data": {
				filter_date: $("#filter_date").val(),
				filter_reception_brand: $("#filter_reception_brand").val(),
				filter_reception_state: $("#filter_reception_state").val(),
				filter_date_start_reception: $("#filter_date_start_reception").val(),
				filter_date_end_reception: $("#filter_date_end_reception").val(),
				filter_reception_code: $("#filter_reception_code").val()
			}
		},
		responsive: false,
		'columns': [
			{data: 'id'},
			{data: 'codigo_recepcion', class: 'text-center'},
			{data: 'nombre'},
			{data: 'nombre_marca'},
			{data: 'nombre_modelo'},
			{data: 'imei', class: 'text-center'},
			{data: 'fecha_registro'},
			{data: 'monto_total', class: 'text-center'},
			// {data: 'garantia', class: 'text-center'},
			{data: 'estado_trabajo', class: 'text-center'},
			{data: 'recepcion_usuario', class: 'text-center'},
			// {data: 'perito_usuario', class: 'text-center'},
			{data: 'opciones', class: 'text-center'}
		],
		"columnDefs": [
			{
				targets: 0,
				visible: false,
				searchable: false
			},
			// {
			// 	targets: 1,
			// 	searchable: true,
			// 	orderable: true,
			// 	render: function (data, type, row) {
			// 		if (row.horas <= 24) {
			// 			return '<div style="background-color: #00CC00">' + row.codigo_recepcion + '</div>';
			// 		} else if (row.horas > 24 && row.horas <= 48) {
			// 			return '<div style="background-color: yellow">' + row.codigo_recepcion + ' </div>';
			// 		} else if (row.horas > 48 && row.horas <= 72) {
			// 			return '<div style="background-color: #dd4b39">' + row.codigo_recepcion + '</div>';
			// 		} else {
			// 			return '<div style="background-color: #999999">' + row.codigo_recepcion + '</div>';
			// 		}
			// 	}
			// },
			{
				targets: 3,
				visible: true,
				searchable: true,
				orderable: true
			},
			{
				targets: 4,
				visible: true,
				searchable: true,
				orderable: true
			},
			{
				targets: 5,
				searchable: true,
				orderable: true
			},
			{
				targets: 8,
				orderable: true
			}, {
				targets: 9,
				visible: true,
				orderable: false,
				searchable: false
			}, 
			{
				targets: 10,
				orderable: false,
				render: function (data, type, row) {
					if (row.estado === '1') {
						return buttons_reception(row.galeria, row.estado_trabajo);
					} else {
						return 'No tiene opciones';
					}
				}
			}
		]
	});
	tabla.ajax.reload();
	/*$('#reception_list').fnPageChange(0);
     $('#reception_list').fnReloadAjax();
     return false;*/
}

function get_reception_list_delivered() {

	var tabla = $('#list_delivered').DataTable({
		'paging': true,
		'info': true,
		'filter': true,
		'stateSave': true,
		'processing': true,
		'serverSide': true,
		'cache': false,
		'destroy': true,
		'ajax': {
			"url": siteurl('reception/get_receptions_list_delivered'),
			"type": "post",
			"data": {
				filter_date_start_delivered: $("#filter_date_start_delivered").val(),
				filter_date_end_delivered: $("#filter_date_end_delivered").val()
			}
		},
		responsive: false,
		'columns': [
			{data: 'id'},
			{data: 'codigo_recepcion', class: 'text-center'},
			{data: 'nombre'},
			{data: 'nombre_marca'},
			{data: 'nombre_modelo'},
			{data: 'imei', class: 'text-center'},
			{data: 'fecha_registro'},
			{data: 'fecha_entrega'},
			{data: 'monto_total', class: 'text-center'},
			{data: 'estado_trabajo', class: 'text-center'},
			{data: 'perito_usuario', class: 'text-center'},
			{data: 'opciones', class: 'text-center'}
		],
		"columnDefs": [
			{
				targets: 0,
				visible: false,
				searchable: false
			}, {
				targets: 1,
				searchable: true,
				orderable: true
			}, {
				targets: 3,
				visible: true,
				searchable: true,
				orderable: true
			}, {
				targets: 4,
				visible: true,
				searchable: true,
				orderable: true
			}, {
				targets: 5,
				searchable: true,
				orderable: true
			}, {
				targets: 9,
				orderable: true
			}, {
				targets: 10,
				orderable: false,
				searchable: false
			}, {
				targets: 11,
				orderable: false,
				render: function (data, type, row) {
					if (row.estado === '1') {
						return buttons_reception_delivered(row.galeria);
					} else {
						return 'No tiene opciones';
					}
				}
			}
		]
	});
	tabla.ajax.reload();
}

function buttons_reception(has_gallery, state_order_work) {
	var buttons = '';
	buttons = '<div class="btn-group">' +
		'<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
		'OPCIONES <span class="caret"></span> ' +
		'</button><ul class="dropdown-menu">';
	// if (state_order_work != 'ENTREGADO') {
	// 	buttons = buttons + '<li><a onclick="get_html_states(this)"  data-toggle="modal"><i class="fa fa-navicon"></i> Estados</a></li>';
	// }
	buttons = buttons + '<li><a onclick="reception_view(this);"><i class="fa fa-eye"></i> Ver</a></li>' +
		'<li><a onclick="reception_history(this);"><i class="fa fa-book"></i>Historial</a></li>';
		// '<li><a onclick="reception_payment(this);"><i class="fa fa-book"></i>Pagos</a></li>';

	if (state_order_work == 'RECEPCIONADO') {
		
		buttons = buttons + '<li><a onclick="edit_register(this)"><i class="fa fa-edit"></i> Editar</a></li>';
		buttons = buttons + '<li><a onclick="diagnostic_form(this)"><i class="fa fa-edit"></i> Reparar</a></li>';
	}
	if (state_order_work == 'REPARADO') {
		// buttons = buttons + '<li><a onclick="edit_register(this)"><i class="fa fa-edit"></i> Editar</a></li>';
		buttons = buttons + '<li><a onclick="generar_venta(this)" ><i class="fa fa-navicon"></i> Generar Venta</a></li>'; //entregado
		// buttons = buttons + '<li><a onclick="get_html_states(this)"  data-toggle="modal"><i class="fa fa-navicon"></i> Estados</a></li>';
		// buttons = buttons + '<li><a onclick="add_spare(this)"><i class="fa fa-plus-square"></i> Proforma</a></li>';
	}
	// if (state_order_work == 'APROBADO') {
	// 	buttons = buttons + '<li><a onclick="edit_register(this)"><i class="fa fa-edit"></i> Editar</a></li>';
	// }
	buttons = buttons + '<li><a onclick="print_reception_option(this);"><i class="fa fa-print"></i> Imprimir</a></li>'; //+
		// '<li><a onclick="add_gallery(this);"><i class="fa fa-image"></i> Agregar Fotos </a></li>';
	if (state_order_work != 'ENTREGADO') {
		buttons = buttons + '<li><a onclick="delete_register(this)"><i class="fa fa-times"></i> Eliminar</a></li> ';
	}
	
	

	buttons = buttons + '</ul> ' + '</div>';
	return buttons;
}
function diagnostic_form(elemento) {
	var table = $(elemento).closest('table').DataTable();
	var current_row = $(elemento).parents('tr');
	if (current_row.hasClass('child')) {
		current_row = current_row.prev();
	}
	var data = table.row(current_row).data();
	var url = siteurl('order_work/order_work_detail');
	$.redirect(url, {id: data['id']}, 'POST', '_self');
}
function buttons_reception_delivered(has_gallery) {
	var buttons = '';
	buttons = '<div class="btn-group">' +
		'<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
		'OPCIONES <span class="caret"></span> ' +
		'</button><ul class="dropdown-menu">' +
		'<li><a onclick="reception_view(this);"><i class="fa fa-eye"></i> Ver</a></li>' +
		'<li><a onclick="reception_history(this);"><i class="fa fa-book"></i> Historial</a></li>' +
		'<li><a onclick="print_reception_option(this);"><i class="fa fa-print"></i> Imprimir</a></li>' +
		// '<li><a onclick="add_gallery(this);"><i class="fa fa-image"></i> Agregar Fotos </a></li>' +
		'</ul> ' +
		'</div>';
	return buttons;
}

function add_gallery(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	$.redirect(siteurl('reception/register_gallery'), {id: rowData['id']}, 'POST', '_self');
}

function get_html_states(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var TYPE_STATES_RECEPTION = 0;
	/* tipo de servicio para el cargado de menu de estados*/
	$.ajax({
		url: siteurl('reception/get_html_state'),
		data: {
			state_reception: rowData['estado_trabajo'],
			reception_id: rowData['id'],
			type_option: TYPE_STATES_RECEPTION
		},
		type: 'post',
		success: function (response) {
			$('#container_states').html(response);
			$('#receptiom_edit_states').modal('show');
		}
	});
}

function load_devices_customer() {
	$('#devices_select').empty();
	if ($("#id_customer").val() !== "" && undefined !== $("#id_customer").val()) {
		var id_customer = $("#id_customer").val();
		$.ajax({
			url: siteurl('customer/get_customer_devices'),
			data: 'id_customer=' + id_customer,
			type: 'post',
			success: function (data) {
				$('#devices_select').append('<option value="0"> Seleccione un dispositivo</option>');
				var datos = JSON.parse(data);
				$.each(datos, function (i, item) {
					$('#devices_select').append('<option value="' + item.producto_cliente_id + '">' + item.nombre + ' / ' + item.imei + '</option>');
				})
				$("#devices_select").val($("#equipo_cliente_id").val());
			}
		});
	} else {
		$('#devices_select').append('<option value="0"> Seleccione un dispositivo</option>');
	}
}

function load_services_type() {
	$.ajax({
		url: siteurl('service/get_service_types'),
		type: 'post',
		success: function (data) {
			datos = JSON.parse(data);
			$('#service_type').empty();
			$('#service_type').append('<option value="0">Seleccione una opcion</option>');
			$.each(datos, function (i, item) {
				$('#service_type').append('<option value="' + item.id + '">' + item.nombre + '</option>');
			})
		}
	});
}

function load_devices() {
	var datos = get_customer_devices();
	$.each(datos, function (i, item) {
		$('#brand').append('<option value="' + item.id + '">' + item.nombre + '</option>');
	})
}

function load_colors() {
	var datos = get_colors();
	$.each(datos, function (i, item) {
		$('#color_select').append('<option value="' + item.id + '">' + item.nombre + '</option>');
	})
}

function load_brands() {
	var datos = get_brands_devices();
	$("#brand").empty();
	$.each(datos, function (i, item) {
		$('#brand').append('<option value="' + item.id + '">' + item.nombre + '</option>')
	})
}
function load_models() {
	var datos = get_models_devices();
	$.each(datos, function (i, item) {
		$('#model').append('<option value="' + item.id + '">' + item.nombre + '</option>')
	})
}


function load_failures() {
	$("#failure_select").empty();
	if (undefined !== $("#id_reception").val() && $("#id_reception").val() !== "") {
		$.ajax({
			url: siteurl('failure/get_failure_reception'),
			async: false,
			type: 'post',
			data: {reception_id: $("#id_reception").val()},
			success: function (data) {
				var result = JSON.parse(data);
				var datos = [];
				$.each(result, function (i, item) {
					datos.push(item.id);
				});
				var data_failures = get_failures();
				$.each(data_failures, function (i, item) {
					var selected = "";
					if (datos.indexOf(item.id) >= 0) {
						selected = "selected";
					}
					$('#failure_select').append('<option value="' + item.id + '" ' + selected + ' >' + item.nombre + '</option>')
					if (item.id = 72) {
						$('#div_color').show();
					}

				})
			}
		});
	} else {
		var datos = get_failures();
		$.each(datos, function (i, item) {
			$('#failure_select').append('<option value="' + item.id + '">' + item.nombre + '</option>')
		})

	}
}

function load_failures_select() {
	if (undefined !== $("#id_reception").val() && $("#id_reception").val() !== "") {
		$.ajax({
			url: siteurl('failure/get_failure_reception'),
			async: false,
			type: 'post',
			data: {reception_id: $("#id_reception").val()},
			success: function (data) {
				var result = JSON.parse(data);
				var datos = [];
				$.each(result, function (i, item) {
					datos.push(item.id);
				});

				$("#failure_select").empty();
				var data_failures = get_failures();
				$.each(data_failures, function (i, item) {
					var selected = "";
					if (item.id.indexOf(datos) > -1) {
						selected = "selected";
					}
					$('#failure_select').append('<option value="' + item.id + '" ' + selected + ' >' + item.nombre + '</option>')
				})
			}
		});
	}
}

function load_solutions() {
	$("#solution_select").empty();
	if (undefined !== $("#id_reception").val() && $("#id_reception").val() !== "") {
		$.ajax({
			url: siteurl('solution/get_solution_reception'),
			async: false,
			type: 'post',
			data: {reception_id: $("#id_reception").val()},
			success: function (data) {
				var result = JSON.parse(data);
				var datos = [];
				$.each(result, function (i, item) {
					datos.push(item.id);
				});
				var data_solutions = get_solutions();

				$.each(data_solutions, function (i, item) {
					var selected = "";
					if (datos.indexOf(item.id) >= 0) {
						selected = "selected";
					}
					$('#solution_select').append('<option value="' + item.id + '" ' + selected + ' >' + item.nombre + '</option>')
				})
			}
		});
	} else {
		var datos = get_solutions();
		$.each(datos, function (i, item) {
			$('#solution_select').append('<option value="' + item.id + '">' + item.nombre + '</option>')
		})
	}
}

function load_references() {
	$("#reference_select").empty();
	if (undefined !== $("#id_reception").val() && $("#id_reception").val() !== "") {
		var datos = get_references();
		// $('#reference_select').append('<option value="0" selected>' + 'Seleccione referencia' + '</option>');
		var reference_id = $("#reference_id").val();
		$.each(datos, function (i, item) {
			var selected = '';
			if (reference_id == item.id) {
				selected = 'selected';
			}
			$('#reference_select').append('<option value="' + item.id + '" ' + selected + '>' + item.nombre + '</option>')
		})
	} else {
		var datos = get_references();
		// $('#reference_select').append('<option value="0" selected>' + 'Seleccione referencia' + '</option>');
		$.each(datos, function (i, item) {
			$('#reference_select').append('<option value="' + item.id + '">' + item.nombre + '</option>')
		})
	}
}

function select_gallery_images() {
	window.KCFinder = {
		callBackMultiple: function (images) {
			window.KCFinder = null;
			for (var i = 0; i < images.length; i++) {
				add_image_galery(images[i]);
			}
		}
	};
	window.open(base_url + 'assets/kcfinder/browse.php?type=image&dir=image',
		'kcfinder_multiple', 'status=0, toolbar=0, location=0, menubar=0, ' +
		'directories=0, resizable=1, scrollbars=0, width=800, height=600'
	);
}

function add_image_galery(src) {
	var child = '<div id="img_galeria"><li data-src="' + src + '" class="img-container"><div><a class="delete-img" title="Eliminar imagen de la lista">x</a><img src="' + src + '" class="img-responsive"></div></li></div>';
	$('#sortable-gallery').append(child);

	recargar_galeria();
}

function recargar_galeria() {
	destruir_galeria();
	crear_galeria();
}

function saludar(event) {
	// event.stopPropagation();
	// event.preventDefault();
	// $(this).closest('.img-container').remove();
}

function destruir_galeria() {
	var $lg = $('#sortable-gallery');
	$lg.lightGallery();
	$lg.data('lightGallery').destroy(true);
}

function crear_galeria() {
	var $lg = $('#sortable-gallery');
	$lg.lightGallery({
		thumbnail: true
	});
}

var total_services = 0;
var html = '';

function add_row() {
	if ($('#service_type').val() === '0' || $('#service').val() === '0') {
		swal('Datos Incorrectos', 'Seleccione los datos relacionados con el servicio', 'error');
		return true;
	}
	var html1 = '';
	var type_service = $('#service_type option:selected').text();
	var id_serice = $('#service').val();
	var service = $('#service option:selected').text();
	var precio_venta = parseFloat($('#price').val());
	var precio_costo = parseFloat($('#price_cost').val());

	html_body = "";
	html_body = '<tr data-id="' + id_serice + '" data-cost="' + precio_costo + '" data-price="' + precio_venta + '">' +
		'<td style="padding-left: 2%; text-align: left">' + type_service + '</td>' +
		'<td style="padding-left: 2%; text-align: left">' + service + '</td>' +
		'<td style="padding-right: 8%; text-align: right">' + precio_venta.toFixed(2) + '</td>' +
		'<td style="text-align: center"><button type="button" class="btn btn-danger eliminar">' +
		'<i class="fa fa-minus"></i> Eliminar</button></td>' +
		'</tr>';
	$('#table_services tbody').append(html_body);
	delete_row_table();
	calculate_total_amounts();
}

function delete_row_table() {
	$("button.eliminar").click(function () {
		$(this).parents("tr").fadeOut("normal", function () {
			$(this).remove();
			calculate_total_amounts();
		});
	});
}

function get_detail() {
	var detalle = [];
	$('#table_services tbody tr').each(function (index, value) {
		var data = {};
		data['id'] = value.dataset.id;
		data['price'] = value.dataset.price;
		data['cost'] = value.dataset.cost;
		detalle.push(data);
	});

	return detalle;
}

function get_detail_product() {
	var detalle = [];
	$('#table_services_product tbody tr').each(function (index, value) {
		var data = {};
		data['product_id'] = value.dataset.product_id;
		data['price_product'] = value.dataset.price_product;
		data['price_sale'] = value.dataset.price_sale;
		data['quantity'] = value.dataset.quantity;
		detalle.push(data);
	});
	return detalle;
}

function set_failures() {
	var failures = [];
	$($('#failure_select').val()).each(function (i, value) {
		failures.push(value);
	});
	return failures;
}

function set_solutions() {
	var solutions = [];
	$($('#solution_select').val()).each(function (i, value) {
		solutions.push(value);
	});
	return solutions;
}

function calculate_total_amounts() {

	var total_amount = 0;
	var html_foot_string = "";
	$('#table_services tbody tr').each(function (index, value) {
		total_amount = total_amount + parseFloat(value.dataset.price);
	});

	html_foot_string = '<tr> ' +
		'<td style="text-align: right" colspan="2"><b>Total Bs.:</b></td> ' +
		'<td style="padding: 10px; text-align: right"><b>' + total_amount.toFixed(2) + '</b></td> ' +
		'</tr>';
	$("#total_amount_service").val(total_amount.toFixed(2));
	$('#table_services tfoot').empty();
	$('#table_services tfoot').append(html_foot_string);
	calculate_total_reception();
}

function onchange_select_garantia() {
	var valor = $('#warranty_select').val();
	switch (valor) {
		case "0": { //sin garantia
			// document.getElementById('div_pagos_anticipados').style.display = 'block';
			break;
		}
		case "1": {//con garantia
			// document.getElementById('div_pagos_anticipados').style.display = 'none';
			break;
		}
		case "2": {//por verificar
			// document.getElementById('div_pagos_anticipados').style.display = 'block';
			break;
		}
	}
}

function calculate_total_reception() {
	var total_services = $('#total_amount_service').val();
	var total_products = $('#total_amount_product').val();

	var reception_total = parseFloat(total_services) + parseFloat(total_products);
	$('#reception_total').val(reception_total.toFixed(2));

	var reception_discount = $('#reception_discount').val();
	if (reception_discount == '') {
		reception_discount = 0;
	}
	var reception_total_payment = reception_total - reception_discount;

	$('#reception_total_payment').val(reception_total_payment.toFixed(2));

	var total_payment = $('#reception_payment').val();
	if (total_payment == '') {
		total_payment = 0;
	}
	var total_balance = reception_total_payment - total_payment;
	$('#reception_balance').val(total_balance.toFixed(2));
}

function calcular() {

}

function edit_register(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var id = rowData['id'];
	$.redirect(siteurl('reception/edit'), {id: id}, 'POST', '_self');
}

function add_spare(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var id = rowData['id'];
	$.redirect(siteurl('order_work/add_spare'), {id: id}, 'POST', '_self');
}

/*funcion eliminar */
function delete_register(element) {
	delete_register_commom(element, 'reception/disable');
}

function onclick_state(state) {
	var NO_APROBADO = 9; // En template_helper su estado es nro 9
	var estado = state;
	var recepcion_id = $("#inp_reception_id").val();
	var warranty = $("#warranty").val();

	if (estado == NO_APROBADO) {
		$('#reception_reason_id').val(recepcion_id);
		get_warehouse_for_type_warranty(warranty);

		$('#close_modal_state').click();
		$('#modal_reception_reason').modal({
			show: true,
			backdrop: 'static'
		});
	} else {
		update_reception_states(recepcion_id, estado);
	}
}

function generar_venta(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var estado = 5;
	var recepcion_id = rowData['id'];
	update_reception_states(recepcion_id, estado);
}
function print_reception_option(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var id = rowData['id'];
	$.redirect(siteurl('reports/imprimir_orden_trabajo'), {id: id}, 'POST', '_blank');
}

function reception_history(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var id = rowData['id'];
	$.redirect(siteurl('reception_notification/reception_specific'), {id: id}, 'POST', '_self');
}

function reception_payment(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var id = rowData['id'];
	$.redirect(siteurl('reception_payment'), {id: id}, 'POST', '_self');
}

/* Abre una ventana modal para ver la recepcion*/
function reception_view(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var id = rowData['id'];
	var totals = get_sum_reception_payments(id);
	console.log(totals);
	$('#delete_data').remove();
	$('#generate_totals_view').empty();

	$.post(siteurl('reception/view_reception'), {id: id},
		function (response) {
			var data = JSON.parse(response);
			var customer_phone = data.reception.telefono1 + ' - ' + data.reception.telefono1;
			var customer_type = '';
			var warranty = '';
			if (parseInt(data.reception.garantia) == 0) {
				warranty = 'Sin Garantia';
			} else if (parseInt(data.reception.garantia) == 1) {
				warranty = 'Con Garantia';
			} else {
				warranty = 'Por Verificar';
			}

			switch (parseInt(data.reception.tipo_cliente)) {
				case 0:
					customer_type = 'Cliente Diario';
					break;
				case 1:
					customer_type = 'Cliente por Mayor';
					break;
				case 2:
					customer_type = 'Cliente Express';
					break;
				case 3:
					customer_type = 'Cliente laboratorio';
					break;
			}

			var reception_observation = data.reception.observacion_recepcion;
			if (!reception_observation) {
				reception_observation = 'SIN OBSERVACION';
			}

			var order_work_observation = data.order_work.observacion;
			if (!order_work_observation) {
				order_work_observation = 'SIN OBSERVACION';
			}
			var reception_header = '<div class="form-group" style="text-align: left" id="delete_data"> <div class="form-line">' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Sucursal: ' + '&nbsp;' + data.branch_office.nombre_comercial + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Cod. Recepcion: ' + '&nbsp;' + data.reception.codigo_recepcion + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Cod. Trabajo: ' + '&nbsp;' + data.order_work.codigo_trabajo + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Vendedor: ' + '&nbsp;' + data.user.nombre + '</label></div>' +
				'<div class="col-lg-6 col-md-6 col-sm-4 col-xs-12"><label>Fecha Recepcion: ' + '&nbsp;' + data.reception.fecha_registro + '</label></div>' +
				'<div class="col-lg-6 col-md-6 col-sm-4 col-xs-12"><label>Fecha Orden Trabajo: ' + '&nbsp;' + data.order_work.fecha_registro + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Tipo Cliente: ' + '&nbsp;' + customer_type + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Cliente: ' + '&nbsp;' + data.reception.nombre + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>C.I.: ' + '&nbsp;' + data.reception.ci + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Telefono: ' + '&nbsp;' + customer_phone + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Marca: ' + '&nbsp;' + data.reception.nombre_marca + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Modelo: ' + '&nbsp;' + data.reception.nombre_comercial + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Imei: ' + '&nbsp;' + data.reception.imei + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Garantia: ' + '&nbsp;' + warranty + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Clave: ' + '&nbsp;' + data.reception.codigo_seguridad + '</label></div>' +
				'<div class="col-lg-9 col-md-3 col-sm-4 col-xs-12"><label>Accesorios: ' + '&nbsp;' + data.reception.accesorio_dispositivo + '</label></div>' +
				'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" ><label>Observacion Recepcion: ' + '&nbsp;' + reception_observation + '</label></div>' +
				'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: #fac4c4"><label>Observacion Tecnico: ' + '&nbsp;' + order_work_observation + '</label></div>' +
				'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE FALLAS Y SOLUCIONES</label></div>';
			var failure_detail = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="center"><label>FALLAS</label>';
			var data_detail_failure = data.detail_failures;
			for (var i = 0; i < data_detail_failure.length; i++) {
				var item = data_detail_failure[i];
				failure_detail = failure_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center"><label>' + item.nombre + '</label></div>';
			}
			;
			failure_detail = failure_detail + '</div>';

			var solution_detail = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="center"><label>SOLUCIONES</label>';
			var data_detail_solution = data.detail_solutions;
			for (var i = 0; i < data_detail_solution.length; i++) {
				var item = data_detail_solution[i];
				solution_detail = solution_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center"><label>' + item.nombre + '</label></div>';
			}
			solution_detail = solution_detail + '</div>';

			var service_detail = '';
			if (data.detail_services.length > 0) {
				service_detail = service_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE SERVICIOS</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>SERVICIO</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>OBSERVACION</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>PRECIO</label></div>';
				var data_detail_services = data.detail_services;
				for (var i = 0; i < data_detail_services.length; i++) {
					var item = data_detail_services[i];
					var service_observation = item.observacion;
					if (!service_observation) {
						service_observation = 'SIN OBSERVACION';
					}
					service_detail = service_detail + '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.nombre_servicio + '</label></div>' +
						'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + service_observation + '</label></div>' +
						'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.precio_servicio + '</label></div>';
				}
			}


			var product_detail = '';
			if (data.detail_products.length > 0) {
				product_detail = product_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE REPUESTOS</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>REPUESTO</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>CANTIDAD</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>PRECIO</label></div>';
				var data_detail_services = data.detail_products;
				for (var i = 0; i < data_detail_services.length; i++) {
					var item = data_detail_services[i];
					product_detail = product_detail + '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.nombre_producto + '</label></div>' +
						'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.cantidad + '</label></div>' +
						'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.precio_venta + '</label></div>';
				}
			}

			var view_descuento = 0;
			if (data.order_work.descuento != null) {
				view_descuento = data.order_work.descuento;
			} else {
				view_descuento = view_descuento.toFixed(2);
			}

			var reception_footer = '</div></div>';
			var reception_data = reception_header + failure_detail + solution_detail + service_detail + product_detail + reception_footer;
			$('#reception_data').append(reception_data);
			var reception_totals = '';
			reception_totals += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">' +
				'<div class="block" align="right" style="padding-right: 4%">' +
				'<label>Monto Total Trabajo: ' + '&nbsp;</label>' + data.order_work.monto_total +
				'<br>' +
				'</div>' +
				'<div class="block" align="right" style="padding-right: 4%">' +
				'<b>Descuentos Anteriores: ' + '&nbsp;</b><span id="lbl_reception_discount_old">' + totals.total_discount + '</span>' +
				'<br>' +
				'</div>' +
				'<div class="block" align="right" style="padding-right: 4%" hidden>' +
				'<b>Descuentos Actual: ' + '&nbsp;</b><span id="lbl_reception_discount">0.00</span>' +
				'<br>' +
				'</div>' +
				'<div class="block" align="right" style="padding-right: 4%">' +
				'<b>Total a Pagar: ' + '&nbsp;</b><span id="lbl_reception_total_payment">0.00</span>' +
				'<br>' +
				'</div>' +
				'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">' +
				'<div class="block" align="right" style="padding-right: 4%">' +
				'<label>Monto Total Pagado: ' + '&nbsp;</label>' + totals.total_payments +
				'<br>' +
				'</div> ' +
				'<div class="block" align="right" style="padding-right: 4%">' +
				'<b>Saldo Total ' + '</b><span id="lbl_reception_balance">0.00</span>' +
				'<br>' +
				'</div> ' +
				'<br>' +
				'</div> ' +
				'<div class="block" align="right" style="padding-right: 4%" hidden>' +
				'<b>Pagar para Entregar ' + '</b><span id="lbl_reception_payment">0.00</span>' +
				'<br>' +
				'</div> ' +
				'<input id="reception_total" name="reception_total"  type="number" step="any" hidden value="' + data.order_work.monto_total + '" >' +
				'<input id="reception_discount_old" name="reception_discount_old"  type="number" step="any" hidden value="' + totals.total_discount + '" >' +
				'<input id="reception_discount" name="sale_discount"  type="number" step="any" hidden value="0.00" >' +
				'<input id="reception_total_payment" name="reception_total_payment" type="number" step="any" hidden value="0.00" >' +
				'<input id="reception_payment_old" name="reception_payment_old" type="number" step="any" hidden value="' + totals.total_payments + '" >' +
				'<input id="reception_payment" name="reception_payment" type="number" step="any" hidden value="0.00" >' +
				'<input id="reception_balance" name="reception_balance" type="number" step="any" hidden value="0.00" >'
			;
			$('#generate_totals_view').append(reception_totals);
		}
	);
	$('#modal_view_reception').modal({
		show: true,
		backdrop: 'static'
	});
	setTimeout(function () {
		calculate_total_reception_deliver();
	}, 1500);
}

/* Abre una ventana modal para ver la recepcion y generar la venta*/
function open_modal_generate_sale(reception_id) {
	var id = reception_id;
	$('#delete_data').remove();
	$('#generate_totals').empty();
	var totals = get_sum_reception_payments(reception_id);
	console.log(totals);
	$.post(siteurl('reception/view_reception'), {id: id},
		function (response) {
			var data = JSON.parse(response);
			var customer_phone = data.reception.telefono1 + ' - ' + data.reception.telefono1;
			var customer_type = '';
			var warranty = '';

			var saldo_total = 0.00;
			if (parseInt(data.reception.garantia) == 0) {
				warranty = 'Sin Garantia';
			} else if (parseInt(data.reception.garantia) == 1) {
				warranty = 'Con Garantia';
			} else {
				warranty = 'Por Verificar';
			}
			switch (parseInt(data.reception.tipo_cliente)) {
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

			var reception_observation = data.reception.observacion_recepcion;
			if (!reception_observation) {
				reception_observation = 'SIN OBSERVACION';
			}

			var order_work_observation = data.order_work.observacion;
			if (!order_work_observation) {
				order_work_observation = 'SIN OBSERVACION';
			}
			var date_current = new Date();

			$('#order_work_id').val(data.order_work.id);
			$('#reception_id').val(id);
			$('#code_reception').val(data.order_work.codigo_trabajo);
			$('#reception_payment_observation').val('Pago para la entrega de equipo de ' + data.order_work.codigo_trabajo + ' en fecha ' + date_current.getDate() + "/" + (date_current.getMonth() + 1) + "/" + date_current.getFullYear());
			var reception_header = '<div class="form-group" style="text-align: left" id="delete_data"> <div class="form-line">' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Sucursal: ' + '&nbsp;' + data.branch_office.nombre_comercial + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Cod. Recepcion: ' + '&nbsp;' + data.reception.codigo_recepcion + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Cod. Trabajo: ' + '&nbsp;' + data.order_work.codigo_trabajo + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Vendedor: ' + '&nbsp;' + data.user.nombre + '</label></div>' +
				'<div class="col-lg-6 col-md-6 col-sm-4 col-xs-12"><label>Fecha Recepcion: ' + '&nbsp;' + data.reception.fecha_registro + '</label></div>' +
				'<div class="col-lg-6 col-md-6 col-sm-4 col-xs-12"><label>Fecha Orden Trabajo: ' + '&nbsp;' + data.order_work.fecha_registro + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Tipo Cliente: ' + '&nbsp;' + customer_type + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Cliente: ' + '&nbsp;' + data.reception.nombre + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>C.I.: ' + '&nbsp;' + data.reception.ci + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Telefono: ' + '&nbsp;' + customer_phone + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Marca: ' + '&nbsp;' + data.reception.nombre_marca + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Modelo: ' + '&nbsp;' + data.reception.nombre_comercial + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Imei: ' + '&nbsp;' + data.reception.imei + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Garantia: ' + '&nbsp;' + warranty + '</label></div>' +
				'<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Clave: ' + '&nbsp;' + data.reception.codigo_seguridad + '</label></div>' +
				'<div class="col-lg-9 col-md-3 col-sm-4 col-xs-12"><label>Accesorios: ' + '&nbsp;' + data.reception.accesorio_dispositivo + '</label></div>' +
				'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" ><label>Observacion Recepcion: ' + '&nbsp;' + reception_observation + '</label></div>' +
				'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: #fac4c4"><label>Observacion Tecnico: ' + '&nbsp;' + order_work_observation + '</label></div>' +
				'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE FALLAS Y SOLUCIONES</label></div>';
			var failure_detail = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="center"><label>FALLAS</label>';
			var data_detail_failure = data.detail_failures;
			for (var i = 0; i < data_detail_failure.length; i++) {
				var item = data_detail_failure[i];
				failure_detail = failure_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center"><label>' + item.nombre + '</label></div>';
			}
			;
			failure_detail = failure_detail + '</div>';

			var solution_detail = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="center"><label>SOLUCIONES</label>';
			var data_detail_solution = data.detail_solutions;
			for (var i = 0; i < data_detail_solution.length; i++) {
				var item = data_detail_solution[i];
				solution_detail = solution_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center"><label>' + item.nombre + '</label></div>';
			}
			solution_detail = solution_detail + '</div>';

			var service_detail = '';
			if (data.detail_services.length > 0) {
				service_detail = service_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE SERVICIOS</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>SERVICIO</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>OBSERVACION</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>PRECIO</label></div>';
				var data_detail_services = data.detail_services;
				for (var i = 0; i < data_detail_services.length; i++) {
					var item = data_detail_services[i];
					var service_observation = item.observacion;
					if (!service_observation) {
						service_observation = 'SIN OBSERVACION';
					}
					service_detail = service_detail + '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.nombre_servicio + '</label></div>' +
						'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + service_observation + '</label></div>' +
						'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.precio_servicio + '</label></div>';
				}
			}
			var product_detail = '';
			if (data.detail_products.length > 0) {
				product_detail = product_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE REPUESTOS</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>REPUESTO</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>CANTIDAD</label></div>' +
					'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>PRECIO</label></div>';
				var data_detail_services = data.detail_products;
				for (var i = 0; i < data_detail_services.length; i++) {
					var item = data_detail_services[i];
					product_detail = product_detail + '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.nombre_comercial + '</label></div>' +
						'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.cantidad + '</label></div>' +
						'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.precio_venta + '</label></div>';
				}
			}
			var reception_footer = '</div></div>';
			var reception_data = reception_header + failure_detail + solution_detail + service_detail + product_detail + reception_footer;
			$('#generate_data').append(reception_data);
			var reception_totals = '';
			reception_totals += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">' +
				'<div align="right" style="padding-right: 4%">' +
				'<label>Monto Total Trabajo: ' + '&nbsp;</label>' + data.order_work.monto_total +
				'<br>' +
				'</div>' +
				'<div align="right" style="padding-right: 4%">' +
				'<b>Descuentos Anteriores: ' + '&nbsp;</b><span id="lbl_reception_discount_old">' + totals.total_discount + '</span>' +
				'<br>' +
				'</div>' +
				'<div align="right" style="padding-right: 4%">' +
				'<b>Descuentos Actual: ' + '&nbsp;</b><span id="lbl_reception_discount">0.00</span>' +
				'<br>' +
				'</div>' +
				'<div align="right" style="padding-right: 4%">' +
				'<b>Total a Pagar: ' + '&nbsp;</b><span id="lbl_reception_total_payment">0.00</span>' +
				'<br>' +
				'</div>' +
				'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">' +
				'<div align="right" style="padding-right: 4%">' +
				'<label>Monto Total Pagado: ' + '&nbsp;</label>' + totals.total_payments +
				'<br>' +
				'</div> ' +
				'<div class="block" align="right" style="padding-right: 4%">' +
				'<b>Saldo Total ' + '</b><span id="lbl_reception_balance">0.00</span>' +
				'<br>' +
				'</div> ' +
				'<br>' +
				'</div> ' +
				'<div class="block" align="right" style="padding-right: 4%">' +
				'<b>Pagar para Entregar ' + '</b><span id="lbl_reception_payment">0.00</span>' +
				'<br>' +
				'</div> ' +
				'<input id="reception_total" name="reception_total"  type="number" step="any" hidden value="' + data.order_work.monto_total + '" >' +
				'<input id="reception_discount_old" name="reception_discount_old"  type="number" step="any" hidden value="' + totals.total_discount + '" >' +
				'<input id="reception_discount" name="sale_discount"  type="number" step="any" hidden value="0.00" >' +
				'<input id="reception_total_payment" name="reception_total_payment" type="number" step="any" hidden value="0.00" >' +
				'<input id="reception_payment_old" name="reception_payment_old" type="number" step="any" hidden value="' + totals.total_payments + '" >' +
				'<input id="reception_payment" name="reception_payment" type="number" step="any" hidden value="0.00" >' +
				'<input id="reception_balance" name="reception_balance" type="number" step="any" hidden value="0.00" >'
			;
			$('#generate_totals').append(reception_totals);
		});
	$('#modal_generate_sale_reception').modal({
		show: true,
		backdrop: 'static'
	});


	$('#reception_discount').hide();
	setTimeout(function () {
		calculate_total_reception_deliver();
		/*alert($('#reception_discount_old').val());
		alert($('#reception_payment_old').val());*/
	}, 1500);
}

function get_sum_reception_payments(recepcion_id) {
	var totals = new Object();
	$.ajax({
		url: siteurl('reception_payment/get_sum_reception_payments'),
		type: 'post',
		data: {id: recepcion_id},
		success: function (response) {
			var data = JSON.parse(response);
			totals.total_discount = data.total_descuentos;
			totals.total_payments = data.total_pagados;
			// alert(data.total_descuentos);
			// alert(data.total_pagados);
			/*alert(totals.total_discount );
			alert(totals.total_payments);*/

		}
	});
	return totals;

}

function calculate_total_reception_deliver() {

	var reception_total = $('#reception_total').val();
	var discount_current = 0;

	var reception_discount = $('#sale_discount').val();
	if (reception_discount == '') {
		reception_discount = 0;
	}
	discount_current=reception_discount;
	$('#reception_discount').val(reception_discount);

	var reception_discount_old = $('#reception_discount_old').val();
	if (reception_discount_old == '') {
		reception_discount_old = 0;
	}
	reception_discount = parseFloat(reception_discount) + parseFloat(reception_discount_old);

	var reception_total_payment = reception_total - reception_discount;

	var total_payment_old = $('#reception_payment_old').val();
	if (total_payment_old == '') {
		total_payment_old = 0;
	}

	var total_balance = parseFloat(reception_total_payment) - parseFloat(total_payment_old);

	$('#lbl_reception_total_payment').text(reception_total_payment);
	$('#lbl_reception_payment').text(total_balance);
	$('#lbl_reception_balance').text(total_balance);
	$('#lbl_reception_discount').text(discount_current);

	$('#reception_total_payment').val(reception_total_payment.toFixed(2));

	$('#reception_payment').val(total_balance);
	$('#reception_balance').val(total_balance - total_balance);
}


function exists_support_user_media() {
	return !!(navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia)
}

function exists_browser_permissions_user_media() {
	return (navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia).apply(navigator, arguments);
}

function enable_webcam() {

	var $video = document.getElementById("video"),
		$canvas = document.getElementById("canvas"),
		$boton = document.getElementById("boton")

	if (exists_support_user_media()) {
		exists_browser_permissions_user_media(
			{video: true},
			function (stream) {
				console.log("Permiso concedido");
				$video.srcObject = stream;
				$video.play();

				$boton.addEventListener("click", function () {
					// Pausar reproducción
					$video.pause();
					// Obtener contexto del canvas y dibujar sobre él
					var contexto = $canvas.getContext("2d");
					$canvas.width = $video.videoWidth;
					$canvas.height = $video.videoHeight;
					contexto.drawImage($video, 0, 0, $canvas.width, $canvas.height);

					// Imagen tomada en base64
					var image = $canvas.toDataURL();

					$.ajax({
						url: siteurl('reception/take_picture_from_webcam'),
						data: {
							reception_id: $("#id_reception").val(),
							reception_code: $("#reception_code").val(),
							image: encodeURIComponent(image)
						},
						type: 'post',
						success: function (data) {
							list_images_reception();
						}
					});

					$video.play();
					list_images_reception();
				});

			}, function (error) {
				console.log("Permiso denegado o error: ", error);
			});
	} else {
		alert("Lo siento. Tu navegador no soporta esta característica");
	}
}

function list_images_reception() {
	$.ajax({
		url: siteurl('reception/get_reception_images'),
		type: "POST",
		data: {reception_id: $("#id_reception").val()},
		success: function (response) {
			console.log(response);
			if (response == '1') {
				console.log("DATOS CORRECTOS");
			} else {
				console.log("DEBE SELECCIONAR ALGUNA IMAGEN");
			}
			//$('#image_preview').append(response);
			document.getElementById("image_preview").innerHTML = response;
		},
		error: function (response) {
			console.log("ERROR INTERNO, CONTACTE CON EL ADMINISTRADOR");
		}
	})
}

function update_reception_states(recepcion_id, estado) {
	ajaxStart('Registrando datos...');
	$.ajax({
		url: siteurl('order_work/update_state_order'),
		type: 'post',
		data: {
			reception_id: recepcion_id,
			state_reception: estado
		},
		dataType: 'json',
		success: function (response) {
			ajaxStop();
			if (response.verify === true) {
				if (response.success === true) {
					if (response.sale === true) {
						$('#close_modal_state').click();
						var id = response.reception_id;

						//////////////////////////////////////////////////
						$.redirect(siteurl('reception/view_reception_and_note_sale'), {
							reception_id: id
						}, 'POST','');
						//////////////////////////////////////////////////

						//open_modal_generate_sale(id);
					} else {
						swal('DATOS CORRECTOS', response.messages, 'success');
						update_data_table($("#reception_list"));
						$('#receptiom_edit_states').modal('hide');
					}
				} else if (response.login === true) {
					login_session();
				} else if (response.tecnico === false) {
					swal('ADVERTENCIA', 'Ud. no puede cambiar de estado por que no es usuario tecnico.', 'error');
				} else {
					swal('DATOS NO SE PUEDE ACTUALIZAR EL ESTADO', response.messages, 'error');
				}
			} else {
				if (response.success === true) {
					if (response.sale === true) {
						$('#close_modal_state').click();
						var id = response.reception_id;
						//////////////////////////////////////////////////
						$.redirect(siteurl('reception/view_reception_and_note_sale'), {
							reception_id: id
						}, 'POST','');
						//////////////////////////////////////////////////
						// $.redirect(siteurl('reception/view_reception_and_note_sale'), {
						// 	reception_id: id
						// }, 'POST','');

						//open_modal_generate_sale(id);
					} else {
						swal('DATOS CORRECTOS', response.messages, 'success');
						update_data_table($("#reception_list"));
						$('#receptiom_edit_states').modal('hide');
					}
				} else if (response.login === true) {
					login_session();
				} else if (response.tecnico === false) {
					swal('ADVERTENCIA', 'Ud. no puede cambiar de estado por que no es usuario tecnico.', 'error');
				} else {
					swal('DATOS NO SE PUEDO ACTUALIZAR EL ESTADO', response.messages, 'error');

				}
			}
		}
	});
}

function get_warehouse_for_type_warranty() {
	var warranty_select = $("#warranty_select").val();
	if (warranty_select == '') {
		warranty_select = 2;
	}
	$.ajax({
		url: site_url + 'reception/get_warehouse_for_type_warranty',
		dataType: "json",
		type: 'post',
		data: {
			warranty: warranty_select,
		},
		success: function (data) {
			var html_warehouse = "";
			$("#warehouse_id").empty();
			if (warranty_select == 2) {
				html_warehouse = '<option value="">Seleccione una opcion</option>';
			}
			$.each(data, function (i, item) {
				html_warehouse = html_warehouse + '<option value="' + item.id + '">' + item.nombre + '</option>';
			});
			$('#warehouse_id').append(html_warehouse);
		}
	});
}

function get_warehouse_for_type_warranty(warranty_select) {
	if (warranty_select == '') {
		warranty_select = 2;
	}
	$.ajax({
		url: site_url + 'reception/get_warehouse_for_type_warranty',
		dataType: "json",
		type: 'post',
		data: {
			warranty: warranty_select,
		},
		success: function (data) {
			var html_warehouse = "";
			$("#warehouse_id").empty();
			if (warranty_select == 2) {
				html_warehouse = '<option value="">Seleccione una opcion</option>';
			}
			$.each(data, function (i, item) {
				html_warehouse = html_warehouse + '<option value="' + item.id + '">' + item.nombre + '</option>';
			});
			$('#warehouse_id').append(html_warehouse);
		}
	});
}
