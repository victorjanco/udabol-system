<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 01/08/2017
 * Time: 10:46 AM
 */
class Reception_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('user_model');
        $this->load->model('order_work_model');
        $this->load->model('company_model');
        $this->load->model('failure_model');
        $this->load->model('solution_model');
        $this->load->model('reference_model');
        $this->load->model('warehouse_model');
        $this->load->model('reception_payment_model');
    }

    public function get_receptions_list($params = array())
    {
        $this->db->start_cache();
        $this->db->select('*');
        $this->db->from('vista_lista_recepcion');
        $this->db->where('sucursal_id', get_branch_id_in_session());
        if ($params['days'] == 2) {
            $this->db->where('fecha_registro <=', $this->substrack_date());
            $this->db->where('estado_trabajo =', RECEPCIONADO);
        } else {
            if (isset($params['reception_date_start']) && $params['reception_date_start'] != '') {
                $this->db->where('DATE(fecha_registro) >=', $params['reception_date_start']);
            }

            if (isset($params['reception_date_end']) && $params['reception_date_end'] != '') {
                $this->db->where('DATE(fecha_registro) <=', $params['reception_date_end']);
            }
        }

        if ($params['reception_state'] != '') {
            $this->db->where('estado_trabajo', $params['reception_state']);
        }

        if ($params['reception_number'] != '') {
            $this->db->where('codigo_recepcion', $params['reception_number']);
        }

        if ($params['reception_brand'] != '') {
            $this->db->where('nombre_marca', $params['reception_brand']);
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
            if ($params['column'] == 'codigo_recepcion') {
                $this->db->order_by('id', $params['order']);
            } else {
                $this->db->order_by($params['column'], $params['order']);
            }
        } else {
            $this->db->order_by('id', 'DESC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('lower(nombre)', strtolower($params['search']));
            $this->db->or_like('lower(imei)', strtolower($params['search']));
            $this->db->or_like('lower(nombre_modelo)', strtolower($params['search']));
            $this->db->group_end();
            $this->db->order_by('id', 'DESC');
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

    public function get_receptions_list_delivered($params = array())
    {
        $this->db->start_cache();
        $this->db->select('*')
            ->from('vista_lista_recepcion')
            ->where('sucursal_id', get_branch_id_in_session())
            ->where('estado_trabajo', ENTREGADO);

        if (isset($params['delivered_date_start']) && $params['delivered_date_start'] != '') {
            $this->db->where('DATE(fecha_entrega) >=', $params['delivered_date_start']);
        }

        if (isset($params['delivered_date_end']) && $params['delivered_date_end'] != '') {
            $this->db->where('DATE(fecha_entrega) <=', $params['delivered_date_end']);
        }

        $this->db->stop_cache();

        $records_total = count($this->db->get()->result_array());
        // Concatenar parametros enviados (solo si existen)
        if (array_key_exists('start', $params) && array_key_exists('limit', $params)) {
            $this->db->limit($params['limit']);
            $this->db->offset($params['start']);
        }

        if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
            if ($params['column'] == 'codigo_recepcion') {
                $this->db->order_by('id', $params['order']);
            } else {
                $this->db->order_by($params['column'], $params['order']);
            }
        } else {
            $this->db->order_by('id', 'DESC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('lower(codigo_recepcion)', strtolower($params['search']));
            $this->db->or_like('lower(nombre)', strtolower($params['search']));
            $this->db->or_like('lower(imei)', strtolower($params['search']));
            $this->db->or_like('lower(nombre_marca)', strtolower($params['search']));
            $this->db->or_like('lower(nombre_modelo)', strtolower($params['search']));
            $this->db->group_end();
            $this->db->order_by('id', 'DESC');
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
            'params' => $params
        );
        return $json_data;
    }

    public function get_reception_by_id($id_reception)
    {
        $this->db->select('r.id, r.numero_ticket, e.imei, e.imei2, e.numero_telefono, mod.nombre as nombre_comercial,
         c.ci, c.nombre, r.usuario_id, c.telefono1, c.telefono2, r.codigo_recepcion, 
        c.direccion, r.fecha_registro, mar.nombre AS nombre_marca, r.codigo_seguridad, r.accesorio_dispositivo,
        r.observacion_recepcion,e.cliente_id, r.sucursal_id, r.garantia,c.tipo_cliente,r.monto_total');
        $this->db->from('cliente c, equipo_cliente e, modelo_recepcion mod, recepcion r, marca_recepcion mar');
        $this->db->where('c.id = e.cliente_id');
        $this->db->where('e.modelo_id = mod.id');
        $this->db->where('e.marca_id = mar.id');
        $this->db->where('e.id = r.equipo_cliente_id');
        $this->db->where('r.id', $id_reception);
        $data = $this->db->get()->row();
        return $data;
    }

    public function get_reception_enable_by_customer($id_customer)
    {
        $this->db->select('*')
            ->from('vista_recepcion_clientes')
            ->where('cliente_id', $id_customer)
            ->where('estado_trabajo!=5');
        return $this->db->get()->result();
    }

    public function show_image($reception_id)
    {
        $data = [];
        foreach ($reception_id as $reception_id_list) :
            $this->db->select('nombre, url')
                ->from('imagen_recepcion')
                ->where('recepcion_id', $reception_id_list->recepcion_id);
            $resultado = $this->db->get();
            if ($resultado->num_rows() > 0) {
                /*$data = array(
                    $reception_id_list => $resultado->result(),
                    'imagen' => 'CON-IMAGEN'
                );*/
                $data += [$reception_id_list->recepcion_id => $resultado->result()];
            } else {
                /*$data = array(
                    'imagen' => 'SIN-IMAGEN'
                );*/
                $data += [$reception_id_list->recepcion_id => 'SIN-IMAGEN'];
            }
        endforeach;
        return $data;
    }

    public function reception_completed_by_customer($id_customer)
    {
        $this->db->select('o.*')
            ->from(' recepcion r, equipo_cliente e, cliente c, orden_trabajo o')
            ->where('r.equipo_cliente_id= e.id')
            ->where('o.recepcion_id= r.id')
            ->where('e.cliente_id= c.id')
            ->where('o.estado', get_state_abm('ACTIVO'))
            ->where('o.estado_trabajo', CONCLUIDO)
            ->where('e.cliente_id', $id_customer);
        $this->db->group_by("o.id");
        return $this->db->get()->result();
    }

    public function get_customer_device_by_code_reception($code_reception)
    {
        $this->db->select('e.*,m.codigo as codigo_modelo, m.nombre nombre_modelo, ma.nombre nombre_marca')
            ->from(' recepcion r, equipo_cliente e, orden_trabajo o, modelo m, marca ma')
            ->where('r.equipo_cliente_id= e.id')
            ->where('o.recepcion_id= r.id')
            ->where('e.modelo_id= m.id')
            ->where('m.marca_id= ma.id')
            ->where('r.sucursal_id', get_branch_id_in_session())
            ->where('o.codigo_trabajo', $code_reception);
        $response = $this->db->get();
        if ($response->num_rows() > 0) {
            return $response->row_array();
        } else {
            $response = array(
                'codigo_modelo' => '',
                'nombre_modelo' => '',
                'nombre_marca' => ''
            );
            return $response;
        }

    }

    public function register_reception()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE,
            'receipt_payment_id' => ''
        );
        try {
            if (verify_session()) {
                $this->load->model('customer_model');
                $this->form_validation->set_rules('id_customer', 'Cliente', 'trim|required');/* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
                $this->form_validation->set_rules('ci_customer', 'ci', 'trim|required');/* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
                $this->form_validation->set_rules('name_customer', 'nombre cliente', 'trim|required');
                $this->form_validation->set_rules('devices_select', 'dispositivo', sprintf("trim|required|in_list[%s]", implode_array($this->customer_model->get_customer_devices($this->input->post('id_customer')), 'producto_cliente_id')));
                // $this->form_validation->set_rules('code_segurity', 'codigo', 'trim|required');
                $this->form_validation->set_rules('failure_select', 'fallas', sprintf("trim|required|in_list[%s]", implode_array($this->failure_model->get_failure_enable(), 'id')));
                $this->form_validation->set_rules('solution_select', 'solucion', sprintf("trim|required|in_list[%s]", implode_array($this->solution_model->get_solution_enable(), 'id')));
                // $this->form_validation->set_rules('reference_select', 'referencia', sprintf("trim|required|in_list[%s]", implode_array($this->reference_model->get_references_enable(), 'id')));
                $this->form_validation->set_rules('service_type', 'tipo de servicio', 'trim');
                $this->form_validation->set_rules('service', 'servicio', 'trim');
                $this->form_validation->set_rules('asigned_user', 'USUARIO', sprintf("trim|required|in_list[%s]", implode_array($this->user_model->get_list_users(), 'id')));
                //    $this->form_validation->set_rules('pago', 'pago', 'trim');
                $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');
    
                if ($this->form_validation->run() === true) {
                    $this->db->trans_start();
                    $sesion = $this->session->userdata('user');
    
                    $codigo_recepcion = $this->get_codigo_recepcion();
                    /** OBTENERMOS VALORES DE LOS INPUT **/
                    $tipo_de_garantia = $this->input->post('warranty_select');
                //    $monto_total = $this->input->post('total_amount_service') + $this->input->post('total_amount_product');
                    $reception_total = $this->input->post('reception_total');
                    $reception_discount = $this->input->post('reception_discount');
                    $reception_total_payment = $this->input->post('reception_total_payment');
                    $reception_payment = $this->input->post('reception_payment');
                    $reception_balance = $this->input->post('reception_balance');
    
                    $numero_codigo_recepcion = $this->get_numero_codigo_recepcion();
                    $obj_reception['codigo_recepcion'] = strval($numero_codigo_recepcion);
                    $obj_reception['codigo_seguridad'] = $this->input->post('code_segurity');
                    $obj_reception['garantia'] = $tipo_de_garantia;
                    $obj_reception['accesorio_dispositivo'] = text_format($this->input->post('accessories_select'));
                    $obj_reception['observacion_recepcion'] = text_format($this->input->post('observation_select'));
                    $obj_reception['prioridad'] = $this->input->post('priority');
                    $obj_reception['numero_ticket'] = $this->input->post('ticket');
                    $obj_reception['fecha_registro'] = date('Y-m-d H:i:s');
                    $obj_reception['fecha_modificacion'] = date('Y-m-d H:i:s');
                    $obj_reception['monto_total'] = $reception_total;
                    $obj_reception['galeria'] = 0;
                    $obj_reception['estado'] = ACTIVO;
                    $obj_reception['equipo_cliente_id'] = $this->input->post('devices_select');
                    $obj_reception['sucursal_id'] = get_branch_id_in_session();
                    $obj_reception['usuario_id'] = get_user_id_in_session();
                    $obj_reception['usuario_asignado_id'] = $this->input->post('asigned_user');
                    $obj_reception['referencia_id'] = $this->input->post('reference_select');
                    $obj_reception['color_id'] = $this->input->post('color_select');
                    $obj_reception['contacto'] = $this->input->post('telefono2_customer');
                    
    
                    $this->db->insert('recepcion', $obj_reception);
                    $reception_inserted = $this->_get_reception($obj_reception);
                    $reception_id = $reception_inserted->id;

                    if ($tipo_de_garantia == 0 || $tipo_de_garantia == 2) {
                        $pago_anticipado = $this->input->post('reception_payment');
                        if ($pago_anticipado > 0) {
                            $payment['observacion'] = 'Primer pago ' . 'O.T.' . $numero_codigo_recepcion . ' en fecha' . date('d/m/Y');
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
                            $this->db->insert('pago_recepcion', $payment);
                            $response['receipt_payment_id'] = $this->get_receipt_payment($payment)->id;
                        }
                    }
                    $obj_reception_branch_office['sucursal_id'] = get_branch_id_in_session();/*REGISTRAMOS EN recepcion_sucursal */
                    $obj_reception_branch_office['recepcion_id'] = $reception_id;
                    $obj_reception_branch_office['nro_recepcion'] = $numero_codigo_recepcion;
                    $this->db->insert('recepcion_sucursal', $obj_reception_branch_office);
    
    
                    $orden['codigo_trabajo'] = 'O.T.' . $numero_codigo_recepcion;/*/ Insertamos el codigo de lassh root orden de trabajo*/
                    $orden['monto_subtotal'] = $reception_total;
                    $orden['descuento'] = $reception_discount;
                    $orden['monto_total'] = $reception_total_payment;
                    $orden['monto_pagado'] = $reception_payment;
                    $orden['monto_deuda'] = $orden['monto_total'] - $orden['monto_pagado'];
                    $orden['monto_saldo'] = $orden['monto_total'] - $orden['monto_pagado'];
                    $orden['fecha_registro'] = date('Y-m-d H:i:s');
                    $orden['fecha_modificacion'] = date('Y-m-d H:i:s');
                    $orden['estado_deuda'] = 1;
                    $orden['progreso'] = 0;
                    $orden['estado_trabajo'] = RECEPCIONADO;
                    $orden['estado'] = ACTIVO;
                    $orden['recepcion_id'] = $reception_id;
                    $orden['sucursal_id'] = get_branch_id_in_session();
                    $orden['usuario_id'] = get_user_id_in_session();
    
                    $this->load->model('order_work_model');
                    $this->order_work_model->register_order($orden);
                    $order_work_id = $this->db->get_where('orden_trabajo', $orden)->row()->id;/* Recuperamos el id de la orden de trabajo registrado */
    
                    foreach ($this->input->post('failures') as $failure_id) {// Insertamos las fallas seleccionadas
                        $this->db->insert('falla_recepcion', array('estado' => 0, 'recepcion_id' => $reception_id, 'falla_id' => $failure_id));
                    }
    
                    foreach ($this->input->post('solutions') as $solution_id) {/*// Insertamos las soluciones seleccionadas*/
                        $this->db->insert('solucion_recepcion', array('estado' => 0, 'recepcion_id' => $reception_id, 'solucion_id' => $solution_id));
                    }
    
                    $detail_table_data = $this->input->post('detail_table');
                    if (isset($detail_table_data)) {
                        foreach ($this->input->post('detail_table') as $detail_reception) {/*// Insertamos el detalle de la recepcion*/
                            $this->db->insert('detalle_recepcion_servicio', array(
                                'precio_costo' => $detail_reception['cost'],
                                'precio_venta' => $detail_reception['price'],
                                'estado' => 0, 'recepcion_id' => $reception_id,
                                'servicio_id' => $detail_reception['id']));
                            $this->db->insert('detalle_orden_trabajo_servicio',
                                array('precio_servicio' => $detail_reception['price'],
                                    'estado' => 0,
                                    'orden_trabajo_id' => $order_work_id,
                                    'servicio_id' => $detail_reception['id']));
                        }
                    }
    
                    $detail_table_data_product = $this->input->post('detail_table_product');
                    if (isset($detail_table_data_product)) {
                        foreach ($this->input->post('detail_table_product') as $detail_product) {/*registro de detalle de productos de la orden*/
                            $detalle_producto_trabajo['estado'] = ACTIVO;
                            $detalle_producto_trabajo['cantidad'] = $detail_product['quantity'];
                            $detalle_producto_trabajo['precio_costo'] = $detail_product['price_product'];
                            $detalle_producto_trabajo['precio_venta'] = $detail_product['price_sale'];
                            $detalle_producto_trabajo['orden_trabajo_id'] = $order_work_id;
                            $detalle_producto_trabajo['producto_id'] = $detail_product['product_id'];
                            $this->db->insert('detalle_producto_trabajo', $detalle_producto_trabajo);
                        }
                    }
    
                    $this->order_work_model->register_order_work_history($this->order_work_model->get_order_by_reception_id($reception_id)->id, 'CREACION DE LA RECEPCION', 'ESTA RECEPCION FUE CREADA POR: ' . get_user_name_in_session());
    
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $response['success'] = false;
                    } else {
                        $this->db->trans_commit();
                        $response['success'] = TRUE;
                        $response['messages'] = $reception_id;
                        // Llamamos el metodo que crea el direcctorio para la galeria de esta recepcion
                        // $this->create_directory($reception_id); ///problema ---------------------------------
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

    public function edit_reception()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE,
            'receipt_payment_id' => ''
        );

        if (verify_session()) {

            $this->load->model('failure_model');
            $this->load->model('solution_model');
            $this->load->model('customer_model');

            /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
            $this->form_validation->set_rules('ci_customer', 'ci', 'trim|required');
            $this->form_validation->set_rules('name_customer', 'nombre cliente', 'trim|required');
            $this->form_validation->set_rules('devices_select', 'dispositivo', sprintf("trim|required|in_list[%s]", implode_array($this->customer_model->get_customer_devices($this->input->post('id_customer')), 'producto_cliente_id')));
            $this->form_validation->set_rules('code_segurity', 'codigo', 'trim|required');
            $this->form_validation->set_rules('failure_select', 'fallas', sprintf("trim|required|in_list[%s]", implode_array($this->failure_model->get_failure_enable(), 'id')));
            $this->form_validation->set_rules('solution_select', 'solucion', sprintf("trim|required|in_list[%s]", implode_array($this->solution_model->get_solution_enable(), 'id')));
            $this->form_validation->set_rules('reference_select', 'referencia', sprintf("trim|required|in_list[%s]", implode_array($this->reference_model->get_references_enable(), 'id')));
            $this->form_validation->set_rules('service_type', 'tipo de servicio', 'trim');
            $this->form_validation->set_rules('service', 'servicio', 'trim');
            $this->form_validation->set_rules('asigned_user', 'USUARIO', sprintf("trim|required|in_list[%s]", implode_array($this->user_model->get_list_users(), 'id')));
            //$this->form_validation->set_rules('price', 'precio', 'trim|required');

            $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');

            if ($this->form_validation->run() === true) {

                $this->db->trans_start();
                $sesion = $this->session->userdata('user');
                $reception_id = $this->input->post('id_reception');

                // Insertamos las fallas seleccionadas
                $this->db->delete('falla_recepcion', array("recepcion_id" => $reception_id));/*  antes de registrar, eliminamos to.do. el detalle*/

                foreach ($this->input->post('failures') as $failure_id) {
                    $this->db->insert('falla_recepcion', array('estado' => 0, 'recepcion_id' => $reception_id, 'falla_id' => $failure_id));
                }
                // Insertamos las soluciones seleccionadas
                $this->db->delete('solucion_recepcion', array("recepcion_id" => $reception_id));/*  antes de registrar, eliminamos to.do. el detalle*/

                foreach ($this->input->post('solutions') as $solution_id) {
                    $this->db->insert('solucion_recepcion', array('estado' => 0, 'recepcion_id' => $reception_id, 'solucion_id' => $solution_id));
                }

                $this->db->delete('detalle_recepcion_servicio', array("recepcion_id" => $reception_id));/*  antes de registrar, eliminamos to.do. el detalle*/

                $detail_table = $this->input->post('detail_table');
                if (isset($detail_table)) {
                    foreach ($this->input->post('detail_table') as $detail_reception) {
                        $this->db->insert('detalle_recepcion_servicio', array('precio_costo' => $detail_reception['cost'], 'precio_venta' => $detail_reception['price'], 'estado' => 0, 'recepcion_id' => $reception_id, 'servicio_id' => $detail_reception['id']));
                    }
                }


                $this->db->delete('detalle_orden_trabajo_servicio', array("orden_trabajo_id" => $this->order_work_model->get_order_by_reception_id($reception_id)->id));/*  antes de registrar, eliminamos to.do. el detalle*/
                $detail_service_work = $this->input->post('detail_table');
                $total_amount = 0;
                if (isset($detail_service_work)) {
                    foreach ($this->input->post('detail_table') as $detail_reception) {
                        $total_amount = $total_amount + $detail_reception['price'];
                        $this->db->insert('detalle_orden_trabajo_servicio', array(
                            'precio_servicio' => $detail_reception['price'],
                            'estado' => 0,
                            'orden_trabajo_id' => $this->order_work_model->get_order_by_reception_id($reception_id)->id,
                            'servicio_id' => $detail_reception['id']));
                    }
                }

                $order_work_id = $this->db->get_where('orden_trabajo', array('recepcion_id' => $reception_id))->row()->id;/* Recuperamos el id de la orden de trabajo registrado */
                $detail_product_work = $this->input->post('detail_table_product');
                $this->db->delete('detalle_producto_trabajo', array('orden_trabajo_id' => $order_work_id));
                if (isset($detail_product_work)) {
                    foreach ($detail_product_work as $detail_product) {/*registro de detalle de productos de la orden*/
                        $total_amount = $total_amount + ($detail_product['price_product'] * $detail_product['quantity']);
                        $detalle_producto_trabajo['estado'] = ACTIVO;
                        $detalle_producto_trabajo['cantidad'] = $detail_product['quantity'];
                        $detalle_producto_trabajo['precio_costo'] = $detail_product['price_product'];
                        $detalle_producto_trabajo['precio_venta'] = $detail_product['price_sale'];
                        $detalle_producto_trabajo['orden_trabajo_id'] = $order_work_id;
                        $detalle_producto_trabajo['producto_id'] = $detail_product['product_id'];
                        $this->db->insert('detalle_producto_trabajo', $detalle_producto_trabajo);
                    }
                }

                $tipo_de_garantia = $this->input->post('warranty_select');
//                $monto_total = $this->input->post('total_amount_service') + $this->input->post('total_amount_product');
                $reception_total = $this->input->post('reception_total');
                $reception_discount = $this->input->post('reception_discount');
                $reception_total_payment = $this->input->post('reception_total_payment');
                $reception_payment = $this->input->post('reception_payment');
                $reception_balance = $this->input->post('reception_balance');

                if ($tipo_de_garantia == 0 || $tipo_de_garantia == 2) {
                    $pago_anticipado = $this->input->post('reception_payment');
                    if ($pago_anticipado > 0) {
                        if ($this->exist_payment($reception_id)) {
                            $payment['subtotal'] = $reception_total;
                            $payment['descuento'] = $reception_discount;
                            $payment['total'] = $reception_total_payment;
                            $payment['pago'] = $reception_payment;
                            $payment['pagos_anteriores'] = 0;
                            $payment['saldo'] = $reception_balance;
                            $payment['fecha_modificacion'] = date('Y-m-d H:i:s');
                            $payment['usuario_id_modificacion'] = get_user_id_in_session();

                            $first_payment = $this->get_first_payment($reception_id);
                            $this->db->where('id', $first_payment);
                            $this->db->update('pago_recepcion', $payment);
                        } else {
                            $numero_codigo_recepcion = $this->get_reception_by_id($reception_id)->codigo_recepcion;
                            $payment['observacion'] = 'Primer pago ' . 'O.T.' . $numero_codigo_recepcion . ' en fecha ' . date('d/m/Y');
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
                            $this->db->insert('pago_recepcion', $payment);
                            $response['receipt_payment_id'] = $this->get_receipt_payment($payment)->id;
                        }

                    }
                }

                /** OBTENERMOS VALORES DE LOS INPUT **/
                $obj_reception['codigo_seguridad'] = $this->input->post('code_segurity');
                $obj_reception['garantia'] = $this->input->post('warranty_select');
                $obj_reception['accesorio_dispositivo'] = $this->input->post('accessories_select');
                $obj_reception['observacion_recepcion'] = $this->input->post('observation_select');
                $obj_reception['prioridad'] = $this->input->post('priority');
                $obj_reception['numero_ticket'] = $this->input->post('ticket');
                $obj_reception['fecha_modificacion'] = date('Y-m-d H:i:s');
                $obj_reception['monto_total'] = $total_amount;
                $obj_reception['estado'] = get_state_abm('ACTIVO');
                $obj_reception['equipo_cliente_id'] = $this->input->post('devices_select');
                $obj_reception['usuario_asignado_id'] = $this->input->post('asigned_user');
                $obj_reception['referencia_id'] = $this->input->post('reference_select');
                $obj_reception['color_id'] = $this->input->post('color_select');
                //echo  $obj_reception;
                $this->db->update('recepcion', $obj_reception, array("id" => $reception_id));

                // ACTUALIZAMOS la orden de trabajo
                $orden['monto_subtotal'] = $reception_total;
                $orden['descuento'] = $reception_discount;
                $orden['monto_total'] = $reception_total_payment;
                $orden['monto_pagado'] = $reception_payment;
                $orden['fecha_modificacion'] = date('Y-m-d H:i:s');
                $orden['estado_deuda'] = 1;
                $orden['estado_trabajo'] = RECEPCIONADO;
                $orden['recepcion_id'] = $reception_id;

                $this->db->update('orden_trabajo', $orden, array("recepcion_id" => $reception_id));


                $this->order_work_model->register_order_work_history($this->order_work_model->get_order_by_reception_id($reception_id)->id, 'EDICION DE RECEPCION', 'SE EDITO LA RECEPCION POR: ' . get_user_name_in_session());
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response['success'] = false;
                } else {
                    $this->db->trans_commit();
                    $response['success'] = TRUE;
                    $response['messages'] = $reception_id;
                    /*$response['file_url_reception'] = DIRECTORY_RAIZ_PATH.$reception_id;
                    $response['file_url'] = DIRECTORY_RAIZ_PATH;*/
                    // Llamamos el metodo que crea el direcctorio para la galeria de esta recepcion
                    // $this->create_directory($reception_id);
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

    /* ---------------------------------------------
     * Metodo que crea el direcctorio de las
     * galerias para las recepciones
     * --------------------------------------------
     * */
    private function create_directory($reception_id)
    {
        $directory = DIRECTORY_RAIZ_PATH;

        $file_directory = DIRECTORY_RAIZ_PATH . $reception_id;

        //verificamos si la carpeta galeria_recepcion se creo
        if (!is_dir($directory)) {
            mkdir($directory, 0777);
        }

        //verificamos si el directorio ya existe
        if (!is_dir($file_directory)) {
            mkdir($file_directory, 0777);
        }
    }

    public function get_only_recepetion_by_id($reception_id)
    {
        $this->db->select('r.id, r.codigo_recepcion, r.galeria, r.monto_total, r.prioridad, r.estado, c.ci, c.nombre,  r.contacto,
                           c.ci, r.codigo_seguridad, c.id AS cliente_id, c.telefono1, c.telefono2, r.garantia, r.observacion_recepcion, r.numero_ticket,
                           r.accesorio_dispositivo, r.equipo_cliente_id, ordn.id as orden_trabajo_id, r.usuario_asignado_id, ordn.codigo_trabajo,
                           ref.nombre AS nombre_referencia, r.referencia_id')
            ->from('recepcion r, cliente c, equipo_cliente eqc, orden_trabajo ordn, referencia ref')
            ->where('r.equipo_cliente_id = eqc.id')
            ->where('eqc.cliente_id = c.id')
            ->where('r.id = ordn.recepcion_id')
            ->where('r.referencia_id = ref.id')
            ->where('r.id', $reception_id);
        return $this->db->get()->row();
    }

    public function get_reception_detail_service($reception_id)
    {
        $this->db->select('dts.precio_costo, dts.precio_venta, dts.servicio_id, serv.nombre AS nombre_servicio, 
        tips.nombre AS nombre_tipo_servicio, tips.id AS tipo_servicio_id')
            ->from('detalle_recepcion_servicio dts, servicio serv, tipo_servicio tips')
            ->where('dts.recepcion_id', $reception_id)
            ->where('dts.servicio_id = serv.id')
            ->where('tips.id = serv.tipo_servicio_id');
        return $this->db->get()->result();
    }

    public function get_id_reception_detail_solution($reception_id)
    {
        $this->db->select('fall.id')
            ->from('falla_recepcion frec, falla fall')
            ->where('frec.recepcion_id', $reception_id)
            ->where('frec.falla_id = fall.id');
        return $this->db->get()->result();
    }

    /*Funcion para desabilitar*/
    public function disable()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'recepcion',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion que retorna el siguiente codigodo de recepcion    */
    public function get_codigo_recepcion()
    {
        /* datos de la sucursal */
        $this->db->select('*')
            ->from('sucursal')
            ->where('id', get_branch_id_in_session());
        $data_branch_office = $this->db->get()->row();
        return $data_branch_office->codigo;
    }

    /*Funcion que retorna el siguiente codigodo de recepcion    */
    public function get_numero_codigo_recepcion()
    {
        /* Obtenemos el siquieten numero de recepcion   */
        $this->db->select('MAX(nro_recepcion) AS nro_recepcion')
            ->from('recepcion_sucursal')
            ->where('sucursal_id', get_branch_id_in_session());
        $data_reception = $this->db->get()->row();
        $numero = 0;
        if ($data_reception->nro_recepcion == '') {
            $numero = $numero + 1;
        } else {
            $numero = $data_reception->nro_recepcion + 1;
        }

        return $numero;
    }

    public function getdata_printer_report($reception_id)
    {
        $order_work_id = $this->order_work_model->get_order_by_reception_id($reception_id)->id;
        $data_reception = $this->get_reception_by_id($reception_id);
        $data_order_work = $this->order_work_model->get_order_work_id($order_work_id);
        $data['reception'] = $data_reception;
        $data['order_work'] = $data_order_work;
        $data['user'] = $this->user_model->get_user_id($data_reception->usuario_id);
        $data['user_technical'] = $this->user_model->get_user_id($data_order_work->asignado_usuario_id);
        $data['user_diagnose'] = $this->user_model->get_user_id($data_order_work->perito_usuario_id);
        $data['payments'] = $this->reception_payment_model->get_payment_by_reception_id($reception_id);
        $data['payment_observation'] = "Pago para la entrega de equipo de " .  $data['order_work']->codigo_trabajo . ' en fecha ' . date('Y-m-d');

        $data_detail_solution = $this->solution_model->get_solution_order_work_by_order_work_id($order_work_id);
        if (count($data_detail_solution) < 1) {
            $data_detail_solution = $this->solution_model->get_solution_reception_by_reception_id($reception_id);
        }

        $data_detail_failure = $this->failure_model->get_failure_order_work_by_order_work_id($order_work_id);
        if (count($data_detail_failure) < 1) {
            $data_detail_failure = $this->failure_model->get_failure_reception_by_reception_id($reception_id);
        }

        $data['detail_solutions'] = $data_detail_solution;
        $data['detail_failures'] = $data_detail_failure;
        $data['detail_services'] = $this->order_work_model->get_detail_service_by_order_work_id($order_work_id);
        $data['detail_products'] = $this->order_work_model->get_detail_product_by_order_work_id($order_work_id);
        $data['detail_recycled'] = $this->get_detail_recycled_by_order_work_id($order_work_id);
        $data['company'] = $this->company_model->get_company();
        $data['branch_office'] = $this->office_model->get_branch_office_id($data_reception->sucursal_id);
        return $data;
    }

    public function get_reception_now_service($key)
    {

    }

    public function get_html_state($state, $reception_id, $type_option)
    {
        $reception = $this->order_work_model->get_order_by_reception_id($reception_id);

        // Para seleccionar el almacen de que garantia de recepcion
        $warranty = $this->get_reception_by_id($reception_id)->garantia;

        $state_work = $reception->estado_trabajo;
        $html = '<input id="inp_reception_id" type="text" hidden value="' . $reception_id . '">' .
            '<input id="warranty" type="text" hidden value="' . $warranty . '">';
        switch ($type_option):
            case TYPE_STATES_RECEPTION:
                switch ($state_work) {
                    case RECEPCIONADO:
                        $html = $html . $this->get_html_RECEPCIONADO(RECEPCIONADO, $reception_id);
                        break;
                    case REPARADO:
                        $html .= $this->get_html_REPARADO(REPARADO, $reception_id);
                        // $html .= $this->get_html_aprobado(REPARADO, $reception_id);
                        $html .= $this->get_html_entregado(REPARADO, $reception_id);
                        // $html .= $this->get_html_en_mora(REPARADO, $reception_id);
                        // $html .= $this->get_html_espera_stock(REPARADO, $reception_id);
                        // $html .= $this->get_html_entregado_espera_stock(REPARADO, $reception_id);
                        // $html .= $this->get_html_no_aprobado(REPARADO, $reception_id);
                        break;
                    case APROBADO:
                        $html .= $this->get_html_aprobado(APROBADO, $reception_id);
                        $html .= $this->get_html_en_mora(APROBADO, $reception_id);
                        // $html .= $this->get_html_espera_stock(APROBADO, $reception_id);
                        // $html .= $this->get_html_entregado_espera_stock(APROBADO, $reception_id);
                        break;
                    case EN_PROCESO:
                        $html .= $this->get_html_en_proceso(EN_PROCESO, $reception_id);
                        $html .= $this->get_html_concluido(EN_PROCESO, $reception_id);
                        // $html .= $this->get_html_espera_stock(EN_PROCESO, $reception_id);
                        // $html .= $this->get_html_entregado_espera_stock(EN_PROCESO, $reception_id);
                        $html .= $this->get_html_en_mora(EN_PROCESO, $reception_id);
                        break;
                    case CONCLUIDO:
                        $html .= $this->get_html_concluido(CONCLUIDO, $reception_id);
                        $html .= $this->get_html_entregado(CONCLUIDO, $reception_id);
                        break;
                    case ENTREGADO:
                        $html .= $this->get_html_entregado(ENTREGADO, $reception_id);
                        break;
                    case EN_MORA:
                        $html .= $this->get_html_en_mora(EN_MORA, $reception_id);
                        $html .= $this->get_html_en_proceso(EN_MORA, $reception_id);
                        $html .= $this->get_html_concluido(EN_MORA, $reception_id);
                        // $html .= $this->get_html_espera_stock(EN_MORA, $reception_id);
                        // $html .= $this->get_html_entregado_espera_stock(EN_MORA, $reception_id);
                        break;
                    case ESPERA_STOCK:
                        $html .= $this->get_html_en_proceso(ESPERA_STOCK, $reception_id);
                        $html .= $this->get_html_concluido(ESPERA_STOCK, $reception_id);
                        $html .= $this->get_html_en_mora(ESPERA_STOCK, $reception_id);
                        // $html .= $this->get_html_espera_stock(ESPERA_STOCK, $reception_id);
                        // $html .= $this->get_html_entregado_espera_stock(ESPERA_STOCK, $reception_id);
                        break;
                    case ENTREGADO_ESPERA_STOCK:
                        $html .= $this->get_html_aprobado(ENTREGADO_ESPERA_STOCK, $reception_id);
                        $html .= $this->get_html_en_proceso(ENTREGADO_ESPERA_STOCK, $reception_id);
                        break;
                    case SIN_SOLUCION:
                        $html .= $this->get_html_entregado(SIN_SOLUCION, $reception_id);
                        $html .= $this->get_html_sin_solucion(SIN_SOLUCION, $reception_id);
                        break;
                    case NO_APROBADO:
                        $html .= $this->get_html_entregado(NO_APROBADO, $reception_id);
                        $html .= $this->get_html_no_aprobado(NO_APROBADO, $reception_id);
                        break;
                }
                break;
            case TYPE_STATES_SERVICE:
                switch ($state_work):
                    case APROBADO:
                        $html .= $this->get_html_en_mora(APROBADO, $reception_id);
                        $html .= $this->get_html_en_proceso(APROBADO, $reception_id);
                        // $html .= $this->get_html_espera_stock(APROBADO, $reception_id);
                        $html .= $this->get_html_concluido(APROBADO, $reception_id);
                        break;
                    case EN_MORA:
                        $html .= $this->get_html_en_mora(EN_MORA, $reception_id);
                        $html .= $this->get_html_en_proceso(EN_MORA, $reception_id);
                        // $html .= $this->get_html_espera_stock(EN_MORA, $reception_id);
                        $html .= $this->get_html_concluido(EN_MORA, $reception_id);
                        break;
                    case ESPERA_STOCK:
                        $html .= $this->get_html_en_mora(ESPERA_STOCK, $reception_id);
                        $html .= $this->get_html_en_proceso(ESPERA_STOCK, $reception_id);
                        // $html .= $this->get_html_espera_stock(ESPERA_STOCK, $reception_id);
                        $html .= $this->get_html_concluido(ESPERA_STOCK, $reception_id);
                        break;
                    case EN_PROCESO:
                        $html .= $this->get_html_en_mora(EN_PROCESO, $reception_id);
                        $html .= $this->get_html_en_proceso(EN_PROCESO, $reception_id);
                        // $html .= $this->get_html_espera_stock(EN_PROCESO, $reception_id);
                        $html .= $this->get_html_concluido(EN_PROCESO, $reception_id);
                        break;
                endswitch;
                break;
        endswitch;

        return $html;
    }

    public function get_html_RECEPCIONADO($state, $reception_id)
    {
        $bg = 'default';
        if (RECEPCIONADO == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . RECEPCIONADO . ');"><i class="material-icons"></i><b>POR PERITAR</b></a><!--  -->
                    </div>
                  </div><br>';

        return $html;
    }

    public function get_html_REPARADO($state, $reception_id)
    {
        $bg = 'default';
        if (REPARADO == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . REPARADO . ');"><i class="material-icons"></i><b>REPARADO</b></a>
                    </div>
                  </div><br>';

        return $html;
    }

    public function get_html_aprobado($state, $reception_id)
    {
        $bg = 'default';
        if (APROBADO == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control " onclick="onclick_state(' . APROBADO . ')"><i class="material-icons"></i><b>APROBADO</b></a><!-- -->
                    </div>
                  </div><br>';
        return $html;
    }

    public function get_html_en_proceso($state, $reception_id)
    {

        $bg = 'default';
        if (EN_PROCESO == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . EN_PROCESO . ')"><i class="material-icons"></i><b>EN PROCESO</b></a>
                    </div>
                  </div><br>';
        return $html;
    }

    public function get_html_concluido($state, $reception_id)
    {
        $bg = 'default';
        if (CONCLUIDO == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . CONCLUIDO . ')"><i class="material-icons"></i><b>CONLUIDO</b></a>
                    </div>
                  </div><br>';
        return $html;
    }

    public function get_html_entregado($state, $reception_id)
    {
        $bg = 'default';
        if (ENTREGADO == $state) {
            $bg = 'success';
        }

        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . ENTREGADO . ')"><i class="material-icons"></i><b>ENTREGADO</b></a>
                    </div>
                  </div><br>';
        return $html;
    }

    public function get_html_en_mora($state, $reception_id)
    {
        $bg = 'default';
        if (EN_MORA == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . EN_MORA . ')"><i class="material-icons"></i><b>EN MORA</b></a>
                    </div>
                  </div><br>';
        return $html;
    }

    public function get_html_espera_stock($state, $reception_id)
    {
        $bg = 'default';
        if (ESPERA_STOCK == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . ESPERA_STOCK . ')"><i class="material-icons"></i><b>EN ESPERA DE STOCK</b></a>
                    </div>
                  </div><br>';
        return $html;
    }

    public function get_html_entregado_espera_stock($state, $reception_id)
    {
        $bg = 'default';
        if (ENTREGADO_ESPERA_STOCK == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . ENTREGADO_ESPERA_STOCK . ')"><i class="material-icons"></i><b>ENTREGADO Y A LA ESPERA DE STOCK</b></a>
                    </div>
                  </div><br>';
        return $html;
    }

    public function get_html_no_aprobado($state, $reception_id)
    {
        $bg = 'default';
        if (NO_APROBADO == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . NO_APROBADO . ')"><i class="material-icons"></i><b>NO APROBADO</b></a>
                    </div>
                  </div><br>';
        return $html;
    }

    public function get_html_sin_solucion($state, $reception_id)
    {
        $bg = 'default';
        if (SIN_SOLUCION == $state) {
            $bg = 'success';
        }
        $html = '<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                         
                        <a class="btn btn-' . $bg . ' waves-effect form-control" onclick="onclick_state(' . SIN_SOLUCION . ')"><i class="material-icons"></i><b>SIN SOLUCION</b></a>
                    </div>
                  </div><br>';
        return $html;
    }

    /* Registrar la Imagen (BD=>imagen_recepcion) con atributos de id_recepcion y nombre de la imagen*/
    public function register_image_reception($reception_id, $name_image)
    {
        $imagen_recepcion['nombre'] = $name_image;
        //$imagen_recepcion['url']            = base_url().'reception_galery\\'.$reception_id.'\\';
        $imagen_recepcion['url'] = base_url() . 'reception_gallery/' . $reception_id . '/' . $name_image;
        $imagen_recepcion['fecha_registro'] = date('Y-m-d H:i:s');
        $imagen_recepcion['estado'] = ACTIVO;
        $imagen_recepcion['recepcion_id'] = $reception_id;
        $imagen_recepcion['sucursal_id'] = get_branch_id_in_session();
        $imagen_recepcion['usuario_id'] = get_user_id_in_session();
        $this->db->insert('imagen_recepcion', $imagen_recepcion);

        $image_reception_inserted = $this->_get_image_reception($imagen_recepcion);
		$image_reception_id = $image_reception_inserted->id;

        return $image_reception_id;
    }

    public function _get_image_reception($image_reception)
	{
		return $this->db->get_where('imagen_recepcion', $image_reception)->row();
	}

    public function get_reception_images($reception_id)
    {
        $this->db->select('*')
            ->from('imagen_recepcion')
            ->where('recepcion_id', $reception_id);
        $data = $this->db->get()->result();
        $html_image = '';
        foreach ($data as $row_data):
            $html_image .= '<div class="col-md-3" id="div_' . $row_data->id . '">
                                <img class="img-responsive" width="100%" height="100%" src="' . $row_data->url . '">
                                <input type="text" class="btn form-control btn-danger" onclick="delete_div(' . $row_data->id . ')" value="Eliminar' . $row_data->id . '">
                            </div>';
        endforeach;
        return $html_image;
    }

    public function delete_image($image_id)
    {
        $this->db->where('id', $image_id);
        return $this->db->delete('imagen_recepcion');
    }

    public function substrack_date()
    {
        $fecha = date('Y-m-d');
        $nuevafecha = strtotime('-3 day', strtotime($fecha));
        $nuevafecha = date('Y-m-d', $nuevafecha);

        return $nuevafecha;
    }

    public function get_report_brand_list($params = array())
    {

        /*parametros*/
        $reception_date_start = $params["reception_date_start"];
        $reception_date_end = $params["reception_date_end"];
        $reception_brand = $params["reception_brand"];

        /* Se cachea la informacion que se arma en query builder*/
        $this->db->start_cache();
        $this->db->select('vista_lista_recepcion.*');
        $this->db->from('vista_lista_recepcion');
        $this->db->where('sucursal_id', get_branch_id_in_session());
        $this->db->where('estado_trabajo', ENTREGADO);
        if ($reception_date_start != '') {
            $this->db->where('DATE(fecha_registro) >=', $reception_date_start);
        }
        if ($reception_date_end != '') {
            $this->db->where('DATE(fecha_registro) <=', $reception_date_end);
        }
        if ($reception_brand != '0') {
            $this->db->where('marca_id =', $reception_brand);
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
            //$this->db->order_by($params['column'], $params['order']);
        } else {
            $this->db->order_by('id', 'ASC');
        }
        // Obtencion de resultados finales
        $draw = $this->input->post('draw');
        $data_result = $this->db->get()->result_array();
        $data = array();
        foreach ($data_result as $row) {
            $list_detal_work = $this->order_work_model->get_detail_service_by_order_work_id(8);
            $detail_service = '';
            foreach ($list_detal_work AS $row_service):
                $detail_service = $detail_service . ' - ' . $row_service->nombre_servicio;
            endforeach;

            $array = array();
            $telefono = $row['telefono1'];
            if ($row['telefono2'] != '') {
                $telefono = $telefono . ' - ' . $row['telefono2'];
            }
            $array['id'] = $row['id'];
            $array['codigo_recepcion'] = $row['codigo_recepcion'];
            $array['telefono'] = $telefono;
            $array['nombre'] = $row['nombre'];
            $array['prioridad'] = get_priority_text($row['prioridad']);
            $array['monto_total'] = $row['monto_total'];
            $array['estado'] = $row['estado'];
            $array['imei'] = $row['imei'];
            $array['galeria'] = $row['galeria'];
            $array['nombre_marca'] = $row['nombre_marca'];
            $array['nombre_modelo'] = $row['nombre_modelo'];
            $array['fecha_registro'] = date('d-m-Y', strtotime($row['fecha_registro']));
            $array['fecha_entrega'] = $this->get_delivery_date($row['fecha_entrega']);
            $array['estado_trabajo'] = get_work_order_states($row['estado_trabajo']);
            $array['orden_trabajo_id'] = $row['orden_trabajo_id'];
            $array['detalle_reparacion'] = $detail_service;
            $array['dias'] = $this->is_late($row['fecha_modificacion'], date('Y-m-d'));
            $data[] = $array;
        }
        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_total,
            'data' => $data,
        );
        return $json_data;
    }

    public function get_report_brand_list_to_export_xls($params = array())
    {
        $data_result = $this->get_data_report_brand($params);
        $data = array();
        foreach ($data_result as $row) {
            $list_detal_work = $this->order_work_model->get_detail_service_by_order_work_id($row['orden_trabajo_id']);
            $detail_service = '';
            $switch_guion = false;
            foreach ($list_detal_work AS $row_service):
                if ($switch_guion) {
                    $detail_service = $detail_service . ' - ' . $row_service->nombre_servicio;
                } else {
                    $detail_service = $detail_service . '' . $row_service->nombre_servicio;
                    $switch_guion = true;
                }

            endforeach;

            $array = array();
            $telefono = $row['telefono1'];
            if ($row['telefono2'] != '') {
                $telefono = $telefono . ' - ' . $row['telefono2'];
            }
            $array['id'] = $row['id'];
            $array['codigo_recepcion'] = $row['codigo_recepcion'];
            $array['telefono'] = $telefono;
            $array['nombre'] = $row['nombre'];
            $array['prioridad'] = get_priority_text($row['prioridad']);
            $array['monto_total'] = $row['monto_total'];
            $array['estado'] = $row['estado'];
            $array['imei'] = $row['imei'];
            $array['galeria'] = $row['galeria'];
            $array['nombre_marca'] = $row['nombre_marca'];
            $array['codigo_modelo'] = $row['codigo_modelo'];
            $array['nombre_modelo'] = $row['nombre_modelo'];
            $array['fecha_registro'] = date('d-m-Y', strtotime($row['fecha_registro']));
            $array['fecha_entrega'] = $this->get_delivery_date($row['fecha_entrega']);
            $array['estado_trabajo'] = get_work_order_states($row['estado_trabajo']);
            $array['orden_trabajo_id'] = $row['orden_trabajo_id'];
            $array['detalle_reparacion'] = $detail_service;
            $array['dias'] = $this->is_late($row['fecha_modificacion'], date('Y-m-d'));
            $array['garantia'] = $row['garantia'];
            $data[] = $array;
        }
        $json_data = array(
            'data' => $data,
        );
        return $json_data;
    }

    public function get_data_report_brand($params = array())
    {
        /*parametros*/
        $reception_date_start = $params["reception_date_start"];
        $reception_date_end = $params["reception_date_end"];
        $reception_brand = $params["reception_brand"];

        $this->db->select('*');
        $this->db->from('vista_lista_recepcion');
        $this->db->where('sucursal_id', get_branch_id_in_session());
        $this->db->where('estado_trabajo', ENTREGADO);
        if ($reception_date_start != '') {
            $this->db->where('DATE(fecha_registro) >=', $reception_date_start);
        }
        if ($reception_date_end != '') {
            $this->db->where('DATE(fecha_registro) <=', $reception_date_end);
        }
        if ($reception_brand != '0') {
            $this->db->where('marca_id =', $reception_brand);
        }
        return $this->db->get()->result_array();
    }

    public function get_delivery_date($date)
    {
        if ($date == null) {
            return '';
        }
        return date('d-m-Y', strtotime($date));
    }

    public function is_late($date_latter, $date_begin)
    {
        $dias = (strtotime($date_begin) - strtotime(date('Y-m-d', strtotime($date_latter)))) / 86400;
        $dias = abs($dias);
        $dias = floor($dias);
        return $dias;

    }

    public function get_colors()
    {
        $this->db->select('*');
        $this->db->from('color');
        $this->db->where('estado', ACTIVO);

        return $this->db->get()->result();
    }

    public function get_color_by_reception_id($reception_id)
    {
        $this->db->select('c.id as color_id, c.nombre as nombre_color');
        $this->db->from('recepcion r, color c');
        $this->db->where('r.color_id=c.id');
        $this->db->where('r.id', $reception_id);

        return $this->db->get()->row();
    }

    public function get_detail_recycled_by_order_work_id($order_work_id)
    {
        $this->db->select('prd.id AS producto_id, prd.nombre_comercial AS  nombre_producto, det.cantidad, det.precio_venta,')
            ->from('orden_trabajo ord, detalle_producto_reciclado det, producto prd')
            ->where('det.orden_trabajo_id = ord.id')
            ->where('prd.id = det.producto_id')
            ->where('ord.id', $order_work_id);
        return $this->db->get()->result();
    }

    public function register_reception_reason()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            $this->form_validation->set_rules('type_reason', 'tipo de motivo', 'trim|required');
            $this->form_validation->set_rules('warehouse_id', 'almacen', 'trim|required');
            $this->form_validation->set_rules('reason_product_selected', 'producto', 'trim|required');

            $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');

            if ($this->form_validation->run() === true) {

                $this->db->trans_start();
                $reception_id = $this->input->post('reception_reason_id');

                $obj_reception_reason['observacion'] = strtoupper($this->input->post('observations'));
                $obj_reception_reason['estado'] = ACTIVO;
                $obj_reception_reason['tipo_motivo_id'] = $this->input->post('type_reason');
                $obj_reception_reason['recepcion_id'] = $reception_id;
                $obj_reception_reason['fecha_registro'] = date('Y-m-d H:i:s');
                $obj_reception_reason['usuario_id'] = get_user_id_in_session();
                $obj_reception_reason['sucursal_id'] = get_branch_id_in_session();

                $this->_insert_reception_reason($obj_reception_reason);

                $not_approved_id = $this->get_not_approved($obj_reception_reason)->id;

                $obj_reception_detail_not_approved['state'] = ACTIVO;
                $obj_reception_detail_not_approved['recepcion_id'] = $reception_id;
                $obj_reception_detail_not_approved['producto_id'] = $this->input->post('reason_product_selected');
                $obj_reception_detail_not_approved['no_aprobado_id'] = $not_approved_id;
                $obj_reception_detail_not_approved['sucursal_id'] = get_branch_id_in_session();

                $this->_insert_detail_not_approved($obj_reception_detail_not_approved);


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response['success'] = false;
                } else {
                    $this->db->trans_commit();
                    $response['success'] = true;
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

    private function _insert_reception_reason($reception_reason)
    {
        return $this->db->insert('no_aprobado', $reception_reason);
    }

    private function _insert_detail_not_approved($obj_reception_detail_not_approved)
    {
        return $this->db->insert('detalle_no_aprobado', $obj_reception_detail_not_approved);
    }

    public function get_fault_reception_by_reception_id($recepcion_id)
    {
        $this->db->select('f.*');
        $this->db->from('falla_recepcion fr, falla f');
        $this->db->where('fr.recepcion_id', $recepcion_id);
        $this->db->where('fr.falla_id=f.id');
        return $this->db->get()->result();
    }

    public function get_not_approved($obj_reception_reason)
    {
        return $this->db->get_where('no_aprobado', $obj_reception_reason)->row();
    }

    public function exist_payment($reception_id)
    {
        $this->db->select('*')
            ->from('pago_recepcion')
            ->where('recepcion_id', $reception_id);
        $query_user = $this->db->get();
        if ($query_user->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_first_payment($reception_id)
    {
        $this->db->select('*');
        $this->db->from('pago_recepcion');
        $this->db->where('recepcion_id', $reception_id);
        $this->db->order_by('id', 'ASC');
        $query_user = $this->db->get();
        if ($query_user->num_rows() > 0) {
            return $query_user->row()->id;
        } else {
            return 0;
        }
    }

    public function calculate_total_reception_deliver($data1 = array(), $data2 = array())
    {

        $reception_total = $data1["order_work"]->monto_total;
        $discount_current = 0;

        $reception_discount = 0;
        $discount_current = $reception_discount;

        $reception_discount_old = $data2->total_descuentos;

        $reception_discount = $reception_discount + $reception_discount_old;
        $reception_total_payment = $reception_total - $reception_discount;

        $total_payment_old = $data2->total_pagados;

        $total_balance = $reception_total_payment - $total_payment_old;

        $calculate = Array(
            "total_payment" => $reception_total_payment,
            "payment" => $total_balance,
            "balance" => $total_balance,
            "discount" => $discount_current
        );

        return $calculate;

    }

    public function get_receipt_payment($data_receipt_payment)
    {
        return $this->db->get_where('pago_recepcion', $data_receipt_payment)->row();
    }

    public function _get_reception($obj_reception)
	{
		return $this->db->get_where('recepcion', $obj_reception)->row();
	}
}
