/**
 * Created by Ariel on 17/07/2017.
 */
$(document).ready(function () {
    get_branch_office_list();

    $('#ciudad').hide();

    $('#departamento').change(function () {
        if ($(this).val() != '0') {
            $('#ciudad').show();
            $('#ciudad').focus();
        } else {
            $('#ciudad').hide();
        }
    });
});

$('#frm_registrar_sucursal').submit(function (event) {
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
                $('.error').remove();
                swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                $('#close_modal_registro_sucursal').click();

                update_data_table($('#lista_sucursal'));
            } else {
                $('.error').remove();
                if (respuesta.messages !== null) {
                    console.log(respuesta.messages)
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

$('#frm_editar_sucursal').submit(function (event) {
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
                $('.error').remove();
                swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                $('#close_modal_editar_sucursal').click();

                update_data_table($('#lista_sucursal'));
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

function open_modal_office() {
    $('.error').empty();
    $('#frm_registrar_sucursal')[0].reset();
    $('#modal_registro_sucursal').modal({
        show: true,
        backdrop: 'static'
    });
}


/*carga la lista de los clientes al datatable*/
function get_branch_office_lisat() {
    var tabla = $('#list_branch_office');
    tabla.DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('customer/get_customer_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'nombre'},
            {data: 'nombre_comercial'},
            {data: 'telefono'},
            {data: 'direccion'},
            {data: 'correo'},
            {data: 'ciudad'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 4,
            render: function (data) {
                return get_type_customer(parseInt(data));
            }
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
                    return load_buttons_crud(3, 'formulario');
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }],
        /* responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
}

function edit_register(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var subpadre = $(fila).parent('tbody');
    var padre = $(subpadre).parent('table').closest();
    var id = padre.prevObject[0].id;
    console.log(id)
    var rowData = table.row(fila).data();
    $('#id_sucursal').val(rowData['id']);
    $('#sucursal_sins').val(rowData['nombre']);
    $('#sucursal_comercials').val(rowData['nombre_comercial']);
    $('#sucursal_telefonos').val(rowData['telefono']);
    $('#sucursal_direccions').val(rowData['direccion']);
    $('#sucursal_correos').val(rowData['correo']);
    var ciudad = rowData['ciudad'];
    var dato_ciudad = ciudad.split(' - ');
    $('#departamentos').val(dato_ciudad[0]);
    $('#ciudads').val(dato_ciudad[1]);

    $('#modal_editar_sucursal').modal({
        show: true,
        backdrop: 'static'
    });
}

function delete_register_office(elemento) {
    delete_register_commom(elemento,'company/delete_office')
}

