<?php

class Dosage_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}


	public function get_number_printer_by_branch_office_id($branch_office_id)
	{
		$this->db->distinct();
		$this->db->select('impresora_id');
		$this->db->from('asignacion_dosificacion');
		$this->db->where('sucursal_id', $branch_office_id);
		return $this->db->get()->num_rows();
	}

	public function get_printer_by_branch_office_id($branch_office_id)
	{

		$this->db->select('distinct(a.impresora_id),i.marca,i.serial');
		$this->db->from('asignacion_dosificacion a,impresora i');
		$this->db->where('a.impresora_id=i.id');
		$this->db->where('a.sucursal_id', $branch_office_id);
		return $this->db->get()->result();
	}

	//obtener dosificaciones activas
	public function get_dosage_active()
	{
		$this->db->select('d.*,a.nombre as nombre_actividad, i.marca, i.serial');
		$this->db->from('dosificacion d, asignacion_dosificacion ad, impresora i, actividad a');
		$this->db->where(' d.asignacion_dosificacion_id = ad.id');
		$this->db->where(' ad.actividad_id = a.id');
		$this->db->where(' ad.impresora_id = i.id');
		$this->db->where('ad.sucursal_id', get_branch_id_in_session());
		$this->db->where(' d.estado=1');
		return $this->db->get()->result();
	}

	//obterner dosificaciones vencidas
	public function get_dosage_expired()
	{
		$this->db->select('count(d.id) AS vencidas');
		$this->db->from('dosificacion d, asignacion_dosificacion ad');
		$this->db->where(' d.asignacion_dosificacion_id=ad.id');
		$this->db->where('ad.sucursal_id', get_branch_id_in_session());
		$this->db->where('d.fecha_limite > d.fecha_solicitada');
		$this->db->where(' d.estado=1');
		return $this->db->get()->result();
	}

	//obterner dosificaciones inactivas
	public function get_dosage_disable()
	{
		$this->db->select('count(d.id) AS dosage_inactivas');
		$this->db->from('dosificacion d, asignacion_dosificacion ad');
		$this->db->where(' d.asignacion_dosificacion_id=ad.id');
		$this->db->where('ad.sucursal_id', get_branch_id_in_session());
		$this->db->where(' d.estado=2');
		return $this->db->get()->result();
	}

	/*Obtener datos del cliente apartit del cliente_id*/
	public function get_dosage_id($dosage_id)
	{
		return $this->db->get_where('dosificacion', array('id' => $dosage_id))->row();
	}

	/*Obtener lista de cliente para cargar la lista de dataTable*/
	public function get_inactive_dosage_list($params = array())
	{
		$this->db->start_cache();
		$this->db->select('dos.id,dos.autorizacion,dos.nro_inicio,dos.llave, dos.fecha_registro,dos.fecha_solicitada,dos.fecha_limite,dos.leyenda,dos.estado,asd.sucursal_id,asd.impresora_id,asd.actividad_id,suc.nombre as sucursal,act.nombre as actividad,imp.marca as marca,dos.asignacion_dosificacion_id');
		$this->db->from('dosificacion dos');
		$this->db->join('asignacion_dosificacion asd', 'dos.asignacion_dosificacion_id=asd.id');
		$this->db->join('impresora imp', 'imp.id=asd.impresora_id');
		$this->db->join('sucursal suc', 'suc.id=asd.sucursal_id');
		$this->db->join('actividad act', 'act.id=asd.actividad_id');
		$this->db->where('dos.estado=0');

		$this->db->stop_cache();

		// Obtener la cantidad de registros NO filtrados.
		// Query builder se mantiene ya que se tiene almacenada la estructura de la consulta en memoria
		$records_total = count($this->db->get()->result_array());


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

	public function get_enable_dosage_list($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$valor = 1;
		$this->db->start_cache();
		$this->db->select('*,suc.nombre as sucursal,act.nombre as actividad,imp.marca as marca');
		$this->db->from('dosificacion dos');
		$this->db->join('asignacion_dosificacion asd', 'dos.asignacion_dosificacion_id=asd.id');
		$this->db->join('impresora imp', 'imp.id=asd.impresora_id');
		$this->db->join('sucursal suc', 'suc.id=asd.sucursal_id');
		$this->db->join('actividad act', 'act.id=asd.actividad_id');
		$this->db->where('dos.estado', $valor);

		$this->db->stop_cache();

		// Obtener la cantidad de registros NO filtrados.
		// Query builder se mantiene ya que se tiene almacenada la estructura de la consulta en memoria
		$records_total = count($this->db->get()->result_array());


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

	public function get_caducated_dosage_list($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$valor = 2;
		$this->db->start_cache();
		$this->db->select('*,suc.nombre as sucursal,act.nombre as actividad,imp.marca as marca');
		$this->db->from('dosificacion dos');
		$this->db->join('asignacion_dosificacion asd', 'dos.asignacion_dosificacion_id=asd.id');
		$this->db->join('impresora imp', 'imp.id=asd.impresora_id');
		$this->db->join('sucursal suc', 'suc.id=asd.sucursal_id');
		$this->db->join('actividad act', 'act.id=asd.actividad_id');
		$this->db->where('dos.estado', $valor);

		$this->db->stop_cache();

		// Obtener la cantidad de registros NO filtrados.
		// Query builder se mantiene ya que se tiene almacenada la estructura de la consulta en memoria
		$records_total = count($this->db->get()->result_array());


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


	private function _insert_asignacion_dosage($asignacion_dosage)
	{
		return $this->db->insert('asignacion_dosificacion', $asignacion_dosage);
	}


	/*Funcion registrar nuevo cliente para validar los datos de la vista */
	public function register_dosage()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'login' => FALSE
		);

		if (verify_session()) {

			// Reglas de validacion
			$config = array(
				array(
					'field' => 'autorizacion',
					'label' => 'Numero de Autorizacion',
					'rules' => 'required'
				)
			);

			$this->form_validation->set_rules($config);

			// Pasar reglas de validacion como parámetro

			$this->form_validation->set_error_delimiters('<p class="abm-error">', '</p>');


			if ($this->form_validation->run() === TRUE) {
				$activity_id = $this->input->post('actividad');
				$printer_id = $this->input->post('impresora');
				$branch_office_id = $this->input->post('sucursal');


				$assignment_dosage = $this->get_assignment_dosage($branch_office_id, $activity_id, $printer_id);

				/*Inicio de transacción*/
				$this->db->trans_begin();
				if ($assignment_dosage != null) {
					$assignment_dosage_id = $assignment_dosage->id;
				} else {
					$data_assignment_dosage = array(
						'actividad_id' => $activity_id,
						'impresora_id' => $printer_id,
						'sucursal_id' => $branch_office_id
					);
					$this->_insert_asignacion_dosage($data_assignment_dosage);
					$assignment_dosage_id = $this->db->insert_id();
				}

				$data_dosage = array(
					'autorizacion' => $this->input->post('autorizacion'),
					'nro_inicio' => 1,
					'llave' => $this->input->post('llave'),
					'fecha_registro' => date('Y-m-d H:i:s'),
					'fecha_solicitada' => $this->input->post('fecha_solicitada'),
					'fecha_limite' => $this->input->post('fecha_limite'),
					'leyenda' => $this->input->post('leyenda'),
					'estado' => ANULADO,
					'asignacion_dosificacion_id' => $assignment_dosage_id,
					'usuario_id' => get_user_id_in_session()
				);

				// Registrar ala base de datos la nuevadosificacion
				$this->_insert_dosage($data_dosage);

				$last_dosage_id = $this->db->insert_id();

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

	public function printer_invoice()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'login' => FALSE
		);

		if (verify_session()) {
			$printer_id = $this->input->post('printer_id');
			$dosage = array(
				'id_printer' => $printer_id
			);

			$this->session->set_userdata('dosage', $dosage);
				$response['success'] = TRUE;
		} else {
			$response['login'] = TRUE;
		}

		echo json_encode($response);
	}

	public function get_activated_dosage_by_id($asignacion_dosficacion_id)
	{
		$this->db->select('*');
		$this->db->from('dosificacion dos');
		$this->db->join('asignacion_dosificacion asd', 'asd.id=dos.asignacion_dosificacion_id');
		$this->db->join('impresora imp', 'imp.id=asd.impresora_id');
		$this->db->join('sucursal suc', 'suc.id=asd.sucursal_id');
		$this->db->join('actividad act', 'act.id=asd.actividad_id');
		$this->db->where('dos.asignacion_dosificacion_id', $asignacion_dosficacion_id);
		$this->db->where('dos.estado', 1);

		$exite_dosificacion = $this->db->get();
		// Verificamos si el menu padre de la funcion analida existe,
		// si es cero => no existe
		// si es uno => existe;
		if ($exite_dosificacion->num_rows() > 0) {
			return true;
		} else {
			return false;
		}


	}

	public function activate_dosage()
	{
		$asignacion_dosficacion_id = $this->input->post('asignacion_dosificacion_id');
		$res = $this->get_activated_dosage_by_id($asignacion_dosficacion_id);
		if ($res == false) {
			$id = $this->input->post('id');
			return $this->db->update(
				'dosificacion',
				['estado' => get_state_abm('ACTIVO')],
				['id' => $id]
			);
		}
	}

	public function get_assignment_dosage($branch_office_id, $activity_id, $printer_id)
	{

		$this->db->select('*');
		$this->db->from('asignacion_dosificacion');
		$this->db->where('sucursal_id', $branch_office_id);
		if ($activity_id > 0) {
			$this->db->where('actividad_id', $activity_id);
		}
		if ($printer_id > 0) {
			$this->db->where('impresora_id', $printer_id);
		}

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	public function get_active_dosage_by_assignment_id($dosage_assignment_id)
	{

		$this->db->select('*');
		$this->db->from('dosificacion');
		$this->db->where('asignacion_dosificacion_id', $dosage_assignment_id);
		$this->db->where('estado', ACTIVO);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}

	}

	/*Funcion privada para insertar en la base de datos al nuevo cliente*/
	private function _insert_dosage($dosage)
	{
		return $this->db->insert('dosificacion', $dosage);
	}


}
