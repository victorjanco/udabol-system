<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 19/7/2017
 * Time: 12:07 PM
 */
class Model_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las modelo activas especial para cargar combos o autocompletados*/
    public function get_model_enable($type = 'object')
    {
        return $this->db->get_where('modelo', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos de la modelo apartir del modelo_id*/
    public function get_model_id($model_id)
    {
        return $this->db->get_where('modelo', array('id' => $model_id))->row();
    }

    /*Obtener lista de modelos para cargar la lista de dataTable*/
    public function get_model_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('m.id,m.codigo,m.nombre,m.estado,m.marca_id,ma.nombre as nombre_marca')
            ->from('modelo m,  marca ma')
            ->where('m.marca_id = ma.id');
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
            $this->db->order_by('m.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(m.nombre)', strtolower($params['search']));
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

    /*Funcion registrar nuevo modelo para validar los datos de la vista */
    public function register_model()
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
                    'field' => 'codigo',
                    'label' => 'El campo codigo no puede ser vacio',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'nombre',
                    'label' => 'El nombre de la modelo debe ser unico y solo permite numeros y letras',
                    'rules' => 'trim|required|alpha_numeric_spaces'
                ),
                array(
                    'field' => 'marca',
                    'label' => 'Debe seleccionar una marca por favor',
                    'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->brand_model->get_brand_enable(), 'id'))
                )

            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

            if ($this->form_validation->run() === TRUE) {
                $data_model = array(
                    'codigo' => strtoupper($this->input->post('codigo')),
                    'nombre' => strtoupper($this->input->post('nombre')),
                    'estado' => 1,
                    'marca_id' => $this->input->post('marca')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos de nuevo modelo
                $this->_insert_model($data_model);

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

    /*Funcion para actualizar la modelo para validar los datos de la vista */
    public function modify_model()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            $model_id = $this->input->post('id_modelo');
            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'codigo_modelo',
                    'label' => 'El campo codigo no puede ser vacio',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'nombre_modelo',
                    'label' => 'El nombre de la modelo debe ser unico y solo permite numeros y letras ',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, modelo, nombre]", $model_id)
                ),
                array(
                    'field' => 'marca_edit_model',
                    'label' => 'El nombre de la modelo debe ser unico y solo permite numeros y letras',
                    'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->brand_model->get_brand_enable(), 'id'))
                )

            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

            if ($this->form_validation->run() === TRUE) {
                $data_model = array(
                    'codigo' => strtoupper($this->input->post('codigo_modelo')),
                    'nombre' => strtoupper($this->input->post('nombre_modelo')),
                    'marca_id' => $this->input->post('marca_edit_model'),
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar el modelo
                $this->_update_model($model_id, $data_model);

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

    /*Funcion para desabilitar el modelo*/
    public function disable_model()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'modelo',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion privada para insertar en la base de datos del nuevo modelo*/
    private function _insert_model($model)
    {
        return $this->db->insert('modelo', $model);
    }

    /*Funcion privada para actualizar en la base de datos la modelo*/
    private function _update_model($model_id, $data_model)
    {
        $where = array('id' => $model_id);
        return $this->db->update('modelo', $data_model, $where);
    }

    public function get_model_by_brand()
    {
        $brand_id = $this->input->post("marca_id");
        $this->db->select('mod.nombre, mod.id')
            ->from('marca marc, modelo mod')
            ->where('marc.id = mod.marca_id')
            ->where('marca_id', $brand_id)
            ->where('mod.estado', ACTIVO);
        return $this->db->get()->result();
    }

    public function get_model_by_brand_id($brand_id)
    {
        $this->db->select('mod.nombre, mod.id')
            ->from('marca marc, modelo mod')
            ->where('marc.id = mod.marca_id')
            ->where('marca_id', $brand_id)
            ->where('mod.estado', ACTIVO);
        return $this->db->get()->result();
    }

    public function exists_code_model($code_model,$brand_id)
    {
        $this->db->select('*')
            ->from('modelo')
            ->where('codigo', $code_model)
            ->where('marca_id', $brand_id)
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

    public function exists_name_model($name_model,$brand_id)
    {
        $this->db->select('*')
            ->from('modelo')
            ->where('nombre', $name_model)
            ->where('marca_id', $brand_id)
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
    public function get_model_by_name_and_brand_id($name_model, $brand_id){
        $this->db->select('*')
            ->from('modelo')
            ->where('nombre', $name_model)
            ->where('marca_id', $brand_id)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    public function get_model_by_code_and_brand_id($code_model, $brand_id){
        $this->db->select('*')
            ->from('modelo')
            ->where('codigo', $code_model)
            ->where('marca_id', $brand_id)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    public function get_model($data_model)
    {
        return $this->db->get_where('modelo', $data_model)->row();
    }
}