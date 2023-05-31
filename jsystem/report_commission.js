/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 16/09/2019
 * Time: 18:42 PM
 */

$(document).ready(function () {

    $("#btn_search").on('click', function () {
        get_report_commission();
    });

    $("#btn_import_excel").on('click', function () {
        $.ajax({
            url: siteurl('report_commission/import_permission'),
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.success == 1) {
                    window.location.replace(siteurl('Report_commission/report_import_commission'));
                } else {
                    swal('No tienes permiso', 'El administrador solo puede crear producto', 'warning');
                }
            }
        });
    });

    $("#btn_export_excel").on('click', function () {
        $.redirect(siteurl('report_commission/export_report_commission'), {
            brand: $("#brand").val(),
            model: $("#model").val(),
            serie: $("#serie").val(),
            product: $("#product").val(),
            commission_branch_office: $("#commission_branch_office").val(),
            start_date: $("#start_date").val(),
            end_date: $("#end_date").val()
        }, 'POST','_blank');
    });

});

function get_report_commission() {
    $('#list_commission').DataTable({
        paging: false,
        info: false,
        filter: false,
        stateSave: true,
        processing: true,
        serverSide: false,
        destroy: true,
        'ajax': {
            "url": siteurl('report_commission/get_report_commission'),
            "type": 'post',
            "data": {
                brand: $("#brand").val(),
                model: $("#model").val(),
                serie: $("#serie").val(),
                product: $("#product").val(),
                commission_branch_office: $("#commission_branch_office").val(),
                start_date: $("#start_date").val(),
                end_date: $("#end_date").val()
            }
        },
        'columns': [
            {data: 'fecha_transaccion', class: 'text-center'},
            {data: 'codigo', class: 'text-center'},
            {data: 'nombre_producto', class: 'text-center'},
            {data: 'nombre_sucursal_comision', class: 'text-center'},
            {data: 'glosa_productos', class: 'text-center'},
            {data: 'nombre_serie', class: 'text-center'},
            {data: 'cantidad', class: 'text-center'},
            {data: 'total_bs', class: 'text-center'},
            {data: 'total_sus', class: 'text-center'},
            {data: 'codigo_transaccion', class: 'text-center'},
            {data: 'porcentaje', class: 'text-center'},
            {data: 'comision_bs', class: 'text-center'}
        ],
        "columnDefs": [{}]
    });
}

