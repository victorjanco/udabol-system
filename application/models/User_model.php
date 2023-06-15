<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 13/07/2017
 * Time: 03:20 AM
 */
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('office_model');
    }
    public function get_userss()
    {
        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('id > 1');
        $this->db->where('estado',ACTIVO);
        return $this->db->get()->result();
    }
      /*Obtener todas las caja activas especial para cargar combos o autocompletados*/
      public function get_user_enable()
      {
          $this->db->select('*')
              ->from('usuario')
              // ->where('id>1')
              ->where('estado', ACTIVO);
          $this->db->order_by('id', 'ASC');
          return $this->db->get()->result();
      }
    /**
     * Metodo para registro de usuarios
     */
    public function registrer_charge()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $this->form_validation->set_rules('cargo_nombre', 'cargo', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $response['success'] = $this->db->insert('cargo', array('descripcion' => $this->input->post('cargo_nombre'), 'estado' => get_state_abm('ACTIVO')));
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return $response;
    }

    /**
     * Obtener todos los cargos activos
     */
    public function get_charges()
    {
        return $this->db->get_where('cargo', array('estado' => get_state_abm('ACTIVO')))->result();
    }

    /**
     * Obtener los datos de un usuario por su id
     */
    public function get_user_id($id)
    {
        return $this->db->get_where('usuario', array('id' => $id))->row();
    }

    public function verify_key()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'data_user' => array(),
        );
        $key = $this->input->post('key');
        $username = $this->input->post('user');
        $this->db->select('*');
        $this->db->from('usuario ');
        $this->db->where('estado', get_state_abm('ACTIVO'));
        $this->db->where('generated_key', $key);
        $this->db->where('usuario', $username);
        $this->db->where_in('cargo_id',['2','4']);
        $user = $this->db->get();
        if ($user->num_rows() > 0) {
            $response['data_user'] = $user->row()->descuento;
            $response['success'] = true;
        } else {
            $response['messages'] = 'Esta clave no corresponde al usuario o es incorrecta, vuelva a intentar por favor.';
        }
        return $response;
    }

    /**
     * Obtener la lista de usuarios utilizando server side
     */
    public function get_users($start, $length, $search, $column, $order)
    {
        $this->db->start_cache();
        $this->db->select('u.id, u.ci, u.nombre, u.telefono, u.usuario, u.estado, c.descripcion');
        $this->db->from('usuario u, cargo c');
        $this->db->where('u.cargo_id = c.id');
        $this->db->where('u.id <> 1');
        $this->db->stop_cache();
        $count_users = count($this->db->get()->result());
//        $data_users = $this->db->get()->result();
        if (isset($start) && isset($length)) {
            $this->db->limit($length);
            $this->db->offset($start);
        }
        if (isset($column) && isset($order)) {
            $this->db->order_by($column, $order);
        } else {
            $this->db->order_by('u.id', 'ASC');
        }

        if (isset($search)) {
            $this->db->group_start();
            $this->db->like('lower(u.ci)', strtolower($search));
            $this->db->or_like('lower(u.nombre)', strtolower($search));
            $this->db->or_like('lower(u.usuario)', strtolower($search));
            $this->db->group_end();
            $this->db->order_by('u.id', 'DESC');
        }

        $response = $this->db->get();
        $result = array(
            'total_register' => $count_users,
            'data_users' => $response,
        );
        return $result;
    }

    /**
     * Metodo para registrar un nuevo usuario
     * con la seleccion de sus permisos de acceso
     * y con los acceso a las sucursales seleccionadas
     */
    public function registrer_user()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */


            $this->form_validation->set_rules('ci_usuario', '<strong>CI</strong>', 'trim|required');
            $this->form_validation->set_rules('nombre_usuario', '<b>NOMBRE</b>', 'trim|required');
            $this->form_validation->set_rules('telefono', '<b>TELEFONO</b>', 'trim');
            $this->form_validation->set_rules('usuario', '<b>USUARIO</b>', 'trim|required|min_length[4]|max_length[15]|is_unique[usuario.usuario]');
            $this->form_validation->set_rules('clave', '<b>CONTRASEÑA</b>', 'trim|required|min_length[3]|max_length[15]');
//        $this->form_validation->set_rules('seleccion_sucursal', 'Sucursales', 'required');
//        $this->form_validation->set_rules('menu', 'Menu', 'required');

            $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');

            if ($this->form_validation->run() === true) {

                // Verificamos si selecciono sucursales y funciones
                $offices = $this->input->post('seleccion_sucursal');
                $menu = $this->input->post('menu');
                if (isset($offices) || isset($menu)) {
                    $this->db->trans_start();
                    /** OBTENERMOS VALORES DE LOS INPUT **/
                    $obj_user['ci'] = $this->input->post('ci_usuario');
                    $obj_user['nombre'] = mb_strtoupper($this->input->post('nombre_usuario'), "UTF-8");
                    $obj_user['telefono'] = $this->input->post('telefono');
                    $obj_user['usuario'] = $this->input->post('usuario');
                    $obj_user['clave'] = password_hash($this->input->post('clave'), PASSWORD_BCRYPT);
                    $obj_user['estado'] = get_state_abm('ACTIVO');
                    $obj_user['cargo_id'] = $this->input->post('cargo');

                    $this->db->insert('usuario', $obj_user);
                    $id_user =  $this->get_users_data($obj_user)->id;

                    // Registramos las sucursales
                    $menu = $this->input->post('menu');

                    // Ver si el cargo es del admininstrador
                    $this->log_user_permissions($menu, $id_user);
                    $branch_office = $this->office_model->first();
                    // Registramos las sucursales seleccionadas
                    $list_offices = $this->input->post('seleccion_sucursal');
                    // foreach ($list_offices as $row) {
                        // $this->db->insert('usuario_sucursal', array('usuario_id' => $id_user, 'sucursal_id' => $row));
                        $this->db->insert('usuario_sucursal', array('usuario_id' => $id_user, 'sucursal_id' => $branch_office->id));
                    // }
                } else {
                    $response['messages'] = 'error';
                    $this->db->trans_rollback();
                }
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


    public function edit_user()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
            $this->form_validation->set_rules('ci_usuario', 'ci', 'trim|required');
            $this->form_validation->set_rules('nombre_usuario', 'nombre', 'trim|required');
            $this->form_validation->set_rules('telefono', 'telefono', 'trim');

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');

            if ($this->form_validation->run() === true) {

                // Verificamos si selecciono sucursales y funciones
                $offices = $this->input->post('seleccion_sucursal');
                $menu = $this->input->post('menu');
                /*echo json_encode($menu);
                exit();*/
                if (isset($offices) || isset($menu)) {
                    $this->db->trans_start();
                    $id_user = $this->input->post('id_usuario');
                    /** OBTENERMOS VALORES DE LOS INPUT **/
                    $obj_user['ci'] = $this->input->post('ci_usuario');
                    $obj_user['nombre'] = mb_strtoupper($this->input->post('nombre_usuario'), "UTF-8");
                    $obj_user['telefono'] = $this->input->post('telefono');
                    $obj_user['cargo_id'] = $this->input->post('cargo');

                    $this->db->where('id', $id_user);
                    $this->db->update('usuario', $obj_user);

                    // Registramos las sucursales
                    $menu = $this->input->post('menu');

                    $this->db->where('usuario_id', $id_user);/*Borramos las funciones que estan registradas*/
                    $this->db->delete('acceso');
                    $this->log_user_permissions($menu, $id_user);
                    // Registramos las sucursales seleccionadas
                    $list_offices = $this->input->post('seleccion_sucursal');
                    $this->db->where('usuario_id', $id_user);
                    $this->db->delete('usuario_sucursal');
                    foreach ($list_offices as $row) {
                        // Borramos las sucursales registradas a las que el usuario
                        // tiene acceso
                        $this->db->insert('usuario_sucursal', array('usuario_id' => $id_user, 'sucursal_id' => $row));
                    }
                } else {
                    $response['messages'] = 'error';
                    $this->db->trans_rollback();
                }
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

    public function delete_user($id_user)
    {
        $this->db->where('id', $id_user);
        return $this->db->update('usuario', array('estado' => get_state_abm('INACTIVO')));
    }
    
    public function activate_user($id_user)
    {
        $this->db->where('id', $id_user);
        return $this->db->update('usuario', array('estado' => ACTIVO));
    }

    /**
     * Metodo para registrar las funciones padres e hijos seleccionadas
     * con el usuario registrado.
     */
    public function log_user_permissions($function, $id_user)
    {
        $this->db->trans_start();
        foreach ($function as $row) {
            $response = $this->parent_menu_exists($row, $id_user);
            if ($response == false) {
                $parent = $this->get_menu_parent($row);
                $this->db->insert('acceso', array('usuario_id' => $id_user, 'menu_id' => $parent));
            }
            // Aqui insertamos la funcion (hijo)
            $this->db->insert('acceso', array('usuario_id' => $id_user, 'menu_id' => $row));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    /**
     * Verifica si un menu padre ya esta registrado
     *
     * @id_menu     Es el id de un menu hijo
     * @id_usuario  es el id del usuario registrado
     */
    public function parent_menu_exists($id_menu, $id_user)
    {
        $menu_parent = $this->get_menu_parent($id_menu);

        $this->db->select('COUNT(pm.menu_id) AS existe');
        $this->db->from('acceso pm');
        $this->db->where('pm.usuario_id', $id_user);
        $this->db->where('pm.menu_id', $menu_parent);
        $exite_parent = $this->db->get()->row()->existe;
        // Verificamos si el menu padre de la funcion analizada existe,
        // si es cero => no existe
        // si es uno => existe;
        if ($exite_parent == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Devuelve el menu padre
     *
     * @menu_son    Es el id de un menu hijo
     */
    public function get_menu_parent($menu_son)
    {
        $this->db->select('parent');
        $this->db->from('menu');
        $this->db->where('id', $menu_son);
        return $this->db->get()->row()->parent;
    }

    public function get_list_users($type = 'object')
    {
        return $this->db->get_where('usuario', array('estado' => ACTIVO))->result($type);
    }

    public function get_list_users_repairman($type = 'object')
    {

        $this->db->select('u.*');
        $this->db->from('usuario u, usuario_sucursal us');
        $this->db->where('u.estado', ACTIVO);
        $this->db->where('u.cargo_id', 5);
        $this->db->where('us.sucursal_id', get_branch_id_in_session());
        $this->db->where('u.id=us.usuario_id');
        return $this->db->get()->result($type);
    }

    public function get_list_users_admin($type = 'object')
    {

        $this->db->select('u.*');
        $this->db->from('usuario u, usuario_sucursal us');
        $this->db->where('u.estado', ACTIVO);
        $this->db->where('us.sucursal_id', get_branch_id_in_session());
        $this->db->where('u.id=us.usuario_id');
        $this->db->group_start();
        $this->db->where('u.cargo_id', 2);
        $this->db->or_where('u.cargo_id', 6);
        $this->db->group_end();
        return $this->db->get()->result($type);
    }

    public function change_pass_user()
    {
        $user_id = get_user_id_in_session();
        $key_new = $this->input->post('key_new');
        $pass_current = $this->input->post('pass_current');
        $pass_new = $this->input->post('pass_new');
        $where = array('id' => $user_id);

        if ($pass_current != '' && $pass_new != '') {
            if ($this->verify_password($user_id, $pass_current)) {
                $this->db->update('usuario', array('clave' => password_hash($pass_new, PASSWORD_BCRYPT)), $where);
                $response['success'] = TRUE;
                $response['messages'] = 'Se cambio su clave correctamente. ';
            } else {
                $response['success'] = FALSE;
                $response['messages'] = 'Su clave actual que ingreso no coincide con la del sistema vuelva a intentar por favor..';
                $response['clave_actual'] = password_hash($pass_current, PASSWORD_BCRYPT);
            }
        }
        if ($key_new != '') {
            $this->db->update('usuario', array('generated_key' => $key_new), $where);
            $response['success'] = TRUE;
            $response['messages'] = 'Se cambio su clave correctamente. ';
        }
        return $response;

    }

    public function verify_password($user_id, $password_current)
    {
        $this->db->where('id', $user_id);
        $user = $this->db->get('usuario');
        if ($user->num_rows() > 0) {
            if (password_verify($password_current, $user->row()->clave)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get_users_assigned_branch($branch_office_id)
    {
        $this->db->select('u.*');
        $this->db->from('usuario u, usuario_sucursal us');
        $this->db->where('us.usuario_id=u.id');
        $this->db->where('u.estado', ACTIVO);
        $this->db->where('us.sucursal_id', $branch_office_id);
        $this->db->order_by('u.id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_users_reception($branch_office_id)
    {
        $this->db->select('distinct(p.usuario_id) as id, u.usuario');
        $this->db->from('usuario u, pago_recepcion p');
        $this->db->where('p.usuario_id=u.id');
        $this->db->where('p.sucursal_id', $branch_office_id);
        return $this->db->get()->result();
    }
    public function get_users_data($data_users){
        return $this->db->get_where('usuario', $data_users)->row();
    }
}