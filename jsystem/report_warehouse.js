/**
 * Created by Green Ranger on 23/04/2018.
 */

$(document).ready(function () {

});

$(function () {

    $("#btn_new_warehouse").on('click', function () {
        get_report_warehouse_list();
    });

    $("#btn_excel_export_warehouse").on('click', function () {
        $.redirect(siteurl('report_warehouse/export_to_excel_warehouse'), {
            report_start_date: $("#start_date").val(),
            report_end_date: $("#end_date").val(),
            report_warehouse: $("#report_warehouse").val(),
            report_brand: $("#report_brand").val(),
            report_model: $("#report_model").val(),
            report_product: $("#report_product").val()
        }, 'POST','_blank');
    });
});

/*Retorna la consulta de almacen de la Vista de BD*/
function get_report_warehouse_list() {
    var tabla = $('#list_warehouse_report').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,

        ajax: {
            url: siteurl('report_warehouse/get_report_warehouse_list'),
            type: 'post',
            data: {
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val(),
                report_warehouse: $("#report_warehouse").val(),
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_product: $("#report_product").val()
            }
        },
        columns: [
            {data: 'nombre_almacen', class: 'text-center'},
            {data: 'nombre_marca', class: 'text-center'},
            {data: 'nombre_comercial', class: 'text-center'},
            {data: 'nombre_generico', class: 'text-center'},
            //{data: 'codigo_modelo', class: 'text-center'},
            //{data: 'nombre_modelo', class: 'text-center'},
            {data: 'stock', class: 'text-center'},
            {data: 'precio_costo', class: 'text-center'},
            {data: 'total', class: 'text-center'},
        ],
        responsive: false,
    });
    tabla.ajax.reload();
}
