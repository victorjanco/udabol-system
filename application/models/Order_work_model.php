<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 22/08/2017
 * Time: 06:54 PM
 */
class Order_work_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('service_model');
		$this->load->model('category_model');
		$this->load->model('model_model');
		$this->load->model('product_model');
		$this->load->model('user_model');
		$this->load->model('customer_model');
		$this->load->model('solution_model');
		$this->load->model('failure_model');
		$this->load->model('type_service_model');
		$this->load->model('sale_model');
		$this->load->model('reception_model');
		$this->load->model('transit_model');
		$this->load->model('inventory_model');
	}

//obtener trabajos en peritar
	public function get_assess()
	{
		$this->db->select('count(*) as orden_peritar');
		$this->db->from('orden_trabajo');
		$this->db->where('estado_trabajo', RECEPCIONADO);
		$this->db->where('sucursal_id', get_branch_id_in_session());
		return $this->db->get()->row();
	}

	//obtener trabajo concluidos
	public function get_concluded()
	{
		$this->db->select('count(*) as orden_concluida');
		$this->db->from('orden_trabajo ot');
		$this->db->where('estado_trabajo', CONCLUIDO);
		$this->db->where('sucursal_id', get_branch_id_in_session());
		return $this->db->get()->row();

	}

	//obtener trabajos en procesos
	public function get_process()
	{
		$this->db->select('count(*) as orden_proceso');
		$this->db->from('orden_trabajo ot');
		$this->db->where('estado_trabajo', EN_PROCESO);
		$this->db->where('sucursal_id', get_branch_id_in_session());
		return $this->db->get()->row();
	}

	public function get_order_work_all()
	{
		$this->db->select('*');
		$this->db->from('orden_trabajo ot');
		return $this->db->get()->result();
	}

	public function register()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array()
		);
		try {
			if (verify_session()) {

				/*Reglas de validacion*/
				$validation_rules = array(
					array(
						'field' => 'failure_select[]',
						'label' => 'FALLAS',
						'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->failure_model->get_failure_enable(), 'id'))
					),
					array(
						'field' => 'solution_select[]',
						'label' => 'SOLUCION ',
						'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->solution_model->get_solution_enable(), 'id'))
					),
					array(
						'field' => 'user',
						'label' => 'USUARIO',
						'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->user_model->get_list_users(), 'id'))
					)
				);
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
	
				if ($this->form_validation->run() === true) {
	
					$this->db->trans_start();
					$sesion = $this->session->userdata('user');
					$total_amount_service = 0;
					/*Lista de array*/
					$failures_array = $this->input->post('failure_select');
					$solutions_array = $this->input->post('solution_select');
	
					$service_array_id = $this->input->post('serviceid');
					$service_array_price = $this->input->post('serviceprice');
					$service_array_observation = $this->input->post('serviceobservation');
	
					$product_array_id = $this->input->post('product_id');
					$product_array_quantity = $this->input->post('quantity_product');
					$product_array_price = $this->input->post('price_product');
					$product_array_price_sale = $this->input->post('price_sale');
					$product_array_warehouse_id = $this->input->post('product_warehouse_id');
	
					$product_array_id_recondition = $this->input->post('product_id_recondition');
					$product_array_quantity_recondition = $this->input->post('quantity_product_recondition');
					$product_array_price_recondition = $this->input->post('price_product_recondition');
	
					$order_work_id = $this->input->post('id_order_work');
					/*Insertamos las fallas seleccionadas*/
					$length_failures_select = count($this->input->post("failure_select"));
					$this->db->delete('falla_orden_trabajo', array("orden_trabajo_id" => $order_work_id));/*  antes de registrar, eliminamos to.do. el detalle*/
					for ($index = 0; $index < $length_failures_select; $index++) {
						$failure_id = $failures_array[$index];  /*Insertamos las fallas seleccionadas*/
						$this->db->insert('falla_orden_trabajo', array('estado' => ACTIVO, 'orden_trabajo_id' => $order_work_id, 'falla_id' => $failure_id));
					}
	
	
					/*Insertamos las soluciones seleccionadas*/
					$length_solution_select = count($this->input->post("solution_select"));
					$this->db->delete('solucion_orden_trabajo', array("orden_trabajo_id" => $order_work_id));/*  antes de registrar, eliminamos to.do. el detalle*/
					for ($index = 0; $index < $length_solution_select; $index++) {
						$solution_id = $solutions_array[$index];    /* Insertamos las fallas seleccionadas  */
						$this->db->insert('solucion_orden_trabajo', array('estado' => ACTIVO, 'orden_trabajo_id' => $order_work_id, 'solucion_id' => $solution_id));
					}
	
					/*Insertamos los servicios agregados en el peritaje*/
					$service_num_rows = count((array)$this->input->post('serviceid'));
					$this->db->delete('detalle_orden_trabajo_servicio', array("orden_trabajo_id" => $order_work_id));/*  antes de registrar, eliminamos to.do. el detalle*/
					for ($index = 0; $index < $service_num_rows; $index++) {
						$detalle_orden_trabajo_servicio = [];
						$detalle_orden_trabajo_servicio['precio_servicio'] = $service_array_price[$index];
						$detalle_orden_trabajo_servicio['observacion'] = $service_array_observation[$index];
						$detalle_orden_trabajo_servicio['estado'] = ACTIVO;
						$detalle_orden_trabajo_servicio['servicio_id'] = $service_array_id[$index];
						$detalle_orden_trabajo_servicio['orden_trabajo_id'] = $order_work_id;
						$this->_insert_detail_orderwork_service($detalle_orden_trabajo_servicio);
						$total_amount_service = $total_amount_service + $service_array_price[$index];
					}
	
					/*Insertamos los productos y/o accesorios agregados en el peritaje*/
					$product_num_rows = count((array)$this->input->post('product_id'));
					$total_amount_product = 0;
					$this->db->delete('detalle_producto_trabajo', array("orden_trabajo_id" => $order_work_id));/*  antes de registrar, eliminamos to.do. el detalle*/
					for ($index_product = 0; $index_product < $product_num_rows; $index_product++) {
						$detalle_producto_trabajo['estado'] = ACTIVO;
						$detalle_producto_trabajo['cantidad'] = $product_array_quantity[$index_product];
						$detalle_producto_trabajo['precio_costo'] = $product_array_price[$index_product];
						$detalle_producto_trabajo['precio_venta'] = $product_array_price_sale[$index_product];
						$detalle_producto_trabajo['orden_trabajo_id'] = $order_work_id;
						$detalle_producto_trabajo['producto_id'] = $product_array_id[$index_product];
						$this->_insert_detail_product_work($detalle_producto_trabajo);
						$total_amount_product = $total_amount_product + ($product_array_price_sale[$index_product] * $product_array_quantity[$index_product]);
					}
	
					/*Insertamos los productos para reacondicionar agregados en el peritaje*/
					if (is_array($this->input->post('product_id_recondition'))) {
						$product_num_rows_recondition = count($this->input->post('product_id_recondition'));
					} else {
						$product_num_rows_recondition = 0;
					}
	
					if ($product_num_rows_recondition > 0) {
						$this->db->delete('detalle_producto_reciclado', array("orden_trabajo_id" => $order_work_id));/*  antes de registrar, eliminamos to.do. el detalle*/
						for ($index = 0; $index < $product_num_rows_recondition; $index++) {
							$detalle_producto_trabajo_recondition['estado'] = ACTIVO;
							$detalle_producto_trabajo_recondition['cantidad'] = $product_array_quantity_recondition[$index];
							$detalle_producto_trabajo_recondition['precio_venta'] = 0;
							$detalle_producto_trabajo_recondition['orden_trabajo_id'] = $order_work_id;
							$detalle_producto_trabajo_recondition['producto_id'] = $product_array_id_recondition[$index];
							$this->_insert_detail_product_work_recycling($detalle_producto_trabajo_recondition);
						}
					}
					$work_state = REPARADO;
					if ($this->input->post('warranty_select') == 1) { //CON GARANTIA
						$work_state = ESPERA_STOCK; 
					}
					$orden['observacion'] = trim($this->input->post('observation_select'));
					$orden['monto_subtotal'] = $total_amount_product + $total_amount_service;
					$orden['descuento'] = 0;
					$orden['monto_total'] = $total_amount_product + $total_amount_service;
					$orden['monto_pagado'] = 0;
					$orden['monto_deuda'] = $total_amount_product + $total_amount_service;
					$orden['monto_saldo'] = $total_amount_product + $total_amount_service;
					$orden['fecha_modificacion'] = date('Y-m-d H:i:s');
					$orden['fecha_perito'] = date('Y-m-d H:i:s');
					$orden['fecha_asignado'] = date('Y-m-d H:i:s');
					$orden['fecha_proforma'] = date('Y-m-d H:i:s');
					$orden['estado_trabajo'] = $work_state;
					$orden['estado_diagnostico'] = 1;
					$orden['progreso'] = 50;
					$orden['perito_usuario_id'] = get_user_id_in_session();/*usuario selecccionado de interfaz*/
					$orden['proforma_usuario_id'] = get_user_id_in_session();/*usuario selecccionado de interfaz*/
					$orden['asignado_usuario_id'] = $this->input->post('user');/*usuario selecccionado de interfaz*/
	
					$this->_update_order_work($orden, $order_work_id);
					$this->register_order_work_history($order_work_id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A REPARADO');
	
					$obj_reception['fecha_modificacion'] = date('Y-m-d H:i:s');
					$obj_reception['monto_total'] = $orden['monto_subtotal'];
					$obj_reception['garantia'] = $this->input->post('warranty_select');
					$this->db->update('recepcion', $obj_reception, array("id" => $this->get_order_work_id($order_work_id)->recepcion_id));
	
					/////////////////////////////////////////////SALIDA DE INVENTARIO//////////////////////////////////////////////////////////
					$today = date('Y-m-d H:i:s');
					$data_order_inventory = [];
					$data_order_inventory["fecha_registro"] = $today;
					$data_order_inventory["fecha_modificacion"] = $today;
					$data_order_inventory["sincronizado"] = 0;
					$data_order_inventory["observacion"] = 'La salida de inventario desde O.T. cambio de repuesto';
					$data_order_inventory["estado"] = ACTIVO;
					$data_order_inventory["tipo_salida_inventario_id"] = 2;//2=salida por orden de trabajo
					$data_order_inventory["sucursal_id"] = get_branch_id_in_session();
					$data_order_inventory["nro_salida_inventario"] = $this->last_number_inventory_output();
	
					$this->_insert_order_work_inventory($data_order_inventory);
					$output_inventory_inserted = $this->_get_order_work_inventory($data_order_inventory);
	
					
					$data_order_work_output_inventory["orden_trabajo_id"] = $order_work_id;
					$data_order_work_output_inventory["salida_inventario_id"] = $output_inventory_inserted->id;
	
					/*Registramos en la tabla salida salida inventario orden trabajo*/
					$this->_insert_order_work_output_inventory($data_order_work_output_inventory);
	
					for ($index = 0; $index < $product_num_rows; $index++) {
						$quantity = $product_array_quantity[$index];
						$product_id = $product_array_id[$index];
						$warehouse_id = $product_array_warehouse_id[$index];
						$branch_office_id = get_branch_id_in_session();
						// $session_id = $session_id_array[$index];
						$user_id = get_user_id_in_session();
						$output_inventory_id = $output_inventory_inserted->id;
						$verify_quantity = $quantity;
	
						$direct_discount_inventory = $this->verify_stock_decrese_full_inventory($warehouse_id, $product_id, $quantity);
						if ($direct_discount_inventory != null) {
							$inventory_id = $direct_discount_inventory->id;
							$stock_inventory = $direct_discount_inventory->cantidad;
							$data_detail_sale_inventory["cantidad"] = $product_array_quantity[$index];
							$data_detail_sale_inventory["cantidad_antigua"] = $stock_inventory;
							$data_detail_sale_inventory["precio_costo"] = $direct_discount_inventory->precio_costo;
							$data_detail_sale_inventory["precio_venta"] = $product_array_price[$index];
							$data_detail_sale_inventory["observacion"] = "Salida de inventario por orden de trabajo";
							$data_detail_sale_inventory["salida_inventario_id"] = $output_inventory_id;
							$data_detail_sale_inventory["inventario_id"] = $inventory_id;
							$this->_insert_detail_order_work_inventory($data_detail_sale_inventory);//detalle_salida_inventario
							$quantity_update = $stock_inventory - $quantity;
							if($quantity_update < 0){
								throw new Exception("Inventario menor a la cantidad que quiere retirar");
							}
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
										$data_detail_sale_inventory["precio_costo"] = $row['precio_costo'];
										$data_detail_sale_inventory["precio_venta"] = $product_array_price[$index];
										$data_detail_sale_inventory["observacion"] = "Salida de inventario por orden de trabajo";
										$data_detail_sale_inventory["salida_inventario_id"] = $output_inventory_id;
										$data_detail_sale_inventory["inventario_id"] = $inventory_id;
										$this->_insert_detail_order_work_inventory($data_detail_sale_inventory);//detalle_salida_inventario
										$quantity_update = 0;
										$this->_update_stock_inventory($inventory_id, $quantity_update);
										$quantity = $quantity - $stock_inventory;
										$this->inventory_model->update_branch_office_inventory($inventory_id);
									} else {
										$data_detail_sale_inventory["cantidad"] = $quantity;
										$data_detail_sale_inventory["cantidad_antigua"] = $stock_inventory;
										$data_detail_sale_inventory["precio_costo"] = $row['precio_costo'];
										$data_detail_sale_inventory["precio_venta"] = $product_array_price[$index];
										$data_detail_sale_inventory["observacion"] = "Salida de inventario por orden de trabajo";
										$data_detail_sale_inventory["salida_inventario_id"] = $output_inventory_id;
										$data_detail_sale_inventory["inventario_id"] = $inventory_id;
										$this->_insert_detail_order_work_inventory($data_detail_sale_inventory);//detalle_salida_inventario
										$quantity_update = $stock_inventory - $quantity;

										if($quantity_update < 0){
											throw new Exception("Inventario menor a la cantidad que quiere retirar");
										}
										
										$this->_update_stock_inventory($inventory_id, $quantity_update);
										$quantity = 0;
										$this->inventory_model->update_branch_office_inventory($inventory_id);
									}
								}
	
							}
						}
					}
					///////////////////////////////////////////////////////////END SALIDA DE INVENTARIO///////////////////////////////////////////////
					$this->db->trans_complete();
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$response['success'] = false;
					} else {
						$this->db->trans_commit();
						///////////////////////////////////////////////SALIDA INVENTARIO/////////////////////////////////////
						// $detail_order_work_list = $this->get_detail_product_by_order_work_id($order_work_id);
						// $this->inventory_model->update_product_in_inventory_branch_office($detail_order_work_list);
						////////////////////////////////////////////////////////////////////////////////////
						$response['success'] = TRUE;
						$response['id'] = $this->get_order_work_id($order_work_id)->recepcion_id;
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

	public function register_spare()
	{
		try {
			$response = array(
				'success' => FALSE,
				'messages' => array()
			);
	
			if (verify_session()) {
				$this->db->trans_start();
				$total_amount_service = 0;
				/*Lista de array*/
	
				$service_array_id = $this->input->post('serviceid');
				$service_array_price = $this->input->post('serviceprice');
				$service_array_observation = $this->input->post('serviceobservation');
	
				$product_array_id = $this->input->post('product_id');
				$product_array_quantity = $this->input->post('quantity_product');
				$product_array_price = $this->input->post('price_product');
				$product_array_price_sale = $this->input->post('price_sale');
	
				$order_work_id = $this->input->post('id_order_work');
	
				/*Insertamos los servicios agregados en el peritaje*/
				$service_num_rows = count((array)$this->input->post('serviceid'));
				$this->db->delete('detalle_orden_trabajo_servicio', array("orden_trabajo_id" => $order_work_id));/*  antes de registrar, eliminamos to.do. el detalle*/
				for ($index = 0; $index < $service_num_rows; $index++) {
					$detalle_orden_trabajo_servicio = [];
					$detalle_orden_trabajo_servicio['precio_servicio'] = $service_array_price[$index];
					$detalle_orden_trabajo_servicio['observacion'] = $service_array_observation[$index];
					$detalle_orden_trabajo_servicio['estado'] = ACTIVO;
					$detalle_orden_trabajo_servicio['servicio_id'] = $service_array_id[$index];
					$detalle_orden_trabajo_servicio['orden_trabajo_id'] = $order_work_id;
					$this->_insert_detail_orderwork_service($detalle_orden_trabajo_servicio);
					$total_amount_service = $total_amount_service + $service_array_price[$index];
				}
	
				/*Insertamos los productos y/o accesorios agregados en el peritaje*/
				$product_num_rows = count((array)$this->input->post('product_id'));
				$total_amount_product = 0;
				$this->db->delete('detalle_producto_trabajo', array("orden_trabajo_id" => $order_work_id));/*  antes de registrar, eliminamos to.do. el detalle*/
				for ($index_product = 0; $index_product < $product_num_rows; $index_product++) {
					$detalle_producto_trabajo['estado'] = ACTIVO;
					$detalle_producto_trabajo['cantidad'] = $product_array_quantity[$index_product];
					$detalle_producto_trabajo['precio_costo'] = $product_array_price[$index_product];
					$detalle_producto_trabajo['precio_venta'] = $product_array_price_sale[$index_product];
					$detalle_producto_trabajo['orden_trabajo_id'] = $order_work_id;
					$detalle_producto_trabajo['producto_id'] = $product_array_id[$index_product];
					$this->_insert_detail_product_work($detalle_producto_trabajo);
					$total_amount_product = $total_amount_product + ($product_array_price_sale[$index_product] * $product_array_quantity[$index_product]);
				}
	
				$orden['monto_subtotal'] = $total_amount_product + $total_amount_service;
				$orden['descuento'] = 0;
				$orden['monto_total'] = $total_amount_product + $total_amount_service;
				$orden['monto_pagado'] = 0;
				$orden['monto_deuda'] = $total_amount_product + $total_amount_service;
				$orden['monto_saldo'] = $total_amount_product + $total_amount_service;
				$orden['fecha_modificacion'] = date('Y-m-d H:i:s');
				$orden['fecha_proforma'] = date('Y-m-d H:i:s');
				/*$orden['estado_trabajo'] = $work_state;*/
				$orden['progreso'] = 50;
				$orden['estado_proforma'] = 1;
				$orden['proforma_usuario_id'] = get_user_id_in_session();/*usuario selecccionado de interfaz*/
	
				$this->_update_order_work($orden, $order_work_id);
				$this->register_order_work_history($order_work_id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A REPARADO CON PROFORMA');
	
				$obj_reception['fecha_modificacion'] = date('Y-m-d H:i:s');
				$obj_reception['monto_total'] = $orden['monto_subtotal'];
				/*$obj_reception['garantia'] = $this->input->post('warranty_select');*/
				$this->db->update('recepcion', $obj_reception, array("id" => $this->get_order_work_id($order_work_id)->recepcion_id));
	
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$response['success'] = false;
				} else {
					$this->db->trans_commit();
					$response['success'] = TRUE;
				}
	
			} else {
				$response['login'] = TRUE;
			}
	
			return $response;
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/* registro mediante recepcion*/
	public function register_order($data)
	{
		$response = array(
			'success' => FALSE,
			'messages' => array()
		);
		$this->db->trans_start();
		$this->db->insert('orden_trabajo', $data);
		$order_work_inserted = $this->_get_order_work($data);
		$order_work_id = $order_work_inserted->id;

		//Insertamos los datos en el historial
		$sesion = $this->session->userdata('user');
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response['success'] = false;
		} else {
			$this->db->trans_commit();
			$response['success'] = TRUE;
		}
		return $response;
	}

	public function _get_order_work($order_work)
	{
		return $this->db->get_where('orden_trabajo', $order_work)->row();
	}

	public function get_order_work_list($params = array())
	{

		/* Se cachea la informacion que se arma en query builder*/
		$this->db->start_cache();
		$this->db->select('*');
		$this->db->from('vista_orden_trabajo');

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
			$this->db->order_by('id', 'ASC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->like('lower(nombre_cliente)', strtolower($params['search']));
			$this->db->or_like('lower(codigo_trabajo)', strtolower($params['search']));
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

	public function get_order_list_peritaje($params = array())
	{

		/*parametros*/
		$days = $params["days"];
		$date_start_reception = $params["date_start_reception"];
		$date_end_reception = $params["date_end_reception"];
		$reception_code = $params["reception_code"];

		/* Se cachea la informacion que se arma en query builder*/
		$this->db->start_cache();
		$this->db->select('*');
		$this->db->from('vista_orden_trabajo');
		$this->db->where('sucursal_id', get_branch_id_in_session());
		$this->db->where('estado_trabajo', RECEPCIONADO);

		if ($reception_code != '') {
			$this->db->like('lower(codigo_trabajo)', strtolower($reception_code));
		} else {
			if ($days == 2) {
				$this->db->where('fecha_modificacion <=', $this->substrack_date());
				$this->db->where('estado_trabajo =', RECEPCIONADO);
			} else {
				if (isset($date_start_reception) && $date_start_reception != '') {
					$this->db->where('DATE(fecha_registro) >=', $date_start_reception);
				}

				if (isset($date_end_reception) && $date_end_reception) {
					$this->db->where('DATE(fecha_registro) <=', $date_end_reception);
				}
			}
		}


		$this->db->stop_cache();
		$count_items = count($this->db->get()->result());
		if ($days == 1 && $reception_code == '') {
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
				$this->db->order_by('id', 'ASC');
			}

			if (array_key_exists('search', $params)) {
				$this->db->group_start();
				$this->db->like('lower(nombre_cliente)', strtolower($params['search']));
				$this->db->or_like('lower(codigo_trabajo)', strtolower($params['search']));
				$this->db->or_like('lower(imei)', strtolower($params['search']));
				$this->db->or_like('lower(nombre_marca)', strtolower($params['search']));
				$this->db->or_like('lower(nombre_modelo)', strtolower($params['search']));
				$this->db->or_like('lower(nombre_cliente)', strtolower($params['search']));
				$this->db->group_end();
			}
		}
		// Obtencion de resultados finales
		$response = $this->db->get();
		$json_data = array(
			'total_register' => $count_items,
			'data_list' => $response,
		);
		return $json_data;
	}

	public function get_order_service_list($params = array())
	{

		/*parametros*/
		$days = $params["days"];
		$date_start_reception = $params["date_start_reception"];
		$date_end_reception = $params["date_end_reception"];
		$reception_code = $params["reception_code"];

		/* Se cachea la informacion que se arma en query builder*/
		$this->db->start_cache();
		$this->db->select('*');
		$this->db->from('vista_orden_trabajo');
		$this->db->where('sucursal_id', get_branch_id_in_session());
		/*$this->db->where('estado_trabajo',  REPARADO);
        $this->db->or_where('estado_trabajo', EN_MORA); si
        $this->db->or_where('estado_trabajo', ESPERA_STOCK); si
        $this->db->or_where('estado_trabajo', APROBADO);
        $this->db->or_where('estado_trabajo', EN_PROCESO); si */
		$this->db->where('estado_trabajo!=', RECEPCIONADO);
		$this->db->where('estado_trabajo!=', CONCLUIDO);/* si puede poner en este estado*/
		$this->db->where('estado_trabajo!=', ENTREGADO);
		$this->db->where('estado_trabajo!=', ENTREGADO_ESPERA_STOCK);
		$this->db->where('estado_trabajo!=', NO_APROBADO);

		if ($reception_code != '') {
			$this->db->like('lower(codigo_trabajo)', strtolower($reception_code));
		} else {
			if ($days == 2) {
				$this->db->where('fecha_registro <=', $this->substrack_date());
			} else {
				if (isset($date_start_reception) && $date_start_reception != '') {
					$this->db->where('DATE(fecha_registro) >=', $date_start_reception);
				}

				if (isset($date_end_reception) && $date_end_reception) {
					$this->db->where('DATE(fecha_registro) <=', $date_end_reception);
				}
			}
		}

		$this->db->stop_cache();
		$count_items = count($this->db->get()->result());

		if ($days == 1 && $reception_code == '') {
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
				$this->db->order_by('id', 'ASC');
			}

			if (array_key_exists('search', $params)) {
				$this->db->group_start();
				$this->db->like('lower(nombre_cliente)', strtolower($params['search']));
				$this->db->or_like('lower(codigo_trabajo)', strtolower($params['search']));
				$this->db->group_end();
			}
		}
		// Obtencion de resultados finales
		$response = $this->db->get();
		$this->db->flush_cache();

		$json_data = array(
			'total_register' => $count_items,
			'data_list' => $response,
		);
		return $json_data;
	}

	public function add_row_service()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array()
		);

		// Reglas de validacion
		$validation_rules = array(
			array(
				'field' => 'service_work',
				'label' => '<strong style="font-style: italic">SERVICIO</strong>',
				'rules' => 'trim|required',

			),
			array(
				'field' => 'gama_work',
				'label' => '<strong style="font-style: italic">GAMA</strong>',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'price_work',
				'label' => '<strong style="font-style: italic">PRECIO</strong>',
				'rules' => 'trim|required'
			)
		);

		$this->form_validation->set_rules($validation_rules);
		$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
		//$this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');

		if ($this->form_validation->run() === true) {
			$service = $this->service_model->get_service_id($this->input->post("service_work"));
			$category = $this->category_model->get_category_id($this->input->post("gama_work"));
			$tipo_servicio = $this->type_service_model->get_type_service_id($service->tipo_servicio_id);
			$service_price = $this->input->post("price_work");
			$observation = $this->input->post("order_work_observation");
			$fila = '<tr data-price="' . number_format($service_price, '2', '.', '') . '">';
			$fila .= '<td><input type="number" value="' . $category->id . '"  name="categoryid[]" hidden/>' . $tipo_servicio->nombre . '</td>';
			$fila .= '<td><input type="text" value="' . $service->id . '" name="serviceid[]" hidden/>' . $service->nombre . '('. $observation .')</td>';
			$fila .= '<td align="right"><input type="number" value="' . $service_price . '" name="serviceprice[]" hidden/>' . $service_price .'</td>';
			$fila .= '<td class="text-center"><input type="text" name="serviceobservation[]" value="' . $observation . '" hidden><a class="elimina_service btn-danger btn" ><i class="material-icons">delete_forever</i><span class="icon-name">Eliminar</span></a></td>';
			$fila .= '</tr>';
			$response['success'] = true;
			$response['data'] = $fila;
		} else {
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}
		return $response;
	}

	public function add_row_product()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array()
		);
		// Reglas de validacion
		$validation_rules = array(
			// array(
			// 	'field' => 'brand_product',
			// 	'label' => '<strong style="font-style: italic">MARCA</strong>',
			// 	'rules' => 'trim|required',

			// ),
			// array(
			// 	'field' => 'model_product',
			// 	'label' => '<strong style="font-style: italic">MODELO</strong>',
			// 	'rules' => 'trim|required'
			// ),
			array(
				'field' => 'producto_order_work',
				'label' => '<strong style="font-style: italic">REPUESTO</strong>',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'price_product',
				'label' => '<strong style="font-style: italic">PRECIO UNITARIO</strong>',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'quantity_product',
				'label' => '<strong style="font-style: italic">CANTIDAD</strong>',
				'rules' => 'trim|required'
			)
		);

		$this->form_validation->set_rules($validation_rules);
		$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
		//$this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');

		if ($this->form_validation->run() === true) {
			// $model = $this->model_model->get_model_id($this->input->post("model_product"));
			$product = $this->product_model->get_product_by_id(intval($this->input->post("product_selected")));
			$quantity = $this->input->post("quantity_product");
			$price_product = $this->input->post("price_product");
			$price_product_sale = $this->input->post("price_sale");
			$warehouse_id = $this->input->post("warehouse_id");
			$observation = $this->input->post("order_work_observation");
			$fila = '<tr data-price_product="' . $price_product . '" data-price_sale="' . $price_product_sale . '" data-quantity="' . $quantity . '" data-product_id="' . $product->id . '" data-price="' . number_format($price_product_sale * $quantity, 2, '.', '') . '" >';
			// $fila .= '<td>' . $model->nombre . '</td>';
			$fila .= '<td>' . $product->modelo . '</td>';
			$fila .= '<td hidden><input type="text" value="' . $warehouse_id . '"  name="product_warehouse_id[]" /></td>';
			$fila .= '<td><input value="' . $product->id . '"  name="product_id[]" hidden/>' . $product->nombre_comercial . '</td>';
			$fila .= '<td align="right"><input value="' . $quantity . '" name="quantity_product[]" hidden/>' . number_format($quantity, CANTIDAD_MONTO_DECIMAL, '.', '') . '</td>';
			$fila .= '<td align="right"><input value="' . $price_product . '" name="price_product[]" hidden/><input value="' . $price_product_sale . '" name="price_sale[]" hidden/>' . number_format(($price_product_sale), 2, '.', '') . '</td>';
			$fila .= '<td align="right">' . number_format($quantity * $price_product_sale, 2, '.', '') . '</td>';
			$fila .= '<td class="text-center"><a class="elimina btn-danger btn" ><i class="material-icons">delete_forever</i><span class="icon-name">Eliminar</span></a></td>';
			$fila .= '</tr>';
			$response['success'] = true;
			$response['data'] = $fila;
			$response['product_id'] = intval($this->input->post("product_selected"));
			$response['product'] = json_encode($product);
		} else {
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}
		return $response;
	}

	public function add_row_product_recondition()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array()
		);
		// Reglas de validacion
		$validation_rules = array(
			// array(
			// 	'field' => 'brand_product_recondition',
			// 	'label' => '<strong style="font-style: italic">MARCA</strong>',
			// 	'rules' => 'trim|required',

			// ),
			// array(
			// 	'field' => 'model_product_recondition',
			// 	'label' => '<strong style="font-style: italic">MODELO</strong>',
			// 	'rules' => 'trim|required'
			// ),
			array(
				'field' => 'producto_order_work_recondition',
				'label' => '<strong style="font-style: italic">REPUESTO</strong>',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'price_product_recondition',
				'label' => '<strong style="font-style: italic">PRECIO UNITARIO</strong>',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'quantity_product_recondition',
				'label' => '<strong style="font-style: italic">CANTIDAD</strong>',
				'rules' => 'trim|required'
			)
		);

		$this->form_validation->set_rules($validation_rules);
		$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
		//$this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');

		if ($this->form_validation->run() === true) {
			// $model = $this->model_model->get_model_id($this->input->post("model_product_recondition"));
			$product = $this->product_model->get_product_by_id($this->input->post("product_selected_recondition"));
			$quantity = $this->input->post("quantity_product_recondition");
			$price_product = $this->input->post("price_product_recondition");
			$fila = '<tr data-price_product_recondition="' . $price_product . '" data-quantity_recondition="' . $quantity . '" data-product_id_recondition="' . $product->id . '" data-price_recondition="' . number_format($price_product * $quantity, 2, '.', '') . '" >';
			$fila .= '<td>' . $product->modelo . '</td>';
			$fila .= '<td><input value="' . $product->id . '"  name="product_id_recondition[]" hidden/>' . $product->nombre_comercial . '</td>';
			$fila .= '<td align="right"><input value="' . $quantity . '" name="quantity_product_recondition[]" hidden/>' . number_format($quantity, CANTIDAD_MONTO_DECIMAL, '.', '') . '</td>';
			$fila .= '<td class="text-center"><a class="elimina btn-danger btn" ><i class="material-icons">delete_forever</i><span class="icon-name">Eliminar</span></a></td>';
			$fila .= '</tr>';
			$response['success'] = true;
			$response['data'] = $fila;
		} else {
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}
		return $response;
	}


	public function _insert_detail_orderwork_service($data)
	{
		$this->db->insert('detalle_orden_trabajo_servicio', $data);
	}

	public function _insert_detail_product_work($data)
	{
		$this->db->insert('detalle_producto_trabajo', $data);
	}

	public function _insert_detail_product_work_recycling($data)
	{
		$this->db->insert('detalle_producto_reciclado', $data);
	}

	public function _update_order_work($data, $id)
	{
		$this->db->where('id', $id);
		$this->db->update('orden_trabajo', $data);
	}

	public function get_detail_product_by_reception_id($reception_id)
	{
		$this->db->select('prd.id AS producto_id, mod.nombre AS nombre_modelo, prd.nombre_comercial AS  nombre_producto, det.cantidad, det.precio_venta, det.precio_costo')
			->from('orden_trabajo ord, detalle_producto_trabajo det, producto prd, modelo mod')
			->where('det.orden_trabajo_id = ord.id')
			->where('prd.id = det.producto_id')
			->where('mod.id = prd.modelo_id')
			->where('ord.recepcion_id', $reception_id);
		return $this->db->get()->result();
	}

	public function get_order_by_reception_id($reception_id)
	{
		return $this->db->get_where('orden_trabajo', array('recepcion_id' => $reception_id))->row();
	}

	/* Actualiza los ESTADOS de Orden Trabajo*/
	public function update_state_order($reception_id, $state_reception)
	{
		$state_reception = intval($state_reception);
		$response = array(
			'success' => FALSE,
			'messages' => 'Se produjo un error, por favor contacte con su administrador de sistema.',
			'login' => FALSE,
			'tecnico' => TRUE,
			'inventory' => FALSE
		);

		if (verify_session()) {
			if (intval($this->reception_model->get_only_recepetion_by_id($reception_id)->garantia) == SIN_GARANTIA) {

				if ($state_reception == CONCLUIDO) {
					if (intval(get_user_type_in_session()) === 5 || intval(get_user_type_in_session()) === 1 || intval(get_user_type_in_session()) === 2 || intval(get_user_type_in_session()) === 3) {
						$response = $this->register_order_work_output_inventory($this->get_order_by_reception_id($reception_id)->id, $reception_id, SIN_GARANTIA);
						// $this->register_inventory_recycled_product($reception_id); 							///QUE HACE ESTE COIDIGO?????
						if ($response['verify'] == TRUE && $response['success'] == TRUE) {
							$response['entro_por_stock'] = 'por aki deberia';
							$this->db->where('recepcion_id', $reception_id);
							$data['estado_trabajo'] = $state_reception;
							if ($state_reception == CONCLUIDO) {
								$data['concluido_usuario_id'] = get_user_id_in_session();
								$data['fecha_concluido'] = date('Y-m-d H:i:s');
							}
							$this->db->update('orden_trabajo', $data);
							$this->register_order_work_history($this->get_order_by_reception_id($reception_id)->id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception));
							$response['data_error'] = 'orden_id: ' . $this->get_order_by_reception_id($reception_id)->id . ' tipo: ACTUALIZACION DE ESTADO' . ' estado: ' . 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception);

						} else {
							if ($response['success'] == TRUE) {
								$this->db->where('recepcion_id', $reception_id);
								$data['estado_trabajo'] = $state_reception;
								if ($state_reception == CONCLUIDO) {
									$data['concluido_usuario_id'] = get_user_id_in_session();
									$data['fecha_concluido'] = date('Y-m-d H:i:s');
								}
								$this->db->update('orden_trabajo', $data);
								$this->register_order_work_history($this->get_order_by_reception_id($reception_id)->id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception));
								$response['data_error'] = 'orden_id: ' . $this->get_order_by_reception_id($reception_id)->id . ' tipo: ACTUALIZACION DE ESTADO' . ' estado: ' . 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception);

							} else if ($response['success'] == FALSE) {
								$response['data_error'] = 'orden_id: ' . $this->get_order_by_reception_id($reception_id)->id . ' tipo: ACTUALIZACION DE ESTADO' . ' estado: ' . 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception);
								$response['entro_por_stock'] = 'cualquier cosa';
								$response['success'] = FALSE;
							}
						}
					} else {
						$response['tecnico'] = FALSE;
					}
				} else if ($state_reception == ENTREGADO) {/* generate_sale_by_order_work_id(orden_trbajo_id)*/
					if (intval($this->get_order_by_reception_id($reception_id)->estado_trabajo) !== SIN_SOLUCION) {
						$response['sale'] = TRUE;
						$response['success'] = TRUE;
						$response['reception_id'] = $reception_id;
					} else {
						$this->db->where('recepcion_id', $reception_id);
						$data['estado_trabajo'] = $state_reception;
						if ($state_reception == ENTREGADO) {
							$data['entrega_usuario_id'] = get_user_id_in_session();
							$data['fecha_entrega'] = date('Y-m-d H:i:s');
						}
						$this->db->update('orden_trabajo', $data);
						$this->register_order_work_history($this->get_order_by_reception_id($reception_id)->id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception));
						$this->update_date_delivery_reception($reception_id);/*Actualiziamos la fecha de entrega*/
						$response['success'] = TRUE;
						$response['entro'] = TRUE;
						$response['messages'] = 'Registro realizado correctamente';
					}

				} else if ($state_reception == NO_APROBADO) {/* generate_sale_by_order_work_id(orden_trbajo_id)*/

					$this->register_not_approved_service($reception_id);
					$this->db->where('recepcion_id', $reception_id);
					$data['estado_trabajo'] = $state_reception;
					$this->db->update('orden_trabajo', $data);
					$this->register_order_work_history($this->get_order_by_reception_id($reception_id)->id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception));

					$response['success'] = TRUE;
					$response['messages'] = 'Registro realizado correctamente';

				} else if ($state_reception == APROBADO || $state_reception == EN_MORA || $state_reception == ESPERA_STOCK || $state_reception == ENTREGADO_ESPERA_STOCK) {
					$order_work = $this->get_order_by_reception_id($reception_id);
					if ($order_work->estado_proforma != 0) {
						$this->db->where('recepcion_id', $reception_id);
						$data['estado_trabajo'] = $state_reception;
						$this->db->update('orden_trabajo', $data);
						$this->register_order_work_history($order_work->id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception));
						$response['success'] = TRUE;
						$response['messages'] = 'Registro realizado correctamente';
						$response['data_error'] = 'orden_id: ' . $this->get_order_by_reception_id($reception_id)->id . ' tipo: ACTUALIZACION DE ESTADO' . ' estado: ' . 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception);
					} else {
						$response['success'] = FALSE;
						$response['messages'] = 'No puede cambiar de estado. Tiene que agregar su proforma';
					}

				} else {
					$this->db->where('recepcion_id', $reception_id);
					$data['estado_trabajo'] = $state_reception;
					$this->db->update('orden_trabajo', $data);
					$this->register_order_work_history($this->get_order_by_reception_id($reception_id)->id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception));
					$response['success'] = TRUE;
					$response['messages'] = 'Registro realizado correctamente';
				}

			} else {

				if (intval($this->reception_model->get_only_recepetion_by_id($reception_id)->garantia) == CON_GARANTIA) {

					if ($state_reception == ENTREGADO) {
						$response['success'] = TRUE;
						$this->db->where('recepcion_id', $reception_id);
						$data['estado_trabajo'] = $state_reception;
						if ($state_reception == ENTREGADO) {
							$data['entrega_usuario_id'] = get_user_id_in_session();
							$data['fecha_entrega'] = date('Y-m-d H:i:s');
						}
						$this->db->update('orden_trabajo', $data);
						$this->register_order_work_history($this->get_order_by_reception_id($reception_id)->id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception));
						$this->update_date_delivery_reception($reception_id);
						$response['messages'] = 'Registro realizado correctamente';
					} else {
						$this->db->where('recepcion_id', $reception_id);
						$data['estado_trabajo'] = $state_reception;
						if ($state_reception == CONCLUIDO) {
							$data['concluido_usuario_id'] = get_user_id_in_session();
							$data['fecha_concluido'] = date('Y-m-d H:i:s');
						}
						$this->db->update('orden_trabajo', $data);
						$this->register_order_work_history($this->get_order_by_reception_id($reception_id)->id, 'ACTUALIZACION DE ESTADO', 'PROCESO CAMBIADO A ' . get_work_order_states($state_reception));
						$response['success'] = TRUE;
						$response['messages'] = 'Registro realizado correctamente';
					}
				}
			}

			/* UPDATE DEL PROCENTAJE*/
			if ($state_reception == APROBADO || $state_reception == EN_MORA || $state_reception == ESPERA_STOCK || $state_reception == ENTREGADO_ESPERA_STOCK) {
				$this->db->set('progreso', 50);
				$this->db->where('recepcion_id', $reception_id);
				$this->db->update('orden_trabajo');
			}

			if ($state_reception == EN_PROCESO) {
				$this->db->set('progreso', 80);
				$this->db->where('recepcion_id', $reception_id);
				$this->db->update('orden_trabajo');
			}

			if ($state_reception == CONCLUIDO || $state_reception == ENTREGADO || $state_reception == NO_APROBADO || $state_reception == CONCLUIDO) {
				$this->db->set('progreso', 100);
				$this->db->where('recepcion_id', $reception_id);
				$this->db->update('orden_trabajo');
			}
		} else {
			$response['login'] = TRUE;
		}

		return $response;
	}

	public function register_order_work_output_inventory($order_work_id, $reception_id, $type_guarantee)
	{
		$response = array(
			'success' => FALSE,
			'messages' => 'Se cambio el estado correctamente.',
			'login' => FALSE,
			'inventory' => FALSE,
			'verify' => FALSE
		);

		if (verify_session()) {
			if (!$this->register_verify_order_work_inventory($order_work_id)) {
				if ($this->verify_detail_product_by_order_work_id($order_work_id)) {
					$today = date('Y-m-d H:i:s');

					$data_order_work_inventory = [];
					$data_order_work_inventory["fecha_registro"] = $today;
					$data_order_work_inventory["fecha_modificacion"] = $today;
					$data_order_work_inventory["sincronizado"] = 0;
					$data_order_work_inventory["estado"] = ACTIVO;
					$data_order_work_inventory["sucursal_id"] = get_branch_id_in_session();
					$data_order_work_inventory["observacion"] = 'La salida de inventario desde O.T. cambio de repuesto';
					$data_order_work_inventory["tipo_salida_inventario_id"] = 2;//2=salida por orden de trabajo
					$data_order_work_inventory["nro_salida_inventario"] = $this->last_number_inventory_output();

					$order_work = $this->get_order_work_id($order_work_id);
					$detail_order_work_list = $this->get_detail_product_by_order_work_id($order_work_id);

					$this->db->trans_begin();

					/*Registramos en la tabla salida inventario*/
					$this->_insert_order_work_inventory($data_order_work_inventory);

					/*obtenemos la tupla que se inserto en la tabla salida inventario*/
					$order_work_inventory_inserted = $this->_get_order_work_inventory($data_order_work_inventory);

					$data_order_work_output_inventory["orden_trabajo_id"] = $order_work_id;
					$data_order_work_output_inventory["salida_inventario_id"] = $order_work_inventory_inserted->id;

					/*Registramos en la tabla salida salida inventario orden trabajo*/
					$this->_insert_order_work_output_inventory($data_order_work_output_inventory);
					$verify_stock = $this->verify_stock($detail_order_work_list, $order_work->sucursal_id, $type_guarantee, $order_work_id, $reception_id);
					if ($verify_stock) {
						foreach ($detail_order_work_list as $detail_order_work) {
							$quantity = $detail_order_work->cantidad;
							$product_id = $detail_order_work->producto_id;
							$branch_office_id = $order_work->sucursal_id;

							$output_inventory_id = $order_work_inventory_inserted->id;
							$discount_inventory_for_lote = $this->stock_decrese_inventory_by_transit($branch_office_id, $product_id, $reception_id, $type_guarantee);
							if ($discount_inventory_for_lote != null) {
								foreach ($discount_inventory_for_lote as $row) {
									$inventory_id = $row['id'];
									$stock_inventory = $row['cantidad'];
									$warehouse_id = $row['almacen_id'];
									$product_id = $row['producto_id'];
									$current_average_cost = get_current_average_cost($warehouse_id, $product_id)->precio_costo_ponderado;
									/*verifica bien pero abria que filtra la salida rapido cuando quantity sea =0*/
									if ($quantity > 0) {
										if ($stock_inventory <= $quantity) {
											$data_detail_order_work_inventory["cantidad"] = $stock_inventory;
											$data_detail_order_work_inventory["precio_costo"] = $current_average_cost;
											$data_detail_order_work_inventory["precio_venta"] = $detail_order_work->precio_venta;
											$data_detail_order_work_inventory["observacion"] = "Salida de inventario por orden de trabajo";
											$data_detail_order_work_inventory["salida_inventario_id"] = $output_inventory_id;
											$data_detail_order_work_inventory["inventario_id"] = $inventory_id;
											$this->_insert_detail_order_work_inventory($data_detail_order_work_inventory);
											$quantity_update = 0;
											$this->_update_stock_inventory($inventory_id, $quantity_update);
											$quantity = $quantity - $stock_inventory;
										} else {
											$data_detail_order_work_inventory["cantidad"] = $quantity;
											$data_detail_order_work_inventory["precio_costo"] = $current_average_cost;
											$data_detail_order_work_inventory["precio_venta"] = $detail_order_work->precio_venta;
											$data_detail_order_work_inventory["observacion"] = "Salida de inventario por orden de trabajo";
											$data_detail_order_work_inventory["salida_inventario_id"] = $output_inventory_id;
											$data_detail_order_work_inventory["inventario_id"] = $inventory_id;
											$this->_insert_detail_order_work_inventory($data_detail_order_work_inventory);
											$quantity_update = $stock_inventory - $quantity;
											$this->_update_stock_inventory($inventory_id, $quantity_update);
											$quantity = 0;
										}
									}
								}
							}
						}

						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							$response['success'] = FALSE;
							$response['messages'] = 'Su inventario no cuenta con el stock suficiente de estos productos';
						} else {
							$this->db->trans_commit();
							$this->inventory_model->update_product_in_inventory_branch_office($detail_order_work_list);
							$response['verify'] = TRUE;
							$response['success'] = TRUE;
						}

					} else {
						$this->db->trans_rollback();
						$response['success'] = FALSE;
						$response['messages'] = 'Los productos que estan en esta recepcion no cuentan con stock suficiente para realizar la salida de inventario';
					}
				} else {
					$response['success'] = TRUE;
				}
			} else {
				$response['success'] = TRUE;
				$response['verify'] = TRUE;
			}
		} else {
			$response['login'] = TRUE;
		}

		return $response;
	}

	public function verify_stock($product_list, $branch_office_id, $type_guarantee, $order_work_id, $reception_id)
	{
		$verify = true;
		foreach ($product_list as $detail_order_work) {
			$quantity = $detail_order_work->cantidad;
			$product_id = $detail_order_work->producto_id;

			if (!$this->verify_stock_available_inventory($branch_office_id, $product_id, $quantity, $type_guarantee, $order_work_id, $reception_id)) {
				$verify = false;
				break;
			}
		}
		return $verify;
	}

	public function verify_stock_available_inventory($branch_office_id, $product_id, $quantity, $type_guarantee, $order_work_id, $reception_id)
	{
		/* --------------------Codigo nuevo-----------*/
		/*$this->db->select('*')
			->from('detalle_transito d, transito t, inventario i')
			->where('d.transito_id=t.id')
			->where('i.detalle_transito_id=d.id')
			->where('t.recepcion_id', $reception_id)
			->where('d.producto_id', $product_id)
			->where('i.cantidad >=', $quantity);
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return true;
		} else {
			return false;
		}*/

		/* --------------------Codigo antiguo-----------*/
		$this->load->model('warehouse_model');

		if ($type_guarantee == SIN_GARANTIA) {
			$warehouse = $this->warehouse_model->get_warehouse_without_guarantee(get_branch_id_in_session());

		} else if ($type_guarantee == CON_GARANTIA) {
			$warehouse = $this->warehouse_model->get_warehouse_with_guarantee(get_branch_id_in_session());
		}


		$this->db->select('i.*')
			->from('inventario_stock_general i')
			->where('almacen_id!=6')
			->where('sucursal_id', $branch_office_id)
			->where('producto_id', $product_id)
			->where('stock >=', $quantity);
		if (count($warehouse) > 0) {
			$this->db->where_in('i.almacen_id', $warehouse);
		} else {
			$warehouse = $this->warehouse_model->get_warehouse_other(get_branch_id_in_session());
			if (count($warehouse) > 0) {
				$this->db->where_in('i.almacen_id', $warehouse);
			} else {
				$this->db->where('i.almacen_id', 0);
			}
		}

		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_detail_product_by_order_work_id($order_work_id)
	{
		$this->db->select('prd.id AS producto_id, prd.nombre_comercial AS  nombre_producto, det.cantidad, det.precio_venta,')
			->from('orden_trabajo ord, detalle_producto_trabajo det, producto prd')
			->where('det.orden_trabajo_id = ord.id')
			->where('prd.id = det.producto_id')
			->where('ord.id', $order_work_id);
		return $this->db->get()->result();
	}

	/*Verifica si tiene detalle de productos para descontar la orden */
	public function verify_detail_product_by_order_work_id($order_work_id)
	{
		$this->db->select('prd.id AS producto_id, prd.nombre_comercial AS  nombre_producto, det.cantidad, det.precio_venta,')
			->from('orden_trabajo ord, detalle_producto_trabajo det, producto prd')
			->where('det.orden_trabajo_id = ord.id')
			->where('prd.id = det.producto_id')
			->where('ord.id', $order_work_id);
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/*Obtener datos del orden de trabajo apartit del id*/
	public function get_order_work_id($order_work_id)
	{
		return $this->db->get_where('orden_trabajo', array('id' => $order_work_id))->row();
	}

	/*Obtener datos del salida inventario orden de trabajo apartit de un array de campos*/
	public function _get_order_work_inventory($order_work)
	{
		return $this->db->get_where('salida_inventario', $order_work)->row();
	}

	/*Funcion privada para insertar en la base de datos del salida de inventario*/
	public function _insert_order_work_inventory($sale_inventario)
	{
		return $this->db->insert('salida_inventario', $sale_inventario);
	}

	/*Funcion privada para insertar en la base de datos del nueva venta*/
	public function _insert_order_work_output_inventory($order_work_output_inventory)
	{
		return $this->db->insert('salida_inventario_orden_trabajo', $order_work_output_inventory);
	}

	/*Insertamos en la tabla detalle salida inventario*/
	public function _insert_detail_order_work_inventory($detail_order_work_inventory)
	{
		return $this->db->insert('detalle_salida_inventario', $detail_order_work_inventory);
	}

	/*  Actualizar producto stock de inventario */
	public function _update_stock_inventory($inventory_id, $quantity)
	{
		$obj_inventory['cantidad'] = $quantity;
		$this->db->where('id', $inventory_id);
		$this->db->update('inventario', $obj_inventory);
	}

	/*Saca todos los lotes que tengan cantida de stock mayor a 0*/
	public function stock_decrese_inventory($warehouse_id, $product_id)
	{
		$this->db->select('i.*')
			->from('inventario i')
			// ->where('a.id=i.almacen_id')
			// ->where('a.estado', ACTIVO)
			->where('i.estado', ACTIVO)
			// ->where('a.sucursal_id', $branch_office_id)
			->where('i.almacen_id', $warehouse_id)
			->where('i.producto_id', $product_id)
			// ->where('a.id!=6')
			->where('i.cantidad >0');
		$this->db->order_by('i.id', 'ASC');
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return $response->result_array();
		} else {
			return null;
		}
	}

	public function stock_decrese_inventory_by_transit($branch_office_id, $product_id, $reception_id, $type_guarantee)
	{
		/*$this->db->select('i.*')
			->from('detalle_transito d, transito t, inventario i')
			->where('d.transito_id=t.id')
			->where('i.detalle_transito_id=d.id')
			->where('t.recepcion_id', $reception_id)
			->where('d.producto_id', $product_id)
			->where('i.cantidad >0');

		$this->db->order_by('i.id', 'ASC');
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return $response->result_array();
		} else {
			return null;
		}*/


		$this->load->model('warehouse_model');

		if ($type_guarantee == SIN_GARANTIA) {
			$warehouse = $this->warehouse_model->get_warehouse_without_guarantee(get_branch_id_in_session());

		} else if ($type_guarantee == CON_GARANTIA) {
			$warehouse = $this->warehouse_model->get_warehouse_with_guarantee(get_branch_id_in_session());
		}

		$this->db->select('i.*')
			->from('inventario i , almacen a')
			->where('a.id=i.almacen_id')
			->where('a.estado', ACTIVO)
			->where('i.estado', ACTIVO)
			->where('a.sucursal_id', $branch_office_id)
			->where('i.producto_id', $product_id)
			->where('a.id!=6')
			->where('i.cantidad >0');

		if (count($warehouse) > 0) {
			$this->db->where_in('i.almacen_id', $warehouse);
		} else {
			$warehouse = $this->warehouse_model->get_warehouse_other(get_branch_id_in_session());
			if (count($warehouse) > 0) {
				$this->db->where_in('i.almacen_id', $warehouse);
			} else {
				$this->db->where('i.almacen_id', 0);
			}
		}
		$this->db->order_by('i.id', 'ASC');
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return $response->result_array();
		} else {
			return null;
		}


	}

	/*verificar si ya existe orden de trabajo en salida de inventario*/
	public function register_verify_order_work_inventory($order_work_id)
	{
		$this->db->select('*')
			->from('salida_inventario_orden_trabajo')
			->where('orden_trabajo_id', $order_work_id);
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_detail_service_by_order_work_id($order_work_id)
	{
		$this->db->select('d.*,s.nombre as nombre_servicio, tips.nombre AS nombre_tipo_servicio, tips.id AS tipo_servicio_id')
			->from('detalle_orden_trabajo_servicio d, servicio s, tipo_servicio tips')
			->where('d.servicio_id=s.id')
			->where('tips.id=s.tipo_servicio_id')
			->where('orden_trabajo_id', $order_work_id);
		return $this->db->get()->result();
	}

	/*Funcion registrar nuevo tipo_almacen para validar los datos de la vista */
	public function register_order_work_history($order_work_id, $type_history, $description)
	{
		$data_notification = array(
			'tipo_historial' => $type_history,
			'descripcion' => $description,
			'fecha_registro' => date('Y-m-d H:i:s'),
			'estado' => ACTIVO,
			'orden_trabajo_id' => $order_work_id,
			'sucursal_id' => get_branch_id_in_session(),
			'usuario_id' => get_user_id_in_session()
		);

		// Inicio de transacción
		$this->db->trans_begin();

		// Registrar a la base de datos de nuevo tipo_almacen
		$this->_insert_history($data_notification);

		// Obtener resultado de transacción
		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();
			$response['success'] = TRUE;
		} else {
			$this->db->trans_rollback();
			$response['success'] = FALSE;
		}

		//echo json_encode($response);
	}

	/*Funcion privada para insertar en la base de datos del nuevo tipo_almacen*/
	public function _insert_history($order_work_history)
	{
		return $this->db->insert('historial_orden_trabajo', $order_work_history);
	}

	public function register_not_solution_service($reception_id, $observation)
	{
		$this->db->delete('detalle_orden_trabajo_servicio', array("orden_trabajo_id" => $this->get_order_by_reception_id($reception_id)->id));/* eliminamos to.do. el detalle*/
		$this->db->delete('detalle_producto_trabajo', array("orden_trabajo_id" => $this->get_order_by_reception_id($reception_id)->id));/*  antes de registrar, eliminamos to.do. el detalle*/
		$service = $this->db->get_where('servicio', array('tipo_servicio_id' => 3))->row();
		$detalle_orden_trabajo_servicio = [];
		$detalle_orden_trabajo_servicio['precio_servicio'] = 0;
		$detalle_orden_trabajo_servicio['observacion'] = $observation;
		$detalle_orden_trabajo_servicio['estado'] = ACTIVO;
		$detalle_orden_trabajo_servicio['servicio_id'] = $service->id;
		$detalle_orden_trabajo_servicio['orden_trabajo_id'] = $this->get_order_by_reception_id($reception_id)->id;
		$this->_insert_detail_orderwork_service($detalle_orden_trabajo_servicio);


		$orden['monto_subtotal'] = 0;
		$orden['observacion'] = $observation;
		$orden['descuento'] = 0;
		$orden['monto_total'] = 0;
		$orden['monto_pagado'] = 0;
		$orden['monto_deuda'] = 0;
		$orden['monto_saldo'] = 0;
		$orden['fecha_modificacion'] = date('Y-m-d H:i:s');
		$orden['perito_usuario_id'] = get_user_id_in_session();
		$orden['proforma_usuario_id'] = get_user_id_in_session();
		$orden['concluido_usuario_id'] = get_user_id_in_session();
		$orden['fecha_proforma'] = date('Y-m-d H:i:s');
		$orden['fecha_concluido'] = date('Y-m-d H:i:s');
		$orden['progreso'] = 100;
		$this->_update_order_work($orden, $this->get_order_by_reception_id($reception_id)->id);

		$obj_reception['fecha_modificacion'] = date('Y-m-d H:i:s');
		$obj_reception['monto_total'] = 0;
		$this->db->update('recepcion', $obj_reception, array("id" => $reception_id));

	}

	public function register_not_approved_service($reception_id)
	{
		$this->db->delete('detalle_orden_trabajo_servicio', array("orden_trabajo_id" => $this->get_order_by_reception_id($reception_id)->id));/* eliminamos to.do. el detalle*/
		$this->db->delete('detalle_producto_trabajo', array("orden_trabajo_id" => $this->get_order_by_reception_id($reception_id)->id));/*  antes de registrar, eliminamos to.do. el detalle*/
		$service = $this->db->get_where('servicio', array('tipo_servicio_id' => 4))->row();
		$detalle_orden_trabajo_servicio = [];
		$detalle_orden_trabajo_servicio['precio_servicio'] = $service->precio;
		$detalle_orden_trabajo_servicio['estado'] = ACTIVO;
		$detalle_orden_trabajo_servicio['servicio_id'] = $service->id;
		$detalle_orden_trabajo_servicio['orden_trabajo_id'] = $this->get_order_by_reception_id($reception_id)->id;
		$this->_insert_detail_orderwork_service($detalle_orden_trabajo_servicio);


		$orden['monto_subtotal'] = $service->precio;
		$orden['descuento'] = 0;
		$orden['monto_total'] = $service->precio;
		$orden['monto_pagado'] = 0;
		$orden['monto_deuda'] = 0;
		$orden['monto_saldo'] = 0;
		$orden['fecha_modificacion'] = date('Y-m-d H:i:s');
		$orden['fecha_proforma'] = date('Y-m-d H:i:s');
		$orden['fecha_concluido'] = date('Y-m-d H:i:s');
		$orden['proforma_usuario_id'] = get_user_id_in_session();
		$orden['concluido_usuario_id'] = get_user_id_in_session();

		$this->_update_order_work($orden, $this->get_order_by_reception_id($reception_id)->id);

		$obj_reception['fecha_modificacion'] = date('Y-m-d H:i:s');
		$obj_reception['monto_total'] = $service->precio;
		$this->db->update('recepcion', $obj_reception, array("id" => $reception_id));

	}

	public function update_date_delivery_reception($reception_id)
	{
		$obj_reception['fecha_entrega'] = date('Y-m-d H:i:s');
		$this->db->where('id', $reception_id);
		return $this->db->update('recepcion', $obj_reception);
	}

	public function substrack_date()
	{
		$fecha = date('Y-m-d');
		$nuevafecha = strtotime('-3 day', strtotime($fecha));
		$nuevafecha = date('Y-m-d', $nuevafecha);

		return $nuevafecha;
	}

	public function get_order_work_enable()
	{
		$this->db->select('*');
		$this->db->from('orden_trabajo');
		$this->db->order_by('codigo_trabajo');
		return $this->db->get()->result();
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

	public function get_reference_enable()
	{
		$this->db->select('*');
		$this->db->from('referencia');
		$this->db->where('estado', ACTIVO);
		return $this->db->get()->result();
	}

	public function get_fault_order_work_by_reception_id($orden_work_id)
	{
		$this->db->select('f.*');
		$this->db->from('falla_orden_trabajo fo, falla f');
		$this->db->where('fo.orden_trabajo_id', $orden_work_id);
		$this->db->where('fo.falla_id=f.id');
		return $this->db->get()->result();
	}

	public function get_reference_id($referencia_id)
	{
		return $this->db->get_where('referencia', array('id' => $referencia_id))->row();
	}

	public function register_inventory_recycled_product($reception_id)
	{
		$response = array(
			'success' => FALSE,
			'login' => FALSE
		);

		if (verify_session()) {


			$order_work = $this->get_order_by_reception_id($reception_id);
			$list_recycled_product = $this->get_recycled_product($order_work->id);
			$number_rows = count($list_recycled_product);
			if ($number_rows > 0) {


				$warehouse_technical_id = $this->warehouse_model->get_warehouse_technical(get_branch_id_in_session())->id;


				$today = date('Y-m-d H:i:s');

				/*Datos para registrar en la tabla ingreso de inventario*/
				$obj_entry_inventario = [];
				$obj_entry_inventario["nombre"] = 'INGRESO DE INVENTARIO POR CAMBIO DE REPUESTO DE LA ' . $order_work->codigo_trabajo;
				$obj_entry_inventario["fecha_ingreso"] = $today;
				$obj_entry_inventario["fecha_registro"] = $today;
				$obj_entry_inventario["fecha_modificacion"] = $today;
				$obj_entry_inventario["estado"] = ACTIVO;
				$obj_entry_inventario["tipo_ingreso_inventario_id"] = 5; // Tipo de ingreso por prestamo de pieza
				$obj_entry_inventario["sucursal_id"] = get_branch_id_in_session();
				$obj_entry_inventario["usuario_id"] = get_user_id_in_session();
				$obj_entry_inventario["nro_ingreso_inventario"] = $this->transfer_inventory_model->last_number_inventory_entry();

				$this->db->trans_begin();

				/*Insertamos en la tabla ingreso inventario*/
				$this->inventory_model->_insert_inventory_entry($obj_entry_inventario);
				$inventory_entry_inserted = $this->inventory_model->_get_inventory_entry($obj_entry_inventario);

				$detalle_prestado = '';

				foreach ($list_recycled_product as $row) {
					$product = $this->product_model->get_product_by_id($row->producto_id);

					/* Registro de nuevo ingreso de inventario */
					$obj_detail_entry_inventary["codigo"] = '0';
					$obj_detail_entry_inventary["cantidad"] = $row->cantidad;
					$obj_detail_entry_inventary["cantidad_ingresada"] = $row->cantidad;
					$obj_detail_entry_inventary["precio_compra"] = 0;
					$obj_detail_entry_inventary["precio_costo"] = 0;
					$obj_detail_entry_inventary["precio_venta"] = $row->precio_venta;
					$obj_detail_entry_inventary["fecha_ingreso"] = $today;
					$obj_detail_entry_inventary["fecha_modificacion"] = $today;
					$obj_detail_entry_inventary["estado"] = ACTIVO;
					$obj_detail_entry_inventary["almacen_id"] = $warehouse_technical_id;
					$obj_detail_entry_inventary["ingreso_inventario_id"] = $inventory_entry_inserted->id;
					$obj_detail_entry_inventary["producto_id"] = $row->producto_id;
					$detalle_prestado = $product->nombre_comercial . ' , ' . $detalle_prestado;

					$this->inventory_model->_insert_inventory($obj_detail_entry_inventary);
				}

				$obj_transit = [];
				$obj_transit['nro_prestamo'] = strval($this->transit_model->last_number_transit_by_branch_office_id(get_branch_id_in_session()));
				$obj_transit['nro_transaccion'] = $this->transit_model->last_number_transit_by_branch_office_id(get_branch_id_in_session());
				$obj_transit['tipo'] = 7; // MOTIVO RECICLADO
				$obj_transit['estado'] = ACTIVO;
				$obj_transit['estado_transito'] = PRESTADO;
				$obj_transit['recepcion_id'] = $reception_id;
				$obj_transit['observacion_prestamo'] = 'CAMBIO DE REPUESTO POR LA ORDEN ' . $order_work->codigo_trabajo;
				$obj_transit['detalle_prestamo'] = $detalle_prestado;
				$obj_transit['detalle_devolucion'] = $detalle_prestado;
				$obj_transit['fecha_transito_prestamo'] = $today;
				$obj_transit['fecha_registro_prestamo'] = $today;
				$obj_transit['usuario_entregador_id_prestamo'] = get_user_id_in_session();
				$obj_transit['usuario_solicitante_id_prestamo'] = get_user_id_in_session();
				$obj_transit['sucursal_origen_id_prestamo'] = get_branch_id_in_session();
				$obj_transit['sucursal_destino_id_prestamo'] = get_branch_id_in_session();
				$obj_transit['almacen_destino_id_prestamo'] = $warehouse_technical_id;
				$obj_transit['ingreso_inventario_id_prestamo'] = $inventory_entry_inserted->id;
				$obj_transit['sucursal_id_prestamo'] = get_branch_id_in_session();
				$obj_transit['codigo_recepcion'] = $order_work->codigo_trabajo;
				$this->transit_model->_insert_transit($obj_transit);
				$transit_inserted = $this->transit_model->_get_transit($obj_transit);

				foreach ($list_recycled_product as $new_row) {
					/*Registro de detalle de transito*/
					$obj_detail_transit["cantidad"] = intval($new_row->cantidad);
					$obj_detail_transit["estado"] = ACTIVO;
					$obj_detail_transit["transito_id"] = $transit_inserted->id;
					$obj_detail_transit["producto_id"] = $new_row->producto_id;

					$this->transit_model->_insert_detail_transit($obj_detail_transit);
				}

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$response['success'] = FALSE;
				} else {
					$this->db->trans_commit();
					$this->inventory_model->update_product_in_inventory_branch_office($list_recycled_product);
					$response['success'] = TRUE;
				}
			}

		} else {
			$response['login'] = TRUE;
		}

		return $response;
	}

	public function get_recycled_product($order_work_id)
	{
		$this->db->select('*')
			->from('detalle_producto_reciclado')
			->where('orden_trabajo_id', $order_work_id)
			->where('estado', ACTIVO);
		return $this->db->get()->result();
	}

	////////////////////////////////////////////////////////////////////////////
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
}
