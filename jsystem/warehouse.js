/**
 * Created by Alejandro on 24/7/2017.
 */
$(document).ready(function () {
    get_warehouse_list();
})
$(function () {
    /* Abre una ventana modal para registrar un nuevo almacen */
    $('#btn_new_warehouse').click(function () {
        $('#frm_new_warehouse')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_warehouse').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nuevo almacen se activa esta accion*/
    $('#frm_new_warehouse').submit(function (event) {
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
                    $('#close_modal_new_warehouse').click();
                    update_data_table($('#list_warehouse'));
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
    $('#frm_edit_warehouse').submit(function (event) {
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
                    $('#close_modal_edit_warehouse').click();

                    update_data_table($('#list_warehouse'));
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


/* Abre una ventana modal para editar el almacen */
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
    var direccion = rowData['direccion'];
    var sucursal_id = rowData['sucursal_id'];
    var nombre_sucursal = rowData['nombre_comercial'];
    var tipo_almacen_id = rowData['tipo_almacen_id'];
    var nombre_tipo = rowData['nombre_tipo'];
    $('#id_almacen_edit').val(id);
    $('#nombre_almacen_edit').val(nombre);
    $('#descripcion_almacen_edit').val(descripcion);
    $('#direccion_almacen_edit').val(direccion);
    document.getElementById("sucursal_almacen_edit").options.length = 0;
    document.getElementById("tipo_almacen_almacen_edit").options.length = 0;
    
    $('#sucursal_almacen_edit').append('<option value="' + sucursal_id + '">' + nombre_sucursal + '</option>');
    $('#tipo_almacen_almacen_edit').append('<option value="' + tipo_almacen_id + '">' + nombre_tipo + '</option>');
    get_offices_enable(sucursal_id,'sucursal_almacen_edit');
    get_type_warehouse_enable(tipo_almacen_id);
    $('#modal_edit_warehouse').modal({
        show: true,
        backdrop: 'static'
    });
}

/*Obtines la lista de todos los tipos de almacen para cargar en un combo*/
function get_type_warehouse_enable(type_warehouse_id) {
    $.post(siteurl('warehouse/get_type_warehouse_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                if (type_warehouse_id != item.id) {
                    $('#tipo_almacen_almacen_edit').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                }
            });
        });
}
/*funcion eliminar almacen */
function delete_register(element) {
    delete_register_commom(element, 'warehouse/disable_warehouse');
}
/*Para cargar la lista de almacenes en el dataTable*/
function get_warehouse_list() {
    var tabla = $('#list_warehouse').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('warehouse/get_warehouse_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'sucursal_id', class: 'text-center'},
            {data: 'nombre_comercial', class: 'text-center'},
            {data: 'tipo_almacen_id', class: 'text-center'},
            {data: 'nombre_tipo', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'direccion', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 1,
            visible: false,
            searchable: false
        }, {
            targets: 3,
            visible: false,
            searchable: false
        }, {
            targets: 7,
            visible: false,
            searchable: false
        }, {
            targets: 8,
            render: function (data) {
                return state_crud(data);
            }
        }, {
            targets: 9,
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