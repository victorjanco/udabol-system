<?php

class Sale_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->model('inventory_model');
		$this->load->model('user_model');
		$this->load->model('office_model');
		$this->load->model('company_model');
		$this->load->model('customer_model');
		$this->load->model('dosage_model');
		$this->load->model('reception_model');
		$this->load->model('order_work_model');
		$this->load->model('model_model');
		$this->load->model('cash_income_model');
		$this->load->model('cash_income_type_model');
	}

	/*Obtener datos de la venta apartit del venta_id*/
	public function get_sale_id($sale_id)
	{
		return $this->db->get_where('venta', array('id' => $sale_id))->row();
	}

	/*Obtener datos del factura apartit del factura_id*/
	public function get_invoice_id($invoice_id)
	{
		return $this->db->get_where('factura', array('id' => $invoice_id))->row();
	}

	/*Obtener datos del factura apartit del factura_id*/
	public function get_invoice_sale_by_invoice_id($invoice_id)
	{
		return $this->db->get_where('factura_venta', array('factura_id' => $invoice_id))->result();
	}

	/*Obtener datos de la fila factura_venta a partir de la factura_id*/
	public function get_invoice_sale($invoice_id)
	{
		return $this->db->get_where('factura_venta', array('factura_id' => $invoice_id))->row();
	}

	/*Obtener datos del detalle de la venta apartit del cliente_id*/
	public function get_detail_sale_id($sale_id)
	{
		// $group = 0;
		// if ($group = 0) {
		// 	$this->db->select('descripcion, SUM(cantidad)as cantidad, precio_venta, precio_costo, SUM(total)as total, producto_id')
		// 		->from('detalle_venta')
		// 		->where('venta_id', $sale_id);
		// 	$this->db->group_by(array('producto_id', 'descripcion', 'precio_venta','precio_costo'));
		// 	$data = $this->db->get()->result();
		// } else {
		// 	$this->db->select('descripcion, SUM(cantidad)as cantidad, precio_venta , precio_costo, SUM(total)as total, producto_id')
		// 		->from('detalle_venta')
		// 		->where('venta_id', $sale_id);
		// 	$this->db->group_by(array('producto_id', 'descripcion', 'precio_venta','precio_costo'));
		// 	$data = $this->db->get()->result();
		// }
		$this->db->select('dv.*, p.codigo as codigo_producto, p.nombre_comercial as nombre_producto')
				// ->from('detalle_venta')
				->from('detalle_venta dv, producto p')
				->where('dv.producto_id=p.id')
				->where('dv.venta_id', $sale_id);
			// $this->db->group_by(array('producto_id', 'descripcion', 'precio_venta','precio_costo', 'precio_venta_descuento'));
		$data = $this->db->get()->result();
		return $data;

	}

	public function verify_order_work_by_sale_id($sale_id)
	{
		$this->db->select('*')
			->from('venta_orden_trabajo')
			->where('venta_id', $sale_id);
		$order_work = $this->db->get();
		if ($order_work->num_rows() > 0) {
			return true;
		} else {
			return false;
		}

	}

	/*Obtener datos del cliente apartit del cliente_id*/
	public function get_sale_branch_office_number($sale_id, $branch_office_id)
	{
		return $this->db->get_where('venta_sucursal', array('venta_id' => $sale_id, 'sucursal_id' => $branch_office_id))->row();
	}

	/*Obtener lista de venta para cargar la lista de dataTable*/
	public function get_sale_note_list($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('v.*,tipo_venta,c.nombre,vs.nro_venta,u.usuario,u.nombre as nombre_usuario')
			->from('venta v, cliente c, venta_sucursal vs,usuario u')
			->where('v.cliente_id=c.id')
			->where('v.usuario_id=u.id')
			->where('v.facturado=0')
			->where('v.tipo_venta=v.tipo_venta')
			->where('v.sucursal_id', get_branch_id_in_session())
			->where('vs.venta_id=v.id');

		if ($params['sale_number'] != '') {
			$this->db->where('vs.nro_venta', $params['sale_number']);
		}

		if (isset($params['sale_date_start']) && $params['sale_date_start'] != '') {
			$this->db->where('DATE(v.fecha_registro) >=', $params['sale_date_start']);
		}
		if (isset($params['sale_date_end']) && $params['sale_date_end'] != '') {
			$this->db->where('DATE(v.fecha_registro) <=', $params['sale_date_end']);
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
			$this->db->order_by('v.fecha_registro', 'DESC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->group_start();
			$this->db->like('lower(c.nombre)', strtolower($params['search']));
			$this->db->or_like('lower(c.nombre_factura)', strtolower($params['search']));
			$this->db->or_like('lower(v.codigo_recepcion)', strtolower($params['search']));
			$this->db->or_like('lower(u.nombre)', strtolower($params['search']));
			$this->db->or_like('lower(u.usuario)', strtolower($params['search']));

			$this->db->group_end();
			$this->db->order_by('v.fecha_registro', 'DESC');
		}

		// Obtencion de resultados finales
		$draw = $this->input->post('draw');
		$data = $this->db->get()->result_array();

		$json_data = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_total,
			'data' => $data,
		);
		return $json_data;
	}

	public function get_sale_invoice_list($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('f.*,f.nro_factura,u.usuario,u.nombre as nombre_usuario')
			->from('factura f,usuario u')
			->where('f.usuario_id=u.id')
			->where('f.sucursal_id', get_branch_id_in_session());

		if ($params['invoice_number'] != '') {
			$this->db->where('f.nro_factura', intval($params['invoice_number']));
		}

		if (isset($params['sale_date_start']) && $params['sale_date_start'] != '') {
			$this->db->where('DATE(f.fecha) >=', $params['sale_date_start']);
		}
		if (isset($params['sale_date_end']) && $params['sale_date_end'] != '') {
			$this->db->where('DATE(f.fecha) <=', $params['sale_date_end']);
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
			$this->db->order_by('f.fecha', 'DESC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->group_start();
			$this->db->like('lower(f.nombre_cliente)', strtolower($params['search']));
			$this->db->or_like('lower(f.nit_cliente)', strtolower($params['search']));
			$this->db->or_like('lower(u.nombre)', strtolower($params['search']));
			$this->db->or_like('lower(u.usuario)', strtolower($params['search']));

			$this->db->group_end();
			$this->db->order_by('f.fecha', 'DESC');
		}

		// Obtencion de resultados finales
		$draw = $this->input->post('draw');
		$data = $this->db->get()->result_array();

		$json_data = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_total,
			'data' => $data,
		);
		return $json_data;
	}

	public function register_sale()
	{
		try {
			$response = array(
				'success' => FALSE,
				'messages' => array(),
				'login' => FALSE,
				'dosage_message' => '',
				'dosage' => FALSE,
				'cash' => FALSE
			);
			$customer_id = $this->input->post('id_customer');
			$customer_name = $this->input->post('nombre_factura');
			$customer_nit = $this->input->post('nit');
			
			if($customer_name!='' && $customer_nit!=''){
				if($this->customer_model->exists_customer_by_name_nit($customer_nit, $customer_name)){
					$customer = $this->customer_model->get_customer_id($customer_id);
					$customer_id = $customer->id;
				}else{
					$data_customer = array(
						'tipo_cliente' => 0,
						'codigo_cliente' => '0',
						'ci' => $customer_nit,
						'nit' => $customer_nit,
						'nombre' => text_format($customer_name),
						'nombre_factura' => $customer_name,
						'fecha_nacimiento' => date('Y-m-d H:i:s'),
						'fecha_registro' => date('Y-m-d H:i:s'),
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'sincronizado' => 0,
						'estado' => get_state_abm('ACTIVO'),
						'sucursal_id' => get_branch_id_in_session(),
						'usuario_id' => get_user_id_in_session(),
						'user_updated' => get_user_id_in_session()
					);
					
					$this->db->insert('cliente', $data_customer);
					$customer = $this->db->get_where('cliente', $data_customer)->row();
					$customer_id = $customer->id;
				}
			}else{
				$customer_id = 1;
				$customer_name = 'SN';
				$customer_nit = 0;
			}
	
			if (verify_session()) {
	
				// if(verify_cash_session()){
					$sale_subtotal = floatval($this->input->post('sale_subtotal'));
					$sale_discount = floatval($this->input->post('sale_discount'));
					$sale_total = floatval($this->input->post('sale_total'));
					$sale_type = $this->input->post('sale_type');
					$date_expiration = $this->input->post('date_expiration');
					$glosa = $this->input->post('glosa');
	
	
					$number_rows = count($this->input->post('id_product'));
					$product_id_array = $this->input->post("id_product");
					$product_name_array = $this->input->post("name_product");
					$cost_price_array = $this->input->post("price_cost");
					$product_price_array = $this->input->post("price_product");
					$product_quantity_array = $this->input->post("quantity_product");
					$price_discount_array = $this->input->post("price_discount");
					$product_price_discount_array = $this->input->post("product_price_discounts");
	
					$total_product_array = $this->input->post("total_product");
					$branch_office_id_array = $this->input->post("id_branch_office");
					$warehouse_id_array = $this->input->post("id_warehouse");
					$session_id_array = $this->input->post("id_session");
					$user_id_array = $this->input->post("id_user");
	
	
					$today = date('Y-m-d H:i:s');
					$invoce_day = date('Y-m-d');
					$data_sale = [];
					$data_sale["tipo_venta"] = $sale_type;
					$data_sale["fecha_registro"] = $today;
					$data_sale["subtotal"] = number_format($sale_subtotal, 2, '.', '');
					$data_sale["descuento"] = number_format($sale_discount, 2, '.', '');
					$data_sale["total"] = number_format($sale_total, 2, '.', '');
					$data_sale["glosa"] = $glosa;
					$data_sale["sincronizado"] = 0;
					$data_sale["estado"] = ACTIVO;
					if ($sale_type == 'FACTURA') {
						$data_sale["facturado"] = 1;
					} else {
						$data_sale["facturado"] = 0;
					}
					$data_sale["codigo_recepcion"] = "";
					$data_sale["cliente_id"] = $customer_id;
					$data_sale["sucursal_id"] = get_branch_id_in_session();
					$data_sale["usuario_id"] = get_user_id_in_session();
					$data_sale["user_updated"] = get_user_id_in_session();
	
					$data_sale_inventory = [];
					$data_sale_inventory["fecha_registro"] = $today;
					$data_sale_inventory["fecha_modificacion"] = $today;
					$data_sale_inventory["sincronizado"] = 0;
					$data_sale_inventory["observacion"] = 'La salida de inventario desde el formulario de ventas';
					$data_sale_inventory["estado"] = ACTIVO;
					$data_sale_inventory["tipo_salida_inventario_id"] = 1;
					$data_sale_inventory["sucursal_id"] = get_branch_id_in_session();
					$data_sale_inventory["nro_salida_inventario"] = $this->last_number_inventory_output();
					$data_sale_inventory["user_created"] = get_user_id_in_session();
					$data_sale_inventory["user_updated"] = get_user_id_in_session();
	
					$this->db->trans_begin();
	
					$this->_insert_sale($data_sale);
					$sale_entry_inserted = $this->_get_sale($data_sale);
					////////////////////////////////////INGRESO CAJA/////////////////////////////////////////
					if ($sale_type != 'VENTA CREDITO'){
						$monto_efectivo = $this->input->post('monto_efectivo');
						$monto_bs = $this->input->post('monto_bs');
						$monto_sus = $this->input->post('monto_sus');
						$monto_cheque = $this->input->post('monto_cheque');
						$monto_tarjeta = $this->input->post('monto_tarjeta');
						$monto_cambio = $this->input->post('monto_cambio');
	
						if (strlen($monto_efectivo) == 0) {
							$monto_efectivo = 0;
						}
						if (strlen($monto_bs) == 0) {
							$monto_bs = 0;
						}
						if (strlen($monto_sus) == 0) {
							$monto_sus = 0;
						}
						if (strlen($monto_cheque) == 0) {
							$monto_cheque = 0;
						}
						if (strlen($monto_tarjeta) == 0) {
							$monto_tarjeta = 0;
						}
						if (strlen($monto_cambio) == 0) {
							$monto_cambio = 0;
						}
						// $data_cash_income = array(
						// 	'nro_transaccion'=>$this->cash_income_model->get_number_transaction(),
						// 	'nro_ingreso'=> strval($this->cash_income_model->get_number_transaction()),
						// 	'detalle' => 'INGRESO POR VENTAS',
						// 	'monto_venta' => number_format($sale_total, 2, '.', ''),
						// 	'monto_bs' => number_format($monto_bs, 2, '.', ''),
						// 	'monto_sus' => number_format($monto_sus, 2, '.', ''),
						// 	'monto_tarjeta' => number_format($monto_tarjeta, 2, '.', ''),
						// 	'monto_cheque' => number_format($monto_cheque, 2, '.', ''),
						// 	'monto_efectivo' => number_format($monto_efectivo, 2, '.', ''),
						// 	'monto_cambio' => number_format($monto_cambio, 2, '.', ''),
						// 	'fecha_ingreso' => date('Y-m-d'),
						// 	'fecha_registro' => date('Y-m-d H:i:s'),
						// 	'fecha_modificacion' => date('Y-m-d H:i:s'),
						// 	'estado' => ACTIVO,
						// 	'tipo_ingreso_caja_id'=> $this->cash_income_type_model->get_first()->id,
						// 	'caja_id'=> get_session_cash_id(),
						// 	'apertura_caja_id'=> get_session_cash_aperture_id(),
						// 	'sucursal_id' => get_branch_id_in_session(),
						// 	'user_created' => get_user_id_in_session(),
						// 	'user_updated' => get_user_id_in_session(),
						// );
						// $this->cash_income_model->_insert_cash_income($data_cash_income);
						// $cash_income_inserted = $this->cash_income_model->_get_cash_income($data_cash_income);
						// $data_cash_income_sale = array(
						// 	'ingreso_caja_id'=>$cash_income_inserted->id,
						// 	'venta_id'=> $sale_entry_inserted->id
						// );
						// $this->_insert_cash_income_sale($data_cash_income_sale);
					}
					/////////////////////////////////////////////////////////////////////////////////////////////
					$this->_insert_sale_inventory($data_sale_inventory);
					$sale_inventory_entry_inserted = $this->_get_sale_inventory($data_sale_inventory);
	
					$data_sale_output_inventory["venta_id"] = $sale_entry_inserted->id;
					$data_sale_output_inventory["salida_inventario_id"] = $sale_inventory_entry_inserted->id;
					$this->_insert_sale_output_inventory($data_sale_output_inventory);
					$sale_id = $sale_entry_inserted->id;
					for ($index = 0; $index < $number_rows; $index++) {
						$quantity = $product_quantity_array[$index];
						$product_id = $product_id_array[$index];
						$warehouse_id = $warehouse_id_array[$index];
						$branch_office_id = $branch_office_id_array[$index];
						$session_id = $session_id_array[$index];
						$user_id = $user_id_array[$index];
						$output_inventory_id = $sale_inventory_entry_inserted->id;
						$verify_quantity = $quantity;
	
						$direct_discount_inventory = $this->verify_stock_decrese_full_inventory($warehouse_id, $product_id, $quantity);
						if ($direct_discount_inventory != null) {
							$inventory_id = $direct_discount_inventory->id;
							$stock_inventory = $direct_discount_inventory->cantidad;
							$data_detail_sale_inventory["cantidad"] = $product_quantity_array[$index];
							$data_detail_sale_inventory["cantidad_antigua"] = $stock_inventory;
							$data_detail_sale_inventory["precio_costo"] = $cost_price_array[$index];
							$data_detail_sale_inventory["precio_venta"] = $product_price_array[$index];
							$data_detail_sale_inventory["observacion"] = "Salida de inventario por venta";
							$data_detail_sale_inventory["salida_inventario_id"] = $output_inventory_id;
							$data_detail_sale_inventory["inventario_id"] = $inventory_id;
							$this->_insert_detail_sale_inventory($data_detail_sale_inventory);

							if ($quantity > $stock_inventory) {
								throw new Exception("Inventario menor a lo que quiere retirar");
							}
							$quantity_update = $stock_inventory - $quantity;
							$this->_update_stock_inventory($inventory_id, $quantity_update);
							$this->inventory_model->update_branch_office_inventory($inventory_id);

						} else {
							$discount_inventory_for_lote = $this->stock_decrese_inventory($warehouse_id, $product_id);
							foreach ($discount_inventory_for_lote as $row) {
								$inventory_id = $row['id'];
								$stock_inventory = $row['cantidad'];
								/*verifica bien pero abria que filtra la salida rapido cuando quantity sea =0*/
								if ($quantity > 0) {
									if ($stock_inventory <= $quantity) {
										$data_detail_sale_inventory["cantidad"] = $stock_inventory;
										$data_detail_sale_inventory["cantidad_antigua"] = $stock_inventory;
										$data_detail_sale_inventory["precio_costo"] = $cost_price_array[$index];
										$data_detail_sale_inventory["precio_venta"] = $product_price_array[$index];
										$data_detail_sale_inventory["observacion"] = "Salida de inventario por venta";
										$data_detail_sale_inventory["salida_inventario_id"] = $output_inventory_id;
										$data_detail_sale_inventory["inventario_id"] = $inventory_id;
										$this->_insert_detail_sale_inventory($data_detail_sale_inventory);
										$quantity_update = 0;
										$this->_update_stock_inventory($inventory_id, $quantity_update);
										/////////////////////////////////////////////
										$quantity = $quantity - $stock_inventory;
										$this->inventory_model->update_branch_office_inventory($inventory_id);
									} else { //$stock_inventory > $quantity
										$data_detail_sale_inventory["cantidad"] = $quantity;
										$data_detail_sale_inventory["cantidad_antigua"] = $stock_inventory;
										$data_detail_sale_inventory["precio_costo"] = $cost_price_array[$index];
										$data_detail_sale_inventory["precio_venta"] = $product_price_array[$index];
										$data_detail_sale_inventory["observacion"] = "Salida de inventario por venta";
										$data_detail_sale_inventory["salida_inventario_id"] = $output_inventory_id;
										$data_detail_sale_inventory["inventario_id"] = $inventory_id;
										$this->_insert_detail_sale_inventory($data_detail_sale_inventory);
										if ($quantity > $stock_inventory) {
											throw new Exception("Inventario menor a lo que quiere retirar");
										}
										$quantity_update = $stock_inventory - $quantity;
										$this->_update_stock_inventory($inventory_id, $quantity_update);
										$quantity = 0;
										$this->inventory_model->update_branch_office_inventory($inventory_id);
									}
								}
	
							}
						}
						$product_object=$this->product_model->get_product_by_id($product_id_array[$index]);
						// $data_detail_sale["descripcion"] = '(' . $product_object->grupo . ')'. $product_name_array[$index] . '(' . $product_object->nombre_marca . ')';
						$data_detail_sale["descripcion"] = '(' . $product_object->grupo . ')'. $product_object->nombre_generico . '(' . $product_object->nombre_marca . ')';
						$data_detail_sale["cantidad"] = $product_quantity_array[$index];
						$data_detail_sale["precio_costo"] = $cost_price_array[$index];
						$data_detail_sale["precio_venta"] = $product_price_array[$index];
						$data_detail_sale["precio_descuento"] = $price_discount_array[$index];
						$data_detail_sale["precio_venta_descuento"] = $product_price_discount_array[$index];
						$data_detail_sale["total"] = $total_product_array[$index];
						$data_detail_sale["estado"] = ACTIVO;
						$data_detail_sale["venta_id"] = $sale_entry_inserted->id;
						$data_detail_sale["producto_id"] = $product_id_array[$index];
						$this->_insert_detail_sale($data_detail_sale);
						$this->delete_row_detail_virtual($quantity, $branch_office_id, $warehouse_id, $product_id, $user_id, $session_id);
					}
					$number_sale = $this->last_sale_number();
					$data_sale_branch_office["venta_id"] = $sale_entry_inserted->id;
					$data_sale_branch_office["sucursal_id"] = get_branch_id_in_session();
					$data_sale_branch_office["nro_venta"] = $number_sale;
					$this->_insert_sale_branch_office($data_sale_branch_office);
	
					$this->delete_detail_virtual(get_branch_id_in_session(), get_user_id_in_session(), get_session_id());
					if ($sale_type == 'FACTURA') {
						$invoice_branch_office_id = $sale_entry_inserted->sucursal_id;
						$invoice_printer_id = 1;
						$invoice_activity_id = 1;
						$assignment_dosage = $this->dosage_model->get_assignment_dosage($invoice_branch_office_id, $invoice_activity_id, $invoice_printer_id);
						if ($assignment_dosage != null) {
							$assignment_dosage_id = $assignment_dosage->id;
							$dosage = $this->dosage_model->get_active_dosage_by_assignment_id($assignment_dosage_id);
	
							if ($dosage != null) {
								$dosage_id = $dosage->id;
								$dosage_nro_authorization = $dosage->autorizacion;
								$dosage_key = $dosage->llave;
								$ice_total = 0;
								$excenta_operation = 0;
								$zero_rate_sale = 0;
								$subtotal = $sale_subtotal - $ice_total - $excenta_operation - $zero_rate_sale;
								$total_base_debit_iva = $subtotal - $sale_discount;
	
								$invoice_number = $this->get_invoice_number_by_dosage_id($dosage_id);
								$control_code = $this->generar_codigo_control($invoce_day, trim($dosage_nro_authorization), $invoice_number, trim($customer_nit), number_format($total_base_debit_iva, 2, '.', ''), trim($dosage_key));
	
								$data_invoice = [];
								$data_invoice["nro_factura"] = $invoice_number;
								$data_invoice["fecha"] = $invoce_day;
								$data_invoice["nro_autorizacion"] = $dosage_nro_authorization;
								$data_invoice["nit_cliente"] = strval($customer_nit);
								$data_invoice["nombre_cliente"] = $customer_name;
								$data_invoice["importe_total_venta"] = $sale_subtotal;
								$data_invoice["importe_no_sujeto_iva"] = $ice_total;
								$data_invoice["operacion_excenta"] = $excenta_operation;
								$data_invoice["venta_tasa_cero"] = $zero_rate_sale;
								$data_invoice["subtotal"] = $subtotal;
								$data_invoice["descuento"] = $sale_discount;
								$data_invoice["importe_base_iva"] = $total_base_debit_iva;
								$data_invoice["iva"] = $total_base_debit_iva * 0.13;
								$data_invoice["codigo_control"] = $control_code;
								$data_invoice["sincronizado"] = 0;
								$data_invoice["estado"] = 'V';
								$data_invoice["dosificacion_id"] = $dosage_id;
								$data_invoice["sucursal_id"] = get_branch_id_in_session();
								$data_invoice["usuario_id"] = get_user_id_in_session();
	
	
								$this->_insert_invoice($data_invoice);

								$invoice_inserted = $this->_get_data_invoice($data_invoice);
								$invoice_id = $invoice_inserted->id;

								$invoice = $this->_get_invoice($invoice_id);
	
	
								$data_invoice_sale["factura_id"] = $invoice->id;
								$data_invoice_sale["venta_id"] = $sale_id;
								$this->_insert_invoice_sale($data_invoice_sale);
	
								$data_qr["id"] = $invoice->id;
								$data_qr['nit_empresa'] = get_branch_office_nit_in_session();
								$data_qr['nro_factura'] = $invoice->nro_factura;
								$data_qr['nro_autorizacion'] = $invoice->nro_autorizacion;
								$data_qr['fecha'] = $invoice->fecha;
								$data_qr['importe_total_venta'] = $invoice->importe_total_venta;
								$data_qr['importe_base_iva'] = $invoice->importe_base_iva;
								$data_qr['codigo_control'] = $invoice->codigo_control;
								$data_qr['nit_cliente'] = $invoice->nit_cliente;
								$data_qr['importe_no_sujeto_iva'] = $invoice->importe_no_sujeto_iva;
								$data_qr['venta_tasa_cero'] = $invoice->venta_tasa_cero;
								$total_not_subject = $invoice->importe_no_sujeto_iva + $invoice->operacion_excenta + $invoice->venta_tasa_cero + $invoice->descuento;
								$data_qr['suma_no_sujeto_iva'] = $total_not_subject;
								$data_qr['descuento'] = $invoice->descuento;
	
								$this->generate_qr($data_qr);
	
	
								if ($this->db->trans_status() === FALSE) {
									$this->db->trans_rollback();
									$response['success'] = FALSE;
	
								} else {
									$this->db->trans_commit();
									// $this->inventory_model->update_product_in_inventory_branch_office_for_sale($this->get_detail_sale_id($sale_entry_inserted->id));
									$response['success'] = TRUE;
									$response['sale'] = $invoice->id;
									$response['url_impression'] = 'print_sale/print_invoice_sale';;
								}
							} else {
								$this->db->trans_rollback();
								$response['dosage'] = FALSE;
								$response['dosage_message'] = 'No cuenta dosificacion activa por favor registre una nueva dosificacion';
							}
						} else {
							$this->db->trans_rollback();
							$response['dosage'] = FALSE;
							$response['dosage_message'] = 'No tiene registrado ninguna dosificacion';
						}
					} else if ($sale_type == 'VENTA CREDITO') {
	
						$date_now = date('Y-m-d');
						$datetime1 = date_create($date_now);
						$datetime2 = date_create($date_expiration);
					
						$interval = date_diff($datetime1, $datetime2);
	
						$number_credit_sale = $this->sale_model->last_number_credit_sale_by_sucursal_id(get_branch_id_in_session());
						$data_credit_sale["nro_venta_credito"] = $number_credit_sale;
						$data_credit_sale["nro_cuotas_credito"] = 1;
						$data_credit_sale["nro_cuotas_pagadas"] = 0;
						$data_credit_sale["monto_credito"] = number_format($sale_total, 2, '.', '');
						$data_credit_sale["monto_saldo"] = number_format($sale_total, 2, '.', '');
						$data_credit_sale["interes"] = 0;
						$data_credit_sale["fecha_registro"] = $today;
						$data_credit_sale["fecha_modificacion"] = $today;
						$data_credit_sale["fecha_vencimiento"] = $date_expiration;
						$data_credit_sale["dias_plazo"] = $interval->days;
						$data_credit_sale["deuda"] = 1;
						$data_credit_sale["estado"] = 1;
						$data_credit_sale["sucursal_id"] = get_branch_id_in_session();
						$data_credit_sale["venta_id"] = $sale_entry_inserted->id;;
						$data_credit_sale["usuario_id"] = get_user_id_in_session();
	
						$this->db->insert('venta_credito', $data_credit_sale);
	
						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							$response['success'] = FALSE;
	
						} else {
							$this->db->trans_commit();
	
							// $this->inventory_model->update_product_in_inventory_branch_office_for_sale($this->get_detail_sale_id($sale_entry_inserted->id));
							$response['success'] = TRUE;
							$response['sale'] = $sale_entry_inserted->id;
							$response['url_impression'] = 'print_sale/print_note_sale';
						}
					} else {
	
						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							$response['success'] = FALSE;
						} else {
							$this->db->trans_commit();
							// $this->inventory_model->update_product_in_inventory_branch_office_for_sale($this->get_detail_sale_id($sale_entry_inserted->id));
							$response['success'] = TRUE;
							$response['sale'] = $sale_entry_inserted->id;
							$response['url_impression'] = 'print_sale/print_note_sale';
	
						}
					}
				// } else {
				// 	$response['cash'] = TRUE;
				// }
			} else {
				$response['login'] = TRUE;
			}
	
			return $response;
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}


	public function last_sale_number()
	{
		$this->db->select_max('nro_venta');
		$this->db->where('sucursal_id', get_branch_id_in_session());
		$result = $this->db->get('venta_sucursal');
		if ($result->num_rows() > 0) {
			$query = $result->row();
			return $query->nro_venta + 1;
		} else {
			return 1;
		}
	}

	/*Verifica la cantida de stock en un solo lote*/
	public function verify_stock_decrese_full_inventory($warehouse_id, $product_id, $quantity)
	{
		$this->db->select('*')
			->from('inventario')
			->where('almacen_id', $warehouse_id)
			->where('producto_id', $product_id)
			->where('estado', ACTIVO)
			->where('cantidad >=', $quantity);
		$this->db->limit(1);
		$this->db->order_by('id', 'ASC');
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return $response->row();
		} else {
			return null;
		}
	}

	/*Saca todos los lotes que tengan cantida de stock mayor a 0*/
	public function stock_decrese_inventory($warehouse_id, $product_id)
	{
		$this->db->select('*')
			->from('inventario')
			->where('almacen_id', $warehouse_id)
			->where('producto_id', $product_id)
			->where('estado', ACTIVO)
			->where('cantidad >0');
		$this->db->order_by('id', 'ASC');
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return $response->result_array();
		} else {
			return null;
		}
	}

	public function add_row_product_sale()
	{
		try {
			$response = array(
				'success' => FALSE,
				'messages' => array(),
				'login' => FALSE,
				'verify_data' => FALSE
			);
	
			// Reglas de validacion
			$validation_rules = array(
				array(
					'field' => 'product_name_sale',
					'label' => '<strong style="font-style: italic">MODELO</strong>',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'product_price_sale',
					'label' => '<strong style="font-style: italic">PRECIO UNITARIO</strong>',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'product_quantity_sale',
					'label' => '<strong style="font-style: italic">CANTIDAD</strong>',
					'rules' => 'trim|required'
				)
			);
	
			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
			if (verify_session()) {
				if ($this->form_validation->run() === true) {
					$branch_office_id = $this->input->post("branch_office_sale");
					if ($branch_office_id == get_branch_id_in_session()) {
						$user_id = get_user_id_in_session();
						$session_id = get_session_id();
						$product_id = $this->input->post("product_id_sale");
						$product = $this->product_model->get_product_by_id($this->input->post("product_id_sale"));
						$quantity = $this->input->post("product_quantity_sale");
						$warehouse_id = $this->input->post("warehouse_sale");
						$price_product = $this->input->post("product_price_sale");
						$real_stock_product = $this->inventory_model->verify_stock_available_inventory($branch_office_id, $warehouse_id, $product_id, $quantity);
						// $real_stock_product = $this->inventory_model->stock_by_warehouse_id($warehouse_id, $product_id);
						// $virtual_stock_product = $this->inventory_model->verify_stock_detail_virtual($branch_office_id, $warehouse_id, $product_id);
						$price_discount = $this->input->post("price_discount");
						$product_price_discount = $this->input->post("product_price_discount");
	
						$virtual_stock_product = 0;
	
						$stock_for_sale = $real_stock_product - $virtual_stock_product;
						if ($stock_for_sale >= $quantity) {
							$fila = '<tr data-price="' . number_format($product_price_discount * $quantity, 2, '.', '') . '" >';
							$fila .= '<td><input type="number" value="' . $product->id . '"  name="id_product[]" id="id_product" hidden/></td>';
							$fila .= '<td hidden><input type="number" value="' . $branch_office_id . '"  name="id_branch_office[]" id="id_branch_office" /></td>';
							$fila .= '<td hidden><input type="text" value="' . $warehouse_id . '"  name="id_warehouse[]" id="id_warehouse"/></td>';
							$fila .= '<td hidden><input type="text" value="' . get_user_id_in_session() . '"  name="id_user[]" id="id_user"/></td>';
							$fila .= '<td hidden><input type="number" value="' . get_session_id() . '"  name="id_session[]" id="id_session" /></td>';
							$fila .= '<td><input type="text" value="' . $product->codigo . '"  name="codigo_product[]" hidden/>' . $product->codigo . '</td>';
							$fila .= '<td><input type="text" value="' . $product->nombre_comercial .' - '. $product->nombre_generico . '"  name="name_product[]" hidden/>' . $product->nombre_comercial .' - '. $product->nombre_generico . '</td>';
							$fila .= '<td hidden><input type="text" value="' . $product->precio_compra . '" name="price_cost[]" hidden/>' . number_format(($product->precio_compra), 2, '.', '') . '</td>';
							$fila .= '<td hidden><input type="text" value="' . $price_product . '" name="price_product[]" hidden/>' . number_format(($price_product), 2, '.', '') . '</td>';
							$fila .= '<td hidden><input type="text" value="' . $price_discount . '" name="price_discount[]" hidden/>' . number_format(($price_discount), 2, '.', '') . '</td>';
							$fila .= '<td align="right"><input type="text" value="' . $product_price_discount . '" name="product_price_discounts[]" hidden/>' . number_format(($product_price_discount), 2, '.', '') . '</td>';
							$fila .= '<td align="right"><input type="text" value="' . $quantity . '" name="quantity_product[]" id="quantity_product" hidden/>' . $quantity . '</td>';
							$fila .= '<td align="right"><input type="text" value="' . number_format($quantity * $product_price_discount, 2, '.', '') . '" name="total_product[]" id="total_product" hidden/>' . number_format($quantity * $product_price_discount, 2, '.', '') . '</td>';
							$fila .= '<td class="text-center"><a class="elimina btn-danger btn" >Eliminar</a></td>';
							$fila .= '</tr>';
							$response['success'] = true;
							$response['data'] = $fila;
							$response['verify_data'] = true;
							// $this->add_row_detail_virtual($quantity, $branch_office_id, $warehouse_id, $product_id, $user_id, $session_id);
						} else {
							$response['data'] = "La cantidad que ingresada ya no esta disponible en su inventario por favor vuelva a buscar el producto";
						}
					} else {
						$response['data'] = "Solo puede vender productos de " . get_branch_office_name_in_session();
					}
				} else {
					foreach ($_POST as $key => $value) {
						$response['messages'][$key] = form_error($key);
					}
				}
			} else {
				$response['login'] = TRUE;
			}
			return $response;
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	public function delete_row_product_detail_sale()
	{
		$product_id = $this->input->post("product_id");
		$quantity = $this->input->post("quantity");
		$branch_office_id = $this->input->post("branch_office_id");
		$warehouse_id = $this->input->post("warehouse_id");
		$session_id = $this->input->post("session_id");
		$user_id = $this->input->post("user_id");
		/*Falta un metodo de verificar si es que existe de todos estos campos registros por que si no existe
        no deberia ejecutarse el delete*/
		$this->db->trans_begin();
		$this->delete_row_detail_virtual($quantity, $branch_office_id, $warehouse_id, $product_id, $user_id, $session_id);
		// Obtener resultado de transacción
		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();
			return true;
		} else {
			$this->db->trans_rollback();
			return false;
		}
	}

	public function cancel_sale()
	{
		$branch_office_id = get_branch_id_in_session();
		$session_id = get_session_id();
		$user_id = get_user_id_in_session();
		/*Falta un metodo de verificar si es que existe de todos estos campos registros por que si no existe
        no deberia ejecutarse el delete*/
		$this->db->trans_begin();
		$this->delete_detail_virtual($branch_office_id, $user_id, $session_id);
		// Obtener resultado de transacción
		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();
			return true;
		} else {
			$this->db->trans_rollback();
			return false;
		}
	}

	private function delete_row_detail_virtual($quantity, $branch_office_id, $warehouse_id, $product_id, $user_id, $session_id)
	{
		$this->db->where('cantidad', $quantity);
		$this->db->where('sucursal_id', $branch_office_id);
		$this->db->where('almacen_id', $warehouse_id);
		$this->db->where('producto_id', $product_id);
		$this->db->where('usuario_id', $user_id);
		$this->db->where('sesion_id', $session_id);
		$this->db->delete('detalle_virtual');
	}

	private function delete_detail_virtual($branch_office_id, $user_id, $session_id)
	{
		$this->db->where('sucursal_id', $branch_office_id);
		$this->db->where('usuario_id', $user_id);
		$this->db->where('sesion_id', $session_id);
		$this->db->delete('detalle_virtual');
	}

	private function add_row_detail_virtual($quantity, $branch_office_id, $warehouse_id, $product_id, $user_id, $session_id)
	{
		$data_detail_virtual = array(
			'cantidad' => $quantity,
			'sucursal_id' => $branch_office_id,
			'almacen_id' => $warehouse_id,
			'producto_id' => $product_id,
			'usuario_id' => $user_id,
			'sesion_id' => $session_id
		);
		$this->db->insert('detalle_virtual', $data_detail_virtual);
	}

	/*Funcion privada para insertar en la base de datos del detalle de la nueva venta*/
	private function _insert_detail_sale($detail_sale)
	{
		return $this->db->insert('detalle_venta', $detail_sale);
	}

	public function _insert_detail_sale_inventory($detail_sale_inventory)
	{
		return $this->db->insert('detalle_salida_inventario', $detail_sale_inventory);
	}

	private function _get_sale($sale)
	{
		return $this->db->get_where('venta', $sale)->row();
	}

	private function _get_invoice($invoice_id)
	{
		return $this->db->get_where('factura', array('id' => $invoice_id))->row();
	}

	public function _get_data_invoice($invoice)
	{
		return $this->db->get_where('factura', $invoice)->row();
	}

	public function _get_sale_inventory($sale)
	{
		return $this->db->get_where('salida_inventario', $sale)->row();
	}

	/*Funcion privada para insertar en la base de datos del nueva venta*/
	private function _insert_sale($sale)
	{
		return $this->db->insert('venta', $sale);
	}

	/*Funcion privada para insertar en la base de datos del nueva factura*/
	private function _insert_invoice($invoice)
	{
		return $this->db->insert('factura', $invoice);
	}

	/*Funcion privada para insertar en la base de datos la tabla de venta factura*/
	private function _insert_invoice_sale($invoice_sale)
	{
		return $this->db->insert('factura_venta', $invoice_sale);
	}

	private function _insert_sale_branch_office($sale)
	{
		return $this->db->insert('venta_sucursal', $sale);
	}

	private function _insert_sale_orden_trabajo($sale)
	{
		return $this->db->insert('venta_orden_trabajo', $sale);
	}

	/*Funcion privada para insertar en la base de datos del nueva venta*/
	public function _insert_sale_inventory($sale_inventario)
	{
		return $this->db->insert('salida_inventario', $sale_inventario);
	}

	/*Funcion privada para insertar en la base de datos del nueva venta*/
	public function _insert_sale_output_inventory($sale_output_inventory)
	{
		return $this->db->insert('salida_inventario_venta', $sale_output_inventory);
	}

	/*  Actualizar producto */
	public function _update_stock_inventory($inventory_id, $quantity)
	{
		$obj_inventory['cantidad'] = $quantity;
		$this->db->where('id', $inventory_id);
		$this->db->update('inventario', $obj_inventory);
	}

	public function get_print_note_sale($sale_id)
	{
		$sale = $this->get_sale_id($sale_id);
		$data_sale['sale'] = $sale;
		$data_sale['sale_detail'] = $this->get_detail_sale_id($sale_id);
		$data_sale['customer'] = $this->customer_model->get_customer_id($sale->cliente_id);
		$data_sale['user'] = $this->user_model->get_user_id($sale->usuario_id);
		$data_sale['branch_office'] = $this->office_model->get_branch_office_id($sale->sucursal_id);
		$data_sale['sale_branch_office'] = $this->sale_model->get_sale_branch_office_number($sale_id, $sale->sucursal_id);
		$data_sale['company'] = $this->company_model->get_company();
		$data_sale['dosage'] = $this->dosage_model->get_dosage_active();
		return $data_sale;
	}

	public function get_print_invoice_sale($invoice_id)
	{
		$invoice = $this->get_invoice_id($invoice_id);
		$data_invoice['invoice'] = $invoice;
		$invoice_sale = $this->get_invoice_sale_by_invoice_id($invoice_id);
		$data_invoice['sale_detail'] = [];
		$list_sale_detail = [];
		foreach ($invoice_sale as $sales) {
			$list_sale_detail[] = $this->get_detail_sale_id($sales->venta_id);
		};
		$dosage = $this->dosage_model->get_dosage_id($invoice->dosificacion_id);
		$assignment_dosage = $this->dosage_model->get_active_dosage_by_assignment_id($dosage->asignacion_dosificacion_id);
		$data_invoice['sale_detail'] = $list_sale_detail;
		$data_invoice['dosage'] = $dosage;
		if ($assignment_dosage != null) {
			$data_invoice['activity'] = $this->company_model->get_activity_id(1);
		} else {
			$data_invoice['activity'] = $this->company_model->get_activity_id($assignment_dosage->actividad_id);
		}
		$data_invoice['user'] = $this->user_model->get_user_id($invoice->usuario_id);
		$data_invoice['branch_office'] = $this->office_model->get_branch_office_id($invoice->sucursal_id);
		$data_invoice['company'] = $this->company_model->get_company();
		return $data_invoice;
	}

	public function get_invoice_number_by_dosage_id($dosage_id)
	{
		$invoice_number = 1;
		$this->db->select_max('nro_factura');
		$this->db->from('factura');
		$this->db->where('dosificacion_id', $dosage_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$invoice_number = $row->nro_factura;
			return $invoice_number + 1;
		} else {
			return $invoice_number;
		}

	}

	function generate_qr($data)
	{
		$PNG_TEMP_DIR = 'assets/invoice_qr/';

		//html PNG location prefix
		$PNG_WEB_DIR = 'assets/invoice_qr/';

		include APPPATH . '/libraries/qrcode/qrlib.php';
		//ofcourse we need rights to create temp dir
		if (!file_exists($PNG_TEMP_DIR))
			mkdir($PNG_TEMP_DIR);
		$errorCorrectionLevel = 'L';
		if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
			$errorCorrectionLevel = $_REQUEST['level'];

		$matrixPointSize = 3;
		if (isset($_REQUEST['size']))
			$matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

		/*Nombre de la imagen qr que se generara*/
		$filename = $PNG_TEMP_DIR . 'qr' . $data['id'] . '.png';

		$data = $data['nit_empresa'] . '|'
			. $data['nro_factura'] . '|'
			. $data['nro_autorizacion']
			. '|' . $data['fecha']
			. "|" . $data['importe_total_venta']
			. '|' . $data['importe_base_iva']
			. '|' . $data['codigo_control']
			. "|" . $data['nit_cliente']
			. '|' . $data['importe_no_sujeto_iva']
			. '|' . $data['venta_tasa_cero']
			. '|' . $data['suma_no_sujeto_iva']
			. '|' . $data['descuento'];

		QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
	}

	function generar_codigo_control($date, $authorization, $number, $customer_nit, $total, $key)
	{
		include APPPATH . '/libraries/impuestos/ControlCode.php';
		//Formateamos la fecha para que quede sin separadores
		$year = substr($date, 0, 4);
		$mounth = substr($date, 5, 2);
		$day = substr($date, 8);
		$date_ymd = $year . $mounth . $day;
		// Formateamos el total para que se aplique o no el redondeo
		$data_total = explode(".", $total); // DIVIDO EL MONTO A PAGAR PARA EXTRAER LA PARTE ENTERA Y LA PARTE DECIMAL
		$integer = $data_total[0];
		$decimal = @$data_total[1];
		if ($decimal >= 50) {
			$integer = $integer + 1;
		}
		/*$code_create = new CodigoControl(
            $authorization, $number, $customer_nit, $date_ymd, $integer, $key
        );

        $control_code = $code_create->generar();
        return $control_code;*/


		$controlCode = new ControlCode();
		$control_code = $controlCode->generate(
			$authorization, $number, $customer_nit, $date_ymd, $integer, $key
		);

		return $control_code;
	}

	/*Funcion para desabilitar la venta*/
	public function disable_sale()
	{
		$id = $this->input->post('id');
		$sale = $this->get_sale_id($id);
		$disable["estado"] = ANULADO;
		$disable["user_updated"] = get_user_id_in_session();
		/*verificacimos que solo se reponga inventario por ventas distintas de servicio*/
		$this->db->trans_begin();
		if ($sale->tipo_venta != 'SERVICIO TECNICO') {
			$inventory_output_id = $this->sale_inventory_output_by_sale_id($id);
			/*verificacion que si no existe la salida de inventario no haga nada*/
			if ($inventory_output_id > 0) {
				$detail_inventory_output = $this->detail_inventory_output_by_id($inventory_output_id);
				/*Verificamos que el detalle no este vacio*/
				if ($detail_inventory_output != null) {
					foreach ($detail_inventory_output as $detail) {
						$quantity_output = $detail->cantidad;
						$inventory_id = $detail->inventario_id;
						$quantity_inventory_current = $this->inventory_id($inventory_id);
						$data_detail["cantidad"] = $quantity_output + $quantity_inventory_current;
						/*Actualizamos la cantidad que se habia sacado*/
						$this->db->where('id', $inventory_id);
						$this->db->update('inventario', $data_detail);
						$this->inventory_model->update_branch_office_inventory($inventory_id);
					}
				}
				/*Actualizamos la cantidad que se habia sacado*/
				$this->db->where('id', $inventory_output_id);
				$this->db->update('salida_inventario', $disable);
			}
		}
		$this->db->where('id', $id);
		$this->db->update('venta', $disable);
		///////////////////////////////////////ingreso caja//////////////////////////////////
		$cash_income_sale=$this->cash_income_model->get_cash_income_sale_by_sale_id($id);
		$this->db->where('id', $cash_income_sale->ingreso_caja_id);
		$this->db->update('ingreso_caja', $disable);

		// Obtener resultado de transacción
		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();
			// $this->inventory_model->update_product_in_inventory_branch_office_for_sale($this->get_detail_sale_id($id));
			return true;
		} else {
			$this->db->trans_rollback();
			return false;
		}
	}

	public function disable_invoice()
	{
		$id = $this->input->post('id');
		$sale = $this->get_invoice_sale_by_invoice_id($id)[0];
		$sale_id = $this->get_sale_id($sale->venta_id)->id;
		$disable["nit_cliente"] = 0;
		$disable["nombre_cliente"] = "ANULADO";
		$disable["importe_total_venta"] = 0;
		$disable["importe_no_sujeto_iva"] = 0;
		$disable["operacion_excenta"] = 0;
		$disable["venta_tasa_cero"] = 0;
		$disable["subtotal"] = 0;
		$disable["descuento"] = 0;
		$disable["importe_base_iva"] = 0;
		$disable["iva"] = 0;
		$disable["estado"] = "A";
		/*verificacimos que solo se reponga inventario por ventas distintas de servicio*/
		$this->db->trans_begin();
		// if (!$this->verify_order_work_by_sale_id($sale_id)) {
			$inventory_output_id = $this->sale_inventory_output_by_sale_id($sale_id);
			//verificacion que si no existe la salida de inventario no haga nada
			if ($inventory_output_id > 0) {
				$detail_inventory_output = $this->detail_inventory_output_by_id($inventory_output_id);
				//Verificamos que el detalle no este vacio
				if ($detail_inventory_output != null) {
					foreach ($detail_inventory_output as $detail) {
						$quantity_output = $detail->cantidad;
						$inventory_id = $detail->inventario_id;
						$quantity_inventory_current = $this->inventory_id($inventory_id);
						$data_detail["cantidad"] = $quantity_output + $quantity_inventory_current;
						//Actualizamos la cantidad que se habia sacado
						$this->db->where('id', $inventory_id);
						$this->db->update('inventario', $data_detail);
						$this->inventory_model->update_branch_office_inventory($inventory_id);
					}
				}
				//Actualizamos la cantidad que se habia sacado
				$disable_inventory["estado"] = ANULADO;
				$this->db->where('id', $inventory_output_id);
				$this->db->update('salida_inventario', $disable_inventory);
			}
		// }
		$this->db->where('id', $id);
		$this->db->update('factura', $disable);

		$disable_sale["estado"] = ANULADO;
		$this->db->where('id', $sale_id);
		$this->db->update('venta', $disable_sale);

		///////////////////////////////////////ingreso caja//////////////////////////////////
		$cash_income_sale=$this->cash_income_model->get_cash_income_sale_by_sale_id($sale_id);
		$this->db->where('id', $cash_income_sale->ingreso_caja_id);
		$this->db->update('ingreso_caja', $disable_sale);

		// Obtener resultado de transacción
		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();
			// $this->inventory_model->update_product_in_inventory_branch_office_for_sale($this->get_detail_sale_id($sale_id));
			return true;
		} else {
			$this->db->trans_rollback();
			return false;
		}
	}

	/*Funcion para desabilitar la venta*/
	public function disable_sale_credit()
	{
		$id = $this->input->post('id');
		$sale = $this->get_sale_id($id);
		$disable["estado"] = ANULADO;
		/*verificacimos que solo se reponga inventario por ventas distintas de servicio*/
		$this->db->trans_begin();
		if ($sale->tipo_venta != 'SERVICIO TECNICO') {
			$inventory_output_id = $this->sale_inventory_output_by_sale_id($id);
			/*verificacion que si no existe la salida de inventario no haga nada*/
			if ($inventory_output_id > 0) {
				$detail_inventory_output = $this->detail_inventory_output_by_id($inventory_output_id);
				/*Verificamos que el detalle no este vacio*/
				if ($detail_inventory_output != null) {
					foreach ($detail_inventory_output as $detail) {
						$quantity_output = $detail->cantidad;
						$inventory_id = $detail->inventario_id;
						$quantity_inventory_current = $this->inventory_id($inventory_id);
						$data_detail["cantidad"] = $quantity_output + $quantity_inventory_current;
						/*Actualizamos la cantidad que se habia sacado*/
						$this->db->where('id', $inventory_id);
						$this->db->update('inventario', $data_detail);
					}
				}
				/*Actualizamos la cantidad que se habia sacado*/
				$this->db->where('id', $inventory_output_id);
				$this->db->update('salida_inventario', $disable);
			}
		}
		$this->db->where('id', $id);
		$this->db->update('venta', $disable);
		///////////////////////////////////////ingreso caja//////////////////////////////////
		$sale_credits=$this->get_sale_credit_transactions($id);
		foreach ($sale_credits as $row) {
			// $cash_income_sale=$this->cash_income_model->get_cash_income_sale_by_sale_id($id);
			$this->db->where('id', $row->ingreso_caja_id);
			$this->db->update('ingreso_caja', $disable);
		}

		// Obtener resultado de transacción
		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();
			$this->inventory_model->update_product_in_inventory_branch_office_for_sale($this->get_detail_sale_id($id));
			return true;
		} else {
			$this->db->trans_rollback();
			return false;
		}
	}
	public function get_sale_credit_transactions($sale_id)
	{
		$this->db->select('ictp.*')
			->from('venta_credito vc, pago_cuota_venta_credito pcvc, transaccion_pago_venta_credito tpvc, ingreso_caja_transaccion_pago ictp')
			->where('vc.id=pcvc.venta_credito_id')
			->where('pcvc.transaccion_pago_venta_credito_id=tpvc.id')
			->where('tpvc.id=ictp.transaccion_pago_venta_credito_id')
			->where('vc.venta_id', $sale_id);
		$sale_credit = $this->db->get()->result();
		return $sale_credit;
	}
	public function generate_sale_by_order_work_id($order_work_id)
	{
		try {
			$response = array(
				'success' => FALSE,
				'messages' => '',
				'login' => FALSE,
				'inventory' => FALSE,
				'cash' => FALSE,
			);
			$discount = $this->input->post('sale_discount');
			//$discount = $this->input->post('discount');
			$reception_id = $this->input->post('reception_id');
	
			if (verify_session()) {
				if(verify_cash_session()){
					$today = date('Y-m-d H:i:s');
					$invoce_day = date('Y-m-d');
	
					$order_work_update = $this->order_work_model->get_order_work_id($order_work_id);
					$subtotal = $order_work_update->monto_subtotal;
					$total = $subtotal - $discount;
					$this->db->where('id', $order_work_id);
					$data['estado_trabajo'] = ENTREGADO;
					$data['entrega_usuario_id'] = get_user_id_in_session();
					$data['fecha_entrega'] = date('Y-m-d H:i:s');
					$data['monto_subtotal'] = $subtotal;
					$data['descuento'] = $discount;
					$data['monto_total'] = $total;
					$this->db->update('orden_trabajo', $data);
	
					$code_reception = $this->input->post('code_reception');
					$date_payment = $this->input->post('date_payment');
					$reception_total = $this->input->post('reception_total');
					$reception_discount = $this->input->post('reception_discount');
					$reception_total_payment = $this->input->post('reception_total_payment');
					$reception_payment = $this->input->post('reception_payment');
					$reception_balance = $this->input->post('reception_balance');
					$reception_observation = $this->input->post('reception_payment_observation');
					$payment['observacion'] = $reception_observation;
					$payment['subtotal'] = $reception_total;
					$payment['descuento'] = $reception_discount;
					$payment['total'] = $reception_total_payment;
					$payment['pago'] = $reception_payment;
					$payment['pagos_anteriores'] = 0;
					$payment['saldo'] = $reception_balance;
					$payment['fecha_registro'] = date('Y-m-d H:i:s');
					$payment['fecha_modificacion'] = date('Y-m-d H:i:s');
					$payment['estado'] = ACTIVO;
					$payment['usuario_id'] = get_user_id_in_session();
					$payment['usuario_id_modificacion'] = get_user_id_in_session();
					$payment['recepcion_id'] = $reception_id;
					$payment['fecha_pago'] = date('Y-m-d');
					$payment['sucursal_id'] = get_branch_id_in_session();
	
					if ($reception_payment > 0) {
						$this->db->insert('pago_recepcion', $payment);
					}
	
					$this->order_work_model->register_order_work_history($order_work_id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A ' . get_work_order_states(ENTREGADO));
					$this->order_work_model->update_date_delivery_reception($reception_id);/*Actualiziamos la fecha de entrega*/
	
					$order_work = $this->order_work_model->get_order_work_id($order_work_id);
					$reception = $this->reception_model->get_reception_by_id($order_work->recepcion_id);
					$customer_id = $reception->cliente_id;
					$detail_product_order_work_list = $this->order_work_model->get_detail_product_by_order_work_id($order_work_id);
					$detail_service_order_work_list = $this->order_work_model->get_detail_service_by_order_work_id($order_work_id);
	
					$data_sale = [];
					$data_sale["tipo_venta"] = 'SERVICIO TECNICO';
					$data_sale["fecha_registro"] = $today;
					$data_sale["subtotal"] = $order_work->monto_subtotal;
					$data_sale["descuento"] = $order_work->descuento;
					$data_sale["total"] = $order_work->monto_total;
					$data_sale["sincronizado"] = 0;
					$data_sale["estado"] = ACTIVO;
					$data_sale["codigo_recepcion"] = $order_work->codigo_trabajo;
					$data_sale["facturado"] = 0;
					$data_sale["cliente_id"] = $customer_id;
					$data_sale["sucursal_id"] = get_branch_id_in_session();
					$data_sale["usuario_id"] = get_user_id_in_session();
					$data_sale["user_updated"] = get_user_id_in_session();
	
	
					$this->db->trans_begin();
	
					$this->_insert_sale($data_sale);
					$sale_entry_inserted = $this->_get_sale($data_sale);
					$branch_office_id = $order_work->sucursal_id;
					/*Registramos el detalle de venta de los repuestos que se ocupo en la orden de trabajo*/
	
					////////////////////////////////////INGRESO CAJA/////////////////////////////////////////
						$monto_efectivo = $this->input->post('monto_efectivo');
						$monto_bs = $this->input->post('monto_bs');
						$monto_sus = $this->input->post('monto_sus');
						$monto_cheque = $this->input->post('monto_cheque');
						$monto_tarjeta = $this->input->post('monto_tarjeta');
						$monto_cambio = $this->input->post('monto_cambio');
	
						if (strlen($monto_efectivo) == 0) {
							$monto_efectivo = 0;
						}
						if (strlen($monto_bs) == 0) {
							$monto_bs = 0;
						}
						if (strlen($monto_sus) == 0) {
							$monto_sus = 0;
						}
						if (strlen($monto_cheque) == 0) {
							$monto_cheque = 0;
						}
						if (strlen($monto_tarjeta) == 0) {
							$monto_tarjeta = 0;
						}
						if (strlen($monto_cambio) == 0) {
							$monto_cambio = 0;
						}
						$data_cash_income = array(
							'nro_transaccion'=>$this->cash_income_model->get_number_transaction(),
							'nro_ingreso'=> strval($this->cash_income_model->get_number_transaction()),
							'detalle' => 'INGRESO POR VENTAS DE RECEPCION',
							'monto_venta' => number_format($reception_payment, 2, '.', ''),
							'monto_bs' => number_format($monto_bs, 2, '.', ''),
							'monto_sus' => number_format($monto_sus, 2, '.', ''),
							'monto_tarjeta' => number_format($monto_tarjeta, 2, '.', ''),
							'monto_cheque' => number_format($monto_cheque, 2, '.', ''),
							'monto_efectivo' => number_format($monto_efectivo, 2, '.', ''),
							'monto_cambio' => number_format($monto_cambio, 2, '.', ''),
							'fecha_ingreso' => date('Y-m-d'),
							'fecha_registro' => date('Y-m-d H:i:s'),
							'fecha_modificacion' => date('Y-m-d H:i:s'),
							'estado' => ACTIVO,
							'tipo_ingreso_caja_id'=> $this->cash_income_type_model->get_first()->id,
							'caja_id'=> get_session_cash_id(),
							'apertura_caja_id'=> get_session_cash_aperture_id(),
							'sucursal_id' => get_branch_id_in_session(),
							'user_created' => get_user_id_in_session(),
							'user_updated' => get_user_id_in_session(),
						);
						$this->cash_income_model->_insert_cash_income($data_cash_income);
						$cash_income_inserted = $this->cash_income_model->_get_cash_income($data_cash_income);
						$data_cash_income_sale = array(
							'ingreso_caja_id'=>$cash_income_inserted->id,
							'venta_id'=> $sale_entry_inserted->id
						);
						$this->_insert_cash_income_sale($data_cash_income_sale);
					
					/////////////////////////////////////////////////////////////////////////////////////////////
	
					foreach ($detail_product_order_work_list as $detail_order_work) {
						$quantity = $detail_order_work->cantidad;
						$product_id = $detail_order_work->producto_id;
						$price_sale = $detail_order_work->precio_venta;
						$product = $this->product_model->get_product_by_id($product_id);
	
						$data_detail_sale["descripcion"] = $product->nombre_comercial . ' (' . $product->modelo . ')';
						// $data_detail_sale["cantidad"] = $quantity;
						// $data_detail_sale["precio_costo"] = $product->precio_compra;
						// $data_detail_sale["precio_venta"] = $price_sale;
						// $data_detail_sale["total"] = $price_sale * $quantity;
	
						$data_detail_sale["cantidad"] = $quantity;
						$data_detail_sale["precio_costo"] = $product->precio_compra;
						$data_detail_sale["precio_venta"] = $price_sale;
						$data_detail_sale["precio_descuento"] = 0;
						$data_detail_sale["precio_venta_descuento"] = $price_sale;
						$data_detail_sale["total"] = $price_sale * $quantity;
	
						$data_detail_sale["estado"] = ACTIVO;
						$data_detail_sale["venta_id"] = $sale_entry_inserted->id;
						$data_detail_sale["producto_id"] = $product_id;
						$this->_insert_detail_sale($data_detail_sale);
	
	
	
	
	
	
					}
	
					/*Registramos el detalle de venta de los servicios que se ocupo en la orden de trabajo*/
					$service_type_producto = $this->product_model->get_producto_type_service();
					foreach ($detail_service_order_work_list as $detail_order_work) {
						$quantity = 1;
						$service_id = $detail_order_work->servicio_id;
						$service = $this->service_model->get_service_id($service_id);
	
						$data_detail_sale["descripcion"] = $service->nombre . ' (' . $detail_order_work->observacion . ')';
						// $data_detail_sale["cantidad"] = $quantity;
						// $data_detail_sale["precio_costo"] = $detail_order_work->precio_servicio;
						// $data_detail_sale["precio_venta"] = $detail_order_work->precio_servicio;
						// $data_detail_sale["total"] = $detail_order_work->precio_servicio * $quantity;
	
						$data_detail_sale["cantidad"] = $quantity;
						$data_detail_sale["precio_costo"] = 0;
						$data_detail_sale["precio_venta"] = $detail_order_work->precio_servicio;
						$data_detail_sale["precio_descuento"] = 0;
						$data_detail_sale["precio_venta_descuento"] = $detail_order_work->precio_servicio;
						$data_detail_sale["total"] = $detail_order_work->precio_servicio * $quantity;
	
						$data_detail_sale["estado"] = ACTIVO;
						$data_detail_sale["venta_id"] = $sale_entry_inserted->id;
						$data_detail_sale["producto_id"] = $service_type_producto->id;
						$this->_insert_detail_sale($data_detail_sale);
					}
					$number_sale = $this->last_sale_number();
					$data_sale_branch_office["venta_id"] = $sale_entry_inserted->id;
					$data_sale_branch_office["sucursal_id"] = $branch_office_id;
					$data_sale_branch_office["nro_venta"] = $number_sale;
					$this->_insert_sale_branch_office($data_sale_branch_office);
	
					$data_sale_orden_trabajo["venta_id"] = $sale_entry_inserted->id;
					$data_sale_orden_trabajo["orden_trabajo_id"] = $order_work->id;
					$this->_insert_sale_orden_trabajo($data_sale_orden_trabajo);
	
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$response['success'] = FALSE;
						$response['messages'] = 'no se pudo generar la venta';
					} else {
						$this->db->trans_commit();
						$response['success'] = TRUE;
						$response['sale_id'] = $sale_entry_inserted->id;
						$response['sale'] = TRUE;
						$response['url_impression'] = 'print_sale/print_note_sale';
						$response['messages'] = 'Se genero correctamente la venta.';
					}
				} else {
					$response['cash'] = TRUE;
				}
			} else {
				$response['login'] = TRUE;
			}
			return $response;
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	/*Obtener lista de venta para cargar la lista de dataTable*/
	public function get_sale_note_list_disable($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('v.*,tipo_venta,c.nombre,vs.nro_venta,u.usuario')
			->from('venta v, cliente c, venta_sucursal vs,usuario u')
			->where('v.cliente_id=c.id')
			->where('v.usuario_id=u.id')
			->where('v.facturado=0')
			->where('v.sucursal_id', get_branch_id_in_session())
			->where('vs.venta_id=v.id')
			->where('v.estado', 0);

		if ($params['sale_number'] != '') {
			$this->db->where('vs.nro_venta', $params['sale_number']);
		}

		if (isset($params['sale_date_start']) && $params['sale_date_start'] != '') {
			$this->db->where('DATE(v.fecha_registro) >=', $params['sale_date_start']);
		}
		if (isset($params['sale_date_end']) && $params['sale_date_end'] != '') {
			$this->db->where('DATE(v.fecha_registro) <=', $params['sale_date_end']);
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
			$this->db->order_by('v.id', 'DESC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->group_start();
			$this->db->like('lower(c.nombre)', strtolower($params['search']));
			$this->db->or_like('lower(c.nombre_factura)', strtolower($params['search']));
			$this->db->or_like('lower(v.codigo_recepcion)', strtolower($params['search']));

			$this->db->group_end();
			$this->db->order_by('v.id', 'DESC');
		}

		// Obtencion de resultados finales
		$draw = $this->input->post('draw');
		$data = $this->db->get()->result_array();

		$json_data = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_total,
			'data' => $data,
		);
		return $json_data;
	}

	function last_number_credit_sale_by_sucursal_id($branch_office_id)
	{
		$this->db->select_max('nro_venta_credito');
		$this->db->where('sucursal_id', $branch_office_id);
		$result = $this->db->get('venta_credito');

		if ($result->num_rows() > 0) {
			$query = $result->row();
			return $query->nro_venta_credito + 1;
		} else {
			return 1;
		}
	}

	function sale_inventory_output_by_sale_id($sale_id)
	{
		$this->db->where('venta_id', $sale_id);
		$result = $this->db->get('salida_inventario_venta');

		if ($result->num_rows() > 0) {
			$query = $result->row();
			return $query->salida_inventario_id;
		} else {
			return 0;
		}
	}

	function detail_inventory_output_by_id($inventory_output_id)
	{
		$this->db->where('salida_inventario_id', $inventory_output_id);
		$result = $this->db->get('detalle_salida_inventario');

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return null;
		}
	}

	function inventory_id($inventory_id)
	{
		$this->db->where('id', $inventory_id);
		$result = $this->db->get('inventario');

		if ($result->num_rows() > 0) {
			$query = $result->row();
			return $query->cantidad;
		} else {
			return 0;
		}
	}

	public function get_sale_note_credit_list($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('v.*,tipo_venta,c.nombre,vs.nro_venta,u.usuario,u.nombre as nombre_usuario,vc.deuda,vc.monto_credito, vc.monto_saldo')
			->from('venta v, cliente c, venta_sucursal vs,usuario u, venta_credito vc')
			->where('v.cliente_id=c.id')
			->where('vc.venta_id=v.id')
			->where('v.usuario_id=u.id')
			->where('v.facturado=0')
			->where('v.sucursal_id', get_branch_id_in_session())
			->where('vs.venta_id=v.id');

		if ($params['sale_number'] != '') {
			$this->db->where('vs.nro_venta', $params['sale_number']);
		}

		if (isset($params['sale_date_start']) && $params['sale_date_start'] != '') {
			$this->db->where('DATE(v.fecha_registro) >=', $params['sale_date_start']);
		}
		if (isset($params['sale_date_end']) && $params['sale_date_end'] != '') {
			$this->db->where('DATE(v.fecha_registro) <=', $params['sale_date_end']);
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
			$this->db->order_by('v.id', 'DESC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->group_start();
			$this->db->like('lower(c.nombre)', strtolower($params['search']));
			$this->db->or_like('lower(c.nombre_factura)', strtolower($params['search']));
			$this->db->or_like('lower(v.codigo_recepcion)', strtolower($params['search']));
			$this->db->or_like('lower(u.nombre)', strtolower($params['search']));
			$this->db->or_like('lower(u.usuario)', strtolower($params['search']));

			$this->db->group_end();
			$this->db->order_by('v.id', 'DESC');
		}

		// Obtencion de resultados finales
		$draw = $this->input->post('draw');
		$data = $this->db->get()->result_array();

		$json_data = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_total,
			'data' => $data,
		);
		return $json_data;
	}

	/* Nueva numeracion de salida de inventario */
	public function last_number_inventory_output()
	{
		$this->db->select_max('nro_salida_inventario');
		$this->db->where('sucursal_id', get_branch_id_in_session());
		$result = $this->db->get('salida_inventario');
		if ($result->num_rows() > 0) {
			$query = $result->row();
			return $query->nro_salida_inventario + 1;
		} else {
			return 1;
		}
	}

	/* Nueva numeracion de salida de inventario */
	public function getSumDiscount($sale_id)
	{

		$this->db->select('SUM(cantidad * precio_descuento) as descuento')
			->from('detalle_venta')
			->where('venta_id', $sale_id);
		$result = $this->db->get()->row();
		return $result;


		// $this->db->select_sum('cantida * precio_descuento');
		// $this->db->where('venta_id', $sale_id);
		// $result = $this->db->get('detalle_venta');
		// return $result;
	}

	public function update_cost_by_sale()
	{
		$detail = $this->get_detail_sale_all();
		if ($detail != null) {
			foreach ($detail as $row_detail) {
				$sale_id = $row_detail->venta_id;
				$product_id = $row_detail->producto_id;


			}
		}
	}

	public function get_detail_sale_all()
	{
		$this->db->select('*');
		$this->db->where('estado', ACTIVO);
		$result = $this->db->get('detalle_venta');
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return null;
		}
	}

	public function get_detail_output($sale_id, $product_id)
	{
		$this->db->select('*');
		$this->db->where('estado', ACTIVO);
		$result = $this->db->get('detalle_venta');
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return null;
		}
	}

	public function generate_invoice()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'login' => FALSE,
			'dosage_message' => '',
			'dosage' => FALSE

		);
		$sale_id = $this->input->post('id_sale');

		$customer_name = $this->input->post('nombre_factura');
		$customer_nit = $this->input->post('nit');
		$dosage_id = $this->input->post('dosage_id');
		if (verify_session()) {

			$today = date('Y-m-d H:i:s');
			$invoice_day = date('Y-m-d');

			$this->db->trans_begin();

			$sale = $this->get_sale_id($sale_id);

			if ($dosage_id != null) {

				$dosage = $this->dosage_model->get_dosage_id($dosage_id);

				if ($dosage != null) {
					$dosage_id = $dosage->id;
					$dosage_nro_authorization = $dosage->autorizacion;
					$dosage_key = $dosage->llave;
					$ice_total = 0;
					$excenta_operation = 0;
					$zero_rate_sale = 0;
					$subtotal = $sale->subtotal - $ice_total - $excenta_operation - $zero_rate_sale;
					$total_base_debit_iva = $subtotal - $sale->descuento;

					$invoice_number = $this->get_invoice_number_by_dosage_id($dosage_id);
					$control_code = $this->generar_codigo_control($invoice_day, trim($dosage_nro_authorization), $invoice_number, trim($customer_nit), $total_base_debit_iva, trim($dosage_key));

					$data_invoice = [];
					$data_invoice["nro_factura"] = $invoice_number;
					$data_invoice["fecha"] = $invoice_day;
					$data_invoice["nro_autorizacion"] = $dosage_nro_authorization;
					$data_invoice["nit_cliente"] = strval($customer_nit);
					$data_invoice["nombre_cliente"] = $customer_name;
					$data_invoice["importe_total_venta"] = $sale->subtotal;
					$data_invoice["importe_no_sujeto_iva"] = $ice_total;
					$data_invoice["operacion_excenta"] = $excenta_operation;
					$data_invoice["venta_tasa_cero"] = $zero_rate_sale;
					$data_invoice["subtotal"] = $subtotal;
					$data_invoice["descuento"] = $sale->descuento;
					$data_invoice["importe_base_iva"] = $total_base_debit_iva;
					$data_invoice["iva"] = $total_base_debit_iva * 0.13;
					$data_invoice["codigo_control"] = $control_code;
					$data_invoice["sincronizado"] = 0;
					$data_invoice["estado"] = 'V';
					$data_invoice["dosificacion_id"] = $dosage_id;
					$data_invoice["sucursal_id"] = get_branch_id_in_session();
					$data_invoice["usuario_id"] = get_user_id_in_session();


					$this->_insert_invoice($data_invoice);
					$invoice_inserted = $this->_get_data_invoice($data_invoice);
					$invoice_id = $invoice_inserted->id;

					$invoice = $this->_get_invoice($invoice_id);


					$data_invoice_sale["factura_id"] = $invoice->id;
					$data_invoice_sale["venta_id"] = $sale_id;
					$this->_insert_invoice_sale($data_invoice_sale);
					$this->_update_sale_invoice($sale_id);

					$data_qr["id"] = $invoice->id;
					$data_qr['nit_empresa'] = get_branch_office_nit_in_session();
					$data_qr['nro_factura'] = $invoice->nro_factura;
					$data_qr['nro_autorizacion'] = $invoice->nro_autorizacion;
					$data_qr['fecha'] = $invoice->fecha;
					$data_qr['importe_total_venta'] = $invoice->importe_total_venta;
					$data_qr['importe_base_iva'] = $invoice->importe_base_iva;
					$data_qr['codigo_control'] = $invoice->codigo_control;
					$data_qr['nit_cliente'] = $invoice->nit_cliente;
					$data_qr['importe_no_sujeto_iva'] = $invoice->importe_no_sujeto_iva;
					$data_qr['venta_tasa_cero'] = $invoice->venta_tasa_cero;
					$total_not_subject = $invoice->importe_no_sujeto_iva + $invoice->operacion_excenta + $invoice->venta_tasa_cero + $invoice->descuento;
					$data_qr['suma_no_sujeto_iva'] = $total_not_subject;
					$data_qr['descuento'] = $invoice->descuento;

					$this->generate_qr($data_qr);


					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$response['success'] = FALSE;

					} else {
						$this->db->trans_commit();
						//	$this->inventory_model->update_product_in_inventory_branch_office($this->get_detail_sale_id($sale->id));
						$response['success'] = TRUE;
						$response['sale'] = $invoice->id;
						$response['url_impression'] = 'print_sale/print_invoice_sale';
					}
				} else {
					$this->db->trans_rollback();
					$response['dosage'] = FALSE;
					$response['dosage_message'] = 'No cuenta dosificacion activa por favor registre una nueva dosificacion';
				}
			} else {
				$this->db->trans_rollback();
				$response['dosage'] = FALSE;
				$response['dosage_message'] = 'No tiene registrado ninguna dosificacion';
			}
		} else {
			$response['login'] = TRUE;
		}

		return $response;
	}

	public function _update_sale_invoice($sale_id)
	{
		$data['tipo_venta'] = 'FACTURA';
		$data['facturado'] = 1;
		$this->db->where('id', $sale_id);
		$this->db->update('venta', $data);
	}

	private function _insert_cash_income_sale($cash_income_sale)
	{
		return $this->db->insert('ingreso_caja_venta', $cash_income_sale);
	}
}
