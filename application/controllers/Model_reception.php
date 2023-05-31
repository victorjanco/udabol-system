<?php

/**
 * Created by PhpStorm.
 * User: Victo Janco
 * Date: 05/01/2021
 * Time: 17:01 PM
 */
class Model_reception extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_reception_model');
	}

	/*Mandar al index para cargar la lista de marcas*/
	public function index()
	{
		template('model_model/index');
	}

	/*Para registrar la nueva marca*/
	public function register_model_reception()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->model_reception_model->register_model_reception();
		} else {
			show_404();
		}
	}

	/*Para modificar una marca*/
	public function modify_model_reception()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->model_reception_model->modify_model_reception();
		} else {
			show_404();
		}
	}

	/*Para eliminar un marca seleccionado de la lista*/
	public function disable_model_reception()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->model_reception_model->disable_model_reception();
		} else {
			show_404();
		}
	}
	public function enable_model_reception()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->model_reception_model->enable_model_reception();
		} else {
			show_404();
		}
	}

	/*Retorna listado de modelos por marca  */
    public function get_model_reception_by_brand()
    {
        if ($this->input->is_ajax_request()) {
		echo json_encode($this->model_reception_model->get_model_reception_by_brand());
        } else {
            show_404();
        }
    }
	/*Obtener todas las marcas activos especial para cargar combos o autocompletados*/
	public function get_model_reception_enable()
	{
		if ($this->input->is_ajax_request()) {

			$model_reception_list = $this->model_reception_model->get_model_reception_enable();
			echo json_encode($model_reception_list);
		} else {
			show_404();
		}
	}

	/*Para cargar la lista de marcas en el dataTable*/
	public function get_model_reception_list()
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

			echo json_encode($this->model_reception_model->get_model_reception_list($params));
		} else {
			show_404();
		}
	}
}
