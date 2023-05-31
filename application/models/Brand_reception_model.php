<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 18/7/2017
 * Time: 8:08 PM
 */
class Brand_reception_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las marca_recepcion activas especial para cargar combos o autocompletados*/
    public function get_brand_reception_enable($type = 'object')
    {
        return $this->db->get_where('marca_recepcion', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos de la marca_recepcion apartir del marca_recepcion_id*/
    public function get_brand_reception_id($brand_reception_id)
    {
        return $this->db->get_where('marca_recepcion', array('id' => $brand_reception_id))->row();
    }

    /*Obtener lista de marca_recepcions para cargar la lista de dataTable*/
    public function get_brand_reception_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('marca_recepcion');
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

    /*Funcion registrar nueva marca_recepcion para validar los datos de la vista */
    public function register_brand_reception()
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
                    'field' => 'name_brand',
                    'label' => 'Marca',
                    'rules' => 'trim|required|alpha_numeric_spaces|is_unique[marca_recepcion.nombre]|strtoupper'
                )
                // array(
                //     'field' => 'description_brand',
                //     'label' => 'Descripcion es requerida',
                //     'rules' => 'trim|required'
                // )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data_brand_reception = array(
                    'nombre' => strtoupper($this->input->post('name_brand')),
                    'descripcion' => strtoupper($this->input->post('name_brand')),
                    'estado' => 1,
                    'fecha_creacion' => date('Y-m-d H:i:s'),
                    'fecha_actualizacion' => date('Y-m-d H:i:s')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos la nueva marca_recepcion
                $this->_insert_brand_reception($data_brand_reception);

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

    /*Funcion para actualizar la marca_recepcion para validar los datos de la vista */
    public function modify_brand_reception()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $brand_reception_id = $this->input->post('id_brand_reception');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'edit_name_brand',
                    'label' => 'Nombre',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, marca_recepcion, nombre]|strtoupper", $brand_reception_id)
                )
                // array(
                //     'field' => 'edit_description_brand',
                //     'label' => 'Descripcion',
                //     'rules' => 'trim|required'
                // )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');

            if ($this->form_validation->run() === TRUE) {
                $data_brand_reception = array(
                    'nombre' => strtoupper($this->input->post('edit_name_brand')),
                    'descripcion' => strtoupper($this->input->post('edit_name_brand')),
                    'fecha_actualizacion' => date('Y-m-d H:i:s')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar la marca_recepcion
                $this->_update_brand_reception($brand_reception_id, $data_brand_reception);

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
    public function disable_brand_reception()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'marca_recepcion',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    public function enable_brand_reception()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'marca_recepcion',
            ['estado' => ACTIVO],
            ['id' => $id]
        );
    }


    /*Retorna el modelo y la marca_recepcion del producto */
    public function get_brand_reception_model($model_id){
        $this->db->select('mar.*')
            ->from('marca_recepcion mar')
            ->join('modelo mod','mod.marca_recepcion_id = mar.id')
            ->where('mod.id', $model_id);
        return $this->db->get()->row();
    }

    /*Funcion privada para insertar en la base de datos de la nueva marca_recepcion*/
    private function _insert_brand_reception($brand_reception)
    {
        return $this->db->insert('marca_recepcion', $brand_reception);
    }

    /*Funcion privada para actualizar en la base de datos la marca_recepcion*/
    private function _update_brand_reception($brand_reception_id, $data_brand_reception)
    {
        $where = array('id' => $brand_reception_id);
        return $this->db->update('marca_recepcion', $data_brand_reception, $where);
    }

    public function exists_brand_reception_name($name_brand_reception)
    {
        $this->db->select('*')
            ->from('marca_recepcion')
            ->where('nombre', $name_brand_reception)
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

    public function get_brand_reception_by_name($name_brand_reception){
        $this->db->select('*')
            ->from('marca_recepcion')
            ->where('nombre', $name_brand_reception)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    public function get_brand_reception($data_brand_reception)
    {
        return $this->db->get_where('marca_recepcion', $data_brand_reception)->row();
    }
}