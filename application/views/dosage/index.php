<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 11/7/2017
 * Time: 1:51 PM
 */
?>
<div class="block-header">
    <a type="button" href="<?= site_url('dosage/new_dosage') ?>" class="btn btn-success waves-effect"><i class="fa fa-plus"></i> Nueva Dosificacion
    </a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Dosificacion Activas</h2>
            </div>
            <div class="body table-responsive">
                <table id="list_enable_dosage" class="table table-striped table-bordered ">
                    <thead>
                    <th class="text-center">ID</th>
                    <th class="text-center"><b>SUCURSAL</b></th>
                    <th class="text-center"><b>ACTIVIDAD</b></th>
                    <th class="text-center"><b>IMPRESORA</b></th>
                    <th class="text-center"><b>AUTORIZACION</b></th>
                    <th class="text-center"><b>FECHA DE REGISTRO</b></th>
                    <th class="text-center"><b>FECHA DE LIMITE</b></th>

                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Dosificacion Caducadas</h2>
            </div>
            <div class="body table-responsive">
                <table id="list_caducated_dosage" class="table table-striped table-bordered ">
                    <thead>
                    <th class="text-center">ID</th>
                    <th class="text-center"><b>SUCURSAL</b></th>
                    <th class="text-center"><b>ACTIVIDAD</b></th>
                    <th class="text-center"><b>IMPRESORA</b></th>
                    <th class="text-center"><b>AUTORIZACION</b></th>
                    <th class="text-center"><b>FECHA DE REGISTRO</b></th>
                    <th class="text-center"><b>FECHA DE LIMITE</b></th>

                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/dosage.js'); ?>"></script>