$(document).ready(function () {
    get_printer_list();
})
$(function () {
    /* Abre una ventana modal para registrar una nueva impresora */
    $('#btn_new_printer').click(function () {
        $('#frm_new_printer')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_printer').modal({
            show: true,
            backdrop: 'static'
        });
    });
    /*Cuando da clic guardar de modal nueva impresora se activa esta accion*/
    $('#frm_new_printer').submit(function (event) {
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
                    $('#close_modal_printer').click();

                    update_data_table($('#list_printer'));
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

    /*Cuando da clic guardar de modal editar impresora se activa esta accion*/
    $('#frm_edit_printer').submit(function (event) {
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
                    $('#close_modal_edit_printer').click();

                    update_data_table($('#list_printer'));
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

/* Abre una ventana modal para editar el almacen */
function edit_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var marca = rowData['marca'];
    var serie = rowData['serial'];
    var nombre = rowData['nombre'];
    var nombre_comercial = rowData['nombre_comercial'];
    var sucursal_id = rowData['sucursal_id'];
    $('#id_printer').val(id);
    document.getElementById("sucursal_edit").options.length = 0;
    $('#sucursal_edit').append('<option value="' + sucursal_id + '">' + nombre_comercial +' - '+ nombre +'</option>');
    get_offices_enable(sucursal_id,'sucursal_edit');
    $('#marca_edit').val(marca);
    $('#serie_edit').val(serie);

    $('#modal_edit_printer').modal({
        show: true,
        backdrop: 'static'
    });
}

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


function activate_dosage(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];

    swal({
            title: "Esta seguro que desea activar la dosificacion?",
            text: "El estado del registro cambiara",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, activar dosificacion!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.post(site_url + 'dosage/activate_dosage', {id: id}).done(function (response) {
                    if (response) {
                        swal("Activado!", "La dosificacion ha sido activada.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "Problemas al Activar", "error");
                    }
                });
            }
            /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
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
function get_printer_list() {
    var tabla = $('#list_printer').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('printer/get_printer_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'nombre', class: 'text-center'},
            {data: 'nombre_comercial', class: 'text-center'},
            {data: 'marca', class: 'text-center'},
            {data: 'serial', class: 'text-center'},
            {data: 'sucursal_id', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 5,
            visible: false,
            searchable: false
        },{
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
        /* responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}