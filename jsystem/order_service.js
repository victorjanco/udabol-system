/**
 * Created by Ariel on 23/08/2017.
 */
//#region DOCUMENT READY
$(document).ready(function () {
    get_list_order_work();
    /*Cargado del listaado*/
    load_solutions();
    /* carga las soluciones que se le asignarion en la recepcion   */
    load_failures();
    /* carga las fallas que se le asignarion en la recepcion   */
    load_devices_customer();
    /* carga el dispositivo*/
    delete_row_product();
    delete_row_service();
});
//#endregion

//#region EVENTOS
$(document).ready(function () {
    /*Evento Submit de agregar servicio */
    $('#frm_add_row_service').submit(function (event) {
        event.preventDefault();
        add_row_service();
    });

    /*Evento Submit de agregar servicio */
    $('#frm_add_row_product').submit(function (event) {
        event.preventDefault();
        add_row_product();
    });

    /* Evento de seleccion en SERVICIO  */
    $("#service_work").change(function () {
        $("#price_work").val("");
        if ($("#service_work").val() !== "") {
            $.ajax({
                url: siteurl('service/get_service_price'),
                data: {id: $("#service_work").val()},
                type: 'post',
                success: function (data) {
                    $('#gama_work').empty();
                    $('#gama_work').append('<option value="">Seleccione una opcion</option>');
                    var data = JSON.parse(data);
                    object_service = data;
                    $.each(data, function (i, item) {
                        $('#gama_work').append('<option value="' + item.id + '" data-price="' + item.precio_servicio + '">' + item.nombre + '</option>');
                    });
                }
            });
        } else {
            $("#gama_work").empty();
            var html_option = '';
            html_option = '<option value="">Seleccione una opcion</option>'
            $("#gama_work").append(html_option);
        }
    })

    /* Evento de seleccion en GAMA*/
    $("#gama_work").change(function () {
        if ($("#gama_work").val() !== "") {
            $("#price_work").val($("#gama_work option:selected").data("price"));
        } else {
            $("#price_work").val("");
        }
    })

    /* Evento de cambio de marca de producto*/
    $("#brand_product").change(function () {
        //$("#price_work").val("");
        if ($("#brand_product").val() !== "") {
            $.ajax({
                url: siteurl('model/get_model_by_brand'),
                data: {marca_id: $("#brand_product").val()},
                type: 'post',
                success: function (data) {
                    $('#model_product').empty();
                    $('#model_product').append('<option value="">Seleccione una opcion</option>');
                    var data = JSON.parse(data);
                    object_service = data;
                    $.each(data, function (i, item) {
                        $('#model_product').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                    });
                }
            });
        } else {
            $('#model_product').empty();
        }
    })

    /*autocompletado para la busqueda por el nombre del producto apartir de sucursal_id, almacen_id y tipo_producto_id*/
    $('.search_name_product_sale').autocomplete({
        source: function (request, response) {
            var model_product = $('#model_product').val();
            $.ajax({
                url: siteurl('inventory/get_product_autocomplete'),
                dataType: "json",
                type: 'post',
                data: {
                    name_startsWith: request.term,
                    type: 'name_product_expertise',
                    model_product: model_product,
					warehouse_id: $('#warehouse_id').val()
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        var data = nombre.split('/');
                        if (data.length > 1) {
                            return {
                                label: nombre,
                                value: data[1],
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
            if ($('#producto_order_work').val() !== "") {
                var data_product = (ui.item.id);
                var elem = data_product.split('/');
                $('#price_product').val(elem[2]);
                $('#quantity_product').focus();
                $("#product_selected").val(elem[0])
            } else {
                $('#producto_order_work').focus();
            }

        }
    });

    /*evento submit del formulario principal de contenido*/
    $('#frm_register').submit(function (event) {
        event.preventDefault();

        var data = {};
        var form_data = $(this).serialize();
        console.log(form_data);
        if ($('#table_services_work tbody tr').length > 0) {
            //console.log(form_data)
            ajaxStart('Guardando registros, por favor espere');
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: form_data,
                dataType: 'json',
                success: function (response) {
                    ajaxStop();
                    console.log(response);
                    if (response.success === true) {
                        swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    } else {
                        $('.abm-error').remove();
                        if (response.messages !== null) {
                            $.each(response.messages, function (key, value) {
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

        } else {
            if ($('#table_services_work tbody tr').length < 1) {
                swal('Error', 'Debe de agregar un detalle.', 'error');
            }
        }
    });


    $('#frm_receptiom_edit_states').submit(function (event) {
        event.preventDefault();
        console.log('hola');
        alert('mundo');
    });
    /* FILTROS DE BUSQUEDA ORDEN DE TRABAJO*/
    /*  Evento change de filtro por dias  */
    $("#filter_date").on('change', function () {
        get_list_order_work();
    });

    /*  Evento change de filtro por fecha de rececpion  */
    $("#filter_date_start_reception").on('change', function () {
        console.log($("#filter_date_start_reception").val());
        get_list_order_work();
    });

    /*  Evento change de filtro por fecha de rececpion  */
    $("#filter_date_end_reception").on('change', function () {
        console.log($("#filter_date_end_reception").val());
        get_list_order_work();
    });

    /*  Evento change de filtro por codigo de recepcion  */
    $("#filter_reception_code").keyup(function () {
        get_list_order_work();
    });

});

//#endregion
function get_list_order_work() {
    var tabla = $('#list').DataTable({
        'paging': true,
        'cache': false,
        'info': true,
        'destroy': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,
        'ajax': {
            "url": siteurl('order_work/get_order_service_list'),
            "type": "post",
            "data": {
                filter_date: $("#filter_date").val(),
                filter_date_start_reception: $("#filter_date_start_reception").val(),
                filter_date_end_reception: $("#filter_date_end_reception").val(),
                filter_reception_code: $("#filter_reception_code").val()
            }
        },
        responsive: false,
        'columns': [
            {data: 'id', class: 'hidden'},
            {data: 'codigo_trabajo', class: 'text-center'},
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'nombre_sucursal', class: 'hidden'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'nombre_marca', class: 'text-center'},
            {data: 'nombre_modelo', class: 'text-center'},
            {data: 'imei', class: 'text-center'},
            {data: 'estado_trabajo', class: 'text-center'},
            {data: 'perito_usuario', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        scrollY: "500px",
        scrollX: true,
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false
            },
            {
                targets: 4,
                visible: false,
                searchable: false
            },
            {
                targets: 9,
                visible: true,
                searchable: false
            },
            {
                targets: 11,
                orderable: false,
                render: function (data, type, data_row, meta) {
                    botones = load_options(data_row.estado_trabajo);
                    /*                    var rowIndex = meta.row+1;
                                        if(rowIndex===2){
                                            $('#list tbody tr:nth-child('+rowIndex+')').addClass('red-material-design');
                                        }


                                        if(rowIndex===2){
                                            $('#list tbody tr:nth-child('+rowIndex+')').addClass('red-material-design');
                                        }*/
                    return botones;
                    //return '<label>No tiene Opciones</label>';
                }
            }
        ],
        "order": [[0, "asc"]]
    });
    tabla.ajax.reload();
}

function load_options(work_state) {
    var atributo = '';
    var evento_edit = 'edit_register_form(this)';
    var diagnose = '';
    if (work_state === 'ESPERA STOCK' || work_state === 'ENTREGADO ESPERA STOCK' || work_state === 'REPARADO') {
        diagnose = '<li><a ' + atributo + ' onclick="' + evento_edit + '"><i class="fa fa-edit"></i> Diagnosticar</a></li>';
    }
    evento_edit = 'edit_register_form(this)';
    var botones = '<div class="btn-group">' +
        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
        'OPCIONES <span class="caret"></span> ' +
        '</button><ul class="dropdown-menu">';

    botones = botones +
        '<li><a onclick="get_html_states(this)"  data-toggle="modal"><i class="fa fa-navicon"></i> Estados</a></li>' +
        '<li><a onclick="reception_view(this);"><i class="fa fa-eye"></i> Ver</a></li>' + diagnose +
        // '<li><a onclick="add_gallery(this);"><i class="fa fa-image"></i> Agregar Fotos </a></li>' +
        '<li><a onclick="delete_register(this);"><i class="fa fa-times"></i> Eliminar</a></li> ' +
        '</ul> ' +
        '</div>';
    return botones;
}

function edit_register_form(elemento) {
    var table = $(elemento).closest('table').DataTable();
    var current_row = $(elemento).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var url = siteurl('order_work/order_work_detail');
    $.redirect(url, {id: data['id']}, 'POST', '_self');
}

function add_row_service() {
    var frm = $('#frm_add_row_service').serialize();
    ajaxStart('Guardando registros, por favor espere');
    $.ajax({
        url: site_url + 'order_work/add_row_service',
        data: frm,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            ajaxStop();
            $('.modal-error').remove();
            if (response.success === true) {
                $('#table_services_work tbody').append(response.data);
                delete_row_service();
                $("#frm_add_row_service")[0].reset();
                $(".close-modal").click();
                calculate_total_amounts_service();
            } else {
                $('.modal-error').remove();
                if (response.messages !== null) {
                    $.each(response.messages, function (key, value) {
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
}

function add_gallery(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    $.redirect(siteurl('reception/register_gallery'), {id: rowData['id']}, 'POST', '_self');
}

function add_row_product() {
    var frm = $('#frm_add_row_product').serialize();
    ajaxStart('Guardando registros, por favor espere');
    $.ajax({
        url: site_url + 'order_work/add_row_product',
        data: frm,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            ajaxStop();
            $('.modal-error').remove();
            if (response.success === true) {
                $('#table_services_product tbody').append(response.data);
                delete_row_product();
                $("#frm_add_row_product")[0].reset();
                $(".close-modal").click();
                calculate_total_amounts_product();
            } else {
                $('.modal-error').remove();
                if (response.messages !== null) {
                    $.each(response.messages, function (key, value) {
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
}

function load_solutions() {
    $("#solution_select").empty();
    if (undefined !== $("#id_reception").val() && $("#id_reception").val() !== "") {
        $.ajax({
            url: siteurl('solution/get_solution_reception'),
            async: false,
            type: 'post',
            data: {reception_id: $("#id_reception").val()},
            success: function (data) {
                var result = JSON.parse(data);
                var datos = [];
                $.each(result, function (i, item) {
                    datos.push(item.id);
                });
                var data_solutions = get_solutions();

                $.each(data_solutions, function (i, item) {
                    var selected = "";
                    if (datos.indexOf(item.id) >= 0) {
                        selected = "selected";
                    }
                    $('#solution_select').append('<option value="' + item.id + '" ' + selected + ' >' + item.nombre + '</option>')
                })
            }
        });
    } else {
        var datos = get_solutions();
        $.each(datos, function (i, item) {
            $('#solution_select').append('<option value="' + item.id + '">' + item.nombre + '</option>')
        })
    }
}

function load_failures() {
    $("#failure_select").empty();
    if (undefined !== $("#id_reception").val() && $("#id_reception").val() !== "") {
        $.ajax({
            url: siteurl('failure/get_failure_reception'),
            async: false,
            type: 'post',
            data: {reception_id: $("#id_reception").val()},
            success: function (data) {
                var result = JSON.parse(data);
                var datos = [];
                $.each(result, function (i, item) {
                    datos.push(item.id);
                });
                var data_failures = get_failures();
                $.each(data_failures, function (i, item) {
                    var selected = "";
                    if (datos.indexOf(item.id) >= 0) {
                        selected = "selected";
                    }
                    $('#failure_select').append('<option value="' + item.id + '" ' + selected + ' >' + item.nombre + '</option>')
                })
            }
        });
    } else {
        var datos = get_failures();
        $.each(datos, function (i, item) {
            $('#failure_select').append('<option value="' + item.id + '">' + item.nombre + '</option>')
        })

    }
}

function calculate_total_amounts_product() {

    var total_amount = 0;
    var html_foot_string = "";
    $('#table_services_product tbody tr').each(function (index, value) {
        total_amount = total_amount + parseFloat(value.dataset.price);
    });

    html_foot_string = '<tr> ' +
        '<td style="text-align: right" colspan="4"><b>Total Bs.:</b></td> ' +
        '<td style="padding: 10px; text-align: right"><b>' + total_amount.toFixed(2) + '</b></td> ' +
        '</tr>';
    $("#total_product_price").val(total_amount);
    $('#table_services_product tfoot').empty();
    $('#table_services_product tfoot').append(html_foot_string);
}

function calculate_total_amounts_service() {

    var total_amount = 0;
    var html_foot_string = "";
    $('#table_services_work tbody tr').each(function (index, value) {
        total_amount = total_amount + parseFloat(value.dataset.price);
    });

    html_foot_string = '<tr> ' +
        '<td style="text-align: right" colspan="2"><b>Total Bs.:</b></td> ' +
        '<td style="padding: 10px; text-align: right"><b>' + total_amount.toFixed(2) + '</b></td> ' +
        '</tr>';
    $("#total_product_price").val(total_amount);
    $('#table_services_work tfoot').empty();
    $('#table_services_work tfoot').append(html_foot_string);
}

function delete_row_product() {
    $("a.elimina").click(function () {
        $(this).parents("tr").fadeOut("normal", function () {
            $(this).remove();
            calculate_total_amounts_product();
        });
    });
}

function delete_row_service() {
    $("a.elimina_service").click(function () {
        $(this).parents("tr").fadeOut("normal", function () {
            $(this).remove();
            calculate_total_amounts_service();
        });

    });
}

function load_devices_customer() {
    var id_customer = $("#id_customer").val();
    $('#devices_select').empty();
    if (id_customer !== "" && undefined !== id_customer) {
        $.ajax({
            url: siteurl('customer/get_customer_devices'),
            data: 'id_customer=' + id_customer,
            type: 'post',
            success: function (data) {
                $('#devices_select').append('<option value="0"> Seleccione un dispositivo</option>');
                var datos = JSON.parse(data);
                $.each(datos, function (i, item) {
                    $('#devices_select').append('<option value="' + item.producto_cliente_id + '">' + item.nombre_comercial + ' / ' + item.imei + '</option>');
                })
                $("#devices_select").val($("#equipo_cliente_id").val());
            }
        });
    } else {
        $('#devices_select').append('<option value="0"> Seleccione un dispositivo</option>');
    }
}

function get_failures_selected() {
    var failures = [];
    $($('#failure_select').val()).each(function (i, value) {
        failures.push(value);
    });
    return failures;
}

function get_solutions_selected() {
    var solutions = [];
    $($('#solution_select').val()).each(function (i, value) {
        solutions.push(value);
    });
    return solutions;
}

function get_html_states(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var TYPE_STATES_SERVICE = 1;
    /* tipo de servicio para el cargado de menu de estados*/
    var rowData = table.row(fila).data();
    $.ajax({
        url: siteurl('reception/get_html_state'),
        data: {
            state_reception: rowData['estado_trabajo'],
            reception_id: rowData['id'],
            type_option: TYPE_STATES_SERVICE
        },
        type: 'post',
        success: function (response) {
            $('#container_states').html(response);
            $('#receptiom_edit_states').modal('show');
        }
    });
}

function onclick_state(state) {
    ajaxStart('Registrando datos...');
    $.ajax({
        url: siteurl('order_work/update_state_order'),
        type: 'post',
        data: {reception_id: $("#inp_reception_id").val(), state_reception: state},
        dataType: 'json',
        success: function (response) {
            ajaxStop();
            if (response.verify === true) {
                if (response.success === true) {
                    swal('DATOS CORRECTOS', response.messages, 'success');
                    update_data_table($("#list"));
                    $('#receptiom_edit_states').modal('hide');
                } else if (response.login === true) {
                    login_session();
                }else if (response.tecnico === false) {
                    swal('ADVERTENCIA', 'Ud. no puede cambiar de estado por que no es usuario tecnico.', 'error');
                } else {
                    swal('DATOS INCORRECTOS', response.messages, 'error');
                }
            } else {
                if (response.success === true) {
                    swal('DATOS CORRECTOS', response.messages, 'success');
                    update_data_table($("#reception_list"));
                    update_data_table($("#list"));
                    $('#receptiom_edit_states').modal('hide');
                }else if (response.login === true) {
                    login_session();
                }else if (response.tecnico === false) {
                    swal('ADVERTENCIA', 'Ud. no puede cambiar de estado por que no es usuario tecnico.', 'error');
                } else {
                    swal('DATOS NO SE PUEDE ACTUALIZAR EL ESTADO', response.messages, 'error');

                }
            }
        }
    });
}

/* Abre una ventana modal para ver la recepcion*/
function reception_view(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    $('#delete_data').remove();
    $.post(siteurl('reception/view_reception'), {id: id},
        function (response) {
            var data = JSON.parse(response);
            var customer_phone = data.reception.telefono1 + ' - ' + data.reception.telefono1;
            var customer_type = '';
            var warranty = '';
            if (parseInt(data.reception.garantia) == 0) {
                warranty = 'Sin Garantia';
            }else if(parseInt(data.reception.garantia) == 1){
                warranty = 'Con Garantia';
            }else {
                warranty = 'Por Verificar';
            }

            switch (parseInt(data.reception.tipo_cliente)) {
                case 0:
                    customer_type = 'Cliente Diario'
                    break;
                case 1:
                    customer_type = 'Cliente por Mayor'
                    break;
                case 2:
                    customer_type = 'Cliente Express'
                    break;
                case 3:
                    customer_type = 'Cliente laboratorio'
                    break;
            }

            var reception_observation = data.reception.observacion_recepcion;
            if (!reception_observation) {
                reception_observation = 'SIN OBSERVACION';
            }

            var order_work_observation = data.order_work.observacion;
            if (!order_work_observation) {
                order_work_observation = 'SIN OBSERVACION';
            }
            var reception_header = '<div class="form-group" style="text-align: left" id="delete_data"> <div class="form-line">' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Sucursal: ' + '&nbsp;' + data.branch_office.nombre_comercial + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Cod. Recepcion: ' + '&nbsp;' + data.reception.codigo_recepcion + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Cod. Trabajo: ' + '&nbsp;' + data.order_work.codigo_trabajo + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Vendedor: ' + '&nbsp;' + data.user.nombre + '</label></div>' +
                '<div class="col-lg-6 col-md-6 col-sm-4 col-xs-12"><label>Fecha Recepcion: ' + '&nbsp;' + data.reception.fecha_registro + '</label></div>' +
                '<div class="col-lg-6 col-md-6 col-sm-4 col-xs-12"><label>Fecha Orden Trabajo: ' + '&nbsp;' + data.order_work.registro + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Tipo Cliente: ' + '&nbsp;' + customer_type + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Cliente: ' + '&nbsp;' + data.reception.nombre + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>C.I.: ' + '&nbsp;' + data.reception.ci + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Telefono: ' + '&nbsp;' + customer_phone + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Marca: ' + '&nbsp;' + data.reception.nombre_marca + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Modelo: ' + '&nbsp;' + data.reception.nombre_comercial + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Imei: ' + '&nbsp;' + data.reception.imei + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Garantia: ' + '&nbsp;' + warranty + '</label></div>' +
                '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><label>Clave: ' + '&nbsp;' + data.reception.codigo_seguridad + '</label></div>' +
                '<div class="col-lg-9 col-md-3 col-sm-4 col-xs-12"><label>Accesorios: ' + '&nbsp;' + data.reception.accesorio_dispositivo + '</label></div>' +
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><label>Observacion Recepcion: ' + '&nbsp;' + reception_observation + '</label></div>' +
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><label>Observacion Tecnico: ' + '&nbsp;' + order_work_observation + '</label></div>' +
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE FALLAS Y SOLUCIONES</label></div>';
            var failure_detail = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="center"><label>FALLAS</label>';
            var data_detail_failure = data.detail_failures;
            for (var i = 0; i < data_detail_failure.length; i++) {
                var item = data_detail_failure[i];
                failure_detail = failure_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center"><label>' + item.nombre + '</label></div>';
            }
            ;
            failure_detail = failure_detail + '</div>';

            var solution_detail = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="center"><label>SOLUCIONES</label>';
            var data_detail_solution = data.detail_solutions;
            for (var i = 0; i < data_detail_solution.length; i++) {
                var item = data_detail_solution[i];
                solution_detail = solution_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center"><label>' + item.nombre + '</label></div>';
            }
            solution_detail = solution_detail + '</div>';

            var service_detail = '';
            if (data.detail_services.length > 0) {
                service_detail = service_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE SERVICIOS</label></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>SERVICIO</label></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>OBSERVACION</label></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>PRECIO</label></div>';
                var data_detail_services = data.detail_services;
                for (var i = 0; i < data_detail_services.length; i++) {
                    var item = data_detail_services[i];
                    var service_observation = item.observacion;
                    if (!service_observation) {
                        service_observation = 'SIN OBSERVACION';
                    }
                    service_detail = service_detail + '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.nombre_servicio + '</label></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + service_observation + '</label></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.precio_servicio + '</label></div>';
                }
            }


            var product_detail = '';
            if (data.detail_products.length > 0) {
                product_detail = product_detail + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center"><label>DETALLE DE REPUESTOS</label></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>REPUESTO</label></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>CANTIDAD</label></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>PRECIO</label></div>';
                var data_detail_services = data.detail_products;
                for (var i = 0; i < data_detail_services.length; i++) {
                    var item = data_detail_services[i];
                    product_detail = product_detail + '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.nombre_producto + '</label></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.cantidad + '</label></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" align="center"><label>' + item.precio_venta + '</label></div>';
                }
            }


            var reception_footer = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center"><div align="right" style="padding-right: 4%">' +
                '<label>Monto Total Trabajo: ' + '&nbsp;</label>' + data.order_work.monto_total + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                '<br>' +
                '</div> </div></div></div>';
            var reception_data = reception_header + failure_detail + solution_detail + service_detail + product_detail + reception_footer;
            $('#reception_data').append(reception_data);
        });
    $('#modal_view_reception').modal({
        show: true,
        backdrop: 'static'
    });
}
