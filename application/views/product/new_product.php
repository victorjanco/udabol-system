<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 05:02 PM
 */
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <form id="frm_new_product" name="frm_new_product" action="<?= site_url('product/register_product') ?>"
                  method="post">
                <?php $this->view('product/form_content') ?>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/product.js') ?>"></script>