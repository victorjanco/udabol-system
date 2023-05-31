<?php
/**
 * Created by PhpStorm.
 * User: Green Ranger
 * Date: 23/04/2018
 * Time: 02:12 PM
 */

class Report_warehouse_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
    }

    /*Retorna almacen de la Vista de BD*/
    public function get_data_report_warehouse($params = array())
    {
        /*parametros*/
        $report_warehouse = $params["report_warehouse"];
        $report_brand = $params["report_brand"];
        $report_model = $params["report_model"];
        $report_product = $params["report_product"];
        $service_product_id=$this->product_model->get_producto_type_service()->id;

        /*consulta vista*/
        $this->db->start_cache();
        $this->db->select('inventario_general.*, TRUNC((stock * precio_costo),2) AS total ');
        $this->db->from('inventario_general');
        $this->db->where('producto_id !=', $service_product_id);
        $this->db->where('sucursal_id', get_branch_id_in_session());

        if ($report_warehouse != '0') {
            $this->db->where('almacen_id =', $report_warehouse);
        }
        if ($report_brand != '0') {
            $this->db->where('marca_id =', $report_brand);
        }
        if ($report_model != '0') {
            $this->db->where('modelo_id =', $report_model);
        }
        if ($report_product != '0') {
            $this->db->where('producto_id =', $report_product);
        }
        $this->db->stop_cache();

        // Obtener la cantidad de registros NO filtrados.
        // Query builder se mantiene ya que se tiene almacenada la estructura de la consulta en memoria
        $records_total = count($this->db->get()->result_array());

        // Concatenar parametros enviados (solo si existen)
        if (array_key_exists('start', $params) && array_key_exists('limit', $params)) {
            $this->db->limit($params['limit']);
            $this->db->offset($params['start']);
        }

        if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
            $this->db->order_by($params['column'], $params['order']);
        } else {
            $this->db->order_by('nombre_comercial', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('nombre_comercial', strtoupper($params['search']));
            $this->db->or_like('codigo_modelo', strtoupper($params['search']));
            $this->db->or_like('nombre_modelo', strtoupper($params['search']));
            $this->db->or_like('nombre_generico', strtoupper($params['search']));
            $this->db->group_end();
        }

        // Obtencion de resultados finales
        $draw = $this->input->post('draw');
        $data = $this->db->get()->result_array();
        $this->db->flush_cache();

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_total,
            'data' => $data,
        );
        return $json_data;
    }


}