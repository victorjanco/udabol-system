<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 17/07/2017
 * Time: 02:24 PM
 */
class Company extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('company_model');
		$this->load->model('office_model');
	}


	public function index()
	{
		template('company/index');
	}


	//region Metodos de Actividad

	public function registrer_activity()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->company_model->registrer_activity());
		} else {
			show_404();
		}
	}


	public function edit_activity()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->company_model->edit_activity());
		} else {
			show_404();
		}
	}

	public function delete_activity()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->company_model->delete_activity());
		} else {
			show_404();
		}
	}

	public function get_activitys()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->company_model->get_activitys());
		} else {
			show_404();
		}
	}

	public function get_activity_list()
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

			echo json_encode($this->company_model->get_activity_list($params));
		} else {
			show_404();
		}
	}
	//endregion


	//region Metodos de Sucursales

	// Registro de sucursales
	public function register_branch_office()
	{
		try {
			throw new Exception("No puede crear mas sucursales");
			
			if ($this->input->is_ajax_request()) {
				echo $this->office_model->register_branch_office();
			} else {
				show_404();
			}
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	// Edicion de sucursal
	public function edit_branch_office()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->office_model->edit_branch_office();
		} else {
			show_404();
		}
	}

	// Eliminacion de sucursales
	public function delete_office()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->office_model->delete_office());
		} else {
			show_404();
		}
	}

	// volver habilitar la sucursal
	public function reactivate_branch_office()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->office_model->reactivate_branch_office());
		} else {
			show_404();
		}
	}

	/*Para obtener datos de una sucursal en especifico por el id de la sucursal*/
	public function get_branch_office_id()
	{
		if ($this->input->is_ajax_request()) {
			$branch_office_id = $this->input->post('id');
			echo json_encode($this->office_model->get_branch_office_id($branch_office_id));
		} else {
			show_404();
		}
	}

	// Obtener listado de sucursales
	public function get_offices()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->office_model->get_offices());
		} else {
			show_404();
		}
	}

	/*Para cargar la lista de clientes en el dataTable*/
	public function get_branch_office_list()
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

			echo json_encode($this->office_model->get_branch_office_list($params));
		} else {
			show_404();
		}
	}
	//endregion

	// Registrar almacen a las sucursales que no tienen almacen tecnico.
	public function register_technical_warehouse_at_branches()
	{
		$this->db->trans_begin();

		$list_branch_office = $this->office_model->get_offices();
		foreach ($list_branch_office as $branch_office) {
			$branch_office_id = $branch_office->id;

			if ($this->exists_technical_warehouse($branch_office_id) == 0) {
				$data_warehouse = array(
					'nombre' => 'ALMACEN TECNICO',
					'descripcion' => 'ALMACEN TECNICO',
					'direccion' => 'TECNICO',
					'estado' => 0,
					'sucursal_id' => $branch_office_id,
					'usuario_id' => get_user_id_in_session(),
					'tipo_almacen_id' => 8
				);

				// Registrar almacen del tecnico
				$this->db->insert('almacen', $data_warehouse);
			}

		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo "ALGO SALIO MAL";
		} else {
			$this->db->trans_commit();
			echo "CORRECTO";
		}
	}

	// Existe almacen tecnico en la sucursal_id
	public function exists_technical_warehouse($branch_office_id)
	{
		$this->db->select('*')
			->from('almacen')
			->where('sucursal_id', $branch_office_id)
			->where('tipo_almacen_id', 8);

		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			// SI EXISTE
			return 1;
		} else {
			// NO EXISTE
			return 0;
		}
	}
}
