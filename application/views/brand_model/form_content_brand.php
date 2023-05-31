<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 17/07/2017
 * Time: 04:14 PM
 */
?>
<div class="modal-body">
    <div class="row clearfix">
        <input id="id_marca" name="id_marca" value="" hidden>
        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label">Nombre marca *</label>
        </div>
        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <textarea class="form-control" id="nombre_marca" name="nombre_marca"
                    placeholder="Ingrese el nombre de su marca economica"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>
    <button id="close_modal_registro_marca" type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i
                class="fa fa-times"></i> Cerrar
    </button>
</div>
