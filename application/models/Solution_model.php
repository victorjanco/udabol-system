<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 13/7/2017
 * Time: 6:14 PM
 */
class Solution_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener los todos las soluciones activos especial para cargar combos o autocompletados*/
    public function get_solution_enable($type = 'object')
    {
        return $this->db->get_where('solucion', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos de la solucion apartit de solucion_id*/
    public function get_solution_id($solution_id)
    {
        return $this->db->get_where('solucion', array('id' => $solution_id))->row();
    }

    /*Obtener lista de soluciones para cargar la lista de dataTable*/
    public function get_solution_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('solucion');
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

    /*Funcion registrar nueva solucion para validar los datos de la vista */
    public function register_solution()
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
                    'label' => 'El nombre de la solucion debe ser unico y solo permite numeros y letras',
                    'rules' => 'trim|required|alpha_numeric_spaces|is_unique[solucion.nombre]'
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
                $data_solution = array(
                    'nombre' => strtoupper($this->input->post('nombre')),
                    'descripcion' => strtoupper($this->input->post('descripcion')),
                    'estado' => get_state_abm('ACTIVO'),
                    'sucursal_id' => get_branch_id_in_session(),
                    'usuario_id' => get_user_id_in_session(),
                    // 'agrupa_id' => $this->input->post('grupo_solucion')
                    'agrupa_id' => 2 //agrupa de tipo solucion
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar solucion ala base de datos
                $this->_insert_solution($data_solution);

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

    /*Funcion modificar la solucion para validar los datos de la vista */
    public function modify_solution()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $solution_id = $this->input->post('id_solucion');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'nombre_solucion',
                    'label' => 'El nombre de la solucion debe ser unico y solo permite numeros y letras',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, solucion, nombre]", $solution_id)
                )
                // array(
                //     'field' => 'descripcion_solucion',
                //     'label' => 'El campo descripcion no puede ser vacio',
                //     'rules' => 'trim'
                // )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

            if ($this->form_validation->run() === TRUE) {
                $data_solution = array(
                    'nombre' => $this->input->post('nombre_solucion'),
                    'descripcion' => $this->input->post('descripcion_solucion'),
                    'sucursal_id' => get_branch_id_in_session(),
                    'usuario_id' => get_user_id_in_session(),
                    'agrupa_id' => $this->input->post('grupo_solucion_edit')

                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar la solucion
                $this->_update_solution($solution_id, $data_solution);

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

    /*Funcion para desabilitar la solucion*/
    public function disable_solution()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'solucion',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }
    /*Funcion para desabilitar el servicio*/
    public function enable_solution()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'solucion',
            ['estado' => ACTIVO],
            ['id' => $id]
        );
    }

    /*Funcion privada para insertar en la base de datos ala nueva solucion*/
    private function _insert_solution($solution)
    {
        return $this->db->insert('solucion', $solution);
    }

    /*Funcion privada para actualizar en la base de datos ala nueva solucion*/
    private function _update_solution($solution_id, $data_solution)
    {
        $where = array('id' => $solution_id);
        return $this->db->update('solucion', $data_solution, $where);
    }

    public function get_solution_reception()
    {
        $reception_id = $this->input->post('reception_id');
        $this->db->select('solu.id')
            ->from('solucion_recepcion srec, solucion solu')
            ->where('srec.recepcion_id',$reception_id)
            ->where('srec.solucion_id = solu.id');
        return $this->db->get()->result();
    }

    public function get_solution_reception_by_reception_id($reception_id)
    {
        $this->db->select('solu.id,solu.nombre')
            ->from('solucion_recepcion srec, solucion solu')
            ->where('srec.recepcion_id',$reception_id)
            ->where('srec.solucion_id = solu.id');
        return $this->db->get()->result();
    }
    public function get_solution_order_work_by_order_work_id($order_work_id)
    {
        $this->db->select('solu.id,solu.nombre')
            ->from('solucion_orden_trabajo srec, solucion solu')
            ->where('srec.orden_trabajo_id',$order_work_id)
            ->where('srec.solucion_id = solu.id');
        return $this->db->get()->result();
    }
}