<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 08:20 PM
 */
class Printer_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_printers($type = 'object'){
        return $this->db->get_where('impresora', array('estado'=>ACTIVO))->result($type);
    }

    public function get_printers_by_branch_id($id_branch_office)
    {
        $this->db->start_cache();
        $this->db->select('*');
        $this->db->from('impresora');
        $this->db->where('sucursal_id', $id_branch_office);
        return $this->db->get()->result();
    }

    public function get_printer_list($params = array()){

        /* Se cachea la informacion que se arma en query builder*/
        $this->db->start_cache();
        $this->db->select('p.*,s.nombre,s.nombre_comercial');
        $this->db->from('impresora p, sucursal s');
        $this->db->where('p.sucursal_id = s.id');

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
            $this->db->order_by('p.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(p.marca)', strtolower($params['search']));
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
    public function register_printer()
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
                    'field' => 'marca',
                    'label' => 'El nombre de la marca es requerido',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'serie',
                    'label' => 'Debe introducir el nombre del modelo y serie de la impresora',
                    'rules' => 'trim|required'
                )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

            if ($this->form_validation->run() === true) {

                $this->db->trans_start();
                /** OBTENERMOS VALORES DE LOS INPUT **/
                $data['marca'] = $this->input->post('marca');
                $data['serial'] = $this->input->post('serie');
                $data['sucursal_id'] = $this->input->post('sucursal');
                $data['usuario_id'] = get_user_id_in_session();
                $data['estado'] = ACTIVO;

                $this->db->insert('impresora', $data);

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

        } else {
            $response['login'] = TRUE;
        }

        return $response;
    }


    /*Funcion para actualizar el almacen para validar los datos de la vista */
    public function modify_printer()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            $printer_id = $this->input->post('id_printer');
            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'marca_edit',
                    'label' => 'El nombre de la marca es requerido',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'serie_edit',
                    'label' => 'Debe introducir el nombre del modelo y serie de la impresora',
                    'rules' => 'trim|required'
                )
            );


            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data['marca'] = $this->input->post('marca_edit');
                $data['serial'] = $this->input->post('serie_edit');
                $data['sucursal_id'] = $this->input->post('sucursal_edit');
                $data['usuario_id'] = get_user_id_in_session();

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar el modelo
                $this->_update_printer($printer_id, $data);

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

    /*Funcion privada para actualizar en la base de datos de la impresora*/
    private function _update_printer($printer_id, $data_printer)
    {
        $where = array('id' => $printer_id);
        return $this->db->update('impresora', $data_printer, $where);
    }

    /*Funcion para desabilitar*/
    public function disable_printer()
    {
        $id = $this->input->post('id');
        return $this->db->update('impresora', ['estado' => ANULADO], ['id' => $id]);
    }

}