<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 18/7/2017
 * Time: 8:08 PM
 */
class Brand_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las marca activas especial para cargar combos o autocompletados*/
    public function get_brand_enable($type = 'object')
    {
        return $this->db->get_where('marca', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos de la marca apartir del marca_id*/
    public function get_brand_id($brand_id)
    {
        return $this->db->get_where('marca', array('id' => $brand_id))->row();
    }

    /*Obtener lista de marcas para cargar la lista de dataTable*/
    public function get_brand_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('marca');
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

    /*Funcion registrar nueva marca para validar los datos de la vista */
    public function register_brand()
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
                    'label' => 'El nombre de la marca debe ser unico y solo permite numeros y letras',
                    'rules' => 'trim|required|alpha_numeric_spaces|is_unique[marca.nombre]'
                )

            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data_brand = array(
                    'codigo' => strtoupper($this->input->post('codigo')),
                    'nombre' => strtoupper($this->input->post('nombre')),
                    'estado' => 1
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos la nueva marca
                $this->_insert_brand($data_brand);

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

    /*Funcion para actualizar la marca para validar los datos de la vista */
    public function modify_brand()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $brand_id = $this->input->post('id_marca');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'codigo_marca',
                    'label' => 'El campo codigo no puede ser vacio',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'nombre_marca',
                    'label' => 'El nombre de la marca debe ser unico y solo permite numeros y letras',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, modelo, nombre]", $brand_id)
                )

            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');

            if ($this->form_validation->run() === TRUE) {
                $data_brand = array(
                    'codigo' => strtoupper($this->input->post('codigo_marca')),
                    'nombre' => strtoupper($this->input->post('nombre_marca')),
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar la marca
                $this->_update_brand($brand_id, $data_brand);

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
    public function disable_brand()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'marca',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Retorna el modelo y la marca del producto */
    public function get_brand_model($model_id){
        $this->db->select('mar.*')
            ->from('marca mar')
            ->join('modelo mod','mod.marca_id = mar.id')
            ->where('mod.id', $model_id);
        return $this->db->get()->row();
    }

    /*Funcion privada para insertar en la base de datos de la nueva marca*/
    private function _insert_brand($brand)
    {
        return $this->db->insert('marca', $brand);
    }

    /*Funcion privada para actualizar en la base de datos la marca*/
    private function _update_brand($brand_id, $data_brand)
    {
        $where = array('id' => $brand_id);
        return $this->db->update('marca', $data_brand, $where);
    }

    public function exists_brand_name($name_brand)
    {
        $this->db->select('*')
            ->from('marca')
            ->where('nombre', $name_brand)
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

    public function get_brand_by_name($name_brand){
        $this->db->select('*')
            ->from('marca')
            ->where('nombre', $name_brand)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    public function get_brand($data_brand)
    {
        return $this->db->get_where('marca', $data_brand)->row();
    }
}