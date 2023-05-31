/**
 * Created by Alejandro on 30/8/2017.
 */
$(document).ready(function () {
    get_group_failure_solution_list();
})
$(function () {
    /* Abre una ventana modal para registrar un nuevo tipo servicio */
        $('#btn_new_group_failure_solution').click(function () {

            $('#frm_new_group_failure_solution')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_group_failure_solution').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nuevo tipo servicio se activa esta accion*/
    $('#frm_new_group_failure_solution').submit(function (event) {
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
                    $('#close_modal_new_group_failure_solution').click();
                    update_data_table($('#list_group_failure_solution'));
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

    /*Cuando da clic guardar de modal editar servicio se activa esta accion*/
    $('#frm_edit_group_failure_solution').submit(function (event) {
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
                    $('#close_modal_edit_group_failure_solution').click();

                    update_data_table($('#list_group_failure_solution'));
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


/* Abre una ventana modal para editar tipo servicio */
function edit_register_type(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var nombre = rowData['nombre'];
    var descripcion = rowData['descripcion'];
    $('#id_group_failure_solution_edit').val(id);
    $('#nombre_group_failure_solution_edit').val(nombre);
    $('#descripcion_group_failure_solution_edit').val(descripcion);
    $('#modal_edit_group_failure_solution').modal({
        show: true,
        backdrop: 'static'
    });
}

/*funcion eliminar tipo de servicio */
function delete_register_type(element) {
    delete_register_commom(element, 'group_failure_solution/disable_group_failure_solution');
}

/*carga la lista de los tipo de servicios al datatable*/
function get_group_failure_solution_list() {
    var tabla = $('#list_group_failure_solution').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('group_failure_solution/get_group_failure_solution_list'),
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
                return state_crud(data);
            }
        }, {
            targets: 4,
            orderable:false,
            render: function (data, type, row) {

                if (row.estado != 0) {
                    var botones = '<div class="btn-group">' +
                        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
                        'OPCIONES <span class="caret"></span> ' +
                        '</button><ul class="dropdown-menu">';
                    botones = botones + '<li><a data-toggle="modal" onclick="edit_register_type(this)"><i class="fa fa-edit"></i> Editar</a></li>' +
                        '<li><a data-toggle="modal" onclick="delete_register_type(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                        '</ul> ' +
                        '</div>';
                    return botones;
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

