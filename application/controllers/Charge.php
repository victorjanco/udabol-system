<?php

class charge extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('charge_model');
	}

	/*Mandar al index para cargar la lista de marcas*/
	public function index()
	{
		template('charge/index');
	}

	/*Para registrar la nueva marca*/
	public function register_charge()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->charge_model->register_charge());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/*Para modificar una marca*/
	public function modify_charge()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->charge_model->modify_charge());
			} else {
				show_404();
			}	
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/*Para eliminar un marca seleccionado de la lista*/
	public function disable_charge()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->charge_model->disable_charge();
		} else {
			show_404();
		}
	}

	/*Obtener todas las marcas activos especial para cargar combos o autocompletados*/
	public function get_charge_enable()
	{
		if ($this->input->is_ajax_request()) {

			$charge_list = $this->charge_model->get_charge_enable();
			echo json_encode($charge_list);
		} else {
			show_404();
		}
	}

	/*Para cargar la lista de marcas en el dataTable*/
	public function get_charge_list()
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

			echo json_encode($this->charge_model->get_charge_list($params));
		} else {
			show_404();
		}
	}
}
