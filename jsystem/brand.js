/**
 * Created by Alejandro on 18/7/2017.
 */
$(document).ready(function () {
    get_brand_list();
})
$(function () {
    /* Abre una ventana modal para registrar un nueva marca */
    $('#btn_new_brand').click(function () {
        $('#frm_new_brand')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_brand').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nueva marca se activa esta accion*/
    $('#frm_new_brand').submit(function (event) {
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
                    $('.modal-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_new_brand').click();

                    update_data_table($('#list_brand'));
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
            }
        });
    });

    /*Cuando da clic guardar de modal editar marca se activa esta accion*/
    $('#frm_edit_brand').submit(function (event) {
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
                    $('.error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal_edit_brand').click();

                    update_data_table($('#list_brand'));
                } else if(respuesta.login === true){
                    login_session();
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

})


/* Abre una ventana modal para editar marca */
function edit_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var nombre = rowData['nombre'];
    var codigo = rowData['codigo'];
    $('#id_marca').val(id);
    $('#nombre_marca').val(nombre);
    $('#codigo_marca').val(codigo);
    $('#modal_edit_brand').modal({
        show: true,
        backdrop: 'static'
    });
}


function consumir_servicio() {
	$.ajax({
		url: "http://workcorp.net/web_service/dripclub_service/index.php/api/getKey",
		headers: {
			'X-API-KEY':'eqWpZQsitVybqbMMqDu0Ij5Xux1pU38S',
			'Content-Type':'application/json'
		},
		type: "POST",
		data: {
			"key":"$2y$10$1sCH2JWH0AFLmB6DWjRig.KXhtDtGmIUs1AgPdBLjTEhbdaB27S3W",
			"name":"",
			"product":[1,2,100]
		},
		dataType: 'json',
		cache: false,
		success: function(data)
		{
			alert(data);

		}
	});
}

/*function servicio_postman() {
	var settings = {
		"async": true,
		"crossDomain": true,
		"url": "http://workcorp.net/web_service/dripclub_service/index.php/api/getProducts",
		"method": "POST",
		"headers": {
			"X-API-KEY": "eqWpZQsitVybqbMMqDu0Ij5Xux1pU38S",
			"Content-Type": "application/json",
			"cache-control": "no-cache",
			"Postman-Token": "514961e5-c6d0-4c5a-a12e-16fa9e042d79"
		},
		"processData": false,
		cookie:"ci_session=78216f253b4584b32c9f3f791de5b6d130294b22",
		dataType: 'json',
		"data": "{\r\n  \"key\":\"$2y$10$1sCH2JWH0AFLmB6DWjRig.KXhtDtGmIUs1AgPdBLjTEhbdaB27S3W\",\r\n  \"name\":\"\",\r\n  \"product\":[1,2,100]\r\n}"
	}

	$.ajax(settings).done(function (response) {
		console.log(response);
		alert(response);
	});
}*/

function servicio_postman() {
	var settings = {
		"async": true,
		"crossDomain": true,
		"url": "http://workcorp.net/web_service/dripclub_service/api/getKey",
		"method": "POST",
		"headers": {
			"X-API-KEY": "eqWpZQsitVybqbMMqDu0Ij5Xux1pU38S",
			"Content-Type": "application/json",
			"cache-control": "no-cache"
		},
		"processData": false,
		"data": "{\r\n  \"user\":\"dripclub_service\",\r\n  \"pass\":\"dripclub_service*RaccoonIT\",\r\n  \"date\":\"2019-09-11\"\r\n}"
	}

	$.ajax(settings).done(function (response) {
		console.log(response);
	});
}

/*funcion eliminar marca */
function delete_register(element) {
    delete_register_commom(element, 'brand/disable_brand');
}

/*carga la lista de las marcas al datatable*/
function get_brand_list() {
    var tabla = $('#list_brand').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        'ajax': {
            "url": siteurl('brand/get_brand_list'),
            "type": 'post'
        },
        'columns': [
            {data: 'id'},
            {data: 'codigo', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        "columnDefs": [{
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
                    return '<label>NO TIENE OPCIONES</label>';
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}
