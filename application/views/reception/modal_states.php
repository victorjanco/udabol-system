<?php
$url_action = 'reception/register_reception';
if(isset($reception)){
    $url_action = 'reception/edit_reception_states';
}
?>
<div class="modal fade" id="receptiom_edit_states" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <h4 class="modal-title titulo_modal" id="largeModalLabel">Opciones de estados</h4>
            </div>
            <form id="frm_receptiom_edit_states" method="post">
                <div class="modal-body">
                    <div id="container_states">

                    </div>
                </div>
                <div class="modal-footer">
                    <!--<button type="submit" id="btn_register_device" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>-->
                    <button type="button" id="close_modal_state" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

