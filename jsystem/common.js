/**
 * User: Ariel Alejandro Gomez Chavez ( @ArielGomez )
 */
/* Ejecutar una llamada ajax enviando como parametro por referencia el formulario y los datos finales */
$(document).ready(function () {
    /*Metodo autocompletado de busqueda por NIT*/
    $('.search_nit').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('customer/get_data_customer'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'nit'
                },
                success: function (data) {
                    response($.map(data, function (item, nit) {
                            var data = nit.split(' - ');
                            if (data.length > 1) {
                                return {
                                    label: nit,
                                    value: data[1],
                                    id: item
                                };
                            } else {
                                return {
                                    label: nit,
                                    value: "",
                                    id: item
                                };
                            }
                        }
                    ));
                }
            });
        },
        select: function (event, ui) {
            var Date = (ui.item.id);
            var elem = Date.split('/');
            $('#nombre_factura').val(elem[0]);
            $('#nit').val(elem[2]);
            $('#id_customer').val(elem[1]);
            $('#type_customer').val(elem[4]);
            console.log(elem[4]);
            var type_cutomer = get_type_customer(parseInt(elem[4]));

            $('#type_customer_sale').val(type_cutomer);
        }
    });

    /*Metodo autocompletado de busqueda por Leadder*/
    $('.search_nombre_factura').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('customer/get_data_customer'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'nombre_factura'
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        var data = nombre.split(' - ');
                        if (data.length > 1) {
                            return {
                                label: nombre,
                                value: data[0],
                                id: item
                            };
                        } else {
                            return {
                                label: nombre,
                                value: "",
                                id: item
                            };
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            var Date = (ui.item.id);
            var elem = Date.split('/');
            $('#nombre_factura').val(elem[0]);
            $('#nit').val(elem[2]);
            $('#id_customer').val(elem[1]);
            $('#type_customer').val(elem[4]);
            var type_cutomer = get_type_customer(parseInt(elem[4]));
            $('#type_customer_sale').val(type_cutomer);
        }
    });

    /*Metodo autocompletado de busqueda por CI*/
    $('.search_ci').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('customer/get_data_customer'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'ci'
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        return {
                            label: nombre,
                            value: nombre,
                            id: item
                        };
                    }));
                }
            });
        },
        select: function (event, ui) {
            var Date = (ui.item.id);
            var elem = Date.split('/');
            $('.search_telf1').val(elem[4]);
            $('.search_telf2').val(elem[3]);
            $('.search_nit').val(elem[2]);
            $('.search_name').val(elem[0]);
            $('.search_id_customer').val(elem[1]);
            $('.search_ci').val(ui.item.value);
        }
    });

    $('.search_name').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteurl('customer/get_data_customer'),
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'name'
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        return {
                            label: nombre,
                            value: nombre,
                            id: item
                        };
                    }));
                }
            });
        },
        select: function (event, ui) {
            var Date = (ui.item.id);
            var elem = Date.split('/');
            $('.search_telf1').val(elem[4]);
            $('.search_telf2').val(elem[3]);
            $('.search_nit').val(elem[2]);
            $('.search_ci').val(elem[0]);
            $('.search_id_customer').val(elem[1]);
            $('.search_name').val(ui.item.value);
        }
    });

    $('.frm-event-submit').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        var type = 'guardar';
        if ($('#imprimir').click()) {
            type = 'imprimir'
        }
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success === true) {
                    $('.abm-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    // Si es un vista de formulario
                    if (!$(this).hasClass('frm-noreset')) {
                        $(form)[0].reset();
                    }

                    if ($(this).hasClass('frm-datatable')) {
                        // reload_table();
                    }
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
/************************** CONFIGURACION POR DEFECTO DEL DATATABLE **************************************/
$.extend(true, $.fn.dataTable.defaults, {
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
    responsive: true
});

function update_data_table(tabla) {
    var tabla = tabla.DataTable();
    tabla.ajax.reload();
}

function view_register_commom(elemento, metodo) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl(metodo);
    $.redirect(url, {id: data['id']}, 'POST', '_self');
}

function edit_register_commom(elemento, metodo) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl(metodo);
    $.redirect(url, {id: data['id']}, 'POST', '_self');
}


function delete_register_commom(elemento, metodo) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];

    swal({
            title: "Esta seguro que desea eliminar este registro?",
            text: "El estado del registro cambiara",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminar registro!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                ajaxStart('Eliminando el Registro, por favor espere');
                $.post(site_url + metodo, {id: id}).done(function (response) {
                    ajaxStop();
                    if (response) {
                        swal("Eliminado!", "El registro ha sido eliminado.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "Problemas al eliminar", "error");
                    }
                });
            }
            /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
        });
}

function reactivate_register_commom(elemento, metodo) {
    var table = $(elemento).closest('table').DataTable();
    var fila = $(elemento).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    swal({
            title: "Esta seguro que desea reactivar este registro?",
            text: "El estado del registro cambiara",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, reactivar el registro!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.post(site_url + metodo, {id: id}).done(function (response) {
                    if (response) {
                        swal("Reactivado!", "El registro ha sido reactivado correctamente.", "success");
                        table.ajax.reload();
                    } else {
                        swal("Error", "Problemas al reactivar", "error");
                    }
                });
            }
        });
}

function aprobation_register_commom(id, metodo,salida) {    
    swal({
            title: "Esta seguro que desea aprobar el registro",
            text: "El estado del registro cambiara",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, aprobar el registro!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                ajaxStart('Actulizando estado del registro');
                $.post(site_url + metodo, {id: id})
                .done(function (response) {
                    ajaxStop();
                    if (response) {
                        // swal("Reactivado!", "El registro ha sido aprobado correctamente.", "success");
                        // $.redirect(siteurl(salida));
                        swal({
                            title: "Aprobado!!",
                            text: "El registro ha sido aprobado correctamente",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: false,
                            closeOnCancel: true
                        },
                        function (isConfirm) {
                            $.redirect(siteurl(salida));
                        });
                    } else {
                        swal("Error", "Problemas al aprobar", "error");
                    }
                }).fail(function(response){
                    ajaxStop();
                    // Error catched, do stuff
                    //  alert(response);
                    console.log(response.responseText);
                    // **alert('error; ' + eval(error));**
                    swal('Error', 'Error al registrar los datos.', 'error');
                });
            }
        });
}

function descart_register_commom(id, metodo,salida) {    
    swal({
            title: "Esta seguro que desea descartar el registro",
            text: "El estado del registro cambiara",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, descartar el registro!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                ajaxStart('Actulizando estado del registro');
                $.post(site_url + metodo, {id: id}).done(function (response) {
                    ajaxStop();
                    if (response) {
                        // swal("Reactivado!", "El registro ha sido aprobado correctamente.", "success");
                        swal({
                            title: "Descartado",
                            text: "El registro ha sido descartado correctamente",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: false,
                            closeOnCancel: true
                        },
                        function (isConfirm) {
                            $.redirect(siteurl(salida));
                        });
                        
                    } else {
                        swal("Error", "Problemas al descartar", "error");
                    }
                }).fail(function(response){
                    ajaxStop();
                    console.log(response.responseText);
                    swal('Error', 'Error al registrar los datos.', 'error');
                });
            }
        });
}

/*
 * Metodo que devulve los estado de un registro
 * */
function state_crud(data) {
    var estado = '';
    switch (parseInt(data)) {
        case 0:
            estado = 'ELIMINADO';
            break;
        case 1:
            estado = 'ACTIVO';
            break;
        case 2:
            estado = 'INACTIVO';
            break;

    }
    return estado;
}

/*Metodo que devuelve un nombre apartir de un numero para el caso de uso tipo notificacion*/
function type_notification(data) {
    var type = '';
    switch (parseInt(data)) {
        case 0:
            type = 'Recepcion';
            break;
        case 1:
            type = 'Orden Trabajo';
            break;
    }
    return type;
}

/**
 * Metodo que devuelve la cantidad de botonoes solicitada
 *
 * @cantidad    numerico que indica la cantidad de botones devuelta;
 * @tipo_boton  determina si es boton de modal o formulario.
 *
 * */
function load_buttons_crud(cantidad, tipo_boton) {
    var atributo = '';
    var evento_new = '';
    var evento_edit = '';
    var evento_view = '';
    var evento_view_list = '';
    var evento_print = '';
    var evento_invoice = '';
    var evento_print_invoice = '';
    if (tipo_boton == 'modal') {
        atributo = 'data-toggle="modal"';
        evento_edit = 'edit_register(this)';
        evento_print = 'print_form(this)';
        evento_invoice = 'generate_invoice(this)';
    } else {
        evento_new = 'new_register_form(this)';
        evento_edit = 'edit_register_form(this)';
        evento_view = 'view_register_form(this)';
        evento_view_list = 'view_list(this)';
        evento_print = 'print_form(this)';
        evento_invoice = 'generate_invoice(this)';
        evento_print_invoice = 'print_form_invoice(this)';
    }

    var botones = '<div class="btn-group">' +
        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
        'OPCIONES <span class="caret"></span> ' +
        '</button><ul class="dropdown-menu">';

    switch (cantidad) {
        case 1:
            botones = botones + '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li></ul></div>';
            break;
        case 2:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_edit + '"><i class="fa fa-edit"></i> Editar</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 3:
            botones = botones + '<li><a ' + atributo + ' onclick="view_register(this);"><i class="fa fa-eye"></i> Ver </a></li>' +
                '<li><a ' + atributo + ' onclick="' + evento_edit + '"><i class="fa fa-edit"></i> Editar</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 4:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_view + '"><i class="fa fa-edit"></i> Ver</a></li>' +
                '<li><a ' + atributo + ' onclick="' + evento_edit + '"><i class="fa fa-edit"></i> Editar</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 5:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_edit + '"><i class="fa fa-edit"></i> Editar</a></li>' +
                '<li><a ' + atributo + ' onclick="' + evento_print + '"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 6:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_print + '"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '<li><a ' + atributo + ' onclick="' + evento_invoice + '"><i class="fa fa-file-text"></i> Facturar</a></li>' +
                '<li><a onclick="sale_view(this);"><i class="fa fa-eye"></i> Ver</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Anular</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 10:
            botones = botones + '<li><a ' + atributo + ' onclick="reactivate_register(this);"><i class="fa fa-check"></i> Reactivar</a></li></ul></div>';
            break;
        case 11:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_view + '"><i class="fa fa-eye"></i> Ver</a></li>' +
                // '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 12:
            botones = botones + '<li><a onclick="sale_view(this);"><i class="fa fa-eye"></i> Ver</a></li>' +
                '</ul> ' +
                '</div>';
            break;
        case 13:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_print + '"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 14:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_new + '"><i class="fa fa-plus"></i> Nuevo Pago</a></li>' +
                '<li><a ' + atributo + ' onclick="' + evento_view_list + '"><i class="fa fa-times"></i> Ver Pagos</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 15:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_print + '"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '<li><a onclick="sale_view(this);"><i class="fa fa-eye"></i> Ver</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Anular</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 16:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_view + '"><i class="fa fa-eye"></i> Ver</a></li>' +
                '<li><a ' + atributo + ' onclick="' + evento_print + '"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 17:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_view + '"><i class="fa fa-edit"></i> Ver</a></li>' +
                '<li><a ' + atributo + ' onclick="' + evento_edit + '"><i class="fa fa-edit"></i> Editar</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                // '<li><a onclick="add_gallery_product(this);"><i class="fa fa-image"></i> Agregar Fotos </a></li>' +
                "<li><a onclick='imprimir_codigo(this)' target='_blank'><i class='fa fa-print'></i> Imprimir </a></li>" +
                '</ul> ' +
                '</div>';
            break;
        case 18:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_new + '"><i class="fa fa-plus"></i> Motivo</a></li>' +
                '</ul> ' +
                '</div>';
            break;
        case 19:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_print_invoice + '"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '<li><a onclick="invoice_sale_view(this);"><i class="fa fa-eye"></i> Ver</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register_invoice(this);"><i class="fa fa-times"></i> Anular</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 20:
            botones = botones +
                '<li><a ' + atributo + ' onclick="' + evento_print + '"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 21:
            botones = botones +
                '<li><a ' + atributo + ' onclick="activate_product(this);"><i class="fa fa-times"></i> Activar</a></li> ' +
                '<li><a ' + atributo + ' onclick="delete_product(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 22:
            botones = botones +
                '<li><a ' + atributo + ' onclick="imprimir_codigo(this)"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '<li><a ' + atributo + ' onclick="disable_product(this);"><i class="fa fa-times"></i> Desactivar</a></li> ' +
                '<li><a ' + atributo + ' onclick="preci_product(this);"><i class="material-icons">cached</i> Precios</a></li> ' +
                '<li><a ' + atributo + ' onclick="updated_stock_product(this);"><i class="material-icons">cached</i> Stock</a></li> ' +
                // '<li><a ' + atributo + ' onclick="delete_product(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 23:
            botones = botones +
                // '<li><a ' + atributo + ' onclick="imprimir_codigo(this)"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '<li><a ' + atributo + ' onclick="activate_product(this);"><i class="fa fa-times"></i> Activar</a></li> ' +
                '<li><a ' + atributo + ' onclick="delete_product(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 24:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_view + '"><i class="fa fa-eye"></i> Ver</a></li>' +
                '<li><a ' + atributo + ' onclick="aprobation_register(this);"><i class="fa fa-times"></i> Aprobacion</a></li> ' +
                // '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 25:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_view + '"><i class="fa fa-eye"></i> Ver</a></li>' +
                '<li><a ' + atributo + ' onclick="' + evento_print + '"><i class="fa fa-print"></i> Imprimir</a></li>' +
                '</ul> ' +
                '</div>';
            break;
        case 26:
            botones = botones + '<li><a ' + atributo + ' onclick="' + evento_view + '"><i class="fa fa-eye"></i> Ver</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
        case 27:
            botones = botones + '<li><a ' + atributo + ' onclick="view_register_brand(this);"><i class="fa fa-eye"></i> Ver </a></li>' +
                '<li><a ' + atributo + ' onclick="edit_register_brand(this);"><i class="fa fa-edit"></i> Editar</a></li>' +
                '<li><a ' + atributo + ' onclick="delete_register_brand(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
                '</ul> ' +
                '</div>';
            break;
    }
    return botones;
}

/*
 * (Factorizado)Metodo generico que devuelve un objeto JSON de los grupos
 * de dispositivos para ser utilizado en la funcion que lo invoca
 * */
function get_groups_devices() {
    var datos = null;
    $.ajax({
        url: siteurl('group/get_groups'),
        async: false,
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}

/*
 * (Factorizado)Devuelve un objeto JSON de las categorias de dispositivos
 * para ser utilizado en la funcion que lo llama
 * */
function get_categorys_devices() {
    var datos = null;
    $.ajax({
        url: siteurl('category/get_categorys'),
        async: false,
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}

/*
 * (Factorizado) Devuelve un objeto JSON de las marcas de dispositivos
 * para ser utilizado en la funcion que lo llama
 * */
function get_brands_devices() {
    var datos = null;
    $.ajax({
        url: siteurl('brand_reception/get_brand_reception_enable'),
        async: false,
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}

/*
 * (Factorizado) Devuelve un objeto JSON de las marcas de dispositivos
 * para ser utilizado en la funcion que lo llama
 * */
function get_models_devices() {
    var datos = null;
    $.ajax({
        url: siteurl('model_reception/get_model_reception_enable'),
        async: false,
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}

/*
 * (Factorizado) Devuelve un objeto JSON de las categorias de dispositivos
 * para ser utilizado en la funcion que lo llama
 * */
function get_customer_devices() {
    var datos = null;
    $.ajax({
        url: siteurl('customer/get_customer_devices'),
        async: false,
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}

/*
 * (Factorizado) Devuelve un objeto JSON de los colores
 * para ser utilizado en la funcion que lo llama
 * */
function get_colors() {
    var datos = null;
    $.ajax({
        url: siteurl('reception/get_colors'),
        async: false,
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}

/*
 * (Factorizado) Devuelve un objeto JSON de las fallas de dispositivos
 * para ser utilizado en la funcion que lo llama
 * */
function get_failures() {
    var datos = null;
    $.ajax({
        url: siteurl('failure/get_failure_enable'),
        async: false,
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}

/*
 * (Factorizado) Devuelve un objeto JSON de las fallas de dispositivos
 * para ser utilizado en la funcion que lo llama
 * */
function get_solutions() {
    var datos = null;
    $.ajax({
        url: siteurl('solution/get_solution_enable'),
        async: false,
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}


/*
 * Devuelve un objeto JSON de las referencias registradas
 * para ser utilizado en la funcion que lo llama
 * */
function get_references() {
    var datos = null;
    $.ajax({
        url: siteurl('reference/get_references_enable'),
        async: false,
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}

/*Obtines la lista de todas la sucursal para cargar en un combo menos el id que tiene el parametro*/
function get_offices_enable(office_id, name_input) {
    $.post(siteurl('company/get_offices'),
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                if (office_id != item.id) {
                    $('#' + name_input).append('<option value="' + item.id + '">' + item.nombre_comercial + ' - ' + item.nombre + '</option>');
                }
            });
        });
}

/*Obtencion de lista de almacenes por sucursal especifica id*/
function get_warehouse_by_branch_office_id(branch_office_id) {
    var datos = null;
    $.ajax({
        url: siteurl('warehouse/get_warehouse_by_branch_office_id'),
        async: false,
        type: 'post',
        data: {branch_office_id: branch_office_id},
        success: function (data) {
            datos = JSON.parse(data);
        }
    });
    return datos;
}

function get_type_customer(type_customer) {
    var type_customer_response = '';
    switch (type_customer) {
        case 0:
            type_customer_response = "Cliente normal";
            break;
        case 1:
            type_customer_response = "Cliente por Mayor";
            break;
        case 2:
            type_customer_response = "Cliente Expres";
            break;
        case 3:
            type_customer_response = "Cliente Laboratorio";
            break;
    }
    return type_customer_response;
}

// Validacion de caracteres al taipear
//#region TAIPEO DE CARACTERES

function alphabets_numbers_point(e) {/*Funcion literales y solo algunos caracteres permitidos*/
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "abcdefghijklmnñopqrstuvwxyz-/@._#";
    especiales = [09, 32, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 08, 17, 18, 19, 20, 37, 38, 39, 40]; //,37,39,46,8, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57
    tecla_especial = false;
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if (letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}

function numbers_point(e) {/*Funcion que devuelve solo numeros y punto*/
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "";
    especiales = [09, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 14, 08, 37, 38, 39, 40]; //,37,39,46,8, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57
    tecla_especial = false
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if (letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}

function numbers_letters(e) {/*Funcion literales y numeros */
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "abcdefghijklmnñopqrstuvwxyz";
    especiales = [09, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 08, 17, 18, 19, 20, 37, 38, 39, 40]; //,37,39,46,8, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57
    tecla_especial = false;
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if (letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}

function numbers_letters_space(e) {/*Funcion literales y numeros */
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "abcdefghijklmnñopqrstuvwxyz";
    especiales = [09, 32, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 08, 17, 18, 19, 20, 37, 38, 39, 40]; //,37,39,46,8, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57
    tecla_especial = false;
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if (letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}

function ajaxStart(text) {
    if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading') {
        jQuery('body').append('<div id="resultLoading" style="display:none"><div><i class="fa fa-cog fa-spin fa-4x fa-fw"></i><div>' + text + '</div></div><div class="bg"></div></div>');
    }

    jQuery('#resultLoading').css({
        'width': '100%',
        'height': '100%',
        'position': 'fixed',
        'z-index': '10000000',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto'
    });

    jQuery('#resultLoading .bg').css({
        'background': '#000000',
        'opacity': '0.7',
        'width': '100%',
        'height': '100%',
        'position': 'absolute',
        'top': '0'
    });

    jQuery('#resultLoading>div:first').css({
        'width': '250px',
        'height': '75px',
        'text-align': 'center',
        'position': 'fixed',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto',
        'font-size': '16px',
        'z-index': '10',
        'color': '#ffffff'

    });

    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeIn(300);
    jQuery('body').css('cursor', 'wait');
}

//
function ajaxStop() {
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(300);
    jQuery('body').css('cursor', 'default');
}

function print(id, url) {
    var page = site_url + url;
    var params = {
        id: id
    };
    var body = document.body;
    form = document.createElement('form');
    form.method = 'POST';
    form.action = page;
    form.name = 'jsform';
    form.target = '_blank';
    for (index in params) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = index;
        input.id = index;
        input.value = params[index];
        form.appendChild(input);
    }
    body.appendChild(form);
    form.submit();
    return false;
}
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
