<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 11/7/2017
 * Time: 2:06 PM
 */
class Customer_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener los todos los clientes activos especial para cargar combos o autocompletados*/
    public function get_customer_enable($type = 'object')
    {
        $this->db->select('*')
            ->from('cliente')
            ->where('id >2');
        return $this->db->get()->result($type);
    }

    public function get_customer_list_all()
    {
        $this->db->select('*')
            ->from('cliente');
        return $this->db->get()->result();
    }

    /*Obtener datos del cliente apartit del cliente_id*/
    public function get_customer_id($client_id)
    {
        return $this->db->get_where('cliente', array('id' => $client_id))->row();
    }

    /*Obtener lista de cliente para cargar la lista de dataTable*/
    public function get_customer_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('cliente')
            ->where('id > 2')
            ->where('sucursal_id', get_branch_id_in_session());
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
            $this->db->order_by('id', 'DESC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('lower(nombre)', strtolower($params['search']));
            $this->db->or_like('lower(nit)', $params['search']);
            $this->db->or_like('lower(ci)', $params['search']);
            $this->db->group_end();
            $this->db->order_by('id', 'DESC');
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

    /*Funcion registrar nuevo cliente para validar los datos de la vista */
    public function register_customer()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'ci',
                'label' => 'Carnet de Identificacion',
                'rules' => 'trim|required|alpha_dash'
            ),
            array(
                'field' => 'nit',
                'label' => 'nit',
                'rules' => 'trim|alpha_dash'
            ),
            array(
                'field' => 'nombre',
                'label' => 'Nombre del Cliente',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'telefono1',
                'label' => 'Telefono del Cliente',
                'rules' => 'trim|required'
            )
        );

        if (verify_session()) {

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<p class="abm-error">', '</p>');

            $name_customer=$this->input->post('nombre');
            $name_invoice=$this->input->post('nombre_factura');
            $name_invoice=$name_invoice!=''? $name_invoice:$name_customer;

            $ci_customer=$this->input->post('ci');
            $nit_customer=$this->input->post('nit');
            $nit_customer=$nit_customer!=''? $nit_customer:$ci_customer;

            if ($this->form_validation->run() === TRUE) {
                $data_customer = array(
                    'tipo_cliente' => $this->input->post('tipo'),
                    'codigo_cliente' => $this->input->post('ci'),
                    'ci' => $this->input->post('ci'),
                    'nit' => $nit_customer,
                    'nombre' => text_format($name_customer),
                    'nombre_factura' => $name_invoice,
                    'telefono1' => $this->input->post('telefono1'),
                    'telefono2' => $this->input->post('telefono2'),
                    'direccion' => text_format($this->input->post('direccion')),
                    'email' => text_format($this->input->post('email')),
                    'ciudad' => $this->input->post('ciudad'),
                    'fecha_nacimiento' => date('Y-m-d H:i:s'),
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'sincronizado' => 0,
                    'estado' => get_state_abm('ACTIVO'),
                    'sucursal_id' => get_branch_id_in_session(),
                    'user_created' => get_user_id_in_session(),
                    'user_updated' => get_user_id_in_session()
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar ala base de datos el nuevo cliente
                $this->_insert_customer($data_customer);
                $last_customer_inserted = $this->db->get_where('cliente', $data_customer)->row();
                $last_customer_id= $last_customer_inserted->id;

                // Obtener resultado de transacción
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    $response['success'] = TRUE;
                    $response['customer']= $this->get_customer_id($last_customer_id);
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

    /*Funcion modificar al cliente para validar los datos de la vista */
    public function modify_customer()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $customer_id = $this->input->post('id');
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'ci',
                'label' => 'Carnet de Identificacion',
                'rules' => 'trim|required'

            ),
            array(
                'field' => 'nit',
                'label' => 'nit',
                'rules' => 'trim|required'

            ),
            array(
                'field' => 'nombre',
                'label' => 'Nombre del Cliente',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'telefono1',
                'label' => 'Telefono del Cliente',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'direccion',
                'label' => 'Direccion del Cliente',
                'rules' => 'trim|required'
            )
        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<p class="abm-error">', '</p>');

        if ($this->form_validation->run() === TRUE) {
            $data_customer = array(
                'tipo_cliente' => $this->input->post('tipo'),
                'codigo_cliente' => $this->input->post('ci'),
                'ci' => $this->input->post('ci'),
                'nit' => $this->input->post('nit'),
                'nombre' => text_format($this->input->post('nombre')),
                'nombre_factura' => text_format($this->input->post('nombre_factura')),
                'telefono1' => $this->input->post('telefono1'),
                'telefono2' => $this->input->post('telefono2'),
                'direccion' => text_format($this->input->post('direccion')),
                'email' => text_format($this->input->post('email')),
                'ciudad' => $this->input->post('ciudad'),
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'sincronizado' => 0,
                'user_updated' => get_user_id_in_session()
            );

            // Inicio de transacción
            $this->db->trans_begin();

            // Actualzar la falla del nuevo cliente
            $this->_update_customer($customer_id, $data_customer);

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

        echo json_encode($response);
    }

    public function disable_customer()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'cliente',
            ['estado' => get_state_abm('INACTIVO'),
            'user_updated' => get_user_id_in_session(),
            'fecha_modificacion' => date('Y-m-d H:i:s')],
            ['id' => $id]
        );
    }
    public function activate_customer()
	{
		// $id = $this->input->post('id');
		// $res = $this->get_activated_dosage_by_id($asignacion_dosficacion_id);
		// if ($res == false) {
			$id = $this->input->post('id');
			return $this->db->update(
				'cliente',
				['estado' => ACTIVO, 
                'user_updated' => get_user_id_in_session(),
                'fecha_modificacion' => date('Y-m-d H:i:s')],
				['id' => $id]
			);
		// }
    }

    /*Funcion privada para insertar en la base de datos al nuevo cliente*/
    private function _insert_customer($customer)
    {
        return $this->db->insert('cliente', $customer);
    }

    /*Funcion privada para actualizar en la base de datos del cliente*/
    private function _update_customer($customer_id, $data_customer)
    {
        $where = array('id' => $customer_id);
        return $this->db->update('cliente', $data_customer, $where);
    }

    /*********** EQUIPO CLIENTE ***********/
    public function register_device_customer()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->load->model('product_model');
        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $this->form_validation->set_rules('brand', 'marca', "trim|required");
        $this->form_validation->set_rules('model', 'modelo', "trim|required");
        $this->form_validation->set_rules('imei_phone', 'IMEI', 'trim|required');

        $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');

        if ($this->form_validation->run() === true) {

            $this->db->trans_start();
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $sesion = $this->session->userdata('user');
            $obj_device['numero_telefono'] = $this->input->post('number_phone'); //contrace~a
            $obj_device['imei'] = $this->input->post('imei_phone');
            $obj_device['imei2'] = $this->input->post('imei2');
            if($this->input->post('date_buy')==''){
                $obj_device['fecha_compra'] = null;
            }
            $obj_device['fecha_registro'] = date('Y-m-d H:i:s');
            $obj_device['fecha_modificacion'] = date('Y-m-d H:i:s');
            $obj_device['cliente_id'] = $this->input->post('id_customer');
            $obj_device['marca_id'] = $this->input->post('brand');
            $obj_device['modelo_id'] = $this->input->post('model');
            $obj_device['sucursal_id'] = get_branch_id_in_session();
            $obj_device['usuario_id'] = get_user_id_in_session();

            $this->db->insert('equipo_cliente', $obj_device);

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
        return $response;
    }

    public function get_customer_devices($id)
    {
        $this->db->select('mod.id, mod.nombre, e.imei, e.id AS producto_cliente_id');
        $this->db->from('equipo_cliente e, cliente c, modelo_recepcion mod, marca_recepcion mar');
        $this->db->where('e.modelo_id = mod.id');
        $this->db->where('e.marca_id = mar.id');
        $this->db->where('e.cliente_id = c.id');
        $this->db->where('mod.estado', ACTIVO);
        $this->db->where('c.id', $id);
        return $this->db->get()->result();
    }

    /*Para verificar al cliente si existe con username y password y responder con el id*/
    public function verify_customer($username, $password)
    {
        $respuesta['success'] = false;
        $data = $this->db->get_where('cliente', array('codigo_cliente' => $username ,'ci' => $password, 'estado' => get_state_abm('ACTIVO')));
        if ($data->num_rows() > 0) {
            $customer = $data->row();
            $respuesta['cliente'] = $customer;
            $respuesta['success'] = true;
        }
        return $respuesta;
    }
    public function exists_customer($id, $nit_customer, $name_customer)
    {
        $this->db->select('*')
            ->from('cliente')
            ->where('id', $id) 
            ->where('nit', $nit_customer)
            ->where('nombre_factura', $name_customer)
            ->where('estado', ACTIVO);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            // SI EXISTE
            return 1;
        } else {
            // NO EXISTE
            return 0;
        }
    }

    public function exists_customer_by_name_nit($nit_customer, $name_customer)
    {
        $this->db->select('*')
            ->from('cliente')
            ->where('nit', $nit_customer)
            ->where('nombre_factura', $name_customer)
            ->where('estado', ACTIVO);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            // SI EXISTE
            return 1;
        } else {
            // NO EXISTE
            return 0;
        }
    }
    public function save($data_customer)
    {
        return $this->_insert_customer($data_customer);
    }
}