<?php
/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 24/05/2019
 * Time: 11:31 AM
 */
?>

<div class="block-header">
    <a type="button" class="btn btn-success" href="<?= site_url('transit/new_transit_output')?>">
        <i class="fa fa-plus"></i>
        Nuevo prestamo de pieza
    </a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Transito de Repuestos</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_output" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center"><b>ID</b></th>
                        <th class="text-center"><b>NRO PRESTAMO</b></th>
                        <th class="text-center"><b>FECHA PRESTAMO</b></th>
                        <th class="text-center"><b>USUARIO ENTREGADOR</b> </th>
                        <th class="text-center"><b>USUARIO SOLICITANTE</b> </th>
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
        get_transit_list();
    });
</script>
<script src="<?= base_url('jsystem/transit_output.js'); ?>"></script>
