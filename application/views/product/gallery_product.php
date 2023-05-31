<?php
/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 01/04/2019
 * Time: 04:37 PM
 */
?>

<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">

            <div class="panel-heading cabecera_frm">
                <h4 class="panel-title titulo_frm">Galeria de Imagenes de Producto</h4>
            </div>

            <div class="body">
                <form id="frm_select_image">
                    <fieldset>
                        <legend align="left">Datos del producto</legend>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="id_product" name="id_product"
                                               value="<?= isset($product) ? $product->id : '' ?>" hidden>
                                        <label>Codigo del Producto:</label>
                                        <input type="text" class="form-control" id="codigo" name="codigo"
                                               value="<?= isset($product) ? $product->codigo : '' ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Nombre Generico</label>
                                        <input type="text" class="form-control" id="nombre_generico"
                                               name="nombre_generico"
                                               value="<?= isset($product) ? $product->nombre_generico : '' ?>"
                                               disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Nombre Comercial</label>
                                        <input type="text" class="form-control" id="nombre_comercial"
                                               name="nombre_comercial"
                                               value="<?= isset($product) ? $product->nombre_comercial : '' ?>"
                                               disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Color:</label>
                                        <input type="text" class="form-control" id="dimension" name="dimension"
                                               value="<?= isset($product) ? $product->dimension : '' ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Modelo:</label>
                                        <input type="text" class="form-control" id="modelo" name="modelo"
                                               value="<?= isset($product) ? $product->modelo : '' ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Marca:</label>
                                        <input type="text" class="form-control" id="marca" name="marca"
                                               value="<?= isset($marca) ? $marca->nombre : '' ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="row clearfix">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading with-border">
                                            <h2 class="panel-title"><i class="fa fa-camera"></i> Tomar Fotografia
                                            </h2>
                                        </div>
                                        <center>
                                            <video id="video_product"
                                                   style="height: max-content; width: max-content"></video>
                                            <br>
                                            <button id="btn_take_photo" style="align-content: center">Tomar foto
                                            </button>
                                        </center>
                                        <canvas id="canvas_product" style="display: none;"></canvas>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading with-border">
                                            <h2 class="panel-title"><i class="fa fa-file-picture-o"></i> Seleccionar
                                                Imagenes
                                            </h2>
                                        </div>
                                        <div class="panel-body">
                                            <p>Puede agregar imágenes que serán visualizadas en forma de galería.</p>
                                            <p>Para seleccionar multiples imagenes, presione ctrl + click.
                                                Posteriormente,
                                                click derecho
                                                sobre una
                                                imagen y selecciones "Select all"</p>
                                            <input name="files[]" type="file" multiple id="file_images_product">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <center>
                        <h4>IMAGENES DEL PRODUCTO GUARDADAS EN SISTEMA</h4>
                    </center>
                    <div class="row" id="image_preview_product"></div>

                    <div class="modal-footer">
                        <div hidden>
                            <button class="btn btn-success waves-effect" type="submit" id="btn_submit_galery"><i
                                        class="material-icons">save</i><span class="icon-name">Guardar Galeria</span>
                            </button>
                        </div>
                        <a href="<?= site_url('product') ?>" class="btn btn-danger waves-effect" type="submit"><i
                                    class="material-icons">cancel</i><span class="icon-name">Cancelar y
                                Salir</span></a>
                    </div>
                </form>

            </div>

        </div>
    </div>
    <script src="<?= base_url('jsystem/product_gallery.js') ?>"></script>
