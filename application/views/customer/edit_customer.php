<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 11/7/2017
 * Time: 1:52 PM
 */ ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="panel-heading cabecera_frm">
            <h4 class="panel-title titulo_frm">Actualizando Datos del Cliente</h4>
        </div>
        <form id="frm_edit_customer" action="<?= site_url('customer/modify_customer') ?>" method="post">
            <?php $this->view('customer/form_content') ?>
        </form>
    </div>
</div>
<script src="<?= base_url('jsystem/customer.js'); ?>"></script>