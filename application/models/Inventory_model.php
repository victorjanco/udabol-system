<?php

/**
 * Created by PhpStorm.
 * User: Gustavo Cisneros
 * Date: 20/07/2017
 * Time: 08:20 PM
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');
class Inventory_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('provider_model');
		$this->load->model('warehouse_model');
		$this->load->model('product_model');
		$this->load->model('inventory_branch_office_model');
	}

	/*Obtener datos del tipo almacen apartir del caja_id*/
	public function find($inventory_id)
	{
		return $this->db->get_where('inventario', array('id' => $inventory_id, 'estado' => ACTIVO))->row();
	}
	/*Obtener datos del tipo almacen apartir del caja_id*/
	public function findED($inventory_id)
	{
		return $this->db->get_where('inventario', array('id' => $inventory_id))->row();
	}

	/*Obtener datos del tipo almacen apartir del caja_id*/
	public function get_inventory_entry_id($inventory_entry_id)
	{
		return $this->db->get_where('ingreso_inventario', array('id' => $inventory_entry_id, 'estado' => ACTIVO))->row();
	}
	/*Obtener datos del tipo almacen apartir del caja_id*/
	public function get_inventory_output_id($inventory_output_id)
	{
		return $this->db->get_where('salida_inventario', array('id' => $inventory_output_id, 'estado' => ACTIVO))->row();
	}
	public function get_inventory_list($params = array())
	{

		/* Se cachea la informacion que se arma en query builder*/
		$this->db->start_cache();
		$this->db->select('inv.id, inv.nro_ingreso_inventario, inv.tipo_ingreso_inventario_id ,inv.nombre, inv.fecha_ingreso, suc.nombre AS nombre_sucursal, tingr.nombre AS nombre_tipo_ingreso')
			->from('ingreso_inventario as inv')
			->join('sucursal as suc', 'suc.id = inv.sucursal_id')
			->join('tipo_ingreso_inventario as tingr', 'tingr.id = inv.tipo_ingreso_inventario_id')
			->where('inv.estado', 1)
			->where('inv.sucursal_id', get_branch_id_in_session());
		// if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
		// 	$this->db->order_by('inv.id', 'desc');
		// }
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
			// if ($params['order'] != '' || $params['column'] != '') {
				$this->db->order_by($params['column'], $params['order']);
			// } else {
				// $this->db->order_by('inv.id', 'desc');
			// }
		} else {
			$this->db->order_by('inv.id', 'desc');
		}

		if (array_key_exists('search', $params)) {
			$this->db->group_start();
			$this->db->like('lower(inv.nombre)', strtolower($params['search']));
			$this->db->group_end();
			$this->db->order_by('inv.fecha_ingreso', 'DESC');
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

	/* Lista de salidas de inventario registrado    */
	public function get_inventory_output_list($params = array())
	{

		/* Se cachea la informacion que se arma en query builder*/
		$this->db->start_cache();
		$this->db->select('sal.id, sal.nro_salida_inventario, sal.tipo_salida_inventario_id, sal.observacion, sal.fecha_registro, tip.nombre AS nombre_tipo_salida, sal.estado')
			->from('salida_inventario sal')
			->join('tipo_salida_inventario tip', 'tip.id = sal.tipo_salida_inventario_id')
			->where('sal.estado', 1)
			->where('sal.sucursal_id', get_branch_id_in_session());
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
			$this->db->order_by('sal.id', 'ASC');
		}

		if (array_key_exists('search', $params)) {

			//$this->db->like('lower(sal.observacion)', strtolower($params['search']));

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

	/*
     * Metodo para registrar agregar una fila al detalle de ingreso comun
     * */
	public function add_detail_common()
	{
		try {
			$response = array(
				'success' => FALSE,
				'messages' => array()
			);
	
			// Reglas de validacion
			$validation_rules = array(
				array(
					'field' => 'producto',
					'label' => '<strong>Producto</strong>',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'precio_venta',
					'label' => '<strong>precio de venta</strong>',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'precio_compra',
					'label' => '<strong>precio de compra</strong>',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'nro_lote',
					'label' => '<strong>nro de lote</strong>',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'cantidad_producto',
					'label' => '<strong>cantidad producto</strong>',
					'rules' => 'trim|required'
				)
			);
	
			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
			$this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');
	
			if ($this->form_validation->run() === true) {
				$product_id = $this->input->post('product_id');
				$product_name = $this->input->post('producto');
				$sale_price = $this->input->post('precio_venta');
				$buy_price = $this->input->post('precio_compra');
				$contador = $this->input->post('contador');
				$quantity = $this->input->post('cantidad_producto');
				$provider_id = $this->input->post('proveedor');
				$warehouse_id = $this->input->post('almacen');
				$code_nro_lote = $this->input->post('nro_lote');
	
				/*obtenemos datos de los select */
				$provider_obj = $this->provider_model->get_provider_by_id_inventory($provider_id);
				$warehouse_obj = $this->warehouse_model->get_warehouse_id($warehouse_id);
				$product_obj = $this->product_model->get_product_entity_by_id($product_id);
	
				$fila = '<tr>';
				$fila .= '<td class="text-center">' . $contador . '</td>';
				$fila .= '<td>' . $product_obj->codigo . '</td>';
				$fila .= '<td><input type="text" value="' . $product_id . '" name="producto_id[]" hidden/><input type="text" value="' . $product_name . '" id="descripcion" name="descripcion[]" hidden/>' . $product_name . '</td>';
				$fila .= '<td align="right"><input  value="' . $sale_price . '"  name="precio_venta[]" hidden/>' . $sale_price . '</td>';
				$fila .= '<td align="right"><input  value="' . $buy_price . '" name="precio_compra[]" hidden/>' . $buy_price . '</td>';
				$fila .= '<td align="right"><input type="text" value="' . $quantity . '" name="cantidad[]" hidden/>' . $quantity . '</td>';
				$fila .= '<td hidden><input type="text" value="' . $warehouse_obj->id . '" name="almacen[]" hidden/><input name="codigo_nro_lote[]" value="' . $code_nro_lote . '" hidden/>' . $warehouse_obj->nombre . '</td>';
				$fila .= '<td><input type="text" value="' . $provider_obj->id . '" name="proveedor[]" hidden/>' . $provider_obj->nombre . '</td>';
				$fila .= '<td class="text-center"><a class="elimina btn-danger btn" >Eliminar</a></td></tr>';
				$response['success'] = true;
				$response['data'] = $fila;
			} else {
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}
			return $response;
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/* Metodo para el registro deun inventario comun o habitual o creados por el usuario*/
	public function register_common()
	{
		try {
			$response = array(
				'success' => FALSE,
				'messages' => array(),
				'login' => FALSE,
				'url_print' => null,
				'inventory' => null
			);
	
			if (verify_session()) {
	
				$validation_rules = array(
					
					array(
						'field' => 'fecha_ingreso',
						'label' => '<strong>fecha de ingreso</strong>',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'nro_comprobante',
						'label' => '<strong>nro. comprobante</strong>',
						'rules' => 'trim|required'
					)
				);
	
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
				$this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');
	
				if ($this->form_validation->run() === true) {
					$number_rows = count($this->input->post('producto_id'));
					$product_array = $this->input->post('producto_id');
					$description_array = $this->input->post('descripcion');
					$sale_price_array = $this->input->post('precio_venta');
					$buy_price_array = $this->input->post('precio_compra');
					$quantity_array = $this->input->post('cantidad');
					$warehouse_array = $this->input->post('almacen');
					$provider_array = $this->input->post('proveedor');
					$code_nro_lote_array = $this->input->post('codigo_nro_lote');
	
					$name = strtoupper($this->input->post('nombre'));
					$date_inventory_entry = $this->input->post('fecha_ingreso');
					$receipt_number_entry = $this->input->post('nro_comprobante');
	
					$tipo_ingreso_inventario_id = $this->input->post('type_inventory_entry_id');
	
					$obj_ingreso_inventario = [];
					$obj_ingreso_inventario["nombre"] = $name;
					$obj_ingreso_inventario["fecha_ingreso"] = $date_inventory_entry;
					$obj_ingreso_inventario["fecha_registro"] = date('Y-m-d H:i:s');
					$obj_ingreso_inventario["fecha_modificacion"] = date('Y-m-d H:i:s');
					$obj_ingreso_inventario["estado"] = ACTIVO;
					$obj_ingreso_inventario["estado_aprobacion"] = ACTIVO;
					$obj_ingreso_inventario["tipo_ingreso_inventario_id"] = $tipo_ingreso_inventario_id;
					$obj_ingreso_inventario["sucursal_id"] = get_branch_id_in_session();
					// $obj_ingreso_inventario["usuario_id"] = get_user_id_in_session();
					$obj_ingreso_inventario["nro_ingreso_inventario"] = $this->last_number_inventory_entry();
					$obj_ingreso_inventario["nro_comprobante"] = $receipt_number_entry;
					$obj_ingreso_inventario["user_created"] = get_user_id_in_session();
					$obj_ingreso_inventario["user_updated"] = get_user_id_in_session();
	
					$this->db->trans_begin();
	
					$this->_insert_inventory_entry($obj_ingreso_inventario);
					$inventory_entry_inserted = $this->_get_inventory_entry($obj_ingreso_inventario);
	
	
					for ($index = 0; $index < $number_rows; $index++) {
						$product = $this->product_model->find($product_array[$index]);
						$obj_inventary["codigo"] = $code_nro_lote_array[$index];
						$obj_inventary["cantidad"] = $quantity_array[$index];
						$obj_inventary["cantidad_ingresada"] = $quantity_array[$index];
						$obj_inventary["precio_compra"] = $buy_price_array[$index];
						$obj_inventary["precio_costo"] = $buy_price_array[$index];
						$obj_inventary["precio_venta"] = $sale_price_array[$index];
						$obj_inventary["fecha_ingreso"] = $date_inventory_entry;
						$obj_inventary["fecha_modificacion"] = date('Y-m-d H:i:s');
						$obj_inventary["estado"] = ACTIVO;
						$obj_inventary["almacen_id"] = $warehouse_array[$index];
						$obj_inventary["ingreso_inventario_id"] = $inventory_entry_inserted->id;
						$obj_inventary["producto_id"] = $product_array[$index];
	
						$this->_insert_inventory($obj_inventary);
						$inventory_inserted = $this->_get_inventory($obj_inventary);
	
						$warehouse_id = $warehouse_array[$index];
						$branch_office_id = get_branch_id_in_session();
						$product_id = $product_array[$index];
						$new_stock = $quantity_array[$index];
						
						if ($this->product_model->exists_branch_office_inventory(get_branch_id_in_session(), $warehouse_id, $product_id) == 0) {
			
							// Registra inventario sucursal
							$data_branch_office_inventory = array(
								'precio_compra' => $buy_price_array[$index],
								'precio_costo' => $buy_price_array[$index],
								'precio_costo_ponderado' =>$buy_price_array[$index],
								'precio_venta' => $sale_price_array[$index],
								'precio_venta_1' => $product->precio_venta_mayor,
								'precio_venta_2' => $sale_price_array[$index],
								'precio_venta_3' => $sale_price_array[$index],
								'porcentaje_precio_venta_3' => 0,
								'stock' => $new_stock,
								'fecha_modificacion' => date('Y-m-d H:i:s'),
								'usuario_id' => get_user_id_in_session(),
								'sucursal_registro_id' =>$branch_office_id,
								'sucursal_id' => $branch_office_id,
								'almacen_id' => $warehouse_id,
								'producto_id' => $product_id,
								'estado' => ACTIVO
							);
							$this->db->insert('inventario_sucursal', $data_branch_office_inventory);	
						}else{
							$this->update_branch_office_inventory($inventory_inserted->id);
							$this->update_preci_branch_office_inventory($inventory_inserted->id);
						}
					}
	
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$response['success'] = FALSE;
					} else {
						$this->db->trans_commit();
						// $this->update_product_in_inventory_branch_office($this->get_detail_inventory($inventory_entry_inserted->id));
	
						$response['success'] = TRUE;
						$response['inventory'] = $inventory_entry_inserted->id;
						$response['url_print'] = 'inventory/print_inventory_entry';
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
	/*Funcion actualizar proveedor para validar los datos de la vista */
    public function register_product_preci()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'cost_price',
                    'label' => 'Precio Costo',
                    'rules' => 'trim|required|numeric'
                ),
                array(
                    'field' => 'sale_price',
                    'label' => 'Precio Venta',
                    'rules' => 'trim|required|numeric'
                ),
				array(
                    'field' => 'sale_price1',
                    'label' => 'Precio Venta Mayorista',
                    'rules' => 'trim|required|numeric'
                ),
            );;

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $inventory_branch_id = $this->input->post('inventory_branch_id');
                $data = array(
                    'precio_costo' => $this->input->post('cost_price'),
                    'precio_venta' => $this->input->post('sale_price'),
                    'precio_venta_1' => $this->input->post('sale_price1')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                $where = array('id' => $inventory_branch_id);
                $this->db->update('inventario_sucursal', $data, $where);

				$inventory_branch_office = $this->inventory_branch_office_model->find($inventory_branch_id);
				
				$data_inventory = array(
                    'precio_costo' => $this->input->post('cost_price'),
                    'precio_venta' => $this->input->post('sale_price'),
                );

				$this->db->where('almacen_id', $inventory_branch_office->almacen_id);
				$this->db->where('producto_id', $inventory_branch_office->producto_id);
				$this->db->where('cantidad > 0');
				$this->db->update('inventario', $data_inventory);


                // Obtener resultado de transacción
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    $response['success'] = TRUE;
                } else {
                    $this->db->trans_rollback();
                    $response['success'] = FALSE;
                }
            } else {
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }

        } else {
            $response['login'] = TRUE;
        }

        echo json_encode($response);
    }
	
	/*Funcion actualizar proveedor para validar los datos de la vista */
	public function updated_product_stock()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'product_id',
                    'label' => 'Producto',
                    'rules' => 'trim|required|numeric'
                ),
                array(
                    'field' => 'warehouse_id',
                    'label' => 'Almacen',
                    'rules' => 'trim|required|numeric'
                )
            );;

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $inventory_branch_id = $this->input->post('id');
                $product_id = $this->input->post('product_id');
                $warehouse_id = $this->input->post('warehouse_id');
                
                // Inicio de transacción
                $this->db->trans_begin();

				$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);
				$data_branch_office_inventory = array(
					'stock' => $new_stock,
					'fecha_modificacion' => date('Y-m-d H:i:s'),
					'sucursal_registro_id' => get_branch_id_in_session(),
				);

                $where = array('id' => $inventory_branch_id);
                $this->db->update('inventario_sucursal', $data_branch_office_inventory, $where);

                // Obtener resultado de transacción
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    $response['success'] = TRUE;
                } else {
                    $this->db->trans_rollback();
                    $response['success'] = FALSE;
                }
            } else {
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }

        } else {
            $response['login'] = TRUE;
        }

        echo json_encode($response);
    }

	public function updated_product_stock_query($warehouse_id, $product_id){
		$this->db->trans_begin();
			$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);
			$data_branch_office_inventory = array(
				'stock' => $new_stock,
				'fecha_modificacion' => date('Y-m-d H:i:s'),
				'sucursal_registro_id' => get_branch_id_in_session(),
			);

			$this->db->where('almacen_id', $warehouse_id);
			$this->db->where('producto_id', $product_id);
			$this->db->update('inventario_sucursal', $data_branch_office_inventory);
		// Obtener resultado de transacción
		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();
			$response['success'] = TRUE;
		} else {
			$this->db->trans_rollback();
			$response['success'] = FALSE;
		}
	}
	
	/* Funcion que retorna el siguiente numero ingreso de inventario por Sucursal*/
	public function last_number_inventory_entry()
	{
		$this->db->select_max('nro_ingreso_inventario');
		$this->db->where('sucursal_id', get_branch_id_in_session());
		$result = $this->db->get('ingreso_inventario');
		if ($result->num_rows() > 0) {
			$query = $result->row();
			return $query->nro_ingreso_inventario + 1;
		} else {
			return 1;
		}
	}

	/* Metodo para el registro de INGRESO inventario sucursal */
	public function insert_inventory_entry_branch_office($data)
	{
		return $this->db->insert('ingreso_inventario_sucursal', $data);
	}

	/* Metodo para el registro de SALIDA inventario sucursal */
	public function insert_inventory_output_branch_office($data)
	{
		return $this->db->insert('salida_inventario_sucursal', $data);
	}

	/* Metodo para el registro deun inventario comun o habitual o creados por el usuario*/
	public function register_inventory_purchase()
	{

		$response = array(
			'success' => FALSE,
			'messages' => array()
		);

		$validation_rules = array(
			array(
				'field' => 'nombre',
				'label' => '<strong>nombre</strong>',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'fecha_ingreso',
				'label' => '<strong>fecha de ingreso</strong>',
				'rules' => 'trim|required'
			)
		);

		$this->form_validation->set_rules($validation_rules);
		$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
		$this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');

		if ($this->form_validation->run() === true) {
			$number_rows = count($this->input->post('producto_id'));
			$purchase_id = $this->input->post('purchase_id');
			$product_array = $this->input->post('producto_id');
			$description_array = $this->input->post('descripcion');
			$sale_price_array = $this->input->post('precio_venta');
			$buy_price_array = $this->input->post('precio_compra');
			$quantity_array = $this->input->post('cantidad');
			$warehouse_array = $this->input->post('almacen');
			$provider_array = $this->input->post('proveedor');
			$code_nro_lote_array = $this->input->post('codigo_nro_lote');
			$detail_buy_id_array = $this->input->post('detalle_compra_id');

			$name = strtoupper($this->input->post('nombre'));
			$date_inventory_entry = $this->input->post('fecha_ingreso');
			$tipo_ingreso_inventario_id = $this->input->post('type_inventory_entry_id');/* tipo de inventario   */


			$obj_ingreso_inventario = [];
			$obj_ingreso_inventario["nombre"] = $name;
			$obj_ingreso_inventario["fecha_ingreso"] = $date_inventory_entry;
			$obj_ingreso_inventario["fecha_registro"] = date('Y-m-d H:i:s');
			$obj_ingreso_inventario["fecha_modificacion"] = date('Y-m-d H:i:s');
			$obj_ingreso_inventario["estado"] = ACTIVO;
			$obj_ingreso_inventario["estado_aprobacion"] = ACTIVO;
			$obj_ingreso_inventario["tipo_ingreso_inventario_id"] = $tipo_ingreso_inventario_id;
			$obj_ingreso_inventario["sucursal_id"] = get_branch_id_in_session();
			$obj_ingreso_inventario["usuario_id"] = get_user_id_in_session();
			$obj_ingreso_inventario["nro_ingreso_inventario"] = $this->last_number_inventory_entry();

			$this->db->trans_begin();

			$this->_insert_inventory_entry($obj_ingreso_inventario);
			$inventory_entry_inserted = $this->_get_inventory_entry($obj_ingreso_inventario);

			for ($index = 0; $index < $number_rows; $index++) {
				$obj_inventary["codigo"] = $code_nro_lote_array[$index];
				$obj_inventary["cantidad"] = $quantity_array[$index];
				$obj_inventary["cantidad_ingresada"] = $quantity_array[$index];
				$obj_inventary["precio_compra"] = $buy_price_array[$index];
				$obj_inventary["precio_costo"] = $buy_price_array[$index];
				$obj_inventary["precio_venta"] = $sale_price_array[$index];
				$obj_inventary["fecha_ingreso"] = $date_inventory_entry;
				$obj_inventary["fecha_modificacion"] = date('Y-m-d H:i:s');
				$obj_inventary["estado"] = ACTIVO;
				$obj_inventary["almacen_id"] = $warehouse_array[$index];
				$obj_inventary["ingreso_inventario_id"] = $inventory_entry_inserted->id;
				$obj_inventary["producto_id"] = $product_array[$index];

				$this->_insert_inventory($obj_inventary);
				$inventory_inserted = $this->_get_inventory($obj_inventary);
				$inventory_id = $inventory_inserted->id;

				/*actualizamos su detalle de compra de ese item    */
				$detail_by_id = $detail_buy_id_array[$index];


				/*obtenemos el detalle de compra    */
				$row_detail = $this->_get_detail_buy($detail_by_id);
				$quantity_total = $row_detail->cantidad_correcta - ($quantity_array[$index] + $row_detail->cantidad_ingresada_inventario);
				if ($quantity_total < 1) {
					$detail_purchase["estado"] = 2;
				}

				$detail_purchase["cantidad_ingresada_inventario"] = $row_detail->cantidad_ingresada_inventario + $quantity_array[$index];
				$this->_update_detail_buy($detail_purchase, $detail_by_id);

				$purchase_entry_product["cantidad"] = $quantity_array[$index];
				$purchase_entry_product["fecha_ingreso"] = date('Y-m-d');
				$purchase_entry_product["estado"] = ACTIVO;
				$purchase_entry_product["compra_id"] = $purchase_id;
				$purchase_entry_product["ingreso_inventario_id"] = $inventory_id;
				$purchase_entry_product["producto_id"] = $product_array[$index];
				$this->_insert_purchase_entry_product($purchase_entry_product);

				/* $item["cantidad_correcta"]-$item["cantidad_ingresada_inventario"]    */

			}

			$this->_update_purchase_enable($purchase_id);


			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$response['success'] = FALSE;
			} else {
				$this->db->trans_commit();
				$this->update_product_in_inventory_branch_office($this->get_detail_inventory($inventory_id));
				$response['success'] = TRUE;
			}

		} else {
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}
		return $response;
	}

	public function register_edit_common()
	{

		$response = array(
			'success' => FALSE,
			'messages' => array()
		);

		$validation_rules = array(
			array(
				'field' => 'nombre',
				'label' => '<strong>nombre</strong>',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'fecha_ingreso',
				'label' => '<strong>fecha de ingreso</strong>',
				'rules' => 'trim|required'
			)
		);

		$this->form_validation->set_rules($validation_rules);
		$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
		$this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');

		if ($this->form_validation->run() === true) {

			$inventory_entry_id = $this->input->post('ingreso_inventario_id');
			$number_rows = count($this->input->post('producto_id'));
			$product_array = $this->input->post('producto_id');
			$description_array = $this->input->post('descripcion');
			$sale_price_array = $this->input->post('precio_venta');
			$buy_price_array = $this->input->post('precio_compra');
			$quantity_array = $this->input->post('cantidad');
			$warehouse_array = $this->input->post('almacen');
			$provider_array = $this->input->post('proveedor');
			$code_nro_lote_array = $this->input->post('codigo_nro_lote');

			$name = strtoupper($this->input->post('nombre'));
			$date_inventory_entry = $this->input->post('fecha_ingreso');
			$tipo_ingreso_inventario_id = $this->input->post('type_inventory_entry_id');


			$obj_ingreso_inventario = [];
			$obj_ingreso_inventario["nombre"] = $name;
			$obj_ingreso_inventario["fecha_ingreso"] = $date_inventory_entry;
			$obj_ingreso_inventario["fecha_modificacion"] = date('Y-m-d');
			$obj_ingreso_inventario["estado"] = ACTIVO;
			$obj_ingreso_inventario["tipo_ingreso_inventario_id"] = $tipo_ingreso_inventario_id;
			$obj_ingreso_inventario["sucursal_id"] = get_branch_id_in_session();
            $obj_ingreso_inventario["user_updated"] = get_user_id_in_session();


			$this->db->trans_begin();
			/*actualizamos el ingreso_inventario*/
			$this->_update_inventory_entry($obj_ingreso_inventario);
			$detail_old_inventory = $this->get_detail_inventory($inventory_entry_id);
			foreach ($detail_old_inventory as $row_old_inventory):

			endforeach;


			for ($index = 0; $index < $number_rows; $index++) {
				$obj_inventary["codigo"] = $code_nro_lote_array[$index];
				$obj_inventary["cantidad"] = $quantity_array[$index];
				$obj_inventary["cantidad_ingresada"] = $quantity_array[$index];
				$obj_inventary["precio_compra"] = $buy_price_array[$index];
				$obj_inventary["precio_costo"] = $buy_price_array[$index];
				$obj_inventary["precio_venta"] = $sale_price_array[$index];
				$obj_inventary["fecha_ingreso"] = $date_inventory_entry;
				$obj_inventary["fecha_modificacion"] = date('Y-m-d H:i:s');
				$obj_inventary["estado"] = ACTIVO;
				$obj_inventary["almacen_id"] = $warehouse_array[$index];
				//$obj_inventary["ingreso_inventario_id"] = $inventory_entry_inserted->id;
				$obj_inventary["producto_id"] = $product_array[$index];

				$this->_insert_inventory($obj_inventary);
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
				$this->update_product_in_inventory_branch_office($this->get_detail_inventory($inventory_entry_id));
			}

		} else {
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}
		return $response;
	}

	/*
     * *Retorna el inventario como entidad o tupla con parametro id
     * */
	public function get_inventory_by_id($inventory_id)
	{
		return $this->db->get_where('ingreso_inventario', array('id' => $inventory_id))->row();
	}
	public function get_detail_inventory($inventory_id)
	{
		// $this->db->where('ingreso_inventario_id', $inventory_id);
		// return $this->db->get('inventario')->result();

		$this->db->select('i.*, p.precio_venta_mayor, p.precio_venta_express, p.precio_venta_laboratorio, p.codigo codigo_producto')
			->from('inventario i, producto p')
			->where('i.producto_id=p.id')
			->where('i.estado',ACTIVO)
			->where('i.ingreso_inventario_id', $inventory_id);
		return $this->db->get()->result();
	}

	public function get_detail_inventory_view($inventory_id)
	{
		$this->db->select('i.*, p.precio_venta_mayor, p.precio_venta_express, p.precio_venta_laboratorio, p.codigo codigo_producto')
			->from('inventario i, producto p')
			->where('i.producto_id=p.id')
			// ->where('i.estado',ACTIVO)
			->where('i.ingreso_inventario_id', $inventory_id);
		return $this->db->get()->result();
	}

	public function get_detail_inventory_transit_entry($inventory_id)
	{
		$this->db->select('i.*,p.nombre_comercial,p.codigo as codigo_producto,p.precio_compra as precio_compra_producto,p.precio_venta as precio_venta_producto, d.almacen_origen_id')
			->from('inventario i, producto p, detalle_transito d')
			->where('i.producto_id=p.id')
			->where('i.detalle_transito_id=d.id');
		$this->db->where('i.ingreso_inventario_id', $inventory_id);
		return $this->db->get()->result();
	}

	/*
     * *Retorna la salida inventario como entidad o tupla con parametro id
     * */
	public function get_inventory_output_by_id($inventory_output_id)
	{
		return $this->db->get_where('salida_inventario', array('id' => $inventory_output_id))->row();
	}

	public function get_detail_inventory_output($inventory_output_id)
	{
		$this->db->where('salida_inventario_id', $inventory_output_id);
		return $this->db->get('detalle_salida_inventario')->result();
		return $this->db->get()->result();
	}
	public function get_detail_inventory_output_disable($inventory_output_id)
	{
		// $this->db->where('salida_inventario_id', $inventory_output_id);
		// return $this->db->get('detalle_salida_inventario')->result();
		$this->db->select('dsi.*, p.nombre_comercial, p.nombre_generico, p.codigo, i.almacen_id, i.producto_id')
			->from('detalle_salida_inventario dsi, inventario i, producto p')
			->where('dsi.inventario_id=i.id')
			->where('i.producto_id=p.id')
			->where('dsi.salida_inventario_id', $inventory_output_id);
		return $this->db->get()->result_array();
		// return $this->db->get()->result();
	}

	public function get_detail_inventory_output_print($inventory_output_id)
	{
		// $this->db->where('salida_inventario_id', $inventory_output_id);
		// return $this->db->get('detalle_salida_inventario')->result();
		$this->db->select('dsi.*, p.nombre_comercial, p.nombre_generico, p.codigo, i.almacen_id, i.producto_id')
			->from('detalle_salida_inventario dsi, inventario i, producto p')
			->where('dsi.inventario_id=i.id')
			->where('i.producto_id=p.id')
			->where('dsi.salida_inventario_id', $inventory_output_id);
		return $this->db->get()->result();
		// return $this->db->get()->result();
	}

	public function get_inventory_new_by_id($inventory_id)
	{
		return $this->db->get_where('inventario', array('id' => $inventory_id))->row();
	}

	/* Listado de compras disponibles para ingresar a inventario    */
	public function get_purchase_list($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('*')
			->from('compra')
			->where('estado', ACTIVO);
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
			$this->db->like('nro_compra', strtolower($params['search']));
//            $this->db->like('lower(nit)', strtolower($params['search']));
//            $this->db->like('lower(contacto)', strtolower($params['search']));
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

	/* Metodo que retorna los productos por tipo y por sucursal*/
	public function get_product_branch_type()
	{
		$branch_office_id = get_branch_office_name_in_session();
		$type_product_id = $this->input->post('product');
	}

	public function _update_purchase_enable($id)
	{
		$this->db->select('*');
		$this->db->from('detalle_compra');
		$this->db->where('compra_id', $id);
		$this->db->where('estado!=', 2);
		$data = $this->db->get();
		if ($data->num_rows() < 1) {
			$this->db->set('estado', 2);
			$this->db->where('id', $id);
			$this->db->update('compra');
		}
	}

	public function verify_stock_available_inventory($branch_office_id, $warehouse_id, $product_id, $quantity)
	{
		$this->db->select('*')
			->from('inventario_stock_general')
			->where('sucursal_id', $branch_office_id)
			->where('almacen_id', $warehouse_id)
			->where('producto_id', $product_id)
			->where('stock >=', $quantity);
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return $response->row()->stock;
		} else {
			return 0;
		}
	}

	/*Metodo que verificar la suma de la cantidad de producto en detalle virtual*/
	public function verify_stock_detail_virtual($branch_office_id, $warehouse_id, $product_id)
	{
		$this->db->select('stock_total')
			->from('detalle_virtual_stock_total')
			->where('sucursal_id', $branch_office_id)
			->where('almacen_id', $warehouse_id)
			->where('producto_id', $product_id);
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return $response->row()->stock_total;
		} else {
			return 0;
		}

	}

	public function verify_stock_detail_virtual_by_branch($branch_office_id, $product_id)
	{
		$this->db->select('SUM(stock_total) AS stock_total')
			->from('detalle_virtual_stock_total')
			->where('sucursal_id', $branch_office_id)
			->where('producto_id', $product_id)
			->group_by('sucursal_id,producto_id');
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return $response->row()->stock_total;
		} else {
			return 0;
		}

	}

	public function register_detail_virtual($data)
	{
		$branch_office_id = $data['branch_office_id'];
		$warehouse_id = $data['warehouse_id'];
		$product_id = $data['product_id'];
		$quantity = $data['quantity'];
		$user_id = get_user_id_in_session();
		$sesion_id = get_session_id();

	}

	/*  Agregar detalle a la salida de inventario   */
	public function add_detail_output()
	{
		try {
			$response = array(
				'success' => FALSE,
				'messages' => array()
			);
	
			// Reglas de validacion
			$validation_rules = array(
				array(
					'field' => 'product_output',
					'label' => '<strong>PRODUCTO</strong>',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'quantity',
					'label' => '<strong>CANTIDAD</strong>',
					'rules' => 'trim|required'
				),
				/*array(
					'field' => 'nro_lote',
					'label' => '<strong>LOTE</strong>',
					'rules' => 'trim|required'
				),*/
				array(
					'field' => 'price_cost',
					'label' => '<strong>PRECIO COSTO</strong>',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'price_sale',
					'label' => '<strong>PRECIO VENTA</strong>',
					'rules' => 'trim|required'
				)
			);
	
	
			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
			$this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');
	
			if ($this->form_validation->run() === true) {
				$inventory_id = $this->input->post('inventory_id');
				$quantity = $this->input->post('quantity');
				$price_cost = $this->input->post('price_cost');
				$price_sale = $this->input->post('price_sale');
	
				/*obtenemos datos de los select */
				$product_obj = $this->get_inventory_product_detail($inventory_id);
	
				$fila = '<tr>';
				$fila .= '<td>' . $product_obj->codigo_producto . '</td>';
				$fila .= '<td><input type="text" value="' . $product_obj->id . '" name="inventory_id[]" hidden/><input type="text" value="' . $product_obj->producto_id . '" name="product_id[]" hidden/>' . $product_obj->nombre_comercial . ' - '. $product_obj->nombre_generico . '</td>';
				$fila .= '<td align="right"><input value="' . $price_cost . '"  name="price_cost[]" hidden/>' . $price_cost . '</td>';
				$fila .= '<td align="right"><input value="' . $price_sale . '" name="price_sale[]" hidden/>' . $price_sale . '</td>';
//				$fila .= '<td align="right"><input type="text" value="' . $product_obj->codigo . '" name="codigo_lote[]" hidden/>' . $product_obj->codigo . '</td>';
				// $fila .= '<td align="right"><input type="text" value="" name="date_validity[]" hidden/>' . $product_obj->fecha_vencimiento . '</td>';
				$fila .= '<td><input type="text" value="' . $quantity . '" name="quantity_ouput[]" hidden/>' . $quantity . '</td>';
				$fila .= '<td><input type="text" value="' . $product_obj->nombre_almacen . '" name="warehouse[]" hidden/>' . $product_obj->nombre_almacen . '</td>';
				$fila .= '<td class="text-center"><a class="elimina btn-danger btn" >Eliminar</a></td></tr>';
				$response['success'] = true;
				$response['data'] = $fila;
			} else {
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}
			return $response;
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/*  Agregar detalle a la salida de inventario array  */
	public function add_detail_output_array()
	{	
		try {
			$response = array(
				'success' => FALSE,
				'messages' => array()
			);
			$number_rows = count($this->input->post('inventory_id'));
			$inventory_id_array = $this->input->post('inventory_id');
			$product_quantity_array = $this->input->post('quantity');
			$price_cost_array = $this->input->post('price_cost');
			$price_sale_array = $this->input->post('price_sale');
			$cantidad = 0;

			$fila = '';
			/*obtenemos datos de los select */
			for ($index = 0; $index < $number_rows; $index++) {
				$inventory_id = $inventory_id_array[$index];
				$quantity = $product_quantity_array[$index];
				$price_cost = $price_cost_array[$index];
				$price_sale = $price_sale_array[$index];
				$product_obj = $this->get_inventory_product_detail($inventory_id);
				$cantidad++;
				if ($quantity > 0) {
					$fila .= '<tr>';
					$fila .= '<td>' . $product_obj->codigo_producto . '</td>';
					$fila .= '<td><input type="text" value="' . $product_obj->id . '" name="inventory_id[]" hidden/>
								<input type="text" value="' . $product_obj->producto_id . '" name="product_id[]" hidden/>
								<input type="text" value="' . $product_obj->nombre_comercial . ' - '. $product_obj->nombre_generico . '" name="product_name[]" hidden/>' . $product_obj->nombre_comercial . ' - '. $product_obj->nombre_generico . '</td>';
					$fila .= '<td align="right"><input value="' . $price_cost . '"  name="price_cost[]" hidden/>' . $price_cost . '</td>';
					$fila .= '<td align="right"><input value="' . $price_sale . '" name="price_sale[]" hidden/>' . $price_sale . '</td>';
					$fila .= '<td align="right"><input type="text" value="' . $product_obj->codigo . '" name="codigo_lote[]" hidden/>' . $product_obj->codigo . '</td>';
					// $fila .= '<td align="right"><input type="text" value="" name="date_validity[]" hidden/>' . $product_obj->fecha_vencimiento . '</td>';
					$fila .= '<td><input type="text" value="' . $quantity . '" name="quantity_ouput[]" hidden/>' . $quantity . '</td>';
					$fila .= '<td><input type="text" value="' . $product_obj->nombre_almacen . '" name="warehouse[]" hidden/>' . $product_obj->nombre_almacen . '</td>';
					$fila .= '<td class="text-center"><a class="elimina btn-danger btn" >Eliminar</a></td></tr>';
				}
			}
			$response['success'] = true;
			$response['data'] = $fila;

			return $response;
		} catch (\Throwable $th) {
			throw $th;
		}

	}

	public function add_transit_detail_output_array()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array()
		);


		$number_rows = count($this->input->post('inventory_id'));
		$inventory_id_array = $this->input->post('inventory_id');
		$product_quantity_array = $this->input->post('quantity');
		$price_cost_array = $this->input->post('price_cost');
		$price_sale_array = $this->input->post('price_sale');
		$cantidad = 0;

		$fila = '';
		/*obtenemos datos de los select */
		for ($index = 0; $index < $number_rows; $index++) {
			$inventory_id = $inventory_id_array[$index];
			$quantity = $product_quantity_array[$index];
			$price_cost = $price_cost_array[$index];
			$price_sale = $price_sale_array[$index];
			$product_obj = $this->get_inventory_product_detail($inventory_id);
			$cantidad++;
			if ($quantity > 0) {
				$fila .= '<tr>';
				$fila .= '<td><input type="text" value="' . $product_obj->codigo_producto . '" name="product_code[]" hidden/>' . $product_obj->codigo_producto . '</td>';
				$fila .= '<td><input type="text" value="' . $product_obj->id . '" name="inventory_id[]" hidden/>
                              <input type="text" value="' . $product_obj->producto_id . '" name="product_id[]" hidden/>
                              <input type="text" value="' . $product_obj->nombre_comercial . '" name="product_name[]" hidden/>' . $product_obj->nombre_comercial . '</td>';
				$fila .= '<td align="right" hidden><input value="' . $price_cost . '"  name="price_cost[]" hidden/>' . $price_cost . '</td>';
				$fila .= '<td align="right" hidden><input value="' . $price_sale . '" name="price_sale[]" hidden/>' . $price_sale . '</td>';
				$fila .= '<td align="right" hidden><input type="text" value="' . $product_obj->codigo . '" name="codigo_lote[]" hidden/>' . $product_obj->codigo . '</td>';
				$fila .= '<td align="right" hidden><input type="text" value="" name="date_validity[]" hidden/>' . $product_obj->fecha_vencimiento . '</td>';
				$fila .= '<td><input type="text" value="' . $quantity . '" name="quantity_ouput[]" hidden/>' . $quantity . '</td>';
				$fila .= '<td hidden><input type="text" value="' . $product_obj->almacen_id . '" name="warehouse[]" hidden/>' . $product_obj->nombre_almacen . '</td>';
				$fila .= '<td class="text-center"><a class="elimina btn-danger btn" >Eliminar</a></td></tr>';
			}
		}
		$response['success'] = true;
		$response['data'] = $fila;

		return $response;
	}

	public function register_inventory_output()
	{
		try {
			$response = array(
				'success' => FALSE,
				'messages' => array(),
				'login' => FALSE
			);
	
			if (verify_session()) {
	
				$validation_rules = array(
					array(
						'field' => 'description',
						'label' => '<strong>Descripcion</strong>',
						'rules' => 'trim|required'
					)
				);
	
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
				$this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');
	
				if ($this->form_validation->run() === true) {
					$number_rows = count($this->input->post('inventory_id'));
					$inventory_array = $this->input->post('inventory_id');
					$price_cost_array = $this->input->post('price_cost');
					$price_sale_array = $this->input->post('price_sale');
					$quantity_ouput_array = $this->input->post('quantity_ouput');
					$branch_office_id = get_branch_id_in_session();
	
					$description = strtoupper($this->input->post('description'));
	
					$today = date('Y-m-d H:i:s');
					$obj_output_inventory = [];
					$obj_output_inventory["fecha_registro"] = $today;
					$obj_output_inventory["fecha_modificacion"] = $today;
					$obj_output_inventory["sincronizado"] = 1;
					$obj_output_inventory["observacion"] = $description;
					$obj_output_inventory["estado"] = ACTIVO;
					$obj_output_inventory["estado_aprobacion"] = ACTIVO;
					$obj_output_inventory["tipo_salida_inventario_id"] = $this->input->post('type_exit_inventory');/*salida de inventario por alguna causa*/
					$obj_output_inventory["sucursal_id"] = get_branch_id_in_session();
					$obj_output_inventory["nro_salida_inventario"] = $this->last_number_inventory_output();
					$obj_output_inventory["user_created"] = get_user_id_in_session();
					$obj_output_inventory["user_updated"] = get_user_id_in_session();
					$this->db->trans_begin();
	
					$this->_insert_inventory_output($obj_output_inventory);
					$inventory_output_inserted = $this->_get_inventory_output($obj_output_inventory);
	
					for ($index = 0; $index < $number_rows; $index++) {
						$data_inventory_db = $this->findED($inventory_array[$index]);

						$obj_detail_output_inventory["cantidad"] = $quantity_ouput_array[$index];
						$obj_detail_output_inventory["cantidad_antigua"] = $data_inventory_db->cantidad;
						$obj_detail_output_inventory["precio_costo"] = $price_cost_array[$index];
						$obj_detail_output_inventory["precio_venta"] = $price_sale_array[$index];
						$obj_detail_output_inventory["observacion"] = $description;
						$obj_detail_output_inventory["salida_inventario_id"] = $inventory_output_inserted->id;
						$obj_detail_output_inventory["inventario_id"] = $inventory_array[$index];
						$this->_insert_detail_inventory_output($obj_detail_output_inventory);
						// $data_inventory_db = $this->get_inventory_product_detail($inventory_array[$index]);
						
						
						if ($quantity_ouput_array[$index] > $data_inventory_db->cantidad) {
							throw new Exception("Inventario menor a lo que quiere retirar");
						}
						$quantity_update = $data_inventory_db->cantidad - $quantity_ouput_array[$index];
						$this->_update_stock_inventory($inventory_array[$index], $quantity_update);
						$this->update_branch_office_inventory($inventory_array[$index]);
					}
	
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$response['success'] = FALSE;
					} else {
						// $this->update_average_price_especific_branch_office();
						$this->db->trans_commit();
						$response['success'] = TRUE;
						$response['inventory_output'] = $inventory_output_inserted->id;
						$response['url_print'] = 'inventory/print_inventory_output';
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

	/* Funcion que retorna el siguiente numero de salida de inventario por Sucursal*/
	public function get_inventory_by_product_id()
	{
		try {
			$product_id = $this->input->post('product_id');
			$branch_office_id = get_branch_id_in_session();
			$warehouse_id = $this->input->post('warehouse_id');
			$this->db->select('*')
				->from('inventario_general as inv')
				->where('inv.sucursal_id', $branch_office_id)
				->where('inv.producto_id', $product_id)
				->where('inv.almacen_id', $warehouse_id)
				->where('inv.estado_producto', ACTIVO)
				->where('inv.estado_inventario', ACTIVO)
				->where('inv.stock>0')
				->order_by('inventario_id', 'ASC');
			return $this->db->get()->result();
		} catch (\Throwable $th) {
			throw $th;
		}
	}

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

	public function get_type_inventory_by_inventory_entry_id($inventory_entry)
	{
		$this->db->select('tip.*');
		$this->db->from('ingreso_inventario ing, tipo_ingreso_inventario tip');
		$this->db->where('ing.tipo_ingreso_inventario_id=tip.id');
		$this->db->where('ing.id', $inventory_entry);
		$this->db->where('tip.estado=1');

		return $this->db->get()->row();
	}

	public function get_user_by_inventory_entry_id($inventory_entry)
	{
		$this->db->select('usu.*');
		$this->db->from('ingreso_inventario ing, usuario usu');
		$this->db->where('ing.user_created=usu.id');
		$this->db->where('ing.id', $inventory_entry);
		$this->db->where('usu.estado=1');

		return $this->db->get()->row();
	}

	public function get_type_inventory_by_inventory_output_id($inventory_output)
	{
		$this->db->select('tip.*');
		$this->db->from('salida_inventario sal, tipo_salida_inventario tip');
		$this->db->where('sal.tipo_salida_inventario_id=tip.id');
		$this->db->where('sal.id', $inventory_output);
		$this->db->where('sal.estado=1');

		return $this->db->get()->row();
	}

	/*  METODOS PRIVADOS  */

	public function _insert_inventory_entry($inventory_entry)
	{
		$this->db->insert('ingreso_inventario', $inventory_entry);
	}

	public function _insert_inventory_output($inventory_output)
	{
		$this->db->insert('salida_inventario', $inventory_output);
	}

	public function _get_inventory_entry($inventory_entry)
	{
		return $this->db->get_where('ingreso_inventario', $inventory_entry)->row();
	}
	public function _get_inventory($inventory)
	{
		return $this->db->get_where('inventario', $inventory)->row();
	}

	public function _get_inventory_output($inventory_output)
	{
		return $this->db->get_where('salida_inventario', $inventory_output)->row();
	}

	public function _insert_inventory($inventory)
	{
		$this->db->insert('inventario', $inventory);
	}

	public function _insert_detail_inventory_output($detail_inventory_output)
	{
		$this->db->insert('detalle_salida_inventario', $detail_inventory_output);
	}

	public function _update_inventory_entry($inventory_entry)
	{
		$this->db->update('ingreso_inventario', $inventory_entry);
	}

	public function _update_detail_buy($detail_purchase, $detail_purchase_id)
	{
		$this->db->where('id', $detail_purchase_id);
		$this->db->update('detalle_compra', $detail_purchase);
	}

	public function _insert_purchase_entry_product($purchase_entry_product)
	{
		$this->db->insert('ingreso_compra_producto', $purchase_entry_product);
	}

	/*  Retorna una fila del detalle de compra */
	public function _get_detail_buy($id)
	{
		return $this->db->get_where('detalle_compra', array('id' => $id))->row();
	}

	public function get_inventory_branch_type_private()
	{
		$data = $this->input->post('name_startsWith');
		$type_product_id = $this->input->post('type_product_id');
		$branch_office_id = get_branch_office_name_in_session();
		$this->db->select('*')
			->from('inventario_general as inv')
			->where('inv.sucursal_id', $branch_office_id)
			->where('inv.tipo_producto_id', $type_product_id)
			->group_start()
			->like('inv.nombre_comercial', $data)
			->or_like('prod.codigo', $data)
			->group_end();
		$result = $this->db->get();
		$data = [];
		foreach ($result->result_array() as $row) {
			$data[$row['nombre_comercial']] = $row['id'] . '/' . $row['nombre_comercial'] . '/' . $row['precio_venta'] . '/' . $row['precio_compra'];
		};
		return $data;
	}

	/*  Retorna un inventario con t odo el detalle de un lote(inventario)*/
	public function get_inventory_product_detail($invetory_id)
	{
		$this->db->select('inv.id, prod.nombre_comercial, prod.nombre_generico, inv.codigo, alm.id as almacen_id, alm.nombre as nombre_almacen, inv.precio_costo, inv.precio_venta, inv.fecha_vencimiento, inv.cantidad,inv.producto_id, prod.codigo as codigo_producto')
			->from('inventario as inv')
			->where('inv.id', $invetory_id)
			->join('producto as prod', 'prod.id = inv.producto_id')
			->join('almacen as alm', 'alm.id = inv.almacen_id');
		return $this->db->get()->row();
	}

	/*  Actualizar producto */
	public function _update_stock_inventory($inventory_id, $quantity)
	{
		$obj_inventory['cantidad'] = $quantity;
		$this->db->where('id', $inventory_id);
		$this->db->update('inventario', $obj_inventory);
	}

	/* Devuelve la suma de la cantidad de detalle de salida por el id de inventario, sino devuelve 0*/
	public function get_detail_inventory_output_by_inventory_id($inventory_id)
	{
		$this->db->select('SUM(cantidad) as cantidad')
			->from('detalle_salida_inventario ')
			->where('inventario_id', $inventory_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->cantidad;
		} else {
			return 0;
		}
	}

	public function disable_inventory_entry_by_id($inventory_entry_id)
	{
		$this->db->trans_begin();

		$this->db->set('estado', 0);
		$this->db->set('user_updated', get_user_id_in_session());
		$this->db->set('fecha_modificacion', date('Y-m-d H:i:s'));
		$this->db->where('id', $inventory_entry_id);
		$this->db->update('ingreso_inventario');

		$detail_inventory_in = $this->get_detail_inventory($inventory_entry_id);

		foreach ($detail_inventory_in as $row) {
			$quantity = 0;
			$inventory_id = $row->id;
			$this->_update_stock_inventory($inventory_id, $quantity);
			$this->update_branch_office_inventory($inventory_id);
		}

		$this->db->set('estado', 0);
		$this->db->where('ingreso_inventario_id', $inventory_entry_id);
		$this->db->update('inventario');

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			// $this->update_product_in_inventory_branch_office($this->get_detail_inventory($inventory_entry_id));
			return true;
		}
	}

	public function disable_inventory_output_by_id($inventory_output_id)
	{
		try {
			$this->db->trans_begin();
			$this->db->set('estado', ANULADO);
			$this->db->where('id', $inventory_output_id);
			$this->db->update('salida_inventario');

			$detail_inventory_output = $this->get_detail_inventory_output($inventory_output_id);
			foreach ($detail_inventory_output as $row) {
				$quantity = $row->cantidad;
				$inventory_id = $row->inventario_id;
				$this->update_stock_specific_by_inventory_id($inventory_id, $quantity);
				$this->update_branch_office_inventory($inventory_id);
			}
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	/*metodo para actualizar el stock apartir de inventario id*/
	public function update_stock_specific_by_inventory_id($inventory_id, $quantity)
	{
		$current_stock = $this->findED($inventory_id)->cantidad;
		$new_stock = $current_stock + $quantity;
		$this->_update_stock_inventory($inventory_id, $new_stock);
	}

	/*Metodo para obtener el inventario especifico para saber todos sus datos*/
	public function get_inventory_specific_by_inventory_id($inventory_id)
	{
		return $this->db->get_where('inventario', array('id' => $inventory_id))->row();
	}

	/* Funcion que devuelve si existe detalle salida inventario por ID (0=EXISTE, 1=NO EXISTE) */
	public function empty_detail_by_inventory_output_id($inventory_output_id)
	{
		$this->db->select('*')
			->from('detalle_salida_inventario ')
			->where('salida_inventario_id', $inventory_output_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return 1; // EXISTE
		} else {
			return 0; // NO EXISTE
		}
	}

	/* Funcion que devuelve si existe detalle ingreso inventario por ID (0=EXISTE, 1=NO EXISTE) */
	public function empty_detail_by_inventory_entry_id($inventory_entry_id)
	{
		$this->db->select('*')
			->from('inventario')
			->where('ingreso_inventario_id', $inventory_entry_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return 1;  // Existe
		} else {
			return 0;   // No Existe
		}
	}


	/* Funcion que retorna el siguiente numero INGRESO de inventario por Sucursal por su sucursal_id */
	public function last_number_inventory_entry_by_sucursal_id($sucursal_id)
	{
		$this->db->select_max('nro_ingreso_inventario');
		$this->db->where('sucursal_id', $sucursal_id);
		$result = $this->db->get('ingreso_inventario');
		if ($result->num_rows() > 0) {
			$query = $result->row();
			return $query->nro_ingreso_inventario + 1;
		} else {
			return 1;
		}
	}

	/* Funcion que retorna el siguiente numero SALIDA de inventario por Sucursal por su sucursal_id */
	public function last_number_inventory_output_by_sucursal_id($sucursal_id)
	{
		$this->db->select_max('nro_salida_inventario');
		$this->db->where('sucursal_id', $sucursal_id);
		$result = $this->db->get('salida_inventario');
		if ($result->num_rows() > 0) {
			$query = $result->row();
			return $query->nro_salida_inventario + 1;
		} else {
			return 1;
		}
	}

	public function update_average_price()
	{

		$this->db->trans_begin();
		$this->db->select('*')
			->from('producto')
			->where('estado', ACTIVO)
			->order_by('id', 'ASC');
		$query = $this->db->get();
		/*echo '<table>';
		echo '<tr>
                    <td>id</td>
                    <td>nombre</td>
                    <td>precio costo producto</td>
                    <td>precio costo ponderado</td>
                    <td>Stock Total</td>
                    <td>Costo total</td>
             </tr>';*/
		foreach ($query->result() as $row_product) {
			/*verificamos si existe registro*/
			if ($this->verify_inventory_by_product_all($row_product->id)) {
				/*sacar el listado de todos los ingresos que tengan stock*/
				$list_inventory = $this->list_product_in_inventory($row_product->id);
				if (!is_null($list_inventory)) {
					$stock_total = floatval(0);
					$cost_total = floatval(0);
					foreach ($list_inventory as $row_products) {
						$stock_total = $stock_total + $row_products->cantidad;
						$subtotal = floatval($row_products->cantidad * $row_products->precio_costo);
						$cost_total = $cost_total + $subtotal;
					}


					/*hacemos el calculos con lo que esta ingresando*/
					$average_price = floatval($cost_total / $stock_total);
					$this->db->set('precio_compra', $average_price);
					$this->db->where('id', $row_product->id);
					$this->db->update('producto');

					$data_branch_office_inventory = array(
						'precio_compra' => $average_price,
						'precio_costo' => $average_price,
						'precio_costo_ponderado' => $average_price
					);


					$this->db->where('producto_id', $row_product->id);
					$this->db->update('inventario_sucursal', $data_branch_office_inventory);


					/*echo '<tr>';

					echo '<td style="background-color: green"> ' . $row_product->id . '</td>';
					echo '<td style="background-color: green"> ' . $row_product->nombre_comercial . '</td>';
					echo '<td style="background-color: green"> ' . $row_product->precio_compra . '</td>';
					echo '<td style="background-color: green"> ' . $average_price . '</td>';
					echo '<td style="background-color: green"> ' . $stock_total . '</td>';
					echo '<td style="background-color: green"> ' . $cost_total . '</td>';


					echo '</tr>';*/
				}
			}


		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
//			echo "Problemas";
		} else {
			$this->db->trans_commit();
			/*echo "Datos actualizados correctamente prueba ".floatval(1690.95 / 5);*/
		}
	}

	public function update_product_in_inventory_other_branch_office($list_product, $branch_office_id)
	{
		$this->db->trans_begin();
		foreach ($list_product as $row_product) {
			/*verificamos si existe registro*/
			// $list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id($branch_office_id);
			// foreach ($list_warehouse as $warehouse) {
				// $warehouse_id = $warehouse->id;
				$warehouse_id = $row_product->almacen_id;
				$product_id = $row_product->producto_id;
				$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);

				if ($this->product_model->exists_branch_office_inventory($branch_office_id, $warehouse_id, $product_id) == 0) { // no existe
					// Registra inventario sucursal
					$data_branch_office_inventory = array(
						'precio_compra' => $row_product->precio_compra,
						'precio_costo' => $row_product->precio_compra,
						'precio_costo_ponderado' => $row_product->precio_costo,
						'precio_venta' => $row_product->precio_venta,
						'precio_venta_1' => $row_product->precio_venta_mayor,
						'precio_venta_2' => $row_product->precio_venta,
						'precio_venta_3' => $row_product->precio_venta,
						'stock' => $new_stock,
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'usuario_id' => get_user_id_in_session(),
						'sucursal_registro_id' => get_branch_id_in_session(),
						'sucursal_id' => $branch_office_id,
						'almacen_id' => $warehouse_id,
						'producto_id' => $product_id,
						'estado' => ACTIVO
					);

					$this->db->insert('inventario_sucursal', $data_branch_office_inventory);
				}else if ($new_stock > 0) { //existe
					$list_inventory = $this->list_product_in_inventory_specific($warehouse_id, $product_id);
					if (!is_null($list_inventory)) {
						$stock_total = floatval(0);
						$cost_total = floatval(0);
						$price_cost = floatval(0);
						$price_purchase = floatval(0);
						$price_sale = floatval(0);
						foreach ($list_inventory as $row_products) {
							$stock_total = $stock_total + $row_products->cantidad;
							$subtotal = floatval($row_products->cantidad * $row_products->precio_costo);
							$cost_total = $cost_total + $subtotal;
							$price_cost = floatval($row_products->precio_costo);
							$price_purchase = floatval($row_products->precio_compra);
							$price_sale = floatval($row_products->precio_venta);
							$price_sale_mayorista = floatval($row_products->precio_venta_mayor);
						}

						/*hacemos el calculos con lo que esta ingresando*/
						$average_price = floatval($cost_total / $stock_total);

						$data_branch_office_inventory = array(
							'precio_compra' => $price_purchase,
							'precio_costo' => $price_cost,
							'precio_costo_ponderado' => $average_price,
							'precio_venta' => $price_sale,
							'precio_venta_1' => $price_sale_mayorista,
							'precio_venta_2' => $price_sale,
							'precio_venta_3' => $price_sale,
							'stock' => $new_stock,
							'fecha_modificacion' => date('Y-m-d H:i:s'),
							'usuario_id' => get_user_id_in_session(),
							'sucursal_registro_id' => get_branch_id_in_session(),
							'sucursal_id' => $branch_office_id
						);
						$this->db->where('sucursal_id', $branch_office_id);
						$this->db->where('almacen_id', $warehouse_id);
						$this->db->where('producto_id', $product_id);
						$this->db->update('inventario_sucursal', $data_branch_office_inventory);
					}
				} else {
					$data_branch_office_inventory = array(
						'stock' => $new_stock,
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'usuario_id' => get_user_id_in_session(),
						'sucursal_registro_id' => get_branch_id_in_session(),
						'sucursal_id' => $branch_office_id
					);

					$this->db->where('sucursal_id', $branch_office_id);
					$this->db->where('almacen_id', $warehouse_id);
					$this->db->where('producto_id', $product_id);
					$this->db->update('inventario_sucursal', $data_branch_office_inventory);
				}
			// }

		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

		}
	}

	public function update_product_in_inventory_other_branch_office_old($list_product, $branch_office_id)
	{
		$this->db->trans_begin();
		foreach ($list_product as $row_product) {
			/*verificamos si existe registro*/
			$list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id($branch_office_id);
			foreach ($list_warehouse as $warehouse) {
				$warehouse_id = $warehouse->id;
				// $warehouse_id = $row_product->almacen_id;
				$product_id = $row_product->producto_id;
				$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);

				if ($this->product_model->exists_branch_office_inventory($branch_office_id, $warehouse_id, $product_id) == 0) { // no existe
					// Registra inventario sucursal
					// $data_branch_office_inventory = array(
					// 	'precio_compra' => $row_product->precio_compra,
					// 	'precio_costo' => $row_product->precio_compra,
					// 	'precio_costo_ponderado' => $row_product->precio_costo,
					// 	'precio_venta' => $row_product->precio_venta,
					// 	'precio_venta_1' => $row_product->precio_venta_mayor,
					// 	'precio_venta_2' => $row_product->precio_venta,
					// 	'precio_venta_3' => $row_product->precio_venta,
					// 	'stock' => $new_stock,
					// 	'fecha_modificacion' => date('Y-m-d H:i:s'),
					// 	'usuario_id' => get_user_id_in_session(),
					// 	'sucursal_registro_id' => get_branch_id_in_session(),
					// 	'sucursal_id' => $branch_office_id,
					// 	'almacen_id' => $warehouse_id,
					// 	'producto_id' => $product_id,
					// 	'estado' => ACTIVO
					// );

					// $this->db->insert('inventario_sucursal', $data_branch_office_inventory);
				}else if ($new_stock > 0) { //existe
					$list_inventory = $this->list_product_in_inventory_specific($warehouse_id, $product_id);
					if (!is_null($list_inventory)) {
						$stock_total = floatval(0);
						$cost_total = floatval(0);
						$price_cost = floatval(0);
						$price_purchase = floatval(0);
						$price_sale = floatval(0);
						foreach ($list_inventory as $row_products) {
							$stock_total = $stock_total + $row_products->cantidad;
							$subtotal = floatval($row_products->cantidad * $row_products->precio_costo);
							$cost_total = $cost_total + $subtotal;
							$price_cost = floatval($row_products->precio_costo);
							$price_purchase = floatval($row_products->precio_compra);
							$price_sale = floatval($row_products->precio_venta);
							$price_sale_mayorista = floatval($row_products->precio_venta_mayor);
						}

						/*hacemos el calculos con lo que esta ingresando*/
						$average_price = floatval($cost_total / $stock_total);

						$data_branch_office_inventory = array(
							'precio_compra' => $price_purchase,
							'precio_costo' => $price_cost,
							'precio_costo_ponderado' => $average_price,
							'precio_venta' => $price_sale,
							'precio_venta_1' => $price_sale_mayorista,
							'precio_venta_2' => $price_sale,
							'precio_venta_3' => $price_sale,
							'stock' => $new_stock,
							'fecha_modificacion' => date('Y-m-d H:i:s'),
							'usuario_id' => get_user_id_in_session(),
							'sucursal_registro_id' => get_branch_id_in_session(),
							'sucursal_id' => $branch_office_id
						);
						$this->db->where('sucursal_id', $branch_office_id);
						$this->db->where('almacen_id', $warehouse_id);
						$this->db->where('producto_id', $product_id);
						$this->db->update('inventario_sucursal', $data_branch_office_inventory);
					}
				} else {
					$data_branch_office_inventory = array(
						'stock' => $new_stock,
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'usuario_id' => get_user_id_in_session(),
						'sucursal_registro_id' => get_branch_id_in_session(),
						'sucursal_id' => $branch_office_id
					);

					$this->db->where('sucursal_id', $branch_office_id);
					$this->db->where('almacen_id', $warehouse_id);
					$this->db->where('producto_id', $product_id);
					$this->db->update('inventario_sucursal', $data_branch_office_inventory);
				}
			}

		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

		}
	}

	public function update_product_in_inventory_other_branch_office_disable($list_product, $branch_office_id)
	{
		$this->db->trans_begin();
		foreach ($list_product as $row_product) {
			/*verificamos si existe registro*/
			$list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id($branch_office_id);
			foreach ($list_warehouse as $warehouse) {
				$warehouse_id = $warehouse->id;
				// $warehouse_id = $row_product->almacen_id;
				$product_id = $row_product->producto_id;
				$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);

				if ($this->product_model->exists_branch_office_inventory($branch_office_id, $warehouse_id, $product_id) == 0) { // no existe
					// Registra inventario sucursal
					// $data_branch_office_inventory = array(
					// 	'precio_compra' => $row_product->precio_compra,
					// 	'precio_costo' => $row_product->precio_compra,
					// 	'precio_costo_ponderado' => $row_product->precio_costo,
					// 	'precio_venta' => $row_product->precio_venta,
					// 	'precio_venta_1' => $row_product->precio_venta_mayor,
					// 	'precio_venta_2' => $row_product->precio_venta,
					// 	'precio_venta_3' => $row_product->precio_venta,
					// 	'stock' => $new_stock,
					// 	'fecha_modificacion' => date('Y-m-d H:i:s'),
					// 	'usuario_id' => get_user_id_in_session(),
					// 	'sucursal_registro_id' => get_branch_id_in_session(),
					// 	'sucursal_id' => $branch_office_id,
					// 	'almacen_id' => $warehouse_id,
					// 	'producto_id' => $product_id,
					// 	'estado' => ACTIVO
					// );

					// $this->db->insert('inventario_sucursal', $data_branch_office_inventory);
				}else if ($new_stock > 0) { //existe
					$list_inventory = $this->list_product_in_inventory_specific($warehouse_id, $product_id);
					if (!is_null($list_inventory)) {
						$stock_total = floatval(0);
						$cost_total = floatval(0);
						$price_cost = floatval(0);
						$price_purchase = floatval(0);
						$price_sale = floatval(0);
						foreach ($list_inventory as $row_products) {
							$stock_total = $stock_total + $row_products->cantidad;
							$subtotal = floatval($row_products->cantidad * $row_products->precio_costo);
							$cost_total = $cost_total + $subtotal;
							$price_cost = floatval($row_products->precio_costo);
							$price_purchase = floatval($row_products->precio_costo);
							$price_sale = floatval($row_products->precio_venta);
							// $price_sale_mayorista = floatval($row_products->precio_venta_mayor);
						}

						/*hacemos el calculos con lo que esta ingresando*/
						$average_price = floatval($cost_total / $stock_total);

						$data_branch_office_inventory = array(
							'precio_compra' => $price_purchase,
							'precio_costo' => $price_cost,
							'precio_costo_ponderado' => $average_price,
							'precio_venta' => $price_sale,
							// 'precio_venta_1' => $price_sale_mayorista,
							'precio_venta_2' => $price_sale,
							'precio_venta_3' => $price_sale,
							'stock' => $new_stock,
							'fecha_modificacion' => date('Y-m-d H:i:s'),
							'usuario_id' => get_user_id_in_session(),
							'sucursal_registro_id' => get_branch_id_in_session(),
							'sucursal_id' => $branch_office_id
						);
						$this->db->where('sucursal_id', $branch_office_id);
						$this->db->where('almacen_id', $warehouse_id);
						$this->db->where('producto_id', $product_id);
						$this->db->update('inventario_sucursal', $data_branch_office_inventory);
					}
				} else {
					$data_branch_office_inventory = array(
						'stock' => $new_stock,
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'usuario_id' => get_user_id_in_session(),
						'sucursal_registro_id' => get_branch_id_in_session(),
						'sucursal_id' => $branch_office_id
					);

					$this->db->where('sucursal_id', $branch_office_id);
					$this->db->where('almacen_id', $warehouse_id);
					$this->db->where('producto_id', $product_id);
					$this->db->update('inventario_sucursal', $data_branch_office_inventory);
				}
			}

		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

		}
	}

	public function register_in_inventory_warehouse(){
		$this->db->trans_begin();
		$date='2020-11-03';
		$list_product=$this->list_inventory_by_date($date);
		foreach ($list_product as $row_product) {
			/*verificamos si existe registro*/
				$warehouse_id = $row_product->almacen_id;
				$branch_office_id = $row_product->sucursal_id;
				$product_id = $row_product->producto_id;
				$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);
				
				if ($this->product_model->exists_branch_office_inventory(get_branch_id_in_session(), $warehouse_id, $product_id) == 0) {
	
					// Registra inventario sucursal
					$data_branch_office_inventory = array(
						'precio_compra' => $row_product->precio_costo,
						'precio_costo' => $row_product->precio_costo,
						'precio_costo_ponderado' => $row_product->precio_costo,
						'precio_venta' => $row_product->precio_venta,
						'precio_venta_1' => $row_product->precio_venta,
						'precio_venta_2' => $row_product->precio_venta,
						'precio_venta_3' => $row_product->precio_venta,
						'porcentaje_precio_venta_3' => 0,
						'stock' => $new_stock,
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'usuario_id' => get_user_id_in_session(),
						'sucursal_registro_id' =>$branch_office_id,
						'sucursal_id' => $branch_office_id,
						'almacen_id' => $warehouse_id,
						'producto_id' => $product_id,
						'estado' => ACTIVO
					);
					$this->db->insert('inventario_sucursal', $data_branch_office_inventory);	
				}
			}
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

		}

	}
	public function update_product_in_inventory_branch_office($list_product)
	{
		$this->db->trans_begin();
		foreach ($list_product as $row_product) {
			/*verificamos si existe registro*/
			$list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
			foreach ($list_warehouse as $warehouse) {
				$warehouse_id = $warehouse->id;
				$product_id = $row_product->producto_id;
				$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);

				if ($this->product_model->exists_branch_office_inventory(get_branch_id_in_session(), $warehouse_id, $product_id) == 0) {
					// if($this->product_by_warehouse_id($warehouse_id, $product_id)==0){
					// // Registra inventario sucursal
					// $data_branch_office_inventory = array(
					// 	'precio_compra' => $row_product->precio_costo,
					// 	'precio_costo' => $row_product->precio_costo,
					// 	'precio_costo_ponderado' => $row_product->precio_costo,
					// 	'precio_venta' => $row_product->precio_venta,
					// 	'precio_venta_1' => $row_product->precio_venta_mayor,
					// 	'precio_venta_2' => $row_product->precio_venta,
					// 	'precio_venta_3' => $row_product->precio_venta,
					// 	'porcentaje_precio_venta_3' => 0.1,
					// 	'stock' => $new_stock,
					// 	'fecha_modificacion' => date('Y-m-d H:i:s'),
					// 	'usuario_id' => get_user_id_in_session(),
					// 	'sucursal_registro_id' => get_branch_id_in_session(),
					// 	'sucursal_id' => get_branch_id_in_session(),
					// 	'almacen_id' => $warehouse_id,
					// 	'producto_id' => $product_id,
					// );

					// $this->db->insert('inventario_sucursal', $data_branch_office_inventory);

					// }
					
				}else if ($new_stock > 0) {
					$list_inventory = $this->list_product_in_inventory_specific($warehouse_id, $product_id);
					if (!is_null($list_inventory)) {
						$stock_total = floatval(0);
						$cost_total = floatval(0);
						$price_cost = floatval(0);
						$price_purchase = floatval(0);
						$price_sale = floatval(0);
						foreach ($list_inventory as $row_products) {
							$stock_total = $stock_total + $row_products->cantidad;
							$subtotal = floatval($row_products->cantidad * $row_products->precio_costo);
							$cost_total = $cost_total + $subtotal;
							$price_cost = floatval($row_products->precio_costo);
							$price_purchase = floatval($row_products->precio_compra);
							$price_sale = floatval($row_products->precio_venta);
							$price_sale_mayorista = floatval($row_products->precio_venta_mayor);
						}

						/*hacemos el calculos con lo que esta ingresando*/
						// $average_price = floatval($cost_total / $stock_total);
						$average_price = floatval($price_sale);

						// Actualiza inventario sucursal

						$data_branch_office_inventory = array(
							'precio_compra' => $price_purchase,
							'precio_costo' => $price_cost,
							'precio_costo_ponderado' => $average_price,
							'precio_venta' => $price_sale,
							'precio_venta_1' => $price_sale_mayorista,
							'precio_venta_2' => $price_sale,
							'precio_venta_3' => $price_sale,
							'porcentaje_precio_venta_3' => 0.1,
							'stock' => $stock_total,
							'fecha_modificacion' => date('Y-m-d H:i:s'),
							'usuario_id' => get_user_id_in_session(),
							'sucursal_registro_id' => get_branch_id_in_session(),
							'sucursal_id' => get_branch_id_in_session()
						);

						$this->db->where('sucursal_id', get_branch_id_in_session());
						$this->db->where('almacen_id', $warehouse_id);
						$this->db->where('producto_id', $product_id);
						$this->db->update('inventario_sucursal', $data_branch_office_inventory);
					}
				} else {
					$data_branch_office_inventory = array(
						'stock' => $new_stock,
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'usuario_id' => get_user_id_in_session(),
						'sucursal_registro_id' => get_branch_id_in_session(),
						'sucursal_id' => get_branch_id_in_session()
					);


					$this->db->where('sucursal_id', get_branch_id_in_session());
					$this->db->where('almacen_id', $warehouse_id);
					$this->db->where('producto_id', $product_id);
					$this->db->update('inventario_sucursal', $data_branch_office_inventory);
				}
			}

		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

		}
	}

	public function update_product_in_inventory_branch_office_for_sale($list_product)
	{
		$this->db->trans_begin();
		foreach ($list_product as $row_product) {
			/*verificamos si existe registro*/
			$list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
			foreach ($list_warehouse as $warehouse) {
				$warehouse_id = $warehouse->id;
				$product_id = $row_product->producto_id;
				$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);

				if ($this->product_model->exists_branch_office_inventory(get_branch_id_in_session(), $warehouse_id, $product_id) == 0) {// no existe
					// Registra inventario sucursal
					// $data_branch_office_inventory = array(
					// 	'precio_compra' => $row_product->precio_costo,
					// 	'precio_costo' => $row_product->precio_costo,
					// 	'precio_costo_ponderado' => $row_product->precio_costo,
					// 	'precio_venta' => $row_product->precio_venta,
					// 	// 'precio_venta_1' => $row_product->precio_venta_mayor,
					// 	// 'precio_venta_2' => $row_product->precio_venta,
					// 	// 'precio_venta_3' => $row_product->precio_venta,
					// 	'porcentaje_precio_venta_3' => 0.1,
					// 	'stock' => $new_stock,
					// 	'fecha_modificacion' => date('Y-m-d H:i:s'),
					// 	'usuario_id' => get_user_id_in_session(),
					// 	'sucursal_registro_id' => get_branch_id_in_session(),
					// 	'sucursal_id' => get_branch_id_in_session(),
					// 	'almacen_id' => $warehouse_id,
					// 	'producto_id' => $product_id,
					// );

					// $this->db->insert('inventario_sucursal', $data_branch_office_inventory);
				}else if ($new_stock > 0) {
					$list_inventory = $this->list_product_in_inventory_specific($warehouse_id, $product_id);
					if (!is_null($list_inventory)) {
						$stock_total = floatval(0);
						$cost_total = floatval(0);
						$price_cost = floatval(0);
						$price_purchase = floatval(0);
						$price_sale = floatval(0);
						foreach ($list_inventory as $row_products) {
							$stock_total = $stock_total + $row_products->cantidad;
							$subtotal = floatval($row_products->cantidad * $row_products->precio_costo);
							$cost_total = $cost_total + $subtotal;
							$price_cost = floatval($row_products->precio_costo);
							$price_purchase = floatval($row_products->precio_compra);
							$price_sale = floatval($row_products->precio_venta);
							// /$price_sale_mayorista = floatval($row_products->precio_venta_mayor);
						}

						/*hacemos el calculos con lo que esta ingresando*/
						$average_price = floatval($cost_total / $stock_total);

						// Actualiza inventario sucursal

						$data_branch_office_inventory = array(
							'precio_compra' => $price_purchase,
							'precio_costo' => $price_cost,
							'precio_costo_ponderado' => $average_price,
							'precio_venta' => $price_sale,
							// 'precio_venta_1' => $price_sale_mayorista,
							// 'precio_venta_2' => $price_sale,
							// 'precio_venta_3' => $price_sale,
							'porcentaje_precio_venta_3' => 0.1,
							'stock' => $stock_total,
							'fecha_modificacion' => date('Y-m-d H:i:s'),
							'usuario_id' => get_user_id_in_session(),
							'sucursal_registro_id' => get_branch_id_in_session(),
							'sucursal_id' => get_branch_id_in_session()
						);


						$this->db->where('sucursal_id', get_branch_id_in_session());
						$this->db->where('almacen_id', $warehouse_id);
						$this->db->where('producto_id', $product_id);
						$this->db->update('inventario_sucursal', $data_branch_office_inventory);
					}
				} else {
					$data_branch_office_inventory = array(
						'stock' => $new_stock,
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'usuario_id' => get_user_id_in_session(),
						'sucursal_registro_id' => get_branch_id_in_session(),
						'sucursal_id' => get_branch_id_in_session()
					);

					$this->db->where('sucursal_id', get_branch_id_in_session());
					$this->db->where('almacen_id', $warehouse_id);
					$this->db->where('producto_id', $product_id);
					$this->db->update('inventario_sucursal', $data_branch_office_inventory);
				}
			}

		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

		}
	}


	public function update_average_price_especific_branch_office()
	{

		$this->db->trans_begin();
		$this->db->select('*')
			->from('producto')
			->where('estado', ACTIVO)
			->order_by('id', 'ASC');
		$query = $this->db->get();

		foreach ($query->result() as $row_product) {
			/*verificamos si existe registro*/
			$list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
			foreach ($list_warehouse as $warehouse) {
				$warehouse_id = $warehouse->id;
				$product_id = $row_product->id;
				$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);

				if ($this->product_model->exists_branch_office_inventory(get_branch_id_in_session(), $warehouse_id, $product_id) == 0) {
					// Registra inventario sucursal
					// $data_branch_office_inventory = array(
					// 	'precio_compra' => $row_product->precio_compra,
					// 	'precio_costo' => $row_product->precio_compra,
					// 	'precio_costo_ponderado' => $row_product->precio_ponderado,
					// 	'precio_venta' => $row_product->precio_venta,
					// 	'precio_venta_1' => $row_product->precio_venta_mayor,
					// 	'precio_venta_2' => $row_product->precio_venta,
					// 	'precio_venta_3' => $row_product->precio_venta,
					// 	'stock' => $new_stock,
					// 	'fecha_modificacion' => date('Y-m-d H:i:s'),
					// 	'usuario_id' => get_user_id_in_session(),
					// 	'sucursal_registro_id' => get_branch_id_in_session(),
					// 	'sucursal_id' => get_branch_id_in_session(),
					// 	'almacen_id' => $warehouse_id,
					// 	'producto_id' => $product_id,
					// );

					// $this->db->insert('inventario_sucursal', $data_branch_office_inventory);


				}else if ($new_stock>0){
					$list_inventory = $this->list_product_in_inventory_specific($warehouse_id, $product_id);
					if (!is_null($list_inventory)) {
						$stock_total = floatval(0);
						$cost_total = floatval(0);
						$price_cost = floatval(0);
						$price_purchase = floatval(0);
						$price_sale = floatval(0);
						foreach ($list_inventory as $row_products) {
							$stock_total = $stock_total + $row_products->cantidad;
							$subtotal = floatval($row_products->cantidad * $row_products->precio_costo);
							$cost_total = $cost_total + $subtotal;
							$price_cost = floatval($row_products->precio_costo);
							$price_purchase = floatval($row_products->precio_compra);
							$price_sale = floatval($row_products->precio_venta);
							$price_sale_mayorista = floatval($row_products->precio_venta_mayor);
						}

						/*hacemos el calculos con lo que esta ingresando*/
						$average_price = floatval($cost_total / $stock_total);

						// Actualiza inventario sucursal
							$data_branch_office_inventory = array(
								'precio_compra' => $price_purchase,
								'precio_costo' => $price_cost,
								'precio_costo_ponderado' => $average_price,
								'precio_venta' => $price_sale,
								'precio_venta_1' => $price_sale_mayorista,
								'precio_venta_2' => $price_sale,
								'precio_venta_3' => $price_sale,
								'stock' => $new_stock,
								'fecha_modificacion' => date('Y-m-d H:i:s'),
								'usuario_id' => get_user_id_in_session(),
								'sucursal_registro_id' => get_branch_id_in_session(),
								'sucursal_id' => get_branch_id_in_session()
							);



						$this->db->where('sucursal_id', get_branch_id_in_session());
						$this->db->where('almacen_id', $warehouse_id);
						$this->db->where('producto_id', $product_id);
						$this->db->update('inventario_sucursal', $data_branch_office_inventory);
					}
				}else {
					$data_branch_office_inventory = array(
						'stock' => $new_stock,
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'usuario_id' => get_user_id_in_session(),
						'sucursal_registro_id' => get_branch_id_in_session(),
						'sucursal_id' => get_branch_id_in_session()
					);


					$this->db->where('sucursal_id', get_branch_id_in_session());
					$this->db->where('almacen_id', $warehouse_id);
					$this->db->where('producto_id', $product_id);
					$this->db->update('inventario_sucursal', $data_branch_office_inventory);
				}
			}

		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			// echo "Problemas";
		} else {
			$this->db->trans_commit();
			/*echo "Datos actualizados correctamente prueba ".floatval(1690.95 / 5);*/
		}
	}
	
	public function update_branch_office_inventory($inventory_id)
	{	
		$inventory = $this->findED($inventory_id);
		$new_stock = $this->stock_by_warehouse_id($inventory->almacen_id, $inventory->producto_id);
		$data_branch_office_inventory = array(
			'stock' => $new_stock,
			'fecha_modificacion' => date('Y-m-d H:i:s'),
			'usuario_id' => get_user_id_in_session(),
			'sucursal_registro_id' => get_branch_id_in_session(),
		);

		// $this->db->where('sucursal_id', $branch_office_id);
		$this->db->where('almacen_id', $inventory->almacen_id);
		$this->db->where('producto_id', $inventory->producto_id);
		$this->db->update('inventario_sucursal', $data_branch_office_inventory);
	}

	public function update_preci_branch_office_inventory($inventory_id)
	{	
		try {
			$inventory = $this->findED($inventory_id);
			$product = $this->product_model->find($inventory->producto_id);
			// var_dump($inventory);
			// $new_stock = $this->stock_by_warehouse_id($inventory->almacen_id, $inventory->producto_id);
			$data_branch_office_inventory = array(
				'precio_compra' => $inventory->precio_compra,
				'precio_costo' => $inventory->precio_costo,
				'precio_venta' => $inventory->precio_venta,
				// 'precio_venta_1' => $product->precio_venta_mayor,
				'fecha_modificacion' => date('Y-m-d H:i:s'),
				'usuario_id' => get_user_id_in_session(),
				'sucursal_registro_id' => get_branch_id_in_session(),
			);
			$this->db->trans_begin();
			
			$this->db->where('almacen_id', $inventory->almacen_id);
			$this->db->where('producto_id', $inventory->producto_id);
			$this->db->update('inventario_sucursal', $data_branch_office_inventory);

			$data_inventory = array(
				'precio_costo' => $inventory->precio_costo,
				'precio_venta' => $inventory->precio_venta,
			);

			$this->db->where('almacen_id', $inventory->almacen_id);
			$this->db->where('producto_id', $inventory->producto_id);
			$this->db->where('cantidad > 0');
			$this->db->update('inventario', $data_inventory);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				// echo "Problemas";
			} else {
				$this->db->trans_commit();
				/*echo "Datos actualizados correctamente prueba ".floatval(1690.95 / 5);*/
			}
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	public function update_average_price_especific_branch_office1($array_product)
	{

		$this->db->trans_begin();
		$this->db->select('*')
			->from('producto')
			->where('estado', ACTIVO);
			if(sizeof($array_product) > 0){
				$this->db->where_in('id', $array_product);
			}
			$this->db->order_by('id', 'ASC');
			$query = $this->db->get();

			if(sizeof($array_product) > 0){
				foreach ($query->result() as $row_product) {
					/*verificamos si existe registro*/
					$list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
					foreach ($list_warehouse as $warehouse) {
						$warehouse_id = $warehouse->id;
						$product_id = $row_product->id;
						$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);

						if ($this->product_model->exists_branch_office_inventory(get_branch_id_in_session(), $warehouse_id, $product_id) == 0) {
							// Registra inventario sucursal
							// $data_branch_office_inventory = array(
							// 	'precio_compra' => $row_product->precio_compra,
							// 	'precio_costo' => $row_product->precio_compra,
							// 	'precio_costo_ponderado' => $row_product->precio_ponderado,
							// 	'precio_venta' => $row_product->precio_venta,
							// 	'precio_venta_1' => $row_product->precio_venta_mayor,
							// 	'precio_venta_2' => $row_product->precio_venta,
							// 	'precio_venta_3' => $row_product->precio_venta,
							// 	'stock' => $new_stock,
							// 	'fecha_modificacion' => date('Y-m-d H:i:s'),
							// 	'usuario_id' => get_user_id_in_session(),
							// 	'sucursal_registro_id' => get_branch_id_in_session(),
							// 	'sucursal_id' => get_branch_id_in_session(),
							// 	'almacen_id' => $warehouse_id,
							// 	'producto_id' => $product_id,
							// );

							// $this->db->insert('inventario_sucursal', $data_branch_office_inventory);


						}else if ($new_stock>0){
							$list_inventory = $this->list_product_in_inventory_specific($warehouse_id, $product_id);
							if (!is_null($list_inventory)) {
								$stock_total = floatval(0);
								$cost_total = floatval(0);
								$price_cost = floatval(0);
								$price_purchase = floatval(0);
								$price_sale = floatval(0);
								foreach ($list_inventory as $row_products) {
									$stock_total = $stock_total + $row_products->cantidad;
									$subtotal = floatval($row_products->cantidad * $row_products->precio_costo);
									$cost_total = $cost_total + $subtotal;
									$price_cost = floatval($row_products->precio_costo);
									$price_purchase = floatval($row_products->precio_compra);
									$price_sale = floatval($row_products->precio_venta);
									$price_sale_mayorista = floatval($row_products->precio_venta_mayor);
								}

								/*hacemos el calculos con lo que esta ingresando*/
								$average_price = floatval($cost_total / $stock_total);

								// Actualiza inventario sucursal
									$data_branch_office_inventory = array(
										'precio_compra' => $price_purchase,
										'precio_costo' => $price_cost,
										'precio_costo_ponderado' => $average_price,
										'precio_venta' => $price_sale,
										'precio_venta_1' => $price_sale_mayorista,
										'precio_venta_2' => $price_sale,
										'precio_venta_3' => $price_sale,
										'stock' => $new_stock,
										'fecha_modificacion' => date('Y-m-d H:i:s'),
										'usuario_id' => get_user_id_in_session(),
										'sucursal_registro_id' => get_branch_id_in_session(),
										'sucursal_id' => get_branch_id_in_session()
									);



								$this->db->where('sucursal_id', get_branch_id_in_session());
								$this->db->where('almacen_id', $warehouse_id);
								$this->db->where('producto_id', $product_id);
								$this->db->update('inventario_sucursal', $data_branch_office_inventory);
							}
						}else {
							$data_branch_office_inventory = array(
								'stock' => $new_stock,
								'fecha_modificacion' => date('Y-m-d H:i:s'),
								'usuario_id' => get_user_id_in_session(),
								'sucursal_registro_id' => get_branch_id_in_session(),
								'sucursal_id' => get_branch_id_in_session()
							);


							$this->db->where('sucursal_id', get_branch_id_in_session());
							$this->db->where('almacen_id', $warehouse_id);
							$this->db->where('producto_id', $product_id);
							$this->db->update('inventario_sucursal', $data_branch_office_inventory);
						}
					}

				}
			}	
			
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			// echo "Problemas";
		} else {
			$this->db->trans_commit();
			/*echo "Datos actualizados correctamente prueba ".floatval(1690.95 / 5);*/
		}
	}

	public function update_average_price_all()
	{
		$this->load->model('provider_model');
		$this->db->trans_begin();
		$this->db->select('*')
			->from('producto')
			->where('estado', ACTIVO)
			->order_by('id', 'ASC');
		$query = $this->db->get();

		foreach ($query->result() as $row_product) {
			/*verificamos si existe registro*/

			$list_branch_office = $this->office_model->get_offices();
			foreach ($list_branch_office as $branch_office) {
				$branch_office_id = $branch_office->id;
				$list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id($branch_office_id);
				foreach ($list_warehouse as $warehouse) {
					$warehouse_id = $warehouse->id;
					$product_id = $row_product->id;
					$new_stock = $this->stock_by_warehouse_id($warehouse_id, $product_id);

					if ($this->product_model->exists_branch_office_inventory($branch_office_id, $warehouse_id, $product_id) == 0) {
						// Registra inventario sucursal
						// $data_branch_office_inventory = array(
						// 	'precio_compra' => $row_product->precio_compra,
						// 	'precio_costo' => $row_product->precio_compra,
						// 	'precio_costo_ponderado' => $row_product->precio_ponderado,
						// 	'precio_venta' => $row_product->precio_venta,
						// 	'precio_venta_1' => $row_product->precio_venta_mayor,
						// 	'precio_venta_2' => $row_product->precio_venta,
						// 	'precio_venta_3' => $row_product->precio_venta,
						// 	'stock' => $new_stock,
						// 	'fecha_modificacion' => date('Y-m-d H:i:s'),
						// 	'usuario_id' => get_user_id_in_session(),
						// 	'sucursal_registro_id' => $branch_office_id,
						// 	'sucursal_id' => $branch_office_id,
						// 	'almacen_id' => $warehouse_id,
						// 	'producto_id' => $product_id,
						// );

						// $this->db->insert('inventario_sucursal', $data_branch_office_inventory);


					}else if($new_stock>0){
						$list_inventory = $this->list_product_in_inventory_specific($warehouse_id, $product_id);
						if (!is_null($list_inventory)) {
							$stock_total = floatval(0);
							$cost_total = floatval(0);
							$price_cost = floatval(0);
							$price_purchase = floatval(0);
							$price_sale = floatval(0);
							foreach ($list_inventory as $row_products) {
								$stock_total = $stock_total + $row_products->cantidad;
								$subtotal = floatval($row_products->cantidad * $row_products->precio_costo);
								$cost_total = $cost_total + $subtotal;
								$price_cost = floatval($row_products->precio_costo);
								$price_purchase = floatval($row_products->precio_compra);
								$price_sale = floatval($row_products->precio_venta);
								$price_sale_mayorista = floatval($row_products->precio_venta_mayor);
							}

							/*hacemos el calculos con lo que esta ingresando*/
							// $average_price = floatval($cost_total / $stock_total);
							$average_price = floatval($price_sale);


							// Actualiza inventario sucursal
							$data_branch_office_inventory = array(
								'precio_compra' => $price_purchase,
								'precio_costo' => $price_cost,
								'precio_costo_ponderado' => $average_price,
								'precio_venta' => $price_sale,
								'precio_venta_1' => $price_sale_mayorista,
								'precio_venta_2' => $price_sale,
								'precio_venta_3' => $price_sale,
								'stock' => $new_stock,
								'fecha_modificacion' => date('Y-m-d H:i:s'),
								'usuario_id' => get_user_id_in_session(),
								'sucursal_registro_id' => $branch_office_id,
								'sucursal_id' => $branch_office_id
							);

							$this->db->where('sucursal_id', $branch_office_id);
							$this->db->where('almacen_id', $warehouse_id);
							$this->db->where('producto_id', $product_id);
							$this->db->update('inventario_sucursal', $data_branch_office_inventory);
						}
					}else {
						// Actualiza inventario sucursal
						$data_branch_office_inventory = array(
							'stock' => $new_stock,
							'fecha_modificacion' => date('Y-m-d H:i:s'),
							'usuario_id' => get_user_id_in_session(),
							'sucursal_registro_id' => $branch_office_id,
							'sucursal_id' => $branch_office_id
						);

						$this->db->where('sucursal_id', $branch_office_id);
						$this->db->where('almacen_id', $warehouse_id);
						$this->db->where('producto_id', $product_id);
						$this->db->update('inventario_sucursal', $data_branch_office_inventory);
					}
				}

			}
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			// echo "Problemas";
		} else {
			$this->db->trans_commit();
			/*echo "Datos actualizados correctamente prueba ".floatval(1690.95 / 5);*/
		}
	}

	public function list_product_in_inventory($product_id)
	{
		$this->db->select('i.*')
			->from('inventario i, almacen a')
			->where('i.almacen_id=a.id')
			->where('i.producto_id', $product_id)
			->where('i.estado', ACTIVO)
			->where('a.estado', ACTIVO)
			->where('i.cantidad>0')
			->order_by('id', 'DESC');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}

	}

	public function list_product_in_inventory_specific($warehouse_id, $product_id)
	{
		$this->db->select('i.*, p.precio_venta_mayor, p.precio_venta_express, p.precio_venta_laboratorio')
			->from('inventario i, almacen a, producto p')
			->where('i.almacen_id=a.id')
			->where('i.producto_id=p.id')
			->where('i.almacen_id', $warehouse_id)
			->where('i.producto_id', $product_id)
			->where('i.estado', ACTIVO)
			->where('a.estado', ACTIVO)
			->where('i.cantidad>0')
			->order_by('id', 'ASC');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function stock_by_warehouse_id($warehouse_id, $product_id)
	{
		$this->db->select('COALESCE(SUM(i.cantidad),0) as stock')
			->from('ingreso_inventario iv, inventario i')
			->where('iv.id=i.ingreso_inventario_id')
			->where('i.producto_id', $product_id)
			->where('i.almacen_id', $warehouse_id)
			->where('i.cantidad>0')
			->where('iv.estado', ACTIVO)
			->where('i.estado', ACTIVO);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row()->stock;
		} else {
			return 0;
		}

	}

	public function product_by_warehouse_id($warehouse_id, $product_id)
	{
		$this->db->select('i.*')
			->from('inventario i, almacen a')
			->where('i.almacen_id=a.id')
			->where('i.producto_id', $product_id)
			->where('i.almacen_id', $warehouse_id)
			->where('i.estado', ACTIVO)
			->where('a.estado', ACTIVO);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}

	}

	public function list_inventory_by_date($date)
	{
		
		$this->db->select('i.*,a.sucursal_id')
			->from('inventario i, almacen a')
			->where('i.almacen_id=a.id')
			->where('DATE(i.fecha_ingreso)', $date)
			->where('i.estado', ACTIVO)
			->where('a.estado', ACTIVO);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}

	}

	function verify_inventory_by_product_all($product_id)
	{
		$this->db->select('*')
			->from('inventario')
			->where('producto_id', $product_id)
			->where('estado', ACTIVO);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function verify_inventory_by_product_especific_branch_office($product_id)
	{
		$this->db->select('*')
			->from('inventario')
			->where('producto_id', $product_id)
			->where('estado', ACTIVO);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function activate_product()
	{
		$id = $this->input->post('id');
		$warehouse_id = $this->input->post('warehouse_id');
		$product_id = $this->input->post('product_id');
		$this->db->update(
			'inventario',
			['estado' => ACTIVO],
            ['almacen_id'=>$warehouse_id, 'producto_id'=>$product_id]
		);
        return $this->db->update(
            'inventario_sucursal',
            ['estado' => ACTIVO],
            ['id' => $id]
        );
	}
	public function disable_product()
	{
		$id = $this->input->post('id');
		$warehouse_id = $this->input->post('warehouse_id');
		$product_id = $this->input->post('product_id');
		$this->db->update(
			'inventario',
			['estado' => ANULADO],
            ['almacen_id'=>$warehouse_id, 'producto_id'=>$product_id]
		);
        return $this->db->update(
            'inventario_sucursal',
            ['estado' => ANULADO],
            ['id' => $id]
        );
	}
	public function delete_product()
	{
		$id = $this->input->post('id');
		$warehouse_id = $this->input->post('warehouse_id');
		$product_id = $this->input->post('product_id');

		$this->db->update(
			'inventario',
			['cantidad' => 0],
            ['almacen_id'=>$warehouse_id, 'producto_id'=>$product_id]
		);
        return $this->db->delete(
            'inventario_sucursal',
            ['id' => $id]
        );
	}
	
	public function updated_branch_office_inventory_stock_global()
	{
		try {
			$this->db->trans_begin();
				$array_branch_office_inventory = $this->db->select('*')->from('inventario_sucursal')->where('estado', ACTIVO)->get()->result();

				foreach ($array_branch_office_inventory as $row) {
					$new_stock = $this->stock_by_warehouse_id($row->almacen_id, $row->producto_id);
                                                                               
					$data_branch_office_inventory = array(
						'stock' => $new_stock,
						'fecha_modificacion' => date('Y-m-d H:i:s')
					);

					$this->db->where('id', $row->id);
					$this->db->update('inventario_sucursal', $data_branch_office_inventory);	
				}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		} catch (\Throwable $th) {
			throw $th;
		}		
	}
}
