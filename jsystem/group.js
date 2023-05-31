/**
 * Created by Alejandro on 18/7/2017.
 */
$(document).ready(function () {
    get_group_list();
});

$(function () {
    /* Abre una ventana modal para registrar un nueva marca */
    $('#btn_new_brand').click(function () {
        $('#frm_new')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nueva marca se activa esta accion*/
    $('#frm_new').submit(function (event) {
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
                console.log(respuesta);
                if (respuesta.success === true) {
                    $('.modal-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    $('#close_modal').click();

                    update_data_table($('#list'));
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

    /*Cuando da clic guardar de modal editar marca se activa esta accion*/
    $('#frm_edit').submit(function (event) {
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
                    $('#close_modal_edit').click();

                    update_data_table($('#list'));
                } else if (respuesta.login === true) {
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

});


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
    var descripcion = rowData['descripcion'];
    $('#id').val(id);
    $('#nombre_grupo_edit').val(nombre);
    $('#descripcion_grupo_edit').val(descripcion);
    $('#modal_edit').modal({
        show: true,
        backdrop: 'static'
    });
}

/*funcion eliminar marca */
function delete_register(element) {
    delete_register_commom(element, 'group/disable');
}

function redirect_subgroup(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    $.redirect(site_url + 'group/sub_group', {
        id: id
    });
}

/*carga la lista de las marcas al datatable*/
function get_group_list() {
    ELIMINAR = 2;
    var tabla = $('#list').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        'ajax': {
            "url": siteurl('group/get_groups_list'),
            "type": 'post'
        },
        'columns': [
            {data: 'id'},
            {data: 'nombre', class: 'text-center'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        "columnDefs": [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 1,
            visible: true,
            searchable: true
        }, {
            targets: 3,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado !== 0) {
                    return load_options(ELIMINAR, 'modal');
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }]
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();

}


function load_options(cantidad, tipo_boton) {
    var atributo = '';
    var evento_edit = '';
    if (tipo_boton == 'modal') {
        atributo = 'data-toggle="modal"';
        evento_edit = 'edit_register(this)';
    } else {
        evento_edit = 'edit_register_form(this)';
    }
    var botones = '<div class="btn-group">' +
        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
        'OPCIONES <span class="caret"></span> ' +
        '</button><ul class="dropdown-menu">';

    botones = botones + '<li><a ' + atributo + ' onclick="' + evento_edit + '"><i class="fa fa-edit"></i> Editar</a></li>' +
        '<li><a ' + atributo + ' onclick="redirect_subgroup(this);"><i class="fa fa-list-alt"></i> Subgrupo</a></li> ' +
        '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
        '</ul> ' +
        '</div>';
    return botones;
}
