<?php
/**
 *
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato
 * Date: 22/10/2019
 * Time: 14:35 PM
 */

class Color_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_country_by_parameter($attribute, $parameter)
	{
		$this->db->select('*')
			->from('color')
			->where($attribute, $parameter)
			->where('estado', ACTIVO);
		return $this->db->get()->row();
	}
	public function get_color_list($params = array())
	{
		$this->db->start_cache();
		$this->db->select('*')
			->from('color');
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
			$this->db->or_like('lower(descripcion)', strtolower($params['search']));
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

	public function register_color()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'login' => FALSE
		);

		$validation_rules = array(
			array(
				'field' => 'add_name',
				'label' => 'Nombre del color',
				'rules' => 'trim|required|is_unique[color.nombre]|strtoupper'
			)
		);

		if (verify_session()) {

			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');
			$this->form_validation->set_message('is_unique', 'El Nombre del color ingresado ya existe.');

			if ($this->form_validation->run() === TRUE) {

				$data_color = array(
					'nombre' => strtoupper($this->input->post('add_name')),
					'descripcion' => strtoupper($this->input->post('add_description')),
					'fecha_registro' => date('Y-m-d H:i:s'),
					'estado' => ACTIVO,
					'usuario_registro_id' => get_user_id_in_session(),
					'sucursal_registro_id' => get_branch_id_in_session()
				);

				$this->db->trans_begin();
				$this->_insert_color($data_color);

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

	public function modify_color()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'login' => FALSE
		);

		$color_id = $this->input->post('color_id');

		if (verify_session()) {

			$validation_rules = array(
				array(
					'field' => 'edit_name',
					'label' => 'Nombre de la unidad de medida',
					'rules' => sprintf('trim|required|is_unique_edit[%u, color, nombre]|strtoupper', $color_id)
				)
			);

			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
			$this->form_validation->set_message('is_unique_edit', 'El Nombre del color ingresado ya existe.');

			if ($this->form_validation->run() === TRUE) {

				$data_color = array(
					'nombre' => strtoupper($this->input->post('edit_name')),
					'descripcion' => strtoupper($this->input->post('edit_description')),
					'fecha_modificacion' => date('Y-m-d H:i:s'),
					'usuario_modificacion_id' => get_user_id_in_session(),
					'sucursal_modificacion_id' => get_branch_id_in_session()
				);

				$this->db->trans_begin();
				$this->_update_color($color_id, $data_color);

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

	public function disable_color()
	{
		$id = $this->input->post('id');
		return $this->db->update(
			'color',
			['estado' => ANULADO],
			['id' => $id]
		);
	}

	public function get_active_colors()
	{
		$this->db->select('*')
			->from('color')
			->where('estado', ACTIVO);
		$this->db->order_by('id', 'ASC');
		return $this->db->get()->result();
	}

	public function exists_color_by_parameter($attribute, $parameter)
	{
		$this->db->select('*')
			->from('color')
			->where($attribute, $parameter)
			->where('estado', ACTIVO);

		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			// SI EXISTE
			return 1;
		} else {
			// NO EXISTE
			return 0;
		}
	}

	public function get_color_by_parameter($attribute, $parameter)
	{
		$this->db->select('*')
			->from('color')
			->where($attribute, $parameter)
			->where('estado', ACTIVO);
		return $this->db->get()->row();
	}

	public function get_data_color($data_color)
	{
		return $this->db->get_where('color', $data_color)->row();
	}


	private function _insert_color($data_color)
	{
		return $this->db->insert('color', $data_color);
	}

	private function _update_color($color_id, $data_color)
	{
		$where = array('id' => $color_id);
		return $this->db->update('color', $data_color, $where);
	}

}
