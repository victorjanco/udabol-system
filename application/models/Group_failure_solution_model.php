<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 29/8/2017
 * Time: 12:06 PM
 */
class Group_failure_solution_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las grupo de falla o solucion activas especial para cargar combos o autocompletados*/
    public function get_group_failure_solution_enable($type = 'object')
    {
        return $this->db->get_where('agrupa', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos del grupo de falla o solucion apartir del tipo_almacen_id*/
    public function get_group_failure_solution_id($group_failure_solution_id)
    {
        return $this->db->get_where('agrupa', array('id' => $group_failure_solution_id))->row();
    }

    /*Obtener lista de grupo de falla o solucion para cargar la lista de dataTable*/
    public function get_group_failure_solution_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('agrupa');
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

    /*Funcion registrar nuevo grupo de falla o solucion para validar los datos de la vista */
    public function register_group_failure_solution()
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
                    'field' => 'nombre_group_failure_solution',
                    'label' => 'El campo codigo no puede ser vacio',
                    'rules' => 'trim|required|alpha_numeric_spaces|is_unique[agrupa.nombre]'
                ),
                array(
                    'field' => 'descripcion_group_failure_solution',
                    'label' => 'El nombre de la modelo debe ser unico y solo permite numeros y letras',
                    'rules' => 'trim|required'
                )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data_group_failure_solution = array(
                    'nombre' => $this->input->post('nombre_group_failure_solution'),
                    'descripcion' => $this->input->post('descripcion_group_failure_solution'),
                    'estado' => get_state_abm('ACTIVO')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos de nuevo tipo_almacen
                $this->_insert_group_failure_solution($data_group_failure_solution);

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

    /*Funcion para actualizar tipo_almacen para validar los datos de la vista */
    public function modify_group_failure_solution()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $group_failure_solution_id = $this->input->post('id_group_failure_solution_edit');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'nombre_group_failure_solution_edit',
                    'label' => 'El campo codigo no puede ser vacio',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, agrupa, nombre]", $group_failure_solution_id)

                ),
                array(
                    'field' => 'descripcion_group_failure_solution_edit',
                    'label' => 'El nombre de la modelo debe ser unico y solo permite numeros y letras ',
                    'rules' => 'trim|required'
                )

            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

            if ($this->form_validation->run() === TRUE) {
                $data_group_failure_solution = array(
                    'nombre' => $this->input->post('nombre_group_failure_solution_edit'),
                    'descripcion' => $this->input->post('descripcion_group_failure_solution_edit')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar el tipo_almacen
                $this->_update_group_failure_solution($group_failure_solution_id, $data_group_failure_solution);

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

    /*Funcion para desabilitar el tipo almacen*/
    public function disable_group_failure_solution()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'agrupa',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion privada para insertar en la base de datos del nuevo tipo_almacen*/
    private function _insert_group_failure_solution($group_failure_solution)
    {
        return $this->db->insert('agrupa', $group_failure_solution);
    }

    /*Funcion privada para actualizar en la base de datos tipo_almacen*/
    private function _update_group_failure_solution($group_failure_solution_id, $data_group_failure_solution)
    {
        $where = array('id' => $group_failure_solution_id);
        return $this->db->update('agrupa', $data_group_failure_solution, $where);
    }
}