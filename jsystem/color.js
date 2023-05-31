/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 21/01/2020
 * Time: 14:38 PM
 */

$(document).ready(function () {
	var buttons=[{name:'NUEVO', id:'btn_new_color', label:' Nuevo Color', type:'modal'}];

	container_buttons(buttons, options);
	get_color_list(options);
});

$(document).ready(function () {
	$('#btn_new_color').click(function () {
		$('#frm_new_color')[0].reset();
		$(".modal-error").empty();
		$('#modal_new_color').modal({
			show: true,
			backdrop: 'static'
		});
	});

	$('#frm_new_color').submit(function (event) {
		event.preventDefault();
		var form = $(this);
		ajaxStart('Registrando datos...');
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: $(form).serialize(),
			dataType: 'json',
			success: function (response) {
				ajaxStop();
				$('.modal-error').remove();
				if (response.success === true) {
					swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
					$('#close_modal_new_color').click();
					update_data_table($('#table_color'));
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
			}
		});
	});

	$('#frm_edit_color').submit(function (event) {
		event.preventDefault();
		var form = $(this);
		ajaxStart('Registrando datos...');
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: $(form).serialize(),
			dataType: 'json',
			success: function (response) {
				ajaxStop();
				$('.modal-error').remove();
				if (response.success === true) {
					swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
					$('#close_modal_edit_color').click();
					update_data_table($('#table_color'));
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
			}
		});
	});
});

function get_color_list(options) {
	$('#table_color').DataTable({
		paging: true,
		info: true,
		filter: true,
		stateSave: true,
		processing: true,
		serverSide: true,
		ajax: {
			url: siteurl('color/get_color_list'),
			type: 'post'
		},
		columns: [
			{data: 'id'},
			{data: 'nombre', class: 'text-center'},
			{data: 'descripcion', class: 'text-center'},
			{data: 'estado', class: 'text-center'},
			{data: 'opciones', class: 'text-center'}
		],
		columnDefs: [{
			targets: 0,
			visible: false,
			searchable: false
		}, {
			targets: 3,
			render: function (data) {
				return messages_states(data);
			}
		}, {
			targets: 4,
			orderable: false,
			render: function (data, type, row) {
				if (row.estado != 0) {
					return load_buttons_crud('modal',options);
				} else {
					return '<label>No tiene Opciones</label>';
				}
			}
		}],
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
	var name = rowData['nombre'];
	var description = rowData['descripcion'];

	$('#color_id').val(id);
	$('#edit_name').val(name);
	$('#edit_description').val(description);

	$('#modal_edit_color').modal({
		show: true,
		backdrop: 'static'
	});
}

function delete_register(element) {
	delete_register_commom(element, 'color/disable_color');
}

function view_register(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	var name = rowData['nombre'];
	var description = rowData['descripcion'];

	$('#view_name').val(name);
	$('#view_description').val(description);

	$('#modal_view_color').modal({
		show: true,
		backdrop: 'static'
	});
}
