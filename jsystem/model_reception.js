/**
 * Created by Alejandro on 18/7/2017.
 */
$(document).ready(function () {
    get_model_reception_list();
})
$(function () {
    /* Abre una ventana modal para registrar una nueva sucursal */
    $('#btn_new_model_reception').click(function () {
        
        $('#frm_new_model_reception')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();

        get_brand_reception_enable();
        console.log('janco');
        $('#modal_registro_model_reception').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nueva marca se activa esta accion*/
    $('#frm_new_model_reception').submit(function (event) {
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
                    $('#close_modal_model_reception').click();

                    update_data_table($('#list_model_reception'));
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
    $('#frm_edit_model_reception').submit(function (event) {
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
                    $('#close_modal_edit_model_reception').click();

                    update_data_table($('#list_model_reception'));
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


/*Obtines la lista de todas las marcas para cargar en un combo*/
function get_brand_reception_enable() {
    $("#brand_reception_model").empty();
    $.post(siteurl('brand_reception/get_brand_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                // if (marca_id != item.id) {
                    $('#brand_reception_model').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                // }
            });
        });
}

/*Obtines la lista de todas las marcas para cargar en un combo*/
function get_brand_reception_edit(marca_id) {
    
    $.post(siteurl('brand_reception/get_brand_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                if (marca_id != item.id) {
                    $('#brand_reception_model_edit').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                }
            });
        });
}
/* Abre una ventana modal para editar marca */
function view_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var name = rowData['nombre'];
    var description = rowData['descripcion'];
    var marca_id = rowData['marca_id'];
    var marca_recepcion = rowData['marca_recepcion'];
    $('#view_name_model').val(name);
    $('#view_description_model').val(description);

    $('#brand_reception_model_view').append('<option value="' + marca_id + '">' + marca_recepcion+ '</option>');
    // get_brand_reception_edit(marca_id);

    $('#modal_view_model_reception').modal({
        show: true,
        backdrop: 'static'
    });
}

/* Abre una ventana modal para editar marca */
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
    var marca_id = rowData['marca_id'];
    var marca_recepcion = rowData['marca_recepcion'];
    $('#id_model_reception').val(id);
    $('#edit_name_model').val(name);
    $('#edit_description_model').val(description);

    $('#brand_reception_model_edit').empty();

    $('#brand_reception_model_edit').append('<option value="' + marca_id + '">' + marca_recepcion+ '</option>');
    get_brand_reception_edit(marca_id);

    $('#modal_edit_model_reception').modal({
        show: true,
        backdrop: 'static'
    });

}

/*funcion eliminar marca */
function delete_register(element) {
    delete_register_commom(element, 'model_reception/disable_model_reception');
}

/*funcion eliminar marca */
function reactivate_register_model(element) {
    reactivate_register_commom(element, 'model_reception/enable_model_reception');
}

/*carga la lista de las marcas al datatable*/
function get_model_reception_list() {
    var tabla = $('#list_model_reception').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        'ajax': {
            "url": siteurl('model_reception/get_model_reception_list'),
            "type": 'post'
        },
        'columns': [
            {data: 'id'},
            {data: 'nombre'},
            {data: 'descripcion'},
            {data: 'marca_recepcion'},
            {data: 'opciones', class: 'text-center'}
        ],
        "columnDefs": [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 4,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(3, 'modal');
                } else {
                    return '<button type="button" onclick="reactivate_register_model(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
}
