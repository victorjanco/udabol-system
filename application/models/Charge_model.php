<?php

class Charge_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las cargo activas especial para cargar combos o autocompletados*/
    public function get_charge_enable($type = 'object')
    {
        return $this->db->get_where('cargo', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos de la cargo apartir del cargo_id*/
    public function get_charge_id($charge_id)
    {
        return $this->db->get_where('cargo', array('id' => $charge_id))->row();
    }

    /*Obtener lista de cargos para cargar la lista de dataTable*/
    public function get_charge_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('cargo');
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
            $this->db->order_by('id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(descripcion)', strtolower($params['search']));
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

    /*Funcion registrar nueva cargo para validar los datos de la vista */
    public function register_charge()
    {
        try {
            $response = array(
                'success' => FALSE,
                'messages' => array(),
                'login' => FALSE
            );
    
            if (verify_session()) {
    
                // Reglas de validacion
                $validation_rules = array(
                    
                    array(
                        'field' => 'description',
                        'label' => 'La descripcion de la cargo debe ser unico y solo permite numeros y letras',
                        'rules' => 'trim|required|alpha_numeric_spaces|is_unique[cargo.descripcion]'
                    )
    
                );
    
                // Pasar reglas de validacion como parámetro
                $this->form_validation->set_rules($validation_rules);
                $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
    
    
                if ($this->form_validation->run() === TRUE) {
                    $data_charge = array(
                        'descripcion' => strtoupper($this->input->post('description')),
                        'estado' => 1
                    );
    
                    // Inicio de transacción
                    $this->db->trans_begin();
    
                    // Registrar a la base de datos la nueva cargo
                    $this->_insert_charge($data_charge);
    
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
    
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*Funcion para actualizar la cargo para validar los datos de la vista */
    public function modify_charge()
    {
        try {
            $response = array(
                'success' => FALSE,
                'messages' => array(),
                'login' => FALSE
            );
    
            $charge_id = $this->input->post('charge_id');
    
            if (verify_session()) {
    
                // Reglas de validacion
                $validation_rules = array(
                    array(
                        'field' => 'description_edit',
                        'label' => 'El nombre de la cargo debe ser unico y solo permite numeros y letras',
                        'rules' => sprintf("trim|required|is_unique_edit[%u, cargo, descripcion]", $charge_id)
                    )
    
                );
    
                // Pasar reglas de validacion como parámetro
                $this->form_validation->set_rules($validation_rules);
                $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');
    
                if ($this->form_validation->run() === TRUE) {
                    $data_charge = array(
                        'descripcion' => strtoupper($this->input->post('description_edit')),
                    );
    
                    // Inicio de transacción
                    $this->db->trans_begin();
    
                    // Actualizar la cargo
                    $this->_update_charge($charge_id, $data_charge);
    
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
    
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    /*Funcion para desabilitar el tipo almacen*/
    public function disable_charge()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'cargo',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Retorna el modelo y la cargo del producto */
    public function get_charge_model($model_id){
        $this->db->select('mar.*')
            ->from('cargo mar')
            ->join('modelo mod','mod.cargo_id = mar.id')
            ->where('mod.id', $model_id);
        return $this->db->get()->row();
    }

    /*Funcion privada para insertar en la base de datos de la nueva cargo*/
    private function _insert_charge($charge)
    {
        return $this->db->insert('cargo', $charge);
    }

    /*Funcion privada para actualizar en la base de datos la cargo*/
    private function _update_charge($charge_id, $data_charge)
    {
        $where = array('id' => $charge_id);
        return $this->db->update('cargo', $data_charge, $where);
    }

    public function exists_charge_name($name_charge)
    {
        $this->db->select('*')
            ->from('cargo')
            ->where('nombre', $name_charge)
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

    public function get_charge_by_name($name_charge){
        $this->db->select('*')
            ->from('cargo')
            ->where('nombre', $name_charge)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    public function get_charge($data_charge)
    {
        return $this->db->get_where('cargo', $data_charge)->row();
    }
}