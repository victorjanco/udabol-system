/**
 * Created by Green Ranger on 20/04/2018.
 */

$(document).ready(function () {
    $("#btn_search_cash").on('click', function () {
        // alert('jNCIdsajdhasjdgh');
        get_report_cash_list();
        
    });

    $("#btn_excel_export_cash").on('click', function () {
        $.redirect(siteurl('report_cash/export_to_excel_cash_close'), {
            report_start_date: $("#start_date").val(),
            report_end_date: $("#end_date").val(),
            report_office: $("#report_office").val(),
            report_cash: $("#report_cash").val(),
            report_user: $("#report_user").val(),
        }, 'POST','_blank');
    });
});

function onChangeBranch(){

    var branch_id = $("#report_office").val();
    $('#report_cash').empty();
    $('#report_cash').append('<option value="0">TODOS</option>');

    $.post(siteurl('cash/get_cash_by_branch'),{branch_id:branch_id},
    function (data) {
        var datos = JSON.parse(data);
        $.each(datos, function (i, item) {
                $('#report_cash').append('<option value="' + item.id + '">' + item.nombre + '</option>');
        });  
    });
}
/*Retorna la consulta de Stock de inventario de la Vista de BD*/
function get_report_cash_list() {

    $('#list_cash_report').DataTable({
        paging: false,
        info: false,
        filter: false,
        stateSave: true,
        processing: true,
        serverSide: true,
        destroy: true,
        'ajax': {
            "url": siteurl('report_cash/buscar_caja'),
            "type": 'post',
            "data": {
                report_start_date: $("#start_date").val(),
                report_end_date: $("#end_date").val(),
                report_office: $("#report_office").val(),
                report_cash: $("#report_cash").val(),
                report_user: $("#report_user").val(),
            },
            "error":function(a,b,c){
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        'columns': [
            {data: 'sucursal', class: 'text-center'},
            {data: 'caja', class: 'text-center'},
            {data: 'usuario', class: 'text-center'},
            {data: 'fecha_cierre', class: 'text-center'},
            {data: 'monto_apertura_bs', class: 'text-center'},
            {data: 'monto_apertura_sus', class: 'text-center'},
            {data: 'total_tarjeta', class: 'text-center'},
            {data: 'total_cheque', class: 'text-center'},
            {data: 'monto_cierre_bs', class: 'text-center'},
            {data: 'monto_cierre_sus', class: 'text-center'},
            {data: 'total_efective', class: 'text-center'}
        ],
        'aoColumnDefs': [ {
            "aTargets": [ 10 ],
            "mRender": function (data, type, full) {
                return data.toString().match(/\d+(\.\d{1,2})?/g)[0];
            }
        }],
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
            for (let index = 4; index < 11; index++) {
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
    });
    // tabla.ajax.reload();
}
