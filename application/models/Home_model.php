<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 10/07/2017
 * Time: 01:49 PM
 */
class Home_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
    }

    public function get_count_product_minium_stock()
    {
        return $this->product_model->get_count_product_minium_stock();
    }


}