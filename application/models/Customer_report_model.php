<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 10/8/2017
 * Time: 5:46 PM
 */
class Customer_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('reception_model');
//        $this->load->model('order_work');

    }

    public function login_validation()
    {
        $user = $this->input->post('username');
        $password = $this->input->post('password');
        $customer = $this->customer_model->verify_customer($user, $password);
        return $customer;
    }
}