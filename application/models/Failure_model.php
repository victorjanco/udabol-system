<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 17/7/2017
 * Time: 6:20 PM
 */
class Failure_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las fallas activas especial para cargar combos o autocompletados*/
    public function get_failure_enable($type = 'object')
    {
        return $this->db->get_where('falla', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos de la falla apartir del falla_id*/
    public function get_failure_id($failure_id)
    {
        return $this->db->get_where('falla', array('id' => $failure_id))->row();
    }

    /*Obtener lista de fallas para cargar la lista de dataTable*/
    public function get_failure_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('falla');
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
            $this->db->like('lower(nombre)', strtolower($params['search']));
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

    /*Funcion registrar nueva falla para validar los datos de la vista */
    public function register_failure()
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
                    'field' => 'nombre',
                    'label' => 'El nombre de la falla debe ser unico y solo permite numeros y letras',
                    'rules' => 'trim|required|alpha_numeric_spaces|is_unique[falla.nombre]'
                )
                // array(
                //     'field' => 'descripcion',
                //     'label' => 'El campo descripcion no puede ser vacio',
                //     'rules' => 'trim|required'
                // )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data_failure = array(
                    'nombre' => text_format($this->input->post('nombre')),
                    'descripcion' => text_format($this->input->post('descripcion')),
                    'estado' => get_state_abm('ACTIVO'),
                    'sucursal_id' => get_branch_id_in_session(),
                    'usuario_id' => get_user_id_in_session(),
                    // 'agrupa_id' => $this->input->post('grupo_falla')
                    'agrupa_id' => 1
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos la nueva falla
                $this->_insert_failure($data_failure);

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

    /*Funcion para actualizar la falla para validar los datos de la vista */
    public function modify_failure()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $failure_id = $this->input->post('id_falla');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'nombre_falla',
                    'label' => 'falla',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, falla, nombre]", $failure_id)
                )
                // array(
                //     'field' => 'descripcion_falla',
                //     'label' => 'El campo descripcion no puede ser vacio',
                //     'rules' => 'trim'
                // )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

            if ($this->form_validation->run() === TRUE) {
                $data_failure = array(
                    'nombre' => $this->input->post('nombre_falla'),
                    'descripcion' => $this->input->post('descripcion_falla'),
                    'sucursal_id' => get_branch_id_in_session(),
                    'usuario_id' => get_user_id_in_session(),
                    'agrupa_id' => $this->input->post('grupo_falla_edit')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar la falla
                $this->_update_failure($failure_id, $data_failure);

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

    /*Funcion para desabilitar la falla*/
    public function disable_failure()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'falla',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion para desabilitar el servicio*/
    public function enable_failure()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'falla',
            ['estado' => ACTIVO],
            ['id' => $id]
        );
    }

    /*Funcion privada para insertar en la base de datos de la nueva falla*/
    private function _insert_failure($failure)
    {
        return $this->db->insert('falla', $failure);
    }

    /*Funcion privada para actualizar en la base de datos la falla*/
    private function _update_failure($failure_id, $data_failure)
    {
        $where = array('id' => $failure_id);
        return $this->db->update('falla', $data_failure, $where);
    }


    public function get_failure_reception(){
        $reception_id = $this->input->post('reception_id');
        $this->db->select('fall.id')
            ->from('falla_recepcion frec, falla fall')
            ->where('frec.recepcion_id',$reception_id)
            ->where('frec.falla_id = fall.id');
        return $this->db->get()->result();
    }

    public function get_failure_reception_by_reception_id($reception_id){
        $this->db->select('fall.id, fall.nombre')
            ->from('falla_recepcion frec, falla fall')
            ->where('frec.recepcion_id',$reception_id)
            ->where('frec.falla_id = fall.id');
        return $this->db->get()->result();
    }

    public function get_failure_order_work_by_order_work_id($order_work_id){
        $this->db->select('fall.id, fall.nombre')
            ->from('falla_orden_trabajo frec, falla fall')
            ->where('frec.orden_trabajo_id',$order_work_id)
            ->where('frec.falla_id = fall.id');
        return $this->db->get()->result();
    }
}