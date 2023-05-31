<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 10/07/2017
 * Time: 01:45 PM
 */
class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('dosage_model');
        $this->load->model('credit_payment_model');
       /// $this->load->model('order_work_model');
    }

    public function index()
    {
        $data['minimum_stock'] = $this->home_model->get_count_product_minium_stock();
        //$data['peritar'] = $this->order_work_model->get_assess();
        //$data['concluido'] = $this->order_work_model->get_concluded();
       // $data['proceso'] = $this->order_work_model->get_process();
        $data['activas'] = $this->dosage_model->get_dosage_active();

        $data['caducadas'] = $this->dosage_model->get_dosage_expired();
        $data['inactivas'] = $this->dosage_model->get_dosage_disable();

        $data['ventas_credito_vencidas'] = $this->credit_payment_model->get_credit_sale_expired();
        $data['ventas_credito_por_expirar'] = $this->credit_payment_model->get_credit_sale_for_expired();

        if (get_number_printer_by_branch_office_id(get_branch_id_in_session())>0){
			$data['list_printer'] = $this->dosage_model->get_printer_by_branch_office_id(get_branch_id_in_session());
		}

        // echo json_encode($data);
        template('home/index', $data);
    }
}
