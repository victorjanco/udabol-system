<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 24/05/2019
 * Time: 11:22 AM
 */

class Transit_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transfer_inventory_model');
        $this->load->model('inventory_model');
    }

    public function get_type_reason_enable()
    {
        return $this->db->get_where('tipo_motivo', array('estado' => get_state_abm('ACTIVO'), 'tipo' => TIPO_MOTIVO_TRANSITO))->result();
    }

    public function get_transit_report($params = array())
    {

        $brand = $params['transit_brand'];
        $product = $params['transit_product'];
        $model = $params['transit_model'];
        /* Se cachea la informacion que se arma en query builder*/
        $this->db->start_cache();
        $this->db->select('v.*, t.nombre as nombre_tipo_motivo')
            ->from('vista_transito v , tipo_motivo t')
            ->where('v.tipo=t.id')
            ->where('v.estado', ACTIVO)
            ->where('v.sucursal_id_prestamo', get_branch_id_in_session());

        if ($params['transit_date_start_loan'] != '') {
            $this->db->where('v.fecha_transito_prestamo >=', $params['transit_date_start_loan']);
        }
        if ($params['transit_date_end_loan'] != '') {
            $this->db->where('v.fecha_transito_prestamo <=', $params['transit_date_end_loan']);
        }

        if ($params['transit_date_start_return'] != '') {
            $this->db->where('v.fecha_transito_devolucion >=', $params['transit_date_start_return']);
        }
        if ($params['transit_date_end_return'] != '') {
            $this->db->where('v.fecha_transito_devolucion <=', $params['transit_date_end_return']);
        }

        if ($params['transit_reception_code'] != '') {
            $this->db->where('v.codigo_recepcion', $params['transit_reception_code']);
        }

        if ($params['transit_state'] != '0') {
            $this->db->where('v.estado_transito', $params['transit_state']);
        }

        if ($params['transit_type_reason'] != '') {
            $this->db->where('v.tipo', $params['transit_type_reason']);
        }

        if ($brand != '') {
            $this->db->where("v.id in (select d.transito_id from detalle_transito d, vista_lista_producto p where d.producto_id=p.producto_id AND p.marca_id='$brand')");
        }

        if ($model != '') {
            $this->db->where("v.id in (select d.transito_id from detalle_transito d, vista_lista_producto p where d.producto_id=p.producto_id AND p.modelo_id='$model')");
        }

        if ($product != '') {
            $this->db->where("v.id in (select d.transito_id from detalle_transito d, vista_lista_producto p where d.producto_id=p.producto_id AND p.producto_id='$product')");
        }

        if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
            $this->db->order_by('v.id', 'asc');
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
            if ($params['order'] != '' || $params['column'] != '') {
                $this->db->order_by($params['column'], $params['order']);
            } else {
                $this->db->order_by('v.id', 'asc');
            }
        } else {
            $this->db->order_by('v.id', 'asc');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('lower(v.codigo_recepcion)', strtolower($params['search']));
            $this->db->or_like('lower(v.codigo_recepcion)', strtolower($params['search']));
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

    public function get_transit_list($params = array())
    {

        /* Se cachea la informacion que se arma en query builder*/
        $this->db->start_cache();
        $this->db->select('*')
            ->from('transito')
            ->where('estado', ACTIVO)
            ->where('sucursal_id_prestamo', get_branch_id_in_session())
        ->where('estado_transito!=2');

        if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
            $this->db->order_by('id', 'asc');
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
            if ($params['order'] != '' || $params['column'] != '') {
                $this->db->order_by($params['column'], $params['order']);
            } else {
                $this->db->order_by('id', 'asc');
            }
        } else {
            $this->db->order_by('id', 'asc');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('lower(observacion_prestamo)', strtolower($params['search']));
            $this->db->or_like('lower(detalle_prestamo)', strtolower($params['search']));
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

    public function register_transfer_inventory_output_transit()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            $validation_rules = array(
                array(
                    'field' => 'observation',
                    'label' => '<strong>Observacion</strong>',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'applicant_user',
                    'label' => '<strong>Usuario Solicitante</strong>',
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
                $product_name_array = $this->input->post('product_name');
                $product_code_array = $this->input->post('product_code');
                $nro_lote_array = $this->input->post('codigo_lote');
                $price_cost_array = $this->input->post('price_cost');
                $price_sale_array = $this->input->post('price_sale');
                $quantity_ouput_array = $this->input->post('quantity_ouput');
                $warehouse_origin_array = $this->input->post('warehouse');

                $description = strtoupper($this->input->post('observation'));
                $warehouse_transfer_id = $this->input->post('destination_warehouse_id');
                $branch_office_transfer_id = $this->input->post('destination_branch_office_id');

                $today = date('Y-m-d H:i:s');
                /*Datos para registrar en la tabla salida de inventario*/
                $obj_output_inventory = [];
                $obj_output_inventory["fecha_registro"] = $today;
                $obj_output_inventory["fecha_modificacion"] = $today;
                $obj_output_inventory["sincronizado"] = 1;
                $obj_output_inventory["observacion"] = $description;
                $obj_output_inventory["estado"] = ACTIVO;
                $obj_output_inventory["tipo_salida_inventario_id"] = 8; // Tipo de salida por prestamo de pieza;
                $obj_output_inventory["sucursal_id"] = get_branch_id_in_session();
                $obj_output_inventory["nro_salida_inventario"] = $this->transfer_inventory_model->last_number_inventory_output();


                /*Datos para registrar en la tabla ingreso de inventario*/
                $obj_entry_inventario = [];
                $obj_entry_inventario["nombre"] = $description;
                $obj_entry_inventario["fecha_ingreso"] = $today;
                $obj_entry_inventario["fecha_registro"] = $today;
                $obj_entry_inventario["fecha_modificacion"] = $today;
                $obj_entry_inventario["estado"] = ACTIVO;
                $obj_entry_inventario["tipo_ingreso_inventario_id"] = 5; // Tipo de ingreso por prestamo de pieza
                $obj_entry_inventario["sucursal_id"] = $branch_office_transfer_id;
                $obj_entry_inventario["usuario_id"] = get_user_id_in_session();
                $obj_entry_inventario["nro_ingreso_inventario"] = $this->transfer_inventory_model->last_number_inventory_entry();


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
                $obj_transfer_output_inventary["almacen_origen_id"] = 1;
                $obj_transfer_output_inventary["salida_inventario_id"] = floatval($inventory_output_inserted->id);
                $obj_transfer_output_inventary["sucursal_id"] = intval(get_branch_id_in_session());
                $obj_transfer_output_inventary["usuario_id"] = intval(get_user_id_in_session());
                $this->_insert_transfer_inventory_output($obj_transfer_output_inventary);
                $transfer_inventory_output_inserted = $this->_get_transfer_inventory_output($obj_transfer_output_inventary);

                $number_inventory_output = $this->transfer_inventory_model->last_number_transfer_inventory_output_by_branch_office_id(get_branch_id_in_session());
                $data_transfer_inventory_output_branch_office["sucursal_id"] = get_branch_id_in_session();
                $data_transfer_inventory_output_branch_office["traspaso_salida_id"] = $transfer_inventory_output_inserted->id;
                $data_transfer_inventory_output_branch_office["nro_traspaso_salida"] = $number_inventory_output;

                $this->transfer_inventory_model->insert_number_transfer_inventory_output_branch_office($data_transfer_inventory_output_branch_office);

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
                $obj_transfer_entry_inventary["almacen_origen_id"] = 1;
                $obj_transfer_entry_inventary["ingreso_inventario_id"] = floatval($inventory_entry_inserted->id);
                $obj_transfer_entry_inventary["salida_inventario_id"] = floatval($inventory_output_inserted->id);
                $obj_transfer_entry_inventary["sucursal_id"] = intval($branch_office_transfer_id);
                $obj_transfer_entry_inventary["usuario_id"] = intval(get_user_id_in_session());
                $this->_insert_transfer_inventory_entry($obj_transfer_entry_inventary);
                $transfer_inventory_entry_inserted = $this->_get_transfer_inventory_entry($obj_transfer_entry_inventary);


                $number_inventory_entry = $this->transfer_inventory_model->last_number_transfer_inventory_entry_by_branch_office_id($branch_office_transfer_id);
                $data_inventory_entry_branch_office["sucursal_id"] = $branch_office_transfer_id;
                $data_inventory_entry_branch_office["traspaso_ingreso_id"] = $transfer_inventory_entry_inserted->id;
                $data_inventory_entry_branch_office["nro_traspaso_ingreso"] = $number_inventory_entry;
                $this->transfer_inventory_model->insert_number_transfer_inventory_entry_branch_office($data_inventory_entry_branch_office);


                $obj_transit = [];
                $obj_transit['nro_prestamo'] = strval($this->last_number_transit_by_branch_office_id(get_branch_id_in_session()));
                $obj_transit['nro_transaccion'] = $this->last_number_transit_by_branch_office_id(get_branch_id_in_session());
                $obj_transit['tipo'] = $this->input->post('reason');
                $obj_transit['estado'] = ACTIVO;
                $obj_transit['estado_transito'] = PRESTADO;
                /*if ($this->input->post('reason') == 5) { // Significa que es por orden de trabajo en la tabla tipo de motivo = 5
                    $obj_transit['recepcion_id'] = $this->input->post('order_work_id');
                }*/
                $obj_transit['observacion_prestamo'] = $description;

               // $obj_transit['detalle_devolucion'] = $detalle_prestado;
                $obj_transit['fecha_transito_prestamo'] = $this->input->post('date_output');
                $obj_transit['fecha_registro_prestamo'] = date('Y-m-d H:i:s');
                $obj_transit['usuario_entregador_id_prestamo'] = $this->input->post('delivery_user_id');
                $obj_transit['usuario_solicitante_id_prestamo'] = $this->input->post('applicant_user');
                $obj_transit['sucursal_origen_id_prestamo'] = $this->input->post('origin_branch_office_id');
                $obj_transit['almacen_origen_id_prestamo'] = $this->input->post('origin_warehouse_id');
                $obj_transit['sucursal_destino_id_prestamo'] = $this->input->post('destination_branch_office_id');
                $obj_transit['almacen_destino_id_prestamo'] = $this->input->post('destination_warehouse_id');
                $obj_transit['ingreso_inventario_id_prestamo'] = $inventory_entry_inserted->id;
                $obj_transit['salida_inventario_id_prestamo'] = $inventory_output_inserted->id;
                $obj_transit['sucursal_id_prestamo'] = get_branch_id_in_session();
                $obj_transit['codigo_recepcion'] = $this->input->post('code_work');
                $this->_insert_transit($obj_transit);
                $transit_inserted = $this->_get_transit($obj_transit);

                $detalle_prestado = '';
                for ($index = 0; $index < $number_rows; $index++) {

                    /*Registro de detalle de transito*/
                    $obj_detail_transit["cantidad"] = $quantity_ouput_array[$index];
                    $obj_detail_transit["estado"] = ACTIVO;
                    $obj_detail_transit["transito_id"] = $transit_inserted->id;
                    $obj_detail_transit["producto_id"] = $product_array[$index];
                    $obj_detail_transit["almacen_origen_id"] = $warehouse_origin_array[$index];
                    $obj_detail_transit["almacen_destino_id"] = $warehouse_transfer_id;
                    $this->_insert_detail_transit($obj_detail_transit);
                    $detail_transit = $this->_get_detail_transit($obj_detail_transit);

                    /*registro de salida de inventario */
                    $obj_detail_output_inventory["cantidad"] = $quantity_ouput_array[$index];
                    $obj_detail_output_inventory["precio_costo"] = $price_cost_array[$index];
                    $obj_detail_output_inventory["precio_venta"] = $price_sale_array[$index];
                    $obj_detail_output_inventory["observacion"] = $description;
                    $obj_detail_output_inventory["salida_inventario_id"] = $inventory_output_inserted->id;
                    $obj_detail_output_inventory["inventario_id"] = $inventory_array[$index];
                    $this->inventory_model->_insert_detail_inventory_output($obj_detail_output_inventory);
                    $data_inventory_db = $this->inventory_model->get_inventory_product_detail($inventory_array[$index]);
                    $quantity_update = $data_inventory_db->cantidad - $quantity_ouput_array[$index];
                    $this->inventory_model->_update_stock_inventory($inventory_array[$index], $quantity_update);

                    /*Registro de nuevo ingreso de inventario */
                    $obj_detail_entry_inventary["codigo"] = $nro_lote_array[$index];
                    $obj_detail_entry_inventary["cantidad"] = $quantity_ouput_array[$index];
                    $obj_detail_entry_inventary["cantidad_ingresada"] = $quantity_ouput_array[$index];
                    $obj_detail_entry_inventary["precio_compra"] = $price_cost_array[$index];
                    $obj_detail_entry_inventary["precio_costo"] = $price_cost_array[$index];
                    $obj_detail_entry_inventary["precio_venta"] = $price_sale_array[$index];
                    $obj_detail_entry_inventary["fecha_ingreso"] = $today;
                    $obj_detail_entry_inventary["fecha_modificacion"] = $today;
                    $obj_detail_entry_inventary["estado"] = ACTIVO;
                    $obj_detail_entry_inventary["almacen_id"] = $warehouse_transfer_id;
                    $obj_detail_entry_inventary["ingreso_inventario_id"] = $inventory_entry_inserted->id;
                    $obj_detail_entry_inventary["producto_id"] = $product_array[$index];
                    $obj_detail_entry_inventary["detalle_transito_id"] = $detail_transit->id;
                    $detalle_prestado = $product_code_array[$index] . '|' . $product_name_array[$index] . ' , ' . $detalle_prestado;

                    $this->inventory_model->_insert_inventory($obj_detail_entry_inventary);
                }

                $this->_update_transit_detail_transit($transit_inserted->id, $detalle_prestado);


                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response['success'] = FALSE;
                } else {
                    $this->db->trans_commit();
					$this->inventory_model->update_product_in_inventory_branch_office($this->inventory_model->get_detail_inventory($inventory_entry_inserted->id));
                    $response['success'] = TRUE;
                    $response['id'] = $transit_inserted->id;
                    $response['url_impression'] = 'transit/print_transit_output';
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
    }
    public function register_transfer_inventory_entry_transit()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            $validation_rules = array(
                array(
                    'field' => 'observation',
                    'label' => '<strong>Observacion</strong>',
                    'rules' => 'trim|required'
                )
            );

            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
            $this->form_validation->set_message('required', 'El campo es necesario, ingrese un valor');

            if ($this->form_validation->run() === true) {
                $number_rows = count($this->input->post('inventory_id'));
                $transit_id = $this->input->post('transit_id');
                $inventory_array = $this->input->post('inventory_id');
                $product_array = $this->input->post('product_id');
                $product_name_array = $this->input->post('product_name');
                $nro_lote_array = $this->input->post('codigo_lote');
                $price_cost_array = $this->input->post('price_cost');
                $price_sale_array = $this->input->post('price_sale');
                $quantity_ouput_array = $this->input->post('quantity_ouput');
                $warehouse_detail_id_origin = $this->input->post('warehouse_origin_detail_id');

                $description = strtoupper($this->input->post('observation'));
                $warehouse_transfer_id = $this->input->post('destination_warehouse_id');
                $branch_office_transfer_id = $this->input->post('destination_branch_office_id');

                $today = date('Y-m-d H:i:s');
                /*Datos para registrar en la tabla salida de inventario*/
                $obj_output_inventory = [];
                $obj_output_inventory["fecha_registro"] = $today;
                $obj_output_inventory["fecha_modificacion"] = $today;
                $obj_output_inventory["sincronizado"] = 1;
                $obj_output_inventory["observacion"] = $description;
                $obj_output_inventory["estado"] = ACTIVO;
                $obj_output_inventory["tipo_salida_inventario_id"] = 8; // Tipo de salida por prestamo de pieza;
                $obj_output_inventory["sucursal_id"] = get_branch_id_in_session();
                $obj_output_inventory["nro_salida_inventario"] = $this->transfer_inventory_model->last_number_inventory_output();


                /*Datos para registrar en la tabla ingreso de inventario*/
                $obj_entry_inventario = [];
                $obj_entry_inventario["nombre"] = $description;
                $obj_entry_inventario["fecha_ingreso"] = $today;
                $obj_entry_inventario["fecha_registro"] = $today;
                $obj_entry_inventario["fecha_modificacion"] = $today;
                $obj_entry_inventario["estado"] = ACTIVO;
                $obj_entry_inventario["tipo_ingreso_inventario_id"] = 5; // Tipo de ingreso por prestamo de pieza
                $obj_entry_inventario["sucursal_id"] = $branch_office_transfer_id;
                $obj_entry_inventario["usuario_id"] = get_user_id_in_session();
                $obj_entry_inventario["nro_ingreso_inventario"] = $this->transfer_inventory_model->last_number_inventory_entry();


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
                $obj_transfer_output_inventary["almacen_destino_id"] = 0;
                $obj_transfer_output_inventary["sucursal_origen_id"] = intval(get_branch_id_in_session());
                $obj_transfer_output_inventary["almacen_origen_id"] = $warehouse_transfer_id;
                $obj_transfer_output_inventary["salida_inventario_id"] = floatval($inventory_output_inserted->id);
                $obj_transfer_output_inventary["sucursal_id"] = intval(get_branch_id_in_session());
                $obj_transfer_output_inventary["usuario_id"] = intval(get_user_id_in_session());
                $this->_insert_transfer_inventory_output($obj_transfer_output_inventary);
                $transfer_inventory_output_inserted = $this->_get_transfer_inventory_output($obj_transfer_output_inventary);

                $number_inventory_output = $this->transfer_inventory_model->last_number_transfer_inventory_output_by_branch_office_id(get_branch_id_in_session());
                $data_transfer_inventory_output_branch_office["sucursal_id"] = get_branch_id_in_session();
                $data_transfer_inventory_output_branch_office["traspaso_salida_id"] = $transfer_inventory_output_inserted->id;
                $data_transfer_inventory_output_branch_office["nro_traspaso_salida"] = $number_inventory_output;

                $this->transfer_inventory_model->insert_number_transfer_inventory_output_branch_office($data_transfer_inventory_output_branch_office);

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
                $obj_transfer_entry_inventary["almacen_destino_id"] = intval(0);
                $obj_transfer_entry_inventary["sucursal_origen_id"] = intval(get_branch_id_in_session());
                $obj_transfer_entry_inventary["almacen_origen_id"] = $warehouse_transfer_id;
                $obj_transfer_entry_inventary["ingreso_inventario_id"] = floatval($inventory_entry_inserted->id);
                $obj_transfer_entry_inventary["salida_inventario_id"] = floatval($inventory_output_inserted->id);
                $obj_transfer_entry_inventary["sucursal_id"] = intval($branch_office_transfer_id);
                $obj_transfer_entry_inventary["usuario_id"] = intval(get_user_id_in_session());
                $this->_insert_transfer_inventory_entry($obj_transfer_entry_inventary);
                $transfer_inventory_entry_inserted = $this->_get_transfer_inventory_entry($obj_transfer_entry_inventary);


                $number_inventory_entry = $this->transfer_inventory_model->last_number_transfer_inventory_entry_by_branch_office_id($branch_office_transfer_id);
                $data_inventory_entry_branch_office["sucursal_id"] = $branch_office_transfer_id;
                $data_inventory_entry_branch_office["traspaso_ingreso_id"] = $transfer_inventory_entry_inserted->id;
                $data_inventory_entry_branch_office["nro_traspaso_ingreso"] = $number_inventory_entry;
                $this->transfer_inventory_model->insert_number_transfer_inventory_entry_branch_office($data_inventory_entry_branch_office);

                $detalle_prestado = '';
                for ($index = 0; $index < $number_rows; $index++) {
                    /*registro de salida de inventario */
                    $obj_detail_output_inventory["cantidad"] = $quantity_ouput_array[$index];
                    $obj_detail_output_inventory["precio_costo"] = $price_cost_array[$index];
                    $obj_detail_output_inventory["precio_venta"] = $price_sale_array[$index];
                    $obj_detail_output_inventory["observacion"] = $description;
                    $obj_detail_output_inventory["salida_inventario_id"] = $inventory_output_inserted->id;
                    $obj_detail_output_inventory["inventario_id"] = $inventory_array[$index];
                    $this->inventory_model->_insert_detail_inventory_output($obj_detail_output_inventory);
                    $data_inventory_db = $this->inventory_model->get_inventory_product_detail($inventory_array[$index]);
                    $quantity_update = $data_inventory_db->cantidad - $quantity_ouput_array[$index];
                    $this->inventory_model->_update_stock_inventory($inventory_array[$index], $quantity_update);

                    // Registro de nuevo ingreso de inventario
                    $obj_detail_entry_inventary["codigo"] = $nro_lote_array[$index];
                    $obj_detail_entry_inventary["cantidad"] = $quantity_ouput_array[$index];
                    $obj_detail_entry_inventary["cantidad_ingresada"] = $quantity_ouput_array[$index];
                    $obj_detail_entry_inventary["precio_compra"] = $price_cost_array[$index];
                    $obj_detail_entry_inventary["precio_costo"] = $price_cost_array[$index];
                    $obj_detail_entry_inventary["precio_venta"] = $price_sale_array[$index];
                    $obj_detail_entry_inventary["fecha_ingreso"] = $today;
                    $obj_detail_entry_inventary["fecha_modificacion"] = $today;
                    $obj_detail_entry_inventary["estado"] = ACTIVO;
                    $obj_detail_entry_inventary["almacen_id"] = $warehouse_detail_id_origin[$index];
                    $obj_detail_entry_inventary["ingreso_inventario_id"] = $inventory_entry_inserted->id;
                    $obj_detail_entry_inventary["producto_id"] = $product_array[$index];
                    $detalle_prestado = $product_name_array[$index] . ' , ' . $detalle_prestado;

                    $this->inventory_model->_insert_inventory($obj_detail_entry_inventary);


                }

                $obj_transit = [];

                $obj_transit['observacion_devolucion'] = $description;
                $obj_transit['detalle_devolucion'] = $detalle_prestado;
                $obj_transit['fecha_transito_devolucion'] = $this->input->post('date_output');
                $obj_transit['fecha_registro_devolucion'] = date('Y-m-d H:i:s');
                $obj_transit['usuario_entregador_id_devolucion'] = $this->input->post('delivery_user_id');
                $obj_transit['usuario_solicitante_id_devolucion'] = $this->input->post('applicant_user');
                $obj_transit['sucursal_origen_id_devolucion'] = $this->input->post('origin_branch_office_id');
                $obj_transit['almacen_origen_id_devolucion'] = $this->input->post('origin_warehouse_id');
                $obj_transit['sucursal_destino_id_devolucion'] = $this->input->post('destination_branch_office_id');
                $obj_transit['almacen_destino_id_devolucion'] = $this->input->post('destination_warehouse_id');
                $obj_transit['ingreso_inventario_id_devolucion'] = $inventory_entry_inserted->id;
                $obj_transit['salida_inventario_id_devolucion'] = $inventory_output_inserted->id;
                $obj_transit['sucursal_id_devolucion'] = get_branch_id_in_session();
                $obj_transit['estado_transito'] = DEVUELTO;

                $this->db->where('id', $transit_id);
                $this->db->update('transito', $obj_transit);


                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response['success'] = FALSE;
                } else {
                    $this->db->trans_commit();
					$this->inventory_model->update_product_in_inventory_branch_office($this->inventory_model->get_detail_inventory($inventory_entry_inserted->id));
                    $response['success'] = TRUE;
                    $response['id'] = $transit_id;
                    $response['url_impression'] = 'transit/print_transit_entry';
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
    }

    public function _insert_transfer_inventory_output($inventory_output)
    {
        $this->db->insert('traspaso_salida', $inventory_output);
    }

    public function _get_transfer_inventory_output($transfer_inventory_output)
    {
        return $this->db->get_where('traspaso_salida', $transfer_inventory_output)->row();
    }

    public function _get_transit($transito)
    {
        return $this->db->get_where('transito', $transito)->row();
    }

    public function _get_detail_transit($detail_transito)
    {
        return $this->db->get_where('detalle_transito', $detail_transito)->row();
    }

    public function _insert_transfer_inventory_entry($inventory_entry)
    {
        $this->db->insert('traspaso_ingreso', $inventory_entry);
    }

    public function _get_transfer_inventory_entry($transfer_inventory_entry)
    {
        return $this->db->get_where('traspaso_ingreso', $transfer_inventory_entry)->row();
    }

    public function _insert_transit($transit)
    {
        $this->db->insert('transito', $transit);
    }

    public function _update_transit_detail_transit($transit_id, $detalle_prestamo)
    {
        $this->db->set('detalle_prestamo', $detalle_prestamo);
        $this->db->where('id', $transit_id);
        $this->db->update('transito');
    }

    public function _insert_detail_transit($detail_transit)
    {
        $this->db->insert('detalle_transito', $detail_transit);
    }

    public function last_number_transit_by_branch_office_id($branch_office_id)
    {
        $this->db->select_max('nro_transaccion');
        $this->db->where('sucursal_id_prestamo', $branch_office_id);
        $result = $this->db->get('transito');
        if ($result->num_rows() > 0) {
            $query = $result->row();
            return $query->nro_transaccion + 1;
        } else {
            return 1;
        }
    }

    public function get_transit_by_id($id)
    {
        $this->db->select('*')
            ->from('vista_transito')
            ->where('id', $id);
        return $this->db->get()->row();
    }

    public function get_detail_transit_by_transit_id($transit_id)
    {
        $this->db->select('*')
            ->from('detalle_transito')
            ->where('transito_id', $transit_id);
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('*')
            ->from('detalle_transito')
            ->where('id', $id);
        return $this->db->get()->row();
    }

    public function get_transit_borrowed_piece($params = array())
    {

        /* Se cachea la informacion que se arma en query builder*/
        $this->db->start_cache();
        $this->db->select('*')
            ->from('transito')
            ->where('estado', ACTIVO)
            ->where('estado_transito', PRESTADO)
            ->where('sucursal_id_prestamo', get_branch_id_in_session())
            ->where('usuario_solicitante_id_prestamo', get_user_id_in_session());

        if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
            $this->db->order_by('id', 'asc');
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
            if ($params['order'] != '' || $params['column'] != '') {
                $this->db->order_by($params['column'], $params['order']);
            } else {
                $this->db->order_by('id', 'asc');
            }
        } else {
            $this->db->order_by('id', 'asc');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('lower(observacion_prestamo)', strtolower($params['search']));
            $this->db->or_like('lower(detalle_prestamo)', strtolower($params['search']));
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

    public function register_transit_approved()
    {
        $response = array(
            'success' => FALSE,
            'login' => FALSE
        );

        if (verify_session()) {

                $transit_id = $this->input->post('transit_id');

                $obj_transit = [];
                $obj_transit['estado_transito'] = POR_APROBAR;

                $this->db->where('id', $transit_id);
                $this->db->update('transito', $obj_transit);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response['success'] = FALSE;
                } else {
                    $this->db->trans_commit();
                    $response['success'] = TRUE;
                }

        } else {
            $response['login'] = TRUE;
        }

        return $response;
    }

}
