<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 11/7/2017
 * Time: 2:28 PM
 */
class Customer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_model');
    }

    /*Mandar al index para cargar la lista de clientes*/
    public function index()
    {
        template('customer/index');
    }

    /*Formulario nuevo cliente*/
    public function new_customer()
    {
        template('customer/new_customer');
    }

    /*Formulario editar cliente*/
    public function edit_customer()
    {
        if ($this->input->post()) {
            $customer_id = $this->input->post('id');
            $customer = $this->customer_model->get_customer_id($customer_id);
            if ($customer) {
                $data = array(
                    'customer' => $customer
                );
                template('customer/edit_customer', $data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }
    /*Funcion para registrar al nuevo cliente*/
    public function register_customer()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->customer_model->register_customer();
        } else {
            show_404();
        }
    }

    /*Funcion para actualizar al cliente seleccionado*/
    public function modify_customer()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->customer_model->modify_customer();
        } else {
            show_404();
        }
    }

    /*Para eliminar un cliente seleccionado de la lista*/
    public function disable_customer()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->customer_model->disable_customer();
        } else {
            show_404();
        }
    }
    
    public function activate_customer()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->customer_model->activate_customer();
        }
        else
        {
            show_404();
        }
    }

    /*Para obtener datos de un cliente en especifico por el id del cliente*/
    public function get_customer_id(){
        if ($this->input->is_ajax_request()){
            $customer_id = $this->input->post('id');
            echo json_encode($this->customer_model->get_customer_id($customer_id));
        }else{
            show_404();
        }
    }

    /* Metodo para obtener los datos de un cliente ci, nombre, nit, telefonos a apartir de su SUCURSAL*/
    public function get_data_customer()
    {
        $dato = $this->input->post_get('name_startsWith');
        $tipo = $this->input->post_get('type');
        switch ($tipo) {
            case 'ci':
                $this->db->like('ci', $dato);
                $this->db->where('id > 2');
                $this->db->where('sucursal_id', get_branch_id_in_session());
                $this->db->where('estado', ACTIVO);
                $res = $this->db->get('cliente');
                if ($res->num_rows() > 0) {
                    foreach ($res->result_array() as $row) {
                        $data[$row['ci'].' - '.$row['nombre']] = $row['nombre'] . '/' . $row['id'] . '/' . $row['nit']. '/' . $row['telefono1']. '/' . $row['telefono2'];
                    }
                    echo json_encode($data); //format the array into json data
                } else {
                    $data["No existe el cliente"] = "No existe el cliente";
                    echo json_encode($data);
                }
                break;
            case 'name':
                $this->db->like('lower(nombre)', strtolower($dato));
                $this->db->where('id > 2');
                $this->db->where('sucursal_id', get_branch_id_in_session());
                $this->db->where('estado', ACTIVO);
                $res = $this->db->get('cliente');
                if ($res->num_rows() > 0) {
                    foreach ($res->result_array() as $row) {
                        $data[$row['ci'].' - '.$row['nombre']] = $row['nombre'] . '/' . $row['id'] . '/' . $row['nit']. '/' . $row['telefono1']. '/' . $row['telefono2'];
                    }
                    echo json_encode($data); //format the array into json data
                } else {
                    $data["No existe el cliente"] = "No existe el cliente";
                    echo json_encode($data);
                }
                break;
            case 'nit':
                $this->db->like('nit', $dato);
                $this->db->or_like('lower(ci)', strtolower($dato));
                $this->db->where('id > 2');
                $this->db->where('sucursal_id', get_branch_id_in_session());
                $res = $this->db->get('cliente');
                if ($res->num_rows() > 0) {
                    foreach ($res->result_array() as $row) {
                        $data[$row['nombre_factura'].' - '.$row['nit'].' - '.$row['nombre']] = $row['nombre_factura'] . '/' . $row['id'] . '/' . $row['nit']. '/' . $row['nombre']. '/' . $row['tipo_cliente'];
                    }
                    echo json_encode($data); //format the array into json data
                } else {
                    $data["No existen datos"] = "No existe datos";
                    echo json_encode($data);
                }
                break;
            case 'nombre_factura':
                $this->db->like('lower(nombre_factura)', strtolower($dato));
                $this->db->or_like('lower(nombre)', strtolower($dato));
                $this->db->where('id > 2');
                $this->db->where('sucursal_id', get_branch_id_in_session());
                $res = $this->db->get('cliente');
                if ($res->num_rows() > 0) {
                    foreach ($res->result_array() as $row) {
                        $data[$row['nombre_factura'].' - '.$row['nit'].' - '.$row['nombre']] = $row['nombre_factura'] . '/' . $row['id'] . '/' . $row['nit']. '/' . $row['nombre']. '/' . $row['tipo_cliente'];
                    }
                    echo json_encode($data); //format the array into json data
                } else {
                    $data["No existen datos"] = "No existe datos";
                    echo json_encode($data);
                }
                break;
        }
    }

    /*Obtener todos los clientes activos especial para cargar combos*/
    public function get_customer_enable()
    {
        if ($this->input->is_ajax_request()) {
            $customer_list=$this->customer_model->get_customer_enable();
            echo json_encode($customer_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de clientes en el dataTable*/
    public function get_customer_list()
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

            echo json_encode($this->customer_model->get_customer_list($params));
        } else {
            show_404();
        }
    }

    /*
     * Metodo para registrar el equipo de un cliente desde la recepcion
     * **/
    public function register_device_customer(){
        if($this->input->is_ajax_request()){
            echo json_encode($this->customer_model->register_device_customer());
        }else{
            show_404();
        }
    }

    /*
     * Obtiene todos los dispositivos que tiene asociado un cliente
     * **/
    public function get_customer_devices(){
        $id_customer = $this->input->post('id_customer');
        echo json_encode($this->customer_model->get_customer_devices($id_customer));
    }

    public function update_customer_code_by_ci()
    {
        $customer_list = $this->customer_model->get_customer_list_all();
        foreach ($customer_list as $customer_list_update) {
            $customer_id = $customer_list_update->id;
            $customer = $this->customer_model->get_customer_id($customer_id);

            $this->db->where('id', $customer_id);
            $data_customer['codigo_cliente'] = $customer->ci;
            $this->db->update('cliente', $data_customer);
        }
    }
}