/**
 * Created by Alejandro on 24/7/2017.
 */

$(document).ready(function () {

    /* Abre una ventana modal para registrar un nuevo tipo almacen */
    $('#btn_new_inventory_income_type').click(function () {
        $('#frm_new_inventory_income_type')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_inventory_income_type').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nuevo tipo almacen se activa esta accion*/
    $('#frm_new_inventory_income_type').submit(function (event) {
        event.preventDefault();
        var form = $(this);
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
                    $('#close_modal_new_inventory_income_type').click();
                    update_data_table($('#list_inventory_income_type'));
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
            },error: function (error) {
                ajaxStop();
                console.log(error.responseText);
                // **alert('error; ' + eval(error));**
                swal('Error', 'Error al registrar los datos.', 'error');
            }
        });
    });

    /*Cuando da clic guardar de modal editar solucion se activa esta accion*/
    $('#frm_edit_inventory_income_type').submit(function (event) {
        event.preventDefault();
        var form = $(this);
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
                    $('#close_modal_edit_inventory_income_type').click();
                    update_data_table($('#list_inventory_income_type'));
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
            },error: function (error) {
                ajaxStop();
                console.log(error.responseText);
                // **alert('error; ' + eval(error));**
                swal('Error', 'Error al registrar los datos.', 'error');
            }
        });
    });

});

/* Abre una ventana modal para editar tipo almacen */
function edit_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var nombre = rowData['nombre'];
    var descripcion = rowData['decripcion'];
	// if (content == 1) {
		$('#edit_id_inventory_income_type').val(id);
		$('#edit_name').val(nombre);
		$('#edit_description').val(descripcion);
		$('#modal_edit_inventory_income_type').modal({
			show: true,
			backdrop: 'static'
		});
	// } else {
	// 	swal('No permitido', 'Valor Este registro no se puede editar', 'info');
	// }

}

/*funcion eliminar tipo de almacen */
function delete_register(element) {
	var table = $(element).closest('table').DataTable();
	var fila = $(element).parents('tr');
	if (fila.hasClass('child')) {
		fila = fila.prev();
	}
	var rowData = table.row(fila).data();
	// var content = rowData['contenido'];

	// if (content == 1) {
		delete_register_commom(element, 'inventory_income_type/disable_inventory_income_type');
	// } else {
	// 	swal('No permitido', 'Este registro no se puede Eliminar', 'info');
	// }

}

/*carga la lista de los tipo de almacenes al datatable*/
function get_inventory_income_type_list() {
    
    $('#list_inventory_income_type').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('inventory_income_type/get_inventory_income_type_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'nombre', class: 'text-center'},
            {data: 'decripcion', class: 'text-left'},
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
                return state_crud(data);
            }
        }, {
            targets: 4,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(2, 'modal');
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }],
    });

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

    $('#modal_view_inventory_income_type').modal({
        show: true,
        backdrop: 'static'
    });
}
