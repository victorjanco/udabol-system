$(document).ready(function () {
    $('#btn_search_report_sale_product').click(function (event) {
        event.preventDefault();
        get_report_sale_product();
    });

    $('#btn_export_report_sale_product').click(function (event) {
        event.preventDefault();
        $.redirect(siteurl('report_sale/export_to_excel_sale_product'), {
            report_branch_office: $("#report_branch_office").val(),
            report_start_date: $("#report_start_date").val(),
            report_end_date: $("#report_end_date").val(),
            report_brand: $("#report_brand").val(),
            report_model: $("#report_model").val(),
            report_product: $("#report_product").val(),
            report_type_sale: $("#report_type_sale").val(),
            report_customer: $("#report_customer").val(),
            reporte_number_sale: $("#reporte_number_sale").val(),
            reporte_number_reception: $("#reporte_number_reception").val(),
            report_type_product: $("#report_type_product").val()
        }, 'POST','_blank');
        //get_report_sale_product();
    });
    
    $('#btn_search_report_sale_user').click(function (event) {
        event.preventDefault();
        get_report_sale_user();
    });
    $('#btn_export_report_sale_user').click(function (event) {
        event.preventDefault();
        $.redirect(siteurl('report_sale/export_to_excel_sale_user'), {
            report_start_date: $("#report_start_date").val(),
            report_end_date: $("#report_end_date").val(),
            report_branch_office: $("#report_branch_office").val(),
            report_user: $("#report_user").val(),
            report_type_sale: $("#report_type_sale").val(),
        }, 'POST','_blank');
        //get_report_sale_product();
    });
});



function get_report_sale_product() {
    var tabla = $('#report_sale_product').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: siteurl('report_sale/get_report_sale_product'),
            type: 'post',
            data: {
                report_branch_office: $("#report_branch_office").val(),
                report_start_date: $("#report_start_date").val(),
                report_end_date: $("#report_end_date").val(),
                report_brand: $("#report_brand").val(),
                report_model: $("#report_model").val(),
                report_product: $("#report_product").val(),
                report_type_sale: $("#report_type_sale").val(),
                report_customer: $("#report_customer").val(),
                reporte_number_sale: $("#reporte_number_sale").val(),
                reporte_number_reception: $("#reporte_number_reception").val(),
                report_type_product: $("#report_type_product").val()
            }
        },
        columns: [
            {data: 'fecha_registro'},
            // {data: 'codigo_recepcion', class: 'text-center'},
            {data: 'nro_venta', class: 'text-center'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'tipo_venta', class: 'text-center'},
            {data: 'tipo_producto_id', class: 'text-center'},
            {data: 'nombre_marca', class: 'text-center'},
            {data: 'codigo_producto', class: 'text-center'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'nombre_modelo', class: 'text-center'},
            {data: 'codigo_modelo', class: 'text-center'},
            {data: 'cantidad', class: 'text-center'},
            {data: 'precio_compra', class: 'text-center'},
            {data: 'precio_venta', class: 'text-center'},
            {data: 'descuento', class: 'text-center'},
            {data: 'precio_venta_descuento', class: 'text-center'},
            {data: 'total', class: 'text-right'},
            {data: 'utilidad', class: 'text-right'}
        ],
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            for (let index = 11; index < 17; index++) {
                total = api
                .column( index )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( index, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( index).footer() ).html(
                // ''+pageTotal +' ('+ total +' total)'
                total.toFixed(2)
            );
            }
            
        },
        responsive: false,
    });
    tabla.ajax.reload();
}

function get_report_sale_user() {
    var tabla = $('#report_sale_user').DataTable({
        paging: true,
        info: true,
        filter: false,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: siteurl('report_sale/get_report_sale_user'),
            type: 'post',
            data: {
                report_start_date: $("#report_start_date").val(),
                report_end_date: $("#report_end_date").val(),
                report_branch_office: $("#report_branch_office").val(),
                report_user: $("#report_user").val(),
                report_type_sale: $("#report_type_sale").val()
            }
        },
        columns: [
            {data: 'nro_venta', class: 'text-center'},
            {data: 'fecha_registro'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'tipo_venta', class: 'text-center'},
            {data: 'nombre_usuario', class: 'text-right'}, 
            {data: 'nombre_sucursal', class: 'text-right'},
            {data: 'precio_costo_total', class: 'text-right'},
            {data: 'subtotal', class: 'text-right'},
            {data: 'descuento', class: 'text-right'},
            {data: 'total', class: 'text-right'},
            //{data: 'utilidad', class: 'text-right'},
        ],
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            for (let index = 8; index < 10; index++) {
                total = api
                .column( index )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( index, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( index).footer() ).html(
                // ''+pageTotal +' ('+ total +' total)'
                total.toFixed(2)
            );
            }
            
        },
        responsive: false,
    });
    // tabla.ajax.reload();
}
