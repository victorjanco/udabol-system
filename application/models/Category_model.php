<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 08:20 PM
 */
class Category_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_categorys($type = 'object'){
        return $this->db->get_where('categoria', array('estado'=>get_state_abm('ACTIVO')))->result($type);
    }

    public function get_categories_list($params = array()){

        /* Se cachea la informacion que se arma en query builder*/
        $this->db->start_cache();
        $this->db->select('*')
            ->from('categoria')
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

    /*
     * Metodo para registrar un nuevo grupo
     * */
    public function register_category()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'nombre_categoria',
                'label' => '<strong>Nombre</strong>',
                'rules' => 'trim|required|is_unique[categoria.nombre]'
            ),
            array(
                'field' => 'descripcion_categoria',
                'label' => '',
                'rules' => 'trim|alpha_numeric_spaces'
            )
        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
        $this->form_validation->set_message('is_unique', 'Nombre de categoria ya existe');

        if ($this->form_validation->run() === true) {

            $this->db->trans_start();
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_category['nombre'] = $this->input->post('nombre_categoria');
            $obj_category['descripcion'] = $this->input->post('descripcion_categoria');
            $obj_category['estado'] = 1;

            $this->db->insert('categoria', $obj_category);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['success'] = false;
            } else {
                $this->db->trans_commit();
                $response['success'] = true;
            }
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return $response;
    }


    /*Funcion para actualizar el almacen para validar los datos de la vista */
    public function modify()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $category_id = $this->input->post('id');
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'nombre_categoria_edit',
                'label' => '',
                'rules' => sprintf("trim|required|is_unique_edit[%u, categoria, nombre]", $category_id)
            ),
            array(
                'field' => 'descripcion_categoria_edit',
                'label' => '',
                'rules' => 'trim|alpha_numeric_spaces'
            )

        );


        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
        $this->form_validation->set_message('is_unique_edit', 'Nombre ya existe');


        if ($this->form_validation->run() === TRUE) {
            $data_category = array(
                'nombre' => $this->input->post('nombre_categoria_edit'),
                'descripcion' => $this->input->post('descripcion_categoria_edit')
            );

            // Inicio de transacción
            $this->db->trans_begin();

            // Actualizar el modelo
            $this->_update($category_id, $data_category);

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

    /*Funcion privada para actualizar en la base de datos la modelo*/
    private function _update($id, $data)
    {
        $where = array('id' => $id);
        return $this->db->update('categoria', $data, $where);
    }

    /*Funcion para desabilitar*/
    public function disable()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'categoria',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /* Fuincion de que devuelve una categoria con id como pareametro    */
    public function get_category_id($id)
    {
        return $this->db->get_where('categoria', array('id' => $id))->row();
    }
}