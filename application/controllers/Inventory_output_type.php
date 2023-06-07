<?php

/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 20/02/2020
 * Time: 15:02 PM
 */
class Inventory_output_type extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventory_output_type_model');
	}

	public function index()
	{
		template('inventory_output_type/index', array(), 'inventory_output_type');
	}

	/*Mandar los parametros al modelo para registrar el tipo de ingreso de inventario*/
	public function register_inventory_output_type()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->inventory_output_type_model->register_inventory_output_type());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/*Mandar los parametros al modelo para modificar el tipo de ingreso de inventario*/
	public function modify_inventory_output_type()
	{
		try {
			if ($this->input->is_ajax_request()) {
				echo json_encode($this->inventory_output_type_model->modify_inventory_output_type());
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	/*Para eliminar un tipo de ingreso de inventario seleccionado de la lista*/
	public function disable_inventory_output_type()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->inventory_output_type_model->disable_inventory_output_type();
		} else {
			show_404();
		}
	}

	/*Obtener todas las almacenes activos especial para cargar combos o autocompletados*/

	public function get_inventory_output_type_enable()
	{
		if ($this->input->is_ajax_request()) {

			$inventory_output_type_list = $this->inventory_output_type_model->get_inventory_output_type_enable();
			echo json_encode($inventory_output_type_list);
		} else {
			show_404();
		}
	}

	/*Para cargar la lista de tipo de ingreso de inventario en el dataTable*/
	public function get_inventory_output_type_list()
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

			echo json_encode($this->inventory_output_type_model->get_inventory_output_type_list($params));
		} else {
			show_404();
		}
	}

}
