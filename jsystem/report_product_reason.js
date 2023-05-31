/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 13/05/2019
 * Time: 06:42 PM
 */

$(document).ready(function () {

    $("#btn_new_product_reason").on('click', function () {
        get_report_product_reason_list();
    });

    $("#btn_excel_export_product_reason").on('click', function () {
        $.redirect(siteurl('report_product/export_to_excel_product_reason'), {
            report_brand: $("#report_brand").val(),
            report_model: $("#report_model").val(),
            report_product: $("#report_product").val(),
            start_date: $("#start_date").val(),
            end_date: $("#end_date").val()
        }, 'POST','_blank');
    });

});


/*Retorna la consulta de Stock de inventario de la Vista de BD*/
function get_report_product_reason_list() {
    $('#list_product_reason_report').DataTable({
        paging: false,
        info: false,
        filter: false,
        stateSave: true,
        processing: true,
        serverSide: false,
        destroy: true,
        'ajax': {
            "url": siteurl('report_product/get_report_product_reason_list'),
            "type": 'post',
            "data": {
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_product: $("#report_product").val(),
                start_date: $("#start_date").val(),
                end_date: $("#end_date").val()
            }
        },
        'columns': [
            {data: 'codigo_producto', class: 'text-center'},
            {data: 'nombre_comercial', class: 'text-center'},
            {data: 'codigo_modelo', class: 'text-center'},
            {data: 'nombre_modelo', class: 'text-center'},
            {data: 'dimension', class: 'text-center'},
            {data: 'nombre_grupo', class: 'text-center'},
            {data: 'nombre_marca', class: 'text-center'},
            {data: 'nombre_tipo_motivo', class: 'text-center'},
            {data: 'observacion', class: 'text-center'}
        ],
        "columnDefs": [{}]
    });
}

