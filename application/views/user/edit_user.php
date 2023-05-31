<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 13/07/2017
 * Time: 02:33 AM
 */
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <form id="frm_edit_user" action="<?= site_url('user/edit_user') ?>"
                  method="post">
                <?php $this->view('user/form_content') ?>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/user.js') ?>"></script>