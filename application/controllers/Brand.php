<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 18/7/2017
 * Time: 8:01 PM
 */
class Brand extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('brand_model');
	}

	/*Mandar al index para cargar la lista de marcas*/
	public function index()
	{
		template('brand/index');
	}

	/*Para registrar la nueva marca*/
	public function register_brand()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->brand_model->register_brand();
		} else {
			show_404();
		}
	}

	/*Para modificar una marca*/
	public function modify_brand()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->brand_model->modify_brand();
		} else {
			show_404();
		}
	}

	/*Para eliminar un marca seleccionado de la lista*/
	public function disable_brand()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->brand_model->disable_brand();
		} else {
			show_404();
		}
	}

	/*Obtener todas las marcas activos especial para cargar combos o autocompletados*/
	public function get_brand_enable()
	{
		if ($this->input->is_ajax_request()) {

			$brand_list = $this->brand_model->get_brand_enable();
			echo json_encode($brand_list);
		} else {
			show_404();
		}
	}

	/*Para cargar la lista de marcas en el dataTable*/
	public function get_brand_list()
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

			echo json_encode($this->brand_model->get_brand_list($params));
		} else {
			show_404();
		}
	}
}
