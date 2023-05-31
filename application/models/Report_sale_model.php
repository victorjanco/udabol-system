<?php
/**
 * Created by PhpStorm.
 * User: Gomez
 * Date: 18/4/2018
 * Time: 2:43 PM
 */

class Report_sale_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');

	}

	public function get_report_sale_product($params = array())
	{
		$service_product_id = $this->product_model->get_producto_type_service()->id;


		$report_branch_office = $params["report_branch_office"];
		$report_date_start = $params["report_start_date"];
		$report_date_end = $params["report_end_date"];
		$report_brand = ($params["report_brand"]);
		$report_model = $params["report_model"];
		$report_product = $params["report_product"];
		$report_type_sale = $params["report_type_sale"];
		$report_customer = $params["report_customer"];
		$report_number_sale = $params["reporte_number_sale"];
		$report_number_reception = $params["reporte_number_reception"];
		$report_type_product = $params["report_type_product"];

		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('dv.id, 
							dv.cantidad,
							TRUNC(dv.precio_costo,2) as precio_costo,
							TRUNC(dv.precio_venta,2) as precio_venta,
                            dv.descripcion, 
                            dv.estado, 
                            dv.venta_id, 
                            dv.producto_id, 
                            vv.nro_venta, 
                            vv.codigo_recepcion,
                            DATE(vv.fecha_registro)as fecha_registro,
                            vv.nombre as nombre_cliente,
                            vv.tipo_venta,
                            vp.tipo_producto_id,
                            vp.nombre_marca,
                            vp.codigo_producto,
                            vp.nombre_modelo,
							vp.codigo_modelo,
							TRUNC((dv.precio_descuento),2) as descuento,
							TRUNC((dv.precio_venta_descuento),2) as precio_venta_descuento,
                            TRUNC((dv.total),2) as total,
                            TRUNC(((dv.total)-(dv.precio_costo * dv.cantidad)),2)as utilidad,
                             TRUNC(dv.precio_costo,2) as precio_compra')
			->from('vista_venta vv, detalle_venta dv, vista_lista_producto vp')
			->where('vv.id = dv.venta_id')
			->where('dv.producto_id = vp.producto_id')
			->where('vv.estado', ACTIVO)
			->where('vp.estado_producto', ACTIVO);
			//  ->where('vp.producto_id !=', $service_product_id)
			// ->where('vv.sucursal_id', get_branch_id_in_session());

		if ($report_branch_office != '0') {
			$this->db->where('vv.sucursal_id', $report_branch_office);
		}
		if ($report_brand != '0') {
			$this->db->where('vp.marca_id', $report_brand);
		}

		if ($report_model != '0') {
			$this->db->where('vp.modelo_id', $report_model);
		}

		if ($report_product != '0') {
			$this->db->where('vp.producto_id', $report_product);
		}

		if ($report_type_sale != '0') {
			$this->db->where('vv.tipo_venta', $report_type_sale);
		}

		if ($report_type_product != '') {
			$this->db->where('vp.tipo_producto_id', $report_type_product);
		}

		if ($report_customer != '0') {
			$this->db->where('vv.cliente_id', $report_customer);
		}

		if ($report_number_sale != '') {
			$this->db->where('vv.nro_venta', $report_number_sale);
		}

		if ($report_number_reception != '') {
			$this->db->where('vv.codigo_recepcion', 'O.T.' . $report_number_reception);
		}

		if (isset($report_date_start) && $report_date_start != '') {
			$this->db->where('DATE(vv.fecha_registro)>=', $report_date_start);
		}

		if (isset($report_date_end) && $report_date_end != '') {
			$this->db->where('DATE(vv.fecha_registro)<=', $report_date_end);
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
			if ($params['column'] == 'codigo_trabajo') {
				$this->db->order_by('id', $params['order']);
			} else {
				$this->db->order_by($params['column'], $params['order']);
			}
		} else {
			$this->db->order_by('vv.fecha_registro', 'ASC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->group_start();
			$this->db->like('lower(vv.nombre)', strtolower($params['search']));
			$this->db->or_like('lower(vp.imei1)', strtolower($params['search']));
			$this->db->or_like('lower(vp.imei2)', strtolower($params['search']));
			$this->db->group_end();
			$this->db->order_by('vv.fecha_registro', 'ASC');
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

	public function get_report_sale_product_for_export($params = array())
	{
		// $service_product_id=$this->product_model->get_producto_type_service()->id;
		$report_branch_office = $params["report_branch_office"];
		$report_date_start = $params["report_start_date"];
		$report_date_end = $params["report_end_date"];
		$report_brand = ($params["report_brand"]);
		$report_model = $params["report_model"];
		$report_product = $params["report_product"];
		$report_type_sale = $params["report_type_sale"];
		$report_customer = $params["report_customer"];
		$report_number_sale = $params["reporte_number_sale"];
		$report_number_reception = $params["reporte_number_reception"];
		$report_type_product = $params["report_type_product"];

		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('dv.id, 
							dv.cantidad,
							TRUNC(dv.precio_costo,2) as precio_costo,
							TRUNC(dv.precio_venta,2) as precio_venta,
                            dv.descripcion, 
                            dv.estado, 
                            dv.venta_id, 
                            dv.producto_id, 
                            vv.nro_venta, 
                            vv.codigo_recepcion,
                            DATE(vv.fecha_registro)as fecha_registro,
                            vv.nombre as nombre_cliente,
                            vv.tipo_venta,
                            vp.tipo_producto_id,
                            vp.nombre_marca,
                            vp.codigo_producto,
                            vp.nombre_modelo,
							vp.codigo_modelo,
							TRUNC((dv.precio_descuento),2) as descuento,
							TRUNC((dv.precio_venta_descuento),2) as precio_venta_descuento,
                            TRUNC((dv.total),2) as total,
                            TRUNC(((dv.total)-(dv.precio_costo * dv.cantidad)),2)as utilidad,
                             TRUNC(dv.precio_costo,2) as precio_compra')
			->from('vista_venta vv, detalle_venta dv, vista_lista_producto vp')
			->where('vv.id = dv.venta_id')
			->where('dv.producto_id = vp.producto_id')
			->where('vv.estado', ACTIVO)
			->where('vp.estado_producto', ACTIVO);
			// ->where('vp.producto_id !=', $service_product_id)
			// ->where('vv.sucursal_id', get_branch_id_in_session());

		if ($report_branch_office != '0') {
			$this->db->where('vv.sucursal_id', $report_branch_office);
		}
		if ($report_brand != '0') {
			$this->db->where('vp.marca_id', $report_brand);
		}

		if ($report_model != '0') {
			$this->db->where('vp.modelo_id', $report_model);
		}

		if ($report_product != '0') {
			$this->db->where('vp.producto_id', $report_product);
		}

		if ($report_customer != '0') {
			$this->db->where('vv.cliente_id', $report_customer);
		}

		if ($report_type_sale != '0') {
			$this->db->where('vv.tipo_venta', $report_type_sale);
		}

		if ($report_number_sale != '') {
			$this->db->where('vv.nro_venta', $report_number_sale);
		}

		if ($report_type_product != '') {
			$this->db->where('vp.tipo_producto_id', $report_type_product);
		}

		if ($report_number_reception != '') {
			$this->db->where('vv.codigo_recepcion', 'O.T.' . $report_number_reception);
		}

		if (isset($report_date_start) && $report_date_start != '') {
			$this->db->where('DATE(vv.fecha_registro)>=', $report_date_start);
		}

		if (isset($report_date_end) && $report_date_end != '') {
			$this->db->where('DATE(vv.fecha_registro)<=', $report_date_end);
		}
		$this->db->order_by('vv.fecha_registro', 'ASC');

		$this->db->stop_cache();
		$data = $this->db->get()->result_array();
		$this->db->flush_cache();
		return $data;
	}
	public function get_report_sale_user($params = array())
	{

		$report_date_start = $params["report_start_date"];
		$report_date_end = $params["report_end_date"];
		$report_branch_office = ($params["report_branch_office"]);
		$report_user = $params["report_user"];//----0
		$report_type_sale = $params["report_type_sale"];
		$report_sale_form = $params["report_sale_form"];
		// Se cachea	 la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select(' vv.id, 
                            vv.nro_venta, 
                            DATE(vv.fecha_registro) as fecha_registro,
                            vv.nombre as nombre_cliente,
							vv.tipo_venta,
							vv.total,
							vv.subtotal,
							vv.descuento,
							vv.nombre_usuario,
							vv.nombre_sucursal,
							TRUNC(SUM(dv.cantidad * dv.precio_costo),2) as precio_costo_total,
							TRUNC(SUM(dv.cantidad * dv.precio_descuento),2) as descuento_producto')
			->from('vista_venta vv')
			// ->where('vv.id = dv.venta_id')
			->join('detalle_venta dv', 'vv.id = dv.venta_id')
			->where('vv.estado', ACTIVO);
			
			

		if ($report_branch_office != '0') {
			$this->db->where('vv.sucursal_id', $report_branch_office);
		}

		if ($report_user != '0') {
			$this->db->where('vv.usuario_id', $report_user);
		}

		if ($report_type_sale != '0') {
			$this->db->where('vv.tipo_venta', $report_type_sale);
		}

		if (isset($report_date_start) && $report_date_start != '') {
			$this->db->where('DATE(vv.fecha_registro)>=', $report_date_start);
		}

		if (isset($report_date_end) && $report_date_end != '') {
			$this->db->where('DATE(vv.fecha_registro)<=', $report_date_end);
		}
		$this->db->group_by('vv.id, 
						vv.nro_venta, 
						vv.fecha_registro,
						vv.nombre,
						vv.tipo_venta,
						vv.total,
						vv.subtotal,
						vv.descuento,
						vv.nombre_usuario,
						vv.nombre_sucursal'
					);
		
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
				$this->db->order_by('id', $params['order']);
			} else {
				$this->db->order_by($params['column'], $params['order']);
			}
		} else {
			$this->db->order_by('vv.fecha_registro', 'DESC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->group_start();
			$this->db->like('lower(vv.nombre)', strtolower($params['search']));
			$this->db->group_end();
			$this->db->order_by('vv.fecha_registro', 'DESC');
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

	public function get_report_sale_user_for_export($params = array())
	{
		$report_date_start = $params["report_start_date"];
		$report_date_end = $params["report_end_date"];
		$report_branch_office = ($params["report_branch_office"]);
		$report_user = $params["report_user"];//----0
		$report_type_sale = $params["report_type_sale"];
		// $report_sale_form = $params["report_sale_form"];

		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select(' vv.id, 
                            vv.nro_venta, 
                            DATE(vv.fecha_registro) as fecha_registro,
                            vv.nombre as nombre_cliente,
							vv.tipo_venta,
							vv.total,
							vv.descuento,
							vv.subtotal,
							vv.nombre_usuario,
							vv.nombre_sucursal,
							TRUNC(SUM(dv.cantidad * dv.precio_costo),2) as precio_costo_total,
							TRUNC(SUM(dv.cantidad * dv.precio_descuento),2) as descuento_producto')
			->from('vista_venta vv')
			// ->where('vv.id = dv.venta_id')
			->join('detalle_venta dv', 'vv.id = dv.venta_id')
			->where('vv.estado', ACTIVO);

		if ($report_branch_office != '0') {
			$this->db->where('vv.sucursal_id', $report_branch_office);
		}

		if ($report_user != '0') {
			$this->db->where('vv.usuario_id', $report_user);
		}

		if ($report_type_sale != '0') {
			$this->db->where('vv.tipo_venta', $report_type_sale);
		}

		if (isset($report_date_start) && $report_date_start != '') {
			$this->db->where('DATE(vv.fecha_registro)>=', $report_date_start);
		}

		if (isset($report_date_end) && $report_date_end != '') {
			$this->db->where('DATE(vv.fecha_registro)<=', $report_date_end);
		}
		$this->db->group_by('vv.id, 
						vv.nro_venta, 
						vv.fecha_registro,
						vv.nombre,
						vv.tipo_venta,
						vv.total,
						vv.subtotal,
						vv.descuento,
						vv.nombre_usuario,
						vv.nombre_sucursal'
					);
		$this->db->order_by('vv.fecha_registro', 'ASC');
		$this->db->stop_cache();
		$data = $this->db->get()->result_array();
		$this->db->flush_cache();
		return $data;
	}
	public function get_facturas_lcv($params = array()){
		
        /*parametros*/
        $month = $params["month"];
		$year = $params["year"];
		$fecha= $year."-".$month;

        /*consulta vista*/
        $this->db->start_cache();
		$this->db->select("f.id, f.nro_factura, f.nro_autorizacion, TO_CHAR(f.fecha  :: DATE,'DD/MM/YYYY') as fecha, f.nit_cliente, f.nombre_cliente, f.importe_total_venta, f.importe_no_sujeto_iva, f.operacion_excenta, f.venta_tasa_cero, f.subtotal, f.descuento, f.importe_base_iva, f.iva, f.codigo_control, f.estado");
		//$this->db->select('f.*');
        $this->db->from('factura f');
        $this->db->where("TO_CHAR(f.fecha  :: DATE,'YYYY-MM') =",$fecha);
        $this->db->where('f.sucursal_id', get_branch_id_in_session());

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
            $this->db->order_by('f.nro_factura', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
          //  $this->db->like('f.nro_factura', strtoupper($params['search']));
            $this->db->or_like('f.nro_autorizacion', strtoupper($params['search']));
            $this->db->or_like('f.nombre_cliente', strtoupper($params['search']));
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
