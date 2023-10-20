<?php
/**
 * Created by PhpStorm.
 * User: JANCO
 * Date: 20/10/2023
 * Time: 10:16 AM
 */

class Report_provider_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
    }

    /*obtener lista de productos para consulta*/
    public function get_report_provider_list($params = array())
    {

        $branch_office_report = $params["branch_office_report"];
        $warehouse_report = $params["warehouse_report"];
        $report_start_date = $params["report_start_date"];
        $report_end_date = $params["report_end_date"];

        $this->load->model('warehouse_model');
        $warehouse = $this->warehouse_model->get_warehouse_without_guarantee(get_branch_id_in_session());
        $search= isset($params['search'])? isset($params['search']): '';
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('p.producto_id as id,
                            p.codigo_producto as codigo,
                            p.nombre_comercial,
                            p.nombre_generico,
                            p.nombre_grupo,
                            p.nombre_marca,
                            p.codigo_modelo,
                            p.nombre_modelo,
                            p.nombre_unidad_medida,
                            p.dimension,
                            p.nombre_marca,
                            s.nombre as nombre_sucursal,
                            i.estado,
                            i.id as inventario_id,
                            a.id as almacen_id,
                            TRUNC(i.cantidad,0) as stock,
                            TRUNC(i.precio_costo,2) as precio_costo,
                            TRUNC(i.precio_venta,2) as precio_venta,
                            a.nombre as almacen,
                            pro.nombre as nombre_proveedor,
                            pro.direccion,
                            pro.telefono')
            ->from('vista_lista_producto p, inventario i, almacen a, sucursal s, producto_proveedor pv, proveedor pro')
            ->where('i.producto_id=p.producto_id')
            // ->where('i.producto_id!=106')
            ->where('i.almacen_id=a.id')
            ->where('a.sucursal_id=s.id')
            ->where('p.producto_id=pv.producto_id')
            ->where('pv.proveedor_id=pro.id')
            ->where('a.estado', ACTIVO)
            ->where('p.estado_producto', ACTIVO)
            ->where('i.estado', ACTIVO)
            ->where('p.producto_id>1');
            
        if ($branch_office_report != '0') {
            $this->db->where('a.sucursal_id =', $branch_office_report);
        }   
        if ($warehouse_report != '0') {
            $this->db->where('i.almacen_id =', $warehouse_report);
        }
        if($report_start_date != ''){
            $this->db->where('DATE(i.fecha_ingreso) >=',$report_start_date );
        }
        if($report_end_date!=''){
            $this->db->where('DATE(i.fecha_ingreso) <=',$report_end_date );
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
            $this->db->order_by('p.producto_id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            if ($search!=''){
                $this->db->group_start();
                $this->db->like('lower(p.codigo_producto)', strtolower($search));
                $this->db->or_like('lower(p.nombre_comercial)', strtolower($search));
                $this->db->or_like('lower(p.nombre_generico)', strtolower($search));
                $this->db->or_like('lower(p.nombre_grupo)', strtolower($search));
                $this->db->or_like('lower(p.nombre_modelo)', strtolower($search));
                $this->db->or_like('lower(p.dimension)', strtolower($search));
                $this->db->or_like('lower(p.imei1)', strtolower($search));
                $this->db->or_like('lower(p.imei2)', strtolower($search));
                $this->db->group_end();
            }

        }

        // Obtencion de resultados finales
        $draw = $this->input->post('draw');
        $data = $this->db->get()->result_array();

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_total,
            'data' => $data,
            'warehouse' => $warehouse
        );
        return $json_data;
    }

}