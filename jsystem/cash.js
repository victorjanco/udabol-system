/**
 * Created by Alejandro on 24/7/2017.
 */

$(document).ready(function () {

    // var buttons=[{name:'NUEVO', id:'btn_new_cash', label:'Nueva Caja', type:'modal'}];

    // container_buttons(buttons, options);
    get_cash_list();

    /* Abre una ventana modal para registrar un nuevo tipo almacen */
    $('#btn_new_cash').click(function () {
        $('#frm_new_cash')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_cash').modal({
            show: true,
            backdrop: 'static'
        });
    });

    /*Cuando da clic guardar de modal nuevo tipo almacen se activa esta accion*/
    $('#frm_new_cash').submit(function (event) {
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
                    $('#close_modal_new_cash').click();
                    update_data_table($('#list_cash'));
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
    $('#frm_edit_cash').submit(function (event) {
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
                    $('#close_modal_edit_cash').click();
                    update_data_table($('#list_cash'));
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

/* Abre una ventana modal para editar tipo almacen */
function edit_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var code = rowData['codigo'];
    var nombre = rowData['nombre'];
    var descripcion = rowData['descripcion'];
    $('#edit_id_cash').val(id);
    $('#edit_code').val(code);
    $('#edit_name').val(nombre);
    $('#edit_description').val(descripcion);

    $("#currency_chek_edit tbody tr").remove();
    $.post(siteurl('cash/get_bank_account_cash_enable'),{id: id},
    function (data) {
        var datos = pluk(JSON.parse(data).bank_account_list,'moneda_id');
        $.each(currencys, function (i, item) {
            if(datos.includes(item.id)){
                var row = "<tr>"+
                "<td><input type='hidden' name='currency[]' value='"+item.id+"'>" + item.nombre + "</td>"+
                "<td><input type='checkbox' checked='checked' class='filled-in' name='currency_check_edit[]' id='currency_check_edit"+item.id+"' value='"+item.id+"'><label for='currency_check_edit"
                +item.id+"'>"+item.simbolo+"</label></td></tr>";
                $("#currency_chek_edit").append(row);
            }else{
                var row = "<tr>"+
                "<td><input type='hidden' name='currency[]' value='"+item.id+"'>" + item.nombre + "</td>"+
                "<td><input type='checkbox' class='filled-in' name='currency_check_edit[]' id='currency_check_edit"+item.id+"' value='"+item.id+"'><label for='currency_check_edit"
                +item.id+"'>"+item.simbolo+"</label></td></tr>";
                $("#currency_chek_edit").append(row);
            }
        });
        if(JSON.parse(data).cash_aperture==true){ //caja aperturada 
            $.each(currencys, function (i, item) {
                $("#currency_check_edit"+item.id).attr("disabled", true);
            });
            swal({
                title: "La Caja Ha Sido Aperturada",
                text: "Los monedas Asignadas no se pueden modificar",
                type: "warning",
                showCancelButton: false,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Ok",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            });
        }
    });

    $('#modal_edit_cash').modal({
        show: true,
        backdrop: 'static'
    });

}

/*funcion eliminar tipo de almacen */
function delete_register(element) {
    delete_register_commom(element, 'cash/disable_cash');
}

/*carga la lista de los tipo de almacenes al datatable*/
function get_cash_list() {
    
    var tabla = $('#list_cash').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('cash/get_cash_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'codigo', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'fecha_modificacion', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        }, {
            targets: 5,
            render: function (data) {
                return state_crud   (data);
            }
        }, {
            targets: 6,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return  load_buttons_crud(2, 'modal');
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

function view_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var code = rowData['codigo'];
    var name = rowData['nombre'];
    var description = rowData['descripcion'];
    $('#view_code').val(code);
    $('#view_name').val(name);
    $('#view_description').val(description);

    $("#currency_chek_view tbody tr").remove();
    $.post(siteurl('cash/get_bank_account_cash_enable'),{id: id},
    function (data) {
        var datos = JSON.parse(data).bank_account_list;
        $.each(datos, function (i, item) {
            var row = "<tr>"+
            "<td><input type='hidden' name='currency[]' value='"+item.moneda_id+"'>" + item.nombre_moneda + "</td>"+
            "<td><input type='checkbox' checked='checked' class='filled-in' name='currency_check[]' id='currency_check"+item.moneda_id+"' value='"+item.moneda_id+"'><label for='currency_check"
            +item.moneda_id+"'>"+item.simbolo+"</label></td></tr>";
            $("#currency_chek_view").append(row);
        });  
    });

    $('#modal_view_cash').modal({
        show: true,
        backdrop: 'static'
    });
}