<?php

class Reference_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_reference_list($params = array()){

        // Se cachea la informacion que se arma en query builder
        //echo $array_sub;
        $this->db->start_cache();
        $this->db->select('*')
            ->from('referencia')
            ->where('estado = 1');
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
            $this->db->or_like('lower(telefono)', strtolower($params['search']));
            $this->db->group_end();
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

    /*Funcion registrar nueva solucion para validar los datos de la vista */
    public function register_reference()
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
                    'field' => 'name_reference',
                    'label' => 'El nombre de la referencia debe ser unico y solo permite numeros y letras',
                    'rules' => 'trim|required|is_unique[referencia.nombre]'
                )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data_reference = array(
                    'nombre' => text_format($this->input->post('name_reference')),
                    'telefono' => text_format($this->input->post('phone_reference')),
                    'direccion' => text_format($this->input->post('address_reference')),
                    'estado' => ACTIVO
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar solucion ala base de datos
                $this->_insert($data_reference);

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
        } else{
            $response['login'] = TRUE;
        }
        echo json_encode($response);
    }

    /*Obtener los todos las referencias activos especial para cargar combos o autocompletados*/
    public function get_references_enable($type = 'object')
    {
        return $this->db->get_where('referencia', array('estado' => ACTIVO))->result($type);
    }

    /*Funcion para actualizar actualizar para validar los datos de la vista */
    public function modify()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $reference_id = $this->input->post('id');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'name_reference_edit',
                    'label' => 'Nombre',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, referencia, nombre]", $reference_id)
                )
            );


            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
            $this->form_validation->set_message('is_unique_edit', 'Nombre ya existe');


            if ($this->form_validation->run() === TRUE) {
                $reference = array(
                    'nombre' => text_format($this->input->post('name_reference_edit')),
                    'telefono' => text_format($this->input->post('phone_reference_edit')),
                    'direccion' => text_format($this->input->post('address_reference_edit'))
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar el modelo
                $this->_update($reference_id, $reference);

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

    /*Funcion para desabilitar*/
    public function disable()
    {
        $reference_id = $this->input->post('id');
        $reference = array(
            'estado' => 0
        );
        // Actualizar el modelo
        return $this->_update($reference_id, $reference);

    }


    /////////////////////////////////////////////////////////////////////////
    /////////////////////// FUNCIONES PRIVADAS   ////////////////////////////
    /// /////////////////////////////////////////////////////////////////////

    /*Funcion privada para insertar en la base de datos la nueva referencia*/
    private function _insert($reference)
    {
        return $this->db->insert('referencia', $reference);
    }

    /*Funcion privada para actualizar en la base de datos ala nueva referencia*/
    private function _update($reference_id, $reference)
    {
        $where = array('id' => $reference_id);
        return $this->db->update('referencia', $reference, $where);
    }


}