<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 24/7/2017
 * Time: 11:52 AM
 */
class Type_warehouse_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las tipo_almacen activas especial para cargar combos o autocompletados*/
    public function get_type_warehouse_enable($type = 'object')
    {
        return $this->db->get_where('tipo_almacen', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos del tipo almacen apartir del tipo_almacen_id*/
    public function get_type_warehouse_id($type_warehouse_id)
    {
        return $this->db->get_where('tipo_almacen', array('id' => $type_warehouse_id))->row();
    }

    /*Obtener lista de tipo_almacen para cargar la lista de dataTable*/
    public function get_type_warehouse_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('tipo_almacen');
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

    /*Funcion registrar nuevo tipo_almacen para validar los datos de la vista */
    public function register_type_warehouse()
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
                    'field' => 'nombre_tipo_almacen',
                    'label' => 'El campo codigo no puede ser vacio',
                    'rules' => 'trim|required|alpha_numeric_spaces|is_unique[tipo_almacen.nombre]'
                ),
                array(
                    'field' => 'descripcion_tipo_almacen',
                    'label' => 'El nombre de la modelo debe ser unico y solo permite numeros y letras',
                    'rules' => 'trim|required|alpha_numeric_spaces'
                )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data_type_warehouse = array(
                    'nombre' => strtoupper($this->input->post('nombre_tipo_almacen')),
                    'descripcion' => strtoupper($this->input->post('descripcion_tipo_almacen')),
                    'estado' => get_state_abm('ACTIVO')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos de nuevo tipo_almacen
                $this->_insert_type_warehouse($data_type_warehouse);

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
    public function modify_type_warehouse()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $type_warehouse_id = $this->input->post('id_tipo_almacen_edit');
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'nombre_tipo_almacen_edit',
                'label' => 'El campo codigo no puede ser vacio',
                'rules' => sprintf("trim|required|is_unique_edit[%u, tipo_almacen, nombre]", $type_warehouse_id)

            ),
            array(
                'field' => 'descripcion_tipo_almacen_edit',
                'label' => 'El nombre de la modelo debe ser unico y solo permite numeros y letras ',
                'rules' => 'trim|required'
            )

        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

        if ($this->form_validation->run() === TRUE) {
            $data_type_warehouse = array(
                'nombre' => strtoupper($this->input->post('nombre_tipo_almacen_edit')),
                'descripcion' => strtoupper($this->input->post('descripcion_tipo_almacen_edit'))
            );

            // Inicio de transacción
            $this->db->trans_begin();

            // Actualizar el tipo_almacen
            $this->_update_type_warehouse($type_warehouse_id, $data_type_warehouse);

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

        echo json_encode($response);
    }

    /*Funcion para desabilitar el tipo almacen*/
    public function disable_type_warehouse()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'tipo_almacen',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion privada para insertar en la base de datos del nuevo tipo_almacen*/
    private function _insert_type_warehouse($type_warehouse)
    {
        return $this->db->insert('tipo_almacen', $type_warehouse);
    }

    /*Funcion privada para actualizar en la base de datos tipo_almacen*/
    private function _update_type_warehouse($type_warehouse_id, $data_type_warehouse)
    {
        $where = array('id' => $type_warehouse_id);
        return $this->db->update('tipo_almacen', $data_type_warehouse, $where);
    }
}