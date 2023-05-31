/**
 * Created by mendoza on 07/08/2017.
 */
$(document).ready(function () {
    get_provider_list();
});

$(function () {
    /* Abre una ventana modal para registrar un nuevo proveedor */
    $('#btn_new_provider').click(function () {
        $('#frm_new_provider')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_provider').modal({
            show: true,
            backdrop: 'static'
        });
    });

    $('.frm_provider').submit(function (event) {
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
                    $('.close_modal_provider').click();

                    update_data_table($('#provider_list'));
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
    
});

/* Abre una ventana modal para editar el proveedor */
function edit_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var nombre = rowData['nombre'];
    var nit = rowData['nit'];
    var direccion = rowData['direccion'];
    var telefono = rowData['telefono'];
    var contacto = rowData['contacto'];
    $('#id_edit').val(id);
    $('#nombre_edit').val(nombre);
    $('#nit_edit').val(nit);
    $('#direccion_edit').val(direccion);
    $('#telefono_edit').val(telefono);
    $('#contacto_edit').val(contacto);
    
    $('#modal_edit_provider').modal({
        show: true,
        backdrop: 'static'
    });


}

/*Para cargar la lista de proveedores en el dataTable*/
function get_provider_list() {
    var tabla = $('#provider_list').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('provider/get_provider_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'nombre', class: 'text-center'},
            {data: 'nit', class: 'text-center'},
            {data: 'direccion', class: 'text-center'},
            {data: 'telefono', class: 'text-center'},
            {data: 'contacto', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 6,
            render: function (data) {
                return state_crud(data);
            }
        }, {
            targets: 7,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(2, 'modal');
                } else {
                    return '<button type="button" onclick="activate_provider(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}

/*funcion eliminar proveedor */
function delete_register(element) {
    delete_register_commom(element, 'provider/disable_provider');
}

function activate_provider(elemento) {
    reactivate_register_commom(elemento, 'provider/activate_provider');
}