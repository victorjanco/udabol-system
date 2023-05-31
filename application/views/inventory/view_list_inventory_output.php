<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 18/7/2017
 * Time: 8:25 PM
 */
?>
<div class="block-header">
    <a type="button" id="btn_new_brand" class="btn btn-success" href="<?= site_url('inventory/new_output')?>"><i class="fa fa-plus"></i> Registrar nueva salida
    </a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Salidas registradas</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_output" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center"><b>NRO. SALIDA</b></th>
                        <th class="text-center"><b>TIPO DE SALIDA</b></th>
                        <th class="text-center"><b>FECHA DE SALIDA</b> </th>
                        <th class="text-center"><b>OBSERVACION</b></th>
                        <th class="text-center"><b>OPCIONES</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_view_inventory_output" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel" align="center">INFORMACION SALIDA INVENTARIO</h4>
            </div>
            <div class="modal-body">
                <input type="text" id="id_sale" name="id_sale" hidden>
                <div class="row clearfix" id="reception_data">
                </div>
            </div>
            <div class="modal-footer">
                <button id="close_modal_edit_service" type="button" class="btn btn-danger waves-effect"
                        data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        get_inventory_output_list();
    });

    function get_inventory_output_list() {
        var tabla = $('#list_output');

        tabla.DataTable({
            paging: true,
            info: true,
            filter: true,
            stateSave: true,
            processing: true,
            serverSide: true,
            'ajax': {
                "url": siteurl('inventory/get_inventory_output_list'),
                "type": 'post'
            },
            'columns': [
                {data: 'nro_salida_inventario', class: 'text-center'},
                {data: 'nombre_tipo_salida', class: 'text-center'},
                {data: 'fecha_registro', class: 'text-center'},
                {data: 'observacion', class: 'text-center'},
                {data: 'opciones', class: 'text-center'},

            ],
            "columnDefs": [
                {
                    targets: 4,
                    orderable: false,
                    render: function (data, type, row) {
                        if (row.estado !== 0) {
                            if(row.tipo_salida_inventario_id == 4 || row.tipo_salida_inventario_id == 5 || row.tipo_salida_inventario_id == 6){// no aprobado
                                return load_buttons_crud(26, 'form');
                            }else{//aprobado
                                return load_buttons_crud(25, 'form');
                            }
                            // return load_buttons_crud(11, 'formulario');
                            
                        } else {
                            return '<label>No tiene Opciones</label>';
                        }
                    }
                }
            ],
            "order": [[1, "asc"]],
        });
    }

    function view_register_form(element) {
        show_inventory_output(element);
    }

    function show_inventory_output(elemento) {
        var table = $(elemento).closest('table').DataTable();
        var current_row = $(elemento).parents('tr');
        if (current_row.hasClass('child')) {
            current_row = current_row.prev();
        }
        var data = table.row(current_row).data();
        var url = siteurl('inventory/view_inventory_output');
        $.redirect(url, {id: data['id']}, 'POST', '_self');
    }

    function delete_register(element) {
        delete_register_commom(element, 'inventory/disable_inventory_output');
    }
    function print_form(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    // $.redirect(siteurl('cash_output/print_cash_output'), {id: id}, 'POST', '_blank');
    print(id, 'inventory/print_inventory_output');
}
</script>

