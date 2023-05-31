 <?php
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="panel-heading cabecera_frm">
            <h4 class="panel-title titulo_frm">Nueva Dosificacion</h4>
        </div>
        <form id="frm_new_dosage" action="<?= site_url('dosage/register_dosage') ?>" method="post">
            <?php $this->view('dosage/form_content') ?>
        </form>
    </div>
</div>
<script src="<?= base_url('jsystem/dosage.js'); ?>"></script>
