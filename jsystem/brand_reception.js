/**
 * Created by Alejandro on 18/7/2017.
 */
$(document).ready(function () {
    get_brand_reception_list();
})
$(function () {
    /* Abre una ventana modal para registrar una nueva sucursal */
    $('#btn_new_brand_reception').click(function () {
        // get_department_new();
        $('#frm_new_brand_reception')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_brand_reception').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nueva marca se activa esta accion*/
    $('#frm_new_brand_reception').submit(function (event) {
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
                    $('#close_modal_brand_reception').click();

                    update_data_table($('#list_brand_reception'));
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
    $('#frm_edit_brand_reception').submit(function (event) {
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
                    $('#close_modal_edit_brand_reception').click();

                    update_data_table($('#list_brand_reception'));
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
function edit_register_brand(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var name = rowData['nombre'];
    var description = rowData['descripcion'];
    $('#id_brand_reception').val(id);
    $('#edit_name_brand').val(name);
    $('#edit_description_brand').val(description);
   
    $('#modal_edit_brand_reception').modal({
        show: true,
        backdrop: 'static'
    });
}

/* Abre una ventana modal para editar marca */
function view_register_brand(element) {
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
    $('#view_name_brand').val(name);
    $('#view_description_brand').val(description);

    $('#modal_view_brand_reception').modal({
        show: true,
        backdrop: 'static'
    });
}

/* Abre una ventana modal para editar marca */
function delete_register_brand(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var name = rowData['nombre'];
    var description = rowData['descripcion'];
    $('#id_brand_reception').val(id);
    $('#edit_name_brand').val(name);
    $('#edit_description_brand').val(description);
   
    $('#modal_edit_brand_reception').modal({
        show: true,
        backdrop: 'static'
    });
}

/*funcion eliminar marca */
function delete_register_brand(element) {
    delete_register_commom(element, 'brand_reception/disable_brand_reception');
}

/*funcion eliminar marca */
function reactivate_register_brand(element) {
    reactivate_register_commom(element, 'brand_reception/enable_brand_reception');
}

/*carga la lista de las marcas al datatable*/
function get_brand_reception_list() {
    var tabla = $('#list_brand_reception').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        'ajax': {
            "url": siteurl('brand_reception/get_brand_reception_list'),
            "type": 'post'
        },
        'columns': [
            {data: 'id'},
            {data: 'nombre'},
            {data: 'descripcion'},
            {data: 'opciones', class: 'text-center'}
        ],
        "columnDefs": [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 3,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(27, 'modal');
                } else {
                    return '<button type="button" onclick="reactivate_register_brand(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
}
