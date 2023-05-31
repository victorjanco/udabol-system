/**
 * Created by Alejandro on 12/7/2017.
 */
$(document).ready(function () {
    get_customer_list();

    $('#frm_new_customer').submit(function (event) {
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
                    $('.abm-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    setTimeout(function () {
                        location.href = site_url + "customer/";
                    }, 1500);
                    update_data_table($('#list_customer'));
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

    $('#frm_edit_customer').submit(function (event) {
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
                    $('.abm-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    setTimeout(function () {
                        location.href = site_url + "customer/";
                    }, 1500);
                    update_data_table($('#list_customer'));
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

/*funcion para ver los datos del cliente*/
function view_register(element) {

    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    $.ajax({
        url: siteurl('customer/get_customer_id'),
        type: 'post',
        data: 'id=' + rowData['id'],
        dataType: 'json',
        success: function (response) {
            $('#ci_cliente_view').val(response.ci);
            $('#nit_cliente_view').val(response.nit);
            $('#nombre_cliente_view').val(response.nombre);
            $('#nombre_factura_cliente_view').val(response.nombre_factura);
            $('#telefono1_cliente_view').val(response.telefono1);
            $('#telefono2_cliente_view').val(response.telefono2);
            $('#direccion_cliente_view').val(response.direccion);
            $('#email_cliente_view').val(response.email);
        }
    });

    $('#modal_view_customer').modal({
        show: true,
        backdrop: 'static'
    });
}

/*funcion para editar al cliente*/
function edit_register_form(element) {
    edit_register_commom(element, 'customer/edit_customer');
}

/*funcion eliminar al cliente*/
function delete_register(element) {
    delete_register_commom(element, 'customer/disable_customer');
}

/*carga la lista de los clientes al datatable*/
function get_customer_list() {
    var tabla = $('#list_customer').DataTable({
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
            {data: 'ci', class: 'text-center'},
            // {data: 'nit', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'tipo_cliente', class: 'text-center'},
            {data: 'telefono1', class: 'text-center'},
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
                return get_type_customer(parseInt(data));
            }
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
                    return load_buttons_crud(3, 'formulario');
                } else {
                    return '<button type="button" onclick="activate_customer(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR';
                }
            }
        }],
        /* responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}

function activate_customer(elemento) {
    reactivate_register_commom(elemento, 'customer/activate_customer');
}
