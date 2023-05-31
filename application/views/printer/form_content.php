<?php


?>
<!--Form body-->
<input id="id" name="id" type="text" value="<?= isset($printer) ? $printer->id : null; ?>" hidden>
<div class="panel-body">
    <h3>Datos para la Impresora</h3>

    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="marca">Marca</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                <input type="text" class="form-control" id="marca" name="marca"
                       value=""
                       title="Marca" placeholder="Marca">
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="serial">Serial</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="serial" name="serial"
                           value=""
                           placeholder="Serial" title="Serial">
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="sucursal_id">Sucursal</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <select class="form-control" id="sucursal_id" name="sucursal_id">
                        <option value="0">::Seleccione una opcion</option>
                        <?php foreach ($branch as $row) : ?>
                            <option value="<?= $row->id; ?>"><?= $row->nombre; ?></option>
                        <?php endforeach;?>
                    </select>

                </div>
            </div>
        </div>
    </div>


</div>
<div class="panel-footer" align="center">
    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
    </button>
    <button type="reset" class="btn btn-warning waves-effect"><i class="fa fa-refresh"></i> Limpiar</button>
    <a type="button" href="<?= site_url('dosage/index') ?>" class="btn btn-danger waves-effect "><i
                class="fa fa-arrow-left"></i> Cancelar</a>
</div>
