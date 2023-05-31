<?php


class Dosage extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dosage_model');
        $this->load->model('printer_model');
        $this->load->model('company_model');
        $this->load->model('office_model');


    }

    /*Mandar al index para cargar las dosificaciones*/
    public function index()
    {
        template('dosage/index');
    }

    public function view_activate_dosage()
    {
        template('dosage/activate_dosage');
    }

    /*Formulario nuevo cliente*/
    public function new_dosage()
    {
        $data['branch']  = $this->office_model->get_offices();
        $data['printer']  = $this->printer_model->get_printers();
        $data['activity']  = $this->company_model->get_activitys();

        template('dosage/new_dosage',$data);
    }

    public function enable_dosage()
    {
        template('dosage/activate_dosage');
    }


    /*Funcion para registrar al nuevo cliente*/
    public function register_dosage()
    {
        if ($this->input->is_ajax_request()) {

            echo $this->dosage_model->register_dosage();
        } else {
            show_404();
        }
    }



    public function activate_dosage()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->dosage_model->activate_dosage();
        }
        else
        {
            show_404();
        }
    }

	public function printer_invoice()
	{
		if ($this->input->is_ajax_request()) {

			echo $this->dosage_model->printer_invoice();
		} else {
			show_404();
		}
	}


    /*Para cargar la lista de clientes en el dataTable*/
    public function get_inactive_dosage_list()
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

            echo json_encode($this->dosage_model->get_inactive_dosage_list($params));
        } else {
            show_404();
        }
    }


    public function get_enable_dosage_list()
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

            echo json_encode($this->dosage_model->get_enable_dosage_list($params));
        } else {
            show_404();
        }
    }

    public function get_caducated_dosage_list()
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

            echo json_encode($this->dosage_model->get_caducated_dosage_list($params));
        } else {
            show_404();
        }
    }


    public function get_branch_by_type()
    {

        if ($this->input->is_ajax_request()) {
            $branch_id = $this->input->post('id');
            echo json_encode($this->branch_model->get_branch_by_type($branch_id));
        } else {
            show_404();
        }
    }
}
