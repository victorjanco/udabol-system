<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 04:50 PM
 */
?>
<div class="block-header">
    
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Productos Inactivos</h2>
            </div>
            <div class="body">
                <div style="padding: 1%;" class="card-content table-responsive">
                    <table id="product_inactive_list" class="table table-striped table-bordered ">
                        <thead>
                        <th>ID</th>
                        <th class="text-center"><b>CÓDIGO</b></th>
                        <th class="text-center"><b>NOMBRE COMERCIAL</b></th>
                        <th class="text-center"><b>NOMBRE GENERICO</b></th>
                        <th class="text-center"><b>COLOR</b></th>
                        <th class="text-center"><b>PRECIO VENTA</b></th>
                        <th class="text-center"><b>GRUPO</b></th>
                        <th class="text-center"><b>SUB GRUPO</b></th>
                        <th class="text-center"><b>MODELO</b></th>
                        <th class="text-center"><b>MARCA</b></th>
                        <th class="text-center"><b>ESTADO</b></th>
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
    get_product_inactive_list();
});
</script>
<script src="<?= base_url('jsystem/product.js') ?>"></script>
