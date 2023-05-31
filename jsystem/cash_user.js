/**
 * Created by Victor Janco on 08/04/2020.
 */
$(document).ready(function () {
    // /**
    //  * @buttons array de informacion de botones que tendra en CU, Estos seran creados en la funcion container_buttons()
    //  * @options_permission array de opciones habilitadas que tendra el CU, las cuales se les asignara en la funcion get_cash_users_list()
    //  */
    // var buttons=[
    //     // {name:'NUEVO', href:siteurl("cash_user/new_cash_user"), label:'Nuevo Usuario Caja', type:'form'}
    // ];

    // if(typeof options_permission !== 'undefined'){
    //     container_buttons(buttons, options_permission);
        get_cash_users_list();
    // }

    //--------------------------------------------------

    $('#frm_new_cash_user').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        var type = 'guardar';
        // if ($('#imprimir').click()) {
        //     type = 'imprimir'
        // }
        ajaxStart('Guardando registros, por favor espere');
        $.ajax({
            url: siteurl('cash_user/register_cash_user'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                console.log(respuesta);
                if (respuesta.success === true) {
                    $('.abm-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    // Si es un vista de formulario
                    // if (!$(this).hasClass('frm-noreset')) {
                    //     $(form)[0].reset();
                    // }

                    // if ($(this).hasClass('frm-datatable')) {
                        reload_table();
                    // }
                } else if(respuesta.login === true){
                    login_session();
                } else {
                    $('.abm-error').remove();
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

    $('#frm_edit_cash_user').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        var type = 'guardar';
        if ($('#imprimir').click()) {
            type = 'imprimir'
        }
        ajaxStart("Guardando...");
        $.ajax({
            url: siteurl('cash_user/edit_cash_user'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta.success === true) {
                    $('.abm-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    location.href = site_url + "cash_user";
                } else if(respuesta.login === true){
                    login_session();
                } else {
                    $('.abm-error').remove();
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

function get_cash_users_list(options) {
    var tabla =$('#cash_users_list').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'ajax': {
            "url": siteurl('cash_user/get_cash_users_list'),
            "type": "post",
            // dataSrc: ''
        },
        'columns': [
            {data: 'id'},
            {data: 'ci', class: 'text-center'},
            {data: 'nombre'},
            {data: 'telefono', class: 'text-center'},
            {data: 'usuario', class: 'text-center'},
            {data: 'cargo', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            }, {
                targets: 6,
                render: function (data) {
                    return state_crud(data);
                }
            },
            {
                targets: 7,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado != 0) {
                        return load_buttons_crud(2, 'formulario');
                    } else {
                        return '<label>No tiene Opciones</label>';
                    }
                }
            }
        ],
        "order": [[0, "asc"]]
    });
    tabla.ajax.reload();
}

function get_cash_user_enable(option){
    var user_id = $("#user_id").val();
    // $('#table_detail_cash_user').DataTable().clear().draw();

    var tabla =$('#table_detail_cash_user').DataTable({
        'paging': true,
        'info': true,
        'filter': false,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'ajax': {
            "url": siteurl('cash_user/get_cash_user_enable'),
            "type": "post",
            "data":{user_id: user_id}
        },
        'columns': [
            {data: 'id'},
            {data: 'usuario', class: 'text-center'},
            {data: 'caja', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 3,
                orderable: false,
                render: function (data, type, row) {
                    return option;
                }
            }
        ],
         "order": [[0, "asc"]]
    });
    // tabla.ajax.reload();
}
function reload_table(){
    var tabla =$('#table_detail_cash_user').DataTable();
    tabla.ajax.reload();
}
// delete <TR> row, childElem is any element inside row
function deleteRow(element) {
    delete_register_commom(element,'cash_user/delete_cash_user')
}
function view_register_form(element) {
    view_register_commom(element, 'cash_user/view');
}
function edit_register_form(element) {
    edit_register_commom(element, 'cash_user/edit');
}

function delete_register(element) {
    //delete_register_commom(element,'cash_user/disable_cash_user')
}
