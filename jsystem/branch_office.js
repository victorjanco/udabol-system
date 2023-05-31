/**
 * Created by Alejandro on 18/7/2017.
 */
$(document).ready(function () {
    get_branch_office_list();

})
$(function () {
    /* Abre una ventana modal para registrar una nueva sucursal */
    $('#btn_new_branch_office').click(function () {
        get_department_new();
        $('#frm_new_branch_office')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_branch_office').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nueva marca se activa esta accion*/
    $('#frm_new_branch_office').submit(function (event) {
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
                    $('#close_modal_branch_office').click();

                    update_data_table($('#list_branch_office'));
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
    });

    /*Cuando da clic guardar de modal editar marca se activa esta accion*/
    $('#frm_edit_branch_office').submit(function (event) {
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
                    $('#close_modal_edit_branch_office').click();

                    update_data_table($('#list_branch_office'));
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
function view_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    $.ajax({
        url: siteurl('company/get_branch_office_id'),
        type: 'post',
        data: 'id=' + id,
        dataType: 'json',
        success: function (response) {
            var tipo = ''
            if (response.tipo_sucursal == 1) {
                tipo = 'Sucursal Propia';
            } else {
                tipo = 'Franquicia';
            }
            ;
            $('#tipo_view').val(tipo);
            $('#nit_view').val(response.nit);
            $('#sucursal_sin_view').val(response.nombre);
            $('#sucursal_comercial_view').val(response.nombre_comercial);
            $('#sucursal_telefono_view').val(response.telefono);
            $('#sucursal_direccion_view').val(response.direccion);
            $('#sucursal_correo_view').val(response.correo);
            $('#departamento_view').val(response.ciudad);
            $('#ciudad_impuestos_view').val(response.ciudad_impuestos);
            $('#url_view').val(response.web);
        }
    });

    $('#modal_view_branch_office').modal({
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
    $.ajax({
        url: siteurl('company/get_branch_office_id'),
        type: 'post',
        data: 'id=' + id,
        dataType: 'json',
        success: function (response) {
            $('#id_branch_office').val(response.id);
            get_type_edit(response.tipo_sucursal);
            $('#nit_edit').val(response.nit);
            $('#sucursal_sin_edit').val(response.nombre);
            $('#sucursal_comercial_edit').val(response.nombre_comercial);
            $('#sucursal_telefono_edit').val(response.telefono);
            $('#sucursal_direccion_edit').val(response.direccion);
            $('#sucursal_correo_edit').val(response.correo);
            $('#departamento_edit').append('<option value="' + response.ciudad + '">' + response.ciudad + '</option>');
            get_department_edit(response.ciudad);
            $('#ciudad_impuestos_edit').val(response.ciudad_impuestos);
            $('#url_edit').val(response.web);
        }
    });

    $('#modal_edit_branch_office').modal({
        show: true,
        backdrop: 'static'
    });
}

function get_department() {
    var data = ['Santa Cruz', 'Cochabamba', 'La Paz', 'Tarija', 'Chuquisaca', 'Oruro', 'Potosi', 'Beni', 'Pando'];
    return data;
}

function get_department_new() {
    $.each(get_department(), function (i, item) {
        $('#departamento').append('<option value="' + item + '">' + item + '</option>');
    });
}

function get_department_edit(department) {
    $.each(get_department(), function (i, item) {
        if (department != item) {
            $('#departamento_edit').append('<option value="' + item + '">' + item + '</option>');
        }
    });
}

function get_type_edit(type) {
    if (type == 1) {
        $('#tipo_edit').append('<option value="1">Sucursal Propia</option>');
        $('#tipo_edit').append('<option value="2">Franquicia</option>');
    }else {
        $('#tipo_edit').append('<option value="2">Franquicia</option>');
        $('#tipo_edit').append('<option value="1">Sucursal Propia</option>');
    }
}



/*funcion eliminar marca */
function delete_register(element) {
    delete_register_commom(element, 'company/delete_office');
}

/*funcion eliminar marca */
function reactivate_register(element) {
    reactivate_register_commom(element, 'company/reactivate_branch_office');
}

/*carga la lista de las marcas al datatable*/
function get_branch_office_list() {
    var tabla = $('#list_branch_office').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        'ajax': {
            "url": siteurl('company/get_branch_office_list'),
            "type": 'post'
        },
        'columns': [
            {data: 'id'},
            {data: 'nit'},
            {data: 'nombre'},
            {data: 'nombre_comercial'},
            {data: 'telefono'},
            {data: 'direccion'},
            {data: 'ciudad', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        "columnDefs": [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 7,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(3, 'modal');
                } else {
                    return load_buttons_crud(10, 'modal');
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
}
