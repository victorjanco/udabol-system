<?php
/**
 * Created by PhpStorm.
 * User: reyesfuentesrenato (Green Ranger)
 * Date: 07/06/2018
 * Time: 11:03 AM
 */
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Pagos</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_payments" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center">ID</th>
                        <th class="text-center"><b>Nro. Pago</b></th>
                        <th class="text-center"><b>Cliente</b></th>
                        <th class="text-center"><b>Fecha</b></th>
                        <th class="text-center"><b>Monto Total</b></th>
                        <th class="text-center"><b>Observacion</b></th>
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
        get_payments_list();
    });
</script>
<script src="<?= base_url('jsystem/credit_payment.js'); ?>"></script>


