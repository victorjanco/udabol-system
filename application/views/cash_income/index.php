<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 20/03/2020
 * Time: 10:02 AM
 */ ?>

<div class="block-header" id="container_buttons">
    <a class="btn btn-success" href="<?= site_url('cash_income/new_cash_income') ?>"><i class="fa fa-plus"></i> Nuevo Ingreso Caja</a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Ingreso de Caja</h2>
            </div>
            <div class="body table-responsive">
                <table id="list_cash_income" class="table table-striped table-bordered">
                    <thead>
                    <th>ID</th>
                    <th><b>NRO INGRESO</b></th>
                    <th><b>DETALLE</b></th>
                    <th><b>FECHA INGRESO</b></th>
                    <th><b>MONTO Bs</b></th>
                    <th><b>MONTO Sus</b></th>
                    <th><b>CAJA</b></th>
                    <th><b>FECHA MOD.</b></th>
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

<script src="<?= base_url('jsystem/cash_income.js'); ?>"></script>
    