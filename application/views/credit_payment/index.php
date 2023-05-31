<?php
/**
 * Created by PhpStorm.
 * User: Gomez
 * Date: 1/6/2018
 * Time: 16:34
 */ ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Deuda por Cobrar</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_credit_payment" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center">ID</th>
                        <th class="text-center"><b>Sucursal</b></th>
                        <th class="text-center"><b>Cliente</b></th>
                        <th class="text-center"><b>Fecha Vencimiento</b></th>
                        <th class="text-center"><b>Monto Venta</b></th>
                        <th class="text-center"><b>Monto Credito</b></th>
                        <th class="text-center"><b>Monto Saldo</b></th>
                        <th class="text-center"><b>Opciones</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script >
    $(document).ready(function () {
        get_credit_payment_list();
    });
</script>
<script src="<?= base_url('jsystem/credit_payment.js'); ?>"></script>
