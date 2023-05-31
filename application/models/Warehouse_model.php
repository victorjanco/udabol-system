<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 20/7/2017
 * Time: 7:03 PM
 */
class Warehouse_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*Obtener todas las almacenes activos especial para cargar combos o autocompletados*/
	public function get_warehouse_enable($type = 'object')
	{
		return $this->db->get_where('almacen', array('estado' => get_state_abm('ACTIVO'), 'sucursal_id' => get_branch_id_in_session()))->result($type);
	}
	
	public function get_warehouse_all($type = 'object')
	{
		return $this->db->get_where('almacen', array('estado' => get_state_abm('ACTIVO')))->result($type);
	}

	/*Obtener datos del almacen apartir del almacen_id*/
	public function get_warehouse_id($warehouse_id)
	{
		return $this->db->get_where('almacen', array('id' => $warehouse_id))->row();
	}

	/*Obtener lista de almacenes para cargar la lista de dataTable*/
	public function get_warehouse_list($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('a.*, t.nombre as nombre_tipo, s.nombre_comercial')
			->from('almacen a,  tipo_almacen t, sucursal s')
			->where('a.tipo_almacen_id = t.id')
			->where('a.sucursal_id = s.id')
			->where('a.estado',ACTIVO)
			->where('a.sucursal_id', get_branch_id_in_session());
		$this->db->stop_cache();

		// Obtener la cantidad de registros NO filtrados.
		// Query builder se mantiene ya que se tiene almacenada la estructura de la consulta en memoria
		$records_total = count($this->db->get()->result_array());

		// Concatenar parametros enviados (solo si existen)
		if (array_key_exists('start', $params) && array_key_exists('limit', $params)) {
			$this->db->limit($params['limit']);
			$this->db->offset($params['start']);
		}

		if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
			$this->db->order_by($params['column'], $params['order']);
		} else {
			$this->db->order_by('a.id', 'ASC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->like('lower(a.nombre)', strtolower($params['search']));
		}

		// Obtencion de resultados finales
		$draw = $this->input->post('draw');
		$data = $this->db->get()->result_array();

		$json_data = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_total,
			'data' => $data,
		);
		return $json_data;
	}

	/*Funcion registrar nuevo almacen para validar los datos de la vista */
	public function register_warehouse()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'login' => FALSE
		);

		if (verify_session()) {

			// Reglas de validacion
			$validation_rules = array(
				array(
					'field' => 'nombre_almacen',
					'label' => 'El nombre del nuevo almacen debe ser unico y solo permite numeros y letras',
					'rules' => 'trim|required|alpha_numeric_spaces|is_unique[almacen.nombre]'
				),
				array(
					'field' => 'descripcion_almacen',
					'label' => '',
					'rules' => 'trim|required|alpha_numeric_spaces'
				),
				array(
					'field' => 'direccion_almacen',
					'label' => 'Debe ingresar datos alfanumericos',
					'rules' => 'trim|required|alpha_numeric_spaces'
				),

				array(
					'field' => 'sucursal_almacen',
					'label' => 'Debe seleccionar una marca por favor',
					'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->office_model->get_offices(), 'id'))
				),
				array(
					'field' => 'tipo_almacen_almacen',
					'label' => 'Debe seleccionar una marca por favor',
					'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->type_warehouse_model->get_type_warehouse_enable(), 'id'))
				)
			);

			// Pasar reglas de validacion como parámetro
			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


			if ($this->form_validation->run() === TRUE) {
				$data_warehouse = array(
					'nombre' => strtoupper($this->input->post('nombre_almacen')),
					'descripcion' => strtoupper($this->input->post('descripcion_almacen')),
					'direccion' => strtoupper($this->input->post('direccion_almacen')),
					'estado' => get_state_abm('ACTIVO'),
					'sucursal_id' => $this->input->post('sucursal_almacen'),
					'tipo_almacen_id' => $this->input->post('tipo_almacen_almacen'),
					'user_created' => get_user_id_in_session(),
                    'date_created' => date('Y-m-d H:i:s')
				);

				// Inicio de transacción
				$this->db->trans_begin();

				// Registrar a la base de datos de nuevo modelo
				$this->_insert_warehouse($data_warehouse);

				// Obtener resultado de transacción
				if ($this->db->trans_status() === TRUE) {
					$this->db->trans_commit();
					$response['success'] = TRUE;
				} else {
					$this->db->trans_rollback();
					$response['success'] = FALSE;
				}
			} else {
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}

		} else {
			$response['login'] = TRUE;
		}

		echo json_encode($response);
	}

	/*Funcion para actualizar el almacen para validar los datos de la vista */
	public function modify_warehouse()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'login' => FALSE
		);

		$warehouse_id = $this->input->post('id_almacen_edit');

		if (verify_session()) {

			// Reglas de validacion
			$validation_rules = array(
				array(
					'field' => 'nombre_almacen_edit',
					'label' => 'El nombre del nuevo almacen debe ser unico y solo permite numeros y letras',
					'rules' => sprintf("trim|required|is_unique_edit[%u, almacen, nombre]", $warehouse_id)
				),
				array(
					'field' => 'descripcion_almacen_edit',
					'label' => 'En nombre de la descripcion tiene caracteristicas especiales',
					'rules' => 'trim|required|alpha_numeric_spaces'
				),
				array(
					'field' => 'direccion_almacen_edit',
					'label' => 'Debe ingresar datos alfanumericos',
					'rules' => 'trim|required|alpha_numeric_spaces'
				),

				array(
					'field' => 'sucursal_almacen_edit',
					'label' => 'Debe seleccionar una marca por favor',
					'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->office_model->get_offices(), 'id'))
				),
				array(
					'field' => 'tipo_almacen_almacen_edit',
					'label' => 'Debe seleccionar una marca por favor',
					'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->type_warehouse_model->get_type_warehouse_enable(), 'id'))
				)

			);


			// Pasar reglas de validacion como parámetro
			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


			if ($this->form_validation->run() === TRUE) {
				$data_warehouse = array(
					'nombre' => strtoupper($this->input->post('nombre_almacen_edit')),
					'descripcion' => strtoupper($this->input->post('descripcion_almacen_edit')),
					'direccion' => strtoupper($this->input->post('direccion_almacen_edit')),
					'sucursal_id' => $this->input->post('sucursal_almacen_edit'),
					'tipo_almacen_id' => $this->input->post('tipo_almacen_almacen_edit'),
					'user_updated' => get_user_id_in_session(),
                    'date_updated' => date('Y-m-d H:i:s')
				);

				// Inicio de transacción
				$this->db->trans_begin();

				// Actualizar el modelo
				$this->_update_warehouse($warehouse_id, $data_warehouse);

				// Obtener resultado de transacción
				if ($this->db->trans_status() === TRUE) {
					$this->db->trans_commit();
					$response['success'] = TRUE;
				} else {
					$this->db->trans_rollback();
					$response['success'] = FALSE;
				}
			} else {
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}

		} else {
			$response['login'] = TRUE;
		}

		echo json_encode($response);
	}

	/*Funcion para desabilitar el almacen*/
	public function disable_warehouse()
	{
		$id = $this->input->post('id');
		return $this->db->update(
			'almacen',
			['estado' => get_state_abm('INACTIVO'),
			 'user_updated' => get_user_id_in_session(),
             'date_updated' => date('Y-m-d H:i:s')],
			['id' => $id]
		);
	}
	/*Funcion privada para insertar en la base de datos del nuevo modelo*/
	private function _insert_warehouse($warehouse)
	{
		return $this->db->insert('almacen', $warehouse);
	}

	/*Funcion privada para actualizar en la base de datos la modelo*/
	private function _update_warehouse($warehouse_id, $data_warehouse)
	{
		$where = array('id' => $warehouse_id);
		return $this->db->update('almacen', $data_warehouse, $where);
	}

	/* Funcion para obtener los almacenes por sucursal logeado*/
	public function get_warehouse_brand_office()
	{
		$sucursal_id = 1;
		return $this->db->get_where('almacen', array('estado' => get_state_abm('ACTIVO'), 'sucursal_id' => $sucursal_id))->result();
	}

	/* Funcion para obtener los almacenes por sucursal seleccionada*/
	public function get_warehouse_by_branch_office_id()
	{
		$branch_office_id = $this->input->post('branch_office_id');
		return $this->db->get_where('almacen', array('estado' => get_state_abm('ACTIVO'), 'sucursal_id' => $branch_office_id))->result();
	}

	/* Funcion para obtener los almacenes por sucursal seleccionada por parametro*/
	public function get_warehouse_branch_office_id($branch_office_id)
	{
		$branch_office_id = $branch_office_id;
		return $this->db->get_where('almacen', array('estado' => get_state_abm('ACTIVO'), 'sucursal_id' => $branch_office_id))->result();
	}

	// Obtener el almacen tecnico por el sucursal_id   // TECNICO=8
	public function get_warehouse_technical($branch_office_id)
	{
		return $this->db->get_where('almacen', array('tipo_almacen_id' => 8, 'sucursal_id' => $branch_office_id))->row();
	}

	/*obtener almacen sin garantia del cada sucursal*/
	public function get_warehouse_without_guarantee($branch_office_id)
	{

		$warehouse = $this->db->get_where('almacen', array('tipo_almacen_id' => 9, 'sucursal_id' => $branch_office_id, 'estado' => ACTIVO))->result();
		$list_warehouse = array();
		if (count($warehouse) > 0) {
			foreach ($warehouse as $row) {
				array_push($list_warehouse, intval($row->id));
			}
		} else {
			$this->db->select('*')
				->from('almacen')
				->where('tipo_almacen_id!=', 9)
				->where('tipo_almacen_id!=', 10)
				->where('estado', ACTIVO)
				->where('sucursal_id', $branch_office_id);
			$warehouse = $this->db->get()->result();
			foreach ($warehouse as $row) {
				array_push($list_warehouse, intval($row->id));
			}
		}
		return $list_warehouse;
	}


	/*obtener almacen con garantia del cada sucursal*/
	public function get_warehouse_with_guarantee($branch_office_id)
	{
		$warehouse = $this->db->get_where('almacen', array('tipo_almacen_id' => 10, 'sucursal_id' => $branch_office_id, 'estado' => ACTIVO))->result();
		$list_warehouse = array();
		if (count($warehouse) > 0) {
			foreach ($warehouse as $row) {
				array_push($list_warehouse, intval($row->id));
			}
		} else {
			$this->db->select('*')
				->from('almacen')
				->where('tipo_almacen_id!=', 9)
				->where('tipo_almacen_id!=', 10)
				->where('estado', ACTIVO)
				->where('sucursal_id', $branch_office_id);
			$warehouse = $this->db->get()->result();
			foreach ($warehouse as $row) {
				array_push($list_warehouse, intval($row->id));
			}
		}

		return $list_warehouse;
	}

	public function get_warehouse_other($branch_office_id)
	{
		$this->db->select('*')
			->from('almacen')
			->where('tipo_almacen_id!=', 9)
			->where('tipo_almacen_id!=', 10)
			->where('estado', ACTIVO)
			->where('sucursal_id', $branch_office_id);
		$warehouse = $this->db->get()->result();
		$list_warehouse = array();
		foreach ($warehouse as $row) {

			array_push($list_warehouse, intval($row->id));
		}
		return $list_warehouse;
	}

	// Obtener almacen for el tipo de almacen
	public function get_warehouse_for_type_warranty($type_warranty)
    {
        $this->db->select('*')
            ->from('almacen')
            ->where('tipo_almacen_id', $type_warranty)
            ->where('sucursal_id', get_branch_id_in_session())
            ->where('estado', ACTIVO);
        return $this->db->get()->result();
	}
	
	// Obtener almacen for el tipo de almacen
	public function get_warehouse_for_sin_garantia()
    {
        $this->db->select('*')
            ->from('almacen')
            ->where('tipo_almacen_id', 8)
            ->where('sucursal_id', get_branch_id_in_session())
            ->where('estado', ACTIVO);
        return $this->db->get()->result();
    }
}
