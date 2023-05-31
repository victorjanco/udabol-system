<?php

/**
 * Created by PhpStorm.
 * User: Green Ranger
 * Date: 17/04/2018
 * Time: 04:15 PM
 */

class Report_inventory_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
    }

    /*Retorna la consulta de Stock de inventario de la Vista de BD*/
    public function get_data_report_stock_inventory($params = array())
    {
        /*parametros*/
        $report_warehouse = $params["report_warehouse"];
        $report_brand = $params["report_brand"];
        $report_model = $params["report_model"];
        $report_product = $params["report_product"];
        $service_product_id=$this->product_model->get_producto_type_service()->id;

        /*consulta vista*/
        $this->db->start_cache();
        // $this->db->select('i.*, a.nombre as nombre_almacen, v.nombre_marca, v.nombre_modelo, v.codigo_producto, v.codigo_barra, v.nombre_comercial, v.nombre_generico');
        $this->db->select('i.*, TRUNC(i.precio_venta,2) as precio_venta_truncate');
        $this->db->from('vista_inventario_sucursal i, almacen a');
        $this->db->where('i.producto_id !=', $service_product_id);
        $this->db->where('i.almacen_id =a.id');
        $this->db->where('a.estado',ACTIVO);
        $this->db->where('i.stock > 0');
        $this->db->where('i.sucursal_id', get_branch_id_in_session());

        if ($report_warehouse != '0') {
            $this->db->where('i.almacen_id =', $report_warehouse);
        }
        if ($report_brand != '0') {
            $this->db->where('i.marca_id =', $report_brand);
        }
        if ($report_model != '0') {
            $this->db->where('i.modelo_id =', $report_model);
        }
        if ($report_product != '0') {
            $this->db->where('i.producto_id =', $report_product);
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
            $this->db->order_by('i.nombre_comercial_producto', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('i.nombre_modelo', strtoupper($params['search']));
            $this->db->or_like('i.codigo_producto', strtoupper($params['search']));
            $this->db->or_like('i.nombre_comercial_producto', strtoupper($params['search']));
            $this->db->or_like('i.nombre_generico_producto', strtoupper($params['search']));
            $this->db->or_like('i.dimension_producto', strtoupper($params['search']));
            $this->db->or_like('lower(i.imei1)', strtolower($params['search']));
			$this->db->or_like('lower(i.imei2)', strtolower($params['search']));
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

