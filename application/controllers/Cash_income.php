<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 16/03/2020
 * Time: 16:02 PM
 */
class Cash_income extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cash_income_model');
        $this->load->model('cash_model');
        $this->load->model('cash_income_type_model');
        $this->load->model('customer_model'); 
        $this->load->model('user_model');
    }

    public function index()
    {
        template('cash_income/index');
    }
    public function new_cash_income(){
        $response['cashs'] = $this->cash_model->get_cash_enable();
        $response['cash_income_types'] = $this->cash_income_type_model->get_cash_income_type_enable();
        if(get_session_cash_id()!=0){
            
        }
        $response['cash_id']=get_session_cash_id(); //caja aperturada
        $response['cash_aperture_id']=get_session_cash_aperture_id(); //aperturada_caja_id


        template('cash_income/new_cash_income',$response);
    }
    public function view(){
        $id_cash_income = $this->input->post('id');
        $response['cashs'] = $this->cash_model->get_cash_enable();
        $response['cash_income_types'] = $this->cash_income_type_model->get_cash_income_type_all_enable();
        $response['cash_income'] = $this->cash_income_model->get_cash_income_id($id_cash_income);
        template('cash_income/view_cash_income',$response);
    }
    public function edit(){
        $id_cash_income = $this->input->post('id');
        $response['cashs'] = $this->cash_model->get_cash_enable();
        $response['cash_income_types'] = $this->cash_income_type_model->get_cash_income_type_enable();
        $response['cash_income'] = $this->cash_income_model->get_cash_income_id($id_cash_income);
        $response['cash_id']=get_session_cash_id(); //caja aperturada
        $response['cash_aperture_id']=get_session_cash_aperture_id(); //aperturada_caja_id
        template('cash_income/edit_cash_income',$response);
    }
    /*Mandar los parametros al modelo para registrar el ingreso de caja*/
    public function register_cash_income()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_income_model->register_cash_income();
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para modificar el Ingreso de Caja*/
    public function edit_cash_income()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_income_model->edit_cash_income();
        } else {
            show_404();
        }
    }

    /*Para eliminar un Ingreso de Caja seleccionado de la lista*/
    public function disable_cash_income()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            echo $this->cash_income_model->disable_cash_income($id);
        } else {
            show_404();
        }
    }

    /*Obtener todos ingresos de cajas*/
    public function get_cash_income_enable()
    {
        if ($this->input->is_ajax_request()) {
            $cash_income_list=$this->cash_income_model->get_cash_income_enable();
            echo json_encode($cash_income_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de ingresos de caja en el dataTable*/
    public function get_cash_income_list()
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

            echo json_encode($this->cash_income_model->get_cash_income_list($params));
        } else {
            show_404();
        }
    }

}