<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 18/7/2017
 * Time: 8:08 PM
 */
class Model_reception_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las modelo_recepcion activas especial para cargar combos o autocompletados*/
    public function get_model_reception_enable($type = 'object')
    {
        return $this->db->get_where('modelo_recepcion', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos de la modelo_recepcion apartir del modelo_recepcion_id*/
    public function get_model_reception_id($model_reception_id)
    {
        return $this->db->get_where('modelo_recepcion', array('id' => $model_reception_id))->row();
    }

    /*Obtener lista de modelo_recepcions para cargar la lista de dataTable*/
    public function get_model_reception_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('mr.*, mrc.nombre as marca_recepcion')
            ->from('modelo_recepcion mr, marca_recepcion mrc')
            ->where('mr.marca_id=mrc.id');
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
            $this->db->order_by('mr.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(mr.nombre)', strtolower($params['search']));
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

    /*Funcion registrar nueva modelo_recepcion para validar los datos de la vista */
    public function register_model_reception()
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
                    'field' => 'name_model',
                    'label' => 'Nombre',
                    'rules' => 'trim|required|alpha_numeric_spaces|is_unique[modelo_recepcion.nombre]|strtoupper'
                )
                // array(
                //     'field' => 'description_model',
                //     'label' => 'Descripcion',
                //     'rules' => 'trim|required'
                // )

            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data_model_reception = array(
                    'nombre' => strtoupper($this->input->post('name_model')),
                    'descripcion' => strtoupper($this->input->post('name_model')),
                    'estado' => 1,
                    'fecha_creacion' => date('Y-m-d H:i:s'),
                    'fecha_actualizacion' => date('Y-m-d H:i:s'),
                    'marca_id' => $this->input->post('brand_reception_model')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos la nueva modelo_recepcion
                $this->_insert_model_reception($data_model_reception);

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

    /*Funcion para actualizar la modelo_recepcion para validar los datos de la vista */
    public function modify_model_reception()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $model_reception_id = $this->input->post('id_model_reception');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'edit_name_model',
                    'label' => 'Nombre',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, modelo_recepcion, nombre]|strtoupper", $model_reception_id)
                ),
                array(
                    'field' => 'edit_description_model',
                    'label' => 'Descripcion',
                    'rules' => 'trim|required'
                )

            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');

            if ($this->form_validation->run() === TRUE) {
                $data_model_reception = array(
                    'nombre' => strtoupper($this->input->post('edit_name_model')),
                    'descripcion' => strtoupper($this->input->post('edit_description_model')),
                    'fecha_actualizacion' => date('Y-m-d H:i:s'),
                    'marca_id' => $this->input->post('brand_reception_model_edit')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar la modelo_recepcion
                $this->_update_model_reception($model_reception_id, $data_model_reception);

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
    public function disable_model_reception()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'modelo_recepcion',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    public function enable_model_reception()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'modelo_recepcion',
            ['estado' => ACTIVO],
            ['id' => $id]
        );
    }

    /*Retorna el modelo y la modelo_recepcion del producto */
    public function get_model_reception_model($model_id){
        $this->db->select('mar.*')
            ->from('modelo_recepcion mar')
            ->join('modelo mod','mod.modelo_recepcion_id = mar.id')
            ->where('mod.id', $model_id);
        return $this->db->get()->row();
    }
    public function get_model_reception_by_brand()
    {
        $brand_id = $this->input->post("marca_id");
        $this->db->select('mod.nombre, mod.id') 
            ->from('marca_recepcion marc, modelo_recepcion mod')
            ->where('marc.id = mod.marca_id')
            ->where('marca_id', $brand_id)
            ->where('mod.estado', ACTIVO);
        return $this->db->get()->result();
    }

    /*Funcion privada para insertar en la base de datos de la nueva modelo_recepcion*/
    private function _insert_model_reception($model_reception)
    {
        return $this->db->insert('modelo_recepcion', $model_reception);
    }

    /*Funcion privada para actualizar en la base de datos la modelo_recepcion*/
    private function _update_model_reception($model_reception_id, $data_model_reception)
    {
        $where = array('id' => $model_reception_id);
        return $this->db->update('modelo_recepcion', $data_model_reception, $where);
    }

    public function exists_model_reception_name($name_model_reception)
    {
        $this->db->select('*')
            ->from('modelo_recepcion')
            ->where('nombre', $name_model_reception)
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

    public function get_model_reception_by_name($name_model_reception){
        $this->db->select('*')
            ->from('modelo_recepcion')
            ->where('nombre', $name_model_reception)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    public function get_model_reception($data_model_reception)
    {
        return $this->db->get_where('modelo_recepcion', $data_model_reception)->row();
    }
}