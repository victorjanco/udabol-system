<?php
/**
 * Created by PhpStorm.
 * User: Green Ranger
 * Date: 20/04/2018
 * Time: 10:16 AM
 */

class Report_product_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
    }

    /*Retorna los productos de inventario mostrado cantidad entrante y saliente de la Vista de BD*/
    public function get_data_report_product($params = array())
    {
        /*parametros*/
        $report_start_date = $params["report_start_date"];
        $report_end_date = $params["report_end_date"];
        $report_brand = $params["report_brand"];
        $report_model = $params["report_model"];
        $report_product = $params["report_product"];
        $report_warehouse = $params["report_warehouse"];
        $service_product_id=$this->product_model->get_producto_type_service()->id;

        /*consulta vista*/
        $this->db->start_cache();

        $this->db->select('v.nombre_marca, v.codigo_producto, v.nombre_comercial, v.codigo_modelo, v.nombre_modelo, TRUNC(v.precio_venta,2) as precio_venta, i.id AS inventario_id,
         i.fecha_modificacion AS fecha, i.precio_costo, i.cantidad_ingresada as ingresada, a.id as almacen_id, a.nombre as nombre_almacen');
        $this->db->from('vista_lista_producto v, inventario i, ingreso_inventario iv, almacen a');
        $this->db->where('i.producto_id=v.producto_id');
        $this->db->where('i.ingreso_inventario_id=iv.id');
        $this->db->where('i.almacen_id=a.id');
        $this->db->where('iv.estado',ACTIVO);
        $this->db->where('v.estado_producto',ACTIVO);
        $this->db->where('i.estado',ACTIVO);
        $this->db->where('v.producto_id !=', $service_product_id);
        $this->db->where('iv.sucursal_id', get_branch_id_in_session());

        if ($report_warehouse != '0') {
            $this->db->where('i.almacen_id =', $report_warehouse);
        }
        if($report_start_date != ''){
            $this->db->where('DATE(i.fecha_modificacion) >=',$report_start_date );
        }
        if($report_end_date!=''){
            $this->db->where('DATE(i.fecha_modificacion) <=',$report_end_date );
        }
        if ($report_brand != '0') {
            $this->db->where('v.marca_id =', $report_brand);
        }
        if ($report_model != '0') {
            $this->db->where('v.modelo_id =', $report_model);
        }
        if ($report_product != '0') {
            $this->db->where('v.producto_id =', $report_product);
        }


        $this->db->order_by('fecha', 'ASC');
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
			if ($params['column'] == 'codigo_trabajo') {
				$this->db->order_by('v.producto_id', $params['order']);
			} else {
				$this->db->order_by($params['column'], $params['order']);
			}
		} else {
			$this->db->order_by('v.fecha_registro', 'DESC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->group_start();
			$this->db->like('lower(v.nombre_comercial)', strtolower($params['search']));
			$this->db->or_like('v.codigo_producto', strtolower($params['search']));
			$this->db->group_end();
			$this->db->order_by('v.fecha_registro', 'DESC');
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

    /*Retorna los productos de inventario mostrado cantidad entrante y saliente de la Vista de BD*/
    public function get_data_report_product_reason_list($params = array())
    {
        /*parametros*/
        $report_brand = $params["report_brand"];
        $report_model = $params["report_model"];
        $report_product = $params["report_product"];
        $start_date = $params["start_date"];
        $end_date = $params["end_date"];

        /*consulta vista*/
        $this->db->start_cache();
        $this->db->select('v.codigo_producto, v.nombre_comercial, v.codigo_modelo, v.nombre_modelo,
                            v.nombre_marca, v.nombre_grupo, v.dimension, n.observacion, t.nombre as nombre_tipo_motivo');
        $this->db->from('vista_lista_producto v , no_recepcionado n, tipo_motivo t');
        $this->db->where('v.tipo_producto_id', 1);
        $this->db->where('v.estado_producto', ACTIVO);
        $this->db->where('n.producto_id=v.producto_id');
        $this->db->where('n.tipo_motivo_id=t.id');
        $this->db->where('t.tipo', TIPO_MOTIVO_PRODUCTO);

        if ($report_brand != '0') {
            $this->db->where('v.marca_id =', $report_brand);
        }
        if ($report_model != '0') {
            $this->db->where('v.modelo_id =', $report_model);
        }
        if ($report_product != '0') {
            $this->db->where('v.producto_id =', $report_product);
        }
        if($start_date != ''){
            $this->db->where('DATE(n.fecha_registro) >=',$start_date );
        }
        if($end_date!=''){
            $this->db->where('DATE(n.fecha_registro) <=',$end_date );
        }

        $this->db->order_by('v.producto_id', 'ASC');
        $this->db->stop_cache();

        // Obtener la cantidad de registros NO filtrados.
        // Query builder se mantiene ya que se tiene almacenada la estructura de la consulta en memoria
        $records_total = count($this->db->get()->result_array());

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