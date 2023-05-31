<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 17/07/2017
 * Time: 03:00 PM
 */
class Company_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	// Metodo de registro de actividad
	public function registrer_activity()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'login' => FALSE
		);

		if (verify_session()) {

			/* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
			$this->form_validation->set_rules('nombre_actividad', 'nombre actividad', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			if ($this->form_validation->run() === true) {
				/** OBTENERMOS VALORES DE LOS INPUT **/
				$activity['nombre'] = $this->input->post('nombre_actividad');
				$activity['usuario_id'] = get_user_id_in_session();
				$activity['estado'] = get_state_abm('ACTIVO');
				$response['success'] = $this->db->insert('actividad', $activity);
			} else {
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}

		} else {
			$response['login'] = TRUE;
		}

		return $response;
	}


	public function register_brand()
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
					'field' => 'nombre_actividad',
					'label' => 'El campo codigo no puede ser vacio',
					'rules' => 'trim|required'
				)

			);

			// Pasar reglas de validacion como parámetro
			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


			if ($this->form_validation->run() === TRUE) {
				$data = array(
					'nombre' => $this->input->post('nombre_actividad'),
					'usuario_id' => get_user_id_in_session(),
					'estado' => ACTIVO,
				);

				// Inicio de transacción
				$this->db->trans_begin();

				// Registrar a la base de datos la nueva marca
				$this->_insert_activity($data);

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

	private function _insert_activity($activity)
	{
		return $this->db->insert('actividad', $activity);
	}

	/** Metodo para editar actividad seleccionada */
	public function edit_activity()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'login' => FALSE
		);

		if (verify_session()) {

			/* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
			$this->form_validation->set_rules('nombre_actividade', 'nombre actividad', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');

			if ($this->form_validation->run() === true) {
				$this->db->where('id', $this->input->post('id_actividade'));
				$response['success'] = $this->db->update('actividad', array('nombre' => $this->input->post('nombre_activida')));
			} else {
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}

		} else {
			$response['login'] = TRUE;
		}

		return $response;
	}

	/** Metodo para eliminar la actividad seleccionada */
	public function delete_activity()
	{
		$this->db->where('id', $this->input->post('id'));
		return $this->db->update('actividad', array('estado' => ANULADO));
	}

	/** Metodos para obtener todas las actividades activas **/
	public function get_activitys($type = 'object')
	{
		return $this->db->get_where('actividad', array('estado' => ACTIVO))->result($type);
	}

	public function get_activity_list($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('*')
			->from('actividad')
			->where('estado',ACTIVO);
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
			$this->db->order_by('id', 'DESC');
		}

		if (array_key_exists('search', $params)) {
			$this->db->group_start();
			$this->db->like('lower(nombre)', strtolower($params['search']));
			$this->db->group_end();
			$this->db->order_by('id', 'DESC');
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

	public function get_company()
	{
		return $this->db->get('system_parameters')->row();
	}

	public function get_activity_id($activity_id)
	{
		return $this->db->get_where('actividad', array('id' => $activity_id))->row();
	}

}
