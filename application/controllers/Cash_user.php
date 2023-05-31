<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 08/04/2020
 * Time: 14:02 PM
 */
class Cash_user extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cash_user_model');
        $this->load->model('user_model');
        $this->load->model('cash_model');
    }

    public function index()
    {
        // $ci = get_instance();
        // $data_option=[];
        // if(isset($ci->session->userdata('option')['cash_user'])){
        //     $data_option = $ci->session->userdata('option')['cash_user'];
            template('cash_user/index');
        // }
    }
    public function view(){
        $user_id = $this->input->post('id');
        $user=$this->user_model->get_user_id($user_id);
        $response['user']=$user;
        $response['cashs']=$this->cash_model->get_cash_enable();
        template('cash_user/view_cash_user',$response);
    }
    public function edit(){
        $user_id = $this->input->post('id');
        $user=$this->user_model->get_user_id($user_id);
        $response['user']=$user;
        $response['cashs']=$this->cash_model->get_cash_enable();
        template('cash_user/edit_cash_user',$response);
    }
    /*Mandar los parametros al modelo para registrar la caja*/
    public function register_cash_user()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_user_model->register_cash_user();
        } else {
            show_404();
        }
    }

    /*Para eliminar una caja seleccionado de la lista*/
    public function disable_cash_user()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_user_model->disable_cash_user();
        } else {
            show_404();
        }
    }
    /*Obtener todas las almacenes activos especial para cargar combos o autocompletados*/

    public function get_cash_user_enable()
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

            echo json_encode($this->cash_user_model->get_cash_user_enable($params));
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de caja en el dataTable*/
    public function get_cash_users_list()
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

            echo json_encode($this->cash_user_model->get_cash_users_list($params));
        } else {
            show_404();
        }
    }
    /*Para eliminar una fila seleccionado de la lista*/
    public function delete_cash_user()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_user_model->delete_cash_user();
        } else {
            show_404();
        }
    }
}