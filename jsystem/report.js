$(document).ready(function () {
    //get_report_brand_list();
});
$(function () {
    $('#excel_export').click(function () {
        var month = $('#start_date').val();
        var year = $('#end_date').val();
        var brand = $('#brand').val();
        location.href= siteurl('/reporte/export_to_excel'+'/'+month+'/'+year+'/'+brand);
    });

    $("#btn_new_brand").on('click',function () {
        get_report_brand_list();
    });

    $("#excel_export").on('click', function () {
        $.redirect(siteurl('report_reception/export_to_excel'), {
            reception_date_start: $("#start_date").val(),
            reception_date_end: $("#end_date").val(),
            reception_brand: $("#reception_brand").val()
        }, 'POST','_blank');
    });

    $("#btn_search_lcv").on('click', function () {
        get_report_lcv();
    });

    $("#btn_txt_export_lcv").on('click', function () {
        $.redirect(siteurl('reports/get_txt'), {
            month: $("#month").val(),
            year: $("#year").val()
        }, 'POST','_blank');
    });

    $("#btn_excel_export_lcv").on('click', function () {
        $.redirect(siteurl('reports/export_excel_lcv'), {
            month: $("#month").val(),
            year: $("#year").val()
        }, 'POST','_blank');
    })
});

/*carga la lista de las marcas al datatable*/
function get_report_brand_list() {
    var tabla = $('#list_brand_report').DataTable({
        paging: false,
        info: false ,
        filter: false,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy:true,
        'ajax': {
            "url": siteurl('report_reception/get_report_brand_list'),
            "type": 'post',
            "data":{
                reception_date_start: $("#start_date").val(),
                reception_date_end: $("#end_date").val(),
                reception_brand: $("#reception_brand").val()
            }

        },
        'columns': [
            {data: 'codigo_recepcion', class: 'text-center'},
            {data: 'nombre', class: 'text-center'},
            {data: 'telefono', class: 'text-center'},
            {data: 'estado_trabajo', class: 'text-center'},
            {data: 'codigo_modelo', class: 'text-center'},
            {data: 'nombre_modelo', class: 'text-center'},
            {data: 'imei', class: 'text-center'},
            {data: 'fecha_registro', class: 'text-center'},
            {data: 'fecha_entrega', class: 'text-center'},
            {data: 'detalle_reparacion', class: 'text-center'}
        ],
        "columnDefs": [{

        }/*, {
            targets: 4,
            orderable: false,
            render: function (data, type, row) {
                if (row.estado != 0) {
                    return load_buttons_crud(2, 'modal');
                } else {
                    return '<label>No tiene Opciones</label>';
                }
            }
        }*/]
        /*responsive: true,
         pagingType: "full_numbers",
         select: false*/
    });
    tabla.ajax.reload();
}

function get_report_lcv() {
    var tabla = $('#list_report_lcv').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: siteurl('reports/get_facturas_lcv'),
            type: 'post',
            data: {
                month: $("#month").val(),
                year: $("#year").val()
            }
        },
        columns: [
            {data: 'nro_factura', class: 'text-center'},
            {data: 'nro_autorizacion', class: 'text-center'},
            {data: 'fecha', class: 'text-center'},
            {data: 'nit_cliente', class: 'text-center'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'subtotal', class: 'text-center'},
            {data: 'descuento', class: 'text-center'},
            {data: 'importe_total_venta', class: 'text-center'},
            {data: 'importe_base_iva', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'codigo_control', class: 'text-center'}
        ],
        responsive: false,
    });
    tabla.ajax.reload();
}