<?php
/**
 * Created by PhpStorm.
 * User: mendoza
 * Date: 07/08/2017
 * Time: 04:54 PM
 */
?>
<div class="block-header">
    <a class="btn btn-success" href="<?=site_url('purchase/new_purchase')?>"><i class="fa fa-plus"></i>Nuevo Compra</a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Compras</h2>
            </div>
            <div class="body">
                <div style="padding: 1%;" class="card-content table-responsive">
                    <table id="purchase_list" class="table table-striped table-bordered ">
                        <thead>
                        <th>ID</th>
                        <th class="text-center"><b>Nro. compra</b></th>
                        <th class="text-center"><b>Monto subtotal</b></th>
                        <th class="text-center"><b>Monto total</b></th>
                        <th class="text-center"><b>Fecha compra</b></th>
                        <th class="text-center"><b>Proveedor</b></th>
                        <th class="text-center"><b>Estado</b></th>
                        <th class="text-center"><b>Opciones</b></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/purchase.js'); ?>"></script>
