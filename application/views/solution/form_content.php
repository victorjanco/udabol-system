<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 14/7/2017
 * Time: 3:57 PM
 */
?>
<div class="row clearfix">
    <input id="id" name="id" type="text" value="<?= isset($solution) ? $solution->id : null; ?>" hidden>
    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 ">
        <label for="nombre" class="form-control-label">Nombre</label>
    </div>

    <div class="form-group ">
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

            <div class="form-line ">
                <input type="text" class="form-control" id="nombre" name="nombre"
                       value="<?= isset($solution) ? $solution->nombre : null; ?>">
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">

    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 ">
        <label for="descripcion" class="form-control-label">Descripcion</label>
    </div>
    <div class="form-group ">
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 ">
            <div class="form-line ">
                <input type="text" class="form-control" id="descripcion" name="descripcion"
                       value="<?= isset($solution) ? $solution->descripcion : null; ?>">
            </div>
        </div>
    </div>
</div>



