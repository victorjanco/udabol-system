<?php

/**
 *  * Created by PhpStorm.
 * User: Ariel Alejandro Gomez Chavez ( @ArielGomez )
 * Date: 7/5/2018
 * Time: 7:24 PM
 */
class Transfer_inventory_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventory_model');
    }

    /*Metodo que obtiene los datos de la tabla traspaso ingreso apartir de id salida de inventario*/
    public function get_transfer_entry_by_inventory_output_id($inventory_output_id)
    {
        return $this->db->get_where('traspaso_ingreso', array('salida_inventario_id' => $inventory_output_id))->row();
    }

    /*Metodo que obtiene los datos de la tabla traspaso ingreso apartir de id salida de inventario*/
    public function get_transfer_entry_by_inventory_entry_id($inventory_entry_id)
    {
        return $this->db->get_where('traspaso_ingreso', array('ingreso_inventario_id' => $inventory_entry_id))->row();
    }

    /*Metodo que obtiene los datos de la tabla traspaso ingreso apartir de id salida de inventario*/
    public function get_transfer_output($inventory_output_id)
    {

        $this->db->select('sal.id, 
                            ts.nro_traspaso_salida as nro, 
                            t.observacion, 
                            sal.fecha_modificacion, 
                            tip.nombre AS nombre_tipo_salida, 
                            sal.estado, 
                            so.nombre_comercial as sucursal_origen, 
                            sd.nombre_comercial as sucursal_destino, 
                            a.nombre as almacen_destino')
            ->from('salida_inventario sal ,tipo_salida_inventario tip, traspaso_salida t, sucursal sd,sucursal so, almacen a,traspaso_salida_sucursal ts')
            ->where('tip.id = sal.tipo_salida_inventario_id')
            ->where('t.id = ts.traspaso_salida_id')
            ->where('t.sucursal_origen_id = so.id')
            ->where('t.sucursal_destino_id = sd.id')
            ->where('t.almacen_destino_id = a.id')
            ->where('sal.id = t.salida_inventario_id')
            ->where('sal.estado', ACTIVO)
            ->where('sal.id', $inventory_output_id)
            ->where('sal.tipo_salida_inventario_id', 3)
            ->where('sal.sucursal_id', get_branch_id_in_session());

        return $this->db->get()->row();
    }

    public function get_transfer_entry($inventory_entry_id)
    {
        $this->db->select('sal.id, 
                            ts.nro_traspaso_ingreso as nro, 
                            t.observacion, 
                            sal.fecha_modificacion, 
                            tip.nombre AS nombre_tipo_ingreso, 
                            sal.estado, 
                            so.nombre_comercial as sucursal_origen, 
                            sd.nombre_comercial as sucursal_destino, 
                            a.nombre as almacen_destino')
            ->from('ingreso_inventario sal ,tipo_ingreso_inventario tip, traspaso_ingreso t, sucursal sd,sucursal so, almacen a, traspaso_ingreso_sucursal ts')
            ->where('tip.id = sal.tipo_ingreso_inventario_id')
            ->where('t.id = ts.traspaso_ingreso_id')
            ->where('t.sucursal_origen_id = so.id')
            ->where('t.sucursal_destino_id = sd.id')
            ->where('t.almacen_destino_id = a.id')
            ->where('sal.id = t.ingreso_inventario_id')
            ->where('sal.estado', ACTIVO)
            ->where('sal.id', $inventory_entry_id)
            ->where('sal.tipo_ingreso_inventario_id', 3)
            ->where('sal.sucursal_id', get_branch_id_in_session());

        return $this->db->get()->row();
    }

    public function get_transfer_inventory_output_list($params = array())
    {

        /* Se cachea la informacion que se arma en query builder*/
        $this->db->start_cache();
        $this->db->select('sal.id, 
                            ts.nro_traspaso_salida as nro, 
                            t.observacion, 
                            sal.fecha_modificacion, 
                            tip.nombre AS nombre_tipo_salida, 
                            sal.estado, 
                            sal.estado_aprobacion,
                            so.nombre_comercial as sucursal_origen, 
                            sd.nombre_comercial as sucursal_destino, 
                            a.nombre as almacen_destino')
            ->from('salida_inventario sal ,tipo_salida_inventario tip, traspaso_salida t, sucursal sd,sucursal so, almacen a,traspaso_salida_sucursal ts')
            ->where('tip.id = sal.tipo_salida_inventario_id')
            ->where('t.id = ts.traspaso_salida_id')
            ->where('t.sucursal_origen_id = so.id')
            ->where('t.sucursal_destino_id = sd.id')
            ->where('t.almacen_destino_id = a.id')
            ->where('sal.id = t.salida_inventario_id')
            ->where('sal.estado', ACTIVO)
            ->where('sal.tipo_salida_inventario_id', 3)
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

            $this->db->like('lower(t.observacion)', strtolower($params['search']));

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

    public function get_transfer_inventory_entry_list($params = array())
    {

        /* Se cachea la informacion que se arma en query builder*/
        $this->db->start_cache();
        $this->db->select('sal.id, 
                            ts.nro_traspaso_ingreso as nro, 
                            t.observacion, 
                            sal.fecha_modificacion, 
                            tip.nombre AS nombre_tipo_ingreso, 
                            sal.estado, 
                            sal.estado_aprobacion, 
                            so.nombre_comercial as sucursal_origen, 
                            sd.nombre_comercial as sucursal_destino, 
                            a.nombre as almacen_destino')
            ->from('ingreso_inventario sal ,tipo_ingreso_inventario tip, traspaso_ingreso t, sucursal sd,sucursal so, almacen a, traspaso_ingreso_sucursal ts')
            ->where('tip.id = sal.tipo_ingreso_inventario_id')
            ->where('t.id = ts.traspaso_ingreso_id')
            ->where('t.sucursal_origen_id = so.id')
            ->where('t.sucursal_destino_id = sd.id')
            ->where('t.almacen_destino_id = a.id')
            ->where('sal.id = t.ingreso_inventario_id')
            ->where('sal.estado', ACTIVO)
            ->where('sal.tipo_ingreso_inventario_id', 3)
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

            $this->db->like('lower(t.observacion)', strtolower($params['search']));

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

    public function register_transfer_inventory_output()
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
                    $product_array = $this->input->post('product_id');
                    $nro_lote_array = $this->input->post('codigo_lote');
                    $price_cost_array = $this->input->post('price_cost');
                    $price_sale_array = $this->input->post('price_sale');
                    $quantity_ouput_array = $this->input->post('quantity_ouput');
    
                    $description = strtoupper($this->input->post('description'));
                    $warehouse_origin_id = $this->input->post('warehouse_origin_id');
                    $warehouse_transfer_id = $this->input->post('warehouse_transfer_id');
                    $branch_office_transfer_id = $this->input->post('branch_office_transfer_id');
    
                    $today = date('Y-m-d H:i:s');
                    /*Datos para registrar en la tabla salida de inventario*/
                    $obj_output_inventory = [];
                    $obj_output_inventory["fecha_registro"] = $today;
                    $obj_output_inventory["fecha_modificacion"] = $today;
                    $obj_output_inventory["sincronizado"] = 1;
                    $obj_output_inventory["observacion"] = $description;
                    $obj_output_inventory["estado"] = ACTIVO;
                    $obj_output_inventory["estado_aprobacion"] = ANULADO;
                    $obj_output_inventory["tipo_salida_inventario_id"] = $this->input->post('type_exit_inventory');/*salida de inventario por alguna causa*/;
                    $obj_output_inventory["sucursal_id"] = get_branch_id_in_session();
                    $obj_output_inventory["nro_salida_inventario"] = $this->last_number_inventory_output();
                    $obj_output_inventory["user_created"] = get_user_id_in_session();
                    $obj_output_inventory["user_updated"] = get_user_id_in_session();
    
                    $tipo_ingreso_inventario_id = $this->input->post('type_exit_inventory');
                    /*Datos para registrar en la tabla ingreso de inventario*/
                    $obj_entry_inventario = [];
                    $obj_entry_inventario["nombre"] = $description;
                    $obj_entry_inventario["fecha_ingreso"] = $today;
                    $obj_entry_inventario["fecha_registro"] = $today;
                    $obj_entry_inventario["fecha_modificacion"] = $today;
                    $obj_entry_inventario["estado"] = ACTIVO;
                    $obj_entry_inventario["estado_aprobacion"] = ANULADO;
                    $obj_entry_inventario["tipo_ingreso_inventario_id"] = $tipo_ingreso_inventario_id;
                    $obj_entry_inventario["sucursal_id"] = $branch_office_transfer_id;
                    // $obj_entry_inventario["nro_ingreso_inventario"] = $this->last_number_inventory_entry();
                    $obj_entry_inventario["nro_ingreso_inventario"] = $this->last_number_inventory_entry_traspaso($branch_office_transfer_id);
                    $obj_entry_inventario["user_created"] = get_user_id_in_session();
                    $obj_entry_inventario["user_updated"] = get_user_id_in_session();
    
    
                    $this->db->trans_begin();
                    /*Insertamos en la tabla ingreso inventario*/
                    $this->inventory_model->_insert_inventory_output($obj_output_inventory);
                    $inventory_output_inserted = $this->inventory_model->_get_inventory_output($obj_output_inventory);
    
                    /*Insertamos en la tablar traspaso salida*/
                    $obj_transfer_output_inventary = [];
                    $obj_transfer_output_inventary["nro_traspaso_salida"] = '0';
                    $obj_transfer_output_inventary["observacion"] = $description;
                    $obj_transfer_output_inventary["fecha_registro"] = $today;
                    $obj_transfer_output_inventary["fecha_modificacion"] = $today;
                    $obj_transfer_output_inventary["estado"] = ACTIVO;
                    $obj_transfer_output_inventary["sucursal_destino_id"] = intval($branch_office_transfer_id);
                    $obj_transfer_output_inventary["almacen_destino_id"] = intval($warehouse_transfer_id);
                    $obj_transfer_output_inventary["sucursal_origen_id"] = intval(get_branch_id_in_session());
                    $obj_transfer_output_inventary["almacen_origen_id"] = $warehouse_origin_id;
                    $obj_transfer_output_inventary["salida_inventario_id"] = floatval($inventory_output_inserted->id);
                    $obj_transfer_output_inventary["sucursal_id"] = intval(get_branch_id_in_session());
                    $obj_transfer_output_inventary["user_created"] = get_user_id_in_session();
                    $obj_transfer_output_inventary["user_updated"] = get_user_id_in_session();
                    $this->_insert_transfer_inventory_output($obj_transfer_output_inventary);
                    $transfer_inventory_output_inserted = $this->_get_transfer_inventory_output($obj_transfer_output_inventary);
    
                    $number_inventory_output = $this->last_number_transfer_inventory_output_by_branch_office_id(get_branch_id_in_session());
                    $data_transfer_inventory_output_branch_office["sucursal_id"] = get_branch_id_in_session();
                    $data_transfer_inventory_output_branch_office["traspaso_salida_id"] = $transfer_inventory_output_inserted->id;
                    $data_transfer_inventory_output_branch_office["nro_traspaso_salida"] = $number_inventory_output;
    
                    $this->insert_number_transfer_inventory_output_branch_office($data_transfer_inventory_output_branch_office);
    
                    /*Insertamos en la tabla ingreso inventario*/
                    $this->inventory_model->_insert_inventory_entry($obj_entry_inventario);
                    $inventory_entry_inserted = $this->inventory_model->_get_inventory_entry($obj_entry_inventario);
    
                    /*Insertamos en la tabla traspaso ingreso*/
                    $obj_transfer_entry_inventary = [];
                    $obj_transfer_entry_inventary["nro_traspaso_ingreso"] = '1';
                    $obj_transfer_entry_inventary["observacion"] = $description;
                    $obj_transfer_entry_inventary["fecha_registro"] = $today;
                    $obj_transfer_entry_inventary["fecha_modificacion"] = $today;
                    $obj_transfer_entry_inventary["estado"] = ACTIVO;
                    $obj_transfer_entry_inventary["sucursal_destino_id"] = intval($branch_office_transfer_id);
                    $obj_transfer_entry_inventary["almacen_destino_id"] = intval($warehouse_transfer_id);
                    $obj_transfer_entry_inventary["sucursal_origen_id"] = intval(get_branch_id_in_session());
                    $obj_transfer_entry_inventary["almacen_origen_id"] = $warehouse_origin_id;
                    $obj_transfer_entry_inventary["ingreso_inventario_id"] = floatval($inventory_entry_inserted->id);
                    $obj_transfer_entry_inventary["salida_inventario_id"] = floatval($inventory_output_inserted->id);
                    $obj_transfer_entry_inventary["sucursal_id"] = intval($branch_office_transfer_id);
                    $obj_transfer_entry_inventary["user_created"] = get_user_id_in_session();
                    $obj_transfer_entry_inventary["user_updated"] = get_user_id_in_session();
                    $this->_insert_transfer_inventory_entry($obj_transfer_entry_inventary);
                    $transfer_inventory_entry_inserted = $this->_get_transfer_inventory_entry($obj_transfer_entry_inventary);
    
    
                    $number_inventory_entry = $this->last_number_transfer_inventory_entry_by_branch_office_id($branch_office_transfer_id);
                    $data_inventory_entry_branch_office["sucursal_id"] = $branch_office_transfer_id;
                    $data_inventory_entry_branch_office["traspaso_ingreso_id"] = $transfer_inventory_entry_inserted->id;
                    $data_inventory_entry_branch_office["nro_traspaso_ingreso"] = $number_inventory_entry;
                    $this->insert_number_transfer_inventory_entry_branch_office($data_inventory_entry_branch_office);
    
    
                    for ($index = 0; $index < $number_rows; $index++) {
                        /*registro de salida de inventario */
                        $data_inventory_db = $this->inventory_model->findED($inventory_array[$index]);
                        $product = $this->product_model->find($product_array[$index]);

                        $obj_detail_output_inventory["cantidad"] = $quantity_ouput_array[$index];
                        $obj_detail_output_inventory["cantidad_antigua"] = $data_inventory_db->cantidad;
                        $obj_detail_output_inventory["precio_costo"] = $price_cost_array[$index];
                        $obj_detail_output_inventory["precio_venta"] = $price_sale_array[$index];
                        $obj_detail_output_inventory["observacion"] = $description;
                        $obj_detail_output_inventory["salida_inventario_id"] = $inventory_output_inserted->id;
                        $obj_detail_output_inventory["inventario_id"] = $inventory_array[$index];
                        $this->inventory_model->_insert_detail_inventory_output($obj_detail_output_inventory);
                        // $data_inventory_db = $this->inventory_model->get_inventory_product_detail($inventory_array[$index]);

                        if ($quantity_ouput_array[$index] > $data_inventory_db->cantidad) {
                            throw new Exception("Inventario menor a lo que quiere retirar");
                        }
                        $quantity_update = $data_inventory_db->cantidad - $quantity_ouput_array[$index];
                        $this->inventory_model->_update_stock_inventory($inventory_array[$index], $quantity_update);
                        $this->inventory_model->update_branch_office_inventory($inventory_array[$index]);

                        /*Registro de nuevo ingreso de inventario */
                        $obj_detail_entry_inventary["codigo"] = $nro_lote_array[$index];
                        $obj_detail_entry_inventary["cantidad"] = $quantity_ouput_array[$index];
                        $obj_detail_entry_inventary["cantidad_ingresada"] = $quantity_ouput_array[$index];
                        $obj_detail_entry_inventary["precio_compra"] = $price_cost_array[$index];
                        $obj_detail_entry_inventary["precio_costo"] = $price_cost_array[$index];
                        $obj_detail_entry_inventary["precio_venta"] = $price_sale_array[$index];
                        $obj_detail_entry_inventary["fecha_ingreso"] = $today;
                        $obj_detail_entry_inventary["fecha_modificacion"] = $today;
                        $obj_detail_entry_inventary["estado"] = ANULADO;
                        $obj_detail_entry_inventary["almacen_id"] = $warehouse_transfer_id;
                        $obj_detail_entry_inventary["ingreso_inventario_id"] = $inventory_entry_inserted->id;
                        $obj_detail_entry_inventary["producto_id"] = $product_array[$index];
    
                        $this->inventory_model->_insert_inventory($obj_detail_entry_inventary);
                        $inventory_inserted = $this->inventory_model->_get_inventory($obj_detail_entry_inventary);

                        if ($this->product_model->exists_branch_office_inventory($branch_office_transfer_id, $warehouse_transfer_id, $product_array[$index]) == 0) {
							// Registra inventario sucursal
							$data_branch_office_inventory = array(
								'precio_compra' => $price_cost_array[$index],
								'precio_costo' => $price_cost_array[$index],
								'precio_costo_ponderado' =>$price_sale_array[$index],
								'precio_venta' => $price_sale_array[$index],
								'precio_venta_1' => $product->precio_venta_mayor,
								'precio_venta_2' => $price_sale_array[$index],
								'precio_venta_3' => $price_sale_array[$index],
								'porcentaje_precio_venta_3' => 0,
								'stock' => 0,
								// 'stock' => $quantity_ouput_array[$index],
								'fecha_modificacion' => date('Y-m-d H:i:s'),
								'usuario_id' => get_user_id_in_session(),
								'sucursal_registro_id' =>$branch_office_transfer_id,
								'sucursal_id' => $branch_office_transfer_id,
								'almacen_id' => $warehouse_transfer_id,
								'producto_id' => $product_array[$index],
								'estado' => ACTIVO
							);
							$this->db->insert('inventario_sucursal', $data_branch_office_inventory);	
						}else{
                            $this->inventory_model->update_preci_branch_office_inventory($inventory_inserted->id);
                        }
                    }
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $response['success'] = FALSE;
                    } else {
                        $this->db->trans_commit();
                        // $this->inventory_model->update_product_in_inventory_branch_office($this->inventory_model->get_detail_inventory_view($inventory_entry_inserted->id));
                        $response['success'] = TRUE;
                        $response['id'] = $inventory_output_inserted->id;
                        $response['url_impression'] = 'transfer_inventory/print_transfer_inventory';
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

    public function _insert_transfer_inventory_entry($inventory_entry)
    {
        $this->db->insert('traspaso_ingreso', $inventory_entry);
    }

    public function _insert_transfer_inventory_output($inventory_output)
    {
        $this->db->insert('traspaso_salida', $inventory_output);
    }

    /*Funcion para desabilitar el traspaso de ingreso y todas las relaciones que tenga*/
    public function aprobation_transfer_inventory_entry($inventory_entry_id)
    {
        try {
            /*Metodo para que  en la tabla traspaso e ingreso de inventario y tambien todas sus relaciones*/
            $this->db->trans_begin();
            $inventory_entry = $this->inventory_model->get_inventory_entry_id($inventory_entry_id);

            $this->db->set('estado_aprobacion', ACTIVO);
            $this->db->where('id', $inventory_entry_id);
            $this->db->update('ingreso_inventario');

            $this->db->set('estado', ACTIVO);
            $this->db->where('ingreso_inventario_id', $inventory_entry_id);
            $this->db->update('inventario');

            $inventory_output_id = $this->get_transfer_entry_by_inventory_entry_id($inventory_entry_id)->salida_inventario_id;

            $this->db->set('estado_aprobacion', ACTIVO);
            $this->db->where('id', $inventory_output_id);
            $this->db->update('salida_inventario');

            $detail_inventory_in = $this->inventory_model->get_detail_inventory($inventory_entry_id);
            
            foreach ($detail_inventory_in as $row) {
                $this->inventory_model->update_branch_office_inventory($row->id);
            }
            
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                // $this->inventory_model->update_product_in_inventory_other_branch_office($this->inventory_model->get_detail_inventory($inventory_entry_id),intval($inventory_entry->sucursal_id));
                return true;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /*Funcion para desabilitar el traspaso de ingreso y todas las relaciones que tenga*/
    public function descart_transfer_inventory_entry($inventory_entry_id)
    {
        try {
            /*Metodo para que  en la tabla traspaso e ingreso de inventario y tambien todas sus relaciones*/
            $this->db->trans_begin();
            $inventory_entry = $this->inventory_model->get_inventory_entry_id($inventory_entry_id);

            $this->db->set('estado_aprobacion', 2);//DESCARTADO
            $this->db->where('id', $inventory_entry_id);    
            $this->db->update('ingreso_inventario');

            $inventory_output_transfer = $this->get_transfer_entry_by_inventory_entry_id($inventory_entry_id);
            $inventory_output = $this->inventory_model->get_inventory_output_id($inventory_output_transfer->salida_inventario_id);


            $this->inventory_model->disable_inventory_output_by_id($inventory_output_transfer->salida_inventario_id);

            // $detail_inventory_output = $this->inventory_model->get_detail_inventory_output($inventory_output_transfer->salida_inventario_id);
			// foreach ($detail_inventory_output as $row) {
            //     $this->inventory_model->_update_stock_inventory($row->inventario_id, 0);
			// 	$this->inventory_model->update_branch_office_inventory($row->inventario_id);
			// }

            $this->db->set('estado_aprobacion', 2); //DESCARTADO
            $this->db->set('estado', 1); //ACTIVO
            $this->db->set('user_updated', get_user_id_in_session());
            $this->db->set('fecha_modificacion', date('Y-m-d H:i:s'));
            $this->db->where('id', $inventory_output_transfer->salida_inventario_id);
            $this->db->update('salida_inventario');

            $this->db->set('estado', ANULADO);
            $this->db->set('user_updated', get_user_id_in_session());
            $this->db->set('fecha_modificacion', date('Y-m-d H:i:s'));
            $this->db->where('ingreso_inventario_id', $inventory_entry_id);
            $this->db->update('inventario');
            
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                // $this->inventory_model->update_product_in_inventory_other_branch_office_old($this->inventory_model->get_detail_inventory_view($inventory_entry_id),intval($inventory_output->sucursal_id));
                return true;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    



    /*Funcion para desabilitar el traspaso de ingreso y todas las relaciones que tenga*/
    public function disable_transfer_inventory_entry($inventory_entry_id)
    {
        /*Metodo para que  en la tabla traspaso e ingreso de inventario y tambien todas sus relaciones*/
        $this->disable_transfer_entry($inventory_entry_id);

        /*Apartir del id de salida de inventario recuperamos la informacion de traspaso ingreso para sacar ingreso_inventario_id*/
        $inventory_output_id = $this->get_transfer_entry_by_inventory_entry_id($inventory_entry_id)->salida_inventario_id;

        /*Metodo para que  en la tabla traspaso y salida de inventario y tambien todas sus relaciones*/
        $this->disable_transfer_output($inventory_output_id);
		// $this->inventory_model->update_average_price_all();
        // $inventory_output_transfer = $this->get_transfer_entry_by_inventory_entry_id($inventory_entry_id);
        // $inventory_output = $this->inventory_model->get_inventory_output_id($inventory_output_transfer->salida_inventario_id);
        // $this->inventory_model->update_product_in_inventory_other_branch_office($this->inventory_model->get_detail_inventory_view($inventory_entry_id),intval($inventory_output->sucursal_id));
        
    }

    /*Funcion para desabilitar el traspaso de ingreso*/
    public function disable_transfer_entry($inventory_entry_id)
    {

        /*Metadp para que anule en la tabla traspaso ingreso*/
        $this->disable_transfer_entry_by_inventory_entry_id($inventory_entry_id);

        /*Metadp para que anule en la tabla ingreso inventario y su detalle*/
        $this->inventory_model->disable_inventory_entry_by_id($inventory_entry_id);
		// $this->inventory_model->update_product_in_inventory_branch_office($this->inventory_model->get_detail_inventory($inventory_entry_id));

    }


    /*Funcion para desabilitar el traspaso de salida y todas las relaciones que tenga*/
    public function disable_transfer_inventory_output($inventory_output_id)
    {
        /*Metodo para que  en la tabla traspaso e salida de inventario y tambien todas sus relaciones*/
        $this->disable_transfer_output($inventory_output_id);
//        echo json_encode($this->get_transfer_entry_by_inventory_output_id($inventory_output_id)) ;
        /*Apartir del id de salida de inventario recuperamos la informacion de traspaso ingreso para sacar ingreso_inventario_id*/
        $inventory_entry_id = $this->get_transfer_entry_by_inventory_output_id($inventory_output_id)->ingreso_inventario_id;
        /*Metodo para que  en la tabla traspaso e ingreso de inventario y tambien todas sus relaciones*/
        $this->disable_transfer_entry($inventory_entry_id);
		// $this->inventory_model->update_average_price_all();


    }

    public function disable_transfer_output($inventory_output_id)
    {
        /*anulamos la tabla traspaso salida por el id de salida inventario*/
        $this->disable_transfer_output_by_inventory_output_id($inventory_output_id);

        /*Anulamos en la tabla salida de inventario y su detalle*/
        $this->inventory_model->disable_inventory_output_by_id($inventory_output_id);
    }

    /*Funcion para desabilitar el traspaso de salida por el id de salida inventario*/
    public function disable_transfer_output_by_inventory_output_id($inventory_output_id)
    {
        return $this->db->update(
            'traspaso_salida',
            ['estado' => ANULADO],
            ['salida_inventario_id' => $inventory_output_id]
        );
    }

    /*Funcion para desabilitar el traspaso de ingreso*/
    public function disable_transfer_entry_by_inventory_entry_id($inventory_entry_id)
    {
        return $this->db->update(
            'traspaso_ingreso',
            ['estado' => ANULADO],
            ['ingreso_inventario_id' => $inventory_entry_id]
        );

    }


    /* Funcion que retorna el siguiente numero INGRESO de inventario por Sucursal por su sucursal_id*/
    public function last_number_transfer_inventory_entry_by_branch_office_id($branch_office_id)
    {
        $this->db->select_max('nro_traspaso_ingreso');
        $this->db->where('sucursal_id', $branch_office_id);
        $result = $this->db->get('traspaso_ingreso_sucursal');
        if ($result->num_rows() > 0) {
            $query = $result->row();
            return $query->nro_traspaso_ingreso + 1;
        } else {
            return 1;
        }
    }

    /* Funcion que retorna el siguiente numero SALIDA de inventario por Sucursal por su sucursal_id*/
    public function last_number_transfer_inventory_output_by_branch_office_id($branch_office_id)
    {
        $this->db->select_max('nro_traspaso_salida');
        $this->db->where('sucursal_id', $branch_office_id);
        $result = $this->db->get('traspaso_salida_sucursal');
        if ($result->num_rows() > 0) {
            $query = $result->row();
            return $query->nro_traspaso_salida + 1;
        } else {
            return 1;
        }
    }

    public function _get_transfer_inventory_entry($transfer_inventory_entry)
    {
        return $this->db->get_where('traspaso_ingreso', $transfer_inventory_entry)->row();
    }

    public function _get_transfer_inventory_output($transfer_inventory_output)
    {
        return $this->db->get_where('traspaso_salida', $transfer_inventory_output)->row();
    }


    /* Metodo para el registro de INGRESO inventario sucursal */
    public function insert_number_transfer_inventory_entry_branch_office($data)
    {
        return $this->db->insert('traspaso_ingreso_sucursal', $data);
    }

    /* Metodo para el registro de SALIDA inventario sucursal */
    public function insert_number_transfer_inventory_output_branch_office($data)
    {
        return $this->db->insert('traspaso_salida_sucursal', $data);
    }

    /* Funcion que retorna el siguiente numero INGRESO de inventario por Sucursal */
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

    /* Funcion que retorna el siguiente numero INGRESO de inventario por Sucursal */
    public function last_number_inventory_entry_traspaso($branch_office_id)
    {
        $this->db->select_max('nro_ingreso_inventario');
        $this->db->where('sucursal_id', $branch_office_id);
        $result = $this->db->get('ingreso_inventario');
        if ($result->num_rows() > 0) {
            $query = $result->row();
            return $query->nro_ingreso_inventario + 1;
        } else {
            return 1;
        }
    }

    /* Funcion que retorna el siguiente numero SALIDA de inventario por Sucursal */
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

}
