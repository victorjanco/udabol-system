<?php

class Color extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('color_model');
	}

	public function index()
	{
		template('color/index', array(), 'color');
	}

	public function get_color_list()
	{
		if ($this->input->is_ajax_request()) {

			$start = $this->input->post('start');
			$limit = $this->input->post('length');
			$search = $this->input->post('search')['value'];
			$order = $this->input->post('order')['0']['dir'];
			$column_num = $this->input->post('order')['0']['column'];
			$column = $this->input->post('columns')[$column_num]['data'];

			$params = array(
					'start' => $start,
					'limit' => $limit,
					'search' => $search,
					'column' => $column,
					'order' => $order
			);

			echo json_encode($this->color_model->get_color_list($params));
		} else {
			show_404();
		}
	}

	public function register_color()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->color_model->register_color();
		} else {
			show_404();
		}
	}

	public function modify_color()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->color_model->modify_color();
		} else {
			show_404();
		}
	}

	public function disable_color()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->color_model->disable_color();
		} else {
			show_404();
		}
	}
}
