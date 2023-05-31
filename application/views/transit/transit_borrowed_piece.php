<?php
/**
 *
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato
 * Date: 16/07/2019
 * Time: 05:19 PM
 */

?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de repuestos prestados</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_borrowed_piece" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center"><b>ID</b></th>
                        <th class="text-center"><b>NRO PRESTAMO</b></th>
                        <th class="text-center"><b>FECHA PRESTAMO</b></th>
                        <th class="text-center"><b>USUARIO SOLICITANTE</b> </th>
                        <th class="text-center"><b>USUARIO ENTREGADOR</b> </th>
                        <th class="text-center"><b>TIPO</b> </th>
                        <th class="text-center"><b>DETALLE DE PRODUCTOS</b> </th>
                        <th class="text-center"><b>O.T.</b> </th>
                        <th class="text-center"><b>ESTADO</b> </th>
                        <th class="text-center"><b>OBSERVACION</b> </th>
                        <th class="text-center"><b>OPCIONES</b> </th>
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
        get_transit_borrowed_piece();
    });
</script>
<script src="<?= base_url('jsystem/transit_output.js'); ?>"></script>
