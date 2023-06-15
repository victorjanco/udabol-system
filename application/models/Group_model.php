<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 08:20 PM
 */
class Group_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function first()
    {
        return $this->db->get('grupo')->row();
    }

    public function get_groups($type = 'object'){
        $sql_query = "SELECT grp.* FROM grupo grp WHERE grp.estado = 1 AND grp.tipo = 1";
        return $this->db->query($sql_query)->result($type);
    }

    public function get_groups_list($params = array()){

        // Se cachea la informacion que se arma en query builder
        $data = $this->db->get('asignacion_grupo')->result();
        $array_sub = [];
        foreach ($data as $row_data):
            $array_sub[] = $row_data->grupo_hijo_id;
            endforeach;
        //echo $array_sub;
        $this->db->start_cache();

            // if(count($array_sub)>0){
            //     $this->db->select('*')
            //     ->from('grupo')
            //     ->where('estado = 1')
            //         ->where_not_in('id',$array_sub);
            // }else{
                $this->db->select('*')
                    ->from('grupo')
                    ->where('tipo = 1')
                    ->where('estado = 1');
            // }
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
    public function register_group()
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
                    'field' => 'nombre_grupo',
                    'label' => '<strong>Nombre</strong>',
                    'rules' => 'trim|required|is_unique[grupo.nombre]'
                ),
                array(
                    'field' => 'descripcion_grupo',
                    'label' => '',
                    'rules' => 'trim|alpha_numeric_spaces'
                )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
            $this->form_validation->set_message('is_unique', 'Nombre de grupo ya existe');

            if ($this->form_validation->run() === true) {

                $this->db->trans_start();
                /** OBTENERMOS VALORES DE LOS INPUT **/
                $obj_group['nombre'] = strtoupper($this->input->post('nombre_grupo'));
                $obj_group['descripcion'] = strtoupper($this->input->post('descripcion_grupo'));
                $obj_group['estado'] = 1;
                $obj_group['tipo'] = 1;

                $this->db->insert('grupo', $obj_group);

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

    /**
     *Registra un subgrupo
     * @return mixed
     */
    public function register_subgroup()
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
                    'field' => 'nombre_subgrupo',
                    'label' => '<strong>Nombre</strong>',
                    'rules' => 'trim|required|is_unique[grupo.nombre]'
                ),
                array(
                    'field' => 'descripcion_subgrupo',
                    'label' => '',
                    'rules' => 'trim|alpha_numeric_spaces'
                )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
            $this->form_validation->set_message('is_unique', 'Nombre de subgrupo ya existe');

            if ($this->form_validation->run() === true) {

                $this->db->trans_start();
                /** OBTENERMOS VALORES DE LOS INPUT **/
                $obj_subgroup['nombre'] = strtoupper($this->input->post('nombre_subgrupo'));
                $obj_subgroup['descripcion'] = strtoupper($this->input->post('descripcion_subgrupo'));
                $obj_subgroup['estado'] = 1;

                $this->db->insert('grupo', $obj_subgroup);
                $subgrupo_register_id = $this->_get_group($obj_subgroup);
                // echo json_encode($subgrupo_register_id);
                if ($subgrupo_register_id !== false) {
                    $subgrupo_register_id = $subgrupo_register_id->id;
                    $obj_asignacion_grupo["grupo_padre_id"] = $this->input->post('group_id');
                    $obj_asignacion_grupo["grupo_hijo_id"] = $subgrupo_register_id;
                    $this->db->insert('asignacion_grupo', $obj_asignacion_grupo);
                } else {
                    $this->db->trans_rollback();
                    $response['success'] = false;
                    return $response;
                }
                $this->db->get_where('grupo', $obj_subgroup);

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

    /*Funcion para desabilitar*/
    public function disable()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'grupo',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion para actualizar actualizar para validar los datos de la vista */
    public function modify()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $group_id = $this->input->post('id');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'nombre_grupo_edit',
                    'label' => '',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, grupo, nombre]", $group_id)
                ),
                array(
                    'field' => 'descripcion_grupo_edit',
                    'label' => '',
                    'rules' => 'trim|alpha_numeric_spaces'
                )

            );


            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
            $this->form_validation->set_message('is_unique_edit', 'Nombre ya existe');


            if ($this->form_validation->run() === TRUE) {
                $data_group = array(
                    'nombre' => strtoupper($this->input->post('nombre_grupo_edit')),
                    'descripcion' => strtoupper($this->input->post('descripcion_grupo_edit'))
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar el modelo
                $this->_update($group_id, $data_group);

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

    public function get_subgroups_list($params = array()){

        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('gr.*')
            ->from('grupo gr')
            ->join('asignacion_grupo asig','gr.id = asig.grupo_hijo_id')
            ->where('gr.estado = 1')
            ->where('asig.grupo_padre_id',$this->input->post('group_id'));
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

    /*Funcion privada para actualizar en la base de datos la modelo*/
    private function _update($id, $data)
    {
        $where = array('id' => $id);
        return $this->db->update('grupo', $data, $where);
    }

    private function _get_group($data)
    {
        $result = $this->db->get_where('grupo',$data);
        if($result->num_rows()>0)
        {
            return $result->row();
        }else{
            return false;
        }

    }

    public function _getgroup_by_id($group_id){
        $this->db->where('id',$group_id);
        return $this->db->get('grupo')->row();
    }

    public function get_subgroups($group_id){
        $this->db->select('gr.*')
            ->from('grupo gr')
            ->join('asignacion_grupo asg','asg.grupo_hijo_id = gr.id')
            ->where('gr.estado', 1)
            ->where('asg.grupo_padre_id', $group_id);
        return $this->db->get()->result();
    }

    public function get_father_group($subgroup_id){
        $this->db->select('asg.*')
            ->from('grupo gr')
            ->join('asignacion_grupo asg','asg.grupo_hijo_id = gr.id')
            ->where('gr.estado', 1)
            ->where('asg.grupo_hijo_id', $subgroup_id);
        return $this->db->get()->row();
    }

    public function get_father_subgroup($subgroup_id){
        $this->db->select('gr.*')
            ->from('grupo gr')
            ->join('asignacion_grupo asg','asg.grupo_padre_id = gr.id')
            ->where('gr.estado', 1)
            ->where('asg.grupo_hijo_id', $subgroup_id);
        return $this->db->get()->result();
    }
    public function get_sub_group_list(){
        $this->db->select('gr.*')
            ->from('grupo gr')
            ->join('asignacion_grupo asg','asg.grupo_hijo_id = gr.id')
            ->where('gr.estado', 1);
        return $this->db->get()->result();
    }

    public function exists_group_name($group_name)
    {
        $this->db->select('*')
            ->from('grupo')
            ->where('nombre', $group_name)
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

    public function get_group_by_name($group_name){
        $this->db->select('*')
            ->from('grupo')
            ->where('nombre', $group_name)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    public function get_group($data_group)
    {
        return $this->db->get_where('grupo', $data_group)->row();
    }

    public function exists_assignment_group($group_id, $subgroup_id)
    {
        $this->db->select('*')
            ->from('asignacion_grupo')
            ->where('grupo_padre_id', $group_id)
            ->where('grupo_hijo_id', $subgroup_id);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            // SI EXISTE
            return 1;
        } else {
            // NO EXISTE
            return 0;
        }
    }
}