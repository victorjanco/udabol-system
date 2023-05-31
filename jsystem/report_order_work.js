/**
 * Created by Green Ranger on 24/04/2018.
 */

    $(document).ready(function () {

    });

    $(function () {

        $("#report_brand").on('change', function () {
            $.ajax({
                url: siteurl('model/get_model_by_brand'),
                type: 'post',
                data: {marca_id: $("#report_brand").val()},
                dataType: 'json',
                success: function (response) {
                    $('#report_model').empty();
                    $('#report_model').append('<option value="' + '0' + '">' + 'Todos' + '</option>');
                    $.each(response, function (i, item) {
                        $('#report_model').append('<option value="' + item.id + '">' + item.nombre + '</option>');
                    });
                }
            })
        });

        $("#btn_new_order_work").on('click', function () {
            get_report_order_work_list();
        });

        $("#btn_excel_export_order_work").on('click', function () {
            $.redirect(siteurl('report_order_work/export_to_excel_order_work'), {
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_order_work_code: $("#report_order_work_code").val(),
                report_gustomer: $("#report_gustomer").val(),
                report_state: $("#report_state").val(),
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val(),
                report_warranty: $("#report_warranty").val()
            }, 'POST','_blank');
        });

        $("#btn_new_order_work_delivered").on('click', function () {
            get_report_order_work_delivered_list();
        });

        $("#btn_excel_export_order_work_delivered").on('click', function () {
            $.redirect(siteurl('report_order_work/export_to_excel_order_work_delivered'), {
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_order_work_code: $("#report_order_work_code").val(),
                report_gustomer: $("#report_gustomer").val(),
                report_state: $("#report_state").val(),
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val()
            }, 'POST','_blank');
        });

        $("#btn_new_order_work_fault").on('click', function () {
            get_report_order_work_fault_list();
        });

        $("#btn_excel_export_order_work_fault").on('click', function () {
            $.redirect(siteurl('report_order_work/export_to_excel_order_work_fault'), {
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_order_work_code: $("#report_order_work_code").val(),
                report_gustomer: $("#report_gustomer").val(),
                report_state: $("#report_state").val(),
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val(),
                report_warranty: $("#report_warranty").val(),
                report_reference: $("#report_reference").val()
            }, 'POST','_blank');
        });

        $("#btn_new_order_work_unapproved").on('click', function () {
            get_report_order_work_unapproved_list();
        });

        $("#btn_excel_export_order_work_unapproved").on('click', function () {
            $.redirect(siteurl('report_order_work/export_to_excel_order_work_unapproved'), {
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_order_work_code: $("#report_order_work_code").val(),
                report_gustomer: $("#report_gustomer").val(),
                report_state: $("#report_state").val(),
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val(),
                report_warranty: $("#report_warranty").val(),
                report_reference: $("#report_reference").val()
            }, 'POST','_blank');
        });
    });

    /*Retorna la consulta de orden de trabajo de la Vista de BD*/
    function get_report_order_work_list() {
        var tabla = $('#list_order_work_report').DataTable({
            paging: true,
            info: true,
            filter: true,
            stateSave: true,
            processing: true,
            serverSide: true,
            destroy: true,

            ajax: {
                url: siteurl('report_order_work/get_report_order_work_list'),
                type: 'post',
                data: {
                    report_brand: $("#report_brand").val(),
                    report_model: $("#report_model").val(),
                    report_order_work_code: $("#report_order_work_code").val(),
                    report_gustomer: $("#report_gustomer").val(),
                    report_state: $("#report_state").val(),
                    report_start_date: $("#start_date").val(),
                    report_end_date: $("#end_date").val(),
                    report_warranty: $("#report_warranty").val()
                }
            },
            columns: [
                {data: 'fecha_registro', class: 'text-center'},
                {data: 'fecha_concluido', class: 'text-center'},
                {data: 'horas', class: 'text-center'},
                {data: 'fecha_entrega', class: 'text-center'},
                {data: 'recepcion_usuario', class: 'text-center'},
                {data: 'perito_usuario', class: 'text-center'},
                {data: 'concluido_usuario', class: 'text-center'},
                {data: 'entrega_usuario', class: 'text-center'},
                {data: 'codigo_trabajo', class: 'text-center'},
                {data: 'nombre_cliente', class: 'text-center'},
                {data: 'nombre_marca', class: 'text-center'},
                {data: 'codigo_modelo', class: 'text-center'},
                {data: 'nombre_modelo', class: 'text-center'},
                {data: 'imei', class: 'text-center'},
                {data: 'estado_trabajo', class: 'text-center'},
                {data: 'garantia', class: 'text-center'},
                {data: 'observacion', class: 'text-center'}
            ],columnDefs: [
                {
                    targets: 2,
                    searchable: false
                }
            ],
            responsive: false,
        });
        tabla.ajax.reload();
    }

function get_report_order_work_delivered_list() {
    var tabla = $('#list_order_work_report_delivered').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,

        ajax: {
            url: siteurl('report_order_work/get_report_order_work_delivered_list'),
            type: 'post',
            data: {
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_order_work_code: $("#report_order_work_code").val(),
                report_gustomer: $("#report_gustomer").val(),
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val(),
                report_warranty: $("#report_warranty").val()
            }
        },
        columns: [
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'fecha_concluido', class: 'text-center'},
            {data: 'horas', class: 'text-center'},
            {data: 'fecha_entrega', class: 'text-center'},
            {data: 'recepcion_usuario', class: 'text-center'},
            {data: 'perito_usuario', class: 'text-center'},
            {data: 'concluido_usuario', class: 'text-center'},
            {data: 'entrega_usuario', class: 'text-center'},
            {data: 'codigo_trabajo', class: 'text-center'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'nombre_marca', class: 'text-center'},
            {data: 'codigo_modelo', class: 'text-center'},
            {data: 'nombre_modelo', class: 'text-center'},
            {data: 'imei', class: 'text-center'},
            {data: 'garantia', class: 'text-center'},
            {data: 'observacion', class: 'text-center'},
            {data: 'servicio', class: 'text-center'},
            {data: 'servicio_total', class: 'text-center'},
            {data: 'producto', class: 'text-center'},
            {data: 'producto_total', class: 'text-center'},
            {data: 'monto_subtotal', class: 'text-center'}

        ],columnDefs: [
            {
                targets: 2,
                searchable: false
            }
        ],
        responsive: false,
    });
    tabla.ajax.reload();
}

/*Retorna la consulta de orden de trabajo de la Vista de BD*/
function get_report_order_work_fault_list() {
    $('#list_order_work_fault_report').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,

        ajax: {
            url: siteurl('report_order_work/get_report_order_work_fault_list'),
            type: 'post',
            data: {
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_order_work_code: $("#report_order_work_code").val(),
                report_gustomer: $("#report_gustomer").val(),
                report_state: $("#report_state").val(),
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val(),
                report_warranty: $("#report_warranty").val(),
                report_reference: $("#report_reference").val()
            }
        },
        columns: [
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'fecha_concluido', class: 'text-center'},
            {data: 'horas', class: 'text-center'},
            {data: 'fecha_entrega', class: 'text-center'},
            {data: 'recepcion_usuario', class: 'text-center'},
            {data: 'perito_usuario', class: 'text-center'},
            {data: 'concluido_usuario', class: 'text-center'},
            {data: 'entrega_usuario', class: 'text-center'},
            {data: 'codigo_trabajo', class: 'text-center'},
            {data: 'nombre_referencia', class: 'text-center'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'nombre_marca', class: 'text-center'},
            {data: 'codigo_modelo', class: 'text-center'},
            {data: 'nombre_modelo', class: 'text-center'},
            {data: 'imei', class: 'text-center'},
            {data: 'estado_trabajo', class: 'text-center'},
            {data: 'garantia', class: 'text-center'},
            {data: 'observacion', class: 'text-center'},
            {data: 'fallas_recepcion', class: 'text-center'},
            {data: 'fallas_tecnico', class: 'text-center'}
        ],columnDefs: [
            {
                targets: 2,
                searchable: false
            }
        ],
        responsive: false,
    });
}

/*Retorna la consulta de orden de trabajo de la Vista de BD*/
function get_report_order_work_unapproved_list() {
    $('#list_order_work_unapproved_report').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,

        ajax: {
            url: siteurl('report_order_work/get_report_order_work_unapproved_list'),
            type: 'post',
            data: {
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_order_work_code: $("#report_order_work_code").val(),
                report_gustomer: $("#report_gustomer").val(),
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val(),
                report_warranty: $("#report_warranty").val(),
                report_reference: $("#report_reference").val()
            }
        },
        columns: [
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'fecha_concluido', class: 'text-center'},
            {data: 'horas', class: 'text-center'},
            {data: 'fecha_entrega', class: 'text-center'},
            {data: 'recepcion_usuario', class: 'text-center'},
            {data: 'perito_usuario', class: 'text-center'},
            {data: 'concluido_usuario', class: 'text-center'},
            {data: 'entrega_usuario', class: 'text-center'},
            {data: 'codigo_trabajo', class: 'text-center'},
            {data: 'nombre_referencia', class: 'text-center'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'nombre_marca', class: 'text-center'},
            {data: 'codigo_modelo', class: 'text-center'},
            {data: 'nombre_modelo', class: 'text-center'},
            {data: 'imei', class: 'text-center'},
            {data: 'estado_trabajo', class: 'text-center'},
            {data: 'garantia', class: 'text-center'},
            {data: 'observacion', class: 'text-center'},
            {data: 'fallas_recepcion', class: 'text-center'},
            {data: 'fallas_tecnico', class: 'text-center'},
            {data: 'nombre_tipo_motivo', class: 'text-center'},
            {data: 'observacion', class: 'text-center'},
        ],columnDefs: [
            {
                targets: 2,
                searchable: false
            }
        ],
        responsive: false,
    });
}