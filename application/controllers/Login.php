<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 14/07/2017
 * Time: 12:44 AM
 */
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('office_model');
    }

    public function index()
    {
        session_destroy();
        $data['branch_offices'] = $this->office_model->get_offices();
        $this->load->view('login/header', $data);
    }

    public function sign_in()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->login_model->sign_in_session();
        } else {
            echo $this->login_model->sign_in();
        }
    }

    public function sign_out()
    {
        $this->login_model->sign_out();
    }

	public function sesion_current()
	{
		echo json_encode($_SESSION);
	}

    public function kill_session(){
        /*$sesion = $this->session->userdata('user');*/
        session_destroy();
        /*$this->session->sess_destroy();*/
    }
}
