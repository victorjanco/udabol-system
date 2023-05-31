/**
 * Created by Victor janco on 20/03/2020.
 */
var amount_index=0;
$(document).ready(function () {
    // //var buttons=[{name:'NUEVO', id:'btn_new_cash_income', label:'Nuevo Ingreso de Caja', type:'modal'}];
    // var buttons=[
    //     {name:'NUEVO', href:siteurl("cash_income/new_cash_income"), label:'Nuevo Ingreso de Caja', type:'form'}
    // ];

    // if(typeof options !== 'undefined'){
    //     container_buttons(buttons, options);
        get_cash_income_list();
    // }
    if(typeof cash_id !== 'undefined' && typeof cash_aperture_id !== 'undefined'){
        if(cash_id==false || cash_aperture_id==0){
            console.log(cash_id);
            console.log(cash_aperture_id);
            // alert('CAJA NO APERTURADA');
            swal({
                title: "Caja No aperturada",
                text: "La caja  no fue aperturada",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ok!",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                $.redirect(siteurl("cash_income"));
            });
        }
    }

    /* Abre una ventana modal para agregar un monto nuevo a detalle */
    $('#btn_new_detail_cash_income').click(function () {
        $('#frm_add_cash_income_amount')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_add_cash_income_amount').modal({
            show: true,
            backdrop: 'static'
        });
        $("#container_bank" ).hide();
        get_currency_enable();
        get_payment_type_enable();
        get_bank_origin_enable();
        get_bank_destination_enable();
    });
     /* Abre una ventana de registro de una nueva cuenta bancaria */
     $('#btn_open_modal_bank_account').click(function () {
        $('#frm_new_bank_account')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_new_bank_account').modal({
            show: true,
            backdrop: 'static'
        });
        //get_currency_enable();
        get_account_type_enable();
        get_bank_enable();
    });

    /*Cuando da clic guardar en el formulario de ingreso de caja se activa esta accion*/
    $('#frm_new_cash_income').submit(function (event) {
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
                    // $('#close_modal_new_cash_income').click();
                    location.href = site_url + "cash_income";
                    update_data_table($('#list_cash_income'));
                } else if(respuesta.cash === true){
                    swal({
                        title: "Caja Cerrada",
                        text: "Caja cerrada, aperture nuevamente la caja",
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        $.redirect(siteurl("cash_income"));
                    });
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

    /*Cuando da clic guardar de modal editar se activa esta accion*/
    $('#frm_edit_cash_income').submit(function (event) {
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
                    // $('#close_modal_edit_cash_income').click();
                    // update_data_table($('#list_cash_income'));
                    location.href = site_url + "cash_income";
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

    /*Cuando da clic guardar del formulario de cuenta bancaria se activa esta accion*/
    $('#frm_new_bank_account').submit(function (event) {
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
                    $('#close_modal_new_bank_account').click();
                    // update_data_table($('#list_cash_income'));
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
// delete <TR> row, childElem is any element inside row
function deleteRow(childElem) {
    var row = $(childElem).closest("tr"); // find <tr> parent
    row.remove();
}
/* Abre Formulario de edicion de Ingreso de Caja*/
function edit_register_form(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var tipo_ingreso_caja_id = rowData['tipo_ingreso_caja_id'];

    if (tipo_ingreso_caja_id==1) {
        swal({
            title: "Registro de Venta",
            text: "El Registro no puede ser editado",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ok!",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            $.redirect(siteurl('cash_income'));
        });   
    }else{
        edit_register_commom(element, 'cash_income/edit');  
    }
}

/*funcion eliminar un Ingreso de Caja*/
function delete_register(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var tipo_ingreso_caja_id = rowData['tipo_ingreso_caja_id'];

    if (tipo_ingreso_caja_id==1) {
        swal({
            title: "Registro de Venta",
            text: "El Registro no puede ser eliminado",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ok!",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            $.redirect(siteurl('cash_income'));
        });   
    }else{
        delete_register_commom(element, 'cash_income/disable_cash_income');
    }
}

/*carga la lista de ingresos de caja al datatable*/
function get_cash_income_list() {
    
    var tabla = $('#list_cash_income').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: siteurl('cash_income/get_cash_income_list'),
            type: 'post'
        },
        columns: [
            {data: 'id'},
            {data: 'nro_ingreso', class: 'text-center'},
            {data: 'detalle', class: 'text-center'},
            {data: 'fecha_ingreso', class: 'text-center'},
            {data: 'monto_bs', class: 'text-center'},
            {data: 'monto_sus', class: 'text-center'},
            {data: 'nombre_caja', class: 'text-center'},
            {data: 'fecha_modificacion', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        columnDefs: [{
            targets: 0,
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
                    return  load_buttons_crud(3,'formulario');
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }],
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
}

function view_register(element) {
    view_register_commom(element, 'cash_income/view');
}

function get_cash_enable(cash_id){
    //alert('janco');
    // $('#edit_cash').empty();
    // $('#edit_cash').append('<option value="">Seleccione Caja</option>');
    $('#add_cash').empty();
    $('#add_cash').append('<option value="">Seleccione Caja</option>');
    // $('#view_cash').empty();
    // $('#view_cash').append('<option value="">Seleccione Caja</option>');
    $.post(siteurl('cash/get_cash_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                //console.log(data);
                // $('#edit_cash').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                $('#add_cash').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                // $('#view_cash').append('<option value="' + item.id + '">' + item.nombre + '</option>');

            });
            //$('#edit_cash').val(family_id);
            // document.getElementById("edit_cash").value=cash_id;  
            //console.log($('#edit_cash').val());   
            // document.getElementById("view_cash").value=cash_id;  
            // console.log($('#view_cash').val());   
        });
         
}
function get_cash_income_type_enable(cash_income_type_id){
    //alert('janco');
    // $('#edit_cash_income_type').empty();
    // $('#edit_cash_income_type').append('<option value="">Seleccione Tipo de Ingreso</option>');
    $('#add_cash_income_type').empty();
    $('#add_cash_income_type').append('<option value="">Seleccione Tipo de Ingreso</option>');
    // $('#view_cash_income_type').empty();
    // $('#view_cash_income_type').append('<option value="">Seleccione Tipo de Ingreso</option>');
    $.post(siteurl('cash_income_type/get_cash_income_type_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                //console.log(data);
                // $('#edit_cash_income_type').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                $('#add_cash_income_type').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                // $('#view_cash_income_type').append('<option value="' + item.id + '">' + item.nombre + '</option>');

            });
            //$('#edit_cash_income_type').val(family_id);
            // document.getElementById("edit_cash_income_type").value=cash_income_type_id;  
            //console.log($('#edit_cash_income_type').val());   
            // document.getElementById("view_cash_income_type").value=cash_income_type_id;  
            // console.log($('#view_cash_income_type').val());   
        });
         
}

function get_currency_enable(){
    $('#currency').empty();
    $('#currency_amount').empty();
    $('#currency').append('<option value="">Seleccione Moneda</option>');
    $('#currency_amount').append('<option value="">Seleccione Moneda</option>');
    $.post(siteurl('currency/get_currency_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                $('#currency').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                $('#currency_amount').append('<option value="' + item.id + '">' + item.nombre + '</option>');
            });
            //document.getElementById("currency").value=currency_id; 
        });
}
function get_payment_type_enable(){
    
    $('#payment_type').empty();
    $('#payment_type').append('<option value="">Seleccione Tipo Pago</option>');
    $.post(siteurl('payment_type/get_payment_type_enable'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                $('#payment_type').append('<option value="' + item.id + '">' + item.nombre + '</option>');

            });
            //document.getElementById("payment_type").value=payment_type_id; 
        }); 
}
function get_bank_enable(){
    $('#bank').empty();
    //$('#bank').append('<option value="0">Seleccione Banco</option>');
    $.post(siteurl('bank/get_bank_origin_enable'),
    function (data) {
        var datos = JSON.parse(data);
        $.each(datos, function (i, item) {
            $('#bank').append('<option value="' + item.id + '">' + item.nombre + '</option>');
            // $('#bank_origin').append('<option value="' + item.id + '">' + item.nombre + '</option>');
            // $('#bank_destination').append('<option value="' + item.id + '">' + item.nombre + '</option>');
        });  
    });
}
function get_bank_origin_enable(){
    $('#bank_origin').empty();
    //$('#bank').append('<option value="0">Seleccione Banco</option>');
    $.post(siteurl('bank/get_bank_origin_enable'),
    function (data) {
        var datos = JSON.parse(data);
        $.each(datos, function (i, item) {
            $('#bank_origin').append('<option value="' + item.id + '">' + item.nombre + '</option>');
        });  
        onChangeBank('ORIGEN'); 
    });
}
function get_bank_destination_enable(){
    $('#bank_destination').empty();
    //$('#bank').append('<option value="0">Seleccione Banco</option>');
    $.post(siteurl('bank/get_bank_origin_enable'),
    function (data) {
        var datos = JSON.parse(data);
        $.each(datos, function (i, item) {
            $('#bank_destination').append('<option value="' + item.id + '">' + item.nombre + '</option>');
        }); 
        onChangeBank('DESTINO'); 
    });
}
function get_account_type_enable(){
    $('#account_type').empty();
    $.post(siteurl('account_type/get_account_type_enable'),
    function (data) {
        var datos = JSON.parse(data);
        $.each(datos, function (i, item) {
            $('#account_type').append('<option value="' + item.id + '">' + item.nombre + '</option>');
        });  
    });
}
$('#amount').keypress(function (e) {
    var character = String.fromCharCode(e.keyCode)
    var newValue = this.value + character;
    if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
        e.preventDefault();
        return false;
    }
});
function onKeyPressAmount(e) {
    // console.log(e.target.id);
    var character = String.fromCharCode(e.keyCode)
    var newValue = $('#'+e.target.id).val() + character;
    
    // console.log($('#'+e.target.id).val());
    if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
        e.preventDefault();
        return false;
    }
}
function hasDecimalPlace(value, x) {
    var pointIndex = value.indexOf('.');
    return  pointIndex >= 0 && pointIndex < value.length - x;
}

function onChangePaymentType(){
    var payment_type_name =$( "#payment_type option:selected").text();
    if(payment_type_name.toUpperCase()=='EFECTIVO'){
        $("#container_bank" ).hide();
        $('#currency_amount').empty();
        $.each(bank_account_cash_apertures, function (i, item) {
            $('#currency_amount').append('<option value="' + item.moneda_id + '">' + item.nombre_moneda + '</option>');
        });
    }else{
        $("#container_bank" ).show();
        get_currency_enable();
    }
}

function onChangeBank(type){

    var bank_id = $("#bank_origin").val();
    var tipo=2; //CLIENTE
    if(type=='DESTINO'){
        $('#bank_account_destination').empty();
        bank_id = $("#bank_destination").val();
        tipo=1; //EMPRESA
    }else{
        $('#bank_account_origin').empty();
    }
    
    $.post(siteurl('bank_account/get_bank_accounts'),{bank_id: bank_id, type:tipo},
    function (data) {
        var datos = JSON.parse(data);
        $.each(datos, function (i, item) {
            if(type=='ORIGEN'){
                $('#bank_account_origin').append('<option value="' + item.id + '">' + item.nro_cuenta_bancaria +'-'+ item.nombre_apoderado + '/' + item.simbolo +'</option>');
            }else{
                $('#bank_account_destination').append('<option value="' + item.id + '">' + item.nro_cuenta_bancaria +'-'+ item.nombre_apoderado + '/' + item.simbolo +'</option>');
            }
            
        });  
    });
}


function get_bank_account_cash_aperture(bank_account_cash_origin, moneda_id){
	for (let i = 0; i < bank_account_cash_origin.length; i++) {
		if(bank_account_cash_origin[i].moneda_id==moneda_id){
			return bank_account_cash_origin[i];
		}
	}
}