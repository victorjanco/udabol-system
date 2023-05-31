<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 31/03/2020
 * Time: 10:02 AM
 */ ?>

<div class="block-header" id="container_buttons">
    <a class="btn btn-success" href="<?= site_url('cash_output/new_cash_output') ?>"><i class="fa fa-plus"></i> Nuevo Egreso Caja</a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Egreso de Caja</h2>
            </div>
            <div class="body table-responsive">
                <table id="list_cash_output" class="table table-striped table-bordered">
                    <thead>
                    <th>ID</th>
                    <th><b>NRO EGRESO</b></th>
                    <th><b>DETALLE</b></th>
                    <th><b>FECHA EGRESO</b></th>
                    <th><b>CAJA</b></th>
                    <th><b>FECHA MOD.</b></th>
                    <th><b>MONTO BS</b></th>
                    <th><b>MONTO SUS</b></th>
                    <th><b>ESTADO</b></th>
                    <th><b>OPCIONES</b></th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/cash_output.js'); ?>"></script>
    