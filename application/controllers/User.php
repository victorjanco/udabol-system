<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 13/07/2017
 * Time: 02:40 AM
 */
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('function_model');
    }

    //region Vistas
    public function index(){
        template('user/index');
    }


    public function new_user(){
        $response['functions'] = $this->function_model->get_menu();
        $this->load->model('office_model');
        $response['offices'] = $this->office_model->get_offices();
        template('user/new_user', $response);
    }

    public function edit(){
        $id_user = $this->input->post('id');
        $this->load->model('function_model');
        $this->load->model('office_model');
        // Obtener datos relacionados al usuario
        $user         = $this->user_model->get_user_id($id_user);
        $functions    = $this->function_model->get_user_functions($id_user);
        $offices      = $this->office_model->get_user_offices($id_user);

        $data = array(
            'id_user'        => $id_user,
            'user'           => $user,
            'functions_user' => $functions,
            'office_user'    => $offices,
            'offices'        => $this->office_model->get_offices(),
            'functions'      => $this->function_model->get_menu(),
            'charges'        => $this->user_model->get_charges(),
            'type'           => 'editar',
        );

        template('user/edit_user',$data);
    }
    //endregion


    //region Funciones de Usuario
    /* ---------------------------------------------------------
     *  Obtiene todos los usuarios utilizando server side de datatable
     *---------------------------------------------------------
     * */
    public function get_users(){
        if ($this->input->is_ajax_request()){
            $start = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            $data_users = $this->user_model->get_users($start,$length,$search,$column,$order);

            $result = $data_users['data_users'];
            $total_data = $data_users['total_register'];

            $data = array();
            foreach ($result->result_array() as $row){
                $array = array();
                $array['id'] = $row['id'];
                $array['ci'] = $row['ci'];
                $array['nombre'] = $row['nombre'];
                $array['telefono'] = $row['telefono'];
                $array['usuario'] = $row['usuario'];
                $array['descripcion'] = $row['descripcion'];
                $array['estado'] = $row['estado'];
                $data[]= $array;
            }

            $count_users = $result->num_rows();

            $json_data = array(
                'draw'              =>  intval($this->input->post('draw')),
                'recordsTotal'      =>  intval($count_users),
                'recordsFiltered'   =>  intval($total_data),
                'data'              =>  $data,
            );

            echo json_encode($json_data);
        }else{
            show_404();
        }
    }

    /* ---------------------------------------------------------
     *  Metodo para obtener todos los cargos
     *---------------------------------------------------------
     * */
    public function get_charges(){
        echo json_encode($this->user_model->get_charges());
    }

    /* ---------------------------------------------------------
     * Registra un nuevo usuario
     *---------------------------------------------------------
     * */
    public function registrer_user()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->user_model->registrer_user());
        } else {
            show_404();
        }
    }

    public function verify_key()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->user_model->verify_key());
        } else {
            show_404();
        }
    }

    public function change_pass_user()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->user_model->change_pass_user());
        } else {
            show_404();
        }
    }
    public function edit_user(){
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->user_model->edit_user());
        } else {
            show_404();
        }
        //echo json_encode($this->user_model->edit_user());
    }

    public function delete_user(){
        if ($this->input->is_ajax_request()) {
            $id_user = $this->input->post('id');
            echo json_encode($this->user_model->delete_user($id_user));
        } else {
            show_404();
        }
    }
    public function activate_user(){
        if ($this->input->is_ajax_request()) {
            $id_user = $this->input->post('id');
            echo json_encode($this->user_model->activate_user($id_user));
        } else {
            show_404();
        }
    }

    /* --------------------------------------------------------------
     * Metodo de registro de cargos de usuario
     *---------------------------------------------------------------
     * **/
    public function registrer_charge(){
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->user_model->registrer_charge());
        } else {
            show_404();
        }
    }
    //endregion
}