<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 16/09/2019
 * Time: 17:56 PM
 */

class Serie_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function first()
    {
        return $this->db->get('serie')->row();
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
                'field' => 'name',
                'label' => 'Nombre',
                'rules' => 'trim|required|is_unique[serie.nombre]'
            ),
            array(
                'field' => 'description',
                'label' => 'Descripcion',
                'rules' => 'trim|alpha_numeric_spaces'
            )
        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
        $this->form_validation->set_message('is_unique', 'Nombre de categoria ya existe');

        if ($this->form_validation->run() === true) {
            $today = date('Y-m-d');
            $this->db->trans_start();
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_category['nombre'] = $this->input->post('name');
            $obj_category['descripcion'] = $this->input->post('description');
            $obj_category['estado'] = 1;
            $obj_category["fecha_registro"] = $today;

            $this->db->insert('serie', $obj_category);

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

    public function modify_category()
    {
        try {
            $response = array(
                'success' => FALSE,
                'messages' => array(),
                'login' => FALSE
            );
    
            $category_id = $this->input->post('category_id');
    
            if (verify_session()) {
    
                // Reglas de validacion
                $validation_rules = array(
                    array(
                        'field' => 'name_edit',
                        'label' => 'Nombre',
                        'rules' => sprintf("trim|required|is_unique_edit[%u, serie, nombre]", $category_id)
                    ),
                    array(
                        'field' => 'description_edit',
                        'label' => 'Descripcion',
                        'rules' => 'trim|required' 
                    )
                );
    
                // Pasar reglas de validacion como parámetro
                $this->form_validation->set_rules($validation_rules);
                $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');
    
                if ($this->form_validation->run() === TRUE) {
                    $data_category = array(
                        'nombre' => strtoupper($this->input->post('name_edit')),
                        'descripcion' => strtoupper($this->input->post('description_edit')),
                    );
    
                    // Inicio de transacción
                    $this->db->trans_begin();
    
                    // Actualizar la cargo
                    $this->_update_category($category_id, $data_category);
    
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

    /*Obtener lista de cargos para cargar la lista de dataTable*/
    public function get_category_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('serie');
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
    public function get_all_serie(){
        $this->db->select('*')
            ->from('serie')
            ->where('estado', ACTIVO)
            ->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_serie_by_id($id)
    {
        $this->db->select('*')
            ->from('serie')
            ->where('id', $id)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    /*Funcion privada para actualizar en la base de datos la cargo*/
    private function _update_category($category_id, $data_category)
    {
        $where = array('id' => $category_id);
        return $this->db->update('serie', $data_category, $where);
    }

    /*Funcion para desabilitar el tipo almacen*/
    public function disable_category()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'serie',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }
}