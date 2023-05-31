/**
 * Created by Alejandro on 14/7/2017.
 */
$(document).ready(function () {
    get_solution_list();
})
$(function () {
    /* Abre una ventana modal para registrar un nueva solucion */
    $('#btn_new_solution').click(function () {
        $('#frm_new_solution')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_solution').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal editar tipo almacen se activa esta accion*/
    $('#frm_new_solution').submit(function (event) {
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
                    $('#close_modal_new_solution').click();

                    update_data_table($('#list_solution'));
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

    /*Cuando da clic guardar de modal editar solucion se activa esta accion*/
    $('#frm_edit_solution').submit(function (event) {
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
                    $('#close_modal_edit_solution').click();

                    update_data_table($('#list_solution'));
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


/* Abre una ventana modal para editar solucion */
function edit_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var nombre = rowData['nombre'];
    var descripcion = rowData['descripcion'];
    $('#id_solucion').val(id);
    $('#nombre_solucion').val(nombre);
    $('#descripcion_solucion').val(descripcion);
    $('#modal_edit_solution').modal({
        show: true,
        backdrop: 'static'
    });
}
/*funcion eliminar solucion */
function delete_register(element) {
    delete_register_commom(element, 'solution/disable_solution');
}

/*funcion activar servicio */
function activate_solution(element) {
    reactivate_register_commom(element, 'solution/enable_solution');
}

/*carga la lista de las soluciones al datatable*/
function get_solution_list() {
    var tabla = $('#list_solution').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('solution/get_solution_list'),
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
                    return load_buttons_crud(2,'modal');
                } else {
                    return '<button type="button" onclick="activate_solution(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                }
            }
        }],
        /*responsive: true,
        pagingType: "full_numbers",
        select: false*/
    });
    tabla.ajax.reload();
}