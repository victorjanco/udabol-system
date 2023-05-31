/**
 * Created by Alejandro on 19/7/2017.
 */
$(document).ready(function () {
    get_model_list();
})
$(function () {
    /* Abre una ventana modal para registrar un nuevo modelo */
    $('#btn_new_model').click(function () {
        $('#frm_new_model')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_model').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nuevo modelo se activa esta accion*/
    $('#frm_new_model').submit(function (event) {
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
                    $('#close_modal_new_model').click();

                    update_data_table($('#list_model'));
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

    /*Cuando da clic guardar de modal editar modelo se activa esta accion*/
    $('#frm_edit_model').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        // alert(form.serialize());
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
                    $('#close_modal_edit_model').click();

                    update_data_table($('#list_model'));
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

})


/* Abre una ventana modal para editar modelo */
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
    var marca_id = rowData['marca_id'];
    var nombre_marca = rowData['nombre_marca'];
    $('#id_modelo').val(id);
    $('#nombre_modelo').val(nombre);
    $('#codigo_modelo').val(codigo);
    $('#marca_edit_model').append('<option value="' + marca_id + '">' + nombre_marca + '</option>');
    get_brand_enable(marca_id);
    $('#modal_edit_model').modal({
        show: true,
        backdrop: 'static'
    });


}

/*Obtines la lista de todas las marcas para cargar en un combo*/
function get_brand_enable(marca_id) {
    $.post(siteurl('brand/get_brand_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                if (marca_id != item.id) {
                    $('#marca_edit_model').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                }
            });
        });
}

/*funcion eliminar modelo */
function delete_register(element) {
    delete_register_commom(element, 'model/disable_model');
}

/*carga la lista de los modelos al datatable*/
function get_model_list() {
    var tabla = $('#list_model').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('model/get_model_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'codigo', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'marca_id', class: 'text-center'},
            {data: 'nombre_marca', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 3,
            visible: false,
            searchable: false
        }, {
            targets: 5,
            render: function (data) {
                return state_crud(data);
            }
        }, {
            targets: 6,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(2, 'modal');
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}