<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 22/08/2017
 * Time: 06:54 PM
 */
class Order_work extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reception_model');
        $this->load->model('order_work_model');
        $this->load->model('service_model');
        $this->load->model('brand_model');
        $this->load->model('user_model');
        $this->load->model('warehouse_model');
        $this->load->model('reception_payment_model');
    }


    //region Vistas
    public function index()
    {
        template('order/index');
    }

    public function service()
    {
        template('order/index_order_service');
    }

    public function order_work_detail()
    {
        $reception_id = $this->input->post('id');
        if ($reception_id == null) {
            template('order/index');
            return;
        }
        $data = array(
            "reception" => $this->reception_model->get_only_recepetion_by_id($reception_id),
            "order_work" => $this->order_work_model->get_order_by_reception_id($reception_id),
            //"detail_service" => $this->reception_model->get_reception_detail_service($reception_id),
            "detail_service" => $this->order_work_model->get_detail_service_by_order_work_id($this->order_work_model->get_order_by_reception_id($reception_id)->id),
            "services" => $this->service_model->get_service_enable(),
			"list_warehouse" => $this->warehouse_model->get_warehouse_enable(),
            "list_brand" => $this->brand_model->get_brand_enable(),
            "list_users" => $this->user_model->get_list_users_repairman(),
            "detail_product" => $this->order_work_model->get_detail_product_by_reception_id($reception_id)
        );

        template('order/execute_order_work', $data);
    }

    public function add_spare()
    {
        $reception_id = $this->input->post('id');
        if ($reception_id == null) {
            template('order/index');
            return;
        }
        $data = array(
            "reception" => $this->reception_model->get_only_recepetion_by_id($reception_id),
            "order_work" => $this->order_work_model->get_order_by_reception_id($reception_id),
            //"detail_service" => $this->reception_model->get_reception_detail_service($reception_id),
            "detail_service" => $this->order_work_model->get_detail_service_by_order_work_id($this->order_work_model->get_order_by_reception_id($reception_id)->id),
            "services" => $this->service_model->get_service_enable(),
            "list_warehouse" => $this->warehouse_model->get_warehouse_enable(),
            "list_brand" => $this->brand_model->get_brand_enable(),
            "list_users" => $this->user_model->get_list_users_repairman(),
            "detail_product" => $this->order_work_model->get_detail_product_by_reception_id($reception_id),
			"reception_payments" => $this->reception_payment_model->get_sum_reception_payments($reception_id)
        );

        template('order/register_spare', $data);
    }
    //endregion


    //region Metodos publicos
    public function register()
    {
        try {
            if ($this->input->is_ajax_request()) {
                echo json_encode($this->order_work_model->register());
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function register_spare()
    {
        try {
            if ($this->input->is_ajax_request()) {
                echo json_encode($this->order_work_model->register_spare());
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }


    function update_table_order_work_for_query()
    {
        $reception_id = 1;
        $query = "UPDATE orden_trabajo SET codigo_trabajo= 'O.T.'||recepcion.codigo_recepcion
                  FROM recepcion
                  WHERE orden_trabajo.recepcion_id=recepcion.id
                        recepcion.id=$reception_id";
        echo $query;
    }

    public function get_order_list()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order
            );
            echo json_encode($this->order_work_model->get_order_work_list($params));
        } else {
            show_404();
        }
    }

    public function get_order_list_peritaje()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            /*Parametros de busqueda sin datatable*/
            $days = $this->input->post('filter_date');
            $date_start_reception = $this->input->post('filter_date_start_reception');
            $date_end_reception = $this->input->post('filter_date_end_reception');
            $reception_code = $this->input->post('filter_reception_code');

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'days' => $days,
                'date_start_reception' => $date_start_reception,
                'date_end_reception' => $date_end_reception,
                'reception_code' => $reception_code
            );
            $data = $this->order_work_model->get_order_list_peritaje($params);
            $result = $data['data_list'];
            $total_data = $data['total_register'];


            $data = array();
            foreach ($result->result_array() as $row) {
                $array = array();
                $array['id'] = $row['id'];
                $array['codigo_trabajo'] = $row['codigo_trabajo'];
                $array['fecha_registro'] = $row['fecha_registro'];
                $array['fecha_registro'] = $row['fecha_registro'];
                $array['nombre_sucursal'] = $row['nombre_sucursal'];
                $array['nombre_cliente'] = $row['nombre_cliente'];
                $array['nombre_modelo'] = $row['nombre_modelo'];
                $array['nombre_marca'] = $row['nombre_marca'];
                $array['imei'] = $row['imei'];
                $array['estado_trabajo'] = get_work_order_states($row['estado_trabajo']);
                $data[] = $array;
            }
            $count_data = $result->num_rows();
            $respone = array(
                'draw' => intval($this->input->post('draw')),
                'recordsTotal' => intval($count_data),
                'recordsFiltered' => intval($total_data),
                'data' => $data,
            );

            echo json_encode($respone);
        } else {
            show_404();
        }
    }

    public function get_order_service_list()/* Listdo del modulo  "Por peritar"*/
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];


            /*Parametros de busqueda sin datatable*/
            $days = $this->input->post('filter_date');
            $date_start_reception = $this->input->post('filter_date_start_reception');
            $date_end_reception = $this->input->post('filter_date_end_reception');
            $reception_code = $this->input->post('filter_reception_code');

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'days' => $days,
                'date_start_reception' => $date_start_reception,
                'date_end_reception' => $date_end_reception,
                'reception_code' => $reception_code
            );
            $data = $this->order_work_model->get_order_service_list($params);
            $result = $data['data_list'];
            $total_data = $data['total_register'];

            $data = array();
            foreach ($result->result_array() as $row) {
                $array = array();
                $array['id'] = $row['id'];
                $array['codigo_trabajo'] = $row['codigo_trabajo'];
                $array['fecha_registro'] = $row['fecha_registro'];
                $array['fecha_registro'] = $row['fecha_registro'];
                $array['nombre_sucursal'] = $row['nombre_sucursal'];
                $array['nombre_cliente'] = $row['nombre_cliente'];
                $array['nombre_marca'] = $row['nombre_marca'];
                $array['nombre_modelo'] = $row['nombre_modelo'];
                $array['imei'] = $row['imei'];
                $array['estado_trabajo'] = get_work_order_states($row['estado_trabajo']);

                if ($row['perito_usuario_id'] != null) {
                    $array['perito_usuario'] = $this->user_model->get_user_id($row['perito_usuario_id'])->usuario;
                } else {
                    $array['perito_usuario'] = 'Usuario sin peritar';
                }

                $data[] = $array;
            }
            $count_data = $result->num_rows();
            $respone = array(
                'draw' => intval($this->input->post('draw')),
                'recordsTotal' => intval($count_data),
                'recordsFiltered' => intval($total_data),
                'data' => $data,
            );
            echo json_encode($respone);
        } else {
            show_404();
        }
    }

    public function add_row_service()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->order_work_model->add_row_service());
        } else {
            show_404();
        }
    }

    public function add_row_product()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->order_work_model->add_row_product());
        } else {
            show_404();
        }
    }

    public function add_row_product_recondition()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->order_work_model->add_row_product_recondition());
        } else {
            show_404();
        }
    }

    public function update_state_order()
    {
        if ($this->input->is_ajax_request()) {
            $reception_id = $this->input->post('reception_id');
            $state_reception = $this->input->post('state_reception');
            echo json_encode($this->order_work_model->update_state_order($reception_id, $state_reception));
        } else {
            show_404();
        }
    }

    public function update_order_work()
    {
        $order_work_list = $this->order_work_model->get_order_work_all();
        foreach ($order_work_list as $order_work_list_update) {
            $order_work_id = $order_work_list_update->id;
            $order_work = $this->order_work_model->get_order_work_id($order_work_id);
            $reception_id = $order_work->recepcion_id;

            $total = 0;

            $detail_product_order_work_list = $this->order_work_model->get_detail_product_by_order_work_id($order_work_id);
            foreach ($detail_product_order_work_list as $detail_order_work) {
                $quantity = $detail_order_work->cantidad;
                $price = $detail_order_work->precio_venta;
                $total = $total + ($quantity * $price);

            }

            $detail_service_order_work_list = $this->order_work_model->get_detail_service_by_order_work_id($order_work_id);
            foreach ($detail_service_order_work_list as $detail_order_work) {
                $quantity = 1;
                $price = $detail_order_work->precio_servicio;
                $total = $total + ($quantity * $price);

            }

            $this->db->where('id', $order_work_id);
            $data_order['monto_subtotal'] = $total;
            $data_order['monto_total'] = $total;
            $this->db->update('orden_trabajo', $data_order);

            $this->db->where('id', $reception_id);
            $data_recepcion['monto_total'] = $total;
            $this->db->update('recepcion', $data_recepcion);
        }
    }

    public function update_state_order_not_solution()
    {
        if ($this->input->is_ajax_request()) {
            $reception_id = $this->input->post('reception_id');
            $state_reception = $this->input->post('state_reception');
            $response = $this->order_work_model->update_state_order($reception_id, $state_reception);
            if ($response['success'] === TRUE) {
                $observation = trim(text_format($this->input->post('observation')));
                $this->order_work_model->register_not_solution_service($reception_id, $observation);
            }
            echo json_encode($response);

        } else {
            show_404();
        }
    }

    public function update_percentage_order_work()
    {

        $result = $this->order_work_model->get_order_work_all();
        $this->db->trans_begin();

        foreach ($result as $row) {
            if ($row->estado_trabajo == REPARADO || $row->estado_trabajo == APROBADO || $row->estado_trabajo == EN_MORA
                || $row->estado_trabajo == ESPERA_STOCK || $row->estado_trabajo == ENTREGADO_ESPERA_STOCK) {
                $this->db->set('progreso', 50);
                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');
            }

            if ($row->estado_trabajo == EN_PROCESO) {
                $this->db->set('progreso', 80);
                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');
            }

            if ($row->estado_trabajo == CONCLUIDO || $row->estado_trabajo == ENTREGADO || $row->estado_trabajo == NO_APROBADO
                || $row->progreso == SIN_SOLUCION) {
                $this->db->set('progreso', 100);
                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            echo "Datos Registrados Correstamente ya Puede trabajar con el Sistema The Best";
        }

    }

    public function order_work_update_dates()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $this->db->trans_begin();
        $this->db->order_by('id','ASC');
        $order_work = $this->db->get('orden_trabajo');
        foreach ($order_work->result() as $row) {
            $concluid = $this->get_concluid_date($row->id, $row->recepcion_id);
            $proforma = $this->get_approved_date($row->id, $row->recepcion_id);
            $delivered = $this->get_delivered_date($row->id, $row->recepcion_id);
            $diagnosed = $this->get_diagnosed_date($row->id, $row->recepcion_id);
            $create = $this->get_create_date($row->id, $row->recepcion_id);



            if (intval($row->estado_trabajo) == ENTREGADO) {
                echo 'ENTREGADO ORDEN= '.$row->id;
                $this->db->set('fecha_entrega', $delivered['fecha']);
                $this->db->set('entrega_usuario_id', $delivered['usuario']);

                $this->db->set('fecha_concluido', $concluid['fecha']);
                $this->db->set('concluido_usuario_id', $concluid['usuario']);

                $this->db->set('fecha_proforma', $proforma['fecha']);
                $this->db->set('proforma_usuario_id', $proforma['usuario']);

                $this->db->set('fecha_perito', $diagnosed['fecha']);
                $this->db->set('fecha_asignado', $diagnosed['fecha']);
                $this->db->set('perito_usuario_id', $diagnosed['usuario']);

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->recepcion_id);
                $this->db->update('recepcion');
            } elseif (intval($row->estado_trabajo) === CONCLUIDO) {
                echo 'CONCLUIDO ORDEN= '.$row->id;
                $this->db->set('fecha_concluido', $concluid['fecha']);
                $this->db->set('concluido_usuario_id', $concluid['usuario']);

                $this->db->set('fecha_proforma', $proforma['fecha']);
                $this->db->set('proforma_usuario_id', $proforma['usuario']);

                $this->db->set('fecha_perito', $diagnosed['fecha']);
                $this->db->set('fecha_asignado', $diagnosed['fecha']);
                $this->db->set('perito_usuario_id', $diagnosed['usuario']);

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->recepcion_id);
                $this->db->update('recepcion');
            } elseif (intval($row->estado_trabajo) === ESPERA_STOCK) {
                /*cuando entra a esperda de stock es porque lo diagnostico*/
                echo 'ESPERA_STOCK ORDEN= '.$row->id;
                $this->db->set('fecha_proforma', $proforma['fecha']);
                $this->db->set('proforma_usuario_id', $proforma['usuario']);

                $this->db->set('fecha_perito', $diagnosed['fecha']);
                $this->db->set('fecha_asignado', $diagnosed['fecha']);
                $this->db->set('perito_usuario_id', $diagnosed['usuario']);
                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->recepcion_id);
                $this->db->update('recepcion');


            } elseif (intval($row->estado_trabajo) === ENTREGADO_ESPERA_STOCK) {
                /*cuando entra a esperda de stock es porque lo diagnostico*/
                echo 'ENTREGADO_ESPERA_STOCK ORDEN= '.$row->id;
                $this->db->set('fecha_proforma', $proforma['fecha']);
                $this->db->set('proforma_usuario_id', $proforma['usuario']);

                $this->db->set('fecha_perito', $diagnosed['fecha']);
                $this->db->set('fecha_asignado', $diagnosed['fecha']);
                $this->db->set('perito_usuario_id', $diagnosed['usuario']);
                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->recepcion_id);
                $this->db->update('recepcion');
            } elseif (intval($row->estado_trabajo) === NO_APROBADO) {
                echo 'NO_APROBADO ORDEN= '.$row->id;
                /*Estos son cuando ya se diagnostico pero no lo aprobo*/
                $this->db->set('fecha_proforma', $proforma['fecha']);
                $this->db->set('proforma_usuario_id', $proforma['usuario']);

                $this->db->set('fecha_perito', $diagnosed['fecha']);
                $this->db->set('fecha_asignado', $diagnosed['fecha']);
                $this->db->set('perito_usuario_id', $diagnosed['usuario']);
                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->recepcion_id);
                $this->db->update('recepcion');
            } elseif (intval($row->estado_trabajo) === APROBADO) {
                echo 'APROBADO ORDEN= '.$row->id;
                $this->db->set('fecha_proforma', $proforma['fecha']);
                $this->db->set('proforma_usuario_id', $proforma['usuario']);

                $this->db->set('fecha_perito', $diagnosed['fecha']);
                $this->db->set('fecha_asignado', $diagnosed['fecha']);
                $this->db->set('perito_usuario_id', $diagnosed['usuario']);
                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->recepcion_id);
                $this->db->update('recepcion');
            } elseif (intval($row->estado_trabajo) === SIN_SOLUCION) {
                echo 'SIN SOLUCION ORDEN= '.$row->id;
                $this->db->set('fecha_proforma', $proforma['fecha']);
                $this->db->set('proforma_usuario_id', $proforma['usuario']);

                $this->db->set('fecha_perito', $diagnosed['fecha']);
                $this->db->set('fecha_asignado', $diagnosed['fecha']);
                $this->db->set('perito_usuario_id', $diagnosed['usuario']);
                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->recepcion_id);
                $this->db->update('recepcion');

            } elseif (intval($row->estado_trabajo) === REPARADO) {
                echo 'REPARADO ORDEN= '.$row->id;
                $this->db->set('fecha_perito', $diagnosed['fecha']);
                $this->db->set('fecha_asignado', $diagnosed['fecha']);
                $this->db->set('perito_usuario_id', $diagnosed['usuario']);
                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->recepcion_id);
                $this->db->update('recepcion');
            } else {
                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->id);
                $this->db->update('orden_trabajo');

                $this->db->set('fecha_registro', $create['fecha']);
                $this->db->set('usuario_id', $create['usuario']);

                $this->db->where('id', $row->recepcion_id);
                $this->db->update('recepcion');
            }

        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            echo "Correctamente ejecuto";
        }

    }

    public function get_delivered_date($order_work_id, $reception_id)
    {
        if ($this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A ENTREGADO') != null) {
            $data_date = $this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A ENTREGADO');
            $data['fecha'] = $data_date->fecha_registro;
            $data['usuario'] = $data_date->usuario_id;
        } elseif ($this->get_order_by_reception($reception_id)->fecha_entrega != null) {
            $data_date = $this->get_order_by_reception($reception_id);
            $data['fecha'] = $data_date->fecha_entrega;
            $data['usuario'] = $data_date->usuario_id;
        } elseif ($this->get_order_work($order_work_id) != null) {
            $data_date = $this->get_order_work($order_work_id);
            $data['fecha'] = $data_date->fecha_modificacion;
            $data['usuario'] = $data_date->usuario_id;
        } else {
            $data['fecha'] = null;
            $data['usuario'] = null;
        }
        return $data;
    }

    public function get_concluid_date($order_work_id,$reception_id)
    {
        if ($this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A CONCLUIDO') != null) {

            $data_date = $this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A CONCLUIDO');
            $data['fecha'] = $data_date->fecha_registro;
            $data['usuario'] = $data_date->usuario_id;

        }elseif ($this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A APROBADO') != null) {

            $data_date = $this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A APROBADO');
            $data['fecha'] = $data_date->fecha_registro;
            $data['usuario'] = $data_date->usuario_id;

        }elseif ($this->get_order_by_reception($reception_id)->fecha_entrega != null) {
            $data_date = $this->get_order_by_reception($reception_id);
            $data['fecha'] = $data_date->fecha_entrega;
            $data['usuario'] = $data_date->usuario_id;
        } elseif ($this->get_order_work($order_work_id) != null) {
            $data_date = $this->get_order_work($order_work_id);
            $data['fecha'] = $data_date->fecha_modificacion;
            $data['usuario'] = $data_date->usuario_id;
        } else {
            $data['fecha'] = null;
            $data['usuario'] = null;
        }
        return $data;
    }

    public function get_approved_date($order_work_id,$reception_id)
    {
        if ($this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A REPARADO CON PROFORMA') != null) {

            $data_date = $this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A REPARADO CON PROFORMA');
            $data['fecha'] = $data_date->fecha_registro;
            $data['usuario'] = $data_date->usuario_id;

        }elseif ($this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A APROBADO') != null) {

            $data_date = $this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A APROBADO');
            $data['fecha'] = $data_date->fecha_registro;
            $data['usuario'] = $data_date->usuario_id;

        }  elseif ($this->get_order_by_reception($reception_id)->fecha_entrega != null) {
            $data_date = $this->get_order_by_reception($reception_id);
            $data['fecha'] = $data_date->fecha_entrega;
            $data['usuario'] = $data_date->usuario_id;
        }elseif ($this->get_order_work($order_work_id) != null) {
            $data_date = $this->get_order_work($order_work_id);
            $data['fecha'] = $data_date->fecha_modificacion;
            $data['usuario'] = $data_date->usuario_id;
        } else {
            $data['fecha'] = null;
            $data['usuario'] = null;
        }
        return $data;
    }

    public function get_diagnosed_date($order_work_id,$reception_id)
    {
        if ($this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A REPARADO') != null) {

            $data_date = $this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A REPARADO');
            $data['fecha'] = $data_date->fecha_registro;
            $data['usuario'] = $data_date->usuario_id;

        } elseif ($this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A APROBADO') != null) {

            $data_date = $this->get_order_by_history($order_work_id, 'PROCESO CAMBIADO A APROBADO');
            $data['fecha'] = $data_date->fecha_registro;
            $data['usuario'] = $data_date->usuario_id;

        }  elseif ($this->get_order_by_reception($reception_id)->fecha_entrega != null) {
            $data_date = $this->get_order_by_reception($reception_id);
            $data['fecha'] = $data_date->fecha_entrega;
            $data['usuario'] = $data_date->usuario_id;
        } elseif ($this->get_order_work($order_work_id) != null) {
            $data_date = $this->get_order_work($order_work_id);
            $data['fecha'] = $data_date->fecha_modificacion;
            $data['usuario'] = $data_date->usuario_id;
        } else {
            $data['fecha'] = null;
            $data['usuario'] = null;
        }
        return $data;
    }

    public function get_create_date($order_work_id,$reception_id)
    {
        if ($this->get_order_by_history($order_work_id, 'CREACION DE LA RECEPCION') != null) {

            $data_date = $this->get_order_by_history($order_work_id, 'CREACION DE LA RECEPCION');
            $data['fecha'] = $data_date->fecha_registro;
            $data['usuario'] = $data_date->usuario_id;

        }  elseif ($this->get_order_by_reception($reception_id)->fecha_registro != null) {
            $data_date = $this->get_order_by_reception($reception_id);
            $data['fecha'] = $data_date->fecha_registro;
            $data['usuario'] = $data_date->usuario_id;
        } else {
            $data['fecha'] = null;
            $data['usuario'] = null;
        }
        return $data;
    }

    public function get_order_by_history($order_work_id, $description)
    {
        $this->db->select('*')
            ->from('historial_orden_trabajo')
            ->where('descripcion', $description)
            ->where('orden_trabajo_id', $order_work_id)
            ->order_by('id', 'DESC')
            ->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            return null;
        }

    }

    public function get_order_work_by_state($order_work_id, $state_work)
    {
        return $this->db->get_where('orden_trabajo', array('id' => $order_work_id, 'estado_trabajo' => $state_work))->row();
    }

    public function get_order_work($order_work_id)
    {
        return $this->db->get_where('orden_trabajo', array('id' => $order_work_id))->row();
    }

    public function get_order_by_reception($reception_id)
    {
        return $this->db->get_where('recepcion', array('id' => $reception_id))->row();
    }


}
