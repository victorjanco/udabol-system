<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 27/07/2017
 * Time: 04:55 PM
 */
class Init_data extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
    }

    public function index()
    {
        /*$this->delete_database();*/
        $this->db->trans_begin();
        //$this->cargar_script_database();
//        $this->cargar_view_system();
//        $this->cargar_system_parameters();
//        $this->cargar_sucursal();
//        $this->cargar_tipo_producto();
//        $this->cargar_tipo_ingreso_inventario();
//        $this->cargar_tipo_almacen();
//        $this->cargar_almacen();
//        $this->cargar_proveedor();
//        $this->cargar_categoria();
        //$this->cargar_grupo();
        //$this->cargar_subgrupo();
        //$this->cargar_marca();
//        $this->cargar_modelo();
//        $this->cargar_unidad_medida();
//        $this->cargar_cargo();
//        $this->cargar_usuario();
//        $this->cargar_usuario_sucursal();
//        $this->cargar_menu();
//        $this->cargar_acceso();
//        $this->cargar_clientes();
        //$this->cargar_producto();
        //$this->cargar_producto_provee();
//        $this->cargar_agrupa();
        //$this->cargar_fallas();
        //$this->cargar_soluciones();
        //$this->cargar_tipo_servicio();
        //$this->cargar_servicio();


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            echo "Datos Registrados Correstamente ya Puede trabajar con el Sistema The Best";
        }

    }

    function update_sucursal_id_for_salida_inventario(){
        $this->db->trans_begin();
        $query = "UPDATE salida_inventario
                      SET sucursal_id = 1;";
        $this->db->query($query);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            echo "Datos Registrados Correstamente ya Puede trabajar con el Sistema The Best";
        }
    }

    function delete_space_codigo_trabajo(){
        $query = "UPDATE orden_trabajo
                  SET codigo_trabajo = REPLACE(codigo_trabajo, ' ', '');";
        $this->db->query($query);
    }

}