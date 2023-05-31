<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 20/03/2020
 * Time: 10:02 AM
 */ ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm ">
                <div>
                    <h2 class="panel-title titulo_frm">Visualizacion de Asignacion de Cajas</h2>
                </div>
            </div>
            
            <div class="body">
                <form id="frm_new_cash_user" name="frm_new_cash_user"
                action="<?= site_url('cash_user/register_cash_user') ?>"
                method="post">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Usuario</b>
                                    <input type="hidden" class="form-control" id="user_id"
                                                name="user_id"
                                                value="<?= $user->id; ?>">
                                    <input type="text" class="form-control" id="name"
                                                name="name"
                                                value="<?= $user->nombre; ?>">
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Caja</b>
                                    <select class="form-control" id="cash_id" name="cash_id">
                                        <?php foreach ($cashs as $row) : ?>
                                            <option value="<?= $row->id; ?>"><?= $row->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label style="font-size: 20pt;">Cajas Asignadas al Usuario</label>
                            </div>
                        </div>
                    </div>
                </form>
                    <div class="table-responsive">
                        <table id="table_detail_cash_user"
                            class="table table-striped table-hover table-bordered table-responsive">
                            <thead>
                            <tr>
                                <th >id</th>
                                <th width="40%">Usuario</th>
                                <th width="40%">Caja</th>
                                <th width="20%">Opcion</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
            </div>
                <div class="panel-footer" align="center">
                    <a id="cancel_sale" href="<?= site_url('cash_user') ?>" class="btn btn-danger waves-effect" type="submit">
                        <i class="fa fa-times"></i> Salir </a>
                </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var option="Sin Opcion!!";
        get_cash_user_enable(option);
    })
</script>
<script src="<?= base_url('jsystem/cash_user.js') ?>"></script>
