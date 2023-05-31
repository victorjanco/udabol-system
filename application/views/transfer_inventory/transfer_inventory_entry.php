<?php
/**
 *  * Created by PhpStorm.
 * User: Ariel Alejandro Gomez Chavez ( @ArielGomez )
 * Date: 7/5/2018
 * Time: 7:13 PM
 */?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Traspaso Ingreso de Ingreso de Inventario</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_output" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center"><b>NRO TRASPASO</b></th>
                        <th class="text-center"><b>TIPO DE INGRESO</b></th>
                        <th class="text-center"><b>FECHA DE SALIDA</b> </th>
                        <th class="text-center"><b>OBSERVACION</b></th>
                        <th class="text-center"><b>SUCURSAL ORIGEN</b></th>
                        <th class="text-center"><b>SUCURSAL DESTINO</b></th>
                        <th class="text-center"><b>ALMACEN DESTINO</b></th>
                        <th class="text-center"><b>ESTADO APROBACION</b></th>
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
<script>
    $(document).ready(function () {
        get_tranfer_inventory_entry_list();
    });
    function view_register_form(elemento) {
        var table = $(elemento).closest('table').DataTable();
        var current_row = $(elemento).parents('tr');
        if (current_row.hasClass('child')) {
            current_row = current_row.prev();
        }
        var data = table.row(current_row).data();
        var url = siteurl('transfer_inventory/view_transfer_inventory_entry');
        $.redirect(url, {id: data['id']}, 'POST', '_self');
    }

    function aprobation_register(elemento){
        var table = $(elemento).closest('table').DataTable();
        var current_row = $(elemento).parents('tr');
        if (current_row.hasClass('child')) {
            current_row = current_row.prev();
        }
        var data = table.row(current_row).data();
        var url = siteurl('transfer_inventory/aprobation_inventory_entry');
        $.redirect(url, {id: data['id']}, 'POST', '_self');
    }
    function delete_register(element) {
        delete_register_commom(element, 'transfer_inventory/disable_trasfer_inventory_entry');
    }
</script>
<script src="<?= base_url('jsystem/transfer_inventory.js'); ?>"></script>
