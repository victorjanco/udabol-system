/**
 * Created by Green Ranger on 17/04/2018.
 */

    $(document).ready(function () {

    });

    $(function () {
        $("#btn_new_stock_inventory").on('click', function () {
            get_report_stock_inventory_list();
        });

        $("#btn_excel_export_stock_inventory").on('click', function () {
            $.redirect(siteurl('report_inventory/export_to_excel_stock_inventory'), {
                report_warehouse: $("#report_warehouse").val(),
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_product: $("#report_product").val()
            }, 'POST','_blank');
        })

    });

    /*Retorna la consulta de Stock de inventario de la Vista de BD*/
    function get_report_stock_inventory_list() {
        var tabla = $('#list_stock_inventory_report').DataTable({
            paging: true,
            info: true,
            filter: true,
            stateSave: true,
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: siteurl('report_inventory/get_report_stock_inventory_list'),
                type: 'post',
                data: {
                    report_warehouse: $("#report_warehouse").val(),
                    report_brand: $("#report_brand").val(),
                    report_model: $("#report_model").val(),
                    report_product: $("#report_product").val()
                }
            },
            columns: [
                // {data: 'nombre_almacen', class: 'text-center'},
                {data: 'nombre_marca', class: 'text-center'},
                {data: 'nombre_modelo', class: 'text-center'},
                {data: 'codigo_producto', class: 'text-center'},
                {data: 'nombre_comercial_producto', class: 'text-center'},
                {data: 'nombre_generico_producto', class: 'text-center'},
                {data: 'dimension_producto', class: 'text-center'},
                {data: 'precio_venta_truncate', class: 'text-center'},
                {data: 'stock', class: 'text-center'}
            ],
            responsive: false,
        });
        tabla.ajax.reload();
    }



