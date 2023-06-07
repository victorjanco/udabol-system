<?php

/**
 * Created by PhpStorm.
 * User: Gustavo Cisneros L.
 * Date: 20/7/2017
 * Time: 7:00 PM
 */

class Inventory extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('type_inventory_entry_model');
		$this->load->model('warehouse_model');
		$this->load->model('office_model');
		$this->load->model('inventory_model');
		$this->load->model('purchase_model');
		$this->load->model('provider_model');
		$this->load->model('type_product_model');
	}

	/*Mandar al index para cargar la lista de tipos de almacen*/
	public function index()
	{
		$data["type_inventory_entry"] = $this->type_inventory_entry_model->get_type_inventory_entry();
		$data["warehouse_list"] = $this->warehouse_model->get_warehouse_brand_office();
		template('inventory/index', $data);
	}
	
	public function update_average_price_especific_branch_office()
	{
		$this->inventory_model->update_average_price_especific_branch_office();
	}

	public function update_average_price_all()
	{
		$this->inventory_model->update_average_price_all();
	}
	public function register_in_inventory_warehouse()
	{
		$this->inventory_model->register_in_inventory_warehouse();
	}
	
	/* Envia al formulario de selecccion de tipo de inventario, para podere ingresar un nuevo registro*/
	public function type_inventory()
	{
		$data["type_inventory_entry"] = $this->type_inventory_entry_model->get_type_inventory_entry();
		$data["warehouse_list"] = $this->warehouse_model->get_warehouse_brand_office();
		template('inventory/type_inventory', $data);
	}

	/* Manda a vista de ingreso comun para registrar un nuevo inventario    */
	public function common()
	{
		if ($this->input->post()) {
			$data["type_inventory_entry_id"] = $this->input->post('id');
			template('inventory/common', $data);
		} else {
			template('inventory/index');
		}
	}

	/* permite agregar detalle a la tabla de detalless, para añadir tuplas*/
	public function add_detail_common()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->inventory_model->add_detail_common());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/* Registra datos de ingreso de inventario comun*/
	public function register_common()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->inventory_model->register_common());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/*Registro de ingreso de por compras   */
	public function register_inventory_purchase()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->inventory_model->register_inventory_purchase());
		} else {
			show_404();
		}
	}

	/*metodo para el indice principal, retorna la lista compelta de los ingresos de inventario*/
	public function get_inventory_list()
	{
		$start = $this->input->post('start');
		$limit = $this->input->post('length');
		$search = $this->input->post('search')['value'];
		$order = $this->input->post('order')['0']['dir'];
		$column_num = $this->input->post('order')['0']['column'];
		$column = $this->input->post('columns')[$column_num]['data'];

		// Se almacenan los parametros recibidos en un array para enviar al modelo
		$params = array(
			'start' => $start,
			'limit' => $limit,
			'search' => $search,
			'column' => $column,
			'order' => $order
		);
		echo json_encode($this->inventory_model->get_inventory_list($params));
	}

	public function edit()
	{
		if ($this->input->post()) {
			$inventory_id = $this->input->post('id');
			$inventory = $this->inventory_model->get_inventory_by_id($inventory_id);
			$data["inventory_entry"] = $inventory;
			// $data["tipo_ingreso_inventario"] = $this->inventory_model->get_type_inventory_by_inventory_entry_id($inventory_id);
			$data["tipo_ingreso_inventario"] = $this->type_inventory_entry_model->get_inventory_by_id($inventory->tipo_ingreso_inventario_id);
			$data["usuario"] = $this->inventory_model->get_user_by_inventory_entry_id($inventory_id);
			$data["inventory_detail"] = $this->inventory_model->get_detail_inventory($inventory_id);
			template('inventory/edit_common', $data);
		} else {
			show_404();
		}
	}

	public function view_inventory_output()
	{
		if ($this->input->post()) {
			$inventory_output_id = $this->input->post('id');
			$data["inventory_output"] = $this->inventory_model->get_inventory_output_by_id($inventory_output_id);
			$data["inventory_output_detail"] = $this->inventory_model->get_detail_inventory_output($inventory_output_id);
			$data["type_inventory_output"] = $this->inventory_model->get_type_inventory_by_inventory_output_id($inventory_output_id);
			template('inventory/view_inventory_output', $data);
		} else {
			show_404();
		}
	}

	public function register_edit_common()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->inventory_model->register_edit_common());
		} else {
			show_404();
		}
	}

	/*Vista de compras disponibles para el ingreso a inventario   */
	public function purchase_entry()
	{
		$data["type_inventory_entry_id"] = $this->input->post('id');
		template('inventory/list_purchase_entry', $data);
	}

	/* Listado de compras disponibles   */
	public function get_purchase_list()
	{
		if ($this->input->is_ajax_request()) {
			// Se recuperan los parametros enviados por datatable
			$start = $this->input->post('start');
			$limit = $this->input->post('length');
			$search = $this->input->post('search')['value'];
			$order = $this->input->post('order')['0']['dir'];
			$column_num = $this->input->post('order')['0']['column'];
			$column = $this->input->post('columns')[$column_num]['data'];

			// Se almacenan los parametros recibidos en un array para enviar al modelo
			$params = array(
				'start' => $start,
				'limit' => $limit,
				'search' => $search,
				'column' => $column,
				'order' => $order
			);

			echo json_encode($this->inventory_model->get_purchase_list($params));
		} else {
			show_404();
		}
	}


	public function new_purchase_entry()
	{
		$idPurchase = $this->input->post('id');
		$purchase = $this->purchase_model->get_purchase_by_id($idPurchase);
		$detail = $this->purchase_model->get_purchase_detail($idPurchase);

		$provider = $this->provider_model->get_provider_by_id($purchase["proveedor_id"]);

		$data = array(
			"purchase" => $purchase,
			"provider" => $provider,
			"detail" => $detail,
			"warehouse" => $this->warehouse_model->get_warehouse_enable(),
			"type_inventory_entry_id" => $this->input->post('type_inventory_entry_id')
		);

		template('inventory/new_purchase_entry', $data);
	}


	public function get_product_autocomplete()
	{
		if ($this->input->is_ajax_request()) {
			$dato = $this->input->post('name_startsWith');
			$tipo = $this->input->post('type');
			switch ($tipo) {
				case 'name_product_sale':
					$group_id = $this->input->post('subgroup_sale');
					$type_customer = $this->input->post('type_customer_sale');
					$sucursal_id = $this->input->post('branch_office_sale');
					$almacen_id = $this->input->post('warehouse_sale');
					$this->db->select('*');
					$this->db->from('vista_inventario_sucursal');
					$this->db->where('sucursal_id', $sucursal_id);
					$this->db->where('estado_is', ACTIVO);
					if ($group_id > 0) {
						$this->db->where('grupo_id', $group_id);
					}
					$this->db->where('almacen_id', $almacen_id)
						->group_start()
						->like('lower(nombre_comercial_producto)', strtolower($dato))
						->or_like('lower(nombre_generico_producto)', strtolower($dato))
						->or_like('lower(nombre_marca)', strtolower($dato))
						->or_like('lower(dimension_producto)', strtolower($dato))
						->limit(12)
						->group_end()
						->order_by('nombre_comercial_producto', 'asc');
					$res = $this->db->get();
					if ($res->num_rows() > 0) {
						foreach ($res->result_array() as $row) {
							switch ($type_customer) {
								case 0:
									$price = $row['precio_venta'];
									break;
								case 1:
									$price = $row['precio_venta_1'];
									break;
								case 2:
									$price = $row['precio_venta_2'];
									break;
								case 3:
									$price = $row['precio_venta_3'] + ($row['precio_venta_3'] * $row['porcentaje_precio_venta_3']);
									break;
							}
							// $stock_virtual = $this->inventory_model->verify_stock_detail_virtual($sucursal_id, $almacen_id, $row['producto_id']);
							$stock_virtual = 0;
							$stock_real = round($row['stock'], 0) - $stock_virtual;
							$data[$row['codigo_producto'] . '/' .
							// $row['nombre_comercial_producto'] .' - '. 
							$row['nombre_generico_producto'] . ' - '. $row['nombre_marca'] . ' - '. $row['dimension_producto'].'/' .
							number_format($price, CANTIDAD_MONTO_DECIMAL, '.', '') . '/' .
							round($stock_real, 0)] = $row['producto_id'] . '/' .
								$row['codigo_producto'] . '/' .
								$price . '/' .
								$row['unidad_medida_id'] . '/' .
								$row['nombre_unidad_medida'] . '/' .
								$row['sucursal_id'] . '/' .
								$row['almacen_id'];
						}
						echo json_encode($data); //format the array into json data
					} else {
						$data["No existen datos"] = "No existe datos";
						echo json_encode($data);
					}
					break;
				case 'code_product_sale':
					$type_customer = $this->input->post('type_customer_sale');
					$group_id = $this->input->post('subgroup_sale');
					$sucursal_id = $this->input->post('branch_office_sale');
					$almacen_id = $this->input->post('warehouse_sale');
					$this->db->select('*');
					$this->db->from('vista_inventario_sucursal');
					$this->db->where('sucursal_id', $sucursal_id);
					$this->db->where('estado_is', ACTIVO);
					if ($group_id > 0) {
						$this->db->where('grupo_id', $group_id);
					}
					$this->db->where('almacen_id', $almacen_id)
						->group_start()
						->like('codigo_producto', $dato)
						// ->or_like('codigo_barra', $dato)
						->limit(12)
						->group_end()
						->order_by('nombre_comercial_producto', 'asc');
					$res = $this->db->get();
					if ($res->num_rows() > 0) {
						foreach ($res->result_array() as $row) {
							switch ($type_customer) {
								case 0:
									$price = $row['precio_venta'];
									break;
								case 1:
									$price = $row['precio_venta_1'];
									break;
								case 2:
									$price = $row['precio_venta_2'];
									break;
								case 3:
									$price = $row['precio_venta_3'] + ($row['precio_venta_3'] * $row['porcentaje_precio_venta_3']);
									break;
							}
							// $stock_virtual = $this->inventory_model->verify_stock_detail_virtual($sucursal_id, $almacen_id, $row['producto_id']);
							$stock_virtual = 0;
							$stock_real = round($row['stock'], 0) - $stock_virtual;
							$data[$row['codigo_producto'] . '/' .
							// $row['nombre_comercial_producto'] .' - '.
							$row['nombre_generico_producto'] . ' - '. $row['nombre_marca'] . ' - '. $row['dimension_producto'].'/' .
							number_format($price, CANTIDAD_MONTO_DECIMAL, '.', '') . '/' .
							round($stock_real, 0)] = $row['producto_id'] . '/' .
								// $row['nombre_comercial_producto'] .' - '. $row['nombre_generico_producto'] . '/' .
								$row['nombre_generico_producto'] . ' - '. $row['nombre_marca'] . ' - '. $row['dimension_producto'].'/' .
								$price . '/' .
								$row['unidad_medida_id'] . '/' .
								$row['nombre_unidad_medida'] . '/' .
								$row['sucursal_id'] . '/' .
								$row['almacen_id'];
						}
						echo json_encode($data); //format the array into json data
					} else {
						$data["No existen datos"] = "No existe datos";
						echo json_encode($data);
					}
					break;
				case 'name_product_expertise':
					$model_product = $this->input->post('model_product');
					$warehouse_id = $this->input->post('warehouse_id');
					
					// Consulta vista_inventario_sucursal(nuevo)
					$this->db->select('*');
					$this->db->from('vista_inventario_sucursal')
						// ->where('modelo_id', $model_product)
						->where('almacen_id', $warehouse_id)
						->where('sucursal_id', get_branch_id_in_session())
						->where('almacen_id!=6')
						->where('tipo_producto_id', 1)
						->where('estado_is', ACTIVO)
						->group_start()
						->like('lower(nombre_comercial_producto)', strtolower($dato))
						->or_like('lower(nombre_generico_producto)', strtolower($dato))
						->or_like('lower(codigo_producto)', strtolower($dato))
						->or_like('lower(nombre_marca)', strtolower($dato))
						->or_like('lower(dimension_producto)', strtolower($dato))
						->limit(12)
						->group_end()
						->order_by('nombre_comercial_producto', 'asc');

					$res = $this->db->get();
					$data = [];
                    if ($res->num_rows() > 0) {
                        foreach ($res->result_array() as $row) {
							// $stock_virtual = $this->inventory_model->verify_stock_detail_virtual_by_branch(get_branch_id_in_session(), $row['producto_id']);
                            $stock_virtual = 0;
							
                            $stock_real = round($row['stock'], 0) - $stock_virtual;

                            $data[$row['codigo_producto'] . '/' .
							// $row['nombre_comercial_producto'] . '/' .
							$row['nombre_generico_producto'] . ' - '. $row['nombre_marca'] . ' - '. $row['dimension_producto'].'/' .
                            number_format($row['precio_compra'], CANTIDAD_MONTO_DECIMAL, '.', '') . '/' .
                            round($stock_real, 0)] = $row['producto_id'] . '/' .
								// $row['codigo_producto'] . '/' .
								$row['nombre_generico_producto'] . ' - '. $row['nombre_marca'] . ' - '. $row['dimension_producto'].'/' .
                                number_format($row['precio_compra'], CANTIDAD_MONTO_DECIMAL, '.', '') . '/' .
                                $row['unidad_medida_id'] . '/' .
                                $row['nombre_unidad_medida'] . '/' .
                                $row['sucursal_id'] . '/' .
                                $row['almacen_id']. '/' .
								$row['precio_venta']. '/' .
								round($stock_real, 0);
                        }
                    } else{
                        $data["No existen datos"] = "No existe datos";
                    }

                    echo json_encode($data); //format the array into json data
					break;
				case 'name_product_inventory_output':/* Utilizado para la salidad de inventario*/
					$type_product_id = $this->input->post('type_product_id');
					$branch_office_id = get_branch_id_in_session();
					$warehouse_id = $this->input->post('warehouse_id');
					$this->db->select('inv.*')
						->from('inventario_general as inv')
						->where('inv.sucursal_id', $branch_office_id)
						->where('inv.tipo_producto_id', $type_product_id)
						->where('inv.almacen_id', $warehouse_id)
						->where('inv.estado_producto', ACTIVO)
						->where('inv.estado_inventario', ACTIVO)
						->where('inv.stock>0')
						->group_start()
						->like('lower(inv.nombre_comercial)', strtolower($dato))
						->limit(12)
						->group_end();
					$res = $this->db->get();
					$data = [];
					if ($res->num_rows() > 0) {
						foreach ($res->result_array() as $row) {
							$data_index = $row['codigo_producto'] . ' / ' . $row['nombre_comercial'] .' - '. $row['nombre_generico'] .' / ' . $row['inventario_id'] . ' Lote ' . $row['lote'] . ' / ' . $row['stock'];
							$data_value = $row['inventario_id'] . '/' . $row['nombre_comercial'] .' - '. $row['nombre_generico'] . '/' . $row['codigo_producto'] . '/' . $row['lote'] . '/' . $row['stock'] . '/' . $row['precio_costo'] . '/' . $row['precio_venta'] . '/' . $row['fecha_vencimiento'] . '/' . $row['producto_id'];
							$data[$data_index] = $data_value;
						};
					} else {
						$data["No existen datos o tiene cantidad 0 el producto"] = "No existe datos o tiene cantidad 0 el producto";
					}
					echo json_encode($data);
					break;
				case 'code_product_inventory_output':
					$type_product_id = $this->input->post('type_product_id');
					$branch_office_id = get_branch_id_in_session();
					$warehouse_id = $this->input->post('warehouse_id');
					$this->db->select('inv.*')
						->from('inventario_general as inv')
						->where('inv.sucursal_id', $branch_office_id)
						->where('inv.tipo_producto_id', $type_product_id)
						->where('inv.almacen_id', $warehouse_id)
						->where('inv.estado_producto', ACTIVO)
						->where('inv.estado_inventario', ACTIVO)
						->where('inv.stock>0')
						->group_start()
						->or_like('lower(inv.codigo_producto)', strtolower($dato))
						->limit(12)
						->group_end();
					$res = $this->db->get();
					$data = [];
					if ($res->num_rows() > 0) {
						foreach ($res->result_array() as $row) {
							$data_index = $row['codigo_producto'] . ' / ' . $row['nombre_comercial'] . ' - '. $row['nombre_generico'] .' / ' . $row['inventario_id'] . ' Lote ' . $row['lote'] . ' / ' . $row['stock'];
							$data_value = $row['inventario_id'] . '/' . $row['nombre_comercial'] .' - '. $row['nombre_generico'] . '/' . $row['codigo_producto'] . '/' . $row['lote'] . '/' . $row['stock'] . '/' . $row['precio_costo'] . '/' . $row['precio_venta'] . '/' . $row['fecha_vencimiento'] . '/' . $row['producto_id'];
							$data[$data_index] = $data_value;
						};
					} else {
						$data["No existen datos o tiene cantidad 0 el producto"] = "No existe datos o tiene cantidad 0 el producto";
					}
					echo json_encode($data);
					break;

				case 'bar_code_reader_output':
					$type_product_id = $this->input->post('type_product_id');
					$branch_office_id = get_branch_id_in_session();
					$warehouse_id = $this->input->post('warehouse_id');
					$this->db->select('inv.*')
						->from('inventario_general as inv')
						->where('inv.sucursal_id', $branch_office_id)
						->where('inv.tipo_producto_id', $type_product_id)
						->where('inv.almacen_id', $warehouse_id)
						->where('inv.estado_producto', ACTIVO)
						->where('inv.estado_inventario', ACTIVO)
						->where('inv.stock>0')
						->group_start()
						->or_like('lower(inv.codigo_producto)', strtolower($dato))
						->limit(12)
						->group_end();
					$res = $this->db->get();
					$data = [];

					if ($res->num_rows() > 0) {
						$data = $res->row();
						$data->validacion = 1;
					} else {
						$data["validacion"] = 0;
					}

					echo json_encode($data);
					break;
				case 'name_product_transfer_inventory_output':/* Utilizado para la salidad de inventario*/
					$type_product_id = $this->input->post('type_product_id');
					$branch_office_id = get_branch_id_in_session();
					$warehouse_id = $this->input->post('warehouse_id');
					$this->db->select('*')
						->from('inventario_stock_general as inv')
						->where('inv.sucursal_id', $branch_office_id)
						->where('inv.tipo_producto_id', $type_product_id)
						->where('inv.almacen_id', $warehouse_id)
						->where('inv.estado_producto', ACTIVO)
						/*->where('inv.estado_inventario', ACTIVO)
                        ->where('inv.stock>0')*/
						->group_start()
						->like('lower(inv.nombre_comercial)', strtolower($dato))
						->limit(12)
						->group_end();
					$res = $this->db->get();

					/*echo json_encode($res->result_array());*/

					if ($res->num_rows() > 0) {

						foreach ($res->result_array() as $row) {
							/*$data_index = $row['codigo_producto'] . ' / ' . $row['nombre_comercial'] . ' / ' . $row['lote'] . ' / ' . $row['stock'];
                            $data_value = $row['inventario_id'] . '/' . $row['nombre_comercial'] . '/' . $row['codigo_producto'] . '/' . $row['lote'] . '/' . $row['stock'] . '/' . $row['precio_costo'] . '/' . $row['precio_venta'] . '/' . $row['fecha_vencimiento']. '/' . $row['producto_id'];*/
							$data[$row['codigo_producto'] . ' / ' . $row['nombre_comercial'] .' - '. $row['nombre_generico'] . ' / ' . $row['stock']] = $row['sucursal_id'] . '/' . $row['almacen_id'] . '/' . $row['producto_id'] . '/' . $row['nombre_comercial'] . '/' . $row['codigo_producto'];
						};
						echo json_encode($data);
					} else {
						$data["No existen datos o tiene cantidad 0 el producto"] = "No existe datos o tiene cantidad 0 el producto";
						echo json_encode($data);
					}
					break;

				case 'code_product_transfer_inventory_output':/* Utilizado para la salidad de inventario*/
					$type_product_id = $this->input->post('type_product_id');
					$branch_office_id = get_branch_id_in_session();
					$warehouse_id = $this->input->post('warehouse_id');
					$this->db->select('*')
						->from('inventario_stock_general as inv')
						->where('inv.sucursal_id', $branch_office_id)
						->where('inv.tipo_producto_id', $type_product_id)
						->where('inv.almacen_id', $warehouse_id)
						->where('inv.estado_producto', ACTIVO)
						/*->where('inv.estado_inventario', ACTIVO)
                        ->where('inv.stock>0')*/
						->group_start()
						->like('lower(inv.codigo_producto)', strtolower($dato))
						->limit(12)
						->group_end();
					$res = $this->db->get();

					/*echo json_encode($res->result_array());*/

					if ($res->num_rows() > 0) {

						foreach ($res->result_array() as $row) {
							/*$data_index = $row['codigo_producto'] . ' / ' . $row['nombre_comercial'] . ' / ' . $row['lote'] . ' / ' . $row['stock'];
                            $data_value = $row['inventario_id'] . '/' . $row['nombre_comercial'] . '/' . $row['codigo_producto'] . '/' . $row['lote'] . '/' . $row['stock'] . '/' . $row['precio_costo'] . '/' . $row['precio_venta'] . '/' . $row['fecha_vencimiento']. '/' . $row['producto_id'];*/
							$data[$row['codigo_producto'] . ' / ' . $row['nombre_comercial'] . ' / ' . $row['stock']] = $row['sucursal_id'] . '/' . $row['almacen_id'] . '/' . $row['producto_id'] . '/' . $row['nombre_comercial'] . '/' . $row['codigo_producto'];
						};
						echo json_encode($data);
					} else {
						$data["No existen datos o tiene cantidad 0 el producto"] = "No existe datos o tiene cantidad 0 el producto";
						echo json_encode($data);
					}
					break;

				case 'code_bar_product_transfer_inventory_output':
					$type_product_id = $this->input->post('type_product_id');
					$branch_office_id = get_branch_id_in_session();
					$warehouse_id = $this->input->post('warehouse_id');
					$this->db->select('*')
						->from('inventario_stock_general as inv')
						->where('inv.sucursal_id', $branch_office_id)
						->where('inv.tipo_producto_id', $type_product_id)
						->where('inv.almacen_id', $warehouse_id)
						->where('inv.estado_producto', ACTIVO)
						/*->where('inv.estado_inventario', ACTIVO)
                        ->where('inv.stock>0')*/
						->group_start()
						->or_like('lower(inv.codigo_producto)', strtolower($dato))
						->limit(12)
						->group_end();
					$res = $this->db->get();

					/*echo json_encode($res->result_array());*/

					if ($res->num_rows() > 0) {
						$data = $res->row();
						$data->validacion = 1;
						echo json_encode($data);
					} else {
						$data["validacion"] = 0;
						echo json_encode($data);
					}
					break;

				// Utilizado con el dispositivo "Lector de codigo de barra en ventas"
				case 'bar_code':
					$type_customer = $this->input->post('type_customer_sale');
					$group_id = $this->input->post('subgroup_sale');
					$sucursal_id = $this->input->post('branch_office_sale');
					$almacen_id = $this->input->post('warehouse_sale');
					$this->db->select('*');
					$this->db->from('inventario_stock_general');
					$this->db->where('sucursal_id', $sucursal_id);
					if ($group_id > 0) {
						$this->db->where('grupo_id', $group_id);
					}
					$this->db->where('almacen_id', $almacen_id)
						->group_start()
						->like('codigo_producto', $dato)
						->or_like('codigo_barra', $dato)
						->group_end()
						->order_by('nombre_comercial', 'asc');
					$res = $this->db->get();
					if ($res->num_rows() > 0) {
						$data = $res->row();
						switch ($type_customer) {
							case 0:
								$price = $data->precio_venta;
								break;
							case 1:
								$price = $data->precio_venta_mayor;
								break;
							case 2:
								$price = $data->precio_venta_express;
								break;
							case 3:
								$price = $data->precio_venta_laboratorio + ($data->precio_venta_laboratorio * $data->porcentaje_precioventa_laboratorio);
								break;
						}

						$stock_virtual = $this->inventory_model->verify_stock_detail_virtual($sucursal_id, $almacen_id, $data->producto_id);
						$stock_real = round($data->stock, 0) - $stock_virtual;
						$data->stock_real = $stock_real;
						$data->precio_real = $price;
						$data->validacion = 1;

						echo json_encode($data);
					} else {
						$data["validacion"] = 0;
						echo json_encode($data);
					}
					break;

                case 'name_product_reception':
                    $warehouse_id = $this->input->post('warehouse_id');

                    // Consulta vista_inventario_sucursal(nuevo)
                    $this->db->select('*');
                    $this->db->from('vista_inventario_sucursal')
                        ->where('sucursal_id', get_branch_id_in_session())
                        ->where('almacen_id', $warehouse_id)
                        // ->where('almacen_id!=6')
						->where('tipo_producto_id', 1)
						->where('estado_is', ACTIVO)
                        ->group_start()
                        ->like('lower(nombre_comercial_producto)', strtolower($dato))
                        ->or_like('lower(nombre_generico_producto)', strtolower($dato))
						->or_like('lower(codigo_producto)', strtolower($dato))
						->limit(12)
                        ->group_end()
                        ->order_by('nombre_comercial_producto', 'asc');

                    $res = $this->db->get();
                    $data = [];
                    if ($res->num_rows() > 0) {
                        foreach ($res->result_array() as $row) {
                            $stock_virtual = $this->inventory_model->verify_stock_detail_virtual_by_branch(get_branch_id_in_session(), $row['producto_id']);
                            $stock_real = round($row['stock'], 0) - $stock_virtual;

                            $data[$row['codigo_producto'] . '/' .
                            $row['nombre_comercial_producto'] . '/' .
                            number_format($row['precio_compra'], CANTIDAD_MONTO_DECIMAL, '.', '') . '/' .
                            round($stock_real, 0) . '/' .
                            $row["nombre_sucursal"]] = $row['producto_id'] . '/' .
                                $row['codigo_producto'] . '/' .
                                number_format($row['precio_compra'], CANTIDAD_MONTO_DECIMAL, '.', '') . '/' .
                                $row['unidad_medida_id'] . '/' .
                                $row['nombre_unidad_medida'] . '/' .
                                $row['sucursal_id'] . '/' .
                                $row['almacen_id']. '/' .
                                $row['precio_venta'];
                        }
                    } else{
                        $data["No existen datos"] = "No existe datos";
                    }

                    echo json_encode($data); //format the array into json data
                    break;

			}
		} else {
			show_404();
		}
	}

	public function verify()
	{
		//$response =$this->inventory_model->verify_stock_detail_virtual(1,1,1);
		$response = $this->inventory_model->verify_stock_available_inventory(1, 1, 1, 1);
		echo($response);
	}

	/* Redireccion a la vista de nueva salida de inventario */
	public function new_output()
	{
		$data['branch_office'] = get_branch_office_name_in_session();
		$data['list_type_product'] = $this->type_product_model->get_type_product();
		$data['list_type_exit_inventory'] = $this->type_inventory_entry_model->get_type_exit_inventory_enable();
		$data['list_warehouse'] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
		template('inventory/inventory_output_form', $data);
	}

	/* permite agregar detalle a la tabla de detalless, para añadir tuplas*/
	public function get_inventory_by_product_id()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->inventory_model->get_inventory_by_product_id());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	/* permite agregar detalle a la tabla de detalless, para añadir tuplas*/
	public function add_detail_output()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->inventory_model->add_detail_output());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	/* permite agregar detalle a la tabla de detalless, para añadir tuplas*/
	public function add_detail_output_array()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->inventory_model->add_detail_output_array());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/* permite agregar detalle a la tabla de detalless, para añadir tuplas*/
	public function add_transit_detail_output_array()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->inventory_model->add_transit_detail_output_array());
		} else {
			show_404();
		}
	}

	/*  Registrar salida de inventario  */
	public function register_inventory_output()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->inventory_model->register_inventory_output());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	/*Mandar al index para cargar la lista de tipos de almacen*/
	public function inventory_output()
	{
		$data["type_inventory_entry"] = $this->type_inventory_entry_model->get_type_inventory_entry();
		$data["warehouse_list"] = $this->warehouse_model->get_warehouse_brand_office();
		template('inventory/view_list_inventory_output', $data);
	}

	/* Obtener Listado de salida de inventario  */
	public function get_inventory_output_list()
	{
		if ($this->input->is_ajax_request()) {
			$start = $this->input->post('start');
			$limit = $this->input->post('length');
			$search = $this->input->post('search')['value'];
			$order = $this->input->post('order')['0']['dir'];
			$column_num = $this->input->post('order')['0']['column'];
			$column = $this->input->post('columns')[$column_num]['data'];

			// Se almacenan los parametros recibidos en un array para enviar al modelo
			$params = array(
				'start' => $start,
				'limit' => $limit,
				'search' => $search,
				'column' => $column,
				'order' => $order
			);
			echo json_encode($this->inventory_model->get_inventory_output_list($params));
		} else {
			show_404();
		}
	}

	/* Actualiza la cantidad ingresada del inventario, BD(inventario.cantidad_ingresada)
    por la suma del detalle de la salida de inventario mas la cantidad restante de inventario
    BD(inventario.cantidad + detalle_salida_inventario.cantidad)*/

	public function error_inventory_update()
	{
		$this->db->trans_begin();
		$inventory = $this->db->get('inventario');
		foreach ($inventory->result() as $row) {
			$quantity_total_detail_output = $this->inventory_model->get_detail_inventory_output_by_inventory_id($row->id);
			$quantity_current = $row->cantidad;
			$quantity_input = $quantity_total_detail_output + $quantity_current;

			$this->db->set('cantidad_ingresada', $quantity_input);
			$this->db->where('id', $row->id);
			$this->db->update('inventario');
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			echo "Correctamente ejecuto";
		}

	}

	public function disable_inventory_entry()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			echo json_encode($this->inventory_model->disable_inventory_entry_by_id($id));
		} else {
			show_404();
		}
	}

	public function disable_inventory_output()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			echo json_encode($this->inventory_model->disable_inventory_output_by_id($id));
		} else {
			show_404();
		}
	}

	function delete_detail_inventory_output()
	{
		$this->db->trans_begin();
		$this->db->select('*')
			->from('salida_inventario');
		$query = $this->db->get();

		foreach ($query->result() as $row) {
			$detail = $this->inventory_model->empty_detail_by_inventory_output_id($row->id);

			if ($detail == 0) {
				$this->db->delete('salida_inventario_orden_trabajo', array('salida_inventario_id' => $row->id));
				$this->db->delete('salida_inventario', array('id' => $row->id));
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			echo "Datos Eliminados Correctamente ya Puede trabajar con el Sistema The Best";
		}
	}

	function delete_detail_inventary_entry()
	{
		$this->db->trans_begin();
		$this->db->select('*')
			->from('ingreso_inventario');
		$query = $this->db->get();

		foreach ($query->result() as $row) {
			$detail = $this->inventory_model->empty_detail_by_inventory_entry_id($row->id);

			if ($detail == 0) {
				$this->db->set('estado', 0);
				$this->db->where('id', $row->id);
				$this->db->update('ingreso_inventario');
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			echo "Datos Ingreso de inventario Eliminados Correctamente ya Puede trabajar con el Sistema The Best";
		}
	}

	/* Nuevo procedimiento para actualizar el nuevo nro de ingreso de inventario */
	function update_nro_inventory_entry()
	{
		$this->db->trans_begin();
		$this->db->select('*')
			->from('ingreso_inventario')
			->order_by('id', 'ASC');
		$query = $this->db->get();

		foreach ($query->result() as $row) {
			$id = $row->id;
			$number_inventory_entry = $this->inventory_model->last_number_inventory_entry_by_sucursal_id($row->sucursal_id);

			$this->db->set('nro_ingreso_inventario', $number_inventory_entry);
			$this->db->where('id', $id);
			$this->db->update('ingreso_inventario');

		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			echo "Datos Registrados Correctamente ya Puede trabajar con el Sistema The Best";
		}
	}

	/* Nuevo procedimiento para actualizar el nuevo nro de salida de inventario */
	function update_nro_inventory_output()
	{
		$this->db->trans_begin();
		$this->db->select('*')
			->from('salida_inventario')
			->order_by('id', 'ASC');
		$query = $this->db->get();

		foreach ($query->result() as $row) {
			$id = $row->id;
			$number_inventory_output = $this->inventory_model->last_number_inventory_output_by_sucursal_id($row->sucursal_id);

			$this->db->set('nro_salida_inventario', $number_inventory_output);
			$this->db->where('id', $id);
			$this->db->update('salida_inventario');

		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			echo "Datos Registrados Correctamente ya Puede trabajar con el Sistema The Best";
		}
	}

	public function print_inventory_entry()
	{
		if ($this->input->post()) {
			$inventory_entry_id = $this->input->post('id');
			$inventory_entry = $this->inventory_model->get_inventory_by_id($inventory_entry_id);
			$detail_inventory_entry = $this->inventory_model->get_detail_inventory($inventory_entry_id);
			$branch_office = $this->office_model->get_branch_office_id($inventory_entry->sucursal_id);

			$this->load->library('pdf');
			$this->pdf = new Pdf('P', 'mm', 'Legal');

			$this->pdf->AddPage();
			// Define el alias para el número de página que se imprimirá en el pie
			$this->pdf->AliasNbPages();

			/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
             */
			$this->pdf->SetTitle("IMPRESION INVENTARIO");
			/* La variable $x se utiliza para mostrar un número consecutivo */

			/* titulo de ingreso*/
			$var_img = base_url() . 'assets/images/'.$branch_office->imagen;
			$this->pdf->Image($var_img, 10, 10, 80, 28);
			/*  NIT Y NRO FACTURA   */

			/* 1ra fila   */
//			$this->pdf->SetFont('Arial', 'B', 8);
//			$this->pdf->Cell(115, 5, '', 0, 0, 'C');
//			$this->pdf->Cell(80, 5, utf8_decode('SISTEMA VENTA'), 0, 0, 'C');

			/*2da fila*/
			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial', 'B', 12);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(85, 5, '', 0, 0, 'C');
			$this->pdf->Cell(40, 5, 'INGRESO INVENTARIO', 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 9);
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->MultiCell(70, 5, utf8_decode($branch_office->nombre_comercial), 0, 'C');

			/*3ra fila*/
			$this->pdf->Ln(0);
			$this->pdf->SetFont('Arial', 'B', 12);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(85, 5, '', 0, 0, 'C');
			$this->pdf->Cell(40, 5, utf8_decode('Nº' . $inventory_entry->nro_ingreso_inventario), 0, 0, 'C');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->MultiCell(70, 4, utf8_decode($branch_office->direccion), 0, 'C');

			/*4ta fila*/
			$this->pdf->Ln(0);
			$this->pdf->Cell(120, 5, '', 0, 0, 'C');
			$this->pdf->Cell(80, 4, 'Telf. ' . utf8_decode($branch_office->telefono), 0, 0, 'C');

			/*5ta fila*/
			$this->pdf->Ln(4);
			$this->pdf->Cell(85, 5, '', 0);
			$this->pdf->SetFont('Arial', 'B', 12);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(40, 5, utf8_decode(''), 0, 0, 'C');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->Cell(72, 4, utf8_decode($branch_office->ciudad_impuestos), 0, 0, 'C');

			$this->pdf->Ln(12);

			$anio = substr($inventory_entry->fecha_ingreso, 0, 4);
			$mes = substr($inventory_entry->fecha_ingreso, 5, 2);
			$dia = substr($inventory_entry->fecha_ingreso, 8, 2);
			$inventory_entry_date = $dia . ' de ' . get_month($mes) . ' del ' . $anio;

			// LUGAR Y FECHA , NRO COMPROBANTE
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, 'Lugar y Fecha : ', 'TL');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, utf8_decode($branch_office->ciudad) . ', ' . $inventory_entry_date, 'T');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(30, 5, 'Nro. Comprobante:', 'T');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(40, 5, utf8_decode($inventory_entry->nro_comprobante), 'TR');
			$this->pdf->Ln(5);

			// GLOSA
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, utf8_decode('Glosa               :'), 'LB');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(170, 5, utf8_decode($inventory_entry->nombre), 'BR');
			$this->pdf->Ln(7);

			//  DETALLE DE ITEMS
			$this->pdf->SetMargins(10, 10, 10);
			$this->pdf->SetFont('Arial', 'B', 8);

			// Encabezado de la columna
			$this->pdf->Cell(8, 5, "NRO.", 1, 0, 'C');
			$this->pdf->Cell(27, 5, "CODIGO", 1, 0, 'C');
			$this->pdf->Cell(82, 5, "PRODUCTO", 1, 0, 'C');
			$this->pdf->Cell(20, 5, "P. COMPRA", 1, 0, 'C');
			$this->pdf->Cell(15, 5, "P. VENTA", 1, 0, 'C');
			$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
			$this->pdf->Cell(20, 5, "ALMACEN", 1, 0, 'C');
			$this->pdf->Ln(5);


			//  detalle
			$nro = 1;
			$this->pdf->SetFont('Arial', '', 8);
			//$this->pdf->SetAligns(array('C', 'L', 'R'));
			$cantidad_filas = 0;
			$numero_items = 0;
			$estilo = 'RL';
			$total_detail = 10;

			//Table with 20 rows and 4 columns
			$this->pdf->SetWidths(array(8, 27, 82, 20, 15, 20, 20));
			$this->pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'C', 'C'));


			foreach ($detail_inventory_entry as $detail) {
				$cantidad_filas++;
				$productos = $this->product_model->get_product_by_id($detail->producto_id);
				$this->pdf->Row(array(
					utf8_decode($cantidad_filas),
					utf8_decode($productos->codigo),
					utf8_decode($productos->nombre_generico),
					utf8_decode($detail->precio_compra),
					utf8_decode($detail->precio_venta),
					utf8_decode(number_format($detail->cantidad_ingresada)),
					utf8_decode($this->warehouse_model->get_warehouse_id($detail->almacen_id)->nombre)
				));
				$nro = $nro + 1;
				$numero_items = $numero_items + 1;
			}

			while ($total_detail - 1 >= $numero_items) {

				$cantidad_filas++;
				$estilo = 'RL';
				if ($nro == 1) {
					$estilo = $estilo . 'T';
				}
				if ($cantidad_filas == $total_detail) {
					$estilo = 'LRB';
				}

				$this->pdf->Cell(8, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(27, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(82, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(20, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(15, 4, '', $estilo, 0, 'R');
				$this->pdf->Cell(20, 4, '', $estilo, 0, 'R');
				$this->pdf->Cell(20, 4, '', $estilo, 0, 'R');
				$this->pdf->Ln(4);
				$numero_items = $numero_items + 1;
			}

			$print_day = date('Y-m-d');
			$print_hour = date('H:i:s');
			$year = substr($print_day, 0, 4);
			$month = substr($print_day, 5, 2);
			$day = substr($print_day, 8, 2);
			$print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

			$this->pdf->Ln(7);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(26, 5, 'Fecha Impresion :', 'TBL', 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(40, 5, $print_date, 'BT', 0, 'L');

			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(30, 5, 'Hora Impresion :', 'BT', 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(30, 5, $print_hour, 'BT', 0, 'L');

			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, 'Usuario :', 'BT', 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(44, 5, get_user_name_in_session(), 'BTR', 0, 'L');

			$this->pdf->Output("Ingreso inventario " . date('Y-m-d') . ".pdf", 'I');
		} else {
			show_404();
		}


	}

	public function print_inventory_output()
	{
		if ($this->input->post()) {
			$inventory_output_id = $this->input->post('id');
			$inventory_output = $this->inventory_model->get_inventory_output_by_id($inventory_output_id);
			$detail_inventory_output = $this->inventory_model->get_detail_inventory_output_print($inventory_output_id);
			$branch_office = $this->office_model->get_branch_office_id($inventory_output->sucursal_id);

			$this->load->library('pdf');
			$this->pdf = new Pdf('P', 'mm', 'Legal');

			$this->pdf->AddPage();
			// Define el alias para el número de página que se imprimirá en el pie
			$this->pdf->AliasNbPages();

			/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
             */
			$this->pdf->SetTitle("IMPRESION INVENTARIO");
			/* La variable $x se utiliza para mostrar un número consecutivo */

			/* titulo de ingreso*/
			$var_img = base_url() . 'assets/images/'.$branch_office->imagen;
			$this->pdf->Image($var_img, 10, 10, 80, 28);
			/*  NIT Y NRO FACTURA   */

			/* 1ra fila   */
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(115, 5, '', 0, 0, 'C');
			$this->pdf->Cell(80, 5, utf8_decode(''), 0, 0, 'C');

			/*2da fila*/
			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial', 'B', 12);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(85, 5, '', 0, 0, 'C');
			$this->pdf->Cell(40, 5, 'SALIDA INVENTARIO', 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 9);
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->MultiCell(70, 5, utf8_decode($branch_office->nombre_comercial), 0, 'C');

			/*3ra fila*/
			$this->pdf->Ln(0);
			$this->pdf->SetFont('Arial', 'B', 12);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(85, 5, '', 0, 0, 'C');
			$this->pdf->Cell(40, 5, utf8_decode('Nº' . $inventory_output->nro_salida_inventario), 0, 0, 'C');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->MultiCell(70, 4, utf8_decode($branch_office->direccion), 0, 'C');

			/*4ta fila*/
			$this->pdf->Ln(0);
			$this->pdf->Cell(120, 5, '', 0, 0, 'C');
			$this->pdf->Cell(80, 4, 'Telf. ' . utf8_decode($branch_office->telefono), 0, 0, 'C');

			/*5ta fila*/
			$this->pdf->Ln(4);
			$this->pdf->Cell(85, 5, '', 0);
			$this->pdf->SetFont('Arial', 'B', 12);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(40, 5, utf8_decode(''), 0, 0, 'C');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->Cell(70, 4, utf8_decode($branch_office->ciudad_impuestos), 0, 0, 'C');

			$this->pdf->Ln(12);

			$anio = substr($inventory_output->fecha_registro, 0, 4);
			$mes = substr($inventory_output->fecha_registro, 5, 2);
			$dia = substr($inventory_output->fecha_registro, 8, 2);
			$inventory_output_date = $dia . ' de ' . get_month($mes) . ' del ' . $anio;

			// LUGAR Y FECHA , NRO COMPROBANTE
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, 'Lugar y Fecha : ', 'TL');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, utf8_decode($branch_office->ciudad) . ', ' . $inventory_output_date, 'T');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(30, 5, ' ', 'T');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(40, 5, utf8_decode(''), 'TR');
			$this->pdf->Ln(5);

			// GLOSA
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, utf8_decode('Glosa               :'), 'LB');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(170, 5, utf8_decode($inventory_output->observacion), 'BR');
			$this->pdf->Ln(7);

			//  DETALLE DE ITEMS
			$this->pdf->SetMargins(10, 10, 10);
			$this->pdf->SetFont('Arial', 'B', 8);

			// Encabezado de la columna
			$this->pdf->Cell(8, 5, "NRO.", 1, 0, 'C');
			$this->pdf->Cell(27, 5, "CODIGO", 1, 0, 'C');
			$this->pdf->Cell(82, 5, "PRODUCTO", 1, 0, 'C');
			$this->pdf->Cell(20, 5, "P. COMPRA", 1, 0, 'C');
			$this->pdf->Cell(15, 5, "P. VENTA", 1, 0, 'C');
			$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
			$this->pdf->Cell(20, 5, "ALMACEN", 1, 0, 'C');
			$this->pdf->Ln(5);


			//  detalle
			$nro = 1;
			$this->pdf->SetFont('Arial', '', 8);
			//$this->pdf->SetAligns(array('C', 'L', 'R'));
			$cantidad_filas = 0;
			$numero_items = 0;
			$estilo = 'RL';
			$total_detail = 10;

			//Table with 20 rows and 4 columns
			$this->pdf->SetWidths(array(8, 27, 82, 20, 15, 20, 20));
			$this->pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'C', 'C'));


			foreach ($detail_inventory_output as $detail) {
				$cantidad_filas++;
				$productos = $this->product_model->get_product_by_id($detail->producto_id);
				$this->pdf->Row(array(
					utf8_decode($cantidad_filas),
					utf8_decode($productos->codigo),
					utf8_decode($productos->nombre_comercial),
					utf8_decode($detail->precio_costo),
					utf8_decode($detail->precio_venta),
					utf8_decode(number_format($detail->cantidad)),
					utf8_decode($this->warehouse_model->get_warehouse_id($detail->almacen_id)->nombre)
				));
				$nro = $nro + 1;
				$numero_items = $numero_items + 1;
			}

			while ($total_detail - 1 >= $numero_items) {

				$cantidad_filas++;
				$estilo = 'RL';
				if ($nro == 1) {
					$estilo = $estilo . 'T';
				}
				if ($cantidad_filas == $total_detail) {
					$estilo = 'LRB';
				}

				$this->pdf->Cell(8, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(27, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(82, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(20, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(15, 4, '', $estilo, 0, 'R');
				$this->pdf->Cell(20, 4, '', $estilo, 0, 'R');
				$this->pdf->Cell(20, 4, '', $estilo, 0, 'R');
				$this->pdf->Ln(4);
				$numero_items = $numero_items + 1;
			}

			$print_day = date('Y-m-d');
			$print_hour = date('H:i:s');
			$year = substr($print_day, 0, 4);
			$month = substr($print_day, 5, 2);
			$day = substr($print_day, 8, 2);
			$print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

			$this->pdf->Ln(7);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(26, 5, 'Fecha Impresion :', 'TBL', 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(40, 5, $print_date, 'BT', 0, 'L');

			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(30, 5, 'Hora Impresion :', 'BT', 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(30, 5, $print_hour, 'BT', 0, 'L');

			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, 'Usuario :', 'BT', 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(44, 5, get_user_name_in_session(), 'BTR', 0, 'L');

			$this->pdf->Output("Ingreso inventario " . date('Y-m-d') . ".pdf", 'I');
		} else {
			show_404();
		}


	}
	/*Verificar si tiene registrado el producto en inventario*/
	function verify_inventory_by_product($product_id)
	{
		$this->db->select('*')
			->from('inventario')
			->where('producto_id', $product_id)
			->where('estado', ACTIVO)
			->where('almacen_id', 20);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}



	/*Metodo para recuperar el ultimo ingreso de inventario activo*/
	public function last_product_in_inventory($product_id)
	{
		$this->db->select('*')
			->from('inventario')
			->where('producto_id', $product_id)
			->where('almacen_id', 20)
			->where('estado', ACTIVO)
			->order_by('id', 'DESC');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}

	}


	/*Metodo para actualizar el precio del producto y que refleje en cualquier consulta*/
	public function update_price_inventory()
	{
		$this->db->trans_begin();
		$this->db->select('*')
			->from('producto')
			->where('estado', ACTIVO)
			->order_by('id', 'ASC');
		$query = $this->db->get();

		foreach ($query->result() as $row_product) {
			if ($this->verify_inventory_by_product($row_product->id)) {
				$inventory = $this->last_product_in_inventory($row_product->id);
				if (!is_null($inventory)) {
					$this->db->set('precio_compra', $inventory->precio_costo);
					$this->db->set('precio_venta', $inventory->precio_venta);
					$this->db->where('id', $inventory->producto_id);
					$this->db->update('producto');
				}
			} else {
				$this->db->set('precio_compra', 0);
				$this->db->set('precio_venta', 0);
				$this->db->where('id', $row_product->id);
				$this->db->update('producto');
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo "Problemas";
		} else {
			$this->db->trans_commit();
			echo "Datos actualizados correctamente";
		}
	}



	public function mostrar_precio_inventario()
	{
		$this->db->select('*')
			->from('producto')
			->where('estado', ACTIVO)
			->order_by('id', 'ASC');
		$query = $this->db->get();
		echo '<table>';
		echo '<tr>
                    <td>id</td>
                    <td>nombre</td>
                    <td>precio costo producto</td>
                    <td>precio costo inventario</td>
                    <td>precio venta producto</td>
                    <td>precio venta inventario</td>
             </tr>';
		foreach ($query->result() as $row_product) {
			if ($this->verify_inventory_by_product($row_product->id)) {
				$inventory = $this->last_product_in_inventory($row_product->id);
				if (!is_null($inventory)) {
					echo '<tr>';
					if ($row_product->precio_venta == $inventory->precio_venta) {
						echo '<td style="background-color: green"> ' . $row_product->id . '</td>';
						echo '<td style="background-color: green"> ' . $row_product->nombre_comercial . '</td>';
						echo '<td style="background-color: green"> ' . $row_product->precio_compra . '</td>';
						echo '<td style="background-color: green"> ' . $inventory->precio_costo . '</td>';
						echo '<td style="background-color: green"> ' . $row_product->precio_venta . '</td>';
						echo '<td style="background-color: green"> ' . $inventory->precio_venta . '</td>';
					} else {
						echo '<td style="background-color: red"> ' . $row_product->id . '</td>';
						echo '<td style="background-color: red"> ' . $row_product->nombre_comercial . '</td>';
						echo '<td style="background-color: red"> ' . $row_product->precio_compra . '</td>';
						echo '<td style="background-color: red"> ' . $inventory->precio_costo . '</td>';
						echo '<td style="background-color: red"> ' . $row_product->precio_venta . '</td>';
						echo '<td style="background-color: red"> ' . $inventory->precio_venta . '</td>';
					}
					echo '</tr>';


				}
			} else {
				echo '<tr>';
				echo '<td style="background-color: blue"> ' . $row_product->id . '</td>';
				echo '<td style="background-color: blue"> ' . $row_product->nombre_comercial . '</td>';
				echo '<td style="background-color: blue"> ' . $row_product->precio_compra . '</td>';
				echo '<td style="background-color: blue"> 0</td>';
				echo '<td style="background-color: blue"> ' . $row_product->precio_venta . '</td>';
				echo '<td style="background-color: blue"> 0</td>';
				echo '</tr>';
			}
		}
		echo '</table>';
	}

	public function register_product_preci()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->inventory_model->register_product_preci();
        }
        else
        {
            show_404();
        }
	}	
	public function updated_product_stock()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->inventory_model->updated_product_stock();
        }
        else
        {
            show_404();
        }
	}
	public function activate_product()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->inventory_model->activate_product();
        }
        else
        {
            show_404();
        }
	}
	public function disable_product()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->inventory_model->disable_product();
        }
        else
        {
            show_404();
        }
    }
    public function delete_product()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->inventory_model->delete_product();
        }
        else
        {
            show_404();
        }
    }

	public function updated_branch_office_inventory_stock_global()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->inventory_model->updated_branch_office_inventory_stock_global());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
