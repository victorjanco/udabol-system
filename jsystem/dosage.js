
$(document).ready(function () {
    get_inactive_dosage_list();
    get_enable_dosage_list();
    get_caducated_dosage_list();
    nro_inicio();

    $('#frm_new_dosage').submit(function (event) {
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
                        swal('Error', 'Error al registrar los datos.', 'error');
                    }
                }
            }
        });
    });

})

function nro_inicio()
{
    if(document.getElementById("nro_inicio"))
    {
        document.getElementById("nro_inicio").readOnly = true;
    }

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
        data: 'id='+rowData['id'],
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



// Evento que obtiene las impresoras por sucursal
$('#sucursal').change(function () {
    // alert('se selecciono algo.');
    $.ajax({
        url: siteurl('printer/get_printers_by_branch_id'),
        data: 'id=' + $('#sucursal').val(),
        type: 'post',
        success: function (data) {
            $('#impresora').append('<option value="0">Seleccione Impresora</option>');
            var printer_list = JSON.parse(data);
            $.each(printer_list, function (i, item) {
                $('#impresora').append('<option value="' + item.id + '">'+'Marca: ' + item.marca+ ' - '  + 'Modelo: ' +item.serial+'</option>');

            });
        }
    });
});

function activate_dosage(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var asignacion_dosificacion_id= rowData['asignacion_dosificacion_id'];

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
                $.post(site_url + 'dosage/activate_dosage', {asignacion_dosificacion_id: asignacion_dosificacion_id,id:id}).done(function (response) {
                    if (response) {
                        swal("Activado!", "La dosificacion ha sido activada.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "Ya existe una dosificacion Activa para esta sucursal", "error");
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
function get_inactive_dosage_list() {
    var tabla = $('#list_dosage').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('dosage/get_inactive_dosage_list'),
            type: 'post'
        },
        columns: [
            { data: 'id' },
            { data: 'asignacion_dosificacion_id' , class: 'text-center' },
            { data: 'sucursal' , class: 'text-center' },
            {data: 'actividad', class: 'text-center'},
            {data: 'marca', class: 'text-center'},
            { data: 'autorizacion' , class: 'text-center' },
            { data: 'fecha_solicitada', class: 'text-center'  },
            { data: 'fecha_limite', class: 'text-center' },
            { data: 'opciones', class: 'text-center' }
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        },{
            targets: 1,
            visible: false,
            searchable: false
        },

            {
            targets: 8,
            orderable: false,
            render: function (data, type, row) {
                    return '<button type="button" onclick="activate_dosage(this);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > ACTIVAR    ';

            }
        }],
       /* responsive: true,
        pagingType: "full_numbers",
        select: false*/
    });
    tabla.ajax.reload();
}

function get_enable_dosage_list() {

    var tabla = $('#list_enable_dosage').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('dosage/get_enable_dosage_list'),
            type: 'post'
        },
        columns: [
            { data: 'id' },
            { data: 'sucursal' , class: 'text-center' },
            {data: 'actividad', class: 'text-center'},
            {data: 'marca', class: 'text-center'},
            { data: 'autorizacion' , class: 'text-center' },
            { data: 'fecha_solicitada', class: 'text-center'  },
            { data: 'fecha_limite', class: 'text-center' }
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }],
        /* responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}

function get_caducated_dosage_list() {
    var tabla = $('#list_caducated_dosage').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('dosage/get_caducated_dosage_list'),
            type: 'post'
        },
        columns: [
            { data: 'id' },
            { data: 'sucursal' , class: 'text-center' },
            {data: 'actividad', class: 'text-center'},
            {data: 'marca', class: 'text-center'},
            { data: 'autorizacion' , class: 'text-center' },
            { data: 'fecha_solicitada', class: 'text-center'  },
            { data: 'fecha_limite', class: 'text-center' }
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }],
        /* responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}