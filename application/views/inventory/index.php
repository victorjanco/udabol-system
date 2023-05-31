<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 18/7/2017
 * Time: 8:25 PM
 */
?>
<div class="block-header">
    <a type="button" id="btn_new_brand" class="btn btn-success" href="<?= site_url('inventory/type_inventory')?>"><i class="fa fa-plus"></i> Registrar nuevo ingreso
    </a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Ingresos registrados</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center">ID</th>
                        <th class="text-center">NRO. INGRESO</th>
                        <th class="text-center"><b>NOMBRE</b></th>
                        <th class="text-center"><b>FECHA DE INGRESO</b> </th>
                        <th class="text-center"><b>SUCURSAL</b></th>
                        <th class="text-center"><b>TIPO DE INGRESO</b></th>
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

<script src="<?= base_url('jsystem/inventory.js'); ?>"></script>
