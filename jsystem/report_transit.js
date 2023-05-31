

$(document).ready(function () {

    $("#btn_search_transit").on('click', function () {
        get_report_transit();
    });

    $("#btn_excel_export_transit").on('click', function () {
        $.redirect(siteurl('transit/export_to_excel_transit'), {
            transit_date_start_loan: $("#transit_date_start_loan").val(),
            transit_date_end_loan: $("#transit_date_end_loan").val(),
            transit_date_start_return: $("#transit_date_start_return").val(),
            transit_date_end_return: $("#transit_date_end_return").val(),
            transit_reception_code: $("#transit_reception_code").val(),
            transit_state: $("#transit_state").val(),
            transit_type_reason: $("#transit_type_reason").val(),
            transit_brand: $("#transit_brand").val(),
            transit_model: $("#transit_model").val(),
            transit_product: $("#transit_product").val()
        }, 'POST','_blank');
    });
});

/*Retorna la consulta de almacen de la Vista de BD*/
function get_report_transit() {
    var tabla = $('#transit_list').DataTable({
        paging: true,
        info: true,
        filter: true,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,

        ajax: {
            url: siteurl('transit/get_transit_report'),
            type: 'post',
            data: {
                transit_date_start_loan: $("#transit_date_start_loan").val(),
                transit_date_end_loan: $("#transit_date_end_loan").val(),
                transit_date_start_return: $("#transit_date_start_return").val(),
                transit_date_end_return: $("#transit_date_end_return").val(),
                transit_reception_code: $("#transit_reception_code").val(),
                transit_state: $("#transit_state").val(),
                transit_type_reason: $("#transit_type_reason").val(),
                transit_brand: $("#transit_brand").val(),
                transit_model: $("#transit_model").val(),
                transit_product: $("#transit_product").val()
            }
        },
        columns: [
            {data: 'id', class: 'text-center'},
            {data: 'nro_prestamo', class: 'text-center'},
            {data: 'nombre_tipo_motivo', class: 'text-center'},
            {data: 'estado_transito', class: 'text-center'},
            {data: 'codigo_recepcion', class: 'text-center'},
            {data: 'fecha_transito_prestamo', class: 'text-center'},
            {data: 'fecha_transito_devolucion', class: 'text-center'},
            {data: 'usuario_entregador_prestamo', class: 'text-center'},
            {data: 'usuario_solicitante_prestamo', class: 'text-center'},
            {data: 'detalle_prestamo', class: 'text-center'}
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 3,
                orderable: false,
                render: function (data, type, row) {
                    if (data == 1){
                        return '<div style="background-color: red">PRESTADO</div>';
                    } else if( data == 2){
                        return '<div style="background-color: green">DEVUELTO</div>';
                    }
                }
            }
        ],
        "order": [[0, "asc"]],
    });
}