<?php


?>
<!--Form body-->
<input id="id" name="id" type="text" value="<?= isset($dosage) ? $dosage->id : null; ?>" hidden>
<div class="panel-body">
    <h3>Datos para la Dosificacion</h3>

    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="autorizacion">Autroizacion</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                <input type="text" class="form-control" id="autorizacion" name="autorizacion"
                       value=""
                       title="Numero de Autorizacion" placeholder="Numero de Autorizacion">
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="nro_inicio">Numero de Inicio</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" value="1" id="nro_inicio" name="nro_inicio"
                           value=""
                           placeholder="Numero de Inicio " title="Numero de Inicio">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="llave">Llave</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="llave" name="llave"
                           value=""
                           placeholder="Llave" title="Llave">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="fecha_solicitada">Fecha Solicitada</label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="date" class="form-control" id="fecha_solicitada" name="fecha_solicitada"
                           value=""
                           placeholder="Fecha Solicitada" title="Fecha Solicitada">
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="fecha_limite">Fecha Limite</label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="date" class="form-control" id="fecha_limite" name="fecha_limite"
                           value=""
                           placeholder="Fecha Limite" title="Fecha Limite">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="leyenda">Leyenda</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="leyenda" name="leyenda"
                           value=""
                           placeholder="Leyenda" title="Leyenda">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="actividad">Actividad</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <select class="form-control" id="actividad" name="actividad">
                        <option value="0">--Seleccione una Actividad--</option>
                        <?php foreach ($activity as $row) : ?>
                            <option value="<?= $row->id; ?>"><?= $row->nombre; ?></option>
                        <?php endforeach;?>
                    </select>

                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="sucursal">Sucursal</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <select class="form-control" id="sucursal" name="sucursal">
                        <option value="0">--Seleccione una Sucursal--</option>
                        <?php foreach ($branch as $row) : ?>
                            <option value="<?=$row->id; ?>" ><?= 'Nombre S.I.N.: '.$row->nombre.' / '.'Nombre Comercial: '.$row->nombre_comercial; ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="impresora">Impresora</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <select class="form-control" id="impresora" name="impresora">
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
