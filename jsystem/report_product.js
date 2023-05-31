/**
 * Created by Green Ranger on 20/04/2018.
 */

    $(document).ready(function () {

    });

    $(function () {

        $("#btn_new_product").on('click', function () {
            get_report_product_list();
        });

        $("#btn_excel_export_product").on('click', function () {
            $.redirect(siteurl('report_product/export_to_excel_product'), {
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val(),
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_product: $("#report_product").val(),
                report_warehouse: $("#report_warehouse").val()
            }, 'POST','_blank');
        });

    });

    /*Retorna la consulta de Stock de inventario de la Vista de BD*/
    function get_report_product_list() {
        var tabla = $('#list_product_report').DataTable({
            paging: true,
            info: true,
            filter: true,
            stateSave: true,
            processing: true,
            serverSide: true,
            destroy: true,
            'ajax': {
                "url": siteurl('report_product/get_report_product_list'),
                "type": 'post',
                "data": {
                    report_start_date: $("#start_date").val(),
                    report_end_date: $("#end_date").val(),
                    report_brand: $("#report_brand").val(),
                    report_model: $("#report_model").val(),
                    report_product: $("#report_product").val(),
                    report_warehouse: $("#report_warehouse").val()
                }

            },
            'columns': [
                {data: 'fecha', class: 'text-center'},
                {data: 'nombre_almacen', class: 'text-center'},
                {data: 'nombre_marca', class: 'text-center'},
                {data: 'codigo_producto', class: 'text-center'},
                {data: 'nombre_comercial', class: 'text-center'},
                {data: 'codigo_modelo', class: 'text-center'},
                {data: 'nombre_modelo', class: 'text-center'},
                {data: 'ingresada', class: 'text-center'},
                {data: 'cantidad_saliente', class: 'text-center'},
                {data: 'saldo_disponible', class: 'text-center'},
                {data: 'precio_costo', class: 'text-center'},
                {data: 'precio_venta', class: 'text-center'}
            ],
            "columnDefs": [{}]
        });
        //tabla.ajax.reload();
    }