<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 17/03/2020
 * Time: 09:02 AM
 */
class Cash_income_type extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cash_income_type_model');
    }

    public function index()
    {
        $ci = get_instance();
        $data_option=[];
        if(isset($ci->session->userdata('option')['cash_income_type'])){
            $data_option = $ci->session->userdata('option')['cash_income_type'];
            template('cash_income_type/index',['option'=> json_encode($data_option)]);
        }
    }

    /*Mandar los parametros al modelo para registrar la caja*/
    public function register_cash_income_type()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_income_type_model->register_cash_income_type();
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para modificar la caja*/
    public function modify_cash_income_type()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_income_type_model->modify_cash_income_type();
        } else {
            show_404();
        }
    }

    /*Para eliminar una caja seleccionado de la lista*/
    public function disable_cash_income_type()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_income_type_model->disable_cash_income_type();
        } else {
            show_404();
        }
    }
    /*Obtener todas las almacenes activos especial para cargar combos o autocompletados*/

    public function get_cash_income_type_enable()
    {
        if ($this->input->is_ajax_request()) {

            $cash_income_type_list=$this->cash_income_type_model->get_cash_income_type_enable();
            echo json_encode($cash_income_type_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de caja en el dataTable*/
    public function get_cash_income_type_list()
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

            echo json_encode($this->cash_income_type_model->get_cash_income_type_list($params));
        } else {
            show_404();
        }
    }

}