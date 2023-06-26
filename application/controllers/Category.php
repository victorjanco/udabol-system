<?php

class Category extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('serie_model');
	}

	/*Mandar al index para cargar la lista de marcas*/
	public function index()
	{
		template('category/index');
	}

	/*Para registrar la nueva marca*/
	public function register_category()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->serie_model->register_category());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/*Para modificar una marca*/
	public function modify_category()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->serie_model->modify_category());
			} else {
				show_404();
			}	
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/*Para eliminar un marca seleccionado de la lista*/
	public function disable_category()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->serie_model->disable_category();
		} else {
			show_404();
		}
	}

	/*Obtener todas las marcas activos especial para cargar combos o autocompletados*/
	public function get_category_enable()
	{
		if ($this->input->is_ajax_request()) {

			$category_list = $this->serie_model->get_category_enable();
			echo json_encode($category_list);
		} else {
			show_404();
		}
	}

	/*Para cargar la lista de marcas en el dataTable*/
	public function get_category_list()
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

			echo json_encode($this->serie_model->get_category_list($params));
		} else {
			show_404();
		}
	}
}
