<?php

/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 26/10/2017
 * Time: 2:06 PM
 */
class Login_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('office_model');
    }

    /* Valida login */
    public function sign_in()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'username',
                'label' => 'username',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'password',
                'label' => 'password',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'branch_office',
                'label' => 'sucursal ',
                'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->office_model->get_offices(), 'id'))
            ),
        );
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<p class="abm-error">', '</p>');


        if ($this->form_validation->run() === TRUE) {
            //true
            $username = trim($this->input->post('username'));
            $password = trim($this->input->post('password'));
            $sucursal_id = trim($this->input->post('branch_office'));
            $data_user = $this->verify_login($username, $password);
            if ($data_user) {
                $user_active = $this->verify_active_session($data_user->id);
                // if ($user_active) {
                //     $branch_office = $this->permission_branch_office($data_user->id, $user_active->sucursal_id);
                // } else {
                    $branch_office = $this->permission_branch_office($data_user->id, $sucursal_id);
                // }
                if ($branch_office) {
                    if ($user_active) {
                        $session_id = $user_active->id;
                        $user_data = array(
                            'id_user' => $data_user->id,
                            'name_user' => $data_user->usuario,
                            'type_user' => $data_user->cargo_id,
                            'id_branch_office' => $branch_office->id,
                            'name_branch_office' => $branch_office->nombre_comercial,
                            'nit_branch_office' => $branch_office->nit,
                            'session_id' => $session_id
                        );
                    } else {
                        $start_session_data = array(
                            'usuario_id' => $data_user->id,
                            'sucursal_id' => $branch_office->id,
                            'fecha_registro' => date('Y-m-d H:i:s'),
                            'estado' => ACTIVO
                        );
                        $this->db->insert('inicio_sesion', $start_session_data);
                        if ($this->db->trans_status() === TRUE) {
                            $this->db->trans_commit();
                            $session_inserted = $this->db->get_where('inicio_sesion', $start_session_data)->row();
                            $session_id = $session_inserted->id;
                            $user_data = array(
                                'id_user' => $data_user->id,
                                'name_user' => $data_user->usuario,
                                'type_user' => $data_user->cargo_id,
                                'id_branch_office' => $branch_office->id,
                                'name_branch_office' => $branch_office->nombre_comercial,
                                'nit_branch_office' => $branch_office->nit,
                                'session_id' => $session_id
                            );
                        } else {
                            $this->db->trans_rollback();
                            redirect(base_url() . 'login');
                        }


                    }
                    $session_data = array(
                        'logged' => true,
                        'user' => $user_data,
                        'dosage' => array('id_printer'=>'')
                    );
                    $menu = $this->cargar_menu($data_user->id);
                    $this->session->set_userdata('menu', $menu);
                    $this->session->set_userdata($session_data);
                    redirect(base_url() . 'home');
                } else {
                    $this->session->set_flashdata('branch_office', 'Usuario invalido para la sucursal seleccionada.');
                    $this->session->set_flashdata('acount_login', $username);
                    $this->session->set_flashdata('pass_login', $password);
                    redirect(base_url() . 'login');
                }

            } else {
                $this->session->set_flashdata('error', 'Datos incorrectos, por favor verifique sus datos');
                $this->session->set_flashdata('acount_login', $username);
                redirect(base_url() . 'login');
            }
        } else {
            $this->index();
        }
    }

    public function sign_in_session()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'username_session',
                'label' => 'username',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'password_session',
                'label' => 'password',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'branch_office_session',
                'label' => 'sucursal ',
                'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->office_model->get_offices(), 'id'))
            ),
        );
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<p class="abm-error">', '</p>');


        if ($this->form_validation->run() === TRUE) {
            $username = trim($this->input->post('username_session'));
            $password = trim($this->input->post('password_session'));
            $sucursal_id = trim($this->input->post('branch_office_session'));
            $data_user = $this->verify_login($username, $password);
            if ($data_user) {
                $user_active = $this->verify_active_session($data_user->id);
                if ($user_active) {
                    $branch_office = $this->permission_branch_office($data_user->id, $user_active->sucursal_id);
                } else {
                    $branch_office = $this->permission_branch_office($data_user->id, $sucursal_id);
                }
                if ($branch_office) {
                    if ($user_active) {
                        $session_id = $user_active->id;
                        $user_data = array(
                            'id_user' => $data_user->id,
                            'name_user' => $data_user->usuario,
                            'type_user' => $data_user->cargo_id,
                            'id_branch_office' => $branch_office->id,
                            'name_branch_office' => $branch_office->nombre_comercial,
                            'nit_branch_office' => $branch_office->nit,
                            'session_id' => $session_id
                        );
                    } else {
                        $start_session_data = array(
                            'usuario_id' => $data_user->id,
                            'sucursal_id' => $branch_office->id,
                            'fecha_registro' => date('Y-m-d H:i:s'),
                            'estado' => ACTIVO
                        );
                        $this->db->insert('inicio_sesion', $start_session_data);
                        if ($this->db->trans_status() === TRUE) {
                            $this->db->trans_commit();
                            
                            $session_inserted = $this->_get_start_session($start_session_data);
				            $session_id = $session_inserted->id;

                            $user_data = array(
                                'id_user' => $data_user->id,
                                'name_user' => $data_user->usuario,
                                'type_user' => $data_user->cargo_id,
                                'id_branch_office' => $branch_office->id,
                                'name_branch_office' => $branch_office->nombre_comercial,
                                'nit_branch_office' => $branch_office->nit,
                                'session_id' => $session_id
                            );
                        } else {
                            $this->db->trans_rollback();
                        }


                    }
                    $session_data = array(
                        'logged' => true,
                        'user' => $user_data
                    );
                    $menu = $this->cargar_menu($data_user->id);
                    $this->session->set_userdata('menu', $menu);
                    $this->session->set_userdata($session_data);
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                }

            } else {
                $response['success'] = false;
            }
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }


    function verify_login($username, $password)
    {
        $this->db->where('usuario', $username);
        $this->db->where('estado', ACTIVO);
        $user = $this->db->get('usuario');
        if ($user->num_rows() > 0) {
            if (password_verify($password, $user->row()->clave)) {
                return $user->row();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function verify_active_session($user_id)
    {
        $this->db->where('usuario_id', $user_id);
        $this->db->where('estado', ACTIVO);
        $user = $this->db->get('inicio_sesion');
        if ($user->num_rows() > 0) {
            return $user->row();
        } else {
            return false;
        }
    }

    public function sign_out()
    {
        $sesion = $this->session->userdata('user');
        if (isset($sesion)) {
            $sesion_id=get_session_id();
            $this->db->update('inicio_sesion', ['estado' => 0], ['id' => $sesion_id]);
        }
        session_destroy();
        $this->session->sess_destroy();
        redirect(site_url('login'));
    }

    /*************************************** PRIVATE FUNCTIONS ********************************************************/
    /* cargado del menu de privilegios  */
    private function cargar_menu($usuario_id)
    {
        $sql_menu = "SELECT m.* 
                FROM menu m, acceso a, usuario u 
                WHERE m.id = a.menu_id AND 
                      a.usuario_id = u.id   AND
                      u.id = ? ORDER BY m.id";
        //return $this->db->get('menu')->result_array();
        return $this->db->query($sql_menu, array($usuario_id))->result_array();
    }

    /* verificar usuario  sucursal*/
    private function permission_branch_office($usuario_id, $sucursal_id)
    {
        $this->db->select('suc.*')
            ->from('sucursal as suc')
            ->join('usuario_sucursal usuc', 'usuc.sucursal_id = suc.id')
            ->join('usuario as usr', 'usr.id = usuc.usuario_id')
            ->where('suc.id', $sucursal_id)
            ->where('usr.id', $usuario_id);
        $branch_office = $this->db->get();

        if ($branch_office->num_rows() > 0) {
            return $branch_office->row();
        } else {
            return false;
        }
    }

    public function _get_start_session($obj_session)
	{
		return $this->db->get_where('inicio_sesion', $obj_session)->row();
	}
}
